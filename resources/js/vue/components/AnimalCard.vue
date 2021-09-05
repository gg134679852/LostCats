<template>
  <div class="row row-cols-2 row-cols-lg-5 g-2 g-lg-3">
  <div class="card border-secondary mb-3 m-auto" style="width: 18rem;" v-for="catInfoData in catInfoDatas" :key="catInfoData.id">
  <img :src="catInfoData.album_file" class="card-img-top rounded-3 pt-2" width="200px" height="200px" onerror="this.src='https://via.placeholder.com/200x200?text=NO+IMAGE'">
  <div class="card-body  text-secondary">
    <h4 class="card-text">
      <i class="fas fa-paw"></i>性別:{{catInfoData.animal_sex}}</h4>
    <h4 class="card-text"><i class="fas fa-paw"></i>所在地:{{catInfoData.shelter_address.slice(0,3)}}</h4>
  <template v-if="isAuthenticated">
     <button type="button" class="btn btn-primary"
     @click.stop.prevent="addFavorite(catInfoData.id)"
     v-if="!isFavorite(catInfoData.id)"
    >
  加入最愛
</button>
  <button type="button" class="btn btn-primary" 
  @click.stop.prevent="removeFavorite(catInfoData.id)"
  v-else>
  移除最愛
</button>
</template>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#AnimalDetailModal"
       @click.stop.prevent="getAnimalId(catInfoData.id,catInfoData.shelter_name)"
    >
  詳細資料
</button>
    </div>
   </div>
   </div>
</template>
<script>
import { mapState } from 'vuex'
import {Toast} from './../utils/helpers'
export default {
  props: {
    catInfoDatas: {
      type: Array,
      required: true
    }
  },
  data(){
    return({
      favoriteId:[]
    })
  },
  created(){
      this.fetchFavoriteCats()
   },
  methods: {
    fetchFavoriteCats(){
      if(this.isAuthenticated){
        if(this.favoriteId.length === 0){
        this.favoriteCats.forEach((data)=>{
        this.favoriteId.push(data.id)
      })
       }
      }
    },
   getAnimalId(id,address){
      this.$emit('get-Animal-Id', id,address)
    },
    addFavorite(id){
      if(this.favoriteId.length === 16){
         Toast.fire({
          icon: 'warning',
          title:"達到收藏上限"
        })
      }else{
        this.favoriteId.push(id)
      this.$emit('get-Favorite-Cat-Id', id)
      }
    },
    removeFavorite(id){
      const target = this.favoriteId.indexOf(id)
      this.favoriteId.splice(target,1)
      this.$emit('get-Remove-Favorite-Cat-Id', id)
    },
    isFavorite(id){
      return this.favoriteId.includes(id)
    }
  },
  watch:{
    favoriteCats: function () {
      if(this.isAuthenticated){

        this.favoriteId = []

        this.favoriteCats.forEach((data)=>{
        this.favoriteId.push(data.id)
       })
      }
    }
  },
  computed:{
    ...mapState(['favoriteCats','isAuthenticated'])
  }
}
</script>
