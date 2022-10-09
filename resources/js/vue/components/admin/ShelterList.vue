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
      </ul>
    </div>
    <div class="button-warp">
      <button type="button" class="btn btn-primary"
        @click.prevent="switcher('none','createNewShelterData')">新增資料</button>
    </div>
  </div>
  <div class="text-end mt-3">
    <table class="table mt-4">
      <thead>
        <tr>
          <th width="120">收容所名稱</th>
          <th width="120">所在地區</th>
          <th width="200">地址</th>
          <th width="200">編輯</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for=" item in shelterData" :key="item.id">
          <td>
            {{ item.shelter_name }}
          </td>
          <td>
            {{ item.shelter_city }}
          </td>
          <td>
            {{ item.shelter_address }}
          </td>
          <td>
            <div class="btn-group">
              <button class="btn btn-outline-primary btn-sm"
                @click.prevent="switcher(item.id,'updateShelterData')">編輯</button>
              <button class="btn btn-outline-danger btn-sm"
                @click.prevent="switcher(item.id,'deleteShelterData')">刪除</button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <ShelterInfoModal
    :shelter-info-data="shelterInfoData" :shelter-info-modal-switcher="shelterInfoModalSwitcher"
    :modal-type="modalType"
    :del-modal-type="delModalType" @upload-new-shelter-data="uploadNewShelterData"
    @update-shelter-data="updateShelterData"
    @form-value-enter="formValueEnter"
    @switcher="switcher"
    />
  <DelModal
    :del-modal-switcher="delModalSwitcher"
    :shelter-info-data="shelterInfoData"
    :del-modal-type="delModalType"
    @delete-shelter-data="deleteShelterData"
    @switcher="switcher"
  />
</template>
<script>
import ShelterInfoModal from './ShelterInfoModal.vue'
import DelModal from './DelModal.vue'
export default {
  components: {
    ShelterInfoModal,
    DelModal
  },
  inject: ['Toast'],
  data () {
    return ({
      shelterInfoData: {
        shelter_name: '',
        shelter_city: '0',
        shelter_address: '',
        shelter_tel: ''
      },
      inputName: {
        shelter_name: '收容所名稱',
        shelter_city: '0',
        shelter_address: '',
        shelter_tel: ''
      },
      shelterInfoModalSwitcher: 'hide',
      delModalSwitcher: 'hide',
      modalType: '',
      delModalType: 'ShelterList'
    })
  },
  props: {
    currentComponent: {
      type: String,
      require: true
    },
    shelterData: {
      type: Object,
      require: true
    }
  },
  emits: ['componentSwitcher', 'loadingSwitcher'],
  methods: {
    componentSwitcher (type) {
      this.$emit('componentSwitcher', type)
    },
    formValueEnter (key, value) {
      this.shelterInfoData[key] = value
    },
    uploadNewShelterData () {
      this.$emit('loadingSwitcher')
      this.$axiosHelper.post('admin/shelterData/createNewShelterData', this.shelterInfoData)
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
    updateShelterData () {
      this.$emit('loadingSwitcher')
      this.$axiosHelper.put('admin/shelterData/updateShelterData', this.shelterInfoData)
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
    deleteShelterData (id) {
      this.$emit('loadingSwitcher')
      this.$axiosHelper.delete(`admin/shelterData/deleteShelterData?id=${id}`)
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
        const shelterDataIndex = this.shelterData.findIndex((obj) => obj.id === id)
        const targetData = { ...this.shelterData[shelterDataIndex] }
        this.shelterInfoData = targetData
      }
      switch (type) {
        case 'createNewShelterData': {
          this.shelterInfoModalSwitcher = 'show'
          this.modalType = 'createNewShelterData'
          break
        }
        case 'updateShelterData': {
          this.shelterInfoModalSwitcher = 'show'
          this.modalType = 'updateShelterData'
          break
        }
        case 'deleteShelterData': {
          this.delModalSwitcher = 'show'
          break
        }
        case 'closeModal': {
          this.shelterInfoModalSwitcher = 'hide'
          this.delModalSwitcher = 'hide'
          this.shelterInfoData = {
            shelter_name: '',
            shelter_city: '0',
            shelter_address: '',
            shelter_tel: ''
          }
          break
        }
      }
    }
  }
}
</script>
