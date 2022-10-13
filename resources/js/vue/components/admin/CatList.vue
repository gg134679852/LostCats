<template>
  <div class="text-star d-flex justify-content-between mt-3">
    <div class="dropdown">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
        aria-expanded="false">
        資料選項
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="#" @click.prevent="componentSwitcher('CatList')">貓咪清單</a></li>
        <li><a class="dropdown-item" href="#" @click.prevent="componentSwitcher('ShelterList')">收容所清單</a></li>
        <li><a class="dropdown-item" href="#" @click.prevent="componentSwitcher('DonateLog')">捐獻紀錄</a></li>
      </ul>
    </div>
    <div class="button-warp">
      <button type="button" class="btn btn-secondary mx-3" @click.prevent="switcher('none','createNewCatData')">新增資料</button>
      <button type="button" class="btn btn-secondary" @click.prevent="filterSwitcher"><i
          class="fas fa-filter"></i>過濾器</button>
    </div>
  </div>
  <div class="text-end mt-3">
    <table class="table mt-4">
      <thead>
        <tr>
          <th width="120">編號</th>
          <th width="120">性別</th>
          <th width="120">毛色</th>
          <th width="120">所在地區</th>
          <th width="100">是否有照片</th>
          <th width="100">是否絕育</th>
          <th width="200">編輯</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for=" item in catData.data" :key="item.animal_id">
          <td>{{ item.animal_id }}</td>
          <td class="text-right">
            {{ item.animal_sex }}
          </td>
          <td class="text-right">
            {{ item.animal_color }}
          </td>
          <td>
            {{ item.shelter.shelter_city}}
          </td>
          <td>{{ item.album_file !== null ? '有照片':'沒照片' }}</td>
          <td>{{ item.animal_sterilization }}</td>
          <td>
            <div class="btn-group">
              <button class="btn btn-outline-primary btn-sm"
                @click.prevent="switcher(item.animal_id,'updateCatData')">編輯</button>
              <button class="btn btn-outline-danger btn-sm"
                @click.prevent="switcher(item.animal_id,'deleteCatData')">刪除</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <PaginationComponent
    :pagination-links="catData.paginationLinks"
    :screen-size="screenSize"
    @fetch-animal-data="fetchAnimalData" />
  <CatInfoModal
    :cat-info-modal-switcher="catInfoModalSwitcher"
    :cat-info-data="catInfoData"
    :modal-type="modalType"
    :shelter-option="shelterOption"
    @form-value-enter="formValueEnter"
    @switcher="switcher"
    @upload-image="uploadImage"
    @upload-new-cat-data="uploadNewCatData"
    @updateCatData="updateCatData"
    @shelter-filter="shelterFilter"
    />
  <DelModal
    :del-modal-switcher="delModalSwitcher"
    :animal-id="catInfoData.animal_id"
    :del-modal-type="delModalType"
    @delete-cat-data="deleteCatData"
    @switcher="switcher"
  />
  <FilterModal
    :filter-modal-switcher="filterModalSwitcher"
    :shelter-data="shelterOption"
    :color="catData.color"
    :screen-size="screenSize"
    @filter-switcher="filterSwitcher"
    @shelter-filter="shelterFilter"
    @fetch-animal-data="fetchAnimalData"
    />
</template>
<script>
import CatInfoModal from './CatInfoModal.vue'
import PaginationComponent from '../PaginationComponent.vue'
import DelModal from '../../components/admin/DelModal.vue'
import FilterModal from '../FilterModal.vue'
export default {
  props: {
    catData: {
      type: Object,
      require: true
    },
    shelterData: {
      type: Array,
      require: true
    },
    screenSize: {
      type: String,
      require: true
    },
    currentComponent: {
      type: String,
      require: true
    }
  },
  emits: ['componentSwitcher', 'fetchAnimalData', 'loadingSwitcher'],
  components: {
    CatInfoModal,
    PaginationComponent,
    DelModal,
    FilterModal
  },
  data () {
    return ({
      catInfoData: {
        album_file: '',
        animal_age: '',
        animal_bacterin: '',
        animal_bodytype: '',
        animal_color: '',
        animal_foundplace: '',
        animal_id: 0,
        animal_remark: '',
        animal_sex: '',
        animal_sterilization: '',
        shelter_id: '0',
        shelter_city: '0'
      },
      inputName: {
        shelter_id: '收容所',
        animal_foundplace: '發現地點',
        animal_bacterin: '是否已施打狂犬病疫苗',
        animal_sterilization: '是否已絕育',
        animal_bodytype: '體型',
        animal_color: '顏色',
        animal_age: '年紀',
        animal_sex: '性別',
        animal_id: '編號'
      },
      shelterOption: [],
      catInfoModalSwitcher: 'hide',
      delModalSwitcher: 'hide',
      filterModalSwitcher: 'hide',
      modalType: '',
      delModalType: 'CatList'
    })
  },
  inject: ['Toast'],
  methods: {
    fetchAnimalData (type, value) {
      switch (type) {
        case 'pageClick': {
          this.$emit('fetchAnimalData', 'pageClick', value)
          break
        }
        case 'filterData': {
          this.$emit('fetchAnimalData', 'filterData', value)
          break
        }
      }
    },
    formValueEnter (key, value) {
      this.catInfoData[key] = value
    },
    uploadImage (value) {
      if (value) {
        this.$emit('loadingSwitcher')
        const responseData = new FormData()
        responseData.append('image', value)
        this.$axiosHelper.post('admin/animalData/uploadImage', responseData, {
          headers: { Accept: 'application/json' }
        })
          .then((obj) => {
            this.Toast.fire({
              icon: obj.data.icon,
              title: obj.data.message
            })
            this.catInfoData.album_file = obj.data.image
            this.$emit('loadingSwitcher')
          })
          .catch((err) => {
            this.Toast.fire({
              icon: 'error',
              title: '發生錯誤，請查看開發者工具'
            })
            console.log(err)
            this.$emit('loadingSwitcher')
          })
      }
    },
    uploadNewCatData () {
      this.$emit('loadingSwitcher')
      this.$axiosHelper.post('admin/animalData/createNewCatData', this.catInfoData)
        .then((obj) => {
          this.Toast.fire({
            icon: obj.data.icon,
            title: obj.data.message
          })
          this.$emit('loadingSwitcher')
        })
        .catch((err) => {
          if (err.response.data.errors) {
            const errorMessage = err.response.data.errors
            const objectKey = Object.keys(errorMessage)
            objectKey.forEach((key) => {
              return this.Toast.fire({
                icon: 'warning',
                title: `${this.inputName[key]} ${errorMessage[key][0].split(' ')[2]}`
              })
            })
            this.$emit('loadingSwitcher')
          }
        })
    },
    updateCatData () {
      this.$emit('loadingSwitcher')
      this.$axiosHelper.put('admin/animalData/updateCatData', this.catInfoData)
        .then((obj) => {
          this.Toast.fire({
            icon: obj.data.icon,
            title: obj.data.message
          })
          this.$emit('loadingSwitcher')
        })
        .catch((err) => {
          if (err.response.data.errors) {
            const errorMessage = err.response.data.errors
            const objectKey = Object.keys(errorMessage)
            objectKey.forEach((key) => {
              return this.Toast.fire({
                icon: 'warning',
                title: `${this.inputName[key]} ${errorMessage[key][0].split(' ')[2]}`
              })
            })
            this.$emit('loadingSwitcher')
          }
        })
    },
    deleteCatData (id) {
      this.$emit('loadingSwitcher')
      this.$axiosHelper.delete(`admin/animalData/deleteCatData?id=${id}`)
        .then((obj) => {
          this.Toast.fire({
            icon: obj.data.icon,
            title: obj.data.message
          })
          this.$emit('loadingSwitcher')
        })
        .catch((err) => {
          this.Toast.fire({
            icon: 'error',
            title: '發生錯誤，請查看開發者工具'
          })
          console.log(err)
          this.$emit('loadingSwitcher')
        })
    },
    switcher (id, type) {
      if (id !== 'none') {
        const catDataIndex = this.catData.data.findIndex((obj) => obj.animal_id === id)
        const targetData = { ...this.catData.data[catDataIndex], shelter_id: this.catData.data[catDataIndex].shelter.id, shelter_city: this.catData.data[catDataIndex].shelter.shelter_city }
        this.shelterFilter(this.catData.data[catDataIndex].shelter.shelter_city)
        this.catInfoData = targetData
      }
      switch (type) {
        case 'createNewCatData': {
          this.catInfoModalSwitcher = 'show'
          this.modalType = 'createNewCatData'
          break
        }
        case 'updateCatData': {
          this.catInfoModalSwitcher = 'show'
          this.modalType = 'updateCatData'
          break
        }
        case 'deleteCatData': {
          this.delModalSwitcher = 'show'
          break
        }
        case 'closeModal': {
          this.catInfoModalSwitcher = 'hide'
          this.delModalSwitcher = 'hide'
          this.catInfoData = {
            album_file: '',
            animal_age: '',
            animal_bacterin: '',
            animal_bodytype: '',
            animal_color: '',
            animal_foundplace: '',
            animal_id: 0,
            animal_remark: '',
            animal_sex: '',
            animal_sterilization: '',
            shelterData: {
              id: '',
              shelter_address: '',
              shelter_city: '',
              shelter_lat: 0,
              shelter_lng: 0,
              shelter_name: '',
              shelter_tel: ''
            }
          }
          break
        }
      }
    },
    filterSwitcher () {
      this.filterModalSwitcher === 'hide'
        ? this.filterModalSwitcher = 'show'
        : this.filterModalSwitcher = 'hide'
    },
    shelterFilter (city) {
      if (city === '0') {
        this.catInfoData.shelter_city = '0'
        this.shelterOption = this.shelterData
      }
      if (city !== '0') {
        this.catInfoData.shelter_city = city
        this.shelterOption = this.shelterData.filter(item => item.shelter_city === city)
      }
    },
    componentSwitcher (type) {
      this.$emit('componentSwitcher', type)
    }
  },
  watch: {
    shelterData (newValue, oldValue) {
      this.shelterOption = this.shelterData
    }
  }
}
</script>
