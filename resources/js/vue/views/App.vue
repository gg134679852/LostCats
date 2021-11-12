<template>
  <main role="main">
      <NavBar/>
   <router-view></router-view>
</main>
</template>
<script>
import NavBar from './../components/NavBar.vue'
export default {
   props:{
      tradeStatus:{
          type: String
      }
   },
   components:{
     NavBar
   }
}
</script>