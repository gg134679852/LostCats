<template>
  <loading-icon :active="isLoading" />
  <div class="detailPage__wrap container" v-if="isLoading === false">
    <div class="detailPage__catInfo">
      <div class="detailPage__catInfo__img">
        <img class="rounded-3" :src="showCatData.album_file"
          onerror="this.src='https://via.placeholder.com/200x200?text=NO+IMAGE'" />
      </div>
      <div class="detailPage__catInfo__content">
        <div class="detailPage__catInfo__content__text__id">
          <h2>編號:{{ showCatData.animal_id }}</h2>
        </div>
        <div class="detailPage__catInfo__info-box__wrap">
          <div class="detailPage__catInfo__info-box">
            <div class="detailPage__catInfo__info-box__text">
              <h6>性別</h6>
              <h2>{{ showCatData.animal_sex }}</h2>
            </div>
          </div>
          <div class="detailPage__catInfo__info-box">
            <div class="detailPage__catInfo__info-box__text">
              <h6>體型</h6>
              <h2>{{ showCatData.animal_bodytype }}</h2>
            </div>
          </div>
          <div class="detailPage__catInfo__info-box">
            <div class="detailPage__catInfo__info-box__text">
              <h6>毛色</h6>
              <h2>{{ showCatData.animal_color }}</h2>
            </div>
          </div>
          <div class="detailPage__catInfo__info-box">
            <div class="detailPage__catInfo__info-box__text">
              <h6>是否結紮</h6>
              <h2>{{ showCatData.animal_sterilization }}</h2>
            </div>
          </div>
          <div class="detailPage__catInfo__info-box">
            <div class="detailPage__catInfo__info-box__text">
              <h6>是否施打疫苗</h6>
              <h2>{{ showCatData.animal_bacterin }}</h2>
            </div>
          </div>
        </div>
        <div class="detailPage__catInfo__buttonGroup">
          <button type="button" class="btn btn-secondary" @click.stop.prevent="adoptionLink"><i
              class="fas fa-home"></i>領養</button>
          <template v-if="this.$store.state.isAuthenticated">
            <button type="button" class="btn btn-secondary" @click="modalSwitcher"><i
                class="fas fa-dollar-sign"></i>捐款</button>
            <button type="button" class="btn btn-secondary" @click="addFavorite(showCatData.id)"
              v-if="isFavorite(showCatData.id) === false"><i class="far fa-heart"></i>收藏</button>
            <button type="button" class="btn btn-secondary" @click="removeFavorite(showCatData.id)" v-else><i
                class="far fa-heart"></i>已收藏</button>
          </template>
        </div>
      </div>
    </div>
    <template v-if="showCatData.animal_remark.length > 0">
      <div class="detailPage__catInfo__content__text__remark">
        <h3>註記:</h3>
        <h2>{{ showCatData.animal_remark }}</h2>
      </div>
    </template>
    <hr>
    <div class="detailPage__shelterInfo__info-box">
      <div class="detailPage__shelterInfo__info-box__text">
        <h6>收容所名稱</h6>
        <h2>{{ showCatData.shelter.shelter_name }}</h2>
      </div>
      <div class="detailPage__shelterInfo__info-box__text">
        <h6>收容所電話</h6>
        <h2>{{ showCatData.shelter.shelter_tel }}</h2>
      </div>
      <div class="detailPage__shelterInfo__info-box__text">
        <h6>收容所地區</h6>
        <h2>{{ showCatData.shelter.shelter_city }}</h2>
      </div>
      <div class="detailPage__shelterInfo__info-box__text">
        <h6>收容所地址</h6>
        <h2>{{ showCatData.shelter.shelter_address }}</h2>
      </div>
    </div>
    <br>
    <div class="detailPage__info__map">
      <GoogleMap :shelter-data="showCatData.shelter" :screen-size="screenSize" />
    </div>
  </div>
  <DonateInfoModal
    :screen-size="screenSize" :donate-info-modal-switcher="donateInfoModalSwitcher"
    :shelter-name="showCatData.shelter.shelter_name" :donate-info="donateInfo" :trade-data="tradeData"
    :is-transaction="isTransaction" @modal-switcher="modalSwitcher" @enter-form-data="enterFormData"
    @send-donate="sendDonate" />
</template>
<script>
import GoogleMap from './GoogleMap.vue'
import DonateInfoModal from './DonateInfoModal.vue'
import { Toast } from '../utils/helpers'
export default {
  components: {
    GoogleMap,
    DonateInfoModal
  },
  provide () {
    return {
      Toast
    }
  },
  data () {
    return ({
      showCatData: {
        album_file: '',
        animal_age: '',
        animal_bacterin: '',
        animal_bodytype: '',
        animal_color: '',
        animal_foundplace: '',
        animal_id: '',
        animal_remark: '',
        animal_sex: '',
        animal_sterilization: '',
        shelter: {
          id: '',
          shelter_address: '',
          shelter_city: '',
          shelter_lat: 0,
          shelter_lng: 0,
          shelter_name: '',
          shelter_tel: ''
        }
      },
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
      screenSize: '',
      donateInfoModalSwitcher: 'hide',
      isLoading: false,
      isTransaction: false
    })
  },
  created () {
    this.fetchAnimalData()
    this.screenRuler()
  },
  mounted () {
    window.onresize = () => {
      this.screenRuler()
    }
  },
  methods: {
    fetchAnimalData () {
      const id = this.$route.params.id
      this.isLoading = true
      this.$axiosHelper.get(`api/animalData/getDetail?id=${id}`)
        .then((obj) => {
          const { catData } = obj.data.responseData
          this.showCatData = { ...catData[0], shelter: { ...catData[0].shelter, shelter_lat: Number(catData[0].shelter.shelter_lat), shelter_lng: Number(catData[0].shelter.shelter_lng) } }
          this.isLoading = false
        })
        .catch((error) => {
          Toast.fire({
            icon: 'warning',
            title: '發生錯誤 請稍後在試'
          })
          console.log(error)
          this.isLoading = false
        })
    },
    isFavorite (id) {
      return this.$store.state.favoriteCats.findIndex((obj) => obj.id === id) !== -1
    },
    addFavorite (id) {
      if (this.$store.state.favoriteCats.length === 16) {
        Toast.fire({
          icon: 'warning',
          title: '達到收藏上限'
        })
      } else {
        this.isLoading = true
        this.$axiosHelper
          .post(`user/addFavorite/${id}`)
          .then(() => {
            this.$store.dispatch('fetchCurrentUser')
            Toast.fire({
              icon: 'success',
              title: '成功加入最愛'
            })
            this.isLoading = false
          })
          .catch((error) => {
            Toast.fire({
              icon: 'warning',
              title: '發生錯誤 請稍後在試'
            })
            console.log(error)
            this.isLoading = false
          })
      }
    },
    removeFavorite (id) {
      this.isLoading = true
      this.$axiosHelper
        .delete(`user/removeFavorite/${id}`)
        .then(() => {
          this.$store.dispatch('fetchCurrentUser')
          Toast.fire({
            icon: 'success',
            title: '成功移除最愛'
          })
          this.isLoading = false
        })
        .catch((error) => {
          Toast.fire({
            icon: 'warning',
            title: '發生錯誤 請稍後在試'
          })
          console.log(error)
          this.isLoading = false
        })
    },
    screenRuler () {
      switch (true) {
        case window.innerWidth <= 767: {
          this.screenSize = 'Small'
          break
        }
        case window.innerWidth >= 992: {
          this.screenSize = 'Big'
          break
        }
        case window.innerWidth >= 768: {
          this.screenSize = 'Middle'
          break
        }
      }
    },
    adoptionLink () {
      window.open(
        `https://asms.coa.gov.tw/Amlapp/App/AnnounceList.aspx?Id=${this.showCatData.animal_id}&AcceptNum=${this.showCatData.animal_subid}&PageType=Adopt`, '_blank')
    },
    enterFormData (e) {
      const key = e.target.name
      const value = e.target.value
      this.donateInfo[key] = value
    },
    sendDonate () {
      if (this.donateInfo.price < 0 || this.donateInfo.price < 500) {
        Toast.fire({
          icon: 'warning',
          title: '金額錯誤'
        })
      } else {
        this.isTransaction = true
        this.$axiosHelper
          .post('spgateway/donate', {
            data: {
              ...this.donateInfo,
              shelter_name: this.showCatData.shelter.shelter_name
            }
          })
          .then((obj) => {
            this.tradeData = { ...this.tradeData, ...obj.data }
            this.isTransaction = false
          })
          .catch((error) => {
            this.Toast.fire({
              icon: 'warning',
              title: '發生錯誤 請稍後在試'
            })
            console.log(error)
            this.isTransaction = false
          })
      }
    },
    modalSwitcher () {
      this.donateInfoModalSwitcher === 'hide' ? this.donateInfoModalSwitcher = 'show' : this.donateInfoModalSwitcher = 'hide'
      if (this.donateInfoModalSwitcher === 'show') {
        this.donateInfo = {
          price: '',
          name: '',
          email: '',
          phone: ''
        }
        this.tradeData = {
          PayGateWay: '',
          MerchantID: '',
          TradeInfo: '',
          TradeSha: '',
          Version: ''
        }
      }
    }
  }
}
</script>
<style lang="scss">
@import'../scss/CatInfo.scss';
</style>
