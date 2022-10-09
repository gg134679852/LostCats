<template>
  <NavBar />
  <div class="container-fluid">
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
