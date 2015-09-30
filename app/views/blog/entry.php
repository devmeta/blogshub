<div class="col-md-9">
  	<h1 class="page-header"><?php echo $entry->title;?></h1>
    <blockquote><em><?php echo $entry->caption;?><br><?php echo date('Y M d',$entry->created);?></em></blockquote>
    <div class="">
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
<?php if($entry->disqus):?>
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
</div>
<div class="col-md-3">
    <ul class="ch-grid">
    <?php foreach($related as $post):?>
        <li>    
            <div class="ch-item" style="background-image: url(/upload/posts/th-<?php echo count($post->files()) ? $post->files()[0]->name : 'default';?>)">
                <div class="ch-title">
                    <h3><?php echo $post->title;?></h3>
                </div>
                <div class="ch-caption">
                    <h5>
                        <i class="ion-android-time"></i> <?php echo date('M d', $post->created);?>
                    </h5>                 
                    <h3><?php echo $post->caption;?></h3>
                    <p>
                        <a href="/blog/<?php echo $post->slug;?>" class="btn btn-sm btn-success"><i class="ion-ios-glasses"></i> Read</a>
                    </p>
                </div>
            </div>
        </li>    
    <?php endforeach;?>
    </ul>
</div>