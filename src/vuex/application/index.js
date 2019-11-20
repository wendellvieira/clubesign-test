import localforage from 'localforage'
import WBS from 'js/http'
import clone from 'js/clone'
let reset_auth = { user_information: {	name: '', img: '' }, mid: { cod: '' } }

export default {
	namespaced: true,
	state: {
		session: null,
		auth: clone(reset_auth),
		tags: [],
		settings: {},
		layout: {
			pages: []
		}   
	},
	mutations: {
		set_pages(state, payload){
			state.layout.pages = payload
		},

		set_state(state, payload = null){
			if(payload == null){
				state.session = null
				state.auth = clone(reset_auth)
			}else{
				state.session = payload[0]

			if(payload[1].user.type == "repair" || payload[1].user.type == "resell"){
				if(payload[1].user.products.length != undefined) payload[1].user.products = {}
				if(payload[1].user.repairs.length != undefined) payload[1].user.repairs = {}
			}

				state.auth = payload[1].user
			}
		},

		set_permission(state, payload){
		state.auth.products = payload.products
		state.auth.repairs = payload.repairs
		}
	},
	actions: {
		init({commit}){
			WBS.send('public-custom-loadSystem', resp=> {
				if(resp.status == "success" && resp.data != undefined){
					commit("set_init_data", resp.data)
				}
			})
		},
		validate({state}, payload ){
			if(payload != undefined){
				let key = WBS.action_key(payload)
				if(state.auth != null){
					let licenses = state.auth.perfil.licenses
					let index = licenses.findIndex(item => item == key)
					return index == -1 ? false : true
				}else{
					return false
				}
			}else{
				return true
			}
		},
		logon({commit, state}, payload = null){
			return new Promise( async (resolve, reject) => {
				if(payload == null){
					let sid = await localforage.getItem('sid')
					if(sid != null && state.session == null){
						WBS.send('protected-custom-loadSession', async resp=> {
							// console.log(resp)
							if(resp.status == 'success'){
								commit('set_state', [sid, resp.data])
							}else{
								await localforage.removeItem('sid')
								commit('set_state')
							}
							resolve()
						})
					}
				}else{
					await localforage.setItem('sid', payload.sid)
					await commit('set_state', [payload.sid, payload.data])
				}
			})
		},
		logout({commit, state}){
			return new Promise( (resolve, reject) => {
				WBS.send('protected-custom-destroySession', async resp => {
					await localforage.removeItem('sid')
					commit('set_state')
					resolve(true)
				})
			})
		}
	},
	getters: {
		full_profile: state => {
			let comp = 0
			for( let item in state.auth.user_information){
				if(state.auth.user_information[item] != null && state.auth.user_information[item] != ''){
					comp++
				}
			}
			return comp >= 10
		},
		is_loged : state => {
			return state.session != null
		},
		type: state => {
			if(state.auth.type == undefined) return false
			else return state.auth.type
		},
		acType: state => {
			if(state.auth.type == undefined) return false
			else return state.auth.type
		},
		sName: s=> {
			let user = s.auth.user_information
			if( user != '0' && user.name != "") return user.name
			if(s.auth.mid != '0' && s.auth.mid != undefined){
			// console.log(s.auth)
				if(s.auth.mid.nome_fantasia != "") return s.auth.mid.nome_fantasia
				if(s.auth.mid.razao_social != "") return s.auth.mid.razao_social
			}
			return s.auth.user
		},
		sPoints: s => function(points){
			if(s.auth.type == "manufacturer") return 0
			else if(s.auth.type == "master") return 0
			else{
				if(points == null) return parseFloat(s.auth.evaluation).toFixed(0)
				else return parseFloat(points).toFixed(0)
			}
		},
		gImage: function(s){
			return s.auth.avatar
			// let uid =
			// if(uid != '0' && uid.img != "") return uid.img
			// else return null
		},
		color: state => "#603e95",
	}
}
