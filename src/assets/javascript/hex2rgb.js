export default {
    cBase: '',
    cRelative: '',
    create(cbase, crelative){
        this.cBase = this.hex2rgb(cbase)
        this.cRelative = this.hex2rgb(crelative)
    },
    // perfil(color){
    //     let perfil = this.hex2rgb(color)
    //     let rp = { r: 0, g: 0, b: 0}
    //     // rp.r = this.limit( (perfil.r * this.cBase.r) / this.cRelative.r ) 
    //     // rp.g = this.limit( (perfil.g * this.cBase.g) / this.cRelative.g ) 
    //     // rp.b = this.limit( (perfil.b * this.cBase.b) / this.cRelative.b ) 
    //     rp.r = this.calc(perfil.r, this.cBase.r, this.cRelative.r)
    //     rp.g = this.calc(perfil.g, this.cBase.g, this.cRelative.g)
    //     rp.b = this.calc(perfil.b, this.cBase.b, this.cRelative.b)

    //     return `rgb(${rp.r}, ${rp.g}, ${rp.b})`
    //     // return this.rgbToHex(rp)
    // },

    perfil(color){
        let perfil = this.hex2rgb(color)
        let rp = { r: 0, g: 0, b: 0}
        rp.r = this.limit(this.cBase.r + ( perfil.r - this.cRelative.r) ) 
        rp.g = this.limit(this.cBase.g + ( perfil.g - this.cRelative.g)) 
        rp.b = this.limit(this.cBase.b + ( perfil.b - this.cRelative.b)) 
        return `rgb(${rp.r}, ${rp.g}, ${rp.b})`
        // return this.rgbToHex(rp)
    },
    rgbToHex(rgb) {
        return "#" + ((1 << 24) + (rgb.r << 16) + (rgb.g << 8) + rgb.b).toString(16).slice(1);
    },
    calc(v1, v2, v3){
        v1 = this.c2p(v1); v2 = this.c2p(v2); v3 = this.c2p(v3);
        let calc = (v1 * v2) / v3
        // console.log( this.p2c( this.limit(calc) ))
        return this.limit( this.p2c( calc ) )
    },
    p2c(val){
        return val * 255 / 100
    },
    c2p(val){
        return 100 * val / 255
    },
    limit(canal){
        canal = parseInt(canal)      
        if(canal < 0 ) return 0
        else if(canal > 255) return 255
        else return canal || 0
    },
    hex2rgb(hex){
        var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        hex = hex.replace(shorthandRegex, function(m, r, g, b) {
            return r + r + g + g + b + b;
        });
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }
}