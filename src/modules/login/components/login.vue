<template>
  <div class='cnt-login'>
    <div class="progress-cst">
      <div class="progress-bar-cst" :style='{ "width" : "100%" }'></div>
    </div>
    <div class="steps-body">
      <customInput title='Usuário' v-model='fLogin.login'
        color='#b78ae5' textColor='#fff'
      />
      <customInput title='Senha' @enter='login' v-model='fLogin.senha' type='password'
        color='#b78ae5' textColor='#fff'
      />
      <button v-if='!load' class="btn-login"  @click='login'>
        Entrar
      </button>
      <button v-else class="btn-login" disabled>
        <i class="fal fa-spinner fa-spin"></i>
      </button>
    </div>
  </div>
</template>
<script>
  import toastr from 'toastr'
  import WBS from 'js/http'
  import customInput from 'app/input-custom'
  import localforage from 'localforage'
  import {mapGetters} from 'vuex'
  import {clone} from 'lodash'

  export default {
    name : "AppLogin",
    components: {customInput},
    data() {
      return {
        fLogin: {
          login: "",
          senha: "",
          type: 'PA'
        },
        load: false
      }
    },
    computed: {
      ...mapGetters("application", ['type'])
    },
    methods: {
      AplicarMask(mask, number){
        let icrement = 0, resp = '';
        for(let x = 0; x < mask.length; x++){
          if(mask[x] == "#"){
            resp += String(number[icrement])
            icrement++;
          }else{
            resp += String(mask[x])
          }
        }
        return resp;
      },
      redirect(type){
        if(type == 'cluberplus' || type == 'repair' || type == 'resell'){
          this.$router.push("/painel")
        }else{
          this.$router.push("/" + type)
        }
      },
      login(){
        if(this.fLogin.login == ""){
          toastr.warning("Entre com o email!")
          jQuery(".login").focus()

        }else if(this.fLogin.senha == ""){
          toastr.warning("Entre com a senha!")
          jQuery(".pass").focus()

        }else{
          this.load = true

          let sData = clone( this.fLogin )

          if( /([0-9]{14})/.test( sData.login ) ){
            sData.login = this.AplicarMask( "##.###.###/####-##", sData.login )
          }

          WBS.send('public-custom-login', sData, resp => {
            if(resp.status == 'success'){
              if(resp.data != undefined){

                // Verificando integridade das contas.....
                let user = resp.data.data.user
                if(user.type == "repair" && user.repairs == null ) {
                  toastr.error("E61: Existe uma incoerência em sua conta;")
                  this.load = false
                  return;
                }
                if(user.type == "resell" && user.products == null ) {
                  toastr.error("E66: Existe uma incoerência em sua conta;")
                  this.load = false
                  return;
                }


                this.$store.dispatch('application/logon', resp.data)
                setTimeout(e=> {
                  this.load = false
                  this.$emit('closeModal')
                  toastr.success('Redirecionando...')
                  this.redirect(resp.data.data.user.type)
                }, 1000)


              }else{
                toastr.error("Erro inesperado, tente novamente mais tarde!")
                console.log(resp)
                this.load = false
              }
            }else{
              if(resp.msgs != undefined){
                toastr.error(resp.msgs[0])
                this.load = false
              }else{
                toastr.error("Erro inesperado, tente novamente mais tarde!")
                console.log(resp)
                this.load = false
              }
            }
          })
        }
      }
    },
    async mounted(){
      // let sid = await localforage.getItem('sid')
      // if(sid != null){
      //  await this.$store.dispatch('application/logon')
      //  this.$router.push(`/${this.type}`)
      // }
    }
  };
</script>

<style scoped>
  .btn-login:disabled {
    opacity: 0.5;
    cursor: no-drop;
  }
  .cnt-login {
    width: 100%;
  }
  .btn-login {
    width: 100%;
      margin-top: 25px;
      background: #fff;
      border: none;
      padding: 15px;
      font-size: 18px;
      color: #6320a7;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
  }
</style>
