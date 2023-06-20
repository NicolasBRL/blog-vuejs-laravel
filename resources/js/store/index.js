
import { createStore } from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import router from '../router';

const store = createStore({
  plugins: [createPersistedState()],
  namespaced: true,
    state: {
        token: null,
        user: null,
    },
    mutations: {
        SET_TOKEN(state, token) {
            state.token = token
        },
          
        SET_USER(state, user) {
            state.user = user
        },
          
        LOGOUT(state) {
            state.token = null
            state.user = null
        }
    },
    actions: {
        login({ commit }, {token, user}) {
            commit('SET_TOKEN', token)
            commit('SET_USER', user)

            router.push({ name: 'home'}) // Redirige sur la home
        },

        setUser({ commit }, {user}) {
            commit('SET_USER', user)
        },

        logout({ commit }) {
            commit('LOGOUT')
            router.push({ name: 'login'}) // Redirige sur la page de connexion
        }
    },
    getters: {
      isAuthenticated(state) {
        return state.token !== null
      },
      currentUser(state) {
        return state.user
      }
    }
})

export default store;