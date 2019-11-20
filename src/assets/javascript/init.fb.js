window.fbAsyncInit = function() {
    FB.init({
      // appId      : '341181423023297',
      appId      : '341181423023297',

      xfbml      : true,
      version    : 'v2.11',
      // version    : 'v3.0',
    });
    FB.AppEvents.logPageView();
  };
  (function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));