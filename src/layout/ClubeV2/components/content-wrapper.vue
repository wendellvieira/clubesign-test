<template>
  <div class='content-wrapper'>
    <section class="content-header">
      <h1 v-if='page != null'>
        <i :class='page.icon'></i>
        {{page.title}}
      </h1>
    </section>
    <section class="content">
      <router-view />
    </section>
  </div>
</template>
<script>
  import {mapState} from 'vuex'
  export default {
    name: 'AppCntPage',
    computed: {
      ...mapState('application', {
        pages: s => s.layout.pages
      }),
      page(){
        return this.pages.find( item => item.link == this.$route.path )
      }
    },
  };
</script>
<style scoped>
  .content-wrapper {
    height: calc(100vh - 50px) !important;
      overflow-x: hidden;
      overflow-y: auto;
      min-height: initial;
      margin-top: 50px;
      padding-top: 0px;
  }
</style>
