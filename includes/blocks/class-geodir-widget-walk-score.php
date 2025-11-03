<?php
/**
 * Walk Score
 *
 * @author    AyeCode Ltd
 * @package   Real_Estate_Directory
 * @version   1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GeoDir_Widget_Walk_Score class.
 */
class GeoDir_Widget_Walk_Score extends WP_Super_Duper {

	public $arguments;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$options = array(
			'base_id'          => 'gd_walk_score',
			'name'             => __( 'GD > Walk Score', 'real-estate-directory' ),
			'class_name'       => __CLASS__,
			'textdomain'       => GEODIRECTORY_TEXTDOMAIN,
			'block-icon'       => 'fas fa-walking',
			'block-category'   => 'geodirectory',
			'block-supports'   => array(
				'customClassName' => false,
			),
			'block-keywords'   => "['geodir','walk','score','walkscore']",
			'widget_ops'       => array(
				'classname'                   => 'geodir-walk-score-container' . ( geodir_design_style() ? ' bsui' : '' ),
				'description'                 => esc_html__( 'Adds a Walk Score widget that can be used on the listings details page.', 'real-estate-directory' ),
				'customize_selective_refresh' => true,
				'geodirectory'                => true,
			),
			'block_group_tabs' => array(
				'content'  => array(
					'groups' => array(
						__( 'Defaults', 'real-estate-directory' ),
//						__( 'Button Content', 'real-estate-directory' )
					),
					'tab'    => array(
						'title'     => __( 'Content', 'real-estate-directory' ),
						'key'       => 'bs_tab_content',
						'tabs_open' => true,
						'open'      => true,
						'class'     => 'text-center flex-fill d-flex justify-content-center'
					),
				),
				'styles'   => array(
					'groups' => array(
						__( 'Background', 'real-estate-directory' )
					),
					'tab'    => array(
						'title'     => __( 'Styles', 'real-estate-directory' ),
						'key'       => 'bs_tab_styles',
						'tabs_open' => true,
						'open'      => true,
						'class'     => 'text-center flex-fill d-flex justify-content-center'
					)
				),
				'advanced' => array(
					'groups' => array(
						__( 'Wrapper Styles', 'real-estate-directory' ),
						__( 'Advanced', 'real-estate-directory' ),
					),
					'tab'    => array(
						'title'     => __( 'Advanced', 'real-estate-directory' ),
						'key'       => 'bs_tab_advanced',
						'tabs_open' => true,
						'open'      => true,
						'class'     => 'text-center flex-fill d-flex justify-content-center'
					)
				),
			)
		);

		parent::__construct( $options );
	}

	/**
	 * Set widget arguments.
	 */
	public function set_arguments() {
		$arguments = array();

		$arguments['wsid'] = array(
			'type'        => 'text',
			'title'       => __( 'Walk Score ID', 'real-estate-directory' ),
			'placeholder' => '',
			'default'     => '',
			'desc'        => __( 'Get a WSID at', 'real-estate-directory' ) . ' <a target="_blank" href="https://www.walkscore.com/professional/sign-up.php">https://www.walkscore.com</a>',
			'desc_tip'    => true,
			'group'       => __( 'Defaults', 'real-estate-directory' ),
		);

		$arguments['wsid_notice'] = array(
			'type'            => 'notice',
			'desc'            => __( 'A WalkScore.com ID is required for this to work.', 'real-estate-directory' ),
			'status'          => 'error', // 'warning' | 'success' | 'error' | 'info'
			'group'           => __( 'Defaults', 'real-estate-directory' ),
			'element_require' => '[%wsid%]==""',
		);

		$arguments['format'] = array(
			'type'     => 'select',
			'title'    => __( 'Format', 'real-estate-directory'  ),
			'options'  => array(
				''       => __( 'Wide', 'real-estate-directory'  ),
				'tall'   => __( 'Tall', 'real-estate-directory'  ),
				'square' => __( 'Square', 'real-estate-directory'  ),
			),
			'default'  => '',
			'desc_tip' => true,
			'group'    => __( 'Defaults', 'real-estate-directory'  ),
		);

		$arguments['size'] = array(
			'type'     => 'select',
			'title'    => __( 'Size', 'real-estate-directory'  ),
			'options'  => array(
				''       => __( 'Large (width 100%)', 'real-estate-directory'  ),
				'large'  => __( 'Large', 'real-estate-directory'  ),
				'medium' => __( 'Medium', 'real-estate-directory'  ),
				'small'  => __( 'Small', 'real-estate-directory'  ),
				'tiny'   => __( 'Tiny', 'real-estate-directory'  ),
				'custom' => __( 'custom', 'real-estate-directory'  ),
			),
			'default'  => '',
			'desc_tip' => true,
			'group'    => __( 'Defaults', 'real-estate-directory'  ),
		);

		$arguments['size_height'] = array(
			'type'            => 'text',
			'title'           => __( 'height', 'real-estate-directory' ),
			'placeholder'     => '525',
//			'desc'            => __( 'Leave blank to use country default (help with number formatting)', 'real-estate-directory' ),
			'default'         => '',
			'desc_tip'        => true,
			'group'           => __( 'Defaults', 'real-estate-directory' ),
			'element_require' => '[%size%]=="custom"',
		);

		$arguments['size_width'] = array(
			'type'            => 'text',
			'title'           => __( 'Width', 'real-estate-directory' ),
			'placeholder'     => '100% or 565',
//			'desc'            => __( 'Leave blank to use country default (help with number formatting)', 'real-estate-directory' ),
			'default'         => '',
			'desc_tip'        => true,
			'group'           => __( 'Defaults', 'real-estate-directory' ),
			'element_require' => '[%size%]=="custom"',
		);


		$arguments = $arguments + sd_get_background_inputs( 'bg' );


		// margins mobile
		$arguments['mt'] = sd_get_margin_input( 'mt', array( 'device_type' => 'Mobile' ) );
		$arguments['mr'] = sd_get_margin_input( 'mr', array( 'device_type' => 'Mobile' ) );
		$arguments['mb'] = sd_get_margin_input( 'mb', array( 'device_type' => 'Mobile' ) );
		$arguments['ml'] = sd_get_margin_input( 'ml', array( 'device_type' => 'Mobile' ) );

		// margins tablet
		$arguments['mt_md'] = sd_get_margin_input( 'mt', array( 'device_type' => 'Tablet' ) );
		$arguments['mr_md'] = sd_get_margin_input( 'mr', array( 'device_type' => 'Tablet' ) );
		$arguments['mb_md'] = sd_get_margin_input( 'mb', array( 'device_type' => 'Tablet' ) );
		$arguments['ml_md'] = sd_get_margin_input( 'ml', array( 'device_type' => 'Tablet' ) );

		// margins desktop
		$arguments['mt_lg'] = sd_get_margin_input( 'mt', array( 'device_type' => 'Desktop' ) );
		$arguments['mr_lg'] = sd_get_margin_input( 'mr', array( 'device_type' => 'Desktop' ) );
		$arguments['mb_lg'] = sd_get_margin_input( 'mb', array( 'device_type' => 'Desktop' ) );
		$arguments['ml_lg'] = sd_get_margin_input( 'ml', array( 'device_type' => 'Desktop' ) );

		// padding
		$arguments['pt'] = sd_get_padding_input( 'pt', array( 'device_type' => 'Mobile' ) );
		$arguments['pr'] = sd_get_padding_input( 'pr', array( 'device_type' => 'Mobile' ) );
		$arguments['pb'] = sd_get_padding_input( 'pb', array( 'device_type' => 'Mobile' ) );
		$arguments['pl'] = sd_get_padding_input( 'pl', array( 'device_type' => 'Mobile' ) );

		// padding tablet
		$arguments['pt_md'] = sd_get_padding_input( 'pt', array( 'device_type' => 'Tablet' ) );
		$arguments['pr_md'] = sd_get_padding_input( 'pr', array( 'device_type' => 'Tablet' ) );
		$arguments['pb_md'] = sd_get_padding_input( 'pb', array( 'device_type' => 'Tablet' ) );
		$arguments['pl_md'] = sd_get_padding_input( 'pl', array( 'device_type' => 'Tablet' ) );

		// padding desktop
		$arguments['pt_lg'] = sd_get_padding_input( 'pt', array( 'device_type' => 'Desktop' ) );
		$arguments['pr_lg'] = sd_get_padding_input( 'pr', array( 'device_type' => 'Desktop' ) );
		$arguments['pb_lg'] = sd_get_padding_input( 'pb', array( 'device_type' => 'Desktop' ) );
		$arguments['pl_lg'] = sd_get_padding_input( 'pl', array( 'device_type' => 'Desktop' ) );

		// border
		$arguments['border']       = sd_get_border_input( 'border' );
		$arguments['rounded']      = sd_get_border_input( 'rounded' );
		$arguments['rounded_size'] = sd_get_border_input( 'rounded_size' );

		// shadow
		$arguments['shadow'] = sd_get_shadow_input( 'shadow' );

		$arguments['css_class'] = sd_get_class_input();

		return $arguments;
	}

	/**
	 * Outputs the save search on the front-end.
	 *
	 * @param array $instance Settings for the widget instance.
	 * @param array $args Display arguments.
	 * @param string $content
	 *
	 * @return mixed|string|void
	 */
	public function output( $instance = array(), $args = array(), $content = '' ) {

		wp_enqueue_script( 'redir-walk-score-js', plugin_dir_url( REAL_ESTATE_DIRECTORY_PLUGIN_FILE ) . 'assets/js/walk-score.min.js', array(), REAL_ESTATE_DIRECTORY_VERSION, true );

		wp_add_inline_script( 'redir-walk-score-js', $this->output_js( $instance, $args ), 'before' );

		$wrap_class = sd_build_aui_class( $instance );

		$styles = sd_build_aui_styles( $instance );
		$style  = $styles ? ' style="' . $styles . '"' : '';


		$css_for_preview = $this->is_preview() ? '#ws-walkscore-tile{ pointer-events: none; }' : '';
		ob_start();
		?>
        <div class="<?php echo esc_attr( $wrap_class ); ?>" <?php echo esc_attr( $style ); ?>>
            <div id='ws-walkscore-tile'></div>
        </div>
        <style onload="<?php
		echo $this->output_js( $instance, $args );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --  This is used in multiple contexts and requires manipulation between contexts, it is full escaped in function.
        ?>">#ws-walkscore-tile {
                position: relative;
                text-align: left
            }

            #ws-walkscore-tile * {
                float: none;
            }<?php
            echo esc_attr( $css_for_preview );
            ?></style>
		<?php

		return ob_get_clean();

	}


	/**
	 * Output the required JS.
	 *
	 * @param $instance
	 * @param $args
	 *
	 * @return array|false|string|string[]
	 */
	public function output_js( $instance, $args ) {
		global $gd_post;

		$width  = '100%';
		$height = '525';

		$wsid        = ! empty( $instance['wsid'] ) ? esc_attr( $instance['wsid'] ) : '';
		$format      = ! empty( $instance['format'] ) ? esc_attr( $instance['format'] ) : 'wide';
		$size        = ! empty( $instance['size'] ) ? esc_attr( $instance['size'] ) : '';
		$size_width  = ! empty( $instance['size_width'] ) ? esc_attr( $instance['size_width'] ) : '100%';
		$size_height = ! empty( $instance['size_height'] ) ? esc_attr( $instance['size_height'] ) : '525';

		$sizes = array(
			'wide'   => array(
				'large'  => array( 'w' => '690', 'h' => '525' ),
				'medium' => array( 'w' => '550', 'h' => '350' ),
				'small'  => array( 'w' => '400', 'h' => '320' ),
				'tiny'   => array( 'w' => '300', 'h' => '200' ),
			),
			'tall'   => array(
				'large'  => array( 'w' => '420', 'h' => '615' ),
				'medium' => array( 'w' => '400', 'h' => '500' ),
				'small'  => array( 'w' => '350', 'h' => '400' ),
				'tiny'   => array( 'w' => '300', 'h' => '350' ),
			),
			'square' => array(
				'large'  => array( 'w' => '620', 'h' => '620' ),
				'medium' => array( 'w' => '500', 'h' => '500' ),
				'small'  => array( 'w' => '400', 'h' => '400' ),
				'tiny'   => array( 'w' => '300', 'h' => '300' ),
			),
		);

		if ( $size == 'custom' ) {
			$height = $size_height;
			$width  = $size_width;
		} elseif ( ! empty( $sizes[ $format ][ $size ] ) ) {
			$height = $sizes[ $format ][ $size ]['h'];
			$width  = $sizes[ $format ][ $size ]['w'];
		}

		$address_parts = array(
			'street'  => ! empty( $gd_post->street ) ? esc_attr( $gd_post->street ) : '',
			'city'    => ! empty( $gd_post->city ) ? esc_attr( $gd_post->city ) : '',
			'region'  => ! empty( $gd_post->region ) ? esc_attr( $gd_post->region ) : '',
			'country' => ! empty( $gd_post->country ) ? esc_attr( $gd_post->country ) : '',
			'zip'     => ! empty( $gd_post->zip ) ? esc_attr( $gd_post->zip ) : '',
		);

		$address_arr = [];
		if ( ! empty( $address_parts ) ) {
			foreach ( $address_parts as $part ) {
				if ( ! empty( $part ) ) {
					$address_arr[] = $part;
				}
			}
		}

		$address = ! empty( $address_arr ) ? implode( ',', $address_arr ) : '';

		if ( empty( $address ) && $this->is_preview() ) {
			$address = '1060 Lombard Street, San Francisco, CA';
		}
		ob_start();
		?>
        <script>
            var ws_wsid = '<?php echo esc_js( $wsid ); ?>';
            var ws_address = '<?php echo esc_js( $address ); ?>';
            var ws_format = '<?php echo esc_js( $format ); ?>';
            var ws_width = '<?php echo esc_js( $width ); ?>';
            var ws_height = '<?php echo esc_js( $height ); ?>';
        </script>

		<?php

		$js = ob_get_clean();

		// JS for block preview, we have to be creative as "<script>" is not called here
		if ($this->is_preview()) {
			$js = str_replace( array( "var ", "\r", "\n" ), '', $js );
			$js .= "var script = document.createElement('script');script.src = '" . esc_url( plugin_dir_url( REAL_ESTATE_DIRECTORY_PLUGIN_FILE ) ) . 'assets/js/walk-score.js' . "';document.head.appendChild(script);";
		}

		return str_replace( array( '<script>', '</script>' ), '', $js );
	}


}












