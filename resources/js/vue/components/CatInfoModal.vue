<template>
<div class="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" ref="modal">
  <div class="modal-dialog" :class="{ 'modal-fullscreen' : screenSize === 'Small'||screenSize === 'Middle','modal-lg':screenSize === 'Big'}">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">喵星人資訊</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
      </div>
      <div class="modal-body">
       <div class="catInfoModal__wrap" v-if="modalType === 'catInfoModal'">
        <div class="catInfoModal__catInfo">
          <div class="catInfoModal__catInfo__img">
            <img
                class="rounded-3"
                :src="showCatData.album_file"
                onerror="this.src='https://via.placeholder.com/200x200?text=NO+IMAGE'"
              />
              <div class="catInfoModal__catInfo__buttonGroup">
                  <button type="button" class="btn btn-success" @click="changeModal" v-if="this.$store.state.isAuthenticated">捐款</button>
                   <button type="button" class="btn btn-success" @click="changeModal" v-if="this.$store.state.isAuthenticated">收藏</button>
              </div>
          </div>
          <div class="catInfoModal__catInfo__content">
              <div class="catInfoModal__catInfo__content__text__id">
                <h2>編號:{{showCatData.animal_id}}</h2>
              </div>
               <div class="catInfoModal__catInfo__info-box__wrap">
              <div class="catInfoModal__catInfo__info-box">
                <div class="catInfoModal__catInfo__info-box__text">
                    <h6>性別</h6>
                  <h2>{{showCatData.animal_sex}}</h2>
                </div>
              </div>
               <div class="catInfoModal__catInfo__info-box">
                <div class="catInfoModal__catInfo__info-box__text">
                   <h6>體型</h6>
                  <h2>{{showCatData.animal_bodytype}}</h2>
                </div>
              </div>
               <div class="catInfoModal__catInfo__info-box">
                <div class="catInfoModal__catInfo__info-box__text">
                   <h6>毛色</h6>
                  <h2>{{showCatData.animal_color}}</h2>
                </div>
              </div>
               <div class="catInfoModal__catInfo__info-box">
                 <div class="catInfoModal__catInfo__info-box__text">
                   <h6>是否結紮</h6>
                  <h2>{{showCatData.animal_sterilization}}</h2>
                 </div>
              </div>
               <div class="catInfoModal__catInfo__info-box">
               <div class="catInfoModal__catInfo__info-box__text">
                  <h6>是否施打疫苗</h6>
                  <h2>{{showCatData.animal_bacterin}}</h2>
               </div>
              </div>
             </div>
          </div>
          </div>
           <div class="catInfoModal__catInfo__content__text__remark">
                <h3>註記:</h3>
                <h2>{{showCatData.animal_remark}}</h2>
              </div>
          <hr>
           <div class="catInfoModal__shelterInfo__info-box__wrap">
             <div class="catInfoModal__shelterInfo__info-box">
                 <div class="catInfoModal__shelterInfo__info-box__text">
                    <h6>收容所地區</h6>
                  <h2>{{showCatData.shelterData.shelter_city}}</h2>
                </div>
                <div class="catInfoModal__shelterInfo__info-box__text">
                    <h6>收容所名稱</h6>
                  <h2>{{showCatData.shelterData.shelter_name}}</h2>
                </div>
                <div class="catInfoModal__shelterInfo__info-box__text">
                    <h6>收容所電話</h6>
                  <h2>{{showCatData.shelterData.shelter_tel}}</h2>
                </div>
              </div>
           </div>
          <div class="catInfoModal__info__map">
            <GoogleMap :shelter-data="showCatData.shelterData" :screen-size="screenSize" />
          </div>
       </div>
       <div class="catInfoModal__donateInfo__wrap" v-if="modalType === 'donateInfoModal'">
        <form v-if="tradeData.MerchantID === ''">
        <div class="mb-3">
          <label class="form-label">捐款金額</label>
           <input
            type="number"
            max="5000"
            class="form-control"
            placeholder="金額不可小於500"
            v-model="donateInfo.price"
            required
            />

        </div>
        <div class="mb-3">
          <label class="form-label">姓名</label>
          <input
            type="text"
            class="form-control"
            v-model="donateInfo.name"
            required
            />
        </div>
        <div class="mb-3">
           <label for="exampleFormControlInput1" class="form-label">電子郵件</label>
           <input
            type="email"
            class="form-control"
            v-model="donateInfo.email"
            required
            />
        </div>
         <div class="mb-3">
           <label for="exampleFormControlInput1" class="form-label">電話</label>
            <input
              type="text"
              class="form-control"
              v-model="donateInfo.phone"
              required
            />

        </div>
        </form>
          <div class="text-center" v-else>
                <h3>捐款金額{{donateInfo.price}}</h3>
                <br />
                <h3>捐款至{{showCatData.shelterData.shelter_name}}</h3>
                <form
                  name="Spgateway"
                  :action="tradeData.PayGateWay"
                  method="POST" target="_blank"
                >
                  <input
                    type="hidden"
                    name="MerchantID"
                    :value="tradeData.MerchantID"
                  /><br />
                  <input
                    type="hidden"
                    name="TradeInfo"
                    :value="tradeData.TradeInfo"
                  /><br />
                  <input
                    type="hidden"
                    name="TradeSha"
                    :value="tradeData.TradeSha"
                  /><br />
                  <input
                    type="hidden"
                    name="Version"
                    :value="tradeData.Version"
                  /><br />
                  <button type="submit" class="btn btn-primary" @click="closeModal">
                    開始捐款程序
                  </button>
                </form>
              </div>
       </div>
      </div>
      <div class="modal-footer">
         <div class="catInfoModal__donateInfo__buttons__wrap" v-if="modalType === 'donateInfoModal'">
         <button type="button" class="btn btn-primary" @click="changeModal">返回</button>
         <button type="button" class="btn btn-primary" @click.prevent.stop="sendDonate">送出</button>
         </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import Modal from '../../../../node_modules/bootstrap/js/dist/modal'
import GoogleMap from './GoogleMap.vue'
export default {
  components: {
    GoogleMap
  },
  props: {
    catInfoModalSwitcher: {
      type: String,
      required: true
    },
    showCatData: {
      type: Object,
      required: true
    },
    screenSize: {
      type: String,
      required: true
    }
  },
  inject: ['Toast'],
  data () {
    return ({
      modal: {},
      donateInfo: {
        price: '',
        name: '',
        email: '',
        phone: ''
      },
      tradeData: {
        PayGateWay: '',
        MerchantID: '',
        TradeInfo: '',
        TradeSha: '',
        Version: ''
      },
      modalType: 'catInfoModal'
    })
  },
  methods: {
    closeModal () {
      this.$emit('switcher', 'catInfo', 'none')
    },
    changeModal () {
      this.modalType === 'catInfoModal' ? this.modalType = 'donateInfoModal' : this.modalType = 'catInfoModal'
    },
    sendDonate () {
      if (this.donateInfo.price < 0 || this.donateInfo.price < 500) {
        this.Toast.fire({
          icon: 'warning',
          title: '金額錯誤'
        })
      } else {
        this.$axiosHelper
          .post('spgateway/donate', {
            data: {
              ...this.donateInfo,
              shelter_name: this.showCatData.shelterData.shelter_name
            }
          })
          .then((obj) => {
            this.tradeData = { ...this.tradeData, ...obj.data }
          })
      }
    }
  },
  mounted () {
    this.modal = this.modal = new Modal(this.$refs.modal)
  },
  watch: {
    catInfoModalSwitcher (newValue, oldValue) {
      return newValue === 'show' ? this.modal.show() : this.modal.hide()
    }
  }
}
</script>
<style lang="scss">
  @import '../scss/CatInfoModal.scss';
</style>
