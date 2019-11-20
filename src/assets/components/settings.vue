<template>
	<div>
		<div class="card">
			<main>
				<div class="cnt-user-data">
					<img :src="img_profile" class='img'/>
					<div class="cnt-text">
						<span class='title'>
							<input class='input-cpf'
								v-model='auth.user_information.name'
								placeholder="Entre com o nome" 
							/>
						</span>
						<span class="desc">
							<i class="fal fa-edit"></i>		
							<input 
								v-model='auth.user_information.cpf' 
								v-mask='"###.###.###-##"'
								class='input-cpf' placeholder="Entre com o CPF" 
							/>							
						</span>
					</div>					
					<div class="clearfix"></div>
				</div>
				<div class="pull-right">
					<button class="btn-save-profile" @click='salvar'>
						<i class="fal fa-save"></i>
						Salvar
					</button>				
				</div>
				<div class="clearfix"></div>
			</main>
		</div>
		<div class="card">
			<header>Enderecos</header>
			<main>
				<div class="row">
					<div class="col-sm-3">
						<appInput v-model='auth.user_information.cep' 
							title='CEP' color='#703ec2' 
							:mask='"#####-###"'
							:disabled='block'
						/>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-6">
						<appInput v-model='auth.user_information.rua' title='Rua' color='#703ec2'/>
					</div>
					<div class="col-sm-2">
						<appInput v-model='auth.user_information.num'
							ref='num' title='Num' color='#703ec2'
						/>
					</div>
					<div class="col-sm-4">
						<appInput v-model='auth.user_information.comp' title='Complemento' color='#703ec2'/>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-4">
						<appInput v-model='auth.user_information.bairro' title='Bairro' color='#703ec2'/>
					</div>
					<div class="col-sm-4">
						<appInput v-model='auth.user_information.cidade'  
							title='Cidade' color='#703ec2'
							:disabled='true'
						/>
					</div>
					<div class="col-sm-2">
						<appInput v-model='auth.user_information.uf' 
							title='Uf' color='#703ec2'
							:disabled='true'							
						/>
					</div>
				</div>
				<br>
			</main>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="card">
					<header>Contatos</header>	
					<main>
						<div class="row">
							<div class="col-sm-6">
								<appInput v-model='auth.user_information.tfixo' 
									title='Telefone fixo' color='#703ec2'
									:mask='"(##) ####-####"'
								/>
							</div>
							<div class="col-sm-6">
								<appInput v-model='auth.user_information.whatsapp' 
									title='Whatsapp' color='#703ec2'
									:mask='"(##) #####-####"'

								/>
							</div>
						</div>
						<br>
					</main>					
				</div>
			</div>
			<div class="col-sm-6">
				<div class="card">
					<header>Senha</header>	
					<main>
						<div class="row">
							<div class="col-sm-6">
								<appInput v-model='pass.new' type='password'
									title='Nova senha' color='#703ec2'
								/>
							</div>
							<div class="col-sm-6">
								<appInput v-model='pass.repeat' type='password'
									title='Repetir senha' color='#703ec2'
								/>
							</div>
						</div>
						<br>
					</main>					
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import {mapState} from 'vuex'
	import appInput from 'app/input'
	import WBS from 'js/http'
	import clone from 'js/clone'
	import toastr from 'toastr'
	import md5 from 'js/md5'

	export default {
		components: {appInput},
		data(){
			return {
				pass: {
					new: '',
					repeat: ''
				},
				block: false
			}
		},
		computed: {
			...mapState('application', {
				auth: s=> s.auth,
				myId: s=> s.auth.cod
			}),
			img_profile(){
				return `${WBS.url_base}uploaded_files/avatares/${this.auth.avatar}`
			}
		},
		watch: {
			'auth.user_information.cep': function(val){
				if(val.length == 9 && !this.block){
					this.block = true
					let cep = val.replace("-", "")
					$.get(`https://viacep.com.br/ws/${cep}/json/`).then(resp => {
						this.block = false
						this.auth.user_information.rua = resp.logradouro
						this.auth.user_information.bairro = resp.bairro
						this.auth.user_information.cidade = resp.localidade
						this.auth.user_information.uf = resp.uf	
						this.$refs.num.focus()
					})
				}
			}
		},
		methods: {
			salvar(){
				let sData = { user_information: clone( this.auth.user_information ) } 
				if(this.pass.new != ""){
					if(this.pass.new == this.pass.repeat){
						sData.pass = {
							pass: md5.get(this.pass.new),
							cod: this.myId
						}
					} else {
						toastr.warning("As senhas não são iguais")
						return;
					}
				}
				WBS.send('protected-save-signInformation', sData, resp=> {
					if(resp.status == "success" && resp.data == true){
						toastr.success("Dados salvos com sucesso!")
						this.$store.commit("application/set_update_date", sData)
					}else{
						toastr.error("Não foi possível salvar seus dados!")
						console.log(resp)
					}
					
				})
			}
		}
	};
</script>
<style scoped>
	.input-cpf {
		    border: none;
	}
	.input-cpf:focus {
		outline: none;
	}
	.btn-save-profile {
		background: none;
		color: #915de7;
	    border: 1px solid #915de7;
	    padding: 0px 25px;
	    height: 70px;
	    transition: 100ms linear all;
	    border-radius: 5px;
    	font-size: 18px;
	}
	.btn-save-profile:hover {
		background-color: #915de7;
		color: #fff;
	}
	.cnt-user-data {
		float: left;
   		max-width: 80%;
	}
	.card * {letter-spacing: 2px;}
	.card > header {font-size: 16px;color: #9ca3af;font-weight: 400;display: block; padding: 10px 15px; border-bottom: 1px solid #eaeaea;}
	.card > main {padding: 18px 15px;}
	.card {border: 1px solid #eaeaea;border-radius: 4px;margin-bottom: 15px;	} 
	.card .img {float: left;width: 70px;height: 70px;border-radius: 70px;filter: grayscale(1);	} 
	.card .cnt-text {float: left;margin-left: 15px;width: calc( 100% - 85px);margin-top: 11px;	}
	.card .title {font-size: 20px;color: #9ca3af;font-weight: 400;display: block;	} 
	.card .desc {color: #c7cbd1;font-size: 16px;margin-top: -5px;display: block;	} 
</style>