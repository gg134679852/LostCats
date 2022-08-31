import { createStore } from 'vuex'

export default createStore({
  state: {
    currentUser: {
      id: -1,
      name: '',
      email: '',
      isAdmin: false,
      token: ''
    },
    favoriteCats: [],
    isAuthenticated: false
  },
  getters: {
  },
  mutations: {
    setUser(state, payLoad) {
      state.currentUser = {
        ...state.currentUser,
        ...payLoad.user
      }
      state.favoriteCats = payLoad.favoriteCats
      state.isAuthenticated = true
    },
    revokeAuthentication(state) {
      state.currentUser = {}
      state.favoriteCats = []
      state.isAuthenticated = false
      localStorage.removeItem('token')
    }
  },
  actions: {
    setUser({ commit }, value) {
      const { token } = value
      const { favoriteCats } = value.userData
      const { email, name, id, isAdmin } = value.userData.user
      commit('setUser', { favoriteCats, user: { token, email, name, id, isAdmin } })
    }
  },
  modules: {
  }
})
