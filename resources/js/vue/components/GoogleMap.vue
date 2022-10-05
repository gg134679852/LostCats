<template>
  <GMapMap
      :center="{ lat: shelterData.shelter_lat, lng: shelterData.shelter_lng }"
      :zoom="16"
      map-type-id="terrain"
      :style="{width: '100%', height: mapHeight + 'px',position: 'relative', overflow: 'hidden'}"
      :options="{
                      zoomControl: true,
                      mapTypeControl: true,
                      scaleControl: true,
                      streetViewControl: false,
                      rotateControl: true,
                      fullscreenControl: true,
                }"
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
    screenSize: {
      type: String,
      required: true
    }
  },
  created () {
    this.mapSize()
  },
  data () {
    return ({
      mapHeight: 0,
      mapWidth: 0
    })
  },
  methods: {
    mapSize () {
      switch (this.screenSize) {
        case 'Small':{
          this.mapHeight = 200
          break
        }
        case 'Middle':{
          this.mapHeight = 450
          break
        }
        case 'Big':{
          this.mapHeight = 450
          break
        }
      }
    }
  },
  watch: {
    screenSize (newValue, oldValue) {
      if (newValue) {
        this.mapSize()
      }
    }
  }
}
</script>
