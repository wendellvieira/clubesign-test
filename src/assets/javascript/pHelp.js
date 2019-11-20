import clone from 'js/clone'
export class pHelp {
	constructor(product){
		if( typeof product == "object"){
			this.product = clone(product)			
		}
	}
	get files(){
		if(this.product != undefined){
			if(this.product.files != undefined){
				if(this.product.files.length == 1 && this.product.files[0] != ""){
					return this.product.files					
				}else if(this.product.files.length > 1){
					return this.product.files	
				}else{
					return false
				}
			}else{
				return false
			}			
		}else return false
	}
	get file(){
		if(this.files !== false) return this.files[0]
		else return false
	}
	getTagsValue(target='text', tag=null){
		let resp = []
		if(this.product == undefined) return false
		if(typeof this.product.tags == "object" && this.product.tags != undefined){
			this.product.tags.forEach(i => {
				if(tag == null){
					let fTag = this.target(target, i)
					if(fTag !== '') resp.push(fTag)		
				}else{
					if(i.id == tag){
						let fTag = this.target(target, i)
						if(fTag !== '') resp.push(fTag)	
					}
				}
			})			
		}
		if(resp.length > 0) return resp.join(' ')
		else return ''
	}
	getSpecificTags(target, value, who='text', join=true){
		let resp = ''
		if(this.product == undefined) return false		
		if(typeof this.product.tags == "object" && this.product.tags != undefined){
			this.product.tags.forEach(i => {
				if(this.target(target, i) == value){
					resp = this.target(who, i, join)
				}
			})
		}
		return resp				

	}
	target(id, tag, join=true){
		if(typeof tag.data == "object"){
			let txt = []
			// console.log(tag.data)
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
	}
}