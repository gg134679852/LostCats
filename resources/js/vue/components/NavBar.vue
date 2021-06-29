<template>
  <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <router-link
      class="navbar-brand"
      to="/"
    >
      迷途喵星人
    </router-link>

    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon" />
    </button>

    <div
      id="navbarSupportedContent" 
      class="navbar-collapse collapse"
    >
      <div class="ml-auto d-flex align-items-center">
        <!-- is user is admin -->
        <router-link
      v-if="currentUser.isAdmin"
      to="#"
      class="text-white mr-3"
        >
         管理員後台
       </router-link>

        <!-- is user is login -->
        <template v-if="isAuthenticated">
      <router-link
        to="#"
        class="text-white mr-3"
      >
        {{ currentUser.name || '使用者' }} 您好
      </router-link>
      <button
        type="button"
        class="btn btn-sm btn-outline-success my-2 my-sm-0"
      >
        登出
      </button>
    </template>
      </div>
    </div>
  </nav>
</template>

<script>
// ./src/components/Navbar.vue
// seed data
const dummyUser = {
  currentUser: {
    id: 1,
    name: '管理者',
    email: 'root@example.com',
    image: 'https://i.pravatar.cc/300',
    isAdmin: true
  },
  isAuthenticated: true
}

export default {
  // Vue 會在沒有資料時使用此預設值
  data () {
    return {
      currentUser: {
        id: -1,
        name: '',
        email: '',
        image: '',
        isAdmin: false
      },
      isAuthenticated: false
    }
  },
   created () {
    this.fetchUser()
  },
  methods: {
    fetchUser () {
      this.currentUser = {
        ...this.currentUser,
        ...dummyUser.currentUser
      }
      this.isAuthenticated = dummyUser.isAuthenticated
    }
  }
}
</script>