const state = {
  basket: false,
  basket_info: false,

  data_basket_info: null
}

const mutations = {}
const getters = {}
const actions = {}

Object.keys(state).map(key => {
  getters[`visible_${key}`] = ((state) => state[key]);
  mutations[`change_visible_${key}`.toUpperCase()] = (state, visible) => state[key] = visible;
  actions[`action_visible_${key}`.toUpperCase()] = (context, visible) => {if(context.state[key] !== visible) context.commit(`change_visible_${key}`.toUpperCase(), visible)}
})

export default {
  namespaced: true,
  state,
  mutations,
  getters,
  actions
}
