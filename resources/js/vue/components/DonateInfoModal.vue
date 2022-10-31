<template>
  <loading-icon :active="isTransaction" :style="{'z-index': 1200}" />
  <div class="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" ref="modal">
    <div class="modal-dialog"
      :class="{ 'modal-fullscreen': screenSize === 'Small' || screenSize === 'Middle', 'modal-lg': screenSize === 'Big' }">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">捐款人資訊</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
            @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <div class="catInfoModal__donateInfo__wrap">
            <form v-if="tradeData.MerchantID === ''">
              <div class="mb-3">
                <label class="form-label">捐款金額</label>
                <input type="number" max="5000" class="form-control" placeholder="金額不可小於500" name="price"
                  @input="enterFormData" required />
              </div>
              <div class="mb-3">
                <label class="form-label">姓名</label>
                <input type="text" class="form-control" name="name" @input="enterFormData" required />
              </div>
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">電子郵件</label>
                <input type="email" class="form-control" name="email" @input="enterFormData" required />
              </div>
              <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">電話</label>
                <input type="text" class="form-control" name="phone" @input="enterFormData" required />
              </div>
            </form>
            <div class="text-center" v-else>
              <h3>捐款金額{{ donateInfo.price }}</h3>
              <br />
              <h3>捐款至{{ shelterName }}</h3>
              <form name="Spgateway" :action="tradeData.PayGateWay" method="POST" target="_blank">
                <input type="hidden" name="MerchantID" :value="tradeData.MerchantID" /><br />
                <input type="hidden" name="TradeInfo" :value="tradeData.TradeInfo" /><br />
                <input type="hidden" name="TradeSha" :value="tradeData.TradeSha" /><br />
                <input type="hidden" name="Version" :value="tradeData.Version" /><br />
                <button type="submit" @click="closeModal" class="btn btn-secondary">
                  開始捐款程序
                </button>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="catInfoModal__donateInfo__buttons__wrap">
            <button type="button" class="btn btn-secondary mx-1" @click.prevent.stop="sendDonate"
              v-if="tradeData.MerchantID === ''">送出</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Modal from 'bootstrap/js/dist/modal'
export default {
  props: {
    donateInfoModalSwitcher: {
      type: String,
      required: true
    },
    screenSize: {
      type: String,
      required: true
    },
    shelterName: {
      type: String,
      required: true
    },
    donateInfo: {
      type: Object,
      required: true
    },
    tradeData: {
      type: Object,
      required: true
    },
    isTransaction: {
      type: Boolean,
      required: true
    }
  },
  inject: ['Toast'],
  data () {
    return ({
      modal: {}
    })
  },
  methods: {
    closeModal () {
      this.$emit('modalSwitcher')
    },
    sendDonate () {
      this.$emit('sendDonate')
    },
    enterFormData (e) {
      this.$emit('enterFormData', e)
    }
  },
  mounted () {
    this.modal = this.modal = new Modal(this.$refs.modal)
  },
  watch: {
    donateInfoModalSwitcher (newValue, oldValue) {
      return newValue === 'show' ? this.modal.show() : this.modal.hide()
    }
  }
}
</script>
