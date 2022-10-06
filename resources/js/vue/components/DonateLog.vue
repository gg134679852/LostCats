<template>
<div class="DonateLog__table__warp" v-if="screenSize === 'Middle' || screenSize === 'Big'">
   <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">款項編號</th>
      <th scope="col">金額</th>
      <th scope="col">收容所名稱</th>
      <th scope="col">捐款時間</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="(item,index) in donateLogData" :key="index">
      <th scope="row">{{index + 1}}</th>
      <td>{{item.order_Id}}</td>
      <td>{{item.donate_price}}</td>
      <td>{{item.shelter_name}}</td>
      <td>{{item.updated_at.slice(0,10)}}</td>
    </tr>
  </tbody>
</table>
</div>
<div class="DonateLog__list-group__warp" v-else>
  <ul class="list-group" v-for="(item,index) in donateLogData" :key="index">
    <li class="list-group-item list-group-item-secondary">
      <label>款項編號</label>
      <h2>{{item.order_Id}}</h2>
    </li>
   <li class="list-group-item">
      <label>金額</label>
      <h2>{{item.donate_price}}</h2>
    </li>
   <li class="list-group-item">
      <label>收容所名稱</label>
      <h2>{{item.shelter_name}}</h2>
    </li>
   <li class="list-group-item">
      <label>捐款時間</label>
      <h2>{{item.updated_at.slice(0,10)}}</h2>
    </li>
  </ul>
</div>
</template>
<script>
export default {
  props: {
    donateLogData: {
      type: Array,
      required: true
    },
    screenSize: {
      type: String,
      required: true
    }
  }
}
</script>
<style lang="scss">
  @import '../scss/DonateLog.scss';
</style>
