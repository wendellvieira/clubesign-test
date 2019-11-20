export let group = {
	row: null,
	id: null,
	cod: null,
	title:null,
	sgroup: []	
};
export let sgroup = {
	row: null,
	id: null,
	title:null,
	inputs: [],
	settings: {
		filtro_grade: '',
		status: '1',
		regra_pantone: [],
		
		sku_tag: '',
		color_tag: '',
		default_pantone: 0
	}
};
export let input = {
	row: null,
	id: '',
	title: '',
	size: '4',
	type: 'radio',
	tag: '',
	options : [],
	visable: [],
	fxIn: '',
	fxOut: '',
	required: true,
	block: false,
	group: false,
	dependence: false,
	oculto: false
};
export let sData = {
	group: '',
	sgroup: '',
	filter_grade: '',
	color: null,
	tags: null,
	manufacturers: null
};