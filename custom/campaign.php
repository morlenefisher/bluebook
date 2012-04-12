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
      $modal .= "\n\t\t<div class=\"bluebook-campaign-cta-modal\"></div>\n";
    }
    if (is_array($meta) && !empty($meta)) {
      $output .= "\n\t\t<div class=\"bluebook-campaign-cta shiny-button \">" .
              "\n\t\t<h3><a class=\"$class\" href=\"" .
              $meta['bluebook_campaign_cta_link'] . "\">" . $meta['bluebook_campaign_cta_text'] .
              "</a></h3>".$modal."\n\t\t</div>\n";
    }
   
  }
  
  

}

?>
