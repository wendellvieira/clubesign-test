<template>
  <div class="cnt-avatar" :class='`${type} ${size}`'>
    <div class="cnt-image" :style='{"width": `${settings.size[size].t}px`, "height": `${settings.size[size].t}px`}'>
      <canvas ref='avatar' :width="settings.size[size].t" :height="settings.size[size].t" ></canvas>
      <img :src="get_image()" alt="Avatar" :style='{"padding": `${settings.size[size].s}px`}' />
      <span class="type-account" :class='av_account'></span>
    </div>
    <div class="cnt-infos">
      <div class="name" :style='{"color": fontColor}'>{{av_name}}</div>

      <div v-if='av_account == "master"' class="points master">Master</div>
      <div v-else-if='av_account == "manufacturer"'class="points manufacturer">Fabricante</div>
      <template v-else>
        <div class="points new" v-if='sPoints(points) == 0' >
            Novo Usuário
        </div>
        <div v-else class="points" :style='{"color": textPointsColor || get_color(sPoints(points))}'>
            {{sPoints(points)}} de Reputação
        </div>
      </template>

    </div>
  </div>
</template>
<script>
  import {mapGetters, mapState }from 'vuex'
  import WBS from 'js/http'

  export default {
    name: 'AppAvatar',
    props: {
      img: {
        default: null,
      },
      points: {
        default: null
      },
      size: {
        default: 'md'
      },
      name: {
        default: ''
      },
      type: {
        default: 'center'
      },
      fontColor: {
        default: "#fff"
      },
      textPointsColor: {
        default: undefined
      },
      account: {
        default: null
      }
    },
    computed: {
      ...mapGetters('application', ["acType","sName","sPoints","gImage"]),
      ...mapState('application', ["auth"]),
      av_account(){
        if(this.account == null) return this.acType
        else return this.account
      },
      av_name(){
        if(this.name != '') return this.name
        if(this.sName != null) return this.sName
        if(this.auth != undefined) return this.auth.user
        return 'NO-USER'
      }
    },
    data(){
      return {
        context: null,
        circles: [],
        settings: {
          size: {
            lg: { t: 120, s: 8 },
            md: { t: 70, s: 4 },
            sm: { t: 40, s: 2 },
          }
        }
      }
    },
    methods: {
      get_image(){
        let base = `${WBS.url_base}uploaded_files/avatares/`

        if(this.img != null && this.img != undefined && this.img != ""){
          return base + this.img
        }

        if(this.gImage != null && this.gImage != undefined && this.gImage != ""){
          return base + this.gImage
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

        return files[this.av_account]

      },
      drawn_circle(p, color='#e58d37') {
        let end_point = 0
        let raio  = this.settings.size[this.size].t / 2
        let r2 = ( this.settings.size[this.size].t - this.settings.size[this.size].s ) / 2

        if( p == 0 ) end_point = 1.5 * Math.PI
        else if(p == 100) end_point = 1.4999 * Math.PI
        else end_point = (2 * Math.PI * p) / 100 - 0.5 * Math.PI

        this.context.beginPath()
        this.context.arc( raio, raio, r2, 1.5 * Math.PI, end_point )
        this.context.strokeStyle = color
        this.context.lineWidth = this.settings.size[this.size].s
        this.context.stroke()

      },
      circle(id, percent = 0, color = '#e58d37'){
        let indexCircle = this.circles.findIndex(circle => circle.id == id)
        if(indexCircle != -1) this.$set(this.circles, indexCircle, {id: id, percent: percent, color: color})
        else this.circles.push({id: id, percent: percent, color: color})

        //Reescreve o canvas
        this.context.clearRect(0, 0, this.settings.size[this.size].t, this.settings.size[this.size].t)
        this.circles.forEach(circle=> this.drawn_circle(circle.percent, circle.color))
      },
      get_color(points){
        // if(this.textPointsColor != null) return this.textPointsColor
        let color = ''
        if( points >= 80 ) color = '#8cab29'
        else if(points >= 60 ) color = '#e3c300'
        else if(points >= 40 ) color = '#f6a21c'
        else if(points >= 20 ) color = '#f86726'
        else if(points < 20 ) color = '#ff353b'
        this.$emit('clienteColor', color)
        return color
      },
    },
    watch: {
      'points': function(val){
        if( val >= 0 && val <= 100 ){
          this.circle("points", val, this.get_color(val))
        }
      }
    },
    mounted(){
      // Inicialização do canvas
      this.context = this.$refs.avatar.getContext('2d')
      // Desenha o primeiro circulo
      this.circle("base", 100, 'rgba(0, 0, 0, 0.1)')
      this.circle("points", this.sPoints(this.points), this.get_color( this.sPoints(this.points) ))
    }
  };
</script>
<style scoped>
  .points.new { color: #33c8cd; }
  .points.master { color: #e45a36; }
  .points.manufacturer { color: #e2960a; }

  .lg .cnt-infos { margin-top: 0px; font-size: 18px;}
  .md .cnt-infos { margin-top: 13px;  font-size: 17px; margin-left: 15px; }
  .sm .cnt-infos { margin-top: 5px; font-size: 12px;}
  .type-account {
    width: 40px;
      height: 40px;
      background-color: #fff;
      position: absolute;
      z-index: 100;
      border-radius: 50px;
      bottom: 0px;
      right: 0px;
      background-image: url('/static/icons-perfis.png');
      background-repeat: no-repeat;
  }
  .lg .master {background-position: 10px 9px;}
  .lg .manufacturer {background-position: 10px 9px;}
  .lg .resell {background-position: 9px -29px;}
  .lg .repair {background-position: 9px 9px;}
  .lg .sign {background-position: 9px -69px;}
  .lg .cluberplus {background-position: 7px -108px;}

  .md .type-account { background-color: rgba(0,0,0,0); bottom: -5px; right: -5px;}
  .md .master {background-position: -48px 9px;}
  .md .manufacturer {background-position: -48px 9px;}
  .md .resell {background-position: -47px -29px;}
  .md .repair {background-position: -47px 10px;}
  .md .sign {background-position: -47px -69px;}

  .sm .type-account { display: none; }


  .center .name, .center .points { text-align: center; }
  .left .name, .left .points  { text-align: left; }

  .left { display: flex }
  .left .cnt-infos { height: 100%; margin-left: 10px; }

  .center {text-align: center;}
  .center .cnt-infos { margin-top: 0px; }
  .center .cnt-image { display: inline-block; }

  .name { color: #fff; margin-bottom: -5px; overflow: hidden; text-overflow: ellipsis;  white-space: nowrap;}
  .name, .points{font-weight: bold;}
  .cnt-image { position: relative; margin-bottom: 10px;}
  .cnt-image img { position: absolute;z-index: 75;border-radius: 98px;width: 100%; height: 100%;top: 0;left: 0; }
  .cnt-image canvas { position: absolute; top: 0px; left: 0px; z-index: 100; }
</style>
