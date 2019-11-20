import WBS from 'js/http'

export default (Img, progress = null, path = 'public') => {
  return new Promise( async (response, reject) => {
    const token = await WBS.access_key('public-custom-upload')
    let form = new FormData;
    form.append("file", Img);
    form.append("path", path);
    form.append("token", token )
    form.append("data", "UPLOAD_PUBLIC_IMAGE")

    let xhr = new XMLHttpRequest
    xhr.open( 'POST', WBS.url_base );
    // xhr.setRequestHeader('Content-Type', 'multipart/form-data');

    if(progress != null) xhr.addEventListener('progress', progress)
    xhr.addEventListener("error", err => reject(err))
    xhr.addEventListener("abort", err => reject(err))
    xhr.addEventListener('load', (status) => {
      try{
        const file = JSON.parse(status.target.responseText)
        if(file.status == "success"){
          response(file.data)
        }else{
          reject("Erro ao enviar imagem!")
        }
      }catch(err){
        reject(err)
      }
    })

    xhr.send(form);

  })
}
