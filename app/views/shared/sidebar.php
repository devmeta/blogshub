    <h3><?php echo config('blog')->data->title;?></h3>
    <blockquote><em><?php echo config('blog')->data->caption;?></em></blockquote>
    <hr>
<?php if(isset($tags) AND count($tags)):?>
    <div class="">
        <h3>Temas</h3>
    <?php foreach($tags as $tag2):?>
        <a href="/tag/<?php echo $tag2->tag;?>" class="label label-<?php echo isset($tag) && $tag == $tag2->tag ? 'info' : 'success';?> label-badge btn-tag-included"><?php echo str_replace('-',' ',$tag2->tag);?></a>
    <?php endforeach;?>
    </div>
<?php endif;?>
