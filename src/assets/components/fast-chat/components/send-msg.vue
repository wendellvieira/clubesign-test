<template>
	<div class="cnt-send-form">
		<div class="input-group">
			<input type="text"  
				class="form-control form-custom input-lg" 
				placeholder="Escreva sua mensagem..."
				v-model='msg' @keyup.enter='sendMsg'
			/>
			<span class="input-group-btn">
				<button @click='sendMsg' type="button" class="btn btn-send">
					<i class="fal fa-paper-plane"></i>
				</button>
			</span>
		</div>
	</div>
</template>
<script>
	import {mapState, mapActions} from 'vuex'
	import {fDate, newId} from 'js/tools'
	export default {
		name: "InputFastChat",
		props: ['cid','to', 'sc'],
		data(){
			return {
				msg: ''
			}
		},
		computed: {
			...mapState('application', {
				myId: s=> s.auth.cod
			})
		},	
		methods: {
			...mapActions('wb-service', ["send_mensage"]),
			mount_mensage(){
				return {
					cod: newId(this.to+this.msg+this.cid+this.myId),
					cid: this.cid,
					data: this.msg,
					event: fDate(new Date(), "YY-MM-DD HH:II:SS"),
					from: this.myId,
					to: this.to,
					status: 0
				}
			},
			sendMsg(){
				if(this.sc == 1){
					let data =this.mount_mensage()
					this.send_mensage(data)
					this.msg = ""					
				}
			}
		}
	};
</script>
<style scoped>
	.btn-send {
		background-color: #3ec2b1;
	    border-radius: 5px;
	    width: 85px;
	    color: #fff;
	    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);	    
    	height: 46px;
    	padding: 10px 40px;
	}
	.form-custom {
		border: none;
	}
	.form-custom:focus { outline: none; box-shadow: none; }
	.cnt-send-form {
		position: absolute;
    	bottom: 2px;
    	width: 100%;
    	display: block;
	}
	
</style>