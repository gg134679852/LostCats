import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import DashBoard from '../views/admin/DashBoard.vue'
import DataList from '../views/admin/DataList.vue'
import store from '../store/index'
import UserPage from '../views/UserPage.vue'
import CatList from '../components/CatList.vue'
import CatInfo from '../components/CatInfo.vue'
import CatListPage from '../views/CatListPage.vue'
import LandingPageComponent from '../components/LandingPageComponent.vue'
import AboutUsPageComponent from '../components/AboutUsPageComponent.vue'
import LoginPageComponent from '../components/LoginPageComponent.vue'
const routes = [
  {
    path: '/',
    component: HomeView,
    children: [
      {
        path: '/landing',
        component: LandingPageComponent
      },
      {
        path: '/aboutUs',
        component: AboutUsPageComponent
      },
      {
        path: '/login',
        component: LoginPageComponent,
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
      }
    ]
  },
  {
    path: '/catList',
    component: CatListPage,
    children: [
      {
        path: '/catList/listPage',
        component: CatList
      },
      {
        path: '/catList/infoPage/:id',
        component: CatInfo
      }
    ]
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
