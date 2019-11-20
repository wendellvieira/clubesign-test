import WBS from 'js/http'
import toastr from 'toastr'
export default {
	methods: {
		toDataUrl(url, callback) {
		    let xhr = new XMLHttpRequest();
		    xhr.onload = function() {
		        let reader = new FileReader();
		        reader.onloadend = function() {
		            callback(reader.result);
		        }
		        reader.readAsDataURL(xhr.response);
		    };
		    xhr.open('GET', url);
		    xhr.responseType = 'blob';
		    xhr.send();
		},
		login_fb(callback = null, reauthenticate = false){
			let settings = { scope: 'email,user_birthday' }
			
			let fields = "id,birthday,email,gender,name,picture.width(160).height(160)"	
			let tratmentResponse = (data = null, callback, status) =>{
				if(callback != null){
					try{ callback(data, status) }catch(e){
						console.log(e)
					}
				}
			};
			let processor =  resp => {
				if(resp.status == "connected" ){
					let sData = {userID: resp.authResponse.userID, type: 'APP'}
					
					WBS.send('public-custom-login', sData, rWbs => {
						if(rWbs.status == 'success' && rWbs.data == undefined ){
							let data_facebook = {}
							FB.api('/me', 'GET', { fields: fields }, gData => {
								this.toDataUrl(gData.picture.data.url, gUrl => {
									gData.img = gUrl
									tratmentResponse(gData, callback, "unregistred")
								})						
						    })
						} else {
							if(rWbs.status == 'success'){
								tratmentResponse(rWbs, callback, "registred")
							}else{
								toastr.warning(rWbs.msgs[0])
								console.log(rWbs)
							}
						}
					})
				}				
			};
			if(!reauthenticate)	FB.login(processor, settings) 

		},
	}
}