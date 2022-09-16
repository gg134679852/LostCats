<template>
<div class="animalCard__container">
  <div
    class="animalCard__wrapper"
    v-for="catInfoData in catInfoData"
    :key="catInfoData.id"
    @click.stop.prevent="clickInfoButton(catInfoData.id)"
  >
  <button type="button" class="animalCard__info-button" data-bs-toggle="modal" data-bs-target="#AnimalDetailModal"
  :id="catInfoData.id"
       @click.stop.prevent="getAnimalId(catInfoData.id,catInfoData.shelter_name)"
    ></button>
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
    <h4 class="card-text"><i class="fas fa-paw"></i>所在地:{{catInfoData.shelter_address.slice(0,3)}}</h4>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import { mapState } from 'vuex'
import { Toast } from './../utils/helpers'
export default {
  props: {
    catInfoData: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      favoriteId: []
    }
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
    getAnimalId (id, address) {
      this.$emit('get-Animal-Id', id, address)
    },
    addFavorite (id) {
      if (this.favoriteId.length === 16) {
        Toast.fire({
          icon: 'warning',
          title: '達到收藏上限'
        })
      } else {
        this.favoriteId.push(id)
        this.$emit('get-Favorite-Cat-Id', id)
      }
    },
    removeFavorite (id) {
      const target = this.favoriteId.indexOf(id)
      this.favoriteId.splice(target, 1)
      this.$emit('get-Remove-Favorite-Cat-Id', id)
    },
    isFavorite (id) {
      return this.favoriteId.includes(id)
    },
    clickInfoButton (id) {
      document.querySelector(`.animalCard__info-button[id="${id}"]`).click()
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
