<?php

require_once(BLUEBOOK_CLASSES . 'bb.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of meta
 *
 * @author stirlyn
 */
class BlueBookMeta extends BlueBook {

  /**
   * The title of the edit
   * @var string
   */
  public $title;

  /**
   * The post type 
   * @var string
   */
  public $page;

  /**
   * The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side'). 
   * @var string
   */
  public $context = 'normal';

  /**
   * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low')
   * @var string
   */
  public $priority = 'high';

  /**
   * The fields data that will make up the meta box
   * @var array
   */
  public $fields;

  /**
   * The prefix to all the fields and the meta box to guard against name collisions
   * @var string
   */
  public $prefix = '';
  
  /**
   * id of the metabox
   * @var string
   */
  public $id = '';

  /**
   * Constructor, pass in an index array or object whose properties match that of the metabox
   * parameters and it will auto assign them to the class properties
   * @param type $args 
   */
  public function __construct($args = array( )) {

    if ($prefix) {
      $this->prefix = $prefix;
    }

    if (!empty($args)) {

      if (is_object($args)) {
        $args = (array) $args;
      }

      $this->assignProperties($args);
    }

    $this->generatePrefixAndId();

    add_action('save_post', array( $this, 'saveMetaBoxData' ));
  }

  public function addMetaBox() {
    add_meta_box($this->id, $this->title, array( $this, 'render' ), $this->page, $this->context, $this->priority);
  }

  

  /**
   * Creates a new field for the custom meta box
   * @param string $name the name of the field
   * @param string $id the id of the field
   * @param string $type the input element type e.g. text, radio, checkbox etc
   * @param string $description 
   * @param string $std sample or default values
   * @param array $attrs Extra attributes like classes, onclicks etc
   * @param array $options options array for selects and alike
   */
  public function createField($name, $id, $type, $description = '', $std = null, $attrs = array( ), $options = array()) {

    if ($attrs) {
      // walk through and clean these please
      $attributes = $attrs;
    }

    $this->fields[] = array(
        'name' => $name,
        'id' => $this->prefix . $id,
        'type' => $type,
        'description' => $description,
        'std' => $std,
        'options' => $options,
        'attr' => $attributes );
  }

  public function render($post) {
    // Use nonce for verification
    echo '<input type="hidden" name="bluebook_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($this->fields as $field) {
      // get current post meta data
      $meta = get_post_meta($post->ID, $field['id'], true);
   
      $attributes = false;
      // check for attributes as k=> pair
      if (!empty($field['attr'])) {
        $attributes = $field['attr'];
        if (is_array($field['attr'])) {
          $attributes = null;
          foreach ($field['attr'] as $k => $v) {
            $attributes .= $k . ' ="' . $v . '" ';
          }
        }
      }
      echo '<tr>',
      '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
      '<td>';
      switch ( $field['type'] ) {
        case 'text':
          echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" ' . $attributes . ' />', '<br />', $field['desc'];
          break;
        case 'textarea':
          echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%" ' . $attributes . ' >', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
          break;
        case 'select':
          echo '<select name="', $field['id'], '" id="', $field['id'], '" ' . $attributes . ' >';
          foreach ($field['options'] as $option) {
            echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
          }
          echo '</select>';
          break;
        case 'radio':
          foreach ($field['options'] as $option) {
            echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', $attributes . '  />', $option['name'];
          }
          break;
        case 'checkbox':
          echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', $attributes . '  />';
          break;
      }
      echo '<td>',
      '</tr>';
    }

    echo '</table>';
  }

  /**
   * Saves the custom post meta data
   * @global array $wp_meta_boxes
   * @param int $post_id
   * @param array $fields
   * @return type 
   */
  private function save($post_id, $fields) {

    // verify nonce
    if (!wp_verify_nonce($_POST['bluebook_meta_box_nonce'], basename(__FILE__))) {
      return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
      if (!current_user_can('edit_page', $post_id)) {
        return $post_id;
      }
    }
    elseif (!current_user_can('edit_post', $post_id)) {
      return $post_id;
    }


    //$screen = convert_to_screen( $screen );
    if (is_array($fields) && !empty($fields)) {
      foreach ($fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
          update_post_meta($post_id, $field['id'], $new);
        }
        elseif ('' == $new && $old) {
          delete_post_meta($post_id, $field['id'], $old);
        }
      }
    }
  }
  
  /**
   * Saves the meta box data
   * @global array $wp_meta_boxes
   * @param type $post_id
   * @return type 
   */
  public function saveMetaBoxData($post_id) {

    $post_type = get_post_type($post_id);
    
    if ($this->page != $post_type) {
      return;
    }

    global $wp_meta_boxes;
    if (is_array($wp_meta_boxes)) {
      foreach ($wp_meta_boxes as $key => $meta_box) {
        if ($key == $post_type) {
          $fields = $meta_box[$this->context][$this->priority][$this->id]['callback'][0]->fields;
          $this->save($post_id, $fields);
        }
      }
    }
  } //saveMetaBoxData

  /**
   * auto generate the id and prefix for this meta box
   */
  private function generatePrefixAndId() {
    if (empty($this->id)) {
      $this->id = $this->page . '-meta';
    }

    $this->prefix = str_replace('-', '_', $this->page) . '_';
  }

}

?>
