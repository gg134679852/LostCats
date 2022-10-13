<template>
  <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="exampleModalLabel"
    aria-hidden="true" ref="modal">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content border-0">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="exampleModalLabel">
            <span>{{ modalType === 'createNewCatData' ? '新增貓咪資料':'編輯貓咪資料'}}</span>
          </h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-4">
              <div class="mb-3">
                <label for="album_file" class="form-label">輸入圖片網址</label>
                <input type="text" class="form-control" id="album_file" placeholder="請輸入圖片連結" @input="passValue"
                  :value="catInfoData.album_file">
              </div>
              <div class="mb-3">
                <label for="customFile" class="form-label">或 上傳圖片
                </label>
                <input type="file" id="customFile" class="form-control" @change="uploadImage">
              </div>
              <img class="img-fluid" :src="catInfoData.album_file" alt="">
            </div>
            <div class="col-sm-8">
              <div class="mb-3" v-if="modalType === 'updateCatData'">
                <label for="animal_id" class="form-label">編號</label>
                <input type="text" class="form-control" id="animal_id" placeholder="請輸入編號"            @input="passValue" :value="catInfoData.animal_id">
              </div>
              <div class="row gx-2">
                <div class="mb-3 col-md-6">
                  <label for="animal_sex" class="form-label">性別</label>
                  <select class="form-select" id="animal_sex" @change="passValue" :value="catInfoData.animal_sex">
                    <option value=""></option>
                    <option value="男生">男生</option>
                    <option value="女生">女生</option>
                  </select>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="animal_age" class="form-label">年紀</label>
                  <select class="form-select" id="animal_age" @change="passValue" :value="catInfoData.animal_age">
                    <option value=""></option>
                    <option value="幼年">幼年</option>
                    <option value="成年">成年</option>
                    <option value="歲數未知">歲數未知</option>
                  </select>
                </div>
              </div>
              <div class="row gx-2">
                <div class="mb-3 col-md-6">
                  <label for="animal_color" class="form-label">顏色</label>
                  <input type="text" class="form-control" id="animal_color" placeholder="請輸入顏色" @input="passValue"
                    :value="catInfoData.animal_color">
                </div>
                <div class="mb-3 col-md-6">
                  <label for="animal_bodytype" class="form-label">體型</label>
                   <select class="form-select" id="animal_bodytype" @change="passValue" :value="catInfoData.animal_bodytype">
                    <option value=""></option>
                    <option value="小型">小型</option>
                    <option value="中型">中型</option>
                    <option value="大型">大型</option>
                  </select>
                </div>
              </div>
              <div class="row gx-2">
                <div class="mb-3 col-md-6">
                  <label for="animal_sterilization" class="form-label">是否已絕育</label>
                  <select class="form-select" id="animal_sterilization" @change="passValue"
                    :value="catInfoData.animal_sterilization">
                    <option value=""></option>
                    <option value="已絕育">已絕育</option>
                    <option value="未絕育">未絕育</option>
                  </select>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="animal_bacterin" class="form-label">是否已施打狂犬病疫苗</label>
                  <select class="form-select" id="animal_bacterin" @change="passValue"
                    :value="catInfoData.animal_bacterin">
                    <option value=""></option>
                    <option value="已打狂犬病疫苗">已打狂犬病疫苗</option>
                    <option value="未打狂犬病疫苗">未打狂犬病疫苗</option>
                  </select>
                </div>
              </div>
               <div class="mb-3">
                <label for="animal_foundplace" class="form-label">發現地點</label>
                <input type="text" class="form-control" id="animal_foundplace" placeholder="請輸入發現地點"
                    @input="passValue" :value="catInfoData.animal_foundplace">
              </div>
               <hr>
              <div class="row gx-2">
                <div class="mb-3 col-md-6">
                  <label for="shelter_city" class="form-label">地區</label>
             <select class="form-select" id="shelter_city" @change="shelterFilter" :value="catInfoData.shelter_city" >
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
                <div class="mb-3 col-md-6">
                   <label for="shelter_id" class="form-label">收容所</label>
                 <select class="form-select" id="shelter_id" :value="catInfoData.shelter_id" @change="passValue">
                 <option value="0">選擇收容所</option>
                 <option :value="item.id" v-for="(item,index) in shelterOption" :key="index">{{item.shelter_name}}</option>
              </select>
                </div>
              </div>
              <hr>
              <div class=" mb-3">
                <label for="animal_remark" class="form-label">補充註記</label>
                <textarea type="text" class="form-control" id="animal_remark" placeholder="請輸入補充註記" @input="passValue"
                  :value="catInfoData.animal_remark"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
              @click="closeModal('none', 'closeModal')">關閉視窗
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="confirmInfoData('newData')" v-if="modalType === 'createNewCatData'" >確認</button>
             <button type="button" class="btn btn-primary" @click.prevent="confirmInfoData('updateData')" v-else >確認</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Modal from '../../../../../node_modules/bootstrap/js/dist/modal'
export default {
  props: {
    catInfoModalSwitcher: {
      type: String,
      required: true
    },
    catInfoData: {
      type: Object,
      required: true
    },
    modalType: {
      type: String,
      required: true
    },
    shelterOption: {
      type: Array,
      required: true
    }
  },
  data () {
    return ({
      modal: {}
    })
  },
  methods: {
    closeModal (id, type) {
      this.$emit('switcher', id, type)
      this.$emit('shelterFilter', '0')
    },
    passValue (e) {
      const key = e.target.id
      const value = e.target.value
      this.$emit('formValueEnter', key, value)
    },
    uploadImage (value) {
      this.$emit('uploadImage', value.target.files[0])
    },
    confirmInfoData (type) {
      switch (type) {
        case 'newData':{
          this.$emit('uploadNewCatData')
          break
        }
        case 'updateData':{
          this.$emit('updateCatData')
          break
        }
      }
    },
    shelterFilter (e) {
      this.$emit('shelterFilter', e.target.value)
    }
  },
  mounted () {
    this.modal = new Modal(this.$refs.modal)
  },
  watch: {
    catInfoModalSwitcher (newValue, oldValue) {
      return newValue === 'show' ? this.modal.show() : this.modal.hide()
    }
  }
}
</script>
