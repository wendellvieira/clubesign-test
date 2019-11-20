<template>
  <div v-if='auth != undefined'>
    <div class="cnt-avatar">

      <div @click='$refs.file.click()' class="update-avatar">
        <input @change='selectImage' ref='file' type="file" accept="image/*" class='hidden'>
        <i class="fal fa-camera-alt"></i>
        <small>Alterar avatar</small>
      </div>

      <div class="avatar"
        :style='{ backgroundImage: `url(${get_image})`}'>
      </div>
    </div>
    <button class="btn-save-profile">
      <i class="fal fa-save"></i>
      Salvar
    </button>
  </div>
</template>
<script>
  import WBS from 'js/http'

  export default {
    props: ['auth'],
    data(){
      return {
        miniatura: null
      }
    },
    methods: {
      selectImage(ev){
        if( ev.target.files.length == 0 ) return;

        const Reader = new FileReader;

        Reader.onload = ev => {
          this.miniatura = ev.target.result
        };

        Reader.readAsDataURL( ev.target.files[0] );

      }
    },
    computed: {
      get_image(){
        if( this.miniatura != null ) return this.miniatura;

        let base = `${WBS.url_base}uploaded_files/avatares/`

        const validate = [ null, undefined, "" ]

        if( validate.indexOf( this.auth.avatar ) == -1 ){
          return base + this.auth.avatar
        }

        let url = "/static/register_v2/avatares/"
        const files = {
          master: "/static/avatar.png",
          manufacturer: url + 'maker.png',
          repair :url + 'servicer.png',
          resell: url + 'cluber.png',
          cluberplus: url + 'cluber.png',
          signer: url + 'signer.png'
        }

        return files[this.auth.type]

      },
    }
  };
</script>
<style scoped>
  .cnt-avatar {
    position: relative;
    width: 200px;
    height: 200px;
    display: block;
    margin: 20px auto 25px;
    border-radius: 200px;
    overflow: hidden;
  }
  .cnt-avatar:hover .update-avatar {
    opacity: 1;
  }
  .update-avatar small {
    font-size: 17px;
  }
  .update-avatar {
    cursor: pointer;
    position: absolute;
    top: 0;
    left: 0;
    background: rgba(77, 50, 119, 0.78);
    z-index: 2;
    width: 100%;
    height: 100%;
    color: #fff;
    font-size: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    opacity: 0;
    transition: opacity linear 300ms;
  }
  .avatar {
    width: 200px;
    height: 200px;
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 1;
  }
  .btn-save-profile {
    background: none;
    color: #915de7;
    border: 1px solid #915de7;
    padding: 0px 25px;
    height: 50px;
    transition: 100ms linear all;
    border-radius: 5px;
    font-size: 18px;
    display: block;
    width: 200px;
    margin: 0 auto;
  }
  .btn-save-profile:hover {
    background-color: #915de7;
    color: #fff;
  }
</style>
