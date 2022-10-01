<template>
  <NavBar />
  <div class="container">
    <div class="userPage__userCard">
      <img src="../../../../public/img/avatar.png" />
      <div class="userInfo">
        <h5>{{ this.currentUser.name }}</h5>
        <h5>{{ this.currentUser.email }}</h5>
      </div>
    </div>
    <div class="userPage__dropdown" v-if="screenSize === 'Small'">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
    頁面選項
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <li><a class="dropdown-item" href="#" @click.prevent="switchComponent('CatCard')">最愛喵星人</a></li>
    <li><a class="dropdown-item" href="#">捐款紀錄</a></li>
    <li><a class="dropdown-item" href="#">會員資料</a></li>
  </ul>
    </div>
     <div class="userPage__navTab" v-if="screenSize === 'Middle' || screenSize === 'Big'">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true" @click.prevent="switchComponent('CatCard')">最愛喵星人</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">捐款紀錄</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">會員資料</button>
          </li>
        </ul>
   </div>
  <div class="userPage__components__warp">
    <div class="userPage__components__CatCard" v-if="currenComponent === 'CatCard'">
     <CatCard
     :cat-info-data="catInfoData"
     @switcher="Switcher"
     />
    </div>
   </div>
    <CatInfoModal
    :cat-info-modal-switcher="catInfoModalSwitcher"
    :show-cat-data="showCatData"
    :screen-size="screenSize"
    @switcher="Switcher"
  />
  </div>
</template>
<script>
import CatCard from './../components/CatCard.vue'
import CatInfoModal from '../components/CatInfoModal.vue'
import NavBar from '../components/NavBar.vue'
import { Toast } from './../utils/helpers'
import { mapState } from 'vuex'
const getToken = () => localStorage.getItem('token')

export default {
  components: {
    NavBar,
    CatCard,
    CatInfoModal
  },
  data () {
    return {
      catInfoData: [],
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
      currenComponent: 'CatCard',
      screenSize: '',
      catInfoModalSwitcher: 'hide'
    }
  },
  created () {
    this.copyFavoriteCats()
    this.screenRuler()
  },
  mounted () {
    window.onresize = () => {
      this.screenRuler()
    }
  },
  methods: {
    copyFavoriteCats () {
      this.catInfoData = [...this.favoriteCats]
    },
    getRemoveFavoriteCatId (id) {
      this.catInfoData = this.catInfoData.filter((data) => data.id !== id)

      this.$axiosHelper
        .delete(`${id}/removeFavorite`, {
          headers: { Authorization: `Bearer ${getToken()}` }
        })
        .then(() => {
          Toast.fire({
            icon: 'success',
            title: '成功移除最愛'
          })
        })
        .catch((error) => {
          Toast.fire({
            icon: 'warning',
            title: '發生錯誤 請稍後在試'
          })
          console.log(error)
        })
    },
    fetchAnimalDetailData (id, address) {
      this.modalIsLoading = true
      this.$axiosHelper.get(`api/animalData/${id}/${address}/detail`).then((obj) => {
        this.catData = {
          ...this.catData,
          ...obj.data[0]
        }
        this.catData.address = {
          ...this.catData.address,
          ...obj.data[1].candidates[0].geometry.location
        }
        this.modalIsLoading = false
      })
    },
    switchComponent (type) {
      switch (type) {
        case 'CatCard':{
          this.currenComponent = 'CatCard'
          break
        }
      }
    },
    screenRuler () {
      switch (true) {
        case window.innerWidth <= 767:{
          this.screenSize = 'Small'
          break
        }
        case window.innerWidth >= 992:{
          this.screenSize = 'Big'
          break
        }
        case window.innerWidth >= 768:{
          this.screenSize = 'Middle'
          break
        }
      }
    },
    Switcher (type, catDataId, shelterDataId) {
      if (catDataId !== 'none' && shelterDataId !== 'none') {
        const catDataIndex = this.catInfoData.findIndex((obj) => obj.animal_id === catDataId)
        const targetData = { ...this.catInfoData[catDataIndex], shelterData: { ...this.catInfoData[catDataIndex].shelter } }
        targetData.shelterData.shelter_lat = Number(targetData.shelterData.shelter_lat)
        targetData.shelterData.shelter_lng = Number(targetData.shelterData.shelter_lng)
        this.showCatData = targetData
      }
      switch (type) {
        case 'catInfo':{
          this.catInfoModalSwitcher === 'hide'
            ? this.catInfoModalSwitcher = 'show'
            : this.catInfoModalSwitcher = 'hide'
          break
        }
      }
    }
  },
  watch: {
    favoriteCats: function () {
      this.catInfoData = [...this.favoriteCats]
    }
  },
  computed: {
    ...mapState(['isAuthenticated', 'currentUser', 'favoriteCats'])
  }
}
</script>
<style lang="sass">
@import '../scss/UserPage.scss'
</style>
