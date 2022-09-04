<template>
  <loading-icon :active="isLoading" />
  <div class="text-end mt-3">
    <button type="button" class="btn btn-primary" @click.prevent="switcher('none','createNewCatData')">新增資料</button>
    <table class="table mt-4">
      <thead>
        <tr>
          <th width="100"></th>
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
          <td>{{item.id}}</td>
          <td>{{ item.animal_id }}</td>
          <td class="text-right">
            {{ item.animal_sex }}
          </td>
          <td class="text-right">
            {{ item.animal_color }}
          </td>
          <td>
            {{ item.short_address}}
          </td>
          <td>{{ item.album_file.length !== 0 ? '有照片':'沒照片' }}</td>
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
    @get-pagination-url="getPaginationUrl"
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
</template>
<script>
import CatInfoModal from './CatInfoModal.vue'
import PaginationComponent from '../PaginationComponent.vue'
import DelModal from '../DelModal.vue'
export default {
  components: {
    CatInfoModal,
    PaginationComponent,
    DelModal
  },
  data () {
    return ({
      catData: [],
      catInfoData: {
        animal_id: '',
        album_file: '',
        animal_age: '',
        animal_bacterin: '',
        animal_bodytype: '',
        animal_color: '',
        animal_foundplace: '',
        animal_remark: '',
        animal_sex: '',
        animal_sterilization: '',
        shelter_address: '',
        shelter_name: '',
        shelter_tel: ''
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
      isLoading: false,
      catInfoModalSwitcher: 'hide',
      delModalSwitcher: 'hide',
      modalType: ''
    })
  },
  created () {
    this.getCatData()
  },
  inject: ['Toast'],
  methods: {
    getCatData () {
      this.isLoading = true
      this.$axiosHelper.get('api/animalData')
        .then((obj) => {
          this.catData = obj.data.responseData.catData.data
          this.paginationLinks = obj.data.responseData.catData.links
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
    formValueEnter (key, value) {
      this.catInfoData[key] = value
    },
    uploadImage (value) {
      if (value) {
        this.isLoading = true
        const responseData = new FormData()
        responseData.append('image', value)
        this.$axiosHelper.post('admin/animalData/uploadImage', responseData, {
          headers: { withCredentials: true, Accept: 'application/json', Authorization: `Bearer ${this.$store.state.currentUser.token}` }
        })
          .then((obj) => {
            this.Toast.fire({
              icon: obj.data.icon,
              title: obj.data.message
            })
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
        const index = this.catData.findIndex((obj) => obj.animal_id === id)
        const targetData = { ...this.catData[index] }
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
            animal_id: '',
            album_file: '',
            animal_age: '',
            animal_bacterin: '',
            animal_bodytype: '',
            animal_color: '',
            animal_place: '',
            animal_remark: '',
            animal_sex: '',
            animal_sterilization: '',
            shelter_address: '',
            shelter_name: '',
            shelter_tel: ''
          }
          break
        }
      }
    },
    getPaginationUrl (url) {
      this.isLoading = true
      this.$axiosHelper.get(url)
        .then((obj) => {
          this.catData = obj.data.responseData.catData.data
          this.paginationLinks = obj.data.responseData.catData.links
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
  }
}
</script>
