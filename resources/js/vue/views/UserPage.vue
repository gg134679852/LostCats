<template>
<div class="container">
  <div class="userCard">
    <img src="/img/avatar.png" width="200px" height="200px">
    <div class="userInfo">
      <h5>{{this.currentUser.name}}</h5>
      <h5>{{this.currentUser.email}}</h5>
    </div>
  </div>
  <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" aria-current="page" href="#">最愛喵星人</a>
  </li>
</ul>
<div class="mt-5">
 <AnimalCard :catInfoDatas = this.catDatas
  @get-Animal-Id="fetchAnimalDetailData"
  @get-Remove-Favorite-Cat-Id="getRemoveFavoriteCatId"
  />
  </div>
 <div class="modal fade" id="AnimalDetailModal" tabindex="-1" aria-labelledby="AnimalDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">喵星人詳細資料</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <Spinner v-if="modalIsLoading"/>
      <div class="modal-body" v-else>
        <div class="d-flex">
          <div class="p-auto">
          <img class="rounded-3" :src="catData.album_file" width="250px" height="250px" onerror="this.src='https://cel.ac/wp-content/uploads/2016/02/placeholder-img-1.jpg'">
          </div>
          <div class="ml-5">
            <ul class="list-group">
              <li class="list-group-item">
               <h6>編號:{{catData.animal_id}}</h6>
             </li>
              <li class="list-group-item">
               <h6>性別:{{catData.animal_sex}}</h6>
             </li>
             <li class="list-group-item">
               <h6>歲數:{{catData.animal_age}}</h6>
             </li>
             <li class="list-group-item">
               <h6>體型:{{catData.animal_bodytype}}</h6>
             </li>
              <li class="list-group-item">
               <h6>顏色:{{catData.animal_colour}}</h6>
             </li>
              <li class="list-group-item">
               <h6>{{catData.animal_sterilization}},{{catData.animal_bacterin}}</h6>
             </li>
             <li class="list-group-item">
               <h6>收容所名稱:{{catData.shelter_name}}</h6>
             </li>
             <li class="list-group-item">
               <h6>地址:{{catData.shelter_address}}</h6>
             </li>
             <li class="list-group-item">
               <h6>電話:{{catData.shelter_tel}}</h6>
             </li>
            </ul>
          </div>
        </div>
         <GmapMap
  :center="{lat:catData.address.lat,lng:catData.address.lng}" 
  :zoom="40"
  :options="{
   zoomControl: true,
   mapTypeControl: false,
   scaleControl: false,
   streetViewControl: false,
   rotateControl: false,
   fullscreenControl: true,
   disableDefaultUI: false
 }"
  map-type-id="terrain"
  style="width: 500px; height: 300px"
>
  <GmapMarker
    :position="{lat:catData.address.lat,lng:catData.address.lng}"
  />
</GmapMap>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
      </div>
    </div>
  </div>
</div>
</div>
</template>
<script>
import AnimalCard from './../components/AnimalCard'
import Spinner from './../components/Spinner'
import {apiHelper,Toast} from './../utils/helpers'
import { mapState } from 'vuex'
const getToken = () => localStorage.getItem('token')

export default {
   components:{
     AnimalCard,
      Spinner
   },
   data(){
     return{
       catDatas:[],
       catData:{
         id:-1,
         animal_id: -1,
         album_file:'',
         animal_age:'',
         animal_bacterin:'',
         animal_bodytype:'',
         animal_colour:'',
         animal_place:'',
         animal_remark:'',
         animal_sex:'',
         animal_sterilization:'',
         shelter_address:'',
         shelter_name:'',
         shelter_tel:'',
         address:{
           lat:-1,
           lng:-1
         },
       },
        modalIsLoading: true,
     }
   },
   created(){
      this.copyFavoriteCats()
   },
   methods: {
     copyFavoriteCats(){
      this.catDatas = [...this.favoriteCats]
     },
  getRemoveFavoriteCatId(id){
    
    this.catDatas = this.catDatas.filter((data)=> data.id !== id)

    apiHelper.delete(`${id}/removeFavorite`,{
      headers: { Authorization: `Bearer ${getToken()}`
      }})
      .then(()=>{
        Toast.fire({
          icon: 'success',
          title:'成功移除最愛'
        })
      })
      .catch((error)=>{
        console.log(error)
      })
    },
     fetchAnimalDetailData (id,address) {
      this.modalIsLoading = true
     apiHelper.get(`api/animalData/${id}/${address}/detail`)
     .then((obj)=>{
       this.catData ={
         ...this.catData,
         ...obj.data[0] 
       } 
       this.catData.address ={
         ...this.catData.address,
        ...obj.data[1].candidates[0].geometry.location
       }
       this.modalIsLoading = false
     })
    }
   },
   watch:{
     favoriteCats:function () {
       this.catDatas = [...this.favoriteCats]
     }
   },
   computed:{
    ...mapState(['isAuthenticated','currentUser','favoriteCats'])
  }
}
</script>
<style lang="sass">
@import '../scss/UserPage.scss'
</style>