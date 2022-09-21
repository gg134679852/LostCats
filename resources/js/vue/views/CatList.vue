<template>
  <NavBarVue />
  <loading-icon :active="isLoading" />
  <div class="catList__warp">
   <div class="catFilter__wrap">
    <button type="button" class="btn btn-secondary" @click.prevent="Switcher('filter')"><i class="fas fa-filter"></i>過濾器</button>
   </div>
    <CatCard :cat-info-data="catInfoData" />
     <PaginationComponent
     :pagination-links="paginationLinks"
     :data-length="dataLength"
     @fetch-animal-data="fetchAnimalData"
     />
   </div>
   <FilterModal
   :filter-modal-switcher="filterModalSwitcher"
   />
</template>
<script>
import NavBarVue from '../components/NavBar.vue'
import CatCard from '../components/CatCard.vue'
import FilterModal from '../components/FilterModal.vue'
import PaginationComponent from '../components/PaginationComponent.vue'
export default {
  components: {
    CatCard,
    NavBarVue,
    FilterModal,
    PaginationComponent
  },
  created () {
    this.screenRuler()
  },
  mounted () {
    window.onresize = () => {
      this.screenRuler()
    }
  },
  inject: ['Toast'],
  data () {
    return ({
      catInfoData: [],
      paginationLinks: {},
      color: [],
      dataLength: 0,
      filterModalSwitcher: 'hide',
      isLoading: false
    })
  },
  methods: {
    fetchAnimalData (type, url) {
      this.isLoading = true
      switch (type) {
        case 'pageClick':{
          url = `${url}&dataLength=${this.dataLength}`
          break
        }
        case 'filterData':{
          url = `${url}?dataLength=${this.dataLength}`
          break
        }
        default:{
          url = `api/animalData?dataLength=${this.dataLength}`
          break
        }
      }
      this.$axiosHelper.get(url)
        .then((obj) => {
          const { catData, selectOption } = obj.data.responseData
          this.catInfoData = catData.data
          this.paginationLinks = {
            links: catData.links,
            currentPage: catData.current_page,
            prevPageUrl: catData.prev_page_url,
            nextPageUrl: catData.next_page_url,
            lastPage: catData.last_page
          }
          this.color = selectOption.color
          this.isLoading = false
        })
        .catch((err) => {
          this.Toast.fire({
            icon: 'error',
            title: '發生錯誤，請查看開發者工具'
          })
          console.log(err)
          this.isLoading = false
        })
      document.documentElement.scrollTop = 0
    },
    getFavoriteCatId (id) {
      this.$axiosHelper
        .post(`${id}/addFavorite`)
        .then(() => {
          this.Toast.fire({
            icon: 'success',
            title: '成功加入最愛'
          })
        })
        .catch((error) => {
          this.Toast.fire({
            icon: 'warning',
            title: '發生錯誤 請稍後在試'
          })
          console.log(error)
        })
    },
    getRemoveFavoriteCatId (id) {
      this.$axiosHelper
        .delete(`${id}/removeFavorite`)
        .then(() => {
          this.Toast.fire({
            icon: 'success',
            title: '成功移除最愛'
          })
        })
        .catch((error) => {
          this.Toast.fire({
            icon: 'warning',
            title: '發生錯誤 請稍後在試'
          })
          console.log(error)
        })
    },
    screenRuler () {
      switch (true) {
        case window.innerWidth <= 767:{
          this.dataLength = 16
          break
        }
        case window.innerWidth >= 768:{
          this.dataLength = 18
          break
        }
      }
    },
    Switcher (type) {
      switch (type) {
        case 'filter':{
          this.filterModalSwitcher === 'hide'
            ? this.filterModalSwitcher = 'show'
            : this.filterModalSwitcher = 'hide'
          break
        }
      }
    }
  },
  watch: {
    dataLength (newValue, oldValue) {
      if (oldValue !== newValue) {
        this.fetchAnimalData()
      }
    }
  }
}
</script>
<style lang="scss">
@import '../scss/CatList.scss'
</style>
