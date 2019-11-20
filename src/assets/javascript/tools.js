import md5 from 'js/md5'
export let clone = payload => JSON.parse(JSON.stringify(payload));
export let pad = (nr, n, str = 0) => Array(n-String(nr).length+1).join(str||'0')+nr;
export let fDate = (date = null, mask = "dsxs, DD de xmlg de YY") => {
	// if( date == null ) date = new Date()		
	if( typeof(date) != "object" ) date = new Date(date)
	const meslg = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"]   
	const mesxs = ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"] 
	const semlg = ["Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira","Sexta-feira", "Sábado"]
	const semxs = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"]

	//estenso
	mask = mask.replace("xmlg", meslg[date.getMonth()])
	mask = mask.replace("xmxs", mesxs[date.getMonth()])
	mask = mask.replace("dslg", semlg[date.getDay()])
	mask = mask.replace("dsxs", semxs[date.getDay()])

	//com zero
	mask = mask.replace("YY", date.getFullYear())
	mask = mask.replace("MM", pad(date.getMonth() + 1, 2, 0) )
	mask = mask.replace("DD", pad(date.getDate(), 2, 0) )
	//sem zero
	mask = mask.replace("yy", date.getFullYear() - 2000)		
	mask = mask.replace("mm", date.getMonth() + 1 )
	mask = mask.replace("dd", date.getDate() )

	mask = mask.replace("HH", pad( date.getHours(), 2, 0) )
	mask = mask.replace("II", pad( date.getMinutes(), 2, 0) )
	mask = mask.replace("SS", pad( date.getSeconds(), 2, 0) )

	mask = mask.replace("hh", date.getHours())
	mask = mask.replace("ii", date.getMinutes())
	mask = mask.replace("ss", date.getSeconds())

	
	return mask
		
};
export let clear =  val => {
	val = val.replace(/[áàãâä]/gui, 'a');
    val = val.replace(/[éèêë]/gui, 'e');
    val = val.replace(/[íìîï]/gui, 'i');
    val = val.replace(/[óòõôö]/gui, 'o');
    val = val.replace(/[úùûü]/gui, 'u');
    val = val.replace(/[ç]/gui, 'c');
    val = val.replace(/[^a-z0-9]/gi, '+');
    val = val.replace(/_+/g, '+'); //
    return val.toLowerCase();
};
export let newId = (val = '') => {
	return md5.get(Math.random() * (new Date).getMilliseconds() + new Date() + Math.random()*10000 + val)
};