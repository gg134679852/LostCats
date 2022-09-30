<template>
<div class="animalCard__container">
  <div
    class="animalCard__wrapper"
    v-for="catInfoData in catInfoData"
    :key="catInfoData.id"
    @click.stop.prevent="clickCard(catInfoData.animal_id,catInfoData.shelter_id)"
  >
  <template v-if="isAuthenticated">
    <button class="animalCard__like-button"  @click.stop.prevent="addFavorite(catInfoData.id)"
     v-if="!isFavorite(catInfoData.id)"><i class="far fa-heart"></i></button>

     <button class="animalCard__like-button" @click.stop.prevent="removeFavorite(catInfoData.id)"
  v-else><i class="fas fa-heart"></i></button>

     </template>
    <div class="animalCard__main">
      <img
        class="animalCard__img"
        :src="catInfoData.album_file"
        onerror="this.src='https://via.placeholder.com/200x200?text=NO+IMAGE'"
      />
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
</template>
<script>
import { mapState } from 'vuex'
export default {
  props: {
    catInfoData: {
      type: Array,
      required: true
    }
  },
  inject: ['Toast'],
  data () {
    return {
      favoriteId: []
    }
  },
  created () {
    this.fetchFavoriteCats()
  },
  methods: {
    clickCard (catDataId, shelterDataId) {
      this.$emit('switcher', 'catInfo', catDataId, shelterDataId)
    },
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
        this.favoriteId.push(id)
        this.$axiosHelper
          .post(`${id}/addFavorite`)
          .then(() => {
            this.Toast.fire({
              icon: 'success',
              title: '成功加入最愛'
            })
          })
          .catch((error) => {
            this.Toast.fire({
              icon: 'warning',
              title: '發生錯誤 請稍後在試'
            })
            console.log(error)
          })
      }
    },
    removeFavorite (id) {
      const target = this.favoriteId.indexOf(id)
      this.favoriteId.splice(target, 1)
      this.$axiosHelper
        .delete(`${id}/removeFavorite`)
        .then(() => {
          this.Toast.fire({
            icon: 'success',
            title: '成功移除最愛'
          })
        })
        .catch((error) => {
          this.Toast.fire({
            icon: 'warning',
            title: '發生錯誤 請稍後在試'
          })
          console.log(error)
        })
    },
    isFavorite (id) {
      return this.favoriteId.includes(id)
    }
  },
  watch: {
    favoriteCats: function () {
      if (this.isAuthenticated) {
        this.favoriteId = []

        this.favoriteCats.forEach((data) => {
          this.favoriteId.push(data.id)
        })
      }
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
