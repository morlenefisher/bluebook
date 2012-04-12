<?php

/**
 * class to manage bluebook settings
 */
class BlueBookSettings extends BlueBook {

  public function __construct() {

    add_action('admin_menu', array( $this, 'createMenu' ));
    add_action('admin_init', array( $this, 'registerSettings' ));
    //$this->addOption('bluebook-types', $this->getCustomPostTypes());
  }

  /**
   * Creates a menu option for our settings
   */
  public function createMenu() {
    add_menu_page('BlueBook Plugin Settings', 'BlueBook', 'manage_options', 'bluebook-options', array( $this, 'showSettingsPage' ), plugins_url('/images/icon.png'));
  }

  /**
   * 
   */
  public function registerSettings() {
    register_setting('bluebook-settings-group', 'bluebook-types');
    add_settings_section('blubook-post-types', 'Enable/Disable Custom Post Types', array( $this, 'addCustomPostTypes' ), 'post_types');
    add_settings_field('bluebook_custom_post_types', 'These are enabled', array($this, 'addCustomPostTypesFields'), 'bluebook', 'post_types');
  }

  /**
   *
   * @param type $name
   * @param type $value
   * @param type $deprecated
   * @param type $autoload 
   */
  public function addOption($name, $value, $deprecated = false, $autoload = false) {
    add_option($name, $value, $deprecated, $autoload);
  }

  public function showSettingsPage() {
    $options = get_option('bluebook-types');
    $available = $this->getCustomPostTypes();
    include BLUEBOOK_VIEWS . 'bb_admin_options.php';
  }

  public function addCustomPostTypes() {
    $options = get_option('bluebook-types');
    $available = $this->getCustomPostTypes();
    p($options);
  }
  
  public function addCustomPostTypesFields() {
    echo "i don't know if this works";
  }

}

?>
