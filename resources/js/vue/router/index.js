import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginPage from '../views/LoginPage.vue'
import DashBoard from '../views/admin/DashBoard.vue'
import adminCatList from '../components/admin/CatList.vue'
import AboutUs from '../views/AboutUs.vue'
import store from '../store/index'
import UserPage from '../views/UserPage.vue'
import userCatList from '../views/CatList.vue'
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
          next({ path: '/admin/dashboard/catlist' })
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
    component: userCatList
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
          path: 'catlist',
          component: adminCatList
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

export default router
