export default (x, in_min, in_max, out_min = 0, out_max = 100) => {
	return (x - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
}
