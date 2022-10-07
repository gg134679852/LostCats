<template>
  <loading-icon :active="isLoading" />
  <div class="text-end mt-3">
    <button type="button" class="btn btn-primary" @click.prevent="switcher('none','createNewCatData')">新增資料</button>
    <button type="button" class="btn btn-secondary" @click.prevent="filterSwitcher"><i class="fas fa-filter"></i>過濾器</button>
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
        <tr v-for=" item in catData" :key="item.animal_id">
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
              <button class="btn btn-outline-primary btn-sm" @click.prevent="switcher(item.animal_id,'updateCatData')">編輯</button>
              <button class="btn btn-outline-danger btn-sm" @click.prevent="switcher(item.animal_id,'deleteCatData')">刪除</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
   <PaginationComponent
    :pagination-links="paginationLinks"
    :screen-size="screenSize"
    @fetch-animal-data="fetchAnimalData"
   />
  <CatInfoModal
    :cat-info-modal-switcher="catInfoModalSwitcher"
    :is-loading="isLoading"
    :cat-info-data="catInfoData"
    :modal-type="modalType"
    @form-value-enter="formValueEnter"
    @switcher="switcher"
    @upload-image="uploadImage"
    @upload-new-cat-data="uploadNewCatData"
    @updateCatData="updateCatData"
  />
  <DelModal
    :del-modal-switcher="delModalSwitcher"
    :animal-id="catInfoData.animal_id"
    @delete-cat-data="deleteCatData"
    @switcher="switcher"
  />
  <FilterModal
    :filter-modal-switcher="filterModalSwitcher"
    :shelter-name="shelterName"
    :color="color"
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
  components: {
    CatInfoModal,
    PaginationComponent,
    DelModal,
    FilterModal
  },
  data () {
    return ({
      catData: [],
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
        shelterData: {
          id: '',
          shelter_address: '',
          shelter_city: '',
          shelter_lat: 0,
          shelter_lng: 0,
          shelter_name: '',
          shelter_tel: ''
        }
      },
      inputName: {
        shelter_address: '收容所地址',
        shelter_tel: '收容所電話',
        shelter_name: '收容所名稱',
        animal_foundplace: '發現地點',
        animal_bacterin: '是否已施打狂犬病疫苗',
        animal_sterilization: '是否已絕育',
        animal_bodytype: '體型',
        animal_color: '顏色',
        animal_age: '年紀',
        animal_sex: '性別',
        animal_id: '編號'
      },
      paginationLinks: [],
      shelterList: [],
      shelterName: [],
      color: [],
      isLoading: false,
      catInfoModalSwitcher: 'hide',
      delModalSwitcher: 'hide',
      filterModalSwitcher: 'hide',
      modalType: '',
      screenSize: 'Big'

    })
  },
  created () {
    this.fetchAnimalData()
  },
  inject: ['Toast'],
  methods: {
    fetchAnimalData (type, url, condition) {
      this.isLoading = true
      switch (type) {
        case 'pageClick':{
          this.$axiosHelper.get(url)
            .then((obj) => {
              const { catData } = obj.data.responseData
              this.catData = catData.data
              this.paginationLinks = {
                links: catData.links,
                currentPage: catData.current_page,
                prevPageUrl: catData.prev_page_url,
                nextPageUrl: catData.next_page_url,
                lastPage: catData.last_page
              }
              this.isLoading = false
            })
            .catch((err) => {
              this.Toast.fire({
                icon: 'error',
                title: '發生錯誤，請查看開發者工具'
              })
              console.log(err)
              this.isLoading = false
            })
          break
        }
        case 'filterData':{
          url = `api/animalData/getFilter?animal_sex=${condition.animal_sex}&animal_color=${condition.animal_color}&shelter_city=${condition.shelter_city}&shelter_name=${condition.shelter_name}&haveImage=${condition.haveImage}&screenSize=${this.screenSize}`
          this.$axiosHelper.get(url)
            .then((obj) => {
              const { catData } = obj.data.responseData
              if (condition.haveImage !== '0') {
                this.catData = Object.values(catData.data)
              } else {
                this.catData = catData.data
              }
              this.paginationLinks = {
                links: catData.links,
                currentPage: catData.current_page,
                prevPageUrl: catData.prev_page_url,
                nextPageUrl: catData.next_page_url,
                lastPage: catData.last_page
              }
              this.isLoading = false
            })
            .catch((err) => {
              this.Toast.fire({
                icon: 'error',
                title: '發生錯誤，請查看開發者工具'
              })
              console.log(err)
              this.isLoading = false
            })
          break
        }
        default:{
          url = `api/animalData?screenSize=${this.screenSize}`
          this.$axiosHelper.get(url)
            .then((obj) => {
              const { catData, selectOption, shelterList } = obj.data.responseData
              this.catData = catData.data
              this.shelterList = shelterList
              this.shelterName = this.shelterList.map(data => data.shelter_name)
              this.paginationLinks = {
                links: catData.links,
                currentPage: catData.current_page,
                prevPageUrl: catData.prev_page_url,
                nextPageUrl: catData.next_page_url,
                lastPage: catData.last_page
              }
              this.color = selectOption.color
              this.isLoading = false
            })
            .catch((err) => {
              this.Toast.fire({
                icon: 'error',
                title: '發生錯誤，請查看開發者工具'
              })
              console.log(err)
              this.isLoading = false
            })
          break
        }
      }
    },
    formValueEnter (key, value) {
      this.catInfoData[key] = value
    },
    uploadImage (value) {
      if (value) {
        this.isLoading = true
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
            this.isLoading = false
          })
          .catch((err) => {
            this.Toast.fire({
              icon: 'error',
              title: '發生錯誤，請查看開發者工具'
            })
            console.log(err)
            this.isLoading = false
          })
      }
    },
    uploadNewCatData () {
      this.isLoading = true
      this.$axiosHelper.post('admin/animalData/createNewCatData', this.catInfoData)
        .then((obj) => {
          this.Toast.fire({
            icon: obj.data.icon,
            title: obj.data.message
          })
          this.isLoading = false
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
            this.isLoading = false
          }
        })
    },
    updateCatData () {
      this.isLoading = true
      this.$axiosHelper.put('admin/animalData/updateCatData', this.catInfoData)
        .then((obj) => {
          this.Toast.fire({
            icon: obj.data.icon,
            title: obj.data.message
          })
          this.isLoading = false
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
            this.isLoading = false
          }
        })
    },
    deleteCatData (id) {
      const index = this.catData.findIndex((obj) => obj.animal_id === id)
      this.isLoading = true
      this.$axiosHelper.delete(`admin/animalData/deleteCatData?id=${id}`)
        .then((obj) => {
          this.Toast.fire({
            icon: obj.data.icon,
            title: obj.data.message
          })
          if (obj.data.icon === 'success') {
            this.catData.splice(index, 1)
          }
          this.isLoading = false
        })
        .catch((err) => {
          this.Toast.fire({
            icon: 'error',
            title: '發生錯誤，請查看開發者工具'
          })
          console.log(err)
          this.isLoading = false
        })
    },
    switcher (id, type) {
      if (id !== 'none') {
        const catDataIndex = this.catData.findIndex((obj) => obj.animal_id === id)
        const targetData = { ...this.catData[catDataIndex], shelterData: { ...this.catData[catDataIndex].shelter } }
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
        case 'deleteCatData' : {
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
      if (city === '') {
        this.shelterName = this.shelterList.map(data => data.shelter_name)
      }
      if (city !== '') {
        this.shelterName = []
        this.shelterList.forEach((data) => {
          if (data.shelter_city === city) {
            this.shelterName.push(data.shelter_name)
          }
        })
      }
    }
  }
}
</script>
