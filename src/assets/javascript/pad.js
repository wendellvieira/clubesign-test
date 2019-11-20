export default function(nr, n, str = 0){
	return Array(n-String(nr).length+1).join(str||'0')+nr;
}