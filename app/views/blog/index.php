<div class="col-md-9">
    <?php if(count($posts)):?>
    <div class="row">
        <div class="feature-list">
        <?php foreach($posts as $post):?>
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <a href="<?php echo site_url($post->slug);?>">
                    <img src="<?php echo config()->baseurl;?>/upload/posts/sd-<?php echo count($post->files()) ? $post->files()[0]->name : 'default.jpg';?>" />
                    <div class="feature-title">
                        <h3><?php echo words($post->title,15);?></h3>
                        <p><?php echo words($post->caption,18);?></p>
                    </div>
                </a>
                <div class="feature-attr">
                    <i class="ion-android-time"></i> <?php echo timespan($post->created);?>
                    <i class="ion-eye"></i> <?php echo $post->hits;?>
                <?php if(config('blog')->data->disqus OR $post->disqus):?>
                    <i class="ion-chatbubbles"></i> <span class="disqus-comment-count" data-disqus-url="<?php echo site_url($post->slug);?>">0</span> 
                <?php endif;?>
                </div>
            </div>
        <?php endforeach;?>
        </div><div class="clearfix"></div>
        <?php $posts[0]->paginator();?>
    </div>
    <?php endif;?>
</div>
<div class="col-md-3">
    <?php include SP . 'app/views/shared/sidebar.php';?>
</div>
