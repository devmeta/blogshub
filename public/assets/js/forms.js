/* 2. Forms */

/* 2.1 General form callbacks */

function newsletter_add(json){
  if(json.result=='already_suscribed'){
    $('.suscribe-response')
      .removeClass('alert-success')
      .addClass('alert-warning')
      .html(_l("Already_Suscribed"))
      .removeClass("hide")
      .hide()
      .slideDown();
  }

  if(json.result=='success'){
    $('.suscribe-response')
      .removeClass('alert-warning')
      .addClass('alert-success')
      .html(_l("Suscribe_Success"))
      .removeClass("hide")
      .hide()
      .slideDown();
  }

  setTimeout(function(){
    $('.disabled').removeClass('disabled');
  },1000);
}

function disqus_comments_count(){
  var disqusPublicKey = "XXjc1IvtLbgPm70Nkl7WZoQtqiy0GS4knkamSG1EtgZEi4XLwrmMsRqNZKHAUCTX";
  var disqusShortname = "devmetablogs";
  var urlArray = [];

  $('.count-comments').each(function () {
    var url = $(this).attr('data-disqus-url');
    urlArray.push('link:' + url);
  });

  $.ajax({
    type: 'GET',
    url: "https://disqus.com/api/3.0/threads/set.jsonp",
    data: { api_key: disqusPublicKey, forum : disqusShortname, thread : urlArray },
    cache: false,
    dataType: 'jsonp',
    success: function (result) {
      for (var i in result.response) {
        var count = result.response[i].posts;
        $('span[data-disqus-url="' + result.response[i].link + '"]').html(count);
      }
    }
  });
}


function post_tags(post_id){
  $.ajax({
    type:"POST",
    url: "/posts/tags",
    data: {
      post_id : post_id
    },
    success : function(json){
      if(json){
        $(json).each(function(i,tag){
          $( ".post-tags" ).append( '<a class="label label-success" href="/tags/' + tag + '">' + tag + '</a>&nbsp;&nbsp;' );
        });
      }
    }
  });
}

function profile_update(json){
  success('Profile_Updated');
  $('button[type="submit"]').removeClass('disabled');
}

function auth_signup_preview(json){
  var frm = $('form[action="/auth/signup_preview"]');
  fdata = frm.serializeArray();
  g('signup');
}

function auth_signup(json){
  location.href= '/';
}

function auth_signin(json){
  if(json.result){
    location.href= "/";
    return false;
  }
  $('button[type="submit"]').removeClass('disabled');
  alerttmpl('badlogin',_l('Bad_Login'));
}

function feed_add(json){
  $('textarea').val('');
  $('button[type="submit"]').removeClass('disabled');
  success("Status_Updated");
  loadfeed();
  BootstrapDialog.closeAll();
}

function posts_edit(json){
  success("Post Updated");
  g(location.pathname);
  BootstrapDialog.closeAll();
}

function users_edit(json){
  success("User Updated");
  g(location.pathname);
  BootstrapDialog.closeAll();
}

function groups_edit(json){
  success("Group Updated");
  g(location.pathname);
  BootstrapDialog.closeAll();
}

function devices_edit(json){
  success("Device Updated");
  g(location.pathname);
  BootstrapDialog.closeAll();
}

function admin_track(json){
  success("Loading Device");
}

function loadfeed(callback){
  $.ajax({
    method: 'post',
    url: '/i/'+$('body').attr('class'),
    data: { 
      limit_ts: $('.timeline').attr('id'),
      segments: segments()
    },
    cache: false,
    timeout: 10000,
    success:function(json){
      if($(json.feed).length){
        $.each(json.feed.reverse(), function(i, item) {
          feed(item);
        });
        $('.timeline').attr('id',json.feed[$(json.feed).length-1].created_ts);
      }
      $('.online').html('');  
      if($(json.online).length){
        $.each(json.online.reverse(), function(i, item) {
          var online = (item.status == 'online');

          $('.online').append('<a class="list-group-item" href="/connect/' + item.username + '"><i class="ion-' + (online ? 'flash' : 'flash-off' ) + '"></i> ' + item.title + '<span onclick="javascript:g(\'/connect/' + item.username + '\');"class="badge">' +item.unread + '</span> <span href="#compose" role="button" data-toggle="modal" data-open="$(\'textarea\').val(\'@' + item.username + ' \').focus();" class="badge bg-' + (online ? 'success2' : 'disabled2' ) + '"><i class="ion-chatbubble"></i></span></a>');
        });
      }
      if(typeof callback == 'function'){
        setTimeout(function(){
          callback.call(this);  
        },100);
      }
    }
  });
}

function feed(entry){
  var feed = '<p class="list-group-item"><a href="/account/' + entry.username + '">' + entry.user + '</a> <a href="/posts/' + entry.id + '"><small> ' + entry.timespan + '</small></a> <i class="' + _p(entry.privacy_id) + '"></i><br>' + entry.status + '</p>';
  if($('.timeline').find('p').first().length){
    $('.timeline').find('p').first().before(feed);
  } else {
    $('.timeline').html(feed);
  }
}

function show_cookie_tools(){
  setTimeout(function(){
    $(".cookietool-settings").each(function(){
      CookieTool.API.displaySettings(this);
    });
  },300);
}