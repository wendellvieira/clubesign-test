<template>
	<div class="cnt-stars">
		<i v-for='n in 5' @click='$emit("input", n)' :data-id='n' class="fas fa-star"></i>
	</div>
</template>
<script>
	export default {
		name: "stars",
		props: ["value"],
		mounted(){
			let self = this
			setTimeout(e=> {
				$('.cnt-stars i').hover(
					function(){
						var hover = $(this).data('id')
						$('.cnt-stars i').each(function(){
							$(this).removeClass('active')
							if( $(this).data('id') <= hover){
								$(this).addClass('active')	
							} 
						})
					},
					function(){
						$('.cnt-stars i').each(function(){
							$(this).removeClass('active')
							if( $(this).data('id') <= self.value){
								$(this).addClass('active')	
							} 
						})
					}
				)
			}, 500)
		}
	};
</script>
<style scoped>
	.cnt-stars {
		text-align: center;
		margin: 29px 0 -10px 0;
	}
	.cnt-stars i {
		font-size: 40px;
	    color: #d8d9de;
	    cursor: pointer;
	}
	.cnt-stars i.active {
		color: #f8b300;
	}
	
</style>