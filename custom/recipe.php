<?php
/**
 * Creates a custom post type called bluebook-recipe 
 */

require_once(BLUEBOOK_CLASSES . 'posts.php');
require_once(BLUEBOOK_CLASSES . 'meta.php');


register_post_type('bluebook-recipe', array(
    'labels' => array(
        'name' => __('Recipes'),
        'singular_name' => __('Recipe'),
        'add_new' => __('Add Recipe'),
        'add_new_item' => __('Add New Recipe'),
        'edit' => __('Edit'),
        'edit_item' => __('Edit Recipe'),
        'new_item' => __('New Recipe'),
        'view' => __('View Recipe'),
        'view_item' => __('View Recipe'),
        'search_items' => __('Search Recipes'),
        'not_found' => __('No Recipes found'),
        'not_found_in_trash' => __('No Recipes found in Trash'),
    ),
    'public' => true,
    'has_archive' => true,
    'show_ui' => true, // UI in admin panel
    '_builtin' => false, // It's a custom post type, not built in!
    '_edit_link' => 'post.php?post=%d',
    'capability_type' => 'post',
    'hierarchical' => false,
    'rewrite' => array( 'slug' => 'recipes' ),
    'menu_position' => 5,
    'taxonomies' => array( 'courses', 'cuisine' ),
    'supports' => array(
        'title',
        'editor',
        'revisions',
        'thumbnail'
    )
));

if ( is_admin() )  
  add_action('admin_menu', 'bluebook_recipe_add_box');

// Add meta box
function bluebook_recipe_add_box() {
  
  $meta = new BlueBookMeta(array('page' => 'bluebook-recipe', 'title' => 'Additional info'));
  $meta->createField('Prep Time', 'bluebook_recipe_preptime', 'text');
  $meta->createField('Cook Time', 'bluebook_recipe_cooktime', 'text');
  $meta->createField('Serves', 'bluebook_recipe_serving', 'text');
  $meta->createField('Ingredients', 'bluebook_recipe_ingredients', 'textarea');
  $meta->addMetaBox();
    
} //

$labels = array(
    'name' => __('Course'),
    'singular_name' => __('Course'),
    'search_items' => __('Courses'),
    'all_items' => __('All Courses'),
    'parent_item' => __('Parent Course'),
    'parent_item_colon' => __('Parent Course:'),
    'edit_item' => __('Edit Course'),
    'update_item' => __('Update Course'),
    'add_new_item' => __('Add New Course'),
    'new_item_name' => __('New Course Name'),
);
register_taxonomy('courses', 'bluebook-recipe', array( 'hierarchical' => false, 'labels' => $labels, 'query_var' => true, 'rewrite' => true ));
$labels = array(
    'name' => __('Cuisine'),
    'singular_name' => __('Cuisine'),
    'search_items' => __('Cuisines'),
    'all_items' => __('All Cuisines'),
    'parent_item' => __('Parent Cuisine'),
    'parent_item_colon' => __('Parent Cuisine:'),
    'edit_item' => __('Edit Cuisine'),
    'update_item' => __('Update Cuisine'),
    'add_new_item' => __('Add New Cuisine'),
    'new_item_name' => __('New Cuisine Name'),
);register_taxonomy('cuisine', 'bluebook-recipe', array( 'hierarchical' => false, 'labels' => $labels, 'query_var' => true, 'rewrite' => true ));


/**
 * Add a set of default terms to the above genre
 *
 */
add_action('init', 'bluebook_recipe_default_genre_terms');
function bluebook_recipe_default_genre_terms(){
   //wp_insert_term( $term, $taxonomy, $args = array() );
   wp_insert_term('African', 'cuisine', $args = array() );
   wp_insert_term('Spanish', 'cuisine', $args = array() );
   wp_insert_term('Italian', 'cuisine', $args = array() );
   wp_insert_term('Indian', 'cuisine', $args = array() );
   wp_insert_term('Caribbean', 'cuisine', $args = array() );
}


?>
