<template>
  <nav aria-label="Page navigation example">
  <ul class="pagination mt-3">
    <li class="page-item" v-for="(pagination ,index) in paginationMeta.links" :key="index">
      <button class="page-link" v-if="index === 0"
      @click.stop.prevent="paginationButtonClick(pagination.url)"
      >&laquo;</button>
      <button class="page-link"  v-if="index === paginationMeta.links.length -1"
      @click.stop.prevent="paginationButtonClick(pagination.url)"
      >&raquo;</button>
      <button class="page-link"  v-if="index !== 0 && index !== paginationMeta.links.length -1"
      @click.stop.prevent="paginationButtonClick(pagination.url)"
      >{{pagination.label}}</button>
      </li>
  </ul>
</nav>
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