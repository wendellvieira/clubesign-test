<template>
	<div class='cnt-input-component' :class='class_inp()'>
		<label 
			:for='"ID_" + inst_id'
			:style='{color: moment_color}'>
			{{title}}
		</label>
		<input v-if='mask != null && type == "text"'
			type="text"
			:id='"ID_" + inst_id'
			:value='value'			
			v-mask='mask'
			:style='{"border-bottom-color": moment_color, color: textColor}'

			@input='$emit("input", $event.target.value)' 
			@focusin='open_input' 
			@focusout='close_input'
			@change='$emit("change", $event.target.value)' 
		/>

		<input v-else-if='mask == null && type == "text"'
			type="text"
			:id='"ID_" + inst_id'
			:value='value'
			:style='{"border-bottom-color": moment_color, color: textColor}'

			@input='$emit("input", $event.target.value)' 
			@focusin='open_input' 
			@focusout='close_input'
			@change='$emit("change", $event.target.value)' 
		/>

		<input v-else-if='type == "password"'
			type="password"
			:id='"ID_" + inst_id'
			:value='value'
			:style='{"border-bottom-color": moment_color, color: textColor}'

			@input='$emit("input", $event.target.value)' 
			@focusin='open_input' 
			@focusout='close_input'
			@keyup.enter='$emit("enter")' 
			@change='$emit("change", $event.target.value)'
		/>
	</div>
</template>
<script>
	import md5 from 'js/md5'

	export default {
		name: 'RegisterInput',
		data(){
			return {
				is_focus: false
			}
		},
		props: {
			value: {
				required: true
			},
			title: {
				required: true
			},
			mask: {
				default: null,
			}, 
			color: {
				default: '#dd6881'
			},
			type: {
				default: 'text'
			},
			xs: {
				default: null
			},
			textColor: {
				default: '#dd6881'
			}
		},
		computed: {			
			inst_id(){
				return md5.get( Math.random() )
			},
			moment_color(){
				if(this.is_focus || this.value != '') return this.color || '#dd6881'
				else return this.textColor || '#9ca3af'
			}	
		},
		methods: {

			open_input(){
				this.$emit('focusin', this.id)
				this.is_focus = true
			},
			close_input(){
				if(this.value == ''){
					this.is_focus = false					
				}
			},
			class_inp(){
				let other = ''
				if(this.is_focus == true){
					other = 'open_input'
				}else{
					if(this.value != '') {
						if(this.status == 2){
							other = 'open_input'
						}else if( this.status == 3 ){
							other = 'open_input opacity'
						}
					}
				}
				if(this.xs != undefined ) return `input-xs ${other}` 
				else return other
			}

		}		
	};
</script>
<style scoped>
	



	.opacity {
		opacity: .4
	}
	.cnt-input-component {
		display: block;
		position: relative;
		margin-top: 50px;
	}
	.cnt-input-component label {
		color: #9ca3af;
		margin: 0px;
    	display: block;
    	font-size: 18px;
    	position: absolute;
    	top: 30px;
    	transition: all linear .1s;
    	font-weight: 400;
	}
	.cnt-input-component input {
		background: none;
	    border: none;
	    border-bottom: 3px solid #9ca3af;
	    width: 100%;
	    display: block;
	    font-size: 18px;
	    color: #9ca3af;
	    padding: 30px 0px 10px 0px;
	    transition: all linear .1s;
	    font-weight: 400;

	}
	.cnt-input-component input:focus {
		outline: none;
	}


	.open_input input {
		border-color: #dd6881;
	}

	.input-xs {
		margin: 0px;
	}
	.input-xs label {
		font-size: 14px;
		top: 22px;
	}
	.input-xs input {
	    padding: 20px 0px 4px 0px;
	    font-size: 16px;
	    border-bottom: 1px solid #9ca3af;
	}
	.open_input label{
		top: 0px ;
		color: #dd6881;
	}


</style>