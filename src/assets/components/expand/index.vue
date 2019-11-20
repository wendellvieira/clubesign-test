<template>
	<div ref='expand' class='expand'>
		<h3>{{title}}<i @click='close' class="fal fa-times-circle"></i></h3>
		<slot/>
	</div>
</template>
<script>
	export default {
		name: "Expand",
		props: ['title'],
		methods: {
			open(){
				$("#maskBlack").show(300)
				$( this.$refs.expand ).show(300)
			},
			close(){
				$("#maskBlack").hide(300)
				$( this.$refs.expand ).hide(300)
			}
		}
	};
</script>
<style scoped>
	.expand h3 i {
		font-size: 30px;
	    float: right;
	    margin: 10px 0 0 0;
	    cursor: pointer;
	}
	.expand h3 {
		background-color: #612d92;
	    margin: 0px;
	    height: 50px;
	    font-size: 18px;
	    text-transform: initial;
	    line-height: 50px;
	    text-align: left;
	    padding: 0px 15px;
	}
	.expand {
		display: none;
		position: absolute;
	    width: 320px;
	    min-height: 300px;
	    background: #6c2fa5;
	    right: 0px;
	    top: 0px;
	    color: #fff;
	    border-radius: 3px;
	    overflow: hidden;
	    z-index: 8000 !important;
	}
</style>