<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Swank Theme' );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/swank/' );
define( 'CHILD_THEME_VERSION', '1.0.0' );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'swank_enqueue_scripts' );
function swank_enqueue_scripts() {

	wp_enqueue_script( 'swank-responsive-menu', get_stylesheet_directory_uri() . '/lib/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true ); 
	wp_enqueue_style( 'swank-google-fonts', '//fonts.googleapis.com/css?family=Old+Standard+TT:400,400italic,700|Montserrat:400,700', array(), CHILD_THEME_VERSION );

}

//* Add support for custom header
/* Friday Next Update header size (640x200 original) */
add_theme_support( 'custom-header', array(
	'width'           => 640,
	'height'          => 382,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 2-column footer widgets - Friday Next Updated to 4
add_theme_support( 'genesis-footer-widgets', 4 );

//* Add new image sizes 
add_image_size( 'circles', 200, 200, TRUE );
add_image_size( 'portfolio-featured', 300, 200, TRUE );
add_image_size( 'sidebar', 290, 150, TRUE );

//* Add Top Bar Above Header
add_action( 'genesis_before_header', 'swank_top_bar' );
function swank_top_bar() {
 
	echo '<div class="top-bar"><div class="wrap">';
 
	genesis_widget_area( 'top-bar-left', array(
		'before' => '<div class="top-bar-left">',
		'after' => '</div>',
	) );

	genesis_widget_area( 'top-bar-right', array(
		'before' => '<div class="top-bar-right">',
		'after' => '</div>',
	) );
 
	echo '</div></div>';
 
}

//* Remove the entry meta in the entry footer
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

//* Customize the entry meta in the entry header
add_filter( 'genesis_post_info', 'swank_post_info_filter' );
function swank_post_info_filter($post_info) {

	$post_info = '[post_date] by [post_author_posts_link] [post_categories] [post_comments]';
	return $post_info;

}

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_before_footer', 'genesis_do_subnav' );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'swank_secondary_menu_args' );
function swank_secondary_menu_args( $args ){

	if( 'secondary' != $args['theme_location'] )
	return $args;

	$args['depth'] = 1;
	return $args;
}

//* Change Avatar Size
add_filter( 'genesis_comment_list_args', 'swank_comment_list_args' );
function swank_comment_list_args( $args ) {

	return array( 'type' => 'comment', 'avatar_size' => 100, 'callback' => 'genesis_comment_callback' );

}

//* Add Support for Comment Numbering
add_action ('genesis_before_comment', 'afn_numbered_comments');
function afn_numbered_comments () {

    if (function_exists('gtcn_comment_numbering'))
    echo gtcn_comment_numbering($comment->comment_ID, $args);

}

//* Change the number of portfolio items to be displayed (props Bill Erickson) 
add_action( 'pre_get_posts', 'swank_portfolio_items' );
function swank_portfolio_items( $query ) {

	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', '12' );
	}

}

//* Create portfolio custom post type 
add_action( 'init', 'portfolio_post_type' );
function portfolio_post_type() {
    register_post_type( 'portfolio',
        array(
            'labels' => array(
                'name' => __( 'Portfolio' ),
                'singular_name' => __( 'Portfolio' ),
            ),
            'exclude_from_search' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'public' => true,
            'rewrite' => array( 'slug' => 'portfolio' ),
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'genesis-seo' ),
        )
    );
}

//* Customize the credits 
add_filter('genesis_footer_creds_text', 'swank_footer_creds_filter');
function swank_footer_creds_filter( $creds ) {

    $creds = 'Copyright [footer_copyright] &middot; <a href="http://isessa.com/">ISES San Antonio Chapter</a>';
    return $creds;

}

//* Register Widget Areas
genesis_register_sidebar( array(
	'id'          => 'top-bar-left',
	'name'        => __( 'Top Bar Left', 'swank' ),
	'description' => __( 'This is the left side of your top bar.', 'swank' ),
) );

genesis_register_sidebar( array(
	'id'          => 'top-bar-right',
	'name'        => __( 'Top Bar Right', 'swank-' ),
	'description' => __( 'This is the right side of your top bar.', 'swank' ),
) );

genesis_register_sidebar( array(
    'id'          => 'portfolioblurb',
    'name'        => __( 'Portfolio Blurb', 'swank' ),
    'description' => __( 'This is a widget area that can be shown above your portfolio', 'swank' ),
) );

genesis_register_sidebar( array(
	'id'         => 'home-slider',
	'name'       => __( 'Home Page Slider Widget', 'swank' ),
	'description' => __( 'This is the slider widget on your home page', 'swank' ),
) );

genesis_register_sidebar( array(
	'id'          => 'featured-circles',
	'name'        => __( 'Home Page Featured Post Circles', 'swank' ),
	'description' => __( 'This is the top section of your home page', 'swank' ),
) );

genesis_register_sidebar( array(
	'id'          => 'home-featured-area',
	'name'        => __( 'Home Featured Widget Area', 'swank' ),
	'description' => __( 'This is the featured posts section of your home page.', 'swank' ),
) );

/* Addition by Friday Next */
add_filter('widget_text', 'do_shortcode');

/* Change Header */
remove_action('genesis_header', 'genesis_do_header');
remove_action('genesis_header', 'genesis_header_markup_open', 5);
remove_action('genesis_header', 'genesis_header_markup_close', 15);
function custom_header() {
	?>
	<header class="site-header" role="banner" itemscope="itemscope" itemtype="http://schema.org/WPHeader"><div class="wrap"><div class="title-area"><p class="site-title" itemprop="headline"><img src="https://antonianawards.com/wp-content/uploads/2015/04/cropped-Antonian-Awards-Logo.png" class="logo-image" /><a href="<?php echo get_bloginfo('url'); ?>"><?php echo get_bloginfo('name'); ?></a></p><p class="site-description" itemprop="description"><?php echo get_bloginfo('description'); ?></p></div></div></header>
	<?php
}
add_action('genesis_header', 'custom_header');

/* Change Upload Path of Files */
/*
add_filter( 'gform_upload_path', 'change_upload_path', 10, 2 );
function change_upload_path( $path_info, $form_id ) {
    $name = rgpost( 'input_1_3' ) . '_' . rgpost( 'input_1_6' );
	$date = getdate();
	GFCommon::log_debug( __METHOD__ . '(): date => ' . print_r( $date, true ) );
    	$path_info['path'] = '/home/antonianawards/public_html/wp-content/uploads/entry-submissions/' . $name . '-' . $date['mon'] . '_' . $date['mday'] . '_' . $date['year'] . '_' . $date['hours'] . $date['minutes'] . $date['seconds'] . '/';
    	$path_info['url']  = 'https://antonianawards.com/wp-content/uploads/entry-submissions/' . $name . '-' . $date['mon'] . '_' . $date['mday'] . '_' . $date['year'] . '/';
    
	return $path_info;
}
*/
/* Change name of uploaded files */
/**
 * Gravity Wiz // Gravity Forms // Rename Uploaded Files
 *
 * Rename uploaded files for Gravity Forms. You can create a static naming template or using merge tags to base names on user input.
 *
 * Features:
 *  + supports single and multi-file upload fields
 *  + flexible naming template with support for static and dynamic values via GF merge tags
 *
 * Uses:
 *  + add a prefix or suffix to file uploads
 *  + include identifying submitted data in the file name like the user's first and last name
 *
 * @version	  1.2
 * @author    David Smith <david@gravitywiz.com>
 * @license   GPL-2.0+
 * @link      http://gravitywiz.com/...
 */
/*
class GW_Rename_Uploaded_Files {

    public function __construct( $args = array() ) {

        // set our default arguments, parse against the provided arguments, and store for use throughout the class
        $this->_args = wp_parse_args( $args, array(
            'form_id'  => false,
            'field_id' => false,
	        'template' => ''
        ) );

        // do version check in the init to make sure if GF is going to be loaded, it is already loaded
        add_action( 'init', array( $this, 'init' ) );

    }

    public function init() {

        // make sure we're running the required minimum version of Gravity Forms
        if( ! property_exists( 'GFCommon', 'version' ) || ! version_compare( GFCommon::$version, '1.8', '>=' ) ) {
            return;
        }

	    add_action( 'gform_pre_submission', array( $this, 'rename_uploaded_files' ) );

    }

	function rename_uploaded_files( $form ) {

		if( ! $this->is_applicable_form( $form ) ) {
			return;
		}

		foreach( $form['fields'] as &$field ) {

			if( ! $this->is_applicable_field( $field ) ) {
				continue;
			}

			$is_multi_file  = rgar( $field, 'multipleFiles' ) == true;
			$input_name     = sprintf( 'input_%s', $field['id'] );
			$uploaded_files = rgars( GFFormsModel::$uploaded_files, "{$form['id']}/{$input_name}" );

			if( $is_multi_file && ! empty( $uploaded_files ) && is_array( $uploaded_files ) ) {

				foreach( $uploaded_files as &$file ) {
					$file['uploaded_filename'] = $this->rename_file( $file['uploaded_filename'] );
				}

				GFFormsModel::$uploaded_files[ $form['id'] ][ $input_name ] = $uploaded_files;

			} else {

				if( empty( $uploaded_files ) ) {

					$uploaded_files = rgar( $_FILES, $input_name );
					if( empty( $uploaded_files ) || empty( $uploaded_files['name'] ) ) {
						continue;
					}

					$uploaded_files['name'] = $this->rename_file( $uploaded_files['name'] );
					$_FILES[ $input_name ] = $uploaded_files;

				} else {

					$uploaded_files = $this->rename_file( $uploaded_files );
					GFFormsModel::$uploaded_files[ $form['id'] ][ $input_name ] = $uploaded_files;

				}

			}

		}

	}

	function rename_file( $filename ) {

		$file_info = pathinfo( $filename );
		$new_filename = $this->remove_slashes( $this->get_template_value( $this->_args['template'], GFFormsModel::get_current_lead(), $file_info['filename'] ) );

		return sprintf( '%s.%s', $new_filename, rgar( $file_info, 'extension' ) );
	}

	function get_template_value( $template, $entry, $filename ) {

		$form = GFAPI::get_form( $entry['form_id'] );
		$template = GFCommon::replace_variables( $template, $form, $entry, false, true, false, 'text' );

		// replace our custom "{filename}" psuedo-merge-tag
		$template = str_replace( '{filename}', $filename, $template );

		return $template;
	}

	function remove_slashes( $value ) {
		return stripslashes( str_replace( '/', '', $value ) );
	}

	function is_applicable_form( $form ) {

		$form_id = isset( $form['id'] ) ? $form['id'] : $form;

		return $form_id == $this->_args['form_id'];
	}

	function is_applicable_field( $field ) {

		$is_file_upload_field   = in_array( GFFormsModel::get_input_type( $field ), array( 'fileupload', 'post_image' ) );
		$is_applicable_field_id = $this->_args['field_id'] ? $field['id'] == $this->_args['field_id'] : true;

		return $is_file_upload_field && $is_applicable_field_id;
	}

}

# Configuration
new GW_Rename_Uploaded_Files( array(
	'form_id' => 2,
	'field_id' => 19,
	'template' => '01_Representative-{filename}' // most merge tags are supported, original file extension is preserved
) );
new GW_Rename_Uploaded_Files( array(
	'form_id' => 2,
	'field_id' => 20,
	'template' => '02_Representative-{filename}' // most merge tags are supported, original file extension is preserved
) );
new GW_Rename_Uploaded_Files( array(
	'form_id' => 2,
	'field_id' => 36,
	'template' => 'Event_Collateral-{filename}' // most merge tags are supported, original file extension is preserved
) );
new GW_Rename_Uploaded_Files( array(
	'form_id' => 2,
	'field_id' => 33,
	'template' => 'Management_Collateral-{filename}' // most merge tags are supported, original file extension is preserved
) );
new GW_Rename_Uploaded_Files( array(
	'form_id' => 2,
	'field_id' => 141,
	'template' => 'Budget Worksheet-{filename}' // most merge tags are supported, original file extension is preserved
) );
*/

//* Remove the site footer
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );
//* Customize the site footer
add_action( 'genesis_footer', 'bg_custom_footer' );
function bg_custom_footer() { ?>

	<div class="site-footer"><div class="wrap"><p>Copyright &copy; 2016. <a href="http://isessa.com/">ILEA SAN ANTONIO CHAPTER</a></div>

<?php
}

add_filter( 'gform_submit_button_3', 'add_paragraph_below_submit', 10, 2 );
function add_paragraph_below_submit( $button, $form ) {

    return $button .= '<span class="please-wait">Please be patient. It takes a few moments for PayPal to open up. You will need to pay your entry fee through the PayPal portal.</span>';
}

/* End additions by Friday Next */
