import store from '../vuex/index.js'
import localforage from 'localforage'
import toastr from 'toastr'

export default async (to, from, next) => {
	// console.log(store, to)
	to.meta.from = from.path
	if(to.meta.requireAuth == true){
		if(store.getters['application/is_loged']) next()
		else{
			let sid = await localforage.getItem('sid')

			if(sid != null) {
				await store.dispatch('application/logon')
				next()
			}else{
				toastr.error('É preciso estar logado para entrar nessa página!')
				next('/login')
			}
		}
	}else next()
}
