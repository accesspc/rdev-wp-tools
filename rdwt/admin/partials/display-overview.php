<?php

if ( ! function_exists( 'add_action' ) ) die() ; 

?>

<div class="wrap rdwt-admin-wrap">

  <h1 class="rdwt-title"><?php esc_html_e( 'Rdev WP Tools', RDWT_DOMAIN ); ?></h1>
  <?php settings_errors(); ?>
  <h2 class="nav-tab-wrapper"></h2>

  <form method="post" action="options.php">

    <?php settings_fields( 'rdwt_plugin_options' ); ?>

    <div class="metabox-holder">
      <div class="meta-box-sortables ui-sortable">
        <div id="rdwt-overview" class="postbox">

          <h2><?php esc_html_e( 'Overview', RDWT_DOMAIN ); ?></h2>

          <div class="panel overview">
            <p>
              <?php 
              esc_html_e( 'Rdev WP Tools', RDWT_DOMAIN );
              esc_html_e( ' is a collection of tools in a single bloat-less plugin.', RDWT_DOMAIN );
              ?>
            </p>
            <ul>
              <li><?php esc_html_e( 'Google Analytics tracking code', RDWT_DOMAIN ); ?></li>
            </ul>
          </div>

        </div>
      </div>
    </div>

  </form>

</div>
