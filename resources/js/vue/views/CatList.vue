<template>
  <NavBarVue />
  <loading-icon :active="isLoading" />
  <div class="catList__warp">
   <div class="catFilter__wrap">
    <button type="button" class="btn btn-secondary" @click.prevent="Switcher('filter')"><i class="fas fa-filter"></i>過濾器</button>
   </div>
    <CatCard
      :cat-info-data="catInfoData"
      @switcher="Switcher"
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
      @switcher="Switcher"
      @shelter-filter="shelterFilter"
      @fetch-animal-data="fetchAnimalData"
   />
   <CatInfoModal
      :cat-info-modal-switcher="catInfoModalSwitcher"
      :show-cat-data="showCatData"
      :screen-size="screenSize"
      @switcher="Switcher"
   />
</template>
<script>
import NavBarVue from '../components/NavBar.vue'
import CatCard from '../components/CatCard.vue'
import FilterModal from '../components/FilterModal.vue'
import CatInfoModal from '../components/CatInfoModal.vue'
import PaginationComponent from '../components/PaginationComponent.vue'
export default {
  components: {
    CatCard,
    NavBarVue,
    FilterModal,
    PaginationComponent,
    CatInfoModal
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
      showCatData: {
        album_file: '',
        animal_age: '',
        animal_bacterin: '',
        animal_bodytype: '',
        animal_color: '',
        animal_foundplace: '',
        animal_id: '',
        animal_remark: '',
        animal_sex: '',
        animal_sterilization: '',
        shelterData: {
          id: '',
          shelter_address: '',
          shelter_city: '',
          shelter_lat: 0,
          shelter_lng: 0,
          shelter_name: '',
          shelter_tel: ''
        }
      },
      paginationLinks: {},
      shelterList: [],
      shelterName: [],
      color: [],
      screenSize: '',
      filterModalSwitcher: 'hide',
      catInfoModalSwitcher: 'hide',
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
    Switcher (type, catDataId, shelterDataId) {
      if (catDataId !== 'none' && shelterDataId !== 'none') {
        const catDataIndex = this.catInfoData.findIndex((obj) => obj.animal_id === catDataId)
        const shelterDataIndex = this.shelterList.findIndex((obj) => obj.id === shelterDataId)
        const targetData = { ...this.catInfoData[catDataIndex], shelterData: { ...this.shelterList[shelterDataIndex] } }
        targetData.shelterData.shelter_lat = Number(targetData.shelterData.shelter_lat)
        targetData.shelterData.shelter_lng = Number(targetData.shelterData.shelter_lng)
        this.showCatData = targetData
      }
      switch (type) {
        case 'filter':{
          this.filterModalSwitcher === 'hide'
            ? this.filterModalSwitcher = 'show'
            : this.filterModalSwitcher = 'hide'
          break
        }
        case 'catInfo':{
          this.catInfoModalSwitcher === 'hide'
            ? this.catInfoModalSwitcher = 'show'
            : this.catInfoModalSwitcher = 'hide'
          break
        }
      }
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
