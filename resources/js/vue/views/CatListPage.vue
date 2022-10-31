<template>
  <router-view />
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
