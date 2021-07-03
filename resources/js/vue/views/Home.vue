<template>
<div class ="mt-4">
  <div class="container">
  <AnimalCard :catInfoDatas = catDatas.data
  @get-Animal-Id="fetchAnimalDetailData"
  />
  <Pagination :paginationLinks = catDatas.links :paginationMeta = catDatas.meta
  @get-pagination-url="getPaginationUrl"
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
        <button type="button" class="btn btn-primary">追蹤</button>
      </div>
    </div>
  </div>
</div>
  </div>
</template>
 <script>
 import axios from 'axios'
 import AnimalCard from './../components/AnimalCard'
 import Pagination from './../components/Pagination'
 import Spinner from './../components/Spinner'
 import {apiHelper} from './../utils/helpers'
 
 export default {
   components:{
     AnimalCard,
     Pagination,
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
         }
       },
       modalIsLoading: true,
       homeIsLoading: true
     }
   },
   created(){
     this.fetchAnimalData()
   },
   methods: {
    fetchAnimalData () {
      this.homeIsLoading = true
     apiHelper.get('api/animalData')
     .then((obj)=>{
       this.catDatas = obj.data
     })
     this.homeIsLoading = false
    },
    getPaginationUrl (url) {
      axios.get(url)
     .then((obj)=>{
       this.catDatas = obj.data
     })
    },
    fetchAnimalDetailData (id,address) {
      this.modalIsLoading = true
     apiHelper.get(`api/animalData/${id}/${address}/detail`)
     .then((obj)=>{
       console.log(obj)
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
  }
 }
 </script>