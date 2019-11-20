<template>
	<label class='cnt-tags' :class='{ "active": focus  }'>
		<ul>
			<li v-for='(val, id) in value' :key="id" 
				v-dragging="{ item: val, list: value, group: 'input-tags' }">
				<i class="fal fa-arrows"></i>
				<small v-if='editabled == false'>{{val}}</small>
				<input v-else 
					:title='val'
					type="text" :value='val' 
					@input='update(id, $event.target.value)' 
					class='input-editable' 
				/>
				<span @click='remove(id)'><i class="far fa-times"></i></span>
				<div class="clearfix"></div>
			</li>
			<input type="text" v-model='input' @keyup.enter='add' @focusin='focus = true' @focusout='focus = false'/>
		</ul>
		<div class="clearfix"></div>
	</label>
</template>
<script>
	import clone from 'js/clone'
	export default {
		name: 'Tags',
		props: {
			value: {
				required: true
			},
			editabled: {
				default: false
			}
		},
		data(){
			return {
				input: '',
				focus: false
			}
		},
		methods: {
			update(id, value){
				this.$set(this.value, id, value)
			},
			remove(id){
				let pVal = clone(this.value) 
				pVal.splice(id, 1)
				this.$emit('input', pVal)
			},
			add(){
				let pVal = typeof this.value == "object" ? clone(this.value) : []
				pVal.push(this.input); this.input = ''
				this.$emit('input', pVal)
			}
		}
	};
</script>
<style scoped>
	.input-editable {
		margin: 0px 0px 0px 5px !important;
    	width: 80px;
	}
	.cnt-tags ul li i { cursor: pointer; }
	.cnt-tags ul { float: left; margin: 0px; padding: 0px;}
	.cnt-tags ul li { float: left; list-style: none; background: #d2d6de; padding: 1px 5px; border-radius: 10px; margin: 5px; height: 22px; margin-left: 5px; margin-right: 0px;}
	.cnt-tags ul li small {float: left;margin-top: 2px;  margin-left: 5px; 	}
	.cnt-tags ul li span { float: right;margin-top: 2px;margin-left: 10px;background: #000;color: #fff;width: 16px;height: 16px;border-radius: 16px;font-size: 9px;text-align: center;line-height: 16px;cursor: pointer;transition: 200ms linear all;	}
	.cnt-tags ul li span:hover { background-color: #c30000 }
	.cnt-tags input { border: none;background: none;margin: 5px;margin-left: 10px;font-weight: 100;	}
	.cnt-tags input:focus { outline: none; 	}
	.cnt-tags { display: block;border: 1px solid #d2d6de; min-height: 34px;background: #fff; transition: 200ms linear all;	}
	.active { border-color: #3c8dbc; }
	.cnt-tags:focus { border-color: #3c8dbc; }
</style>