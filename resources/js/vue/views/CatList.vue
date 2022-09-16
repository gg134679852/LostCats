<template>
  <NavBarVue />
   <CatCard :cat-info-data="catInfoData" />
</template>
<script>
import NavBarVue from '../components/NavBar.vue'
import CatCard from '../components/CatCard.vue'
export default {
  components: {
    CatCard,
    NavBarVue
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
      color: [],
      shortAddress: [],
      dataLength: 0
    })
  },
  methods: {
    fetchAnimalData () {
      this.$axiosHelper.get(`api/animalData?dataLength=${this.dataLength}`)
        .then((obj) => {
          const { catData, selectOption } = obj.data.responseData
          this.catInfoData = catData.data
          this.color = selectOption.color
          this.shortAddress = selectOption.shortAddress
        })
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
