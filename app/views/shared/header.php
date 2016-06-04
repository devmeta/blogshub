<nav class="navbar navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header pull-left">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-md">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="/" class="navbar-brand">
				<span class='<?php echo config('blog')->data->icon ?: 'typcn typcn-microphone';?> x2 pull-left'></span>
				<strong><?php echo config('blog')->data->title;?></strong>
				<em><?php echo config('blog')->data->caption;?></em>
			</a>
		</div>
	<?php if( config('blog')->data ):?>
	        <div class="pull-right search">
	        	<a id="opensearch" href="javascript:void(0)">
	          		<span class='typcn typcn-zoom-in'></span>
	          	</a>
	        </div>
    <?php endif;?>
	</div>
</nav>

<div class="search-results text-center hide">
	<a id="closesearch" href="javascript:void(0)">
		<span class='typcn typcn-times'></span>
	</a>
    <div class="search">
      <span class='typcn typcn-zoom-in'></span>
      <input type="text" class="form-control input-lg" placeholder="<?php print locale('search');?>" />
    </div>
	<div class="search-posts"></div>
</div>