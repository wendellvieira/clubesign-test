<template>
	<div class="modal fade" ref='modal'>
		<div class="modal-dialog modal-lg" role="document">
			<div v-if='data != null' class="modal-content">
				<div v-if='type == "sign"' class="modal-body">
					<div class="cnt-header">
						<div class="avatar-in-mdprop">
							<avatar 
								:name='data.rid.user_information.name' 
								:points='data.rid.evaluation' 
								:img='data.rid.avatar' 
								size='md' type='left' fontColor='#9ca3af'
							/>							
						</div>
						<div class="cnt-value-propostals">R$ {{ contract_value }}</div>	
						<div class="clearfix"></div>				
					</div>
					<div class="line">
						<h3>Produto</h3>
						<div class="clearfix"></div>
						<nav class="cnt-tags">
							<li v-for='(item, id) in data.data'>
								<appTag :id='item.id' :data='item.data' noWidth/>
								<div class="clearfix"></div>
							</li>							
							<div class="clearfix"></div>						
						</nav>
					</div>
					<div v-if='!(data.imagens[0] == "" && data.imagens[1] == "")' class="line">
						<h3>Documentos</h3>
						<div class="clearfix"></div>
						<a v-for='item in data.imagens' :href="path_file+item" target='_blank' class='btn-download'>
							<span>{{ getNameImage(item) }}</span>
							<i class="fal fa-download"></i>
						</a>
						<div class="clearfix"></div>
					</div>
				</div>
				<div v-else class="modal-body">
					<div class="cnt-header">
						<div class="avatar-in-mdprop">
							<avatar 
								:name='data.uid.user_information.name' 
								:points='data.uid.evaluation' 
								:img='data.uid.avatar' 
								size='md' type='left' fontColor='#9ca3af'
							/>							
						</div>
						<!-- <div class="cnt-value-propostals">R$ {{ contract_value }}</div>	 -->
						<div class="clearfix"></div>				
					</div>
					<div class="line">
						<h3>Orçamento</h3>
						<div class="clearfix"></div>
						<nav class="cnt-tags">
							<li v-for='(item, id) in data.tags'>
								<appTag :id='item.id' :data='item.data' noWidth/>
								<div class="clearfix"></div>
							</li>							
							<div class="clearfix"></div>						
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import avatar from 'app/avatar'
	import appTag from 'app/tag/index'
	import {mapState} from 'vuex'
	// import ForceDownload from 'js/force-download'
	export default {
		name: 'ModalOpenPropostal',
		components: {avatar, appTag},
		// mixins: [ForceDownload],
		props: {
			type: {
				default: 'sign'
			}
		},
		data(){
			return {
				data: null
			}
		},
		methods: {
			getNameImage(val){
				let files = ['.jpg', '.jpeg', '.png', '.pdf']
				files.forEach(item => val = val.replace(item, ''))
				try{
					val = JSON.parse(window.atob(val))
					return val[1]
				}catch(e){
					// console.log(e)
					return 'Imagem'
				}			
			},
			open(data){
				this.data = data
				// console.log(data)
				$(this.$refs.modal).modal('show')
			}
		},		
		computed: {
			...mapState("application", {
				path_file: function(state){
					return state.settings.image_path
				}
			}),
			contract_value(){
				return parseFloat( parseFloat(this.data.valor) + parseFloat(this.data.frete) ).toFixed(2)
			},
	
		}
	};
</script>
<style scoped>
	.btn-download {
		float: left;
		background: #01b8a6;
	    border: none;
	    color: #fff;
	    font-size: 22px;
	    padding: 10px 30px;
	    border-radius: 5px;
	    max-width: 49%;	
	    margin-right: 1%;    
	}
	.btn-download span {
		white-space: nowrap;
	    overflow: hidden;
	    text-overflow: ellipsis;
	    width: calc( 100% - 32px );
   		float: left;
	}
	.btn-download i {
		color: #fff;
	    float: left;
	    margin: 5px 0 0px 10px;
	}
	.line {
		display: block;
	}
	.line h3 {
		float: left;
	    color: #c9ced4;
	    border: 1px solid;
	    padding: 10px 25px;
	    font-size: 20px;
	}
	.cnt-tags {
		margin-bottom: 25px;
	}
	.cnt-tags li {
		float: left;
		list-style: none;
	}
	.cnt-header {
		padding-bottom: 10px;
    	border-bottom: 1px solid #e8eaec;
	}
	.avatar-in-mdprop {
		float: left;
    	width: 70%;
	}
	.cnt-value-propostals {
		float: left;
	    font-size: 24px;
	    font-weight: bold;
	    color: #a3bc18;
	    padding: 18px 10px;
	        width: 30%;
	}
</style>