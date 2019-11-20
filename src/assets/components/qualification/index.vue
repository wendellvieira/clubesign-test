<template>
	<appModal ref='modal' :dStyle='{"width": "500px"}'>
		<div v-if='propostal != null && !qualification' slot='body' class='cnt-init-qualification'>
			<div class="cnt-avatar-qualification">
				<span class="close-modal" @click='$refs.modal.close()'>
					<i class="fal fa-times-circle"></i>					
				</span>
				<avatar 
					:name='avData.user_information.name' 
					:points='avData.evaluation' :account='avData.type'
					:img='avData.avatar' 
					size='md' type='left' fontColor='#9ca0ab'
					@clienteColor='color=$event'
				/>
			</div>
			<div class="alert-user">
				Você está prestes a fechar a conversa com <br>
				<span :style='{"color": color }'>
					{{avData.user_information.name}}
				</span>
			</div>
			<h3 class="quest">A negociação foi concluída?</h3>
			<div class="cnt-buttons">
				<button class="btn btn-link btn-lg btn-custom-end" @click='save("terminator")' :disabled='sending'>
					<i v-if='sending' class="fal fa-spin fa-spinner"></i>
					<i v-else class="fal fa-comment-alt-times"></i>
					Não
				</button>
				<button @click='openQualifacation' class="btn btn-success btn-lg">
					<i class="fal fa-star"></i>
					Sim
				</button>
			</div>
		</div>
		<div v-else-if='propostal != null && qualification' slot='body' class='cnt-init-qualification'>
			<h3 class='title'>Ótimo, qualifique <br>
				<span :style='{"color": color }'>
					{{avData.user_information.name}}
				</span>
			</h3>
			<div class="cnt-avatar">
				<avatar 
					:name='avData.user_information.name' 
					:points='avData.evaluation' :account='avData.type'
					:img='avData.avatar' 
					size='lg' type='center' fontColor='#9ca0ab'
				/>				
			</div>
			<qInput v-model='nota'/>
			<div class="cnt-buttons">
				<button class="btn btn-success btn-lg" @click='save("qualification")' :disabled='sending'>
					<i v-if='sending' class="fal fa-spin fa-spinner"></i>					
					<i v-else class="fal fa-save"></i>
					QUALIFICAR
				</button>
			</div>
		</div>
	</appModal>
</template>
<script>
	import appModal from 'app/modal'
	import clone from 'js/clone'
	import avatar from 'app/avatar'
	import {mapState} from 'vuex'
	import toastr from 'toastr'
	import WBS from 'js/http'
	import qInput from 'app/qualification/input'

	export default {
		name: 'UserQualification',
		components: {appModal, avatar, qInput},
		data(){
			return {
				propostal: null,
				conversation: null,
				qualification: false,
				color: '',
				nota: 0,
				sending: false
			}
		},
		computed: {
			...mapState("application", {
				myId: s=> s.auth.cod
			}),
			avData(){
				if(this.myId == this.conversation.to) return this.conversation.dFrom
				else return this.conversation.dTo
			}
		},
		methods: {
			mountDate(){
				return {
					from: this.myId,
					to: this.avData.cod,
					pid: this.propostal.cod,
					qualification: this.nota
				}
			},
			save(type = 'qualification'){
				this.sending = true
				if( this.nota > 0 || type == 'terminator' ){
					let sDate = {
						cid: this.conversation.cod,
						bid: this.propostal.bid,
						type: type,
						qualification: this.mountDate()
					}					
					WBS.send('protected-custom-endConversation', sDate, resp => {
						if(resp.status == "success" && resp.data == true){
							this.nota = 0;
							this.sending = false
							this.qualification=false

							this.$refs.modal.close()
							this.$emit('updateConversations')
						}else{
							toastr.error('Não foi possível salvar a qualificão!')
							console.log(resp)
						}
					})
				}else{
					toastr.warning("Defina uma nota entre 1 à 5 estrelas!")
				}
			},
			direct_qualification(data){
				this.propostal = clone(data.pid)
				delete data.pid
				this.conversation = clone(data)
				this.qualification = false
				this.save("terminator")
			},
			init(data){
				this.propostal = clone(data.pid)
				delete data.pid
				this.conversation = clone(data)
				this.qualification = false
				this.$refs.modal.open()
			},
			openQualifacation(){
				this.qualification=true
				
			}
		}
	};
</script>
<style scoped>
	h3.title {
		color: #969ba6;
	    text-align: center;
	    border-bottom: 1px solid #e4e4e4;
	    padding: 0 0 30px 0;
	    margin: -4px 0 0 0;
	}
	h3.title span { font-weight: bold; 	}
	.cnt-avatar { margin: 30px 0 20px; }
	


	.close-modal {
		position: absolute;
	    right: 21px;
	    top: 16px;
	    color: #bebfc3;
	    font-size: 28px;
	    cursor: pointer;
	}
	.modal-dialog {
		width: 500px;
	}
	.modal-content {
	     border-radius: 5px; 
	     margin: 15px; 
	}
	.cnt-avatar-qualification {
		border-bottom: 1px solid #dfdfdf;
	}
	.cnt-init-qualification { 
	    padding: 25px;
	}
	.quest { 
	    color: #9aa3ac;
	    font-weight: bold;
	    font-size: 26px;
	    text-align: center;
	    margin-top: 42px; 
	}
	.alert-user {
		color: #bdc1c4;
	    font-size: 16px;
	    font-weight: bold;
	    text-align: center;
	    margin-top: 33px;
	    display: block;
	}
	.alert-user span {	
		color: #9bbd07;
	    font-weight: bold;
	}
	.cnt-buttons {
		text-align: center;
    	margin-top: 52px;
	}
	.btn-custom-end {
		text-decoration: none;		
		padding: 15px 50px;
   		margin-right: 20px;
   		transition: 100ms linear all;
	}
	.btn-custom-end:hover {
		background: #ececf1;
	}
	.cnt-buttons button {
		padding: 15px 60px;
    	border-radius: 50px !important;
	}
	.btn-link {
		color: #99a0a8;
	}
</style>