import Vue from 'vue'
import Vuex from 'vuex'
import state from './globla-state.js'
import application from './application/index.js'
Vue.use(Vuex)
export default new Vuex.Store({
	state,
	modules: {
		application
	}
})