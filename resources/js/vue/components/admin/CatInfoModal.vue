<template>
  <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel"
    aria-hidden="true" ref="modal">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content border-0">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="exampleModalLabel">
            <span>新增貓咪資料</span>
          </h5>
        </div>
        <div class="modal-body">
          <loading-icon :active="isLoading" />
          <div class="row">
            <div class="col-sm-4">
              <div class="mb-3">
                <label for="album_file" class="form-label">輸入圖片網址</label>
                <input type="text" class="form-control" id="album_file" placeholder="請輸入圖片連結" @input="passValue"
                  :value="catInfoData.album_file">
              </div>
              <div class="mb-3">
                <label for="customFile" class="form-label">或 上傳圖片
                  <i class="fas fa-spinner fa-spin"></i>
                </label>
                <input type="file" id="customFile" class="form-control" @change="uploadImage">
              </div>
              <img class="img-fluid" alt="">
              <!-- 延伸技巧，多圖 -->
              <div class="mt-5">
                <div class="mb-3 input-group">
                  <input type="url" class="form-control form-control" placeholder="請輸入連結">
                  <button type="button" class="btn btn-outline-danger">
                    移除
                  </button>
                </div>
                <div>
                  <button class="btn btn-outline-primary btn-sm d-block w-100">
                    新增圖片
                  </button>
                </div>
              </div>
            </div>
            <div class="col-sm-8">
              <div class="mb-3">
                <label for="animal_id" class="form-label">編號</label>
                <input type="text" class="form-control" id="animal_id" placeholder="請輸入編號" @input="passValue"
                  :value="catInfoData.animal_id">
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
                  <label for="animal_foundplace" class="form-label">發現地點</label>
                  <input type="text" class="form-control" id="animal_foundplace" placeholder="請輸入發現地點"
                    @input="passValue" :value="catInfoData.animal_foundplace">
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
              <div class="row gx-2">
                <div class="mb-3 col-md-6">
                  <label for="shelter_name" class="form-label">收容所名稱</label>
                  <input type="text" class="form-control" id="shelter_name" placeholder="請輸入收容所名稱" @input="passValue"
                    :value="catInfoData.shelter_name">
                </div>
                <div class="mb-3 col-md-6">
                  <label for="shelter_tel" class="form-label">收容所電話</label>
                  <input type="text" class="form-control" id="shelter_tel" placeholder="請輸入收容所電話" @input="passValue"
                    :value="catInfoData.shelter_tel">
                </div>
              </div>
              <div class="mb-3">
                <label for="shelter_address" class="form-label">收容所地址</label>
                <input type="text" class="form-control" id="shelter_address" placeholder="請輸入收容所地址" @input="passValue"
                  :value="catInfoData.shelter_address">
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
              @click="closeModal('none', 'newCatInfo')">取消
            </button>
            <button type="button" class="btn btn-primary">確認</button>
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
    isLoading: {
      type: Boolean,
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
      this.$emit('cancelFormValueEnter')
    },
    passValue (e) {
      const key = e.target.id
      const value = e.target.value
      this.$emit('formValueEnter', key, value)
    },
    uploadImage (value) {
      this.$emit('uploadImage', value.target.files[0])
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
