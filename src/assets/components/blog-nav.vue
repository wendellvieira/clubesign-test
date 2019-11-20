<template>		
	<nav class='nav-cats'>
		<ul ref='cats' v-for='(cat, id) in cats' 
			@click='click_me(id)' :class='{"c-white": id==value}' v-html='cat.text'>
		</ul>
		<li	v-if='active' :style='style_active'></li>
	</nav>
</template>
<script>
	export default {
		name: 'CatPost',
		props: {
			value: {
				default: 0
			},
			cats: {
				require: true,
				default: []
			},
			color: {
				default: null
			}
		},
		data(){
			return {
				active: true,
				style_active: {
					"width": "0px",
					"background-color": "#70279a",
					"left": "0px"
				}
			}
		},
		methods:{
			click_me(id){
				this.$emit("input", id)
				this.activeMove(id)
			},
			width(id){
				if(this.$refs.cats[id] != undefined) return this.$refs.cats[id].clientWidth
				else return 0
			},
			activeMove(id){
				if( this.width(id) == 0 ){
					return setTimeout(e=>this.activeMove(id), 100)					
				}else{
					let left = 0
					for(let i=0; i < id; i++){
						left+= this.width(i)
					}
					this.style_active = {
						"width" : this.width(id) + "px",  
						"background-color": this.cats[id].color || this.color,
						"left": left + "px" 
					}					
				}
			},
			start(){
				if(this.$refs.cats == undefined) this.start()
				else this.activeMove(this.value)
			}
		},
		mounted(){
			this.start()
		}
	};
</script>
<style scoped>
	.nav-cats * {
		font-weight: bold !important;
	}
	.desc {
		color: #757575;
	    margin-top: 30px;
	    margin-left: 15px;
	}
	.btn-box-tool, .c-white {
		color: #fff !important;
	}
	.box {
		border: none;
    	overflow: hidden;
	}
	.box-header { 
		background-color: #48515e;
    	color: #fff;
    	cursor: pointer;
    	transition: linear opacity 150ms;
	}
	.box-title {
		font-weight: 100;
	    font-family: 'Aller';
	    margin-top: 2px;
	    margin-bottom: -22px;
	}
	.nav-cats {
		display: flex;
		border: 1px solid #e2e2e2;
	    border-radius: 30px;
	    width: max-content;
	    position: relative;
	    overflow: hidden;
	}
	.nav-cats ul {
		padding: 10px 30px;
	    margin: 0px;
	    color: #868686;
	    cursor: pointer;
	    position: relative;
	    z-index: 2;
	    transition: linear .2s color;

	}
	.nav-cats li {
		position: absolute;
	    list-style: none;
	    height: 40px;
	    width: 40px;
	    z-index: 0;
	    background: red;
	    border-radius: 40px;
	    transition: linear .2s all;
	}
</style>