<template>
  <div class='login-content'>

      <img class="logo-clybe-sign" src='static/logo-mobi.png'/>

      <div class="row">

        <div class="col-sm-offset-2 col-sm-4">
          <div @click='create_account' class="cnt-register">
            Não tem uma conta? <span>Clique aqui</span>
          </div>
        </div>

        <div class=" col-sm-4">
          <div class="cnt-actions-login-landPage">
            <AppLogin @closeModal='$refs.modal.close()'/>

            <router-link @click.native='$refs.modal.close()' class="btn-login-fb-landPage"
              to='/account-recovery/send-mail'>
              Redefinir senha
            </router-link>

            <div class="clearfix"></div>
          </div>
        </div>

      </div>
      <div class="clearfix"></div>
  </div>
</template>
<script>
  import aModal from 'app/modal'
  import WBS from 'js/http'
  import toastr from 'toastr'
  import {mapState} from 'vuex'
  import AppLogin from './components/login.vue'
  import loginFB from 'js/login-fb'

  export default {
    components: {aModal, AppLogin},
    mixins: [loginFB],
    methods: {
      create_account(){
        this.$refs.modal.close()
        setTimeout(e=>{
          this.$router.push("/register-v3")
          // this.$router.push("/promo")
        }, 300)
      },
      open(){
        this.$refs.modal.open()
      },
      login(){
        this.load = true
        this.login_fb((data, status) => {
          if(status == "unregistred"){
            console.log(data)
            // this.$refs.modal.close()
            // this.$store.commit('SET_DATA_FACE_NEW_USER', gData)
            // this.$router.push('/register/how_it_works')
          }else{
            toastr.success('Redirecionando...')
            this.$refs.modal.close()
            this.$store.dispatch('application/logon', data.data)
            setTimeout(e=> {
              this.$router.push(`/${data.data.data.user.type}`)
            }, 300)
          }
        })
      },
    },
    computed: {
      ...mapState('application', ['session'])
    }
  };
</script>
<style scoped>
  .login-content {
    background-color: #6b25b2;
    overflow: hidden;
    min-height: 100vh;

  }
 .cnt-input-component {
    margin-top: 20px;
  }
  .separator {
    font-size: 20px;
      color: #fff;
      font-weight: bold;
      position: relative;
      margin: 25px 0px;
      width: 100%;
      display: block;
  }
  .separator::after {
    content:"";
    position: absolute;
      width: 136%;
      right: -100%;
      top: 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.29)
  }
  .separator::before {
    content:"";
    position: absolute;
      width: 136%;
      left: -100%;
      top: 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.29)
  }
  .cnt-register:hover {
    background-color: #431275;
  }
  .logo-clybe-sign {
    width: 278px;
    margin: 55px auto 60px;
    display: block;
  }
  .btn-login-fb-landPage {
    border-radius: 6px;
      border: none;
      display: inline-block;
      /*background-color: rgba(0, 0, 0, 0.1);*/
      color: #fff;
      width: 100%;
      text-align: center;
      cursor: pointer;
      float: left;
      padding: 15px 0 0 0;
      font-size: 18px;
  }
  .cnt-actions-login-landPage {
    text-align: center;
    padding-bottom: 50px;
    width: 350px;
    margin: -55px 0 0 0;
  }
  .cnt-register {
    background-color: #6320a7;
      padding: 40px;
      text-align: center;
      color: #fff;
      font-size: 18px;
      cursor: pointer;
      transition: linear 100ms all;
  }
  .cnt-register:hover {
    background-color: #431275;
  }


  .cnt-register span {
    color: rgba(255,255,255,0.6);
      font-weight: bold;
  }
</style>
