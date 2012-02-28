<?php

/**
 * Creates a custom post type called bluebook-gallery
 */
require_once(BLUEBOOK_CLASSES . 'posts.php');
require_once(BLUEBOOK_CLASSES . 'meta.php');


$args = array(
    'labels' => array( 'name' => __('Galleries'), 'singular_name' => __('Gallery'),
        'add_new' => _x('Add New', 'Gallery'),
        'add_new_item' => __('Add New Gallery'),
        'edit_item' => __('Edit Gallery'),
        'new_item' => __('New Gallery'),
        'view_item' => __('View Gallery'),
        'search_items' => __('Search Galleries'),
        'not_found' => __('No Galleries found'),
        'not_found_in_trash' => __('No Galleries found in Trash'),
    ),
    'description' => 'The Media Gallery',
    'menu_position' => 5,
    'rewrite' => array( 'slug' => 'galleries' ),
    'supports' => array( 'title', 'editor', 'revisions', 'thumbnail', 'comments', 'page-attributes' ),
    'public' => true,
    'has_archive' => true
);

register_post_type('bluebook-gallery', $args);


/**
 * Register taxonomies
 * 
 */
// Add new taxonomy, make it hierarchical (like categories)
$labels = array(
    'name' => __('Genres'),
    'singular_name' => _x('Genre', 'taxonomy singular name'),
    'search_items' => __('Search Genres'),
    'all_items' => __('All Genres'),
    'parent_item' => __('Parent Genre'),
    'parent_item_colon' => __('Parent Genre:'),
    'edit_item' => __('Edit Genre'),
    'update_item' => __('Update Genre'),
    'add_new_item' => __('Add New Genre'),
    'new_item_name' => __('New Genre Name'),
);

register_taxonomy('genre', 'bluebook-gallery', array( 'hierarchical' => true, 'labels' => $labels, 'show_ui' => true, 'query_var' => true, 'rewrite' => array( 'slug' => 'genre' ), ));

$labels = array(
    'name' => __('Series'),
    'singular_name' => __('Series'),
    'search_items' => __('Search Series'),
    'all_items' => __('All Series'),
    'parent_item' => __('Parent Series'),
    'parent_item_colon' => __('Parent Series:'),
    'edit_item' => __('Edit Series'),
    'update_item' => __('Update Series'),
    'add_new_item' => __('Add New Series'),
    'new_item_name' => __('New Series Name'),
);

register_taxonomy('series', 'bluebook-gallery', array( 'hierarchical' => false, 'labels' => $labels, 'query_var' => true, 'rewrite' => true ));

$labels = array(
    'name' => __('Types'),
    'singular_name' => __('Gellery Type'),
    'search_items' => __('Search Types'),
    'all_items' => __('All Gallery Types'),
    'parent_item' => __('Parent Type'),
    'parent_item_colon' => __('Parent Type:'),
    'edit_item' => __('Edit Type'),
    'update_item' => __('Update Gallery Type'),
    'add_new_item' => __('Add New Gallery Type'),
    'new_item_name' => __('New Gallery Type Name'),
);
register_taxonomy('gallery-type', 'bluebook-gallery', array( 'hierarchical' => true, 'labels' => $labels, 'query_var' => true, 'rewrite' => true ));


/**
 * Add a set of default terms to the above genre
 *
 */
add_action('init', 'bluebook_gallery_default_genre_terms');

function bluebook_gallery_default_genre_terms() {
  //wp_insert_term( $term, $taxonomy, $args = array() );
  wp_insert_term('Abstract and Experimental', 'genre', $args = array( ));
  wp_insert_term('Astrophotography', 'genre', $args = array( ));
  wp_insert_term('Aviation', 'genre', $args = array( ));
  wp_insert_term('Black and White', 'genre', $args = array( ));
  wp_insert_term('Cave', 'genre', $args = array( ));
  wp_insert_term('Dance', 'genre', $args = array( ));
  wp_insert_term('Documentary', 'genre', $args = array( ));
  wp_insert_term('Fine Art', 'genre', $args = array( ));
  wp_insert_term('Forensic', 'genre', $args = array( ));
  wp_insert_term('Gothic', 'genre', $args = array( ));
  wp_insert_term('Kite Aerial', 'genre', $args = array( ));
  wp_insert_term('Music Photography', 'genre', $args = array( ));
  wp_insert_term('Nature and Wildlife Photography', 'genre', $args = array( ));
  wp_insert_term('Portrait', 'genre', $args = array( ));
  wp_insert_term('Road Photography', 'genre', $args = array( ));
  wp_insert_term('Underwater', 'genre', $args = array( ));

  wp_insert_term('Photography', 'gallery-type', $args = array( ));
  wp_insert_term('Video', 'gallery-type', $args = array( ));
  wp_insert_term('Mixed Media', 'gallery-type', $args = array( ));
}

/**
 * Add a set of default terms to the gallery type taxonomoy
 */
add_action('init', 'ip_default_gallery_type_terms');

function ip_default_gallery_type_terms() {
  //wp_insert_term( $term, $taxonomy, $args = array() );
}

/**
 * enqueue supersized scripts
 */
if (!is_admin()) {

  wp_register_script('supersized', plugins_url('/bluebook/javascript/contrib/supersized.core.3.2.1.min.js'), array( 'jquery' ));
  wp_enqueue_script('supersized');

  // stylesheets
  wp_register_style('supersized', plugins_url('/bluebook/css/supersized.css'), array( ), 0.2, 'screen');
  wp_enqueue_style('supersized');
}

add_filter('post_gallery', 'renderCampaignGallery', 0, 1);

/**
 * Renders the campaign attachments individually so that they can be made into slides
 * @global type $post
 * @param type $attr
 * @return type 
 */
function renderCampaignGallery($attr) {
  global $post;
  // only want gallery types
  if ($post->post_type != 'bluebook-campaign')
    return;


  // get the post attachments
  // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
  if (isset($attr['orderby'])) {
    $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
    if (!$attr['orderby'])
      unset($attr['orderby']);
  }

  extract(shortcode_atts(array(
              'order' => 'ASC',
              'orderby' => 'menu_order ID',
              'id' => $post->ID,
              'itemtag' => 'ul',
              'icontag' => 'li',
              'captiontag' => 'p',
              'columns' => 3,
              'size' => 'thumbnail',
              'include' => '',
              'exclude' => ''
                  ), $attr));



  $id = intval($id);
  if ('RAND' == $order)
    $orderby = 'none';

  if (!empty($include)) {
    $include = preg_replace('/[^0-9,]+/', '', $include);
    $_attachments = get_posts(array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'order' => $order, 'orderby' => $orderby ));

    $attachments = array( );
    foreach ($_attachments as $key => $val) {
      $attachments[$val->ID] = $_attachments[$key];
    }
  }
  elseif (!empty($exclude)) {
    $exclude = preg_replace('/[^0-9,]+/', '', $exclude);
    $attachments = get_children(array( 'post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'order' => $order, 'orderby' => $orderby ));
  }
  else {
    $attachments = get_children(array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment',
        'order' => $order, 'orderby' => $orderby ));
  }

  if (empty($attachments))
    return '';

  
  return formatCampaignGallery($attachments, $columns, $meta);
}

/**
 * formats the output
 * @param type $attachments 
 */
function formatCampaignGallery($attachments, $columns, $meta = array( )) {

  if (!empty($attachments)) {
    foreach ($attachments as $attachment) {

// check what mime type the attachment is and show appropriate
      // image or icon depending
      $is_image = strpos($attachment->post_mime_type, 'image');
      //print('is image '. $is_image);
      if ($is_image >= 0) {
        $output .= "\t<li class='bluebook-campaign-banner-slide' id='bluebook_campaign_banner_slide_" . $attachment->ID . "'>\n\t\t";
        $output .= wp_get_attachment_image($attachment->ID, 'homepage-promo-banner');
        formatCampaignMeta($output);
      }

      $output .= "\t</li>\n";
    }

    return $output;
  }
}

function formatCampaignMeta(&$output) {
  global $post;
  $meta = bluebook_get_post_meta();

  if (is_array($meta) && !empty($meta)) {
    $output .= "\n\t\t<div id=\"blubook_campaign_cta_" . $post->ID . "\" class=\"bluebook-campaign-cta-rounded-corners\">" .
            "\n\t\t<p><a href=\"" .
            $meta['bluebook_campaign_cta_link'] . "\">" . $meta['bluebook_campaign_cta_text'] .
            "</a></p>\n\t\t</div>\n";
  }
}
?>
