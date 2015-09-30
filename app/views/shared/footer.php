<footer>
	<div class="pull-right">
		<span class="label label-info label-badge" title="Memory usage (process)">
			<?php print number_format(memory_get_usage()/1024); ?>kb 
		</span>
		<span class="label label-success label-badge" title="Execution Time">
			<?php print round((microtime(true) - START_TIME), 5); ?>s
		</span>
	</div>
</footer>