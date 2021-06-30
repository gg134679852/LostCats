<template>
  <div class="row row-cols-4">
  <div class="card border-secondary mb-3 m-auto" style="width: 18rem;" v-for="catInfoData in catInfoDatas" :key="catInfoData.id">
  <img :src="catInfoData.album_file" class="card-img-top rounded-3 pt-2" width="200px" height="200px" onerror="this.src='https://cel.ac/wp-content/uploads/2016/02/placeholder-img-1.jpg'">
  <div class="card-body  text-secondary">
    <h4 class="card-text">
      <i class="fas fa-paw"></i>性別:{{catInfoData.animal_sex}}</h4>
    <h4 class="card-text"><i class="fas fa-paw"></i>體型:{{catInfoData.animal_bodytype}}</h4>
    <h4 class="card-text"><i class="fas fa-paw"></i>所在地:{{catInfoData.shelter_address.slice(0,3)}}</h4>
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

export default {
  props: {
    catInfoDatas: {
      type: Array,
      required: true
    }
  },
  methods: {
   getAnimalId(id,address){
      this.$emit('get-Animal-Id', id,address)
    }
  }
}
</script>
