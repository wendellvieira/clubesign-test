import {mapState} from 'vuex'
export default {
	computed: {
		...mapState("application", {
			path_file: function(state){
				return state.settings.image_path
			}
		})
	},
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
		download(name, data, nw = true) {

		    //Internet Explorer
		    if (navigator.msSaveBlob) {
		        var blob = new Blob([this.path_file + data]); //Aplica o conteúdo para baixar
		        navigator.msSaveBlob(blob, name); //Nome do arquivo
		        return;
		    }

		    var body = document.body;

		    this.toDataUrl(`${this.path_file}index.php?img=${data}`, rest => {
			    var uridown = "data:application/octecstream," + encodeURIComponent(rest);

			    var el = document.createElement("a"); //Cria o link

			    el.download = name; //Define o nome

			    el.href = uridown; //Define a url

			    //Adiciona uma classe css pra ocultar
			    el.className = "hide";

			    //Força abrir em uma nova janela
			    if (nw) {
			        el.target = "_blank";
			    }

			    //Adiciona no corpo da página (necessário para evitar comportamento variado em diferentes navegadores)
			    body.appendChild(el);

			    if (el.fireEvent) {
			        //Simula o click pra navegadores com suporte ao fireEvent
			        el.fireEvent("onclick");
			    } else {
			        //Simula o click com MouseEvents
			        var evObj = document.createEvent("MouseEvents");
			        evObj.initEvent("click", true, false);
			        el.dispatchEvent(evObj);
			    }
			    //Remove o link da página
			    setTimeout(function() {
			        body.removeChild(el);
			    }, 100);
		    	
		    })

		}
	}
}