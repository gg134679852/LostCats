<template>
  <div class="container">
    <div class="userCard">
      <img src="../../../../public/img/avatar.png" />
      <div class="userInfo">
        <h5>{{ this.currentUser.name }}</h5>
        <h5>{{ this.currentUser.email }}</h5>
      </div>
    </div>
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="#">最愛喵星人</a>
      </li>
    </ul>
    <!-- <div class="mt-5 text-center">
      <AnimalCard
        :catInfoDatas="this.catDatas"
        @get-Animal-Id="fetchAnimalDetailData"
        @get-Remove-Favorite-Cat-Id="getRemoveFavoriteCatId"
      />
    </div> -->
  </div>
</template>
<script>
// import CatCard from './../components/CatCard.vue'
import { Toast } from './../utils/helpers'
import { mapState } from 'vuex'
const getToken = () => localStorage.getItem('token')

export default {
  components: {
    // CatCard
  },
  data () {
    return {
      catDatas: [],
      catData: {
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
      modalIsLoading: true
    }
  },
  created () {
    this.copyFavoriteCats()
  },
  methods: {
    copyFavoriteCats () {
      this.catDatas = [...this.favoriteCats]
    },
    getRemoveFavoriteCatId (id) {
      this.catDatas = this.catDatas.filter((data) => data.id !== id)

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
