<template>
  <div class="animalCard__container">
    <div class="animalCard__wrapper" v-for="catInfoData in catInfoData" :key="catInfoData.id"
      @click.stop.prevent="clickCard(catInfoData.animal_id)">
      <template v-if="isAuthenticated">
        <button class="animalCard__like-button" @click.stop.prevent="addFavorite(catInfoData.id)"
          v-if="!isFavorite(catInfoData.id)"><i class="far fa-heart"></i></button>

        <button class="animalCard__like-button" @click.stop.prevent="removeFavorite(catInfoData.id)" v-else><i
            class="fas fa-heart"></i></button>

      </template>
      <div class="animalCard__main">
        <div class="animalCard__img">
          <img
            :src="catInfoData.album_file === null ? 'https://via.placeholder.com/200x200?text=NO+IMAGE' : catInfoData.album_file" />
        </div>
        <div class="animalCard__body">
          <div class="animalCard__info text-secondary">
            <h4 class="card-text">
              <i class="fas fa-paw"></i>性別:{{ catInfoData.animal_sex }}
            </h4>
            <h4 class="card-text"><i class="fas fa-paw"></i>毛色:{{ catInfoData.animal_color }}</h4>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapState } from 'vuex'
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
  inject: ['Toast'],
  data () {
    return ({
      favoriteId: []
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
    clickCard (id) {
      this.$router.push(`/catList/infoPage/${id}`)
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
