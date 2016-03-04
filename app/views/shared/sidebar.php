    <h3><?php echo config('blog')->data->title;?></h3>
    <blockquote><em><?php echo config('blog')->data->caption;?></em></blockquote>
<?php if(file_exists( __DIR__ . '/../../../../blogs/public/upload/profile/ty-' . config('blog')->data->avatar )):?>
	<a href="<?php echo config()->baseurl . '/account/' . config('blog')->data->username;?>">
	    <img class="img-circle" src="<?php echo config()->baseurl . '/upload/profile/ty-' . config('blog')->data->avatar;?>">
    </a>
<?php endif;?>
    <hr>
<?php if(isset($tags) AND count($tags)):?>
    <div class="">
        <h3>Temas</h3>
    <?php foreach($tags as $tag2):?>
        <a href="/tag/<?php echo $tag2->tag;?>" class="label label-<?php echo isset($tag) && $tag == $tag2->tag ? 'info' : 'success';?> label-badge btn-tag-included"><?php echo str_replace('-',' ',$tag2->tag);?></a>
    <?php endforeach;?>
    </div>
<?php endif;?>
    
    <div class="clearfix"></div>
    <div class="group-control">&nbsp;</div>
    <a href="http://blogs.devmeta.net/" target="_blank"><div style="background:url(http://blogs.devmeta.net/upload/posts/th-fist-of-power-e1366397947671_rs.jpg) no-repeat;width:225px;height:225px;padding:25px;text-align:center;-webkit-filter: saturate(7);filter: saturate(7);"><ul style="color:#333;text-shadow:0 0 1px #fff;font-weight:bold;background-color:rgba(250,250,250,0.9);text-align:left;padding:30px;margin-bottom:10px;"><li>Gratis</li><li>Sin publicidad</li></ul><label class="btn btn-lg btn-danger"> Registra tu Blog!</label></div></a>
       
