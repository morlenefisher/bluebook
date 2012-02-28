<?php

/**
 * Creates a custom post type called bluebook-event
 */
require_once(BLUEBOOK_CLASSES . 'posts.php');
require_once(BLUEBOOK_CLASSES . 'meta.php');


register_post_type('bluebook-event', array(
    'labels' => array(
        'name' => __('Events'),
        'singular_name' => __('Event'),
        'add_new' => __('Add Event'),
        'add_new_item' => __('Add New Event '),
        'edit' => __('Edit'),
        'edit_item' => __('Edit Event'),
        'new_item' => __('New Event'),
        'view' => __('View Event'),
        'view_item' => __('View  Event'),
        'search_items' => __('Search Events'),
        'not_found' => __('No Events found'),
        'not_found_in_trash' => __('No Events found in Trash'),
    ),
    'public' => true,
    'has_archive' => true,
    'show_ui' => true, // UI in admin panel
    '_builtin' => false, // It's a custom post type, not built in!
    '_edit_link' => 'post.php?post=%d',
    'capability_type' => 'post',
    'hierarchical' => false,
    'rewrite' => array( 'slug' => 'events' ),
    'menu_position' => 5,
    'taxonomies' => array( 'series', 'region' ),
    'supports' => array(
        'title',
        'editor',
        'revisions',
        'excerpt',
        'thumbnail'
    )
));


if (is_admin()) {
  // add metaboxes to this 
  add_action('admin_menu', 'bluebook_event_add_box');
  
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-datepicker');
  // add jquery call to datepicker classplug
  $src = DS. PLUGINDIR .DS . 'bluebook' . DS . 'javascript' . DS . 'events.js';
  wp_register_script('bluebook_events', $src, array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'));
  wp_enqueue_script('bluebook_events');

}

// Add meta box
function bluebook_event_add_box() {
  
  $meta = new BlueBookMeta(array('page' => 'bluebook-event', 'title' => 'Event Details'));
  
  $meta->createField('Event Date', 'date', 'text', 'What date will the event occur', '', array( 'class' => 'datepicker' ));
  $meta->createField('Time', 'time', 'text');
  $meta->createField('Location', 'location', 'text');
  $meta->createField('Map URL', 'location_map', 'text', 'Enter map url (Google maps, street map etc.)');
  $meta->createField('Tickets', 'ticket_price', 'text', 'Enter the price of the ticket');
  $meta->addMetaBox();
}



?>
