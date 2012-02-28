<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of bb
 *
 * @author stirlyn
 */
 class BlueBook {

  public $post_type;


  /**
   * ASsign keys in an array to the equivalently named class properties
   */
  protected function assignProperties($arguments = array( )) {
    if (is_array($arguments)) {
      foreach ($arguments as $key => $arg) {
        $this->$key = $arg;
      }
    }
  }

  /**
   * Returns all the possible custom post types available
   * @return array
   */
  public function getCustomPostTypes() {

    $cpt_files = list_files(BLUEBOOK_CUSTOM);

    foreach ($cpt_files as $k => $fn) {
      $file = str_replace('.php', '', array_pop(explode('/', $fn)));
      $cpt[] = ucwords($file);
    }

    asort($cpt);
    return $cpt;
  }

}

?>
