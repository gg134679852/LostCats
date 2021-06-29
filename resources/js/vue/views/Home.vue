<template>
<div class="container">
  <AnimalCard :catInfoDatas = catDatas.data 
  @get-Animal-Id="fetchAnimalDetailData"
  />
   <AnimalDetail :catInfoData= catData />
  <Pagination :paginationLinks = catDatas.links :paginationMeta = catDatas.meta
  @get-pagination-url="getPaginationUrl"
   />
</div>
</template>
 <script>
 import axios from 'axios'
 import AnimalCard from './../components/AnimalCard'
 import Pagination from './../components/Pagination'
 import AnimalDetail from './../components/AnimalDetail'
 import {apiHelper} from './../utils/helpers'
 
 export default {
   components:{
     AnimalCard,
     Pagination,
     AnimalDetail
   },
   data(){
     return{
       catDatas:[],
       catData:{}
     }
   },
   created(){
     this.fetchAnimalData()
   },
   methods: {
    fetchAnimalData () {
     apiHelper.get('/animalData')
     .then((obj)=>{
       this.catDatas = obj.data
     })
    },
    getPaginationUrl (url) {
      axios.get(url)
     .then((obj)=>{
       this.catDatas = obj.data
     })
    },
    fetchAnimalDetailData (id,address) {
     apiHelper.get(`/animalData/${id}/${address}/detail`)
     .then((obj)=>{
       this.catData = obj.data[0]
       this.catData.address = obj.data[1].candidates[0].geometry.location
     })
    }
  }
 }
 </script>