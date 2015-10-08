<div class="col-md-9">
  	<h1 class="page-header"><?php echo $entry->title;?></h1>
    <blockquote><em><?php echo $entry->caption;?><br><?php echo date('Y M d',$entry->created);?></em></blockquote>
    <div class="alert">
    <?php foreach($entry->tags() as $tag) : if( ! isset($tag->tag)) continue;?>
        <a href="/tag/<?php echo $tag->tag;?>" class="label label-success label-badge btn-tag-included"><?php echo $tag->tag;?></a>
    <?php endforeach;?>
    </div> 
    <div class="slick-dotted">
    <?php foreach($entry->files() as $file) : if( ! isset($file->name)) continue;?>
        <div class="image">
            <img class="img-responsive" src="/upload/posts/sd-<?php echo $file->name;?>">
        </div>
    <?php endforeach;?>
    </div>
    <?php echo str_replace("/upload/",config('blog')->baseurl . "/upload/",$entry->content);?>
    <hr>


    <script type="text/javascript">
    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    var disqus_shortname = 'devmetablogs'; // required: replace example with your forum shortname

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = '//' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());

    <?php if(config('blog')->data->disqus OR $entry->disqus):?>
    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
    <?php endif;?>    
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript> 
</div>
<div class="col-md-3">
    <ul class="ch-grid">
    <?php foreach($related as $post):?>
        <li>    
            <div class="ch-item" style="background-image: url(<?php echo config()->baseurl;?>/upload/posts/th-<?php echo count($post->files()) ? $post->files()[0]->name : 'default';?>)">
                <a href="/<?php echo $post->slug;?>">
                    <div class="ch-title">
                        <h5>
                            <i class="ion-android-time"></i> <?php echo date('M d', $post->created);?>
                            <i class="ion-eye"></i> <?php echo $post->hits;?>
                            <i class="ion-chatbubbles"></i> <span class="disqus-comment-count" data-disqus-url="http://<?php echo config('blog')->data->username;?>.devmeta.net/<?php echo $post->slug;?>">0</span> 
                        </h5>                 
                        <h3><?php echo $post->title;?></h3>
                    </div>
                    <div class="ch-caption">
                       <h3><?php echo $post->caption;?></h3>
                    </div>
                </a>
            </div>
        </li>
    <?php endforeach;?>
    </ul>
</div>