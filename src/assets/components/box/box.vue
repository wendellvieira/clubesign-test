<template>
	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li v-for='(tab, id) in tabs'
				@click='active=id'
				:class='{"active": active==id}'>
				{{tab.title}}
				<span v-if='tab.span >= 1'>{{ tab.span }}</span>
			</li>
			<div v-if='$slots.tools != undefined' class="pull-right">
				<slot name='tools'></slot>
			</div>
			
		</ul>
		<div class="tab-content">
			<slot></slot>			
		</div>
	</div>
</template>
<script>
	export default {
		data(){
			return {
				tabs: [],
				active: 0
			}
		},
		methods:{
			set(id){
				this.active = id
			},
			getIndex(title){
				return this.tabs.findIndex(t => t.title == title)
			},
			createTabs(title, span){
				let index = this.getIndex(title)
				if(index == -1) this.tabs.push({title: title, span: span})
				else this.$set(this.tabs, index, {title: title, span: span})
			},
			is_active(title){
				let index = this.getIndex(title)
				return index == this.active
			}
		}
	};
</script>
<style scoped>
	.nav-tabs-custom {
		background: #fff;
	    border: 1px solid #eaeaea;
	    border-radius: 3px;
	}
	.pull-right {
		margin: 13px 14px;
	}
	.nav-tabs li span {
		background: #ed708e;
	    color: #fff;
	    padding: 1px 5px;
	    border-radius: 3px;
	    font-size: 14px;
	    text-align: center;
	}	
	.nav-tabs li {
		font-size: 18px;
	    color: #d0d3d9;
	    letter-spacing: 2px;
	    padding: 15px;
	    margin: 0px 0px -1px 30px !important;
	    font-weight: 400;
	    cursor: pointer;
	}
	.nav-tabs li.active {
	    color: #9ca3af;
	    border: none;
	    border-bottom: 2px solid #7fc0eb; 

	}
</style>