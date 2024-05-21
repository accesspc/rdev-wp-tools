<?php

if ( ! function_exists( 'add_action' ) ) die() ; 

?>

<div class="wrap rdwt-admin-wrap">

  <h1><?php esc_html_e( 'Settings', 'rdwt' ); ?></h1>
  <h2 class="nav-tab-wrapper"></h2>

  <form method="post" action="options.php">

    <?php settings_fields( 'rdwt_plugin_options' ); ?>

    <div class="metabox-holder">
      <div class="meta-box-sortables ui-sortable">
        <div id="rdwt-settings" class="postbox">

          <h2><?php esc_html_e( 'Google Analytics Settings', 'rdwt' ); ?></h2>

          <div class="panel">
            <table class="widefat">

              <tr>
                <th>
                  <label for="rdwt_options[ga_enable]"><?php esc_html_e( 'Enable', 'rdwt' ); ?></label>
                </th>
                <td>
                  <input id="rdwt_options[ga_enable]" name="rdwt_options[ga_enable]" type="checkbox" value="1" <?php if ( isset( $rdwt_options['ga_enable'] ) ) checked( '1', $rdwt_options['ga_enable'] ); ?> >
                </td>
              </tr>

              <tr>
                <th>
                  <label for="rdwt_options[ga_id]"><?php esc_html_e( 'GA Tracking ID', 'rdwt' ); ?></label>
                </th>
                <td>
                  <input id="rdwt_options[ga_id]" name="rdwt_options[ga_id]" type="text" size="30" maxlength="30" value="<?php if ( isset($rdwt_options['ga_id'])) echo esc_attr( $rdwt_options['ga_id']); ?>">
                </td>
              </tr>

              <tr>
                <th>
                  <label for="rdwt_options[ga_location]"><?php esc_html_e( 'Tracking code location', 'rdwt' ); ?></label>
                </th>
                <td>
                  <label>
                    <input type="radio" name="rdwt_options[ga_location]" value="header" <?php checked( 'header', $rdwt_options['ga_location'] ); ?> />
                    <?php esc_html_e( 'Include tracking code in page head', 'rdwt' ); ?>
                  </label>
                  <br />
                  <label>
                    <input type="radio" name="rdwt_options[ga_location]" value="footer" <?php checked( 'footer', $rdwt_options['ga_location'] ); ?> />
                    <?php esc_html_e( 'Include tracking code in page footer', 'rdwt' ); ?>
                  </label>
                </td>
              </tr>

            </table>
          </div>

        </div>
      </div>
    </div>

    <?php submit_button(); ?>

  </form>

</div>
