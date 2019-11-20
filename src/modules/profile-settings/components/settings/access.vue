<template>
  <div class="card">
    <header>Dados de acesso</header>
    <main>
      <br>
      <div class="row">
        <div class="col-sm-6">
          <appInput v-model='access.user' title='Usuário' color='#703ec2'/>
        </div>
        <div class="col-sm-3">
          <appInput v-model='access.newPass'
            title='Nova Senha' color='#703ec2'
            type='password'
          />
        </div>
        <div class="col-sm-3">
          <appInput v-model='confirmPass'
            title='Repita a senha' color='#703ec2'
            type='password'
          />
        </div>
      </div>
      <br>
    </main>
  </div>
</template>
<script>
  import appInput from 'app/input'

  export default {
    components: {
      appInput
    },
    data(){
      return {
        confirmPass: '',
        access: {
          user: '',
          newPass: ''
        }
      }
    }
  };
</script>
