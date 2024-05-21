<?php

if ( ! function_exists( 'add_action' ) ) die() ; 

?>

<div class="wrap rdwt-admin-wrap">

  <h1><?php esc_html_e( 'Settings', 'rdwt' ); ?></h1>
  <h2 class="nav-tab-wrapper"></h2>

  <form method="post" action="options.php">

    <?php
    settings_fields( 'rdwt_plugin_options' );
    do_settings_sections( 'rdwt-settings' );
    submit_button(); 
    ?>

  </form>

</div>
