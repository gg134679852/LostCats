<template>
  <div class="mt-4">
    <template>
      <Spinner v-if="homeIsLoading" />
      <div class="container text-center" v-else>
        <form
          class="catFliter d-flex justify-content-between"
          @submit.stop.prevent="getFilter"
          id="searchFilter"
        >
          <div class="w-25">
            <h6>地區</h6>
            <select
              class="form-select"
              v-model="filterSubmitData.short_address"
              required
            >
              <option name="all" value="0">全部</option>
              <option
                v-for="item in shortAddress"
                name="shelter_address"
                :value="item"
                :key="item.index"
              >
                {{ item }}
              </option>
            </select>
          </div>
          <div class="w-25">
            <h6>性別</h6>
            <select
              class="form-select"
              v-model="filterSubmitData.animal_sex"
              required
            >
              <option name="all" value="0">全部</option>
              <option name="animal_sex" value="男生">男生</option>
              <option name="animal_sex" value="女生">女生</option>
            </select>
          </div>
          <div class="w-25">
            <h6>顏色</h6>
            <select
              class="form-select"
              v-model="filterSubmitData.animal_colour"
              required
            >
              <option name="all" value="0">全部</option>
              <option
                v-for="item in catColor"
                name="animal_colour"
                :value="item"
                :key="item.index"
              >
                {{ item }}
              </option>
            </select>
          </div>
          <div>
            <button type="submit" class="btn btn-primary mt-3">送出</button>
          </div>
        </form>
        <Spinner v-if="clickPage" />
        <template v-else>
          <AnimalCard
            :catInfoDatas="catDatas.data"
            @get-Animal-Id="fetchAnimalDetailData"
            @get-Favorite-Cat-Id="getFavoriteCatId"
            @get-Remove-Favorite-Cat-Id="getRemoveFavoriteCatId"
          />
          <Pagination
            :paginationLinks="catDatas.links"
            :paginationMeta="catDatas.meta"
            @get-pagination-url="getPaginationUrl"
          />
        </template>
      </div>
    </template>
    <div
      class="modal fade"
      id="AnimalDetailModal"
      tabindex="-1"
      aria-labelledby="AnimalDetailModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">喵星人詳細資料</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <Spinner v-if="modalIsLoading" />
          <div class="modal-body" v-else>
            <div class="modal-body__cat-info">
              <img
                class="rounded-3"
                :src="catData.album_file"
                onerror="this.src='https://via.placeholder.com/200x200?text=NO+IMAGE'"
              />
              <ul class="list-group">
                <li class="list-group-item">
                  <h6>編號:{{ catData.animal_id }}</h6>
                </li>
                <li class="list-group-item">
                  <h6>性別:{{ catData.animal_sex }}</h6>
                </li>
                <li class="list-group-item">
                  <h6>歲數:{{ catData.animal_age }}</h6>
                </li>
                <li class="list-group-item">
                  <h6>顏色:{{ catData.animal_colour }}</h6>
                </li>
                <li class="list-group-item">
                  <h6>
                    {{ catData.animal_sterilization }},{{
                      catData.animal_bacterin
                    }}
                  </h6>
                </li>
                <li class="list-group-item">
                  <h6>收容所名稱:{{ catData.shelter_name }}</h6>
                </li>
                <li class="list-group-item">
                  <h6>地址:{{ catData.shelter_address }}</h6>
                </li>
                <li class="list-group-item">
                  <h6>電話:{{ catData.shelter_tel }}</h6>
                </li>
              </ul>
            </div>
            <div class="google-map mt-3">
              <MatchMedia query="(max-width: 1023px)" v-slot="{ matches }">
                <GmapMap
                  :center="{
                    lat: catData.address.lat,
                    lng: catData.address.lng,
                  }"
                  :zoom="15"
                  :options="{
                    zoomControl: true,
                    mapTypeControl: false,
                    scaleControl: false,
                    streetViewControl: false,
                    rotateControl: false,
                    fullscreenControl: true,
                    disableDefaultUI: false,
                  }"
                  map-type-id="terrain"
                  style="width: 325px; height: 300px"
                  v-if="matches"
                >
                  <GmapMarker
                    :position="{
                      lat: catData.address.lat,
                      lng: catData.address.lng,
                    }"
                  />
                </GmapMap>
              </MatchMedia>
              <MatchMedia query="(min-width: 1024px)" v-slot="{ matches }">
                <GmapMap
                  :center="{
                    lat: catData.address.lat,
                    lng: catData.address.lng,
                  }"
                  :zoom="15"
                  :options="{
                    zoomControl: true,
                    mapTypeControl: false,
                    scaleControl: false,
                    streetViewControl: false,
                    rotateControl: false,
                    fullscreenControl: true,
                    disableDefaultUI: false,
                  }"
                  map-type-id="terrain"
                  style="width: 766px height: 300px"
                  v-if="matches"
                >
                  <GmapMarker
                    :position="{
                      lat: catData.address.lat,
                      lng: catData.address.lng,
                    }"
                  />
                </GmapMap>
              </MatchMedia>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              關閉
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
 <script>
import axios from "axios";
import AnimalCard from "./../components/AnimalCard";
import Pagination from "./../components/Pagination";
import Spinner from "./../components/Spinner";
import { apiHelper, Toast } from "./../utils/helpers";
import { MatchMedia } from "vue-component-media-queries";
import { mapState } from "vuex";
const getToken = () => localStorage.getItem("token");
export default {
  components: {
    AnimalCard,
    Pagination,
    Spinner,
    MatchMedia,
  },
  data() {
    return {
      catDatas: [],
      catData: {
        id: -1,
        animal_id: -1,
        album_file: "",
        animal_age: "",
        animal_bacterin: "",
        animal_bodytype: "",
        animal_colour: "",
        animal_place: "",
        animal_remark: "",
        animal_sex: "",
        animal_sterilization: "",
        shelter_address: "",
        shelter_name: "",
        shelter_tel: "",
        address: {
          lat: -1,
          lng: -1,
        },
      },
      shortAddress: [],
      catColor: [],
      filterSubmitData: {
        short_address: 0,
        animal_sex: 0,
        animal_colour: 0,
      },
      modalIsLoading: true,
      homeIsLoading: true,
      clickPage: false,
      windowWidth: window.innerWidth
    };
  },
  created() {
    this.fetchAnimalData();
  },
  methods: {
    fetchAnimalData() {
      apiHelper.get("api/animalData").then((obj) => {
        this.catDatas = obj.data;
      });
      apiHelper.get("api/animalData/getSelect").then((obj) => {
        this.shortAddress = obj.data.shortAddress;
        this.catColor = obj.data.color;
        this.homeIsLoading = false;
      });
    },
    getPaginationUrl(url) {
      this.clickPage = true;
      axios.get(url).then((obj) => {
        this.catDatas = obj.data;
        this.clickPage = false;
      });
    },
    getFavoriteCatId(id) {
      apiHelper
        .post(`${id}/addFavorite`, {
          headers: { Authorization: `Bearer ${getToken()}` },
        })
        .then(() => {
          Toast.fire({
            icon: "success",
            title: "成功加入最愛",
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getRemoveFavoriteCatId(id) {
      apiHelper
        .delete(`${id}/removeFavorite`, {
          headers: { Authorization: `Bearer ${getToken()}` },
        })
        .then(() => {
          Toast.fire({
            icon: "success",
            title: "成功移除最愛",
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
    fetchAnimalDetailData(id, address) {
      this.modalIsLoading = true;
      apiHelper.get(`api/animalData/${id}/${address}/detail`).then((obj) => {
        this.catData = {
          ...this.catData,
          ...obj.data[0],
        };
        this.catData.address = {
          ...this.catData.address,
          ...obj.data[1].candidates[0].geometry.location,
        };
        this.modalIsLoading = false;
      });
    },
    getFilter() {
      const data = {
        short_address: this.filterSubmitData.short_address,
        animal_sex: this.filterSubmitData.animal_sex,
        animal_colour: this.filterSubmitData.animal_colour,
      };
      apiHelper
        .get(
          `api/animalData/getFilter/${data.short_address}/${data.animal_sex}/${data.animal_colour}`
        )
        .then((obj) => {
          this.catDatas = obj.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  computed: {
    ...mapState(["isAuthenticated"]),
  },
};
</script>
 <style lang="sass">
@import '../scss/Home.scss'
</style>