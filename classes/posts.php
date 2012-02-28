<?php

require_once(BLUEBOOK_CLASSES . 'bb.php');

abstract class BlueBookPosts extends BlueBook {

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

  /**
   * Takes an array of arguments needed to set up the post type
   * @param array $args 
   */
  public function __construct($args = array( )) {


    if (is_object($args)) {
      // convert to an array
      $args = (array) $args;
    }


    if (!is_array($args)) {
      throw new Exception('Cannot convert object to an array');
      return false;
    }
    // check what we've got
    $this->assignProperties();
    $this->assignLabels();
    $this->assignRewrite();
    $this->assignSupports();
    $this->assignTaxonomy();
    $this->registerPostType();

    // filters
    add_filter('post_gallery', array( $this, 'renderWPGallery' ));
  }

  /**
   * Adds a meta box to the post type
   */
  abstract protected function addMetaBox();

  /**
   * Takes the post type name and makes standard field labels
   */
  private function assignLabels() {
    $labels = array( );
    $labels['name'] = __($this->multiple);
    $labels['singular_name'] = __($this->singular);
    $labels['add_new'] = __('Add ' . $this->singular);
    $labels['add_new_item'] = __('Add New ' . $this->singular);
    $labels['edit'] = __('Edit');
    $labels['edit_item'] = __('Edit ' . $this->singular);
    $labels['new_item'] = __('New ' . $this->singular . ' Item');
    $labels['view'] = __('View ' . $this->singular . ' Item');
    $labels['view_item'] = __('View ' . $this->singular);
    $labels['search_items'] = __('Search ' . $this->singular . ' items');
    $labels['not_found'] = __('No ' . $this->multiple . ' found');
    $labels['not_found_in_trash'] = __('No ' . $this->singular . ' items found in Trash');

    $this->labels = $labels;
  }

  public function assignRewrite($args = array( )) {
    if (empty($args) && empty($this->rewrite)) {
      $this->rewrite = array( 'slug' => strtolower($this->multiple) );
    }
  }

  public function assignSupports($args = array( )) {
    if (empty($args) && empty($this->supports)) {
      $this->supports = array( 'title', 'editor', 'thumbnail', 'excerpt' );
    }
  }

  public function assignTaxonomy($args = array( )) {
    if (empty($args) && empty($this->taxonomies)) {
      return;
    }
  }

  /**
   * Renders the campaign attachments individually so that they can be made into slides
   * @global obj $post
   * @param arr $attr
   * @return type 
   */
  public function renderWPGallery($attr) {
    global $post;
    $this->post = $post;


    // only want gallery types
    if ($this->post->post_type != $this->post_type)
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
                'id' => $this->post->ID,
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


    return $this->formatGallery($attachments);
  }

  /**
   * formats the output
   * @param type $attachments 
   */
  private function formatGallery($attachments) {

    if (!empty($attachments)) {
      foreach ($attachments as $attachment) {

// check what mime type the attachment is and show appropriate
        // image or icon depending
        $is_image = strpos($attachment->post_mime_type, 'image');
        //print('is image '. $is_image);
        if ($is_image >= 0) {
          $output .= "\t<li class='" . $this->meta_element_class . "' id='" .
                  $this->meta_element_id_prefix . "_" . $attachment->ID . "'>\n\t\t";
          $output .= wp_get_attachment_image($attachment->ID, $this->meta_element_attachment_size);
          $this->formatMeta($output);
        }

        $output .= "\t</li>\n";
      }

      return $output;
    }
  }

  /**
   * Needs more generic approach to handling
   * @global obj $post
   * @param string $output 
   */
  abstract protected function formatMeta(&$output);

  /**
   * Returns the custom meta for post
   * @global obj $post
   * @return type 
   */
  public function getPostMeta() {
    global $post;

    $meta = get_post_custom($post->ID);
    $pt = str_replace('-', '_', $post->post_type);

    if (is_array($meta) && !empty($meta)) {
      foreach ($meta as $k => $v) {
        if (false !== strstr($k, $pt)) {
          $ret[$k] = $v[0];
        }
      }
    }

    return empty($ret) ? false : $ret;
  }

  /**
   * Registers the post type withing wp
   */
  protected function registerPostType() {

    register_post_type($this->post_type, array(
        'labels' => $this->labels,
        'public' => $this->is_public,
        'description' => $this->description,
        'has_archive' => $this->has_archive,
        'show_ui' => $this->show_ui,
        'capability_type' => 'post',
        'hierarchical' => $this->is_hierarchical,
        'rewrite' => $this->rewrite,
        'menu_position' => $this->menu_position,
        'can_export' => $this->can_export,
        'taxonomies' => $this->taxonomies,
        'supports' => $this->supports,
    ));
  }

}