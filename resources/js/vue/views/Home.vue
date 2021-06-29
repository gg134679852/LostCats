<template>
<div>
<div>
  <AnimalCard :catInfoDatas = catDatas.data />
   </div>
  <Pagination :paginationLinks = catDatas.links :paginationMeta = catDatas.meta
  @get-pagination-url="getPaginationUrl"
   />
</div>
</template>
 <script>
 import axios from 'axios'
 import AnimalCard from './../components/AnimalCard.vue'
 import Pagination from './../components/Pagination'
 import {apiHelper} from './../utils/helpers'

 export default {
   components:{
     AnimalCard,
     Pagination
   },
   created(){
     this.fetchAnimalData()
   },
   data(){
     return{
       catDatas:{}
     }
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
    }
  }
 }
 </script>