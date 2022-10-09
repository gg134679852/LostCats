<template>
  <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true" ref="modal">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content border-0">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title" id="exampleModalLabel">
            <span>{{ modalType === 'createNewShelterData' ? '新增收容所資料':'編輯收容所資料'}}</span>
          </h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-8">
              <div class="row gx-2">
                <div class="mb-3 col-md-6">
                  <label for="shelter_city" class="form-label">地區</label>
                  <select class="form-select" id="shelter_city" @change="passValue"
                    :value="shelterInfoData.shelter_city">
                    <option value="0">選擇縣市</option>
                    <option value="臺北市">臺北市</option>
                    <option value="基隆市">基隆市</option>
                    <option value="新北市">新北市</option>
                    <option value="宜蘭縣">宜蘭縣</option>
                    <option value="桃園市">桃園市</option>
                    <option value="新竹市">新竹市</option>
                    <option value="新竹縣">新竹縣</option>
                    <option value="苗栗縣">苗栗縣</option>
                    <option value="台中市">台中市</option>
                    <option value="彰化縣">彰化縣</option>
                    <option value="南投縣">南投縣</option>
                    <option value="嘉義市">嘉義市</option>
                    <option value="嘉義縣">嘉義縣</option>
                    <option value="雲林縣">雲林縣</option>
                    <option value="台南市">台南市</option>
                    <option value="高雄市">高雄市</option>
                    <option value="澎湖縣">澎湖縣</option>
                    <option value="金門縣">金門縣</option>
                    <option value="屏東縣">屏東縣</option>
                    <option value="台東縣">台東縣</option>
                    <option value="花蓮縣">花蓮縣</option>
                    <option value="連江縣">連江縣</option>
                  </select>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="shelter_name" class="form-label">收容所名稱</label>
                  <input type="text" class="form-control" id="shelter_name" placeholder="請輸入收容所名稱" @input="passValue"
                    :value="shelterInfoData.shelter_name">
                </div>
              </div>
              <div class="mb-3">
                <label for="shelter_tel" class="form-label">電話</label>
                <input type="text" class="form-control" id="shelter_tel" placeholder="請輸入電話" @input="passValue"
                  :value="shelterInfoData.shelter_tel">
              </div>
              <div class="mb-3">
                <label for="shelter_address" class="form-label">地址</label>
                <input type="text" class="form-control" id="shelter_address" placeholder="請輸入地址" @input="passValue"
                  :value="shelterInfoData.shelter_address">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                  @click="closeModal('none', 'closeModal')">關閉視窗
                </button>
                <button type="button" class="btn btn-primary" @click.prevent="confirmInfoData('newData')"
                  v-if="modalType === 'createNewShelterData'">確認</button>
                <button type="button" class="btn btn-primary" @click.prevent="confirmInfoData('updateData')"
                  v-else>確認</button>
              </div>
            </div>
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
    shelterInfoData: {
      type: Object,
      require: true
    },
    shelterInfoModalSwitcher: {
      type: String,
      require: true
    },
    modalType: {
      type: String,
      require: true
    }
  },
  data () {
    return ({
      modal: {}
    })
  },
  mounted () {
    this.modal = this.modal = new Modal(this.$refs.modal)
  },
  methods: {
    closeModal (id, type) {
      this.$emit('switcher', id, type)
    },
    passValue (e) {
      const key = e.target.id
      const value = e.target.value
      this.$emit('formValueEnter', key, value)
    },
    confirmInfoData (type) {
      switch (type) {
        case 'newData': {
          this.$emit('uploadNewShelterData')
          break
        }
        case 'updateData': {
          this.$emit('updateShelterData')
          break
        }
      }
    }
  },
  watch: {
    shelterInfoModalSwitcher (newValue, oldValue) {
      return newValue === 'show' ? this.modal.show() : this.modal.hide()
    }
  }
}
</script>
