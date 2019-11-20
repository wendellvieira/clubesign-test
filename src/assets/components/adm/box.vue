<template>
	<div class='box' :class='type != undefined ? "box-" + type : ""'>
		<div class="box-header with-border">
			<h3 class="box-title">{{title}}</h3>
			<div v-if='$slots.tools != undefined' class='box-tools pull-right'>
				<slot name='tools'/>
			</div>
		</div>
		<div 
			v-if='$slots.body != undefined' 
			class="box-body" 
			:class='{ "no-padding" : noPadding != undefined }'
		>
			<slot name='body'  />
		</div>
		<div v-if='$slots.footer != undefined' class="box-footer">			
			<slot name='footer'  />
		</div>
	</div>
</template>
<script>
	export default {
		name: 'AppBox',
		props: ['type', 'title', 'no-padding']
	}
</script>
<style scoped>
	h3.box-title {
		font-size: 17px;
	}
</style>