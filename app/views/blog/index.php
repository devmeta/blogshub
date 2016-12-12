<div class="col-md-9">
    <div class="row">
        <div class="feature-list">
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="col-md-3">
    <?php include SP . 'app/views/shared/sidebar.php';?>
</div>

<script id="fetch" type="text/x-jsrender">
    <div class="col-xs-12 col-sm-6 col-lg-4">
        <a href="{{:url}}">
            <img title="{{:title}}" src="{{:image}}" />
            <div class="feature-title">
                <h3>{{if type}}{{:type}}{{/if}} {{:title}}</h3>
                <p>{{:caption}}</p>
            </div>
        </a>
        <div class="feature-attr">
            <i class="ion-android-time"></i> {{:created}}
            <i class="ion-eye"></i> {{:hits}}
        {{if discus}}
            <i class="ion-chatbubbles"></i> <span class="disqus-comment-count" data-disqus-url="{{:url}}">0</span> 
        {{/if}}
        </div>
    </div>
</script>

<script type="text/javascript">
    
    window.prid = '<?php print config('blog')->data->id;?>';

    $(function(){   

        var qtd=9;
        var lastPage = 1;

        function fetch(page){

            if (typeof page == 'undefined'){
                page=1;
            } else {
                if (page && lastPage==page){
                    return false;
                }
            }

            lastPage = page;

            if(page==1)
                $('.feature-list').html('');

            $('.loading').show();

            $.post('/fetch',{id:window.prid,page:page},function(json){
                $('.feature-list').append($.templates("#fetch").render(json.entries)).promise().done(function(){
                    $('.loading').hide();
                });
            });
        }

        function infinite_scroll() {
            var position = Math.ceil($('.feature-list').children().length/qtd)+1;
            fetch(position);
        }

        $(window).scroll(function() {
           if($(window).scrollTop() + $(window).height() + 200 > ($(document).height())) {
                infinite_scroll();
           }
        });

        setTimeout(function(){
            fetch();
        },400);

    });
</script>

