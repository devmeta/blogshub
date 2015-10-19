$(function(){

  if($('.slick-dotted').length){
    $('.slick-dotted').slick({    
      dots: true,
      infinite: true,
      speed: 450,
      pauseOnHover: false,
      autoplaySpeed: 10000,
      autoplay:true,
      arrows: false,
      fade: true
    });
  }

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
            str+='<a href="javascript:void(0)" onclick="$(\'.search-results\').fadeOut();" class="list-group-item text-right"><i class="ion-close"></i></a>';
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

  if($('.pop-link').length){
    $('.pop-link').click(function(e){
      e.preventDefault();

      var width = 500;
      var height = 500;
      // popup position
      var
          px = Math.floor(((screen.availWidth || 1024) - width) / 2),
          py = Math.floor(((screen.availHeight || 700) - height) / 2);

      // open popup
      var popup = window.open($(this).attr('href'), "social", 
          "width="+width+",height="+height+
          ",left="+px+",top="+py+
          ",location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1");
      if (popup) {
          popup.focus();
          if (e.preventDefault) e.preventDefault();
          e.returnValue = false;
      }

      return false;
    });
  }

  if($('.twcnt').length){
    share.jsonRequest('http://urls.api.twitter.com/1/urls/count.json?url=' + location.href + '&callback=share.twitterCountCallback');
  }

  if($('.fbcnt').length){
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