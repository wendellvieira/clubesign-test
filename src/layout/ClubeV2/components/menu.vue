<template>
  <aside class="main-sidebar" :style='{"background-color": color}'>
    <section class="sidebar">
      <div class="user-panel">
        <avatar size='lg' type='center' />
      </div>
      <ul class="sidebar-menu tree">

        <ItemAside
          v-for='(item, index) in pages'
          :key='index'
          v-bind='item'
        />
        <ItemAside title='Sair'
          icon='fal fa-sign-out'
          @click='logout'
        />

      </ul>
    </section>
  </aside>
</template>
<script>
  import ItemAside from 'app/item.aside'
  import {mapState, mapGetters} from 'vuex'
  import avatar from 'app/avatar'


  export default {
    name: 'AppAside',
    components: {
      ItemAside,
      avatar
    },
    data(){
      return {
        opened: ''
      }
    },
    methods: {
      async logout(){
        await this.$store.dispatch('application/logout')
        this.$router.push('/')
      },
      clkme(pay){
        this.opened = pay
      }
    },
    computed: {
      ...mapGetters("application", ['color']),
      ...mapState("application", {
        pages: s=> s.layout.pages,
        name: s=>{
          if(typeof s.auth.mid == 'object'){
            return s.auth.mid.razao_social
          }else if(typeof s.auth.user_information == 'object'){
            return s.auth.user_information.name
          }else{
            return s.auth.user
          }
        },
        doc: s=>{
          if(typeof s.auth.mid == 'object'){
            return s.auth.mid.cnpj
          }else if(typeof s.auth.user_information == 'object'){
            return s.auth.user_information.cpf
          }else{
            return 'Suporte'
          }
        },
        img: s=> s.auth.avatar || null,
        myId: s => s.auth.cod
      }),
      ...mapState('wb-service', {
        newNote: function(state){
          let note = state.notifications.filter(item=> item.status == 0).length
          if(note == 0) return []
          else return [{
            title: "Notificações não lidas!",
            text: (note >= 9 ? "+9" : note),
            color: "danger"
          }]

        },
        newMsgs: function(state){
          let msgs = state.mensages.filter(item => item.status != 3 && item.to == this.myId).length
          if(msgs == 0) return []
          else return [{
            title: "Mensagens não lidas!",
            text: (msgs >= 9 ? "+9" : msgs),
            color: "danger"
          }]
        }
      }),
      breve(){
        if(this.name != undefined){
          let all_name = this.name.split(' ')
          let return_name = ''
          if(all_name[0] != undefined) return_name = all_name[0][0]
          if(all_name[1] != undefined) return_name += all_name[1][0]
          return return_name.toUpperCase()
        }
      }
    },
    mounted(){

      $(".main-sidebar").hover(
        e => {
          // console.log($('body').isClass('sidebar-collapse'))
          if($('body').hasClass('sidebar-collapse')){
            $('body').removeClass('sidebar-collapse')
            $('body').addClass('sidebar-expanded-on-hover')
          }
        },
        e => {
          if($('body').hasClass('sidebar-expanded-on-hover')){
            $('body').removeClass('sidebar-expanded-on-hover')
            $('body').addClass('sidebar-collapse')
          }
        }
      )
      //
    }
  };
</script>
<style scoped>

  .info {
    position: initial;
    width: 100%;
    text-align: center
  }
  .cnt-avatar {
    display: block;
      text-align: center;
  }
  .user-panel .avatar {
    float: left;
      width: 50px;
      height: 50px;
      border-radius: 60px;
  }
  /*colapse menu*/
  .sidebar-collapse .iniciais {
    width: 40px;
      height: 40px;
      line-height: 13px;
  }
  .sidebar-collapse .user-panel {
    padding: 15px 4.5px 60px 4.5px;
  }

  .sidebar-menu {
      /*height: calc( 100vh - 165px );*/
      width: 100%;
      padding-bottom: 44px;
      /*overflow-y: auto;*/
      /*overflow-x: hidden;*/
  }

  .sidebar {
    height: calc( 100vh - 50px );
    display: block;
    overflow: auto;
  }

  .sidebar::-webkit-scrollbar {
      width: 3px;
      background: rgba(95, 95, 95, 0.05);
      margin-right: 5px;
  }
  .sidebar::-webkit-scrollbar-thumb {
      background: #fff;
      border-radius: 5px;
  }
  .sidebar::-webkit-scrollbar-track {
      background-color: rgba(95, 95, 95, 0.05);
      border-radius: 5px;
  }


  .user-panel {
    padding: 40px 25px 50px 10px;
  }
  .info p {
    color: #f9f6fd;
    margin-bottom: 4px;
      margin-top: 4px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      width: 100%;

  }
  .info .cnt-status {
    color: rgba(255, 255, 255, 0.7);
  }
  .iniciais {
    float: left;
      width: 50px;
      height: 50px;
      background-color: rgba(0, 0, 0, 0.2);
      border-radius: 60px;
      text-align: center;
      font-size: 15px;
      font-weight: 600;
      padding: 15px 0;
      color: #f9f6fd;
  }
</style>
