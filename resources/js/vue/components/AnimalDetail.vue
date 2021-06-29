<template>
  <div class="modal fade" id="AnimalDetailModal" tabindex="-1" aria-labelledby="AnimalDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">喵星人詳細資料</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex">
          <div class="p-auto">
          <img class="rounded-3" :src="catInfoData.album_file" width="250px" height="250px">
          </div>
          <div class="ml-5">
            <ul class="list-group">
              <li class="list-group-item">
               <h6>編號:{{catInfoData.animal_id}}</h6>
             </li>
              <li class="list-group-item">
               <h6>性別:{{catInfoData.animal_sex}}</h6>
             </li>
             <li class="list-group-item">
               <h6>歲數:{{catInfoData.animal_age}}</h6>
             </li>
             <li class="list-group-item">
               <h6>體型:{{catInfoData.animal_bodytype}}</h6>
             </li>
              <li class="list-group-item">
               <h6>顏色:{{catInfoData.animal_colour}}</h6>
             </li>
              <li class="list-group-item">
               <h6>{{catInfoData.animal_sterilization}},{{catInfoData.animal_bacterin}}</h6>
             </li>
             <li class="list-group-item">
               <h6>收容所名稱:{{catInfoData.shelter_name}}</h6>
             </li>
             <li class="list-group-item">
               <h6>地址:{{catInfoData.shelter_address}}</h6>
             </li>
             <li class="list-group-item">
               <h6>電話:{{catInfoData.shelter_tel}}</h6>
             </li>
            </ul>
          </div>
        </div>
      </div>
  <GmapMap
  :center="{lat:catInfoData.address.lat,lng:catInfoData.address.lng}" 
  :zoom="40"
  map-type-id="terrain"
  style="width: 500px; height: 300px"
>
  <GmapMarker
    :position="{lat:catInfoData.address.lat,lng:catInfoData.address.lng}"
  />
</GmapMap>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
        <button type="button" class="btn btn-primary">追蹤</button>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
  props: {
    catInfoData: {
      type: Object,
      required: true
    }
  }
}
</script>