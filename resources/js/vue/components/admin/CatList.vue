<template>
  <loading-icon :active="isLoading" />
  <div class="text-end mt-3">
    <button type="button" class="btn btn-primary" @click.prevent="switcher('none','newCatInfo')">新增商品</button>
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
              <button class="btn btn-outline-primary btn-sm" :id="item.id">編輯</button>
              <button class="btn btn-outline-danger btn-sm" :id="item.id">刪除</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <CatInfoModal :cat-info-modal-switcher="catInfoModalSwitcher" :is-loading="isLoading" :cat-info-data="catInfoData"
    @cancel-form-value-enter="cancelFormValueEnter" @form-value-enter="formValueEnter" @switcher="switcher"
    @upload-image="uploadImage" />
</template>
<script>
import CatInfoModal from './CatInfoModal.vue'
export default {
  components: {
    CatInfoModal
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
        animal_place: '',
        animal_remark: '',
        animal_sex: '',
        animal_sterilization: '',
        shelter_address: '',
        shelter_name: '',
        shelter_tel: ''
      },
      isLoading: false,
      catInfoModalSwitcher: 'hide'
    })
  },
  created () {
    this.getCatData()
  },
  methods: {
    getCatData () {
      this.isLoading = true
      this.$axiosHelper.get('api/animalData')
        .then((obj) => {
          this.catData = obj.data.responseData.catData.data
          this.isLoading = false
        })
        .catch((err) => {
          console.log(err)
          this.isLoading = false
        })
    },
    switcher (id, type) {
      if (id !== 'none') {
        const index = this.catData.findIndex((obj) => obj.id === id)
        const targetData = this.catData[index]
        this.catInfoData = targetData
      }
      switch (type) {
        case 'newCatInfo': {
          this.catInfoModalSwitcher === 'hide' ? (this.catInfoModalSwitcher = 'show') : (this.catInfoModalSwitcher = 'hide')
          break
        }
      }
    },
    cancelFormValueEnter () {
      this.catInfoData = {
        animal_id: 0,
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
            this.catInfoData.album_file = obj.data.image
            this.isLoading = false
          })
      }
    }
  }
}
</script>
