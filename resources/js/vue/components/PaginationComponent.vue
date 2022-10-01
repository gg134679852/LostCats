<template>
  <div class="pagination__container">
  <div class="pagination__mobile__wrap" v-if="screenSize === 'Small'">
   <button type="pagination__mobile__button button" class="btn btn-outline-primary btn-lg"
   @click.stop.prevent="paginationButtonClick(paginationLinks.prevPageUrl)"
   :disabled="! paginationLinks.prevPageUrl"
   >&laquo;</button>
   <h2 class="pagination__mobile__count">{{paginationLinks.currentPage}}/{{paginationLinks.lastPage}}</h2>
   <button type="pagination__mobile__button button" class="btn btn-outline-primary btn-lg"
   @click.stop.prevent="paginationButtonClick(paginationLinks.nextPageUrl)"
   :disabled="! paginationLinks.nextPageUrl"
   >&raquo;</button>
   </div>
    <ul class="pagination" v-else>
        <li :class="['page-item', { active: pagination.active}]" v-for="(pagination ,index) in paginationLinks.links"
          :key="index">
          <button :class="['page-link', { disabled: pagination.url === null}]" v-if="index === 0"
            @click.stop.prevent="paginationButtonClick(pagination.url)">&laquo;</button>
          <button :class="['page-link', { disabled: pagination.url === null}]" v-if="index === 14"
            @click.stop.prevent="paginationButtonClick(pagination.url)">&raquo;</button>
          <button class="page-link" v-if="index !== 0 && index !== 14 "
            @click.stop.prevent="paginationButtonClick(pagination.url)">{{pagination.label}}</button>
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
    screenSize: {
      type: String,
      required: true
    }
  },
  methods: {
    paginationButtonClick (url) {
      this.$emit('fetchAnimalData', 'pageClick', url)
    }
  }
}
</script>
<style lang="sass">
@import '../scss/PaginationComponent.scss'
</style>
