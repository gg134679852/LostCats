<template>
  <div class="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" ref="modal">
  <div class="modal-dialog" :class="{ 'modal-fullscreen' : screenSize === 'Small'}">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">過濾器</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="fetchAnimalData">
           <div class="row gx-2">
            <div class="mb-3 col-md-6">
              <label for="animal_sex" class="form-label">性別</label>
              <select class="form-select" id="animal_sex" v-model="formData.animal_sex">
                  <option value="0">選擇性別</option>
                  <option value="男生">男生</option>
                  <option value="女生">女生</option>
              </select>
            </div>
            <div class="mb-3 col-md-6">
              <label for="color" class="form-label">毛色</label>
              <select class="form-select" id="color" v-model="formData.animal_color">
                  <option value="0">選擇毛色</option>
                  <option :value="color" v-for="(color,index) in color" :key="index">{{color}}</option>
              </select>
            </div>
          </div>
           <div class="mb-3">
              <label for="shelter_city" class="form-label">地區</label>
             <select  v-model="formData.shelter_city" class="form-select" id="shelter_city" @change="shelterFilter">
                  <option value="0">選擇縣市</option>
                  <option value="臺北市">臺北市</option>
                  <option value="基隆市">基隆市</option>
                  <option value="新北市">新北市</option>
                  <option value="宜蘭縣">宜蘭縣</option>
                  <option value="桃園市" >桃園市</option>
                  <option value="新竹市" >新竹市</option>
                  <option value="新竹縣" >新竹縣</option>
                  <option value="苗栗縣" >苗栗縣</option>
                  <option value="台中市" >台中市</option>
                  <option value="彰化縣" >彰化縣</option>
                  <option value="南投縣" >南投縣</option>
                  <option value="嘉義市" >嘉義市</option>
                  <option value="嘉義縣" >嘉義縣</option>
                  <option value="雲林縣" >雲林縣</option>
                  <option value="台南市" >台南市</option>
                  <option value="高雄市" >高雄市</option>
                  <option value="澎湖縣" >澎湖縣</option>
                  <option value="金門縣" >金門縣</option>
                  <option value="屏東縣" >屏東縣</option>
                  <option value="台東縣" >台東縣</option>
                  <option value="花蓮縣" >花蓮縣</option>
                  <option value="連江縣" >連江縣</option>
            </select>
            </div>
           <div class="mb-3">
                <label for="shelter_name" class="form-label">收容所</label>
                 <select class="form-select" id="shelter_name" v-model="formData.shelter_name">
                 <option value="0">選擇收容所</option>
                 <option :value="item.shelter_name" v-for="(item,index) in shelterData" :key="index">{{item.shelter_name}}</option>
              </select>
              </div>
              <div class="mb-3">
                <label for="shelter_name" class="form-label">是否要有照片</label>
                 <select class="form-select" id="shelter_name" v-model="formData.haveImage">
                 <option value="0">默認</option>
                 <option value="notNull">有照片</option>
                 <option value="Null">沒照片</option>
              </select>
              </div>
          <button type="submit" class="btn btn-secondary">送出</button>
       </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
</template>
<script>
import Modal from '../../../../node_modules/bootstrap/js/dist/modal'
export default {
  props: {
    filterModalSwitcher: {
      type: String,
      required: true
    },
    shelterData: {
      type: Array,
      required: true
    },
    color: {
      type: Array,
      required: true
    },
    screenSize: {
      type: String,
      required: true
    }
  },
  data () {
    return ({
      modal: {},
      formData: {
        animal_sex: '0',
        shelter_city: '0',
        animal_color: '0',
        shelter_name: '0',
        haveImage: '0'
      }
    })
  },
  methods: {
    closeModal () {
      this.formData =
      {
        animal_sex: '0',
        shelter_city: '0',
        animal_color: '0',
        shelter_name: '0',
        haveImage: '0'
      }
      this.$emit('filterSwitcher')
    },
    shelterFilter (e) {
      this.formData.shelter_name = '0'
      this.$emit('shelterFilter', e.target.value)
    },
    fetchAnimalData () {
      this.$emit('fetchAnimalData', 'filterData', this.formData)
      this.closeModal()
    }
  },
  mounted () {
    this.modal = new Modal(this.$refs.modal)
  },
  watch: {
    filterModalSwitcher (newValue, oldValue) {
      return newValue === 'show' ? this.modal.show() : this.modal.hide()
    }
  }
}
</script>
