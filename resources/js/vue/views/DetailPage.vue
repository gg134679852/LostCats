<template>
  <div>

  </div>
</template>
<script>
export default {
  created () {
    this.fetchAnimalData()
  },
  methods: {
    fetchAnimalData () {
      const id = this.$route.params.id
      this.$axiosHelper.get(`api/animalData/getDetail?id=${id}`)
        .then((obj) => {
          console.log(obj.data)
        })
    }
  }
}
</script>
