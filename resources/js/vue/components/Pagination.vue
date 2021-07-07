<template>
<div>
  <ul class="pagination">
    <li :class="['page-item', { active: pagination.active}]" v-for="(pagination ,index) in paginationMeta.links" :key="index">
      <button class="page-link" v-if="index === 0"
      @click.stop.prevent="paginationButtonClick(pagination.url)"
      :disabled ="pagination.url === null"
      >&laquo;</button>
      <button class="page-link"  v-if="index === paginationMeta.links.length -1"
      @click.stop.prevent="paginationButtonClick(pagination.url)"
      :disabled ="pagination.url === null"
      >&raquo;</button>
      <button class="page-link"  v-if="index !== 0 && index !== paginationMeta.links.length -1"
      @click.stop.prevent="paginationButtonClick(pagination.url)"
      >{{pagination.label}}</button>
      </li>
  </ul>
</div>
</template>
<script>
export default {
  props: {
    paginationLinks: {
      type: Object,
      required: true
    },
    paginationMeta: {
      type: Object,
      required: true
    }
  },
  methods: {
   paginationButtonClick(url){
      this.$emit('get-pagination-url', url)
    }
  }
}
</script>
<style>
.pagination{
  margin-top:20px;
  margin-left:360px;
}
</style>