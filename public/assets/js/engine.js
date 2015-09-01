/*!
 * Engine js v0.3 (http://caldero.devmeta.net)
 * Copyright 2011-2015 Devemta, Inc.
 * Licensed under GNU GENERAL PUBLIC LICENSE (https://github.com/devmeta/caldero/master/LICENSE.md)
 */

(function($){

    var Engine = {
        defaults : {
            startpage : "home",
            loaderbar : true,
            transition : "fade",
            index : 0
        },
        init : function(options){
            Engine.options  = $.extend(true, {}, Engine.defaults, options);
            Engine.load(location.pathname);

            $(document).on('click','a',function(e){

                if($(this).data('close-modal')){
                    BootstrapDialog.closeAll();
                }

                if($(this).attr('href')=="#" || $(this).data('toggle')=="lightbox" || $(this).data('toggle')=="modal" || $(this).parent().hasClass('selector')){
                    return false;
                }
                
                if($(this).attr("target")=="_blank"||$(this).data('external')){
                    return true;
                }
                
                e.preventDefault();
                Engine.link(this,e);
            });

            window.onpopstate = function(event) {
                Engine.load(location.pathname);
            }; 

        },
        nav : function(url){

            var pathArray = url.split( '/' );
            var path = pathArray[1];

            $(".navbar li.active").removeClass("active");  

            if($('a[href="/'+path+'"]').length){
                $('a[href="/'+path+'"]').parent().addClass("active");
            }
        },
        link : function(a,e){

            if($(a).attr("target")=="_blank"||$(a).attr('href').indexOf('javascript')!=-1||$(a).data('external')){
                return true;
            }

            href = $(a).attr("href");
            Engine.load(href);
            try {
                history.pushState('', 'New URL: '+href, href);
            } catch(e) {
                
            }

            e.preventDefault();

            return false;
        },
        load : function(url){

            var arr = url.split('?');
            var url = $.inArray(arr[0],["/",""])>-1 ? Engine.options.startpage : arr[0] ;
            //var search = $.parseJSON(arr[1]);
            var search="";

            if(arr[1] && arr[1].length){
                search = arr[1];
            }

            if(url.substr(0,1)!='/'){
                url = '/'+url;
            }
            
            $('body')
                .removeClass()
                .addClass("loading");

            $('.tooltip').remove();

            $container = $(".content");
            $container.fadeTo("fast", 0.1,function(){
                $.ajax({
                    type:       "post",
                    cache:      false, 
                    url:        url
                }).done(function(json){
                    var display="";

                    if(json.error){
                        alert(json.error);
                    }

                    if(json.view){
                        display = json.view;
                    } else {
                        var tmplurl = url.split("/");
                        tmplurl = tmplurl.filter(function(n){ return n != "" }); 
                        //display = tmplurl[0].split('-').join('/');
                        display = tmplurl.join('/');
                    }

                    //console.log(display);
                    
                    var callurl = '/views/' + display + '.html'
                    
                    //console.log("view: " + callurl);

                    $.ajax({
                        url:        callurl,
                        dataType:   'html',
                        cache:      false, 
                        type:       'GET',
                    }).done(function(markup) {

                        $('body').addClass(display);
                            
                        setTimeout(function(){

                            $container.empty();
                            $.tmpl( markup, json )
                                .appendTo( $container );

                            $('body')
                                .trigger("display")
                                .trigger(display);

                            $container.fadeTo("fast", 1, function(){
                                $('body,html').animate({scrollTop: 0}, 100);
                            });
                            Engine.nav(url);
                            $('body').removeClass("loading");

                        },100)

                    }).error(function(html) {
                        $container.html(html);
                    });
                });
            }); 
        }
    };

    $.fn.engine = function( method,b ) {
        $container = $(this);
        if ( Engine[method] ) {
            return Engine[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return Engine.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.Engine!' );
        }    
    };        
}(jQuery));