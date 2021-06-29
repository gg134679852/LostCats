<template>
<div class="container">
  <div class="row">
  <div class="card border-secondary mb-3 m-auto mt-3" style="width: 18rem;" v-for="catInfoData in catInfoDatas" :key="catInfoData.id">
  <img :src="catInfoData.album_file" class="card-img-top pt-2" width="200px" height="200px">
  <div class="card-body  text-secondary">
    <h4 class="card-text"><i class="fas fa-paw"></i>性別:{{catInfoData.animal_sex}}</h4>
    <h4 class="card-text"><i class="fas fa-paw"></i>體型:{{catInfoData.animal_bodytype}}</h4>
    <h4 class="card-text"><i class="fas fa-paw"></i>所在地:{{catInfoData.shelter_address.slice(0,3)}}</h4>
    <a href="#" class="btn btn-primary">Go somewhere</a>
  </div>
  </div>
  </div>
 </div>
</template>
<script>
export default {
  props: {
    catInfoDatas: {
      type: Array,
      required: true
    }
  }
}
</script>