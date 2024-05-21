<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $rdwt_options[ 'ga_id' ]; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){window.dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $rdwt_options[ 'ga_id' ]; ?>');
</script>
