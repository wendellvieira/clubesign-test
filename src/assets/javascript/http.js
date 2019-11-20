import md5 from 'js/md5'
import STORE from '../../vuex/index.js'
import jQuery from 'jquery'
import toastr from 'toastr'
import is_json from 'js/is_json'
import localforage from 'localforage'

export default {

	//Configurações
	version: '2.0',
	key : '3bea674470cee171754caafcf9b1481c',
	url_base: 'http://localhost/',

	action_key(acao){
		return md5.get(acao + '-' + this.key)
	},

	// funções
	async access_key(acao){
		//Validação inicial
		if(acao == undefined || acao == '') return false
		let action_key = this.action_key(acao)
		let info = acao.split('-')

		//validação de acao
		if(info.length != 3) return false
		if(info[0] == 'public'){
			return action_key
		}else{
			let sid = await localforage.getItem('sid')
			if(sid != null){
				return action_key + '.' + sid
			}else{
				return false
			}
		}
	},

	async send(acao, data, fx, type){
		let token = await this.access_key(acao)
		// console.log(token)
		if(token != false){
			let preData = { token: token }
			let sType = type != undefined ? type : 'hibrid';
			let callback = null

			preData.data = data != undefined && typeof data != 'function' ? data : null
			if( typeof fx == 'string' && type == undefined) sType = fx
			if( typeof data == "function") callback = data
			else if(typeof fx == 'function') callback = fx

			jQuery.post(this.url_base, preData, 'json').then(
				resp => {
					if(type == 'html') callback(resp)
					else if( type == 'json'){
						if( is_json(resp) ) callback( JSON.parse(resp) )
						else{
							toastr.warning('500: Erro interno!')
							console.log(resp)
						}
					}else{
						if( is_json(resp) ) callback( JSON.parse(resp) )
						else callback( resp )
					}
				},
				resp=>{
					toastr.warning('401: Não foi possível conectar-se ao servidor!')
				}
			)

		}else{
			toastr.warning(`A permissão '${acao}' não pode ser executada em local publico!`)
		}

	}
}
