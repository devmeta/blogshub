var app={};

$(function(){
  $.ajax({
    method:'post',
    url:'/api/detect',
    data : { id : $('html').attr('lang') },
    cache: false,
    success:function(json){
      if(json){
        app = json;
        var page = "posts";
        if($('link[rel="group"]').attr('href')!=""){
          page = $('link[rel="group"]').attr('href');
        } 
        $(".navbar a").tooltip({container: 'body'});
        $(".content").engine({startpage:page});
      }
    }
  });
});

$(document).on("keyup",'.search input',function(e){
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
      url:'/posts/search',
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
            str+='<a href="/posts/' + post.slug + '" class="list-group-item">' + post.title + '&nbsp;&nbsp;<small><em>' + post.updated + '</em></small></a>';
          });
          str+='</div>';
          $( ".search" ).after(str);
        }
      }
    });          
  },250));
});

$(document).on("click",'.pop-link',function(e){
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

$(document).on('click', '.goup', function(e) {
  $('body,html').animate({scrollTop: 0}, 400);
});

$(document).on('display','body',function(){

  $( ".search-results").remove();

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
      url:'/api/ip2geo',
      data : { path : location.pathname },
      cache: false
    });          
  },500);

  if( $("audio").length){
    $("audio").each(function(){
      $(this).after('<div><a href="' + $(this).attr('src') + '" target="_blank"><i class="ion-android-download"></i> ' + _l('Download_Audio') + '</a></div>');
    });
  }

  
  Rainbow.color();
});

$(document).on('posts','body',function(){
  disqus_comments_count();
});

$(document).on('post','body',function(){
  disqus_comments_count();
});