<template>
	<div>
		<smtModal title='<i class="fal fa-crop"></i> Cortar Imagem' ref='modal' no-padding static>
			<div slot='body'>
				<img ref='image' :src="src" />
			</div>
			<div slot='footer'>
				<button class="btn btn-default" @click='close'>
					<i class="fal fa-times"></i>
					Cancelar
				</button>
				<button class="btn btn-primary" @click='crop'>
					<i class="fal fa-save"></i>
					Cortar
				</button>
			</div>
		</smtModal>
		<input v-if='src == null' type="file" @change='loadImage' ref='file' class='hidden'/>
	</div>
</template>
<script>
	import smtModal from 'app/modal'
	import Cropper from 'cropperjs'
	import 'cropperjs/dist/cropper.min.css'

	export default {
		name: 'smartCrop',
		props: ['w', 'h'],
		components: {smtModal},
		data(){
			return {
				src: null,
				cropper: null,
				type: 'image/jpeg'
			}
		},
		methods: {
			crop(){
				let imgCroped = this.cropper
					.getCroppedCanvas({width: this.w, height:this.h})
					.toDataURL(this.type)

				if(imgCroped != ''){
					this.$emit('beforeOpened', imgCroped)
					this.$refs.modal.close()
					setTimeout(e => {
						this.src = null
						this.cropper.destroy()
						this.cropper = null						
					}, 100)
				} 
			},
			close(){
				this.$refs.modal.close()
				setTimeout(e => {
					this.src = null
					this.cropper.destroy()
					this.cropper = null						
				}, 100)	
			},
			open(){
				$(this.$refs.file).click()
			},
			loadImage(e){
				let files = e.target.files || e.dataTransfer.files
				if(files.length != 0){
					this.type = files[0].type
					let reader = new FileReader()
					reader.onload = e => {
						this.src = e.target.result
						this.$refs.modal.open()
						setTimeout(e=>{
							this.mount_cropper()						
						}, 100)
					}
					reader.readAsDataURL(files[0])					
				}
			},
			mount_cropper(){
				this.cropper = new Cropper(this.$refs.image, {
					responsive: true,
					cropBoxResizable: true,
					minContainerWidth: 600,
					minContainerHeight: 300,
					viewMode: 1,
					aspectRatio: this.w/this.h
				})
			}
		},
		mounted(){
						// this.mount_cropper()						

		}
	};
</script>
<style scoped>
	img {
		max-width: 100%;
	}
</style>