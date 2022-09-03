<template>
  <div class="pagination__container">
    <ul class="pagination">
        <li :class="['page-item', { active: pagination.active}]" v-for="(pagination ,index) in paginationLinks"
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
      type: Array,
      required: true
    }
  },
  methods: {
    paginationButtonClick (url) {
      this.$emit('getPaginationUrl', url)
    }
  }
}
</script>
<style lang="sass">
@import '../scss/PaginationComponent.scss'
</style>
