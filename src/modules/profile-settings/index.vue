<template>
  <div id='resell-repair-settings'>
    <div class="row">
      <div class="col-sm-9">
        <account-access :auth.sync='auth'/>
        <account-address />
        <account-contact />
      </div>
      <div class="col-sm-3">
        <card-login :auth.sync='auth'/>
      </div>
    </div>
    <!-- <resellSetGerais/> -->
  </div>
</template>
<script>
  import WBS from 'js/http'
  import CardLogin from './components/card-login.vue'
  import AccountAccess from './components/settings/access.vue'
  import AccountAddress from './components/settings/address.vue'
  import AccountContact from './components/settings/contact.vue'

  import {mapState} from 'vuex'

  export default {
    name: 'BaseNotifications',
    components: {
      CardLogin,
      AccountAccess,
      AccountContact,
      AccountAddress,
    },
    computed: {
      ...mapState('application', {
        auth: s => JSON.parse( JSON.stringify( s.auth ))
      })
    }

  };
</script>
<style>
  #resell-repair-settings .card { background-color: #fff; }
  #resell-repair-settings .card * {letter-spacing: 2px;}
  #resell-repair-settings .card > header {font-size: 16px;color: #9ca3af;font-weight: 400;display: block; padding: 10px 15px; border-bottom: 1px solid #eaeaea;}
  #resell-repair-settings .card > main {padding: 18px 15px;}
  #resell-repair-settings .card {border: 1px solid #eaeaea;border-radius: 4px;margin-bottom: 15px;  }
  #resell-repair-settings .card .img {float: left;width: 70px;height: 70px;border-radius: 70px;filter: grayscale(1);  }
  #resell-repair-settings .card .cnt-text {float: left;margin-left: 15px;width: calc( 100% - 85px);margin-top: 11px;  }
  #resell-repair-settings .card .title {font-size: 20px;color: #9ca3af;font-weight: 400;display: block; }
  #resell-repair-settings .card .desc {color: #c7cbd1;font-size: 16px;margin-top: -5px;display: block;  }
</style>
