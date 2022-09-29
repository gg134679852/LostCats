<template>
<div class="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" ref="modal">
  <div class="modal-dialog" :class="{ 'modal-fullscreen' : dataLength === 16||dataLength === 18,'modal-lg':dataLength === 20}">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">喵星人資訊</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
      </div>
      <div class="modal-body">
       <div class="catInfoModal__wrap">
        <div class="catInfoModal__catInfo">
          <div class="catInfoModal__catInfo__img">
            <img
                class="rounded-3"
                :src="showCatData.album_file"
                onerror="this.src='https://via.placeholder.com/200x200?text=NO+IMAGE'"
              />
          </div>
          <div class="catInfoModal__catInfo__content">
              <div class="catInfoModal__catInfo__content__text__id">
                <h2>編號:{{showCatData.animal_id}}</h2>
              </div>
               <div class="catInfoModal__catInfo__info-box__wrap">
              <div class="catInfoModal__catInfo__info-box">
                <div class="catInfoModal__catInfo__info-box__text">
                    <h6>性別</h6>
                  <h2>{{showCatData.animal_sex}}</h2>
                </div>
              </div>
               <div class="catInfoModal__catInfo__info-box">
                <div class="catInfoModal__catInfo__info-box__text">
                   <h6>毛色</h6>
                  <h2>{{showCatData.animal_color}}</h2>
                </div>
              </div>
               <div class="catInfoModal__catInfo__info-box">
                 <div class="catInfoModal__catInfo__info-box__text">
                   <h6>是否結紮</h6>
                  <h2>{{showCatData.animal_sterilization}}</h2>
                 </div>
              </div>
               <div class="catInfoModal__catInfo__info-box">
               <div class="catInfoModal__catInfo__info-box__text">
                  <h6>是否施打疫苗</h6>
                  <h2>{{showCatData.animal_bacterin}}</h2>
               </div>
              </div>
             </div>
               <div class="catInfoModal__catInfo__content__text__remark">
                <h3>註記:</h3>
                <h2>{{showCatData.animal_remark}}</h2>
              </div>
          </div>
          </div>
          <hr>
           <div class="catInfoModal__shelterInfo__info-box__wrap">
             <div class="catInfoModal__shelterInfo__info-box">
                 <div class="catInfoModal__shelterInfo__info-box__text">
                    <h6>收容所地區</h6>
                  <h2>{{showCatData.shelterData.shelter_city}}</h2>
                </div>
                <div class="catInfoModal__shelterInfo__info-box__text">
                    <h6>收容所名稱</h6>
                  <h2>{{showCatData.shelterData.shelter_name}}</h2>
                </div>
                <div class="catInfoModal__shelterInfo__info-box__text">
                    <h6>收容所電話</h6>
                  <h2>{{showCatData.shelterData.shelter_tel}}</h2>
                </div>
              </div>
           </div>
          <div class="catInfoModal__info__map">
            <GoogleMap :shelter-data="showCatData.shelterData" :data-length="dataLength" />
          </div>
       </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
</template>
<script>
import Modal from '../../../../node_modules/bootstrap/js/dist/modal'
import GoogleMap from './GoogleMap.vue'
export default {
  components: {
    GoogleMap
  },
  props: {
    catInfoModalSwitcher: {
      type: String,
      required: true
    },
    showCatData: {
      type: Object,
      required: true
    },
    dataLength: {
      type: Number,
      required: true
    }
  },
  data () {
    return ({
      modal: {}
    })
  },
  methods: {
    closeModal () {
      this.$emit('switcher', 'catInfo', 'none')
    }
  },
  mounted () {
    this.modal = this.modal = new Modal(this.$refs.modal)
  },
  watch: {
    catInfoModalSwitcher (newValue, oldValue) {
      return newValue === 'show' ? this.modal.show() : this.modal.hide()
    }
  }
}
</script>
<style lang="scss">
  @import '../scss/CatInfoModal.scss';
</style>
