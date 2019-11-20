<template>  
	<li v-if='visable' class='item-aside' :class='base_class'>
		<span v-if='type == "header"'>{{title}}</span>	

		<a v-if='type == "action"' @click='$emit("click")'>
			<i v-if='icon != undefined' class='icons' :class='icon'></i> 
			<span class="text-aling">{{title}}</span>
		</a>
			
		
		<router-link :to='link' v-if='type == "sigle" && link != undefined'	>
			<i v-if='icon != undefined' class='icons' :class='icon'></i>
			<span class='text-aling' @click='open("item")'>{{title}}</span>
			<span v-if='labels != undefined' class="pull-right-container">
	            <small 
	            	v-for='label in labels' 
	            	:title='label.title' 
	            	class="label pull-right" :class='"bg-" + label.color' >
					{{label.text}}
	            </small>
            </span>			
		</router-link>

		<a v-if='type == "tree"' @click='open("item")'>
			<i v-if='icon != undefined' class='icons' :class='icon'></i>
			<span class='text-aling'>{{title}}</span>
			<span v-if='labels != undefined' class="pull-right-container">
	            <small 
	            	v-for='label in labels' 
	            	:title='label.title' 
	            	class="label pull-right" :class='"bg-" + label.color' >
					{{label.text}}
	            </small>
            </span>
            <span v-else class="pull-right-container">
              <i class="fa fa-angle-left pull-right" :style='"transform: rotate(" + arrow_rotate + ")"'></i>
            </span>		
		</a>
		<ul class='treeview-menu' 
			v-if='type == "tree" && childs != undefined'
			:style='{height: my_height + "px"}'>
			<li v-for='child in childs' 
				:class='child.link == $route.path ? "active" : ""'>
				<router-link :to='child.link'>
					<i class="fal fa-circle"></i> {{child.text}}
				</router-link>
			</li>		
		</ul>
	</li>
</template>
<script>
	import {mapActions} from 'vuex'
	export default {
		name: 'ItemAside',
		props: [
			'opened',
			'title',
			'icon',
			'link',
			'childs',
			'labels',
			'action'
		],
		data(){
			return {
				my_height: 0,
				arrow_rotate: 0,
				visable: false
			}
		},
		methods:{
			// ...mapGetters(['validate']),
			...mapActions("application", ['validate']),
			open(op){
				if(this.type == 'sigle'){
					this.$emit("clkme", '')					
				}else if(this.type == 'tree'){
					if(this.title == this.opened && op == 'item'){
						this.$emit("clkme", '')
					}else{
						this.$emit("clkme", this.title)					
					}					
				}
			}
		},
		watch: {
			'opened': function(val){
				if(this.childs != undefined){
					if(this.my_height == 0 && this.title == val) {
						this.my_height = 30 * this.childs.length
						this.arrow_rotate = '-90deg'				
					}else{
						this.arrow_rotate = '0deg'
						this.my_height = 0	
					}					
				}
			},
			// 'action': function(val){
			// 	this.validate(val).then(resp => {
			// 		console.log(resp)
			// 	})
			// }
		},
		computed: {
			is_title() { return this.title == undefined ? false : true; },
			is_icon() { return this.icon == undefined ? false : true; },
			is_link() { return this.link == undefined ? false : true; },
			is_childs() { return this.childs == undefined ? false : true; },
			is_labels() { return this.labels == undefined ? false : true; },

			actice_class(){
				if( this.link != undefined && this.$route.path == this.link) return 'active'
				if( this.childs != undefined){
					let sub = this.childs.findIndex(item => item.link == this.$route.path )
					if(sub == -1) return ''
					else return 'active'
				}else return ''
			},

			base_class(){
				if(this.type == 'header') return 'header ' + this.actice_class
				else if(this.type == 'sigle' || this.type == 'action') return this.actice_class
				else return 'treeview ' + this.actice_class
			},

			type(){
				if(this.is_title && !this.is_icon && !this.is_link && !this.is_childs && !this.is_labels ){
					return 'header'					
				}else if( this.is_title && this.is_link && !this.is_childs){
					return 'sigle'
				}else if( this.is_title && this.is_childs ){
					return 'tree'
				}else{
					return 'action'
				}
			}
		},
		mounted(){
			this.validate(this.action).then(resp => {
				this.visable = resp
			})
		}
	};
</script>
<style scoped>
	.sidebar-collapse .item-aside:hover > a {
    	background-color: #9f6ef0;
    	text-decoration: none;
	}
	.sidebar-collapse .item-aside > a {
		height: 44px;
		text-decoration: none;
	}
	.text-aling {}
	.icons {
		font-size: 22px;
   	 	margin-right: 18px;
   	 	float: left;
	}
	.treeview-menu {
		display: block;
		overflow: hidden;
    	-webkit-transition: height linear .4s;
    	-o-transition: height linear .4s;
    	transition: height linear .4s;
	}
	.item-aside {
		cursor: pointer;
		min-height: 44px;
	}
	.fa.fa-angle-left.pull-right{
		-webkit-transition: all linear .4s;
    	-o-transition: all linear .4s;
    	transition: all linear .4s;
	}
</style>