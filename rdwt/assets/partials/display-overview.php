<?php

if ( ! function_exists( 'add_action' ) ) {
  die();
}

?>

<div class="wrap rdwt-admin-wrap">

  <h1 class="rdwt-title"><?php esc_html_e( 'Rdev WP Tools', RDWT_DOMAIN ); ?></h1>
  <?php settings_errors(); ?>
  <h2 class="nav-tab-wrapper">&nbsp;</h2>

  <form method="post" action="options.php">

    <?php
    settings_fields( 'rdwt_plugin' );
    do_settings_sections( 'rdwt' );
    ?>

  </form>

</div>
