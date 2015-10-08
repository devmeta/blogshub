<div class="col-md-9">
    <?php if(count($posts)):?>
    <div class="scroll-content row">
        <div class="feature-list clearfix">
            <ul>
            <?php foreach($posts as $i => $post):?>
                <li>
                    <a href="/<?php echo $post->slug;?>" class="feature-olivia col-md-4 col-sm-6 col-xs-6" style="background-image: url(/upload/posts/thumb/<?php echo count($post->files()) ? $post->files()[0]->name : 'default';?>)">
                        <div class="foam<?php echo $i%6+1;?> overlay"></div>
                        <span class="feature-title"><?php echo $post->title;?></span>
                        <span class="feature-descrip"><?php echo words($post->caption,15);?></span>
                        <span class="feature-attr">
                            <i class="ion-android-time"></i> <?php echo timespan($post->created);?>
                            <i class="ion-eye"></i> <?php echo $post->hits;?>
                            <i class="ion-chatbubbles"></i> <span class="disqus-comment-count" data-disqus-url="http://<?php echo config('blog')->data->username;?>.devmeta.net/<?php echo $post->slug;?>">0</span> 
                        </span>
                    </a>
                </li>
            <?php endforeach;?>
            </ul>
        </div>
        <?php $posts[0]->paginator();?>
    </div>
    <?php endif;?>
</div>
<div class="col-md-3">
    <?php include SP . 'app/views/shared/sidebar.php';?>
</div>

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
</script> 