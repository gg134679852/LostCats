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
   :shelter-name="shelterName"
   :color="color"
   :data-length="dataLength"
   @switcher="Switcher"
   @shelter-filter="shelterFilter"
   @fetch-animal-data="fetchAnimalData"
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
      shelterList: [],
      shelterName: [],
      color: [],
      dataLength: 0,
      filterModalSwitcher: 'hide',
      isLoading: false
    })
  },
  methods: {
    fetchAnimalData (type, url, condition) {
      this.isLoading = true
      switch (type) {
        case 'pageClick':{
          this.$axiosHelper.get(url)
            .then((obj) => {
              const { catData } = obj.data.responseData
              this.catInfoData = catData.data
              this.paginationLinks = {
                links: catData.links,
                currentPage: catData.current_page,
                prevPageUrl: catData.prev_page_url,
                nextPageUrl: catData.next_page_url,
                lastPage: catData.last_page
              }
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
          break
        }
        case 'filterData':{
          url = `api/animalData/getFilter?animal_sex=${condition.animal_sex}&animal_color=${condition.animal_color}&shelter_city=${condition.shelter_city}&shelter_name=${condition.shelter_name}&dataLength=${this.dataLength}`
          this.$axiosHelper.get(url)
            .then((obj) => {
              const { catData } = obj.data.responseData
              this.catInfoData = catData.data
              this.paginationLinks = {
                links: catData.links,
                currentPage: catData.current_page,
                prevPageUrl: catData.prev_page_url,
                nextPageUrl: catData.next_page_url,
                lastPage: catData.last_page
              }
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
          break
        }
        default:{
          url = `api/animalData?dataLength=${this.dataLength}`
          this.$axiosHelper.get(url)
            .then((obj) => {
              const { catData, selectOption, shelterList } = obj.data.responseData
              this.catInfoData = catData.data
              this.shelterList = shelterList
              this.shelterName = this.shelterList.map(data => data.shelter_name)
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
          break
        }
      }
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
        case window.innerWidth >= 992:{
          this.dataLength = 20
          break
        }
        case window.innerWidth >= 768:{
          this.dataLength = 18
          break
        }
      }
    },
    shelterFilter (city) {
      if (city === '') {
        this.shelterName = this.shelterList.map(data => data.shelter_name)
      }
      if (city !== '') {
        this.shelterName = []
        this.shelterList.forEach((data) => {
          if (data.shelter_city === city) {
            this.shelterName.push(data.shelter_name)
          }
        })
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
