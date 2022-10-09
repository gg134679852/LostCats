import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginPage from '../views/LoginPage.vue'
import DashBoard from '../views/admin/DashBoard.vue'
import DataList from '../views/admin/DataList.vue'
import AboutUs from '../views/AboutUs.vue'
import store from '../store/index'
import UserPage from '../views/UserPage.vue'
import CatList from '../views/CatList.vue'
const routes = [
  {
    path: '/',
    component: HomeView
  },
  {
    path: '/aboutUs',
    component: AboutUs
  },
  {
    path: '/login',
    component: LoginPage,
    beforeEnter: (to, from, next) => {
      if (store.state.isAuthenticated) {
        if (store.state.currentUser.isAdmin) {
          next({ path: '/admin/dashboard/datalist' })
        } else {
          next({ path: '/' })
        }
      } else {
        next()
      }
    }
  },
  {
    path: '/catList',
    component: CatList
  },
  {
    path: '/userPage',
    component: UserPage,
    beforeEnter: (to, from, next) => {
      if (store.state.isAuthenticated) {
        if (store.state.currentUser.isAdmin) {
          next({ path: '/admin/dashboard' })
        } else {
          next()
        }
      } else {
        next({ path: '/login' })
      }
    }
  },
  {
    path: '/admin/dashboard',
    component: DashBoard,
    children:
      [
        {
          path: 'datalist',
          component: DataList
        }
      ],
    beforeEnter: (to, from, next) => {
      if (store.state.isAuthenticated) {
        if (store.state.currentUser.isAdmin) {
          next()
        } else {
          next({ path: '/' })
        }
      } else {
        next({ path: '/login' })
      }
    }
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  if (store.state.isAuthenticated) {
    store.dispatch('fetchCurrentUser')
    next()
  } else {
    next()
  }
})

export default router
