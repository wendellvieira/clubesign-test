function getValue(data, value){
	if( typeof data != "object" ) return -1
	if(data[value] != undefined) return  data[value]
	else return -1
}
export default function(data, regras){
	if(regras != undefined){
		let resp = -1
		regras.forEach(item => {
			if(item.value == getValue(data, item.input)){
				resp = item.status
			}
		})
		return resp		
	}else{
		return -1
	}
}
