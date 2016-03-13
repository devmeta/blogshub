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
				<i class='<?php echo config('blog')->data->icon ?: 'ion-speakerphone';?> x2 pull-left'></i>
				<strong><?php echo config('blog')->data->title;?></strong>
				<em><?php echo config('blog')->data->caption;?></em>
			</a>
		</div>
	<?php if( config('blog')->data ):?>
	        <div class="pull-right search">
	        	<a id="opensearch" href="javascript:void(0)">
	          		<i class='ion-ios-search-strong'></i>
	          	</a>
	        </div>
    <?php endif;?>
	</div>
</nav>

<div class="search-results text-center hide">
	<a id="closesearch" href="javascript:void(0)">
		<i class='ion-close'></i>		
	</a>
    <div class="search">
      <i class='ion-ios-search-strong'></i>
      <input type="text" class="form-control input-lg" placeholder="Buscar" /> &nbsp;&nbsp;
    </div>
	<div class="search-posts"></div>
</div>