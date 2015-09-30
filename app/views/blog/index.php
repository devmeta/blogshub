<div class="col-md-9">
    <div>
        <ul class="ch-grid">
        <?php foreach($posts as $post):?>
            <li>    
                <div class="ch-item" style="background-image: url(/upload/posts/th-<?php echo count($post->files()) ? $post->files()[0]->name : 'default';?>)">
                    <div class="ch-title">
                        <h3><?php echo $post->title;?></h3>
                    </div>
                    <div class="ch-caption">
                        <h5>
                            <i class="ion-android-time"></i> <?php echo timespan($post->created);?>
                            <i class="ion-eye"></i> <?php echo $post->hits;?>
                            <i class="ion-chatbubbles"></i> <span class="count-comments" data-disqus-url="http://<?php echo config('blog')->data->username;?>.devmeta.net/<?php echo $post->slug;?>">0</span> 
                        </h5>
                    <?php if($post->caption):?>
                        <h3><?php echo \Bootie\Str::words($post->caption,20);?></h3>
                    <?php endif;?>
                        <p>
                            <a href="/<?php echo $post->slug;?>" class="btn btn-sm btn-success"><i class="ion-ios-glasses"></i> Read</a>
                        </p>
                    </div>
                </div>
            </li>
		<?php endforeach;?>
        </ul>
    </div>
</div>
<div class="col-md-3">
    <?php include SP . 'app/views/shared/sidebar.php';?>
</div>