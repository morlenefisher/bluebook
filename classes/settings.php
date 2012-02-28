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
    add_menu_page('BlueBook Plugin Settings', 'BlueBook', 'administrator', __FILE__, 'bluebook_settings_page', plugins_url('/images/icon.png', __FILE__));
  }

  /**
   * 
   */
  public function registerSettings() {
    register_setting('bluebook-settings-group', 'bluebook-custom-types');
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
  
  
}

?>
