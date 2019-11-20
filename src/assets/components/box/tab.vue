<template>
	<div v-if='$parent.is_active(this.title)' class="box-tab" 
		:class='{ "no-padding" : noPadding != "nao" }'>
		<slot></slot>
	</div>
</template>
<script>
	export default {
		props: {
			title: {
				required: true
			},
			span: {
				default: 0
			},
			noPadding: {
				default: 'nao'
			}
		},
		watch: {
			"title": function(val){
				this.updateTabs({title: val, span: this.span})
			},
			"span": function(val){
				this.updateTabs({title: this.title, span: val})
			}
		},
		methods: {
			updateTabs(inp){
				this.$parent.createTabs(inp.title, inp.span)
			}
		},
		mounted(){
			this.$parent.createTabs(this.title, this.span)
		}
	};
</script>
<style scoped>
	.no-padding {
		padding: 0px !important;
	}
	.box-tab {
		padding: 15px;
	}
</style>