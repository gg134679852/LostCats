<template>
<div class="pagination__container">
  <MatchMedia query="(max-width: 545px)" v-slot="{ matches }">
    <div class="mobile-pagination" v-if="matches">
   <button type="button" class="btn btn-outline-primary btn-lg"
   @click.stop.prevent="paginationButtonClick(paginationLinks.prev)"
   :disabled="! paginationLinks.prev"
   >&laquo;</button>
   <h2 class="mobile-pagination__count">{{paginationMeta.current_page}}/{{paginationMeta.last_page}}</h2>
   <button type="button" class="btn btn-outline-primary btn-lg" 
   @click.stop.prevent="paginationButtonClick(paginationLinks.next)"
   :disabled="! paginationLinks.next"
   >&raquo;</button>
   </div>
   <ul class="pagination" v-else>
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
  </MatchMedia>
  </div>
</template>
<script>
import { MatchMedia } from "vue-component-media-queries";
export default {
  components: {
    MatchMedia
  },
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
<style lang="sass">
@import '../scss/Pagination.scss' 
</style>