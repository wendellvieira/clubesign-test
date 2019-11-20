<template>
	<nav v-if='abas != null' :class="`${type} size-${size}`">
		<li class="navigate" ref='navigate' :style='{"background-color": color}'></li>
		<ul v-for='(aba, id) in abas'
			@click='EmitEvent(aba, id)'
			:class='{ "active" : is_selected(aba, id) }'
			:style='{ "color" : fColor }'
			>
				<li ref='abas' v-html='get_text(aba)'></li>
				<span v-if='aba.span != undefined && aba.span != null && aba.span != 0'>
					{{aba.span}}
				</span>
		</ul>
		<div class="clearfix"></div>
	</nav>
</template>
<script>
	export default {
		name: 'SmartNav',
		props: {
			abas: {
				default: null
			},
      size: {
        default: 'md'
      },
			value: {
				default: ''
			},
			type: {
				default: 'tab'
			},
			color: {
				default: '#1499ed'
			},
			fColor: {
				default: '#b5bac2'
			},
			text: {
				default: 'text'
			},
			cod: {
				default: 'cod'
			}
		},
    watch: {
      'value': function(val, old){
        if(this.cod != null){
          const index = this.abas.findIndex(item => item[this.cod] == val)
          this.presentation(index)
        }else{
          this.presentation(val)
        }
      }
    },
		methods: {
			is_selected(aba, id){
				let visable = false
				if(this.cod == null || aba == undefined || aba[this.cod] == undefined){
					visable = id == this.value
				}else{
					visable = this.value == aba[this.cod]
				}
				if( this.cod != null || ( id != -1 && this.cod == null)) return visable
			},
			get_text(aba){
				if(aba[this.text] != undefined){
					return aba[this.text].replace(/<[^>]+>/ig,"")
				}else {
					return '???'
				}
			},

      EmitEvent(aba, index){
        if(this.cod == null || aba == undefined || aba[this.cod] == undefined){
          this.$emit("input", index)
        }else{
          this.$emit("input", aba[this.cod])
        }
      },
			presentation(index){
				let left = 0;
        let recuo = { md: 40, sm: 20 }
				for(let x =0; x < index; x++) left += $(this.$refs.abas[x]).width() + recuo[this.size]
				$(this.$refs.navigate).css({"left": left}).width( $(this.$refs.abas[index]).width() )
			},


			setSpan(cod, val){
				if(this.abas == undefined || this.abas == null) return;
				let index = this.abas.findIndex( item => item.cod == cod )
				if(index == -1) return false
				else this.$set( this.abas[index], 'span', val )
			}
		},
		mounted(){
			setTimeout(e=> {
				let gIndex = false
				let index = this.abas.findIndex((item, id) => {
					if(this.cod == null || item == undefined || item[this.cod] == undefined){
						gIndex = true
						return id == this.value
					}else{
						return item[this.cod] == this.value
					}
				})
				if( this.cod != null || ( index != -1 && this.cod == null) )
						this.presentation(index)
			}, 500)
		}
	};
</script>
<style scoped>

  .size-sm li{
    line-height: 25px !important;
  }
  .size-sm ul, .size-sm .navigate {
    height: 25px !important;
    padding: 0 10px !important;
  }

	.button {
		border: 1px solid #e5e7ea; border-radius: 50px; float: left;
		background-color: #fff; position: relative;
	}

	.button ul { float: left; position: relative; z-index: 50;	}
	.button .navigate {
		z-index: 10;position: absolute;background-color: #1499ed; border-radius: 40px;
	}
	.button ul, .button .navigate  {
		height: 40px; padding: 0 20px;margin: 0;transition: 0.1s linear all;
	}
	.button li {
		list-style: none;font-size: 13px;text-align: center;line-height: 40px;
		color: #d2d5d9;cursor: pointer;
	}
	.button ul.active {  border-radius: 40px;}
	.button ul.active li {	  color: #fff;}


	/* Estilo da nav tab */
		.tab {margin-bottom: 25px;border-bottom: 1px solid #dadcdf; position: relative;}
		.tab ul.active li:after {height: 3px;}
		.tab ul.active li {opacity: 1;}
		/*.tab ul li:after {content: '';height: 0px;width: 100%;background-color: #1499ed;display: block;position: absolute;bottom: -4px;transition: 0.1s linear all;}*/
		.tab ul {position: relative;color: #b5bac2;font-size: 16px;float: left;margin-right: 40px;cursor: pointer;margin-bottom: 3px;padding: 15px 0px;}
		.tab li {list-style: none;float: left;position: relative;opacity: 0.5;}
		.tab ul span {background-color: #ed146b;color: #fff;padding: 1px 6px;border-radius: 3px;margin: -2px 0 0 5px; position: absolute;}
		.tab li.navigate {width: 0px;height: 3px;position: absolute;bottom: 0px;opacity: 1;
			transition: 200ms linear all}


</style>
