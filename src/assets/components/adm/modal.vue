<template>
	<div class='modal fade' ref='modal' 
		:data-backdrop='static != undefined ? "static" : false'>

		<div class="modal-dialog" :class='size != undefined ? "modal-" + size : "" '>
			<div class="modal-content">
				<div v-if='title != undefined' class="modal-header">
					<button class="close" @click='close'>
						<i class="fa fa-close"></i>
					</button>
					<h4 class="modal-title" v-html='title'></h4>
				</div>
				<div v-if='$slots.body != undefined' class="modal-body" 
					:class='{"no-padding": noPadding != undefined}'>
					<slot name='body' />
				</div>
				<div v-if='$slots.footer != undefined' class="modal-footer">
					<slot name='footer' />					
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		name: 'AppModal',
		props: ['title', 'size', 'static', 'noPadding'],
		
		methods: {
			open(){
				// $(this.$refs.modal).addClass('in');
				$(this.$refs.modal).modal('show');
			},
			close(persit = false){
				$(this.$refs.modal).modal('hide');
				if(persit){
					setTimeout(e=> {
						$('body').addClass('modal-open')					
					}, 500)					
				}
			}
		}
	};
</script>
<style scoped>
	.modal-xg {
		width: 1100px;
	}
	h4.modal-title{
		font-size: 17px;
	}
	.in {
		display: block !important; 
		padding-left: 17px !important;
	}
</style>