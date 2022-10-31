<template>
  <loading-icon :active="isLoading" />
  <div class="container">
    <div class="userPage__container__wrap">
      <div class="userPage__userCard">
        <img src="../../../../public/img/avatar.png" />
        <div class="userInfo">
          <h5>{{ this.currentUser.name }}</h5>
          <h5>{{ this.currentUser.email }}</h5>
        </div>
      </div>
      <div class="userPage__dropdown">
        <div class="userPage__dropdown__wrap">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
            data-bs-toggle="dropdown" aria-expanded="false">
            頁面選項
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item" href="#" @click.prevent="switchComponent('CatCard')">最愛喵星人</a></li>
            <li><a class="dropdown-item" href="#" @click.prevent="switchComponent('DonateLog')">捐款紀錄</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="userPage__components__warp">
      <div class="userPage__components__CatCard" v-if="currenComponent === 'CatCard'">
        <div class="userPage__components__CatCard__message" v-if="catInfoData.length === 0">
          <h2>尚無任何收藏</h2>
        </div>
        <template v-else>
          <CatCard :cat-info-data="catInfoData" :screen-size="screenSize" @loading-switcher="loadingSwitcher" />
        </template>
      </div>
      <div class="userPage__components__DonateLog" v-if="currenComponent === 'DonateLog'">
        <div class="userPage__components_DonateLog__message" v-if="donateLogData.length === 0">
          <h2>尚無任何紀錄</h2>
        </div>
        <template v-else>
          <DonateLog :donate-log-data="donateLogData" :screen-size="screenSize" />
        </template>
      </div>
    </div>
  </div>
</template>
<script>
import CatCard from './../components/CatCard.vue'
import DonateLog from '../components/DonateLog.vue'
import { mapState } from 'vuex'

export default {
  components: {
    CatCard,
    DonateLog
  },
  data () {
    return {
      catInfoData: [],
      donateLogData: [],
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
      isLoading: false
    }
  },
  created () {
    this.loadingSwitcher()
    this.copyFavoriteCats()
    this.fetchDonateLog()
    this.screenRuler()
    this.loadingSwitcher()
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
    fetchDonateLog () {
      this.$axiosHelper.get(`user/donateLogData?userId=${this.currentUser.id}`).then((obj) => {
        this.donateLogData = obj.data.donateLog.data
      })
    },
    switchComponent (type) {
      switch (type) {
        case 'CatCard': {
          this.currenComponent = 'CatCard'
          break
        }
        case 'DonateLog': {
          this.currenComponent = 'DonateLog'
          break
        }
      }
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
    loadingSwitcher () {
      this.isLoading === false ? this.isLoading = true : this.isLoading = false
    }
  },
  computed: {
    ...mapState(['isAuthenticated', 'currentUser', 'favoriteCats'])
  },
  watch: {
    favoriteCats: function () {
      this.catInfoData = [...this.favoriteCats]
    }
  }
}
</script>
<style lang="sass">
@import '../scss/UserPage.scss'
</style>
