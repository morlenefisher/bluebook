<?php

/**
 * Creates a custom post type called bluebook-campaign
 */
require_once(BLUEBOOK_CLASSES . 'posts.php');
require_once(BLUEBOOK_CLASSES . 'meta.php');
require_once(BLUEBOOK_CLASSES . 'gallery.php');

class BlueBookCampaign extends BlueBookPosts {

  public function __construct() {
    $this->post_type = 'bluebook-campaign';
    $this->singular = 'Campaign';
    $this->multiple = 'Campaigns';
    $this->taxonomies = array( 'category', 'post_tag' );
    parent::__construct();

    if (is_admin()) {
      add_action('admin_menu', array( $this, 'addMetaBox' ));
    } //

    if (!is_admin()) {
      // stylesheets
      wp_register_style('bluebook-campaign', plugins_url('/bluebook/css/campaign.css'), array( ), 0.2, 'screen');
      wp_enqueue_style('bluebook-campaign');
      $src = DS . PLUGINDIR . DS . 'bluebook' . DS . 'javascript' . DS . 'campaign.js';
      wp_register_script('bluebook_campaigns', $src, array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' ));
      wp_enqueue_script('bluebook_campaigns');
    }

    /**
     * campaign gallery
     */
    $this->meta_element_attachment_size = 'homepage-promo-banner';
    $this->meta_element_class = 'bluebook-campaign-cta';
    $this->meta_element_id_prefix = 'bluebook-campaign-cta-slide';
  }

  public function addMetaBox() {

    $meta = new BlueBookMeta(array( 'title' => 'Call To Action', 'page' => 'bluebook-campaign' ));

    $meta->createField('CTA Text', 'cta_text', 'text');
    $meta->createField('CTA Link', 'cta_link', 'text');
    $meta->createField('Open Modal', 'cta_modal', 'checkbox');
    $meta->addMetaBox();
  }

  /**
   * Formats any meta fields added by addMetaBox for output
   * @global obj $post
   * @param string $output 
   */
  public function formatMeta(&$output) {

    $meta = $this->getPostMeta();
    $class = 'ctalink';
    $modal = '';
    if ($meta['bluebook_campaign_cta_modal']) {
      $class = 'ctamodal';
      $modal .= "\n\t\t<div id=bluebook_campaign_cta_modal_" . $this->post->ID . "\" class=\"bluebook-campaign-cta-modal\"></div>\n";
    }
    if (is_array($meta) && !empty($meta)) {
      $output .= "\n\t\t<div id=\"bluebook_campaign_cta_" . $this->post->ID . "\" class=\"bluebook-campaign-cta shiny-button \">" .
              "\n\t\t<h3><a class=\"$class\" href=\"" .
              $meta['bluebook_campaign_cta_link'] . "\">" . $meta['bluebook_campaign_cta_text'] .
              "</a></h3>".$modal."\n\t\t</div>\n";
    }
   
  }
  
  

}

//register_post_type('bluebook-campaign', array(
//    'labels' => array(
//        'name' => __('Campaigns'),
//        'singular_name' => __('Campaign'),
//        'add_new' => __('Add New'),
//        'add_new_item' => __('Add New Campaign'),
//        'edit' => __('Edit'),
//        'edit_item' => __('Edit Campaigna'),
//        'new_item' => __('New Campaign Item'),
//        'view' => __('View Campaign Item'),
//        'view_item' => __('View  Campaign time'),
//        'search_items' => __('Search Campaign Items'),
//        'not_found' => __('No Campaigns found'),
//        'not_found_in_trash' => __('No Campaign Items found in Trash'),
//    ),
//    'public' => true,
//    'description' => 'A seasonal promotion that shows on the front page of the site',
//    'has_archive' => true,
//    'show_ui' => true, // UI in admin panel
//    '_builtin' => false, // It's a custom post type, not built in!
//    '_edit_link' => 'post.php?post=%d',
//    'capability_type' => 'post',
//    'hierarchical' => false,
//    'rewrite' => array( 'slug' => 'campaigns' ),
//    'menu_position' => 4,
//    'taxonomies' => array( 'categories', 'post_tag' ),
//    'supports' => array(
//        'title',
//        'editor',
//        'thumbnail',
//        'excerpt' )
//        )
//);

/**
 * MEta Boxes
 */
//if (is_admin()) {
//  add_action('admin_menu', 'bluebook_campaign_add_box');
//} //
//
//function bluebook_campaign_add_box() {
//
//  $meta = new BlueBookMeta(array( 'title' => 'Call To Action', 'page' => 'bluebook-campaign' ));
//
//  $meta->createField('CTA Text', 'cta_text', 'text');
//  $meta->createField('CTA Link', 'cta_link', 'text');
//  $meta->createField('Open Modal', 'cta_modal', 'checkbox');
//  $meta->addMetaBox();
//}
//if (!is_admin()) {
//  // stylesheets
//  wp_register_style('bluebook-campaign', plugins_url('/bluebook/css/campaign.css'), array( ), 0.2, 'screen');
//  wp_enqueue_style('bluebook-campaign');
//}

/**
 * campaign gallery
 */
//$cg = new BlueBookGallery();
//$cg->post_type = 'bluebook-campaign';
//$cg->element_attachment_size = 'homepage-promo-banner';
//$cg->element_class = 'bluebook-campaign-cta';
//$cg->element_id_prefix = 'bluebook-campaign-cta-slide'
?>
