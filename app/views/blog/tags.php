<div class="col-md-9">
    <blockquote>
        <em><i class='ion-pound'></i> <?php echo $tag;?></em>
    </blockquote>
    <?php if(count($posts)):?>
    <div class="row">
        <div class="feature-list">
        <?php foreach($posts as $post):?>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                <a href="<?php echo site_url($post->slug);?>">
                    <img src="<?php echo config()->baseurl;?>/upload/posts/sd-<?php echo count($post->files()) ? $post->files()[0]->name : 'default.jpg';?>" />
                    <h3 class="feature-title"><?php echo words($post->title,15);?></h3>
                    <span class="feature-caption hide"><?php echo words($post->caption,18);?></span>
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
        </div>
        <?php $posts[0]->paginator();?>
    </div>
    <?php endif;?>
</div>
<div class="col-md-3">
    <?php include SP . 'app/views/shared/sidebar.php';?>
</div>