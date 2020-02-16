<?php
/**
 * Understrap functions and definitions
 *
 * @package understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$understrap_includes = array(
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/jetpack.php',                         // Load Jetpack compatibility file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker.
	'/woocommerce.php',                     // Load WooCommerce functions.
	'/editor.php',                          // Load Editor functions.
	'/deprecated.php',                      // Load deprecated functions.
);

foreach ( $understrap_includes as $file ) {
	$filepath = locate_template( 'inc' . $file );
	if ( ! $filepath ) {
		trigger_error( sprintf( 'Error locating /inc%s for inclusion', $file ), E_USER_ERROR );
	}
	require_once $filepath;
}
add_filter( 'widget_text', 'do_shortcode' );

/**
 * Register our sidebars and widgetized areas.
 *
 */
function arphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'Home posts display',
		'id'            => 'home_posts',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );

/* add_theme_support( 'post-thumbnails' );  //Adds thumbnails compatibility to the theme 
set_post_thumbnail_size( 286, 180, true ); // Sets the Post Main Thumbnails 
add_image_size( 'delicious-recent-thumbnails', 286, 180, true ); // Sets Recent Posts Thumbnails  */

function delicious_recent_posts() {
    $del_recent_posts = new WP_Query();
    $del_recent_posts->query('showposts=3');
        while ($del_recent_posts->have_posts()) : $del_recent_posts->the_post(); ?>
            <div class="col-md-4 col-sm-6 col-12">
			
				<div class="card">
					<a href="<?php esc_url(the_permalink()); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
					<div class="card-body">
						<h5 class="card-title"> <?php esc_html(the_title()); ?></h5>
						<p><?php esc_html(the_excerpt()); ?> </p>
					<!--  <a class="btn btn-primary" href="<php esc_url(the_permalink()); ?>">Read more</a> -->
					</div>
				</div>
            </div>
        <?php endwhile;
    wp_reset_postdata();
}

function news_posts() {
    $news_recent_posts = new WP_Query();
    $news_recent_posts->query('showposts=12');
        while ($news_recent_posts->have_posts()) : $news_recent_posts->the_post(); ?>
            <div class="col-md-4 col-sm-6 col-12">
			
				<div class="card">
					<a href="<?php esc_url(the_permalink()); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
					<div class="card-body">
						<h5 class="card-title"> <?php esc_html(the_title()); ?></h5>
						<p><?php esc_html(the_excerpt()); ?> </p>
					<!--  <a class="btn btn-primary" href="<php esc_url(the_permalink()); ?>">Read more</a> -->
					</div>
				</div>
            </div>
        <?php endwhile;
    wp_reset_postdata();
}
//Subscribe newsletter widget 
// Register and load the widget
function snl_load_widget() {
    register_widget( 'snl_widget' );
}
add_action( 'widgets_init', 'snl_load_widget' );
 
// Creating the widget 
class snl_widget extends WP_Widget {
 
	function __construct() {
	parent::__construct(
	
	// Base ID of your widget
	'snl_widget', 
	
	// Widget name will appear in UI
	__('Subscribe newsletter Widget', 'snl_widget_domain'), 
	
	// Widget description
	array( 'description' => __( 'Subscribe to the newsletter widget', 'snl_widget_domain' ), ) 
	);
}
 
// Creating widget front-end
 
public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );
	
	// before and after widget arguments are defined by themes
	echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $args['after_title']; ?>
		
		<div class="container-fluid newsletter-container">
			<div class="row">
				<h3><?php echo $title ?></h3>
				<div class="col-md-6 mx-auto">
					<div class="d-flex justify-content-center">
						<input name="newsletter" class="subscribe">
						<button class="btn ">Subscribe</button>
					</div>
				</div>
			</div>
		</div>

		<?php
		echo $args['after_widget'];
	}
         
	// Widget Backend 
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
	}
		else {
			$title = __( 'New title', 'snl_widget_domain' );
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class wpb_widget ends here