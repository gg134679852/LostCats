<template>
  <loading-icon :active="isLoading" style="z-index: 1500;"/>
  <template v-if="currentComponent === 'CatList'">
    <CatList
    :cat-data="catData"
    :shelter-data="shelterData.data"
    :current-component="currentComponent"
    :screen-size="screenSize"
    @fetch-animal-data="fetchAnimalData"
    @component-switcher="componentSwitcher"
    @loading-switcher="loadingSwitcher"
    />
  </template>
  <template v-if="currentComponent === 'ShelterList'">
    <ShelterList
    :current-component="currentComponent"
    :shelter-data="shelterData.data"
    @loading-switcher="loadingSwitcher"
    @component-switcher="componentSwitcher"
    />
  </template>
  <template v-if="currentComponent === 'DonateLog'">
    <DonateLog
    :current-component="currentComponent"
    :donate-log-data="donateLog"
    :screen-size="screenSize"
    @fetch-animal-data="fetchAnimalData"
    @loading-switcher="loadingSwitcher"
    @component-switcher="componentSwitcher" />
  </template>
</template>
<script>
import CatList from '../../components/admin/CatList.vue'
import ShelterList from '../../components/admin/ShelterList.vue'
import DonateLog from '../../components/admin/DonateLog.vue'
export default {
  components: {
    CatList,
    ShelterList,
    DonateLog
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
        data: [],
        paginationLinks: {}
      },
      currentComponent: 'CatList',
      screenSize: 'Big',
      isLoading: false
    })
  },
  methods: {
    fetchAnimalData (type, value) {
      this.isLoading = true
      switch (type) {
        case 'pageClick': {
          this.$axiosHelper.get(value)
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
        default: {
          this.$axiosHelper.get(`api/animalData?screenSize=${this.screenSize}`)
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
          const { donateLog } = obj.data
          this.donateLog.data = donateLog.data
          this.donateLog.paginationLinks = {
            links: donateLog.links,
            currentPage: donateLog.current_page,
            prevPageUrl: donateLog.prev_page_url,
            nextPageUrl: donateLog.next_page_url,
            lastPage: donateLog.last_page
          }
          this.isLoading = false
        })
        .catch((err) => {
          this.Toast.fire({
            icon: 'error',
            title: '發生錯誤，請查看開發者工具'
          })
          console.log(err)
        })
    },
    componentSwitcher (type) {
      this.currentComponent = type
    },
    loadingSwitcher () {
      this.isLoading === false ? this.isLoading = true : this.isLoading = false
    }
  }
}
</script>
