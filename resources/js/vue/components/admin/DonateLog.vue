<template>
  <div class="DonateLog__table__warp">
    <div class="dropdown mt-2 mb-2">
      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
        aria-expanded="false">
        資料選項
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="#" @click.prevent="componentSwitcher('CatList')">貓咪清單</a></li>
        <li><a class="dropdown-item" href="#" @click.prevent="componentSwitcher('ShelterList')">收容所清單</a></li>
        <li><a class="dropdown-item" href="#" @click.prevent="componentSwitcher('DonateLog')">捐獻紀錄</a></li>
      </ul>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">款項編號</th>
          <th scope="col">使用者編號</th>
          <th scope="col">金額</th>
          <th scope="col">收容所名稱</th>
          <th scope="col">捐款時間</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item,index) in donateLogData.data" :key="index">
          <td>{{item.order_Id}}</td>
          <td>{{item.user_id}}</td>
          <td>{{item.donate_price}}</td>
          <td>{{item.shelter_name}}</td>
          <td>{{item.updated_at.slice(0,10)}}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <PaginationComponent
  :screen-size="screenSize"
  :paginationLinks="donateLogData.paginationLinks"
  @fetch-animal-data="fetchAnimalData"
  />
</template>
<script>
import PaginationComponent from '../PaginationComponent.vue'
export default {
  components: {
    PaginationComponent
  },
  props: {
    donateLogData: {
      type: Object,
      required: true
    },
    screenSize: {
      type: String,
      required: true
    }
  },
  emits: ['componentSwitcher', 'fetchAnimalData'],
  methods: {
    componentSwitcher (type) {
      this.$emit('componentSwitcher', type)
    }
  }
}
</script>
<style lang="scss">
@import '../../scss/DonateLog.scss';
</style>
