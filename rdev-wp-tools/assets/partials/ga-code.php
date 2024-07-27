<?php
/**
 * UI -> GA code.
 *
 * @package Rdev\WpTools
 */

if ( ! function_exists( 'add_action' ) ) {
	die();
}

?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $options['ga_id']; ?>"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){window.dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', '<?php echo $options['ga_id']; ?>');
</script>
<!-- End: Google tag (gtag.js) -->
