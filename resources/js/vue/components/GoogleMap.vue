<template>
  <GMapMap
      :center="{ lat: shelterData.shelter_lat, lng: shelterData.shelter_lng }"
      :zoom="16"
      map-type-id="terrain"
      :style="{width: '100%', height: mapHeight + 'px',position: 'relative', overflow: 'hidden'}"
  >
  </GMapMap>
</template>
<script>
export default {
  props: {
    shelterData: {
      type: Object,
      required: true
    },
    dataLength: {
      type: Number,
      required: true
    }
  },
  created () {
    switch (this.dataLength) {
      case 16:{
        this.mapHeight = 200
        break
      }
      case 18:{
        this.mapHeight = 450
        break
      }
      case 20:{
        this.mapHeight = 450
        break
      }
    }
  },
  data () {
    return ({
      mapHeight: 0,
      mapWidth: 0
    })
  },
  watch: {
    dataLength (newValue, oldValue) {
      switch (newValue) {
        case 16:{
          this.mapHeight = 200
          break
        }
        case 18:{
          this.mapHeight = 450
          break
        }
        case 20:{
          this.mapHeight = 450
          break
        }
      }
    }
  }
}
</script>
