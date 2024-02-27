<?php
/**
 * Real Estate main class
 *
 * @author    AyeCode Ltd
 * @package   GeoDir_Real_Estate
 * @version   1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GeoDir_Real_Estate class.
 */
final class GeoDir_Real_Estate {
	/**
	 * The single instance of the class.
	 *
	 * @since 2.0
	 */
	private static $instance = null;

	/**
	 * Save Search Notifications plugin main instance.
	 *
	 * @since 2.0
	 *
	 * @return GeoDir_Real_Estate instance.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof GeoDir_Real_Estate ) ) {
			self::$instance = new GeoDir_Real_Estate;
			//self::$instance->setup_constants();

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			if ( ! class_exists( 'GeoDirectory' ) ) {
				add_action( 'admin_notices', array( self::$instance, 'geodirectory_notice' ) );

				return self::$instance;
			}

			if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
				add_action( 'admin_notices', array( self::$instance, 'php_version_notice' ) );

				return self::$instance;
			}

			self::$instance->includes();
			self::$instance->init_hooks();

			do_action( 'geodir_real_estate_loaded' );
		}

		return self::$instance;
	}

	/**
	 * File includes.
	 *
	 * @return void
	 */
	public function includes(){
		// blocks
		require_once( plugin_dir_path( GEODIR_REAL_ESTATE_PLUGIN_FILE ) . 'includes/blocks/class-geodir-widget-mortgage-calculator.php' );
		require_once( plugin_dir_path( GEODIR_REAL_ESTATE_PLUGIN_FILE ) . 'includes/blocks/class-geodir-widget-energy-rating.php' );
		require_once( plugin_dir_path( GEODIR_REAL_ESTATE_PLUGIN_FILE ) . 'includes/blocks/class-geodir-widget-walk-score.php' );
	}


	/**
	 * Handle actions and filters.
	 *
	 * @since 2.0
	 */
	private function init_hooks() {
		add_filter( 'geodir_get_widgets', array( $this, 'register_widgets' ), 41, 1 );
		add_filter( 'geodir_custom_fields_predefined', array( $this, 'add_predefined_fields' ), 10, 2 );
		add_filter( 'geodir_extra_custom_fields', array( $this, 'add_dummy_data_custom_fields' ), 10, 3 );
	}

	/**
	 * Add dummy data custom fields.
	 *
	 * @param array $fields List of custom fields.
	 * @param string $post_type The post type.
	 * @param string $package_id The package ID.
	 *
	 * @return array List of custom fields with dummy data custom fields added.
	 */
	public function add_dummy_data_custom_fields($fields, $post_type, $package_id) {


		if ( !empty( $_REQUEST['data_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$key = sanitize_key( $_REQUEST['data_type'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$package = ($package_id=='') ? '' : array($package_id);

			if ( 'property_sale' === $key ||  'property_rent' === $key ) {

				// virtual tour field
				$fields[] = array('post_type' => $post_type,
				                  'data_type' => 'TEXT',
				                  'field_type' => 'textarea',
				                  'admin_title' => __('Virtual Tour', 'geodirectory'),
				                  'frontend_desc' => __('Add matterport.com or similar embed code for 360 tours.', 'geodirectory'),
				                  'frontend_title' => __('Virtual Tour', 'geodirectory'),
				                  'htmlvar_name' => 'virtual_tour',
				                  'default_value' => '',
				                  'is_active' => '1',
				                  'option_values' => '',
				                  'is_default' => '0',
				                  'show_in' => '[owntab]',
				                  'show_on_pkg' => $package,
				                  'clabels' => __('Virtual Tour', 'geodirectory'));
			}


		}

		return $fields;
	}

	/**
	 * Add predefined fields for a specific post type.
	 *
	 * @param $custom_fields
	 *
	 * @return array The array of predefined fields.
	 */
	public function add_predefined_fields( $custom_fields ) {

		// Video
		$custom_fields['virtual_tour'] = array( // The key value should be unique and not contain any spaces.
			'field_type'  => 'textarea',
			'class'       => 'gd-virtual-tour',
			'icon'        => 'fas fa-globe',
			'name'        => __( 'Virtual Tour', 'geodirectory' ),
			'description' => __( 'Adds a matterport.com or similar 360 tour embed code input.', 'geodirectory' ),
			'defaults'    => array(
				'data_type'          => 'TEXT',
				'admin_title'        => 'Virtual Tour',
				'frontend_title'     => 'Virtual Tour',
				'frontend_desc'      => 'Add matterport.com or similar embed code for 360 tours',
				'htmlvar_name'       => 'virtual_tour',
				'is_active'          => true,
				'for_admin_use'      => false,
				'default_value'      => '',
				'show_in'            => '[owntab]',
				'is_required'        => false,
				'option_values'      => '',
				'validation_pattern' => '',
				'validation_msg'     => '',
				'required_msg'       => '',
				'field_icon'         => 'fas fa-globe',
				'css_class'          => '',
				'cat_sort'           => false,
				'cat_filter'         => false
			)
		);

		return $custom_fields;
	}


	/**
	 * Loads the plugin language files
	 *
	 * @since 2.0
	 *
	 * @return void
	 */
	public function load_textdomain() {
		// Determines the current locale.
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else if ( function_exists( 'get_user_locale' ) ) {
			$locale = get_user_locale();
		} else {
			$locale = get_locale();
		}

		$locale = apply_filters( 'plugin_locale', $locale, 'real-estate-directory' );

		unload_textdomain( 'real-estate-directory' );
		load_textdomain( 'real-estate-directory', WP_LANG_DIR . '/real-estate-directory/real-estate-directory-' . $locale . '.mo' );
		load_plugin_textdomain( 'real-estate-directory', false, basename( dirname( GEODIR_REAL_ESTATE_PLUGIN_FILE ) ) . '/languages/' );
	}

	/**
	 * Check plugin compatibility and show warning.
	 *
	 * @since 2.0
	 *
	 * @return void
	 */
	public static function geodirectory_notice() {
		if ( ! class_exists( 'GeoDirectory' ) ) {
			echo '<div class="error"><p>' .  esc_attr__( 'GeoDirectory plugin is required for the Save Search Notifications plugin to work properly.', 'real-estate-directory' ) . '</p></div>';
		}
	}


	/**
	 * Register widgets.
	 *
	 * @since 2.0
	 *
	 * @param array $widgets List of GD widgets.
	 * @return array GD widgets.
	 */
	public function register_widgets( $widgets ) {
		if ( geodir_design_style() ) {
			$widgets[] = 'GeoDir_Widget_Mortgage_Calculator';
			$widgets[] = 'GeoDir_Widget_Energy_Rating';
			$widgets[] = 'GeoDir_Widget_Walk_Score';
		}

		return $widgets;
	}
}