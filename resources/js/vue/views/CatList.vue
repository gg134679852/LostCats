<template>
  <NavBarVue />
  <loading-icon :active="isLoading" />
  <div class="catList__warp">
   <div class="catFilter__wrap">
    <button type="button" class="btn btn-secondary" @click.prevent="filterSwitcher"><i class="fas fa-filter"></i>過濾器</button>
   </div>
    <CatCard
      :cat-info-data="catInfoData"
      :screen-size="screenSize"
    />
     <PaginationComponent
      :pagination-links="paginationLinks"
      :screen-size="screenSize"
      @fetch-animal-data="fetchAnimalData"
     />
   </div>
   <FilterModal
      :filter-modal-switcher="filterModalSwitcher"
      :shelter-name="shelterName"
      :color="color"
      :screen-size="screenSize"
      @filter-switcher="filterSwitcher"
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
      screenSize: '',
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
          url = `api/animalData/getFilter?animal_sex=${condition.animal_sex}&animal_color=${condition.animal_color}&shelter_city=${condition.shelter_city}&shelter_name=${condition.shelter_name}&screenSize=${this.screenSize}`
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
          url = `api/animalData?screenSize=${this.screenSize}`
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
    screenRuler () {
      switch (true) {
        case window.innerWidth <= 767:{
          this.screenSize = 'Small'
          break
        }
        case window.innerWidth >= 992:{
          this.screenSize = 'Big'
          break
        }
        case window.innerWidth >= 768:{
          this.screenSize = 'Middle'
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
    filterSwitcher () {
      this.filterModalSwitcher === 'hide'
        ? this.filterModalSwitcher = 'show'
        : this.filterModalSwitcher = 'hide'
    }
  },
  watch: {
    screenSize (newValue, oldValue) {
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
