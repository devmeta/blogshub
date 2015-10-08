<nav class="navbar navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-md">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="/" class="navbar-brand">
				<?php echo config('blog')->data->title;?> <em><?php echo config('blog')->data->caption;?></em>
			</a>
		</div>
	<?php if( config('blog')->data ):?>
		<div class="collapse navbar-collapse" id="navbar-md">
	        <div class="pull-right search">
	          <i class='ion-search'></i>
	          <input type="text" class="form-control" placeholder="Buscar" /> &nbsp;&nbsp;
	        </div>
		</div>
    <?php endif;?>
	</div>
</nav>
