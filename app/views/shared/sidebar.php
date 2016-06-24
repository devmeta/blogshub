<?php if(isset($tags) AND count($tags)):?>
    <div class="">
    <h3><?php print locale('tags');?></h3>
<?php if(!empty($tags['info'])):?>
    <?php foreach($tags['info'] as $tag):?>
        <a href="/tag/<?php echo $tag->tag;?>" class="label label-info label-badge btn-tag-included"><?php echo str_replace('-',' ',$tag->tag);?></a>
    <?php endforeach;?>
<?php endif;?>
<?php if(!empty($tags['success'])):?>
    <?php foreach($tags['success'] as $tag):?>
        <a href="/tag/<?php echo $tag->tag;?>" class="label label-success label-badge btn-tag-included"><?php echo str_replace('-',' ',$tag->tag);?></a>
    <?php endforeach;?>
    </div>
<?php endif;?>
<?php endif;?>
    <hr>

    <h3><?php echo config('blog')->data->title;?></h3>
    <blockquote><em><?php echo config('blog')->data->caption;?></em></blockquote>
<?php if(file_exists( __DIR__ . '/../../../../blogs/public/upload/profile/ty-' . config('blog')->data->avatar )):?>
	<a href="<?php echo config()->baseurl . '/account/' . config('blog')->data->username;?>">
	    <img class="img-circle" src="<?php echo config()->baseurl . '/upload/profile/ty-' . config('blog')->data->avatar;?>">
    </a>
<?php endif;?>
