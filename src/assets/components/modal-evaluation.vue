<template>
	<div class="modal fade" ref='modal' data-backdrop='static'>
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body">
					<div class="cnt-avatares">
						<h3 class="title">Avalie os seguintes signer`s</h3>
						<nav class="avatares">
							<ul v-for='(item, id) in pendecias' :class='{"active": active == id}'>
								<avatar 
									:name='item.from.user_information.name'
									:img='item.from.avatar'
									:points='item.from.evaluation'
									size='md' type='left'
								/>
							</ul>
						</nav>
						
					</div>
					<div class="cnt-log-avliation" v-if='pendecias[active] != undefined'>
						<div class="cnt-data-interation">
							<navBtn :abas='nav' v-model='gia' color='#fff' fColor='#fff'/>
							<nav v-if='gia == 0' class="cnt-tags" style='height: 452px;'>
								<li v-for='(item, id) in pendecias[active].budget.tags' >
									<appTag :id='item.id' :data='item.data' sm block noWidth/>
									<div class="clearfix"></div>
								</li>
								<div class="clearfix"></div>							
							</nav>
							<div v-else class="cnt-propostas">
								<nav class="cnt-tags" style='height: 387px; padding-bottom: 15px;'>
									<li v-for='(item, id) in pendecias[active].pid.data' >
										<appTag :id='item.id' :data='item.data' sm block noWidth/>
										<div class="clearfix"></div>
									</li>
									<div class="clearfix"></div>							
								</nav>	
								<div class="value">
									R$ {{totalProp}}
								</div>
							</div>			
						</div>
						<div class="cnt-evaluation">
							<span class='text'>Sua qualificação</span>
							<qInput v-model='nota'/>
							<button class="btn btn-success btn-lg" @click='evaluation' :disabled='sending'>
								<i v-if='sending' class="fal fa-spin fa-spinner"></i>					
								<i v-else class="fal fa-save"></i>
								QUALIFICAR
							</button>
							<div class="clearfix"></div>							
						</div>

						
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import avatar from 'app/avatar'
	import appTag from 'app/tag/index'
	import qInput from 'app/qualification/input'
	import navBtn from 'app/nav'
	import {mapState} from 'vuex'
	import toastr from 'toastr'
	import WBS from 'js/http'


	export default {
		name: "modalEvaluation",
		components: {avatar, appTag, qInput, navBtn},
		data(){
			return {
				nav: [
					{cod: 0, text: "Produto"},
					{cod: 1, text: "Proposta"}
				],
				gia: 0,
				pendecias: [],
				active: 0,
				
				nota: 0,
				sending: false
			}
		},
		computed: {
			...mapState("application", {
				myId: s=> s.auth.cod
			}),
			totalProp(){
				if(this.pendecias[this.active] != undefined) {
					if(this.pendecias[this.active].pid != undefined){
						let valor = parseFloat(this.pendecias[this.active].pid.valor)
						let frete = parseFloat(this.pendecias[this.active].pid.frete)
						return parseFloat( valor + frete ).toFixed( 2 )
					}
				}
				return 0.00
			}
		},
		methods: {
			open(data){
				this.pendecias = data
				$(this.$refs.modal).modal("show")
			},
			mountDate(){
				if(this.pendecias[this.active] == undefined) return false
				let item = this.pendecias[this.active]
				return {
					from: this.myId,
					to: item.from.cod,
					pid: item.pid.cod,
					qualification: this.nota
				}
			},
			evaluation(){
				this.sending = true
				let evaluation = this.mountDate()
				if( this.nota > 0 && evaluation != false){
					let sDate = {
						type: 'resell',
						qualification: evaluation,
						eid: this.pendecias[this.active].cod
					}	
					WBS.send('protected-custom-endConversation', sDate, resp => {				
						if(resp.status == "success" && resp.data == true){
							this.sending = false
							this.pendecias.splice(0, 1)	
							toastr.success('Qualificação realizada com sucesso!')
							if(this.pendecias.length == 0){
								$(this.$refs.modal).modal("hide")
							}
						}else{
							toastr.error('Não foi possível salvar a qualificão!')
							console.log(resp)
						}
					})
				}else{
					toastr.warning("Defina uma nota entre 1 à 5 estrelas!")
				}
			}
		}
	};
</script>
<style scoped>
	.tab ul.active li {
		color: #fff;
	}
	.cnt-evaluation {
		float: right;
	    background: #fff;
	    padding: 8px !important;
	    border: 1px solid #cecece;
	    margin: 161px 17px;
	    border-radius: 5px;
	    padding: 30px 15px;
	}

	.cnt-evaluation > span.text {
	    font-size: 19px;
	    font-family: "Aller";
	    color: rgb(131, 141, 147);
	    font-weight: bold;
	    line-height: 0.951;
	    text-align: center;
	    display: block;
	}
	.value {
		margin: 0 10px;
	    border-top: 1px solid rgba(255,255,255,0.2);
	    text-align: center;
	    font-size: 24px;
	    font-weight: bold;
	    padding: 19px 0px;
	    color: #a4d507;
	}
	.cnt-data-interation {
		float: left;
	    width: 50%;
	    height: 100%;
	    background: #70279a;
	    padding: 5px 15px 15px 15px
	}
	h2.desc {
		font-size: 18px;
	    text-align: center;
	    border-bottom: 1px solid #cecece;
	    padding: 15px 0 28px;
	    margin: 0 15px 7px 15px;
	}
	.btn-success {
		padding: 15px 60px;
    	border-radius: 50px !important;
    	margin: 40px auto 0 auto;
    	display: block;
	}
	.commet {
		color: #afb0b4;
	    padding: 10px 10px 5px;
	    font-size: 17px;
	    margin: 0px;
	    margin-top: 3px;
	    font-weight: 100;
	    text-align: center;
	}
	.cnt-tags {
		overflow: auto;
	    display: block;	    
	    padding: 0 5px 0 5px;
	}
	.cnt-tags::-webkit-scrollbar { width: 3px; }
	.cnt-tags::-webkit-scrollbar-thumb { background: #fff;}
	.cnt-tags::-webkit-scrollbar-track { background-color: rgba(0,0,0,0.2); }


	.cnt-tags li {
		list-style: none;
	}
	.avatares {
		margin-top: 31px;
    	display: block;
	}
	.avatares ul {
		padding: 9px 10px 0px 10px;	     
	    margin: 0px 0px 0px 0px;
	    border-bottom: 1px solid rgba(255,255,255, 0.1);
	    cursor: pointer;
	}
	.avatares ul:hover {
		background: #752e9e; 
	}
	.avatares ul.active {
		background: #70279a; 
		border-left: 3px solid #ffff;
	}
	h3.title {
		color: #fff;
	    font-size: 18px;
	    text-align: center;
	   	margin: 20px 0px -10px;
	}
	.modal-body { padding: 0px; }
	.modal-content { border-radius: 3px; }
	.cnt-avatares {
		float: left;
	    width: 35%;
	    height: 550px;
	    background: #7c2bab;
	}
	.cnt-log-avliation {
		float: left;
	    width: 65%;
	    height: 550px;
	    background-color: #e7eaeb;
	}
</style>