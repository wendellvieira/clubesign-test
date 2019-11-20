<template>
	<button :class='btn_class' @click='$emit("click")' :disabled='is_disabled'>
		<i v-if='icon_class != "fa "' :class='icon_class'></i>
		{{text}}
	</button>
</template>
<script>
	export default {
		name: 'AppButton',
		props: ['text', 'type', 'size', 'icon', 'disabled'],
		computed: {
			btn_class(){
				let bClass = 'btn'
				bClass += this.size != undefined ? " btn-" + this.size : ''
				if(this.is_disabled){
					bClass += ' btn-default'
				}else{
					bClass += this.type != undefined ? " btn-" + this.type : ' btn-default'					
				}
				return bClass
			},
			icon_class(){
				let iClass = 'fa '
				iClass += this.icon != undefined ? "fa-" + this.icon : ''
				return iClass
			},
			is_disabled(){
				return this.disabled != undefined ? this.disabled : false
			}
		}
	}
</script>