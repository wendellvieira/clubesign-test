<template>
	<div :id='"radio_" + id' v-if='options != null' class="cnt-float-select">
		<label class='cnt-custom-select'>
			<input type="radio" class='hidden-input'>
			<div class="selected-values"
				:class='{"disabled": this.options.length == 0}' :style='{ "background-color": color }'
				 @click='fxOpen'>
				<span :class='{ "placeholder" : value_selected() == title}'>
					{{value_selected()}}
				</span>
				<i v-if='!block' class="fa fa-caret-down icon"></i>
				<i v-else class="fas fa-lock-alt icon"></i>
				<div class="clearfix"></div>
			</div>
			<div class="hidden-context" :class='type + "-custom"'
				:style='{"max-height": height + "px", "background-color" : color }'>
				<div class="color-variate">
					<br>
					<div v-if='options != undefined' class="cnt-options">
						<div v-for='(item, id) in options' class="item" v-if='item != undefined'
							:class='is_selected(id, item)' @click='select(id, item)'>
							<div class="box"></div>
							<span>{{item[text]}}</span>
							<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
					<div :id='"btn_" + id' v-if='type != "radio"' class="btn-custom" @click='close'>
						{{text_button}}
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</label>
	</div>
</template>
<script>
	import md5 from 'js/md5'
	export default {
		name: 'AppCustomSelect',
		props: {
			type: String,
			options: Array,
			title: String,
			color: String,
			value: {
				default:''
			},
			block: {
				default: false
			},
			cod: {
				default: 'cod'
			},
			text: {
				default: 'text'
			},
      required: {
        default: true
      }
		},
		data(){
			return {
				id: md5.get( Math.random() ),
				open: 0,
				ropen: 0,
				cpnt_value: [],
			}
		},
		computed:{
      text_button(){
        if(this.required === true) return 'CONFIRMAR'
        if(this.cpnt_value.length == 0) return 'PULAR'
        else return 'CONFIRMAR'
      },
			agroup(){
				return typeof this.group == 'undefined' ? false : this.group
			},
			ctn_hover:{
				get(){
					return this.hoverSpan
				},
				set(val){
					this.$set(this.hoverSpan, val.id, val.st)
				}
			},
			height(){
				if (this.open == 0)	return 0
				else return this.type == "radio" ? 176 : 240
			}
		},
		watch: {
			"value": function(val){
				this.cpnt_value = this.value
			},
			"block": function(val){
				if(val == true){
					if(this.options[0] != undefined){
						this.select(0)
					}
				}
			}
		},
		methods: {
			clear(){
				this.cpnt_value = []
			},
			fxOpen(){
				if(!this.block){
					this.$emit('focusin')
					if(this.options.length > 0){
						if(this.open == 0) this.open = 1
						else this.close()
					}
				}
			},
			value_selected(){
				let values = []
        if(this.cpnt_value === "") return this.title || 'Selecionar'

				this.cpnt_value.forEach(item => {
					if(this.cod == null){
						values.push( item[this.text] )
					}else{
						if(typeof this.options[0] == 'object'){
							let option = this.options.find(i=> i[this.cod] == item)
							values.push( option[this.text] )
						}else values.push( this.options[item] )
					}
				})

				if(values.length == 0) return this.title || 'Selecionar'
				else return values.join(', ')
			},
			close(){
        if(this.required !== true && this.cpnt_value.length == 0){
          this.$emit('advence')
        }else{
  				this.$emit('focusout')
  				this.$emit("input", this.cpnt_value || [])
  				this.open = 0
        }

			},
			select(id, item){
        if(item != undefined && this.cpnt_value !== ""){
          this.ropen = 1
          if(this.type == "radio"){
            if(this.cod == null){
              this.$set(this.cpnt_value, 0, item)
            }else if( item[this.cod] == undefined ){
              this.$set(this.cpnt_value, 0, id)
            }else{
              this.$set(this.cpnt_value, 0, item[this.cod])
            }
            this.close()
          }else{
            if(this.cod == null){
              let index = this.cpnt_value.findIndex(lItem => lItem == item)
              if(index !== -1) this.cpnt_value.splice(index, 1)
              else this.cpnt_value.push(item)

            }else if( item[this.cod] == undefined ){
              let index = this.cpnt_value.findIndex(lItem => lItem == id)
              if(index !== -1) this.cpnt_value.splice(index, 1)
              else this.cpnt_value.push(id)

            }else{
              let index = this.cpnt_value.findIndex(lItem => lItem == item[this.cod])
              if(index !== -1) this.cpnt_value.splice(index, 1)
              else this.cpnt_value.push(item[this.cod])
            }
          }
        }else{
          if(item != undefined && this.type != "radio"){
            this.cpnt_value = []
            this.$emit("input", [])
          }
        }
      },
      is_selected(id, item){
        let index = -1
        if(item == undefined || this.cpnt_value === "") return ""
        if(this.cod == null) index = this.cpnt_value.findIndex(lItem => lItem == item)
        else if(item[this.cod] == undefined) index = this.cpnt_value.findIndex(lItem => lItem == id)
        else index = this.cpnt_value.findIndex(lItem => lItem == item[this.cod])
        return index === -1 ? "" : "active"
      },
      focus_out(){
        // setTimeout(() => {
        //  if(
        //    this.open == 1 &&
        //    (
				// 			this.ropen != 1 && this.type != "radio" ||
				// 			this.ropen == 0 && this.type == "radio"
				// 		)
				// 	) this.close()
				// 	this.ropen = 0
				// }, 100)
			}
		},
		mounted(){

			// $("#btn_" + this.id).hover(e => {
			// 	if(e.type == "mouseenter") $("#btn_" + this.id).css("color", this.color)
			// 	else $("#btn_" + this.id).css("color", "#fff")
			// })
			// $("#radio_" + this.id).hover(e => {
			// 	if(e.type == 'mouseleave') this.close()
			// })


			if( this.block == true ){
				if(this.options[0] != undefined){
					this.select(0)
				}
			}
			if(typeof this.value == 'object') this.cpnt_value = this.value
			// this.options.forEach(() => {
			// 	this.hoverSpan.push(0)
			// })
		}
	};
</script>
<style scoped>
	.hidden-input {
		opacity: 0;
	    width: 0;
	    height: 0;
	    margin: 0;
	    float: left;
	    padding: 0;
	}
	.color-variate {
		background-color: rgba(0, 0, 0, .15)
	}
	.icon {
		float: right;
    	margin: 4px 0px 0 0;
    	font-size: 16px;
	}
	.placeholder {
		color: rgba(255,255,255,0.5);
	}
	.title {
		font-size: 18px;
	    font-weight: 400;
	}
	.radio-custom .item .box { border-radius: 45px }
	.radio-custom .item .box:before { border-radius: 45px }
	.cnt-float-select { position: relative; width: 100%; height: 42px; 	}
	.selected-values span {
		font-weight: bold; float: left; max-width: calc( 100% - 18px);
		white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
	}
	.hidden-context {
		transition: all linear .3s; display: block; position: relative; z-index: 100; background: #01a3d2;
	}
	.btn-custom {
		background-color: rgba(0, 0, 0, .1); font-weight: bold; text-align: center; display: block; padding: 15px 0px;
		cursor: pointer; transition: all linear .2s; margin: 0px 5px 5px 5px;
	}
	.btn-custom:hover { background-color: #fff; color: #000;}
	.item { padding: 3px; display: block; cursor: pointer; }
	.item:hover span { color: #fff }
	.item span{
		margin: 5px 0px 5px 40px; display: block; font-weight: bold; color: rgba(255, 255, 255, 0.4);
		width: calc( 100% - 35px ); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
		text-align: left;
	}
	.item .box { margin-bottom: 0px;
		float: left; width: 35px; height: 35px; background-color: rgba(0, 0, 0, .1);
		 border-radius: 0px; border:none;
	}
	.active span { color: #fff }
	.active .box:before {content: "";background-color: #fff; margin: 5px; width: 25px; height: 25px;display: block; }

	.cnt-options {
		display: block; padding: 3px 10px; max-height: 156px; overflow: auto; margin: -15px 14px 10px 0px;
	}

	.cnt-options::-webkit-scrollbar-track { background-color:rgba(95, 95, 95, 0.05); border-radius: 5px; }
	.cnt-options::-webkit-scrollbar { width: 3px; background:rgba(95, 95, 95, 0.05); margin-right: 5px; }
	.cnt-options::-webkit-scrollbar-thumb { background: #fff; border-radius: 5px; }
	.cnt-custom-select {
		display: block; position: relative; color: #fff;
		font-size: 18px; font-weight: bold; border-radius: 3px; overflow: hidden;
	}
	.selected-values {
		background-color: #00b4e7; font-size: 18px; font-weight: bold; padding: 8px 10px; cursor: pointer;
	}
	.caret { float: right; margin: 11px 5px; }
	.disabled {background: #cacdd2; cursor: not-allowed;}
</style>
