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
            <div>
            <button
              type="button"
              class="btn btn-primary modal-body__donate-button"
              data-bs-toggle="modal"
              data-bs-target="#donateModal"
              @click="resetDonateData"
            >
              捐款
            </button>
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
                  style="width: 766px; height: 300px"
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
      <div
        class="modal fade mt-5"
        id="donateModal"
        tabindex="-1"
        aria-labelledby="donateModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-fullscreen-xl-down">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="donateModalLabel">捐款資料</h5>
              <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <form
                @submit.prevent.stop="sendDonate"
                v-if="trade_datas.MerchantID === ''"
              >
                <div class="mb-3">
                  <div class="m-3">
                    <label for="exampleFormControlInput1" class="form-label"
                      >捐款金額</label
                    >
                    <input
                      type="number"
                      max="5000"
                      class="form-control"
                      placeholder="金額不可小於500"
                      v-model="donate_info.price"
                      required
                    />
                  </div>
                  <div class="m-3">
                    <label for="exampleFormControlInput1" class="form-label"
                      >姓名</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      v-model="donate_info.name"
                      required
                    />
                  </div>
                  <div class="m-3">
                    <label for="exampleFormControlInput1" class="form-label"
                      >電子郵件</label
                    >
                    <input
                      type="email"
                      class="form-control"
                      v-model="donate_info.email"
                      required
                    />
                  </div>
                  <div class="m-3">
                    <label for="exampleFormControlInput1" class="form-label"
                      >電話</label
                    >
                    <input
                      type="number"
                      class="form-control"
                      v-model="donate_info.phone"
                      required
                    />
                  </div>
                  <div class="m-3">
                    <label for="exampleFormControlInput1" class="form-label"
                      >地址</label
                    >
                    <input
                      type="text"
                      class="form-control"
                      v-model="donate_info.addres"
                      required
                    />
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
                  <button type="submit" class="btn btn-primary">送出</button>
                </div>
              </form>
              <div class="text-center" v-else>
                <h3></h3>
                <form
                  name="Spgateway"
                  :action="trade_datas.PayGateWay"
                  method="POST" target="_blank"
                >
                  <input
                    type="hidden"
                    name="MerchantID"
                    :value="trade_datas.MerchantID"
                  /><br />
                  <input
                    type="hidden"
                    name="TradeInfo"
                    :value="trade_datas.TradeInfo"
                  /><br />
                  <input
                    type="hidden"
                    name="TradeSha"
                    :value="trade_datas.TradeSha"
                  /><br />
                  <input
                    type="hidden"
                    name="Version"
                    :value="trade_datas.Version"
                  /><br />
                  <button type="submit" class="btn btn-primary" @click="closeModal">
                    開始捐款程序
                  </button>
                </form>
              </div>
            </div>
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
      originCatDatas: [],
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
      donate_info: {
        price: "",
        name: "",
        email: "",
        phone: "",
        addres: "",
      },
      trade_datas: {
        PayGateWay: "",
        MerchantID: "",
        TradeInfo: "",
        TradeSha: "",
        Version: "",
      },
    };
  },
  created() {
    this.fetchAnimalData();
  },
  mounted() {
    window.onresize = () => {
      if (window.innerWidth <= 1024) {
        this.catDatas.data = this.originCatDatas;
      } else if (window.innerWidth >= 1400) {
        this.catDatas.data = this.catDatas.data.slice(0, 16);
      }
    };
  },
  methods: {
    fetchAnimalData() {
      apiHelper.get("api/animalData").then((obj) => {
        this.originCatDatas = obj.data.data;
        if (window.innerWidth <= 1024) {
          this.catDatas = obj.data;
        } else if (window.innerWidth >= 1400) {
          this.catDatas = obj.data;
          this.catDatas.data = this.catDatas.data.slice(0, 16);
        }
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
        this.originCatDatas = obj.data.data;
        if (window.innerWidth <= 1024) {
          this.catDatas = obj.data;
        } else if (window.innerWidth >= 1400) {
          this.catDatas = obj.data;
          this.catDatas.data = this.catDatas.data.slice(0, 16);
        }
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
    sendDonate() {
      if(this.donate_info.price < 0 || this.donate_info.price < 500){
         Toast.fire({
            icon: "warning",
            title: "金額錯誤",
          });
      }else{
         axios
        .post("api/spgateway/donate", {
          data: {
            ...this.donate_info,
            shelter_name: this.catData.shelter_name,
          },
        })
        .then((obj) => {
          this.trade_datas = { ...this.trade_datas, ...obj.data };
        });
      }
    },
   closeModal(){
    document.querySelector('#donateModalLabel').nextElementSibling.click()
    },
    resetDonateData(){
      this.trade_datas = {
        PayGateWay: "",
        MerchantID: "",
        TradeInfo: "",
        TradeSha: "",
        Version: "",
      }
    }
  },
  computed: {
    ...mapState(["isAuthenticated"]),
  },
};
</script>
 <style lang="sass">
@import '../scss/Home.scss'
</style>