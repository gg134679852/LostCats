import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginPage from '../views/LoginPage.vue'
import DashBoard from '../views/admin/DashBoard.vue'
import CatList from '../components/admin/CatList.vue'
import store from '../store/index'
const routes = [
  {
    path: '/',
    component: HomeView
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
    path: '/admin/dashboard',
    component: DashBoard,
    children:
      [
        {
          path: 'catlist',
          component: CatList
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
