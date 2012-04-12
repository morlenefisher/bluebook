<?php

/*
  Plugin Name: Blue Book
  Plugin URI: http://impressivemedia.co.uk/wordpress-plugins/bluebook
  Description:  Adds numerous bits to make your Wordpress site your own
  Version: 0.2.1
  Author: Morlene Fisher
  Author URI: http://digital.ashleylawrence.com/author/morlene-fisher
 */
/*  Copyright 2011  Ashley Lawrence Ltd.  (email : digital@ashleylawrence.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// Exit if accessed directly
if (!defined('ABSPATH'))
  exit;

define('DS', '/');
define('BLUEBOOK_VERSION', '0.4' ); // version of this plugin
define('BLUEBOOK_PATH', plugin_dir_path(__FILE__));
define('BLUEBOOK_CLASSES', plugin_dir_path(__FILE__) . 'classes' . DS);
define('BLUEBOOK_CUSTOM', plugin_dir_path(__FILE__) . 'custom' . DS);
define('BLUEBOOK_INCLUDES', plugin_dir_path(__FILE__) . 'includes' . DS);
define('BLUEBOOK_JAVASCRIPT', plugin_dir_path(__FILE__) . 'javascript' . DS);
define('BLUEBOOK_JS_URL', plugin_dir_url(__FILE__));
define('BLUEBOOK_VIEWS', plugin_dir_path(__FILE__) . 'views' . DS);

require_once(BLUEBOOK_INCLUDES . 'common.php');
// include widgets
require_once(BLUEBOOK_CLASSES . 'bb.php');

include_once plugin_dir_path(__FILE__) . '/classes/widgets.php';
include_once plugin_dir_path(__FILE__) . '/classes/settings.php';

$settings = new BlueBookSettings();

/**
 * Actions
 */
add_action('login_head', 'bluebook_custom_login_logo');
add_action('init', 'bluebook_register_custom_post_types');
//add_action("plugins_loaded", array( new BluebookRelatedPostsWidget('Blue Book Related Posts'), 'register' ));
//add_action('wp_loaded', new BlueBookSettings);




// create custom plugin settings menu

/**
 * Filters
 */
add_filter('avatar_defaults', 'bluebook_addgravatar');
add_filter('wp_mail_from_name', 'bluebook_wp_mail_from_name');
add_filter('wp_mail_content_type', 'bluebook_wp_mail_content_type');



/* * *************************************
 * add new post types to dashboard, Right Now
 * widget
 */

//wp_add_dashboard_widget('bluebook_dashboard_widget', __('Right Now in Ibiza Residents', 'bluebook'), 'bluebook_dashboard_widget');
//add_action('right_now_content_table_end', 'bluebook_right_now_content_table_end');

function bluebook_dashboard_widget() {
  $args = array(
      'public' => true,
      '_builtin' => false
  );
  $output = 'object';
  $operator = 'and';

  $post_types = get_post_types($args, $output, $operator);

  foreach ($post_types as $post_type) {
    $num_posts = wp_count_posts($post_type->name);
    $num = number_format_i18n($num_posts->publish);
    $text = _n($post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));
    if (current_user_can('edit_posts')) {
      $num = "<a href='edit.php?post_type=$post_type->name'>$num</a>";
      $text = "<a href='edit.php?post_type=$post_type->name'>$text</a>";
    }
    echo '<tr><td class="first b b-' . $post_type->name . '">' . $num . '</td>';
    echo '<td class="t ' . $post_type->name . '">' . $text . '</td></tr>';
  }

  $taxonomies = get_taxonomies($args, $output, $operator);

  foreach ($taxonomies as $taxonomy) {
    $num_terms = wp_count_terms($taxonomy->name);
    $num = number_format_i18n($num_terms);
    $text = _n($taxonomy->labels->singular_name, $taxonomy->labels->name, intval($num_terms));
    if (current_user_can('manage_categories')) {
      $num = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$num</a>";
      $text = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$text</a>";
    }
    echo '<tr><td class="first b b-' . $taxonomy->name . '">' . $num . '</td>';
    echo '<td class="t ' . $taxonomy->name . '">' . $text . '</td></tr>';
  }

  //   php do_action('bbp_dashboard_widget_right_now_content_table_end');
}

/**
 * Adds a new from name to the wp_mail
 * @param string $name
 * @return string 
 */
function bluebook_wp_mail_from_name($name) {
  return bloginfo('sitename');
}

/**
 * Adds text/html content type for mail
 * @param string $type
 * @return string 
 */
function bluebook_wp_mail_content_type($type) {
  return 'text/html';
}

/**
 * Adds custom post types to the installation
 * @todo make the selections base on the options chosen by the site admin
 * 
 */
function bluebook_register_custom_post_types() {
  include_once 'custom-post-types.php';
  include_once BLUEBOOK_CUSTOM . 'event.php';
  include_once BLUEBOOK_CUSTOM . 'campaign.php';
  $campaign = new BlueBookCampaign();
  include_once BLUEBOOK_CUSTOM . 'gallery.php';
 // include_once BLUEBOOK_CUSTOM . 'recipe.php';
  include_once BLUEBOOK_CUSTOM . 'property.php';
  $property = new BlueBookProperty();
}



/**
 * Adds a new gravatar to the list of existing ones that represents more your
 * brand. The new avatar should be uploaded and saved in the theme template 
 * directory
 */
function bluebook_addgravatar($avatar_defaults) {
  if (@file_exists(get_bloginfo('template_directory') . '/images/avatar.png')) {
    $myavatar = get_bloginfo('template_directory') . '/images/avatar.png';
    $avatar_defaults[$myavatar] = 'Bluebook';
    return $avatar_defaults;
  }
}

/**
 * Adds a custom logo to the login screen. This function only replaces the 
 * existing wordpress logo with a new logothat you've defined and put in the 
 * directory named below. The image is placedabove the login box, but does not 
 * alter the link. It will still direct the user to the Wordpress website. 
 * If you want to change the link on this screen you will need to do that in 
 * the code or create a new function to do so.
 *
 */
function bluebook_custom_login_logo() {
  echo '<style type="text/css">
	h1 a { background-image: url(/assets/logo.png) !important; }
	</style>';
}

/**
 * Returns the meta data created by this plugin
 * @global object $post
 * @return mixed array|boolean 
 */
function bluebook_get_post_meta() {
  global $post;
  
   $meta = get_post_custom($post->ID);
   $pt = str_replace('-', '_', $post->post_type);

   if (is_array($meta) && !empty($meta)) {
     foreach($meta as $k => $v) {
       if (false !== strstr($k, $pt)) {
         $ret[$k] = $v[0];
       }
     }
   }
   
   return empty($ret) ? false : $ret;
}


?>
