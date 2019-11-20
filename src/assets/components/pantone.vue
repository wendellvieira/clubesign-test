<template>
	<div class='ctn-pantone'>
		<header>
			<input type="text" :placeholder="colorbr !== false ? 'Cor' : 'Pantone' " :disabled='block' 
			 v-model='filter' @focusin='nav = 1' @focusout='close'>
			<div class="selected-color" :style='{"background-color": value }'></div>
			<div class="clearfix"></div>
		</header>
		<aside class='cnt-options' :class='{"close-pantone" : nav == 0 || block}' >
			<nav class='options'>
				<ul class='ctn-itens'>
					<li v-for='pantone in renderPantoneList' 
						@click='select(pantone)'
						:title='pantone.cod'>
							<span >{{pantone.cod}}</span>
							<div class="selected-color" 
								:style='{"background-color": pantone.hex }'></div>
							<div class="clearfix"></div>
					</li>
					<div class="clearfix"></div>					
				</ul>
				<li v-if='renderPantoneList.length > max' class='list-plus'>
					<i class="fa fa-spin fa-spinner"></i>
					Carregando
				</li>				
			</nav>			
		</aside>
	</div>
</template>
<script>
	import pantone from 'js/pantone'
	import color_br from 'js/color_br'

	export default {
		name: 'SelectPantone',
		props: {
			value: {
				default: '#9ca3af',
				required: true				
			}
		},
		data(){
			return {
				nav: 0,
				all_pantone: pantone,
				filter: '',
				max: 50,
				block: false,
				colorbr: false
			}
		},
		methods: {
			setStatus(val){
				if(val == 3){
					this.filter = 'Branco'
					this.setColor('#FFFFFF', true)
				}else{
					this.setColor(null, false)
					if(val == 1){
						this.all_pantone = color_br
					}else{
						this.all_pantone = pantone
					}
				}
			},
			close(){
				setTimeout(e=>{this.nav = 0}, 100)
			},
			clear(){
				this.filter = ''
			},
			setColor(color, block = false){
				// console.log(color)
				if(color != null){
					this.$emit('input', color)					
				}else{
					this.$emit('input', '#9ca3af')					
					this.filter = ''
				}
				this.block = block
			},
			select(pantone){
				this.filter = pantone.cod
				this.$emit('input', pantone.hex)
			},
			scroll(e){
				if(this.renderPantoneList.length > this.max){
					let scroll = $('.options').scrollTop()
					let height = $('.ctn-itens').height()-230
					setTimeout(() => {
						if(scroll == height) this.max += 50					
					}, 800)					
				}
			}
		},
		computed: {
			renderPantoneList(){
				return this.all_pantone.filter( item => {
					if(this.filter == ''){
						if(item.id <= this.max) return item						
					}else{
						let cod = item.cod.toLowerCase()
						if(cod.indexOf(this.filter.toLowerCase()) != -1 ) return item
					}
				})
			}
		},
		mounted(){
			$('.options').on('scroll', this.scroll)
			// if(this.colorbr !== false) this.all_pantone = color_br
		}

	}
</script>

<style scoped>
	.ctn-itens {
		padding: 0px;
		margin: 0px;
		border: none;
	}
	.list-plus {
		display: block;
	    width: 100%;
	    color: #fff;
	    text-align: center;
	    cursor: pointer;
	}
	header {
		background-color: #9ca3af;
   		height: 50px;
	}
	header input::placeholder {
		color: rgba(255,255,255,0.5);
	}
	header input, .options li span  {
		float: left;
	    width: calc( 100% - 50px);
	    height: 50px;
	    background: none;
	    border: none;
	    color: #fff;
	    font-size: 18px;
	    padding: 15px;
	}
	header input:focus { outline: none; }

	.cnt-options {
		position: absolute;
	    height: 0px;
	    width: 100%;
	    z-index: 100;
	}

	.selected-color {
		width: 36px;
	    height: 36px;
	    float: right;
	    background-color: #fff;
	    border-radius: 36px;
	    border: 5px solid #fff;
	    margin: 7px;
	}

	.ctn-pantone { 
		display: block;
	    position: relative;
	    width: 100%;
	    height: 50px;
	    margin: 0px;
	    padding: 0px;
	}

	.close-pantone .options {
		max-height: 0px;
	}
	.options {
		background-color: #8e95a1;
		max-height: 250px;
    	overflow-y: auto;
    	transition: max-height 0.3s linear;
	}
	.options li:nth-child(2n) { background-color: #868d99;  }

	.options::-webkit-scrollbar-track {
	    background-color: #868d9a;
	}
	.options::-webkit-scrollbar {
	    width: 5px;
	    background: #868d9a;
	}
	.options::-webkit-scrollbar-thumb {
	    background: #c8ced9;
	    border-radius: 10px;
	}

	

	.options li { cursor: pointer; list-style: none; }
	.options li span { 
		padding-top: 11px; 
		white-space: nowrap;
	    overflow: hidden;
	    text-overflow: ellipsis;
	}
	.options li .selected-color {}
</style>