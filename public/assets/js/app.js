$(function(){

  $('.search input').keyup(function(e){
    clearTimeout($.data(this, 'timer'));
    var $this = $(this);
    var words = $this.val();

    if(words.length < 4){

      $this.prev()
        .removeClass("fa-spinner faa-spin animated")
        .addClass("fa-search");

      $( ".search-results").remove();
      return false;
    }
    
    $this.prev()
      .removeClass("fa-search")
      .addClass("fa-spinner faa-spin animated");

    $.data(this, 'timer', setTimeout(function() {
      $.ajax({
        method:'post',
        url:'/search',
        data : { words : words },
        cache: false,
        success: function(json){
          $( ".search-results").remove();

          $this.prev()
            .removeClass("fa-spinner faa-spin animated")
            .addClass("fa-search");

          if(json.posts){
            var str = '<div class="list-group search-results">';
            $(json.posts).each(function(i,post) {
              str+='<a href="/' + post.slug + '" class="list-group-item">' + post.title + '&nbsp;&nbsp;<small><i class="ion-android-time"></i> <em>' + post.updated + '</em></small><br><small>' + post.caption + '</small></a>';
            });
            str+='</div>';
            $( ".search" ).after(str);
          }
        }
      });          
    },250));
  });

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
      $(this).after('<div><a href="' + $(this).attr('src') + '" target="_blank"><i class="ion-android-download"></i> Descargar Audio </a></div>');
    });
  }

  $("a,span").tooltip({container: 'body'});
});