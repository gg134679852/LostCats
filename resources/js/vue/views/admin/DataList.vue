<template>
  <loading-icon :active="isLoading" />
  <template v-if="currentComponent === 'CatList'">
    <CatList :cat-data="catData" :shelterData="shelterData" :current-component="currentComponent"
      :screen-size="screenSize" @fetch-animal-data="fetchAnimalData" @component-switcher="componentSwitcher"
      @loading-switcher="loadingSwitcher" />
  </template>
  <template v-if="currentComponent === 'ShelterList'">
    <ShelterList :current-component="currentComponent" :shelter-data="shelterData.data"
      @loading-switcher="loadingSwitcher" @component-switcher="componentSwitcher" />
  </template>
</template>
<script>
import CatList from '../../components/admin/CatList.vue'
import ShelterList from '../../components/admin/ShelterList.vue'
export default {
  components: {
    CatList,
    ShelterList
  },
  created () {
    this.fetchAnimalData()
    this.fetchDonateLogData()
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
        data: []
      },
      donateLog: {
        data: []
      },
      currentComponent: 'CatList',
      screenSize: 'Big',
      isLoading: false
    })
  },
  methods: {
    fetchAnimalData (type, url, condition) {
      this.isLoading = true
      switch (type) {
        case 'pageClick': {
          this.$axiosHelper.get(url)
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
        case 'filterData': {
          url = `api/animalData/getFilter?animal_sex=${condition.animal_sex}&animal_color=${condition.animal_color}&shelter_city=${condition.shelter_city}&shelter_name=${condition.shelter_name}&haveImage=${condition.haveImage}&screenSize=${this.screenSize}`
          this.$axiosHelper.get(url)
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
        default: {
          url = `api/animalData?screenSize=${this.screenSize}`
          this.$axiosHelper.get(url)
            .then((obj) => {
              const { catData, selectOption, shelterList } = obj.data.responseData
              this.catData.data = catData.data
              this.shelterData.data = shelterList
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
    },
    fetchDonateLogData () {
      this.$axiosHelper.get('admin/donateLogData')
        .then((obj) => {
          console.log(obj.data)
        })
    },
    componentSwitcher (type) {
      switch (type) {
        case 'CatList': {
          this.currentComponent = 'CatList'
          break
        }
        case 'ShelterList': {
          this.currentComponent = 'ShelterList'
          break
        }
      }
    },
    loadingSwitcher () {
      this.isLoading === false ? this.isLoading = true : this.isLoading = false
    }
  }
}
</script>
