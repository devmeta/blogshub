
/* 3. General UI, Controls  & Modals */

var fdata=[];

function utable(json,options){
  var entries = json.entries;
  var str ='';
  $('#'+options.slug).find('table tbody tr').remove();
  for(var i in entries){
    str+='<tr>';
    $('#'+options.slug).find('table thead tr').first().children().each(function(j,item){
      str+='<td>' + entries[i][$(item).data('col')] + '</td>';
    });
    str+='</tr>';
  }
  $('#'+options.slug).find('table tbody').html(str).hide().fadeIn(200);
}

function paginate(p,options){
  $.ajax({
    type: "POST",
    url: options.url,
    data : {id:options.id,p:p},
    success: function (json){ 
      var func = eval(options.handler);
      if(options.handler && typeof func=='function'){
        window[options.handler].call(this,json,options);
      }
    }           
  });    
}

$(document).on('click','.autopage .prev',function(e){
  e.preventDefault();
  var current = $(this).parent().parent().find('.active .current-page').text();
  var options = $(this).parent().parent().data("post");
  current--;
  var p = 1;
  if(current > p){
    p = current;
  }
  paginate(p,options);
  $(this).parent().parent().find('.active .current-page').text(p);
});

$(document).on('click','.autopage .next',function(e){
  e.preventDefault();
  var current = $(this).parent().parent().find('.active .current-page').text();
  var total = $(this).parent().parent().find('.active .total-page').text();
  var options = $(this).parent().parent().data("post");
  current++;
  var p = total;
  if(current < p){
    p = current;
  }
  paginate(p,options);
  $(this).parent().parent().find('.active .current-page').text(p);
});


$(document).on('click','.selector a',function(e){
  e.preventDefault();
  $this = $(this);

  $this.parent().find('a').removeClass('active');

  $this.parent().parent().parent().parent().find('.selector-pane').children().each(function(){
    $(this).fadeOut(100);
  });

  setTimeout(function(){
    $this.parent().parent().parent().parent().find('.selector-pane').find('.'+$this.attr('href')).removeClass('hide').hide().fadeIn(100);
    $this.addClass('active');
  },200);
  

  return false;
});

$(document).on('submit','.form',function(){

  $(this).find('button').addClass('disabled');

  var arr = $(this).attr("action") ? $(this).attr("action").split('/') : $(this).data("action").split('/') ;
  arr = $.grep(arr, function(n){ return (n); });
  var callback = arr.join('_');

  if( $(this).attr("action") ){
    $('body').addClass("loading");
    $.ajax({
      type:'post',
      url:$(this).attr('action'),
      data:$(this).serialize(),
      success:function(json){
        if(typeof window[callback] == 'function') {
          window[callback].call(this,json);
        }
        $('body').removeClass("loading");
      }
    });

    return false;
  } 

  if(typeof window[callback] == 'function') {
    window[callback].call(this,null);
  }

  return false;
});


/* link to js engine functions functions */

function isEmpty(text) {
  return text.length<1;
}

function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

function g(url){
  var link = document.createElement('a');
  link.href = url;
  document.body.appendChild(link);
  link.click();
  
  return false;
}


/* Enlaza un campo con un controlador en evento keyup */

function kupjsn(options){
  $(options.target)
    .keyup(function(){
      clearTimeout($.data(this, 'timer'));
      $this = $(this);
      $.data(this, 'timer', setTimeout(function() {
        var data = {};
        data[$this.attr('name')] = $this.val();

        $.ajax({
          method: 'post',
          url: '/status' + options.url,
          data: data,
          success:function(json){
            options.success.call(this,json);
          }
        });
      }, 450));
      return false;
    });

  if(options.focus){
    $(options.target).focus();
  }
}

function kupfn(options){
  $(options.target)
    .keyup(function(){
      clearTimeout($.data(this, 'timer'));
      $this = $(this);
      $.data(this, 'timer', setTimeout(function() {
        options.success.call(this,options);
      }, 450));
      return false;
    });

  if(options.focus){
    $(options.target).focus();
  }
}

function cf(a){
  if(fdata.length){
    var frm2 = $(a);

    for(d in fdata){
      frm2.find('* [name="' + fdata[d]['name'] + '"]').val( fdata[d]['value'] );
    }
  }
}

$(document).on("click",'*[data-toggle="lightbox"]',function(e){  
  e.preventDefault();  
  $(this).ekkoLightbox();
});

$(document).on("click",'*[data-toggle="modal"]',function(e){
  e.preventDefault();

  $('body').addClass("loading");
  var display = $(this).attr("href").replace("#","");

  if(display.indexOf('-lang')>-1){
    display = display.replace("-lang",$('html').attr('lang'));
  }

  display = display.split('_').join('/');
  var onopen = $(this).data("open")||null;
  var onclose = $(this).data("close")||null;
  var url = '/views/modals/' + display + '.html';
  var size = $(this).data("size")||"size-normal";
  
  $.ajax({
      type:       "post",
      cache:      false, 
      url:        display,
      data:       $(this).data("post") 
  }).done(function(json){
    $.ajax({
        url:        url,
        dataType:   'html',
        cache:      false, 
        type:       'GET',
    }).done(function(markup) {

      var message = $.tmpl( markup, json );
      $(message).find('h2').remove();

      var options = {
        message: message,
        size: size,
        onshow: function(dialog) {
          var title = dialog.getModalContent().find('.modal-title').html();
          dialog.getModalContent().find('.modal-title').remove();
          dialog.setTitle(title);

        },
        onshown: function(){
          setTimeout(function(){
            setup_controls();
          },100);
          if( typeof eval(onopen) == "function" ){
            eval(onopen + '()');
          }
          // update modal boundaries because of xhr updates on html tag
          if($(".modal-dialog").height() > $(".modal-backdrop").height()){
            $(".modal-backdrop").height($(".modal-dialog").height()+100);
          }

          $('body').removeClass("loading");
        }
      };

      if(onclose){
        options['onhidden'] = function() {
          if( typeof eval(onclose) == "function" ){
            eval(onclose + '()');
          }
        }
      }

      BootstrapDialog.show(options);
    });
  });

  return false;
});

function sendFile(file, editor, welEditable) {
    data = new FormData();
    data.append("file", file);
    $('.summernote-progress').removeClass('hide').hide().fadeIn();
    $.ajax({
        data: data,
        type: "POST",
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) myXhr.upload.addEventListener('progress',progressHandlingFunction, false);
            return myXhr;
        },        
        url: "/api/upload",
        cache: false,
        contentType: false,
        processData: false,
        success: function(url) {
            $('.summernote-progress').fadeOut();
            editor.insertImage(welEditable, url);
        }
    });
}   

function progressHandlingFunction(e){
    if(e.lengthComputable){
        var perc = Math.floor((e.loaded/e.total)*100);
        $('.progress-bar').attr({"aria-valuenow":perc}).width(perc+'%');
        // reset progress on complete
        if (e.loaded == e.total) {
            $('.progress-bar').attr('aria-valuenow','0.0');
        }
    }
}

function setup_controls(){
  /*if($('.bootstrap-switch').length){
    $('.bootstrap-switch').bootstrapSwitch();
  }*/
  if($('.summernote').length){
    $('.summernote').summernote({
      height: 300,   
      minHeight: null,
      maxHeight: null,
      //airMode: true,
      onImageUpload: function(files, editor, welEditable) {
        sendFile(files[0], editor, welEditable);
      }
    });
  }

  if( $('.slick').length ){
    $('.slick').slick({
      dots: false,
      infinite: true,
      speed: 500,
      autoplaySpeed: 3000,
      autoplay:true,
      arrows: false,
      fade: true
    });
  }

  if( $('.slick-dotted').length ){
    $('.slick-dotted').slick({
      dots: true,
      infinite: true,
      speed: 500,
      autoplaySpeed: 3000,
      autoplay:true,
      fade: true
    });
  }
  if($('.chosen').length){
    $('.chosen').each(function(){
      var width = "100%";
      if($(this).data('width')){
        width = $(this).data('width')+'px';
      }
      $(this).chosen({width:width}); 
    });
  }  
  if($('.chosen').length){
    $('.chosen').chosen(); 
  }
  if($('.datetimepicker').length){
    $('.datetimepicker').datetimepicker();
  }
  $("a,span").tooltip({container: 'body'});
  Rainbow.color();
}

function alerttmpl(display,title,callback){
  $.ajax({
      url:        '/views/modals/' + display + '.html',
      dataType:   'html',
      cache:      false, 
      type:       'GET',
  }).done(function(markup) {

    var message = $.tmpl( markup);
    $(message).find('h2').remove();

    BootstrapDialog.alert({
      message: message,
      onshow: function(dialog) {
        var title = dialog.getModalContent().find('.modal-title').html();
        dialog.getModalContent().find('.modal-title').remove();
        dialog.setTitle(title);
      },
      onshown: function(){
        if( typeof eval(callback) == "function" ){
          eval(callback + '()');
        }
      }
    });
  });
}

function segments(){
  pathname = location.pathname.split('/');
  pathname = pathname.filter(function(n){ return n != "" }); 
  return pathname;
}

function warn(message){
  $('.notifications').notify({
    message: { html: '<i class="ion-android-warning x1"></i> <span>&nbsp;&nbsp;' + _l(message) + '&nbsp;&nbsp;</span>' },
    type : 'warning'
  }).show();
}

function error(message){
  $('.notifications').notify({
    message: { html: '<i class="ion-android-cancel x1"></i> <span>&nbsp;&nbsp;' + _l(message) + '&nbsp;&nbsp;</span>' },
    type : 'danger'
  }).show();
}

function success(message){
  $('.notifications').notify({
    message: { html: '<i class="ion-checkmark-circled x1"></i> <span>&nbsp;&nbsp;' + _l(message) + '&nbsp;&nbsp;</span>' },
    type : 'success'
  }).show();
}

function notify(message,settings){
  var type = 'success';
  if(settings){
    if(settings.msgType){
      type = settings.msgType;
    }
  }

  $('.notifications').notify({
    message: { html: _l(message) + '&nbsp;&nbsp;' },
    type : type
  }).show();
}

function hasWhiteSpace(s) {
  return s.indexOf(' ') >= 0;
}

function _l(w){
  if(app && app.lang != null){
    if(!hasWhiteSpace(w)){
      if(app.lang[w]!=undefined){
        return app.lang[w];
      } else {
        return w;
      }
    } else {
      var ps = w.split(' ');
      var rs = '';
      for(var i in ps){
        rs+=_l(ps[i])+' ';
      }
      return rs;
    }
  } else {
    return w;
  }
}

function _j(w){
  if(app && app.sess != null && app.sess[w]){
    return app.sess[w];
  } else {
    return w;
  }
}

function _p(i){
  if(app && app.sess.privacy_icons[i-1]){
    return app.sess.privacy_icons[i-1].icon;
  } else {
    return i;
  }
}