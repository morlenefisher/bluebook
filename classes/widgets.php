<?php

class BluebookWidget extends WP_Widget {

    public $name;
    public $post_type;

    public function __construct($name) {
        $this->name = $name;
   
    }

    public function render() {
        
    }

    public function register() {
        register_sidebar_widget($this->name, array($this, 'render'));
    }

    function form($instance) {
        // outputs the options form on admin
    }

    function update($new_instance, $old_instance) {
        // processes widget options to be saved
    }

    function widget($args, $instance) {
       $this->render();
    }

}

/**
 * Related gets posts from the same post type and display's their titles
 */
class BluebookRelatedPostsWidget extends BluebookWidget {

    function __construct($name) {
        
             $widget_ops = array('classname' => 'widget_rss_links', 'description' => 'Related posts widget' );
$this->WP_Widget('bluebook_related', 'Related links', $widget_ops);
parent::__construct($name);
    }
    function render() {
        global $wp_query;

        if (is_single()) {

            $post_type = $wp_query->query_vars['post_type'];
            $args = array(
                'numberposts' => 10,
                'offset' => 0,
                'orderby' => 'rand',
                'order' => 'ASC',
                'post_type' => $post_type,
                'post_status' => 'publish');

            query_posts($args);

            if (have_posts()) :
                ?> <div class="shadow_box">
                    <div class="arch_list"><h3>Ibiza Residents <?php echo ucfirst($post_type); ?></h3>
                        <ul>
                            <?php while (have_posts()) : the_post(); ?>
                                <li>  <a href="<?php the_permalink(); ?>">
                                        <?php the_title() ?>  </a>
                                    <?php endwhile; ?>
                        </ul>
                    </div></div>
                <div class="bottom_arch"> <a href="/<?php echo $post_type; ?>">View All</a> </div>
                <?php
            endif;
        }
    }

}

/**
 * Related The latest post of a certain type and adds it to the sidebar
 */
class BluebookSinglePostWidget extends BluebookWidget {

    function render() {
        global $wp_query;

        if (is_single()) {

            $post_type = $wp_query->query_vars['post_type'];
            $args = array(
                'numberposts' => 1,
                'offset' => 0,
                'order' => 'ASC',
                'post_type' => $this->post_type,
                'post_status' => 'publish');

            query_posts($args);

            if (have_posts()) :
                ?> <div class="shadow_box">
                    <div class="arch_list"><h3>Ibiza Residents <?php echo ucfirst($post_type); ?></h3>
                        <ul>
                            <?php while (have_posts()) : the_post(); ?>
                                <li>  <a href="<?php the_permalink(); ?>">
                                        <?php the_title() ?>  </a>
                                <?php endwhile; ?>
                        </ul>
                    </div></div>
                <div class="bottom_arch"> <a href="/<?php echo $post_type; ?>">View All</a> </div>
                <?php
            endif;
        }
    }

}
?>
