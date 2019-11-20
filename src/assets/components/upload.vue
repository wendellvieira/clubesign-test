<template>
	<form :action='base' ref='form' class='cnt-upload' method="post" enctype="multipart/form-data">

		<input type="hidden" name='token' :value='access_key'>
		<input type="hidden" name='data' value='UPLOAD_PUBLIC_IMAGE'>
		<div v-if='value == "" && ( type == "photo" || type == "file") '
			class="btn-custom"
				:style='{"background-color": moment_color}'
				@click='open_explorer("new")'>

			<img v-if='status == "" && type=="photo"' class='img cam'
				src="/static/upload/cam.png" />

			<img v-if='status == "" && type=="file" && value == ""' class='img upload'
				src="/static/upload/upload.png" />

			<img v-if='status == "uploading"' class='img uploading'
				src="/static/upload/upload.png" />

			<img v-if='status == "uploaded" || value != "" && type == "file"' class='img send'
				src="/static/upload/send.png" />

			<div v-if='status == "uploading"' class="upload-progress-text">{{progress}}%</div>
			<div v-if='status == "uploading" || status == "uploaded" || value != ""'
				:style='{bottom: value == "" ? progress-100 + "%" : "0%"}'
				class="upload-progress"></div>
		</div>
		<div v-else class="cnt-preview">
			<div v-if='out == "url" && type == "photo"' class='img-preview'
				:style="{'background-image': 'url(' + image_path + value + ')'}" >
			</div>
			<div v-else-if='out != "url" && type == "photo"' class='img-preview'
				:style="{'background-image': 'url(' + value + ')'}">
			</div>
			<div :title='getNameFile()' v-else class="btn-custom btn-send" style='background-color: #772ea2'>
				<div class="img-send"></div>
				<span>{{getNameFile()}}</span>
			</div>


			<div class="btn-action" @click='open_explorer("edit")'>
				<img v-if='spin == 0' src="/static/upload/edit.png" class='btn-edit' />
				<i v-else class="fa fa-spinner fa-spin"></i>
			</div>
		</div>
		<input ref='file' name='file'
			type="file" class='hidden' :accept="accept"
			@change='pre_upload'
		/>
		<!-- <div class="btn-action edit"></div> -->
		<div v-if='status == "uploaded" || value != "" && type == "file"'
			class="btn-action" @click='delete_file'>
			<img v-if='spin == 0' src="/static/upload/trash.png" class='btn-trash'>
			<i v-else class="fa fa-spinner fa-spin"></i>

		</div>

		<div class="clearfix"></div>
	</form>
</template>
<script>
	import md5 from 'js/md5'
	import wbs from 'js/http'
	import {mapState} from 'vuex'
	import toastr from 'toastr'

	export default {
		name: 'AppRegisterUpload',
		props: {
			value: {
				required: true,
				default: ''
			},
			type: {
				default: 'photo'
			},
			out: {
				default: 'url'
			},
			color: {
				default: "#dd6881"
			},
			max: {
				default: 10000000
			},
			min: {
				default: 1
			},
			accept: {
				default: '.jpg,.png'
			}
		},
		data(){
			return {
				base: wbs.url_base,
				access_key: null,
				status: '',
				progress: 0,
				spin: 0
			}
		},
		methods: {
			getNameFile(){
				let fileData = this.value
				let loadTypes = this.accept.split(",")
				loadTypes.forEach( item => fileData = fileData.replace(item, "" ) )
				try{
					fileData = window.atob(fileData);
					fileData = JSON.parse(fileData);
					return fileData[1];
				}catch(e){
					return "Enviado";
				}
			},
			open_explorer(key){
				if(this.value == '' || key != 'new'){
					$(this.$refs.file).click()
				}
			},
			pre_upload(e){
				if(this.max != undefined || this.min != undefined){
					let files = e.target.files || e.dataTransfer.files
					if(!files.length) return

					if(files[0].size > this.max && this.max != undefined ){
						toastr.warning('Arquivo maior que o permitido!')
						return
					}
					if(files[0].size < this.min && this.min != undefined ){
						toastr.warning('Arquivo menor que o permitido!')
						return
					}
					this.upload_file(e)
				}
			},
			upload_file(e) {
				this.spin = 1
				if( this.out == "base64" ){
					let files = e.target.files || e.dataTransfer.files
					if(!files.length) return
					this.createImage(files[0])
				}else{
					$(this.$refs.form ).ajaxSubmit({
						beforeSend: this.beforeSend,
						uploadProgress: this.uploadProgress,
						complete: this.complete
					})
				}
			},
			createImage(file){
				// let image = new Image()
				let reader = new FileReader()
				reader.onload = e => {
					this.$emit('input', e.target.result)
					this.spin = 0
				}
				reader.readAsDataURL(file)
			},
			beforeSend(){
				this.status = 'uploading'
				this.progress = 0
			},
			uploadProgress(event, position, total, percentComplete) {
				this.progress = percentComplete
			},
			complete(response) {
				this.status = "uploaded"
				let resp = JSON.parse(response.responseText)

				if(resp.data != false){
					this.$emit('input', resp.data)
					this.spin = 0
				}
			},
			delete_file(){
				this.spin = 1
				wbs.send('public-delete-pfile', {img: this.value}, resp => {
					if(resp.status == 'success'){
						$(this.$refs.file).val('')
						this.status = ''
						this.$emit('input', '')
						this.spin = 0
					}
				})
			}
		},
		computed: {
			...mapState("application", {
				image_path: s=> s.settings.image_path
			}),
			moment_color(){
				return this.color || "#dd6881"
			}

		},
		async mounted(){
			this.access_key = await wbs.access_key('public-custom-upload')
		}

	};
</script>
<style scoped>
	/*/static/upload/send.png*/
	.no-preview {

	}
	.btn-send > .img-send {
		background-image: url("/static/upload/send.png");
	    width: 55px;
	    height: 32px;
	    display: block;
	    margin: 38px auto 0 auto;
	}
	.btn-send > span {
		color: #fff;
	    font-weight: 600;
	    font-size: 16px;
	    display: block;
	    padding: 0px 20px;
	    overflow: hidden;
	    text-overflow: ellipsis;
	    white-space: nowrap;
	}



	.btn-custom:hover .img, .btn-action:hover img { animation: tada 1s linear; }
	.cnt-preview { position: relative; width: 128px; height: 128px;}
	.img-preview { background-repeat: no-repeat; background-size: cover;
    background-position: center;position: absolute;border-radius: 128px; width: 128px; height: 128px; z-index: 100; top: 0;left: 0;}
	.btn-custom {
		background-color: #dd6881; width: 128px; overflow: hidden;
	    height: 128px; border-radius: 128px; position: relative;  cursor: pointer;
	}

	.upload-progress {
		width: 100%; background-color: #915de7; height: 128px; position: absolute; z-index: 50;
		left: 0px;bottom: -110%;
	}
	.upload-progress-text {
		position: absolute; z-index: 100; top: 75px; text-align: center;
    	width: 100%; color: #fff;   font-size: 16px;

	}
	.cnt-upload { float: left; position: relative; 	}

	.btn-action {
		position: absolute; width: 40px; height: 40px; background-color: #dd6881; bottom: 0px;
		border-radius: 40px; right: 0px; z-index: 150; cursor: pointer; color: #fff; font-size: 20px;
	}
	.fa-spinner { position: absolute; top: 50%; left: 50%; margin: -10px; }
	.btn-edit { position: absolute; top: 50%; left: 50%; margin: -9.5px; }
	.btn-trash { position: absolute; top: 50%; left: 50%; margin: -9px -7px; }

	.img { position: absolute; top: 50%; left: 50%; }
	.cam { z-index: 100; margin-left: -20.5px; margin-top: -16.5px; }
	.upload { z-index: 100; margin-left: -16.5px; margin-top: -19.5px; }
	.send { z-index: 100; margin-left: -26px; margin-top: -28px;	}
	.uploading { z-index: 100; margin-left: -16.5px; margin-top: -30px; }
</style>
