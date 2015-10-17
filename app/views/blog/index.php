<div class="col-md-9">
    <?php if(count($posts)):?>
    <div class="scroll-content row">
        <div class="feature-list clearfix">
            <ul>
            <?php foreach($posts as $i => $post):?>
                <li>
                    <a href="<?php echo site_url($post->slug);?>" class="feature-olivia col-md-4 col-sm-6 col-xs-6" style="background-image: url(<?php echo config()->baseurl;?>/upload/posts/th-<?php echo count($post->files()) ? $post->files()[0]->name : 'default';?>)">
                        <div class="foam<?php echo $i%6+1;?> overlay"></div>
                        <span class="feature-title"><?php echo words($post->title,10);?></span>
                        <span class="feature-descrip"><?php echo words($post->caption,20);?></span>
                        <span class="feature-attr">
                            <i class="ion-android-time"></i> <?php echo timespan($post->created);?>
                            <i class="ion-eye"></i> <?php echo $post->hits;?>
                        <?php if(config('blog')->data->disqus OR $post->disqus):?>
                            <i class="ion-chatbubbles"></i> <span class="disqus-comment-count" data-disqus-url="<?php echo site_url($post->slug);?>">0</span> 
                        <?php endif;?>
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
