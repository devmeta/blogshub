<div class="col-md-9">
  	<h1 class="page-header"><?php echo $entry->title;?></h1>
    <blockquote><em><?php echo $entry->caption;?><br><?php echo date('d M Y',$entry->created);?></em></blockquote>

    <div class="social">
        <a class="facebook" href="https://www.facebook.com/sharer.php?u=<?php echo PATH;?>&t=<?php print $entry->title;?>" title="Facebook" data-external="true" data-placement="top" class="pop-link"><i class="ion-social-facebook"></i><span class="badge social-count fbcnt"></span></a>
        <a class="twitter" href="https://twitter.com/intent/tweet?text=<?php print $entry->title;?> <?php echo PATH;?>" title="Twitter" data-external="true" data-placement="top" class="pop-link"><i class="ion-social-twitter"></i><span class="badge social-count twcnt"></span></a>
        <a class="googleplus" href="https://plus.google.com/share?url=<?php echo PATH;?>" title="Google+" data-external="true" data-placement="top" class="pop-link"><i class="ion-social-googleplus"></i></a>
        <a class="linkedin" href="https://www.linkedin.com/cws/share?url=<?php echo PATH;?>" title="Linkedin" data-external="true" data-placement="top" class="pop-link"><i class="ion-social-linkedin"></i></a>
        <a class="pinterest" href="http://pinterest.com/pin/create/link/?url=<?php echo PATH;?>&media=&description=<?php print $entry->title;?>" title="Pinterest" data-external="true" data-placement="top" class="pop-link"><i class="ion-social-pinterest"></i></a>
    </div>    
        
<?php if($entry->tags()):?>
    <div class="alert">
    <?php foreach($entry->tags() as $tag) : if( ! isset($tag->tag)) continue;?>
        <a href="/tag/<?php echo $tag->tag;?>" class="label label-success label-badge btn-tag-included"><?php echo $tag->tag;?></a>
    <?php endforeach;?>
    </div> 
<?php endif;?>
    <div class="slick-dotted">
    <?php foreach($entry->files() as $file) : if( ! isset($file->name)) continue;?>
        <div class="image">
            <img class="img-responsive" src="<?php echo config()->baseurl;?>/upload/posts/sd-<?php echo $file->name;?>">
        </div>
    <?php endforeach;?>
    </div>
    <div class="entry-content">
        <?php echo str_replace("/upload/",config()->baseurl . "/upload/",$entry->content);?>
    </div>

    <div class="social">
        <a class="facebook" href="https://www.facebook.com/sharer.php?u=<?php echo PATH;?>&t=<?php print $entry->title;?>" title="Facebook" data-external="true" data-placement="top" class="pop-link"><i class="ion-social-facebook"></i><span class="badge social-count fbcnt"></span></a>
        <a class="twitter" href="https://twitter.com/intent/tweet?text=<?php print $entry->title;?> <?php echo PATH;?>" title="Twitter" data-external="true" data-placement="top" class="pop-link"><i class="ion-social-twitter"></i><span class="badge social-count twcnt"></span></a>
        <a class="googleplus" href="https://plus.google.com/share?url=<?php echo PATH;?>" title="Google+" data-external="true" data-placement="top" class="pop-link"><i class="ion-social-googleplus"></i></a>
        <a class="linkedin" href="https://www.linkedin.com/cws/share?url=<?php echo PATH;?>" title="Linkedin" data-external="true" data-placement="top" class="pop-link"><i class="ion-social-linkedin"></i></a>
        <a class="pinterest" href="http://pinterest.com/pin/create/link/?url=<?php echo PATH;?>&media=&description=<?php print $entry->title;?>" title="Pinterest" data-external="true" data-placement="top" class="pop-link"><i class="ion-social-pinterest"></i></a>
    </div>    
    <hr>
<?php if(getenv('REMOTE_ADDR') != '127.0.0.2'):?>
<?php if(config('blog')->data->disqus OR $entry->disqus):?>
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES * * */
        var disqus_shortname = 'devmetablogs';
        
        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
<?php endif;?>
<?php endif;?>
</div>
<div class="col-md-3">
    <?php include SP . 'app/views/shared/sidebar.php';?>
    <ul class="ch-grid">
    <?php foreach($related as $post):?>
        <li>    
            <div class="ch-item" style="background-image: url(<?php echo config()->baseurl;?>/upload/posts/th-<?php echo count($post->files()) ? $post->files()[0]->name : 'default';?>)">
                <a href="/<?php echo $post->slug;?>">
                    <div class="ch-title">
                        <h5>
                            <i class="ion-android-time"></i> <?php echo date('M d', $post->created);?>
                            <i class="ion-eye"></i> <?php echo $post->hits;?>
                        <?php if(config('blog')->data->disqus OR $post->disqus):?>
                            <i class="ion-chatbubbles"></i> <span class="disqus-comment-count" data-disqus-url="http://<?php echo config('blog')->data->username;?>.devmeta.net/<?php echo $post->slug;?>">0</span> 
                        <?php endif;?>
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