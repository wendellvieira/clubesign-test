<template>
	<div class='item-notification' :class='{"read": data.status != 0}'>
		<span v-if='data.status == 0' class="new-notification">
			<i class="fas fa-certificate"></i> NOVA
		</span>
		<div class="cnt-avatar-notification">
			<avatar 
				:name='data.from.user_information.name' 
				:points='data.from.evaluation' 
				:img='data.from.avatar' 
				size='md' type='left' fontColor='#9ca3af'
			/>
		</div>
		<div class="ctn-mensage" v-html='data.mensage'></div>
		<div class="cnt-actions">
			<button @click='deleteNotification' class="btn-trash">
				<i class="fal fa-trash-alt"></i>
			</button>
		</div>
		<div class="clearfix"></div>
	</div>
</template>
<script>
	import avatar from 'app/avatar'
	import WBS from 'js/http'
	import toastr from 'toastr'

	export default {
		name: "itemNotification",
		components: {avatar},
		props: ['data'],
		methods: {
			deleteNotification(){
				WBS.send('protected-delete-notification', this.data.cod, resp=> {
					if(resp.status == 'success' && resp.data == true){
						toastr.success('Notificação excluída com sucesso!')
						this.$store.commit("wb-service/remove_notification", this.data.cod)
					}else{
						toastr.error('Não foi possível Excluír a notificação!')
						console.log(resp)
					}
				})
			}
		}
	};
</script>
<style scoped>
	.read {
		opacity: 0.7;
	}
	.new-notification {
		background-color: #d36d00;
	    position: absolute;
	    z-index: 1000;
	    color: #fff;
	    padding: 3px 50px;
	    transform: rotate(-45deg);
	    top: 16px;
	    left: -49px;
	    font-size: 10px;
	}
	.new-notification {
		background-color: #d36d00;
	}
	.item-notification {
		padding: 15px 15px 8px 15px;
   		border-bottom: 1px solid #ececec;
   		overflow: hidden;
   		position: relative;
	}
	.item-notification:last-child {
		border-bottom: none;
	}
	.cnt-avatar-notification {
		width: 30%;
    	float: left;
	}
	.ctn-mensage {
		float: left;
	    width: 50%;
	    padding: 15px;
	    border-left: 1px solid #ececec;
	    min-height: 71px;
	}
	.cnt-actions {
		float: right;
	}
	.btn-trash {
		width: 71px;
	    height: 71px;
	    font-size: 26px;
	    color: #ff2b67;
	    border: 1px solid #ff2b67;
	    border-radius: 3px;
	    background: none;
	    cursor: pointer;
	    transition: 100ms linear all;
	}
	.btn-trash:hover {
		background: #ff2b67;
		color: #fff
	}
</style>