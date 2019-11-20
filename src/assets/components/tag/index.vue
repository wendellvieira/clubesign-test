<template>
	<div class='cnt-tag'  :style='{"width": get_width()}'
		:class='base_class' v-if='get_tag() != undefined && !none()'>
		<div class="body-tag" 
			@click='$emit("click", scope)' 
			:style='{ "background-color" : get_tag().cor }'>
			<div v-if='get_tag().icon != ""' :title='get_tag().title' class="cnt-icon">
				<span class='icon'>
					<img :src="get_tag().icon">
				</span>
			</div>

			<!-- Com o Input -->
			<input v-if='is_editable()' @change='$emit("update")'
				v-model='get_editable_text'
				class='input-text' :placeholder='get_placeholder()' 
				type="text"
			/>
			<!-- Apenas texto -->
			<div v-if='is_text()' :class='{ "text-overflow" : get_width() != undefined }'
				class="text" v-html='get_text()' :title='get_text("clear")'
			></div>

			<!-- Com a amostra de cor -->
			<div v-if='is_amostra_color()' class="text">
				<span v-html='get_text()' :title='get_text("clear")'></span>
				<div class="amostra-cor" :style='{"background-color" : get_color()}'></div>
			</div>

		<!-- 	<div v-if='is_description()' 
				:class='{ "text-overflow" : get_width() != undefined }' class="text tag-clicable" 
				v-html='get_text()' :title='get_text("clear")'
				@click='openMdDescription'
			></div> -->

			<div class="clearfix"></div>			
		</div>
		<div v-if='trash != undefined && get_tag().block == 0' class="cnt-actions">
			<i class='action-trash fal fa-trash-alt' 
				@click='$emit("trash", aCod)'
			/>
		</div>
		<div class="clearfix"></div>
		<!-- <md-description ref='mdDesc' v-if='is_description()' :scope='scope'/> -->
	</div>
</template>
<script>
	import {mapState} from 'vuex'
	// import mdDescription from './components/mdDescriotion.vue'

	export default {
		name: 'AppTags',
		// components: {mdDescription},
		props: [
			'id', 'data', 'aCod', 
			'block', 'left', 'right',
			'sm', 'md', 'lg', 
			'trash', 'add', 'noWidth',
			'fx', 'metadata'
		],
		data(){
			return {
				scope: [],
			}
		},
		methods: {
			// openMdDescription(){
			// 	this.$refs.mdDesc.open()
			// },
			// verificando tipo
			is_text(){
				return !this.is_amostra_color() && !this.is_editable() 
				// && !this.is_description()
			},
			is_amostra_color(){
				let editable = this.get_target('hex')
				if(editable.length > 0){
					return true
				}else {
					return false
				}
			},
			is_editable(){
				let editable = this.get_target('editable')
				if(editable.length > 0){
					return editable[0].value
				}else {
					return false
				}
			},
			none(){
				return this.extract(this.get_target('display'), "value").join("") == "none" ? true : false
			},
			// is_description(){
			// 	const desc = this.get_target("md_description")
			// 	if(desc.length == 1) return true
			// 	else return false
			// },


			// atributos
			get_color(){
				let hex = this.get_target('hex')
				if(hex.length > 0){
					return hex[0].value
				}else {
					return false
				}
			},
			get_placeholder(){
				if(this.is_editable()){
					let placeholder = this.get_target('placeholder')
					placeholder = this.extract(placeholder, 'value')
					return placeholder.join(' ')
				}else{
					return ''
				}
			},
			get_text(clear=null){
				let texts = this.get_target("text")
				texts = this.extract(texts, 'value')
				if(clear == null) return texts.join(' ')
				else return String(texts.join(' ')).replace(/<(?!br\s*\/?)[^>]+>/g, '')
			},
			get_width(){
				if(this.noWidth == undefined){
					let width = this.get_target('width')
					if(width.length > 0){
						return width[0].value
					}					
				}
			},


			get_target(target){
				return this.scope.filter(item => {
					if(item.target != undefined){
						if(item.target == target) return item
					}
				})
			},
			get_tag(){
				if(this.id == undefined) return undefined
				if(this.tags == undefined) return undefined
				return this.tags.find(item => item.id == this.id)
			},
			extract(array, key){
				let nArray = []
				array.forEach(item => {
					if(item[key] != undefined) nArray.push(item[key])
				})
				return nArray
			},
			
		},
		watch: {
			data: function(val){
				if(typeof val == 'object' && this.get_tag() != undefined){
					this.scope = this.get_tag().default_data.concat(val)				
				}else if(this.get_tag() != undefined && val == undefined){
					this.scope = this.get_tag().default_data
				}else if(this.get_tag() == undefined && typeof val == 'object'){
					this.scope = val
				}
			}
		},
		computed: {
			...mapState('application', ['tags']),

			get_editable_text:{
				get(){
					if(this.is_editable()){
						let text = this.get_target('text')
						return text[0].value
					}else{
						return ''
					}
				},
				set(val){
					if(this.is_editable()){
						let index = this.scope.findIndex(item => item.target == "text")
						if(index != -1){
							this.$set(this.scope[index], 'value', val)
						}else{
							this.scope.push({target: 'text', value: val})
						}					
					}
				}
				
			},
			base_class(){
				let import_class = ''
				if(this.block != undefined) import_class += 'block'
				else if(this.right != undefined) import_class += 'right'
				else import_class += 'left'

				if(this.trash != undefined && this.get_tag().block == 0)  import_class += ' has-buttons'
				if(this.add != undefined && this.get_tag().block == 0) import_class += ' has-buttons'

				if(this.sm != undefined) import_class += ' sm'
				else if(this.lg != undefined) import_class += ' lg'
				else import_class += ' md'

				return import_class 
			}
		},
		mounted(){
			// console.log(this.data)
			if(typeof this.data == 'object' && this.get_tag() != undefined){
				this.scope = this.get_tag().default_data.concat(this.data)				
			}else if(this.get_tag() != undefined && this.data == undefined){
				this.scope = this.get_tag().default_data
			}else if(this.get_tag() == undefined && typeof this.data == 'object'){
				this.scope = this.data
			}
			if(typeof this.fx == 'function'){
				this.fx(this)
			}
		}
	};
</script>
<style scoped>
	.tag-clicable {
		cursor: pointer;
		transition: linear 100ms background;
	}
	.tag-clicable:hover {
		background: rgba(0, 0, 0, 0.05);
	}
	.amostra-cor {
		width: 20px;
	    height: 20px;
	    display: inline-block;
	    border: 3px solid;
	    border-radius: 20px;
	    margin: 0px 0px -5px 10px;
	    background-color: #cecece;
	}
	.input-text {
		background: none;
   		border: none;
   		padding-left: 10px; 
   		padding-right: 10px; 
   		color: #fff;
	}
	.input-text:focus {
		outline: none;
	}
	.input-text::placeholder {
		color: rgba(255,255,255,0.7);
	}
	.sm .body-tag .text, .sm .body-tag .input-text {
		font-size: 17px; padding-top: 6px; padding-bottom: 6px; width: calc( 100% - 36px )
	}
	.md .body-tag .text, .md .body-tag .input-text { 
		font-size: 20px; padding-top: 7.5px; padding-bottom: 7.5px; width: calc( 100% - 43px )
	}
	.lg .body-tag .text, .lg .body-tag .input-text { 
		font-size: 24px; padding-top: 8.5px; padding-bottom: 8.5px; width: calc( 100% - 51px )
	}
	.text-overflow { text-overflow: ellipsis; overflow: hidden; }

	.cnt-icon {float: left; background: rgba(0, 0, 0, 0.1); text-align: center;}
	.icon img { 
		user-select: none;
		display: block;
	    width: 100%;
	    height: 100%;

	}

	.icon {user-select: none;}
	.sm .body-tag .cnt-icon .icon { width: 17px; height: 17px; display: block; margin: 9.5px;}
	.md .body-tag .cnt-icon .icon { width: 20px; height: 20px; display: block; margin: 11.5px; }
	.lg .body-tag .cnt-icon .icon { width: 24px; height: 24px; display: block; margin: 13.5px; }

	

	.block .body-tag .text 	{ white-space: nowrap; overflow: hidden; text-overflow: ellipsis;  }
	.left .body-tag .text, .right .body-tag .text {white-space: nowrap;}
	.block { display: block; }
	.right { float: right;}
	.left { float: left; }

	.right .body-tag .text, .left .body-tag .text{
		padding-left: 10px; padding-right: 20px; 
	}

	.has-buttons .body-tag { width: calc( 100% - 32px ); float: left; }

	.right { margin-left: 10px;}
	.left { margin-right: 2px;}

	.cnt-tag { margin-bottom: 3px; overflow: hidden; max-width: 100%;}
	.body-tag { border-radius: 3px;  max-width: 100%; overflow: hidden;}
	
	
	.text { color: #fff; padding: 0px 5px; float: left; user-select: none; font-weight: bold;}
	.cnt-actions { float: left; width: 32px; }

	.action-trash {display: block; margin: auto; }
	.action-trash:hover { animation: tada 1s linear; }

	.sm .action-trash { padding: 2.5px 0px; }
	.md .action-trash { padding: 8px 0px; }
	.lg .action-trash { padding: 14.5px 0px;}

	
</style>
<style>
	.cnt-title small{
		color: rgba(255, 255, 255, 0.5)
	}
</style>
