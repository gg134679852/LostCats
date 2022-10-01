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
    <div class="userPage__dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
    頁面選項
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
    <li><a class="dropdown-item" href="#" @click.prevent="switchComponent('CatCard')">最愛喵星人</a></li>
    <!-- <li><a class="dropdown-item" href="#">Another action</a></li>
    <li><a class="dropdown-item" href="#">Something else here</a></li> -->
  </ul>
    </div>
  <div class="userPage__components__warp">
    <div class="userPage__components__CatCard" v-if="currenComponent === 'CatCard'">
     <CatCard :cat-info-data="catInfoData"/>
    </div>
   </div>
  </div>
  <CatInfoModal />
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
      showData: {
        id: -1,
        animal_id: -1,
        album_file: '',
        animal_age: '',
        animal_bacterin: '',
        animal_bodytype: '',
        animal_colour: '',
        animal_place: '',
        animal_remark: '',
        animal_sex: '',
        animal_sterilization: '',
        shelter_address: '',
        shelter_name: '',
        shelter_tel: '',
        address: {
          lat: -1,
          lng: -1
        }
      },
      currenComponent: '',
      catInfoModalSwitcher: 'hide'
    }
  },
  created () {
    this.copyFavoriteCats()
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
    }
  },
  watch: {
    favoriteCats: function () {
      this.catDatas = [...this.favoriteCats]
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
