<footer>
	<div class="pull-right">
		<a href="http://blogs.devmeta.net" class="footer-connect" title="Crea tu Blog"><span class="typcn typcn-microphone x1-2"></span></a>
		</span>
	</div>
</footer>

<?php if(getenv('REMOTE_ADDR') != '127.0.0.1'):?>
<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
    var disqus_shortname = 'devmetablogs'; // required: replace example with your forum shortname

    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement('script'); s.async = true;
        s.type = 'text/javascript';
        s.src = '//' + disqus_shortname + '.disqus.com/count.js';
        (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
    }());
</script> 
<?php endif;?>