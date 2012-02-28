<div class="wrap">
<h2>Blue Book Options</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'bluebook-settings-group' ); ?>
    <?php do_settings_fields( 'bluebook-settings-group' ); ?>
    <?php $options = get_option( 'bluebook-custom-types' );?>
    
    <table class="form-table">
      
      <tr><td colspan ="<?php echo count($custom_post_types);?>">Enable Custom Post Types</td></tr>
      <tr><td>
          <?php 
          foreach($custom_post_types as $pt) {
            ?>
          <td>
            <input type="checkbox" value="<?php echo get_option('bluebook-custom-types['. $pt .']'); ?>" name="bluebook-custom-types[<?php echo $pt;?>]" /> <?echo $pt;?>
          </td>
            <?php
          }
          ?>
        </td></tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
</div>

