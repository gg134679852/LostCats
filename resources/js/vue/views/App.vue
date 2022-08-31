<template>
  <router-view/>
</template>
<script>
import { Toast } from '../utils/helpers'
export default {
  provide () {
    return {
      Toast
    }
  }
}
</script>
<style lang="scss">
@import '../scss/all.scss';
@import '../../../css/app.scss';
</style>
