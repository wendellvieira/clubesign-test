<template>
  <div class="card">
    <header>Endereço</header>
    <main>
      <div class="row">
        <div class="col-sm-3">
          <appInput v-model='address.cep'
            title='CEP' color='#703ec2'
            :mask='"#####-###"'
            :disabled='block'
          />
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-sm-6">
          <appInput v-model='address.rua' title='Rua' color='#703ec2'/>
        </div>
        <div class="col-sm-2">
          <appInput v-model='address.num'
            ref='num' title='Num' color='#703ec2'
          />
        </div>
        <div class="col-sm-4">
          <appInput v-model='address.comp' title='Complemento' color='#703ec2'/>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-sm-4">
          <appInput v-model='address.bairro' title='Bairro' color='#703ec2'/>
        </div>
        <div class="col-sm-4">
          <appInput v-model='address.cidade'
            title='Cidade' color='#703ec2'
            :disabled='true'
          />
        </div>
        <div class="col-sm-2">
          <appInput v-model='address.uf'
            title='Uf' color='#703ec2'
            :disabled='true'
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
        block: false,
        address: {
          cep: '',
          rua: '',
          num: '',
          comp: '',
          bairro: '',
          cidade: '',
          uf: ''
        }
      }
    },
    watch: {
      'address.cep': function(val){
        if(val.length == 9 && !this.block){
          this.block = true
          let cep = val.replace("-", "")
          $.get(`https://viacep.com.br/ws/${cep}/json/`).then(resp => {
            this.block = false
            this.address.rua = resp.logradouro
            this.address.bairro = resp.bairro
            this.address.cidade = resp.localidade
            this.address.uf = resp.uf
            this.$refs.num.focus()
          })
        }
      }
    },
  };
</script>
