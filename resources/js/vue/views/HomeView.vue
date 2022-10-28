<template>
  <NavBarVue />
 <router-view />
</template>
<script>
import NavBarVue from '../components/NavBar.vue'
import { Toast } from '../utils/helpers'
export default {
  components: {
    NavBarVue
  },
  provide () {
    return {
      Toast
    }
  }
}
</script>
