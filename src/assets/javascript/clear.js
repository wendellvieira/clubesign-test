export default val => {
	val = val.replace(/[áàãâä]/gui, 'a');
    val = val.replace(/[éèêë]/gui, 'e');
    val = val.replace(/[íìîï]/gui, 'i');
    val = val.replace(/[óòõôö]/gui, 'o');
    val = val.replace(/[úùûü]/gui, 'u');
    val = val.replace(/[ç]/gui, 'c');
    val = val.replace(/[^a-z0-9]/gi, '_');
    val = val.replace(/_+/g, '_'); //
    return val.toLowerCase();
}