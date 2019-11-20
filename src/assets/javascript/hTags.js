import {mapState} from 'vuex'
export default {
	methods: {
		get_target(id, tag, join=true){
			if(typeof tag.data == "object"){
				let txt = []
				tag.data.forEach(tag => {
					if(tag.target == id) {
						txt.push(tag.value)
					}
				})
				if(txt.length > 0){
					if(join) return txt.join(' ')
					else return txt	
				}else return ''
			}
		},
		get_targets(tags, target, join = true){
			let rTags = []
			tags.forEach( item => {
				const search = this.get_target(target, item)
				if(search != undefined && search != ''){
					rTags.push(search)
				}
			})
			if(rTags.length > 0){
				if(join) return rTags.join(' ')
				else return rTags	
			}else return ''
	},
		get_base_tag(id){
			return this.tags.find( el => el.id == id )
		},

		get_color(id){
			return ( this.get_base_tag(id) || { cor: '#000' } ).cor
		},
		get_icon(id){
			return ( this.get_base_tag(id) || { icon: '' } ).icon
		},
		get_input(tags, input = "all", resp = "text"){
			return this.search_tags(tags, input, "input", resp)
		},
		get_attr(id, tag, espect=null, r=null){
			if(Array.isArray(tag.data)){
				for(let key in tag.data) {
					for(let attr in tag.data[key]){
						if(attr == id){
							if(espect == null) return tag.data[key][attr]
							else {
								if(tag.data[key][attr] == espect){
									if(r == null) tag.data[key][attr]
									else return tag.data[key][r]
								}
							}
						}
					}					
				}
			}
			return false
		},
		search_tags(tags, val = 'all', arg='input', resp='text', join=true){
			let rTags = []
			tags.forEach(tag => {
				if( this.get_target(arg, tag) == val || val == 'all'){
					rTags.push( this.get_target(resp, tag, join) )					
				}
			})
			if(rTags.length > 0){
				if(join) return rTags.join(' ')
				else return rTags	
			}else return ''
		},
		gettagById(tags, id, target='text', join=true){
			if(Array.isArray(tags)){
				let resp = []
				tags.forEach( item => {
					if(item.id == id) resp.push( this.get_target(target, item) )
				})
				if(resp.length > 0){
					if(join) return resp.join(' ')
					else return resp	
				}else return ''
			}else{
				return false
			}
		}
	},
	computed: {
		...mapState('application', {
			tags: s=> s.tags
		})
	}
}