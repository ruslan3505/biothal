const state = {
    token: null,
    user: null,
}

const mutations = {
    SET_TOKEN(state, token) {
        state.token = token;
    },
    SET_USER(state, user) {
        state.user = user;
    },
}

const actions = {
    LOGIN(context, data) {
        const token = data
        context.commit('SET_TOKEN', token);
    },
    SET_USER(context, data) {
        context.commit('SET_USER', data);
    }
}

const getters = {
    getToken: state => state.token,
    user: state => state.user
}

export default {
    state,
    mutations,
    getters,
    actions
}
