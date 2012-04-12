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

    if (is_admin()) {
      add_action('admin_menu', array( $this, 'addMetaBox' ));
    } 
  }

  public function addMetaBox() {
    $meta = new BlueBookMeta(array( 'title' => 'Property Details', 'page' => 'bluebook-property' ));

    $meta->createField('List/Guide Price', 'price', 'text');
    $meta->createField('Address', 'address', 'text');
    $meta->createField('Town', 'town', 'text');
    $meta->createField('City', 'city', 'text');
    $meta->createField('Post Code', 'postcode', 'text');
    $meta->createField('Borough', 'borough', 'select', '', '', '', array('Greater London', 'Manchester'));
    $meta->createField('List/Guide Price', 'price', 'text');
    $meta->createField('Property Type', 'property_type', 'select', '', '', '', 
            array('Greater London', 'Manchester'));
    
    $meta->createField('Bedrooms', 'bedrooms', 'text');
    $meta->createField('Bathrooms', 'bathrooms', 'text');
    $meta->createField('Garage', 'garage', 'checkbox');    
    $meta->createField('Garden', 'garden', 'checkbox');
    $meta->createField('Double Glazing', 'double_glazing', 'checkbox');
    $meta->createField('Central Heating', 'central_heating', 'checkbox');
    $meta->createField('Garage', 'garage', 'checkbox');
    $meta->createField('Off Street Parking', 'off_street_parking', 'checkbox');
    $meta->addMetaBox();
  }

  public function formatMeta(&$output) {
    
  }
  
  
  private function getPropertyTypes() {
    return array( 
        'residential' => 'Residential', 
        'commercial' => 'Commercial',
        'house' =>  'Houses',
        'flat_maisonette' => 'Flats/Maisonettes',
        'mixed_use' => 'Mixed Use',
        'land_site' => 'Site/Land',
        'ground_rent' => 'Ground Rents'
        );
  }
  

  private function getPropertyUsageType() {
    
  }
}




?>
