<template>
  <div class="container-fluid">
    <NavBar />
    <router-view />
  </div>
</template>
<script>
import NavBar from '../../components/admin/NavBar.vue'
export default {
  components: {
    NavBar
  }
}
</script>
