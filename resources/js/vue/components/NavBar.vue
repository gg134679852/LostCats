<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <router-link class="navbar-brand" to="/">
        <h3 class="navbar-brand__title">
          <i class="fas fa-cat"></i> 迷途喵星人
        </h3>
      </router-link>
      <div class="collapse navbar-collapse" id="navbarSupportedContent"></div>
      <div class="navbar-button-wrapper">
        <router-link
          to="/login"
          class="btn btn-primary"
          v-if="!isAuthenticated"
        >
          登陸
        </router-link>
        <router-link
          to="/userpage"
          class="btn btn-primary"
          v-if="isAuthenticated"
        >
          會員頁面
        </router-link>
        <button class="btn btn-primary" v-if="isAuthenticated" @click="logout">
          登出
        </button>
      </div>
    </div>
  </nav>
</template>
<script>
import { mapState } from "vuex";
import { apiHelper, Toast } from "./../utils/helpers";
import axios from "axios";
const getToken = () => localStorage.getItem("token");
export default {
  computed: {
    ...mapState(["currentUser", "isAuthenticated"]),
  },
  methods: {
    logout() {
      axios.get("/sanctum/csrf-cookie").then((response) => {
        apiHelper
          .get("logout", {
            headers: { Authorization: `Bearer ${getToken()}` },
          })
          .then(() => {
            this.$store.commit("revokeAuthentication");
            Toast.fire({
              icon: "success",
              title: "成功登出",
            });
            this.$router.push("/");
          });
      });
    },
  },
};
</script>
<style lang="sass">
@import '../scss/NavBar.scss'
</style>