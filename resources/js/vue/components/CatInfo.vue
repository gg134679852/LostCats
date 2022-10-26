<template>
  <loading-icon :active="isLoading" />
  <div class="detailPage__wrap">
    <div class="detailPage__catInfo">
      <div class="detailPage__catInfo__img">
        <img class="rounded-3" :src="showCatData.album_file"
          onerror="this.src='https://via.placeholder.com/200x200?text=NO+IMAGE'" />
        <div class="detailPage__catInfo__buttonGroup">
          <button type="button" class="btn btn-success" @click="changeModal"
            v-if="this.$store.state.isAuthenticated">捐款</button>
          <template v-if="this.$store.state.isAuthenticated">
            <button type="button" class="btn btn-like" @click="addFavorite(showCatData.id)"
              v-if="isFavorite(showCatData.id) === false">收藏</button>
            <button type="button" class="btn btn-like" @click="removeFavorite(showCatData.id)" v-else>已收藏</button>
          </template>
        </div>
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
      </div>
    </div>
    <div class="detailPage__catInfo__content__text__remark">
      <h3>註記:</h3>
      <h2>{{ showCatData.animal_remark }}</h2>
    </div>
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
    <div class="detailPage__info__map">
      <GoogleMap :shelter-data="showCatData.shelter" :screen-size="screenSize" />
    </div>
  </div>
</template>
<script>
import GoogleMap from './GoogleMap.vue'
import { Toast } from '../utils/helpers'
export default {
  components: {
    GoogleMap
  },
  data () {
    return ({
      showCatData: {},
      screenSize: '',
      isLoading: false
    })
  },
  created () {
    this.fetchAnimalData()
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
      return this.$store.state.favoriteCats.includes(id)
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
    }
  }
}
</script>
<style lang="scss">
@import'../scss/DetailPage.scss';
</style>
