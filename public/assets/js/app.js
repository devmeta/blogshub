$(function(){

  $('#closesearch').click(function(){
    $('.search-results').slideUp();
  });

  $('#opensearch').click(function(){
    $('.search-results').removeClass('hide').hide().slideDown();
  });
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
        .removeClass("ion-funnel")
        .addClass("ion-ios-search-strong");

      return false;
    }
    
    $this.prev()
      .removeClass("ion-ios-search-strong")
      .addClass("ion-funnel");

    $.data(this, 'timer', setTimeout(function() {
      $.ajax({
        method:'post',
        url:'/search',
        data : { q : words },
        cache: false,
        success: function(json){

          $this.prev()
            .removeClass("ion-funnel")
            .addClass("ion-ios-search-strong");

          var str = '<h1 class="page-title text-center"><i class="ion-ios-search-strong"></i> ' + words + ' <span class="badge">' + json.count + '</span>&nbsp;&nbsp;&nbsp;<i class="ion-close-circle hand" onclick="$(\'.search-results\').slideUp();"></i></h1>';

          if(json.posts){
            str+= '<div class="feature-list search-list">';
            $(json.posts).each(function(i,post) {

              str+='<div class="col-md-3 col-sm-6 col-xs-12">' + 
                      '<a href="/' + post.slug + '">' +
                        '<img src="' + post.image + '" />' +
                        '<h3 class="feature-title">' + post.title + '</h3>' +
                      '</a>' +
                      '<div class="feature-attr">' +
                        '<i class="ion-android-time"></i> ' + post.created + 
                        '<i class="ion-eye"></i> ' + post.hits;
                        if(post.disqus) str+='<i class="ion-chatbubbles"></i> <span class="disqus-comment-count" data-disqus-url="' + post.slug + '">0</span>';
              str+= '</div></div>';
            });
            str+= '</div>';
          } 

          str+='<div class="clearfix"></div>';

          if( $( ".search-posts").length){
            $( ".search-posts").html(str);
          } else {
            $( ".search-posts" ).hide()
              .html(str)
              .fadeIn();
          }
        }
      });
    },300));
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

  /*if($('.twcnt').length){
    share.jsonRequest('http://urls.api.twitter.com/1/urls/count.json?url=' + location.href + '&callback=share.twitterCountCallback');
  }*/

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