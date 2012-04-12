<?php

/**
 * taxonomy short Description
 * PHP versions 5
 *
 * APPLICATION :  digital @ Ashley Lawrence <http://digital.ashleylawrence.com>
 * Copyright 2012, Ashley Lawrence Ltd.
 * Maybrook House, 97 Godstone Road, CAterham, Surrey CR3 6RE
 *
 * Licensed under GNU General Public License, version 2 (GPLv2)
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		Copyright 2012, Ashley Lawrence Ltd.
 * @package			BlueBook
 * @subpackage		
 * @since			
 */

require_once(BLUEBOOK_CLASSES . 'bb.php');

class BlueBookTaxonomy extends BlueBook {
  
  
  public $singular = '';
  public $multiple = '';
  public $description = '';
  public $labels = array( );
  public $menu_position = 4;
  public $rewrite = array( );
  public $supports = array( );
  public $taxonomies = array( );
  public $is_public = true;
  public $has_archive = true;
  public $show_ui = true;
  public $is_hierarchical = false;
  public $can_export = true;
  public $meta_element_class;
  public $meta_element_id_prefix;
  public $meta_element_attachment_size;
  public $post;
  
  public function __construct() {
    $this->assignLabels();
  }
  /**
   * Takes the post type name and makes standard field labels
   */
  private function assignLabels() {

    $labels = array(
    'name' => _x( $this->multiple, 'taxonomy general name' ),
    'singular_name' => _x( $this->singular, 'taxonomy singular name' ),
    'search_items' =>  __( 'Search ' . $this->multiple ),
    'popular_items' => __( 'Popular ' . $this->multiple ),
    'all_items' => __( 'All ' . $this->multiple ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit ' . $this->singular ), 
    'update_item' => __( 'Update ' . $this->singular ),
    'add_new_item' => __( 'Add New ' . $this->singular ),
    'new_item_name' => __( 'New ' . $this->singular .' Name' ),
    'separate_items_with_commas' => __( 'Separate ' . $this->multiple .' with commas' ),
    'add_or_remove_items' => __( 'Add or remove ' . $this->multiple ),
    'choose_from_most_used' => __( 'Choose from the most used ' . $this->multiple ),
    'menu_name' => __( $this->multiple ),
      );
    $this->labels = $labels;
  }
  
}

?>
