import apiClient from '@/axios'; // Assurez-vous d'avoir le bon chemin

export default {
  namespaced: true,
  state: {
    isAuthenticated: false,
    user: null
  },
  mutations: {
    setAuth(state, isAuthenticated) {
      state.isAuthenticated = isAuthenticated;
    },
    setUser(state, user) {
      state.user = user;
    }
  },
  actions: {
    async checkAuth({ commit }) {
      try {
        const response = await apiClient.get('/user');
        commit('setAuth', true);
        commit('setUser', response.data);
      } catch (error) {
        commit('setAuth', false);
        commit('setUser', null);
      }
    },
    async logout({ commit }) {
      try {
        await apiClient.post('/logout');
        commit('setAuth', false);
        commit('setUser', null);
        window.location.href = '/'; 
      } catch (error) {
        console.error('Erreur lors de la déconnexion');
      }
    }
  },
  getters: {
    isAuthenticated: state => state.isAuthenticated,
    user: state => state.user
  }
};