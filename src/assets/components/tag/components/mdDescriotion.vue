<template>
	<div ref='modal' class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						twer
						<b>asa</b>
					</h4>
				</div>
				<div class="modal-body">
					<div class="cnt-overview">
						<label for="desc">{{getNameInput}}</label>
						<textarea id='desc' 
							class='input-overview' 
							placeholder="Escreva aqui...
			Maior que 120 caracteres">
						</textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import hTags from 'js/hTags'
	export default {
		name: 'mdDescription',
		props: ['scope'],
		mixins: [hTags],
		computed: {
			getNameInput(){
				this.scope.find()
				return this.search_tags(this.scope, 'all', 'target', 'text')
			}
		},
		methods: {
			open(){
				$(this.$refs.modal).modal('show')
			}
		}
	};
</script>
<style scoped>
	.modal-body-flex {
		padding: 0px;
		height: calc( 100% - 116px );
		display: flex;
	}
	.modal-body-block {
		padding: 0px;
		height: calc( 100% - 116px );
		display: flex;
	}
	.modal-title b:after {
		content: "?";
	    color: #b883e8;
	    font-size: 86px;
	    display: inline-block;
	    line-height: 40px;
	}
	.modal-title b {
		display: block;
	    text-align: center;
	    font-size: 46px;
	    color: #fff;
	    margin-top: -11px;
	}
	.modal-title {
	    width: 90%;
	    max-width: 600px;
	    margin: 0 auto;
	    color: rgba(255, 255, 255, 0.66);
	    font-size: 22px;
	}
	.modal-header {
		border: none;
	}
	.modal-content {
		height: calc( 100vh - 60px );
	    border-radius: 3px;
	    position: relative;
	    display: block;
	    background-color: #6f2faa;
	    overflow: hidden;
	}
		.btn-next[disabled] {
		opacity: 0.6;
		cursor: no-drop;
	}
	.btn-next {
		background: #fff;
	    border: none;
	    font-size: 18px;
	    font-weight: bold;
	    padding: 12px 4em;
	    float: right;
	    border-radius: 3px;
	    color: #6f2faa;
	}
	label {
		color: #fff;
	    font-size: 18px;
	    display: block;
	}
	.input-overview:focus {
		outline: none;
	}
	.input-overview::placeholder {
		color:  rgba(255, 255, 255, 0.6);
	}
	.input-overview {
		width: 100%;
	    height: calc( 100% - 112px );
	    border: none;
	    background: rgba(0, 0, 0, 0.09);
	    color: #fff;
	    padding: 15px;
	    font-size: 16px;
	    margin-bottom: 10px;
	}
	.cnt-overview {
		width: 90%;
	    max-width: 600px;
	    margin: 0 auto;
	}
</style>