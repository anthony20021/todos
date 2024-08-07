import fetchWithCredentials from '@/network';

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
    async checkAuth({ commit, state }) {
        if (state.isAuthenticated) {
            return;
        }
        try {
            const response = await fetchWithCredentials('/user', 'GET', null);
            // console.log('Authentication response:', response);
            commit('setUser', response);
            commit('setAuth', true);
        } catch (error) {
            // console.error('Authentication check error:', error);
            commit('setAuth', false);
            commit('setUser', null);
        }
    },

    async logout({ commit }) {
      try {
        await fetchWithCredentials('/logout', 'POST');
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
