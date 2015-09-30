$(function(){

  if($('.twcnt1').length){
    share.jsonRequest('http://urls.api.twitter.com/1/urls/count.json?url=' + location.href + '&callback=share.twitterCountCallback');
  }

  if($('.fbcount1').length){
    share.jsonRequest('http://api.facebook.com/restserver.php?method=links.getStats&urls=' + location.href + '&callback=share.fbCountCallback');
  }

  setTimeout(function(){
    $.ajax({
      method:'post',
      async: true,
      url:'/ip2geo',
      data : { path : location.pathname },
      cache: false
    });          
  },500);

  if( $("audio").length){
    $("audio").each(function(){
      $(this).after('<div><a href="' + $(this).attr('src') + '" target="_blank"><i class="ion-android-download"></i> ' + _l('Download_Audio') + '</a></div>');
    });
  }

  $("a,span").tooltip({container: 'body'});
});