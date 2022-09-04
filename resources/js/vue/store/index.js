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
    setUser (state, payLoad) {
      state.currentUser = {
        ...state.currentUser,
        ...payLoad.user
      }
      state.favoriteCats = payLoad.favoriteCats
      state.isAuthenticated = true
    },
    revokeAuthentication (state) {
      state.currentUser = {
        id: -1,
        name: '',
        email: '',
        isAdmin: false,
        token: ''
      }
      state.favoriteCats = []
      state.isAuthenticated = false
    }
  },
  actions: {
    setUser ({ commit }, value) {
      const { token } = value
      const { favoriteCats } = value.userData
      const { email, name, id, isAdmin } = value.userData.user
      commit('setUser', { favoriteCats, user: { token, email, name, id, isAdmin } })
    },
    revokeAuthentication ({ commit }) {
      commit('revokeAuthentication')
    }
  },
  modules: {
  }
})
