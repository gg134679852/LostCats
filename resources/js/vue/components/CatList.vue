<template>
  <loading-icon :active="isLoading" />
  <div class="catList__warp">
   <div class="catFilter__wrap">
    <button type="button" class="btn btn-secondary" @click.prevent="filterSwitcher"><i class="fas fa-filter"></i>過濾器</button>
   </div>
    <CatCard
      :cat-info-data="catData.data"
      :screen-size="screenSize"
      @loading-switcher="loadingSwitcher"
    />
     <PaginationComponent
      :pagination-links="catData.paginationLinks"
      :screen-size="screenSize"
      @fetch-animal-data="fetchAnimalData"
     />
   </div>
   <FilterModal
      :filter-modal-switcher="filterModalSwitcher"
      :shelter-data="shelterData.option"
      :color="catData.color"
      :screen-size="screenSize"
      @filter-switcher="filterSwitcher"
      @shelter-filter="shelterFilter"
      @fetch-animal-data="fetchAnimalData"
   />
</template>
<script>
import CatCard from './CatCard.vue'
import FilterModal from './FilterModal.vue'
import PaginationComponent from './PaginationComponent.vue'
export default {
  components: {
    CatCard,
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
      catData: {
        data: [],
        color: [],
        paginationLinks: {}
      },
      shelterData: {
        data: [],
        option: []
      },
      screenSize: '',
      filterModalSwitcher: 'hide',
      isLoading: false
    })
  },
  methods: {
    fetchAnimalData (type, value) {
      this.isLoading = true
      switch (type) {
        case 'pageClick':{
          this.$axiosHelper.get(value)
            .then((obj) => {
              const { catData } = obj.data.responseData
              this.catData.data = catData.data
              this.catData.paginationLinks = {
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
          this.$axiosHelper.get(`api/animalData/getFilter?animal_sex=${value.animal_sex}&animal_color=${value.animal_color}&shelter_city=${value.shelter_city}&shelter_name=${value.shelter_name}&haveImage=${value.haveImage}&screenSize=${this.screenSize}`)
            .then((obj) => {
              const { catData } = obj.data.responseData
              if (Array.isArray(catData)) {
                this.catData.data = catData.data
              } else {
                this.catData.data = Object.values(catData.data)
              }
              this.catData.paginationLinks = {
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
          this.$axiosHelper.get(`api/animalData?screenSize=${this.screenSize}`)
            .then((obj) => {
              const { catData, selectOption, shelterList } = obj.data.responseData
              this.catData.data = catData.data
              this.shelterData.data = shelterList
              this.shelterData.option = shelterList
              this.catData.paginationLinks = {
                links: catData.links,
                currentPage: catData.current_page,
                prevPageUrl: catData.prev_page_url,
                nextPageUrl: catData.next_page_url,
                lastPage: catData.last_page
              }
              this.catData.color = selectOption.color
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
      if (city === '0') {
        this.shelterData.option = this.shelterData.data
      }
      if (city !== '') {
        this.shelterData.option = this.shelterData.data.filter(item => item.shelter_city === city)
      }
    },
    filterSwitcher () {
      this.filterModalSwitcher === 'hide'
        ? this.filterModalSwitcher = 'show'
        : this.filterModalSwitcher = 'hide'
    },
    loadingSwitcher () {
      this.isLoading === false ? this.isLoading = true : this.isLoading = false
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
