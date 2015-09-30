<div class="col-md-9">
    <blockquote>
        <em><i class='ion-pound'></i> <?php echo $tag;?></em>
    </blockquote>
    <ul class="ch-grid">
    <?php foreach($posts as $post):?>
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
                        <a href="/<?php echo $post->slug;?>" class="btn btn-sm btn-success"><i class="ion-ios-glasses"></i> Read</a>
                    </p>
                </div>
            </div>
        </li>
	<?php endforeach;?>
    </ul>
</div>
<div class="col-md-3">
    <?php include SP . 'app/views/shared/sidebar.php';?>
</div>