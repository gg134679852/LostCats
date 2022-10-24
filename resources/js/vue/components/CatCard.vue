<template>
<div class="animalCard__container">
  <div
    class="animalCard__wrapper"
    v-for="catInfoData in catInfoData"
    :key="catInfoData.id"
    @click.stop.prevent="switcher(catInfoData.animal_id,catInfoData.shelter_id)"
  >
  <template v-if="isAuthenticated">
    <button class="animalCard__like-button"  @click.stop.prevent="addFavorite(catInfoData.id)"
     v-if="!isFavorite(catInfoData.id)"><i class="far fa-heart"></i></button>

     <button class="animalCard__like-button" @click.stop.prevent="removeFavorite(catInfoData.id)"
  v-else><i class="fas fa-heart"></i></button>

     </template>
    <div class="animalCard__main">
      <div class="animalCard__img">
        <img
          :src="catInfoData.album_file === null ? 'https://via.placeholder.com/200x200?text=NO+IMAGE':catInfoData.album_file" />
      </div>
      <div class="animalCard__body">
        <div class="animalCard__info text-secondary">
          <h4 class="card-text">
      <i class="fas fa-paw"></i>性別:{{catInfoData.animal_sex}}</h4>
    <h4 class="card-text"><i class="fas fa-paw"></i>毛色:{{catInfoData.animal_color}}</h4>
        </div>
      </div>
    </div>
  </div>
</div>
<CatInfoModal
  :show-cat-data="showCatData"
  :screen-size="screenSize"
  :cat-info-modal-switcher="catInfoModalSwitcher"
  :favorite-id="favoriteId"
  :donate-info="donateInfo"
  :trade-data="tradeData"
  :modal-type="modalType"
  @add-favorite="addFavorite"
  @remove-favorite="removeFavorite"
  @switcher="switcher"
  @send-donate="sendDonate"
  @enter-form-data="enterFormData"
  @change-modal="changeModal"
/>
</template>
<script>
import { mapState } from 'vuex'
import CatInfoModal from './CatInfoModal.vue'
export default {
  props: {
    catInfoData: {
      type: Array,
      required: true
    },
    screenSize: {
      type: String,
      required: true
    }
  },
  emits: ['loadingSwitcher'],
  components: {
    CatInfoModal
  },
  inject: ['Toast'],
  data () {
    return ({
      favoriteId: [],
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
        shelterData: {
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
      catInfoModalSwitcher: 'hide',
      modalType: 'catInfoModal'
    })
  },
  created () {
    this.fetchFavoriteCats()
  },
  methods: {
    fetchFavoriteCats () {
      if (this.isAuthenticated) {
        if (this.favoriteId.length === 0) {
          this.favoriteCats.forEach((data) => {
            this.favoriteId.push(data.id)
          })
        }
      }
    },
    addFavorite (id) {
      if (this.favoriteId.length === 16) {
        this.Toast.fire({
          icon: 'warning',
          title: '達到收藏上限'
        })
      } else {
        this.$emit('loadingSwitcher')
        this.favoriteId.push(id)
        this.$axiosHelper
          .post(`user/addFavorite/${id}`)
          .then(() => {
            this.$store.dispatch('fetchCurrentUser')
            this.Toast.fire({
              icon: 'success',
              title: '成功加入最愛'
            })
            this.$emit('loadingSwitcher')
          })
          .catch((error) => {
            this.Toast.fire({
              icon: 'warning',
              title: '發生錯誤 請稍後在試'
            })
            console.log(error)
            this.$emit('loadingSwitcher')
          })
      }
    },
    removeFavorite (id) {
      this.$emit('loadingSwitcher')
      const target = this.favoriteId.indexOf(id)
      this.favoriteId.splice(target, 1)
      this.$axiosHelper
        .delete(`user/removeFavorite/${id}`)
        .then(() => {
          this.$store.dispatch('fetchCurrentUser')
          this.Toast.fire({
            icon: 'success',
            title: '成功移除最愛'
          })
          this.$emit('loadingSwitcher')
        })
        .catch((error) => {
          this.Toast.fire({
            icon: 'warning',
            title: '發生錯誤 請稍後在試'
          })
          console.log(error)
          this.$emit('loadingSwitcher')
        })
    },
    isFavorite (id) {
      return this.favoriteId.includes(id)
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
    },
    enterFormData (e) {
      const key = e.target.name
      const value = e.target.value
      this.donateInfo[key] = value
    },
    switcher (catDataId, shelterDataId) {
      if (catDataId !== 'none' && shelterDataId !== 'none') {
        const catDataIndex = this.catInfoData.findIndex((obj) => obj.animal_id === catDataId)
        const targetData = { ...this.catInfoData[catDataIndex], shelterData: { ...this.catInfoData[catDataIndex].shelter } }
        targetData.shelterData.shelter_lat = Number(targetData.shelterData.shelter_lat)
        targetData.shelterData.shelter_lng = Number(targetData.shelterData.shelter_lng)
        this.showCatData = targetData
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
        this.modalType = 'catInfoModal'
      }
      this.catInfoModalSwitcher === 'hide'
        ? this.catInfoModalSwitcher = 'show'
        : this.catInfoModalSwitcher = 'hide'
    },
    changeModal () {
      this.modalType === 'catInfoModal' ? this.modalType = 'donateInfoModal' : this.modalType = 'catInfoModal'
    }
  },
  computed: {
    ...mapState(['favoriteCats', 'isAuthenticated'])
  }
}
</script>
<style lang="sass">
@import '../scss/CatCard.scss'
</style>
