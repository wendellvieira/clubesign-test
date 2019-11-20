<template>
  <header class="main-header">
    <div class='cnt-logo' :style='{"background-color": color}'>
      <a class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>C</b>S</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Clube Do Sign</span>
      </a>
    </div>

    <nav class="navbar navbar-static-top">
      <a class="toggle-sidebar" @click='toggle'>
        <i class="fal fa-bars"></i>
      </a>
    </nav>
  </header>
</template>
<script>
  import {mapGetters} from 'vuex'
  export default {
    computed: {
      ...mapGetters("application", ['color'])
    },
    methods: {
      toggle(){
        $('body').toggleClass('sidebar-collapse')
      }
    }
  };
</script>
<style scoped>
  .cnt-logo {
    height: 50px;
      float: left;
  }
  .sidebar-toggle:hover {
    background-color: rgba(0, 0, 0, 0.05);
  }
  .sidebar-toggle {
    cursor: pointer;
    transition: .2s linear background-color;
  }
  .toggle-sidebar {
    color: #fff;
      padding: 15px;
      float: left;
      cursor: pointer;
  }
  .toggle-sidebar:hover {
    background-color: #915de7;
  }
</style>
