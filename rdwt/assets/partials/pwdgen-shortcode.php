<div class="rdwt-pwdgen">
  <input type="hidden" class="pwdgen-count" name="pwdgen-count" value="<?php esc_html_e( $options[ 'pwdgen_count' ] ); ?>" />
  <input type="hidden" class="pwdgen-length" name="pwdgen-length" value="<?php esc_html_e( $options[ 'pwdgen_length' ] ); ?>" />
  <input type="hidden" class="pwdgen-inc_numbers" name="pwdgen-inc_numbers" value="<?php esc_html_e( $options[ 'pwdgen_inc_numbers' ] ); ?>" />
  <input type="hidden" class="pwdgen-inc_lower" name="pwdgen-inc_lower" value="<?php esc_html_e( $options[ 'pwdgen_inc_lower' ] ); ?>" />
  <input type="hidden" class="pwdgen-inc_upper" name="pwdgen-inc_upper" value="<?php esc_html_e( $options[ 'pwdgen_inc_upper' ] ); ?>" />
  <input type="hidden" class="pwdgen-inc_symbols" name="pwdgen-inc_symbols" value="<?php esc_html_e( $options[ 'pwdgen_inc_symbols' ] ); ?>" />
  <div class="wp-block-button rdwt-pwdgen-generate">
    <a class="wp-block-button__link wp-element-button">Generate</a>
  </div>
  <div class="pwdgen-list"></div>
</div>
