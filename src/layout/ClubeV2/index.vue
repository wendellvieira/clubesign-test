<template>
  <div class='wrapper'>
    <div id="maskBlack"></div>
    <app-header />
    <app-menu />
    <app-content-wrapper />
  </div>
</template>
<script>

  import Vue from 'vue'
  import VueDND from 'awe-dnd'
  Vue.use(VueDND)

  import 'admin-lte/dist/css/AdminLTE.min.css'
  import 'admin-lte/dist/css/skins/_all-skins.min.css'
  import 'css/style-admin.css'

  import AppHeader from './components/header.vue'
  import AppMenu from './components/menu.vue'
  import AppContentWrapper from './components/content-wrapper.vue'

  import {mapActions} from 'vuex'
  import WBS from 'js/http'


  export default {
    name : 'CntApp',
    components: {
      AppHeader,
      AppMenu,
      AppContentWrapper
    },
    methods: {
      // ...mapActions('wb-service', ["init_chat", "init_notification"]),

      getLinkPerfil(){
        WBS.send( 'protected-read-myAccess', resp => {
          this.$store.commit( 'application/set_pages', resp.data )
        })
      },

    
    },
    mounted(){
      $("body")
        .addClass('skin-custom')
        .addClass('fixed')
        .addClass('sidebar-mini')

  
      this.getLinkPerfil()

    },
    destroyed(){
      $("body")
        .removeClass('skin-custom')
        .removeClass('fixed')
        .removeClass('sidebar-mini')
    }
  };
</script>
<style>
  #maskBlack {
    display: none;
    position: fixed;
      width: 100% !important;
      height: 100% !important;
      z-index: 7000;
      background: rgba(0, 0, 0, 0.4);
  }
  * {
    font-family: 'Aller';
  }
  .skin-custom .main-header {
    background-color: #383c43;
  }
  .skin-custom .sidebar-toggle {
    color: #fff;
  }
  .skin-custom .logo-lg {
    font-size: 16px;
  }
  .skin-custom  .main-header .logo{
    background-color: rgba(0, 0, 0, 0.2);
    color: #fff;
  }
  .skin-custom .main-sidebar {
    background-color: #1499ed;
  }

  .skin-custom .item-aside > a, .skin-custom .item-aside .treeview-menu > li > a {
    color: rgba(255, 255, 255, 0.7);
    font-family: 'Aller';
    font-size: 14px;
    font-weight: bold;
  }
  .skin-custom .sidebar-menu .item-aside.active > a{
    background-color: rgba(255, 255, 255, 0.2);
    color: #fff;
  }
  .skin-custom .item-aside .treeview-menu > li.active > a {
    color: #fff;

  }

  .skin-custom .sidebar-menu .item-aside:hover > a,
  .skin-custom .item-aside .treeview-menu > li:hover > a {
    color: #fff;
  }

  .skin-custom .treeview-menu, .skin-custom .treeview-menu a {
    background-color: rgba(0, 0, 0, 0.02);
  }
  .skin-custom .content-header {
      padding: 27px 15px 0 15px;
  }
  .skin-custom .content-header h1 {
    font-family: 'Aller';
    font-size: 22px;
    color: #9ca3af;

  }

  .skin-custom .btn-lg {
    padding: 14px 28px;
  }

  .skin-custom .btn{
    font-family: 'Aller';
    font-size: 14px;
    font-weight: bold;
  }
  .skin-custom .btn-success {
    background-color: #a8c311;
      border-color: #a8c311;
  }
  .skin-custom .btn-warning {
    background-color: #db8812;
      border-color: #db8812;
  }
  .skin-custom .btn-danger {
    background-color: #ed1460;
      border-color: #ed1460;
  }
  .skin-custom .btn-primary {
    background-color: #915de7;
      border-color: #915de7;
  }
  .skin-custom .btn-info {
    background-color: #1499ed;
      border-color: #1499ed;
  }

</style>
