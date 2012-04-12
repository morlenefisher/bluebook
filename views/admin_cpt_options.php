<div class="wrap">
  <h2>Blue Book Options</h2>

  <form method="post" action="options.php" name="bluebook-posttypes">
    <?php settings_fields('bluebook-settings-group'); ?>
    <?php do_settings_sections('bluebook'); ?>
   
    <?php p($available);
    p($options) ?>

    <table class="form-table">

      <tr><td colspan ="<?php echo count($available); ?>">Enable Custom Post Types</td></tr>
      <tr><td>
          <?php
          foreach ($available as $k => $v) :
            $checked = false;
            if (isset($options[$k])) :
              $checked = 'checked';
            endif;
            ?>
          <td>
            <input type="checkbox" value="bluebook-types[<?php echo $k; ?>]" name="bluebook-types[<?php echo $k; ?>]" <?php echo $checked; ?> /><? echo $v; ?>
          </td>
<?php endforeach; ?>
        </td></tr>
    </table>

    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

  </form>
</div>

