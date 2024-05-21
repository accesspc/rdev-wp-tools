<?php

if ( ! function_exists( 'add_action' ) ) die() ; 

?>

<div class="wrap rdwt-admin-wrap">

  <h1><?php esc_html_e( 'Rdev WP Tools', 'rdwt' ); ?></h1>
  <h2 class="nav-tab-wrapper"></h2>

  <form method="post" action="options.php">

    <?php settings_fields( 'rdwt_plugin_options' ); ?>

    <div class="metabox-holder">
      <div class="meta-box-sortables ui-sortable">
        <div id="rdwt-overview" class="postbox">

          <h2><?php esc_html_e( 'Overview', 'rdwt' ); ?></h2>

          <div class="panel overview">
            <p>
              <?php 
              esc_html_e( 'Rdev WP Tools', 'rdwt' );
              esc_html_e( ' is a collection of tools in a single bloat-less plugin.', 'rdwt' );
              ?></p>
          </div>

        </div>
      </div>
    </div>

  </form>

</div>
