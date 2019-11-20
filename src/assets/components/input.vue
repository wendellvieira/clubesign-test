<template>
	<div class='cnt-input-component' :class='class_inp()' >
		<label 
			v-if='type == "text" || type == "password"'
			:for='"ID_" + inst_id'
			:style='{color: moment_color}'>
			{{title}}
		</label>
		<input v-if='mask != null && type == "text"'
			type="text"
			:id='"ID_" + inst_id' ref='input'
			:value='value'			
			v-mask='mask'
			:style='{"border-bottom-color": moment_color}'
			:disabled='disabled'

			@input='input($event.target.value)' 
			@focusin='open_input' 
			@focusout='close_input'
			@change='$emit("change", $event)' 
		/>
		<input v-else-if='mask == null && type == "text"'
			type="text"
			:id='"ID_" + inst_id' ref='input'
			:value='value'
			:style='{"border-bottom-color": moment_color}'
			:disabled='disabled'

			@input='input($event.target.value)' 
			@focusin='open_input' 
			@focusout='close_input' 
			@change='$emit("change", $event)'
		/>	
		<input v-else-if='type == "password"'
			type="password"
			:id='"ID_" + inst_id' ref='input'
			:value='value'
			:style='{"border-bottom-color": moment_color}'
			:disabled='disabled'

			@input='input($event.target.value)' 
			@focusin='open_input' 
			@focusout='close_input'
			@change='$emit("change", $event)' 
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
				default: ''
			}, 
			title: {
				required: true
			}, 
			mask: {
				default: null
			}, 
			id: {}, 
			color: {
				default: '#dd6881'
			}, 
			validate: {
				default: null
			}, 
			type: {
				default: 'text'
			},
			disabled: {
				default: false
			}
		},
		computed: {			
			inst_id(){
				return md5.get( Math.random() )
			},
			moment_color(){
				if(this.is_focus || this.value != '') return this.color || '#dd6881'
				else return '#9ca3af'
			}	
		},
		methods: {
			focus(){
				this.$refs.input.focus()
			},
			input(val){
				this.$emit("input", val)
				if(this.validate != null) this.validate(val)
			},
			open_input(){
				this.$emit('focusin', this.id)
				this.is_focus = true
			},
			close_input(){
				this.is_focus = false				
			},
			class_inp(){
				if(this.is_focus == true){
					return 'open_input'
				}else{
					if(this.value != '') {
						return 'open_input'						
					}else{
						return ''
					}
				}
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
		/*margin-top: 50px;*/
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

	.open_input label{
		top: 0px ;
		color: #dd6881;
	}
	.open_input input {
		border-color: #dd6881;
	}


</style>