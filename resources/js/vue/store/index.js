import Vue from 'vue'
import Vuex from 'vuex'
import {apiHelper} from './../utils/helpers'
const getToken = () => localStorage.getItem('token')

Vue.use(Vuex)

export default new Vuex.Store ({
  state:{
     currentUser: {
      id: -1,
      name: '',
      email: ''
    },
    isAuthenticated: false
  },
  mutations:{
     setUser (state, currentUser) {
          state.currentUser = {
        ...state.currentUser,
        ...currentUser.user
      }
      state.isAuthenticated = true
    },
    revokeAuthentication (state) {
      state.currentUser = {}
      state.isAuthenticated = false
      localStorage.removeItem('token')
    }
  },
  actions:{
     fetchCurrentUser ({ commit }) {
         apiHelper.get('/CurrentUser',{
      headers: { Authorization: `Bearer ${getToken()}`}})
  .then((obj)=>{
    const { id, name, email } = obj.data.user

        commit('setUser', {
          id,
          name,
          email,
        })
  })
  .catch((error)=>{
    console.log(error)
  })
  }
 }
})