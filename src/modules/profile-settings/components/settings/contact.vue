<template>
  <div class="card">
    <header>Contatos</header>
    <main>
      <div class="row">
        <div class="col-sm-6">
          <appInput v-model='contact.tfixo'
            title='Telefone fixo' color='#703ec2'
            :mask='"(##) ####-####"'
          />
        </div>
        <div class="col-sm-6">
          <appInput v-model='contact.whatsapp'
            title='Whatsapp' color='#703ec2'
            :mask='"(##) #####-####"'
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
        contact: {
          tfixo: '',
          whatsapp: ''
        }
      }
    }
  };
</script>
