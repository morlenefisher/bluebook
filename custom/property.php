<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



require_once(BLUEBOOK_CLASSES . 'posts.php');
require_once(BLUEBOOK_CLASSES . 'meta.php');

class BlueBookProperty extends BlueBookPosts {

  public function __construct() {
    $this->post_type = 'bluebook-property';
    $this->singular = 'Property';
    $this->multiple = 'Properties';
    parent::__construct();
  }

  public function addMetaBox() {
    
  }

  public function formatMeta(&$output) {
    
  }
  

}

?>
