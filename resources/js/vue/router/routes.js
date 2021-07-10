import Vue from 'vue'
import VueRouter from 'vue-router'
import NotFound from '../views/NotFound'
import Home from '../views/Home.vue'
import SingUp from '../views/SingUp.vue'
import Login from '../views/Login.vue'
import UserPage from '../views/UserPage.vue'
import store from '../store/index'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/singup',
    name: 'SingUp',
    component: SingUp
  },
  {
    path: '/userpage',
    name: 'UserPage',
    component: UserPage,
    meta:{
       isAuthenticated:store.state.isAuthenticated
    },
  beforeEnter:((to, from, next) => {
   if(!store.state.isAuthenticated){
     next(false)
   }else{
     store.dispatch('fetchCurrentUser')
     next()
   }
})
  },
  {
    path: '*',
    name: 'not-found',
    component: NotFound
  }
]

const router = new VueRouter({
  linkExactActiveClass: 'active',
  routes
})

router.beforeEach((to, from, next) => {
  if(store.state.isAuthenticated){
    store.dispatch('fetchCurrentUser')
   }else{
     next()
   }
  next()
})

export default router
