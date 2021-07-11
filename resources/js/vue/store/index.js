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
    favoriteCats:[],
    isAuthenticated: false
  },
  mutations:{
     setUser (state, currentUser) {
          state.currentUser = {
        ...state.currentUser,
        ...currentUser.user
      }
      state.favoriteCats = currentUser.favoriteCats
      state.isAuthenticated = true
    },
    revokeAuthentication (state) {
      state.currentUser = {}
      state.favoriteCats = []
      state.isAuthenticated = false
      localStorage.removeItem('token')
    },
    updateFavoriteCats(state,data){
      console.log(data)
      state.favoriteCats = data
    }
  },
  actions:{
     fetchCurrentUser ({ commit }) {
    apiHelper.get('CurrentUser',{
      headers: { Authorization: `Bearer ${getToken()}`}})
  .then((obj)=>{
    const { id, name, email } = obj.data.user
    const favoriteCats = obj.data.favoriteCats
        commit('setUser', {
          id,
          name,
          email,
          favoriteCats
        })
  })
  .catch((error)=>{
    console.log(error)
  })
  },
  getFavoriteCats ({ commit }){
     apiHelper.get('CurrentUser',{
      headers: { Authorization: `Bearer ${getToken()}`}})
  .then((obj)=>{
    console.log(obj)
    const favoriteCats = obj.data.favoriteCats
    commit('updateFavoriteCats', {
          favoriteCats
        })
   })
  }
 }
})