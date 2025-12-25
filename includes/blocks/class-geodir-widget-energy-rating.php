<?php
/**
 * EPC Rating Chart
 *
 * @author    AyeCode Ltd
 * @package   Real_Estate_Directory
 * @version   1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GeoDir_Widget_Energy_Rating class.
 */
class GeoDir_Widget_Energy_Rating extends WP_Super_Duper {

	public $arguments;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$options = array(
			'base_id'        => 'gd_energy_rating',
			'name'           => __( 'GD > Energy Rating Chart', 'real-estate-directory' ),
			'class_name'     => __CLASS__,
			'textdomain'     => GEODIRECTORY_TEXTDOMAIN,
			'block-icon'     => 'fas fa-chart-bar',
			'block-category' => 'geodirectory',
			'block-supports' => array(
				'customClassName' => false,
			),
			'block-keywords' => "['geodir','epc','rating']",
			'widget_ops'     => array(
				'classname'                   => 'geodir-epc-rating-container ' . geodir_bsui_class(),
				'description'                 => esc_html__( 'Adds an EPC rating chart for real estate.', 'real-estate-directory' ),
				'customize_selective_refresh' => true,
				'geodirectory'                => true,
			),
		);

		parent::__construct( $options );
	}

	/**
	 * Set widget arguments.
	 */
	public function set_arguments() {
		$arguments = array();

		$arguments['type'] = array(
			'type'     => 'select',
			'title'    => __( 'Rating Type', 'real-estate-directory'  ),
			'options'  => array(
				''     => __( 'Auto (use listing location)', 'real-estate-directory' ),
				'epc'  => __( 'EPC Rating (European Union / UK)', 'real-estate-directory' ),
				'hers' => __( 'HERS (United States)', 'real-estate-directory' ),
				'dpe'  => __( 'DPE Energy (France)', 'real-estate-directory' ),
				'dpe_c' => __( 'DPE Climate (France)', 'real-estate-directory' )
			),
			'default'  => '',
			'desc_tip' => true,
			'group'    => __( 'Defaults', 'real-estate-directory'  ),
		);

		$arguments['epc_rating'] = array(
			'type'            => 'text',
			'title'           => __( 'EPC Rating', 'real-estate-directory' ),
			'placeholder'     => '0-100',
			'desc'            => __( 'Enter EPC rating (0 to 100) or leave blank to use the post `energy_rating` field', 'real-estate-directory' ),
			'default'         => '',
			'desc_tip'        => true,
			'group'           => __( 'Defaults', 'real-estate-directory' ),
			'element_require' => '[%type%]=="epc"',
		);

		$arguments['hers_rating'] = array(
			'type'            => 'text',
			'title'           => __( 'HERS Rating', 'real-estate-directory' ),
			'placeholder'     => '0-150',
			'desc'            => __( 'Enter HERS rating (0 to 150) or leave blank to use the post `energy_rating` field', 'real-estate-directory' ),
			'default'         => '',
			'desc_tip'        => true,
			'group'           => __( 'Defaults', 'real-estate-directory' ),
			'element_require' => '[%type%]=="hers"',
		);

		$arguments['dpe_value'] = array(
			'type'            => 'text',
			'title'           => __( 'DPE Energy Consumption', 'real-estate-directory' ),
			'placeholder'     => '0-420+',
			'desc'            => __( 'Enter DPE energy consumption (0 to 420+) or leave blank to use the post `energy_rating` field', 'real-estate-directory' ),
			'default'         => '',
			'desc_tip'        => true,
			'group'           => __( 'Defaults', 'real-estate-directory' ),
			'element_require' => '[%type%]=="dpe"',
		);

		$arguments['dpe_c_value'] = array(
			'type'            => 'text',
			'title'           => __( 'DPE CO2 Emissions', 'real-estate-directory' ),
			'placeholder'     => '0-100+',
			'desc'            => __( 'Enter DPE CO2 Emissions (0 to 100+) or leave blank to use the post `energy_rating` field', 'real-estate-directory' ),
			'default'         => '',
			'desc_tip'        => true,
			'group'           => __( 'Defaults', 'real-estate-directory' ),
			'element_require' => '[%type%]=="dpe_c"',
		);

		$arguments['css_class'] = sd_get_class_input();

		return $arguments;
	}

	/**
	 * Outputs the EPC Rating Chart on the front-end.
	 *
	 * @param array $instance Settings for the widget instance.
	 * @param array $args Display arguments.
	 * @param string $content
	 *
	 * @return mixed|string|void
	 */
	public function output( $instance = array(), $args = array(), $content = '' ) {
		global $gd_post;

		$_type = ! empty( $instance['type'] ) ? esc_attr( $instance['type'] ) : 'auto';

		if ( in_array( $_type, array( 'dpe', 'dpe_c', 'epc', 'hers' ) ) ) {
			$type = $_type;
		} elseif ( ! empty( $gd_post->country ) ) {
			if ( $gd_post->country == 'United States' ) {
				$type = 'hers';
			} elseif ( $gd_post->country == 'France' ) {
				$type = 'dpe';
			} else {
				$type = 'epc';
			}
		} else {
			$type = 'epc';
		}

		$block_preview = $this->is_block_content_call();

		if ( $type == 'dpe' ) {
			// DPE Energy
			$rating = ! empty( $instance['dpe_value'] ) ? absint( $instance['dpe_value'] ) : 0;

			if ( empty( $rating ) ) {
				if ( isset( $gd_post->energy_rating ) ) {
					$rating = absint( $gd_post->energy_rating );
				} else {
					if ( ! $block_preview ) {
						return;
					}

					$rating = 210;
				}
			}

			return $this->output_dpe( $rating );
		} elseif ( $type == 'dpe_c' ) {
			// DPE Climate
			$rating = ! empty( $instance['dpe_c_value'] ) ? absint( $instance['dpe_c_value'] ) : 0;

			if ( empty( $rating ) ) {
				if ( isset( $gd_post->energy_rating ) ) {
					$rating = absint( $gd_post->energy_rating );
				} else {
					if ( ! $block_preview ) {
						return;
					}

					$rating = 50;
				}
			}

			return $this->output_dpe_climate( $rating );
		} elseif ( $type == 'hers' ) {
			// HERS
			$rating = ! empty( $instance['hers_rating'] ) ? absint( $instance['hers_rating'] ) : 0;

			if ( empty( $rating ) ) {
				if ( isset( $gd_post->energy_rating ) ) {
					$rating = absint( $gd_post->energy_rating );
				} else {
					if ( ! $block_preview ) {
						return;
					}

					$rating = 100;
				}
			}

			return $this->output_hers( $rating );
		} else {
			// EPC
			$rating = ! empty( $instance['epc_rating'] ) ? absint( $instance['epc_rating'] ) : 0;

			if ( empty( $rating ) ) {
				if ( isset( $gd_post->energy_rating ) ) {
					$rating = absint( $gd_post->energy_rating );
				} else {
					if ( ! $block_preview ) {
						return;
					}

					$rating = 50;
				}
			}

			return $this->output_epc( $rating );
		}
	}

	public function output_epc( $epc_rating ) {
		// Define rating category widths and colors
		$rating_categories = array(
			'A' => array( 'width' => '14.285714286%', 'color' => '#0b7735' ),
			'B' => array( 'width' => '14.285714286%', 'color' => '#3ca937' ),
			'C' => array( 'width' => '14.285714286%', 'color' => '#85ba34' ),
			'D' => array( 'width' => '14.285714286%', 'color' => '#ffdf09' ),
			'E' => array( 'width' => '14.285714286%', 'color' => '#ef9124' ),
			'F' => array( 'width' => '14.285714286%', 'color' => '#ea402e' ),
			'G' => array( 'width' => '14.285714286%', 'color' => '#d51216' )
		);

		// The marker's left position will be the EPC rating
		$marker_left = $this->sapPointToPercentage( $epc_rating ) . '%';

		// translators: The DPE rating value.
		$title = wp_sprintf( _x( 'EPC Rating: %d', 'EPC rating value', 'real-estate-directory' ), $epc_rating );

		$output = '<div class="position-relative z-index-1">';
		$output .= '<div id="marker" class="c-pointer position-absolute top-50 translate-middle-y rounded" style="right:' . esc_attr( $marker_left ) . ';background:#000000a3;width:10px;height:45px;top:15px!important;margin-right:-10px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="' . esc_attr( $title ) . '"></div>';
		$output .= '</div>';

		$output .= '<div class="progress position-relative" style="height: 30px;">';

		foreach ( $rating_categories as $category => $data ) {
			$output .= '<div class="progress-bar" role="progressbar" style="width: ' . esc_attr( $data['width'] ) . ';background:' . esc_attr( $data['color'] ) . ' !important;" aria-valuenow="' . intval( $data['width'] ) . '" aria-valuemin="0" aria-valuemax="100">' . esc_html( $category ) . '</div>';
		}

		$output .= '</div>';

		return $output;
	}

	/**
	 * Converts SAP point to percentage based on rating bounds.
	 *
	 * @param int $point The SAP point to convert.
	 *
	 * @return float|int Returns the percentage value based on the SAP point.
	 */
	function sapPointToPercentage( $point ) {
		// If the SAP point is above 100, default it to 100
		$point = min( $point, 100 );

		// Define the boundaries for each rating
		$ratingBounds = [
			'A' => [ 92, 100 ],
			'B' => [ 81, 91 ],
			'C' => [ 69, 80 ],
			'D' => [ 55, 68 ],
			'E' => [ 39, 54 ],
			'F' => [ 21, 38 ],
			'G' => [ 1, 20 ],
		];

		$segmentPercentage = 100 / count( $ratingBounds );

		foreach ( $ratingBounds as $bounds ) {
			if ( $point >= $bounds[0] && $point <= $bounds[1] ) {
				// Determine the relative position within the segment
				$positionInSegment = ( $point - $bounds[0] ) / ( $bounds[1] - $bounds[0] );

				// Determine starting percentage of this segment
				$numberOfSegmentsAbove = 6 - array_search( $bounds, array_values( $ratingBounds ) );
				$startPercentage       = $numberOfSegmentsAbove * $segmentPercentage;

				// Calculate the final percentage
				return $startPercentage + ( $positionInSegment * $segmentPercentage );
			}
		}

		// Return -1 if the SAP point does not fit into any defined range (though with the above condition, this is unlikely)
		return - 1;
	}

	/**
	 * Outputs the HERS Rating Chart on the front-end.
	 *
	 * @param int $rating The HERS rating.
	 *
	 * @return string The HTML output of the HERS Rating Chart.
	 */
	public function output_hers( $rating ) {
		// The marker's left position will be the EPC rating
		$marker_left = round( $rating / 150 * 100, 5 ) . '%';

		// translators: The hers rating value.
		$title = wp_sprintf( __( 'HERS Rating: %s', 'real-estate-directory' ), esc_attr( $rating ) );

		$output = '<div class="position-relative z-index-1">';

		$output .= '<div id="marker" class="c-pointer position-absolute top-50 translate-middle-y rounded" style="left:' . esc_attr( $marker_left ) . ';background:#000000a3;width:10px;height:45px;top:15px!important;margin-left:-3px;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="' . $title . '"></div>';
		$output .= '</div>';

		$common_css = 'margin-left:-7px;padding-bottom:4px;';
		$output     .= '<div class="position-relative ">';
		$output     .= '<div class="progress" style="height: 30px;">';
		$output     .= '<div class="progress-bar" style="width: 100%; background: linear-gradient(90deg, green, yellow, red); min-width: 100%; " ></div>';
		$output     .= '<div class="px-2">';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 0%; writing-mode: vertical-rl; transform: rotate(180deg);">0</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 6.66%; writing-mode: vertical-rl; transform: rotate(180deg);">10</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 13.33%; writing-mode: vertical-rl; transform: rotate(180deg);">20</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 20%; writing-mode: vertical-rl; transform: rotate(180deg);">30</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 26.66%; writing-mode: vertical-rl; transform: rotate(180deg);">40</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 33.33%; writing-mode: vertical-rl; transform: rotate(180deg);">50</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 40%; writing-mode: vertical-rl; transform: rotate(180deg);">60</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 46.66%; writing-mode: vertical-rl; transform: rotate(180deg);">70</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 53.33%; writing-mode: vertical-rl; transform: rotate(180deg);">80</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 60%; writing-mode: vertical-rl; transform: rotate(180deg);">90</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 66.66%; writing-mode: vertical-rl; transform: rotate(180deg);">100</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 73.33%; writing-mode: vertical-rl; transform: rotate(180deg);">110</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 80%; writing-mode: vertical-rl; transform: rotate(180deg);">120</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 86.66%; writing-mode: vertical-rl; transform: rotate(180deg);">130</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 93.33%; writing-mode: vertical-rl; transform: rotate(180deg);">140</div>';
		$output     .= '<div style="' . $common_css . 'position: absolute; top: 100%; left: 100%; writing-mode: vertical-rl; transform: rotate(180deg);">150</div>';
		$output     .= '</div>';
		$output     .= '</div>';
		$output     .= '</div>';

		return $output;
	}

	/**
	 * Determine the rating category.
	 *
	 * @param int $rating The EPC rating.
	 *
	 * @return string The rating category.
	 */
	private function get_rating_category( $rating ) {
		if ( $rating >= 85 ) {
			return 'A';
		} elseif ( $rating >= 70 ) {
			return 'B';
		} elseif ( $rating >= 55 ) {
			return 'C';
		} elseif ( $rating >= 40 ) {
			return 'D';
		} elseif ( $rating >= 25 ) {
			return 'E';
		} elseif ( $rating >= 10 ) {
			return 'F';
		} else {
			return 'G';
		}
	}

	public function output_dpe( $rating ) {
		// Define rating category widths and colors
		$rating_categories = array(
			'A' => array( 'width' => '14.285714285714%', 'color' => '#0b7735' ),
			'B' => array( 'width' => '8.1632653061224%', 'color' => '#3ca937' ),
			'C' => array( 'width' => '14.285714285714%', 'color' => '#85ba34' ),
			'D' => array( 'width' => '14.285714285714%', 'color' => '#ffdf09' ),
			'E' => array( 'width' => '16.326530612245%', 'color' => '#ef9124' ),
			'F' => array( 'width' => '18.367346938776%', 'color' => '#ea402e' ),
			'G' => array( 'width' => '14.285714285714%', 'color' => '#d51216' )
		);

		// translators: The DPE rating value.
		$title    = wp_sprintf( _x( 'Energy Consumption: %d kWh/m²/year', 'DPE rating value', 'real-estate-directory' ), $rating );

		$rating   = min( $rating, 490 );
		$width    = round( 5 / 490 * 100, 5 );
		$position = round( $rating / 490 * 100, 5 ) - ( $width / 2 );

		$output = '<div class="position-relative z-index-1">';
		$output .= '<div id="marker" class="c-pointer position-absolute top-50 translate-middle-y rounded" style="left:' . esc_attr( $position ) . '%;background:#000000a3;width:' . (float) $width . '%;height:45px;top:15px!important;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="' . esc_attr( $title ) . '"></div>';
		$output .= '</div>';

		$output .= '<div class="progress position-relative" style="height: 30px;">';

		foreach ( $rating_categories as $category => $data ) {
			$output .= '<div class="progress-bar" role="progressbar" style="width: ' . esc_attr( $data['width'] ) . ';background:' . esc_attr( $data['color'] ) . ' !important;" aria-valuenow="' . intval( $data['width'] ) . '" aria-valuemin="0" aria-valuemax="490">' . esc_html( $category ) . '</div>';
		}

		$output .= '</div>';

		return $output;
	}

	public function output_dpe_climate( $rating ) {
		// Define rating category widths and colors
		$rating_categories = array(
			'A' => array( 'width' => '5.4545454545455%', 'color' => '#0b7735' ),
			'B' => array( 'width' => '4.5454545454545%', 'color' => '#3ca937' ),
			'C' => array( 'width' => '17.272727272727%', 'color' => '#85ba34' ),
			'D' => array( 'width' => '18.181818181818%', 'color' => '#ffdf09' ),
			'E' => array( 'width' => '18.181818181818%', 'color' => '#ef9124' ),
			'F' => array( 'width' => '27.272727272727%', 'color' => '#ea402e' ),
			'G' => array( 'width' => '9.0909090909091%', 'color' => '#d51216' )
		);

		// translators: The DPE rating value.
		$title    = wp_sprintf( _x( 'CO2 Emissions: %d kg CO2/m²·year', 'DPE rating value', 'real-estate-directory' ), $rating );

		$rating   = min( $rating, 110 );
		$width    = round( 1.1 / 110 * 100, 5 );
		$position = round( $rating / 110 * 100, 5 ) - ( $width / 2 );

		$output = '<div class="position-relative z-index-1">';
		$output .= '<div id="marker" class="c-pointer position-absolute top-50 translate-middle-y rounded" style="left:' . esc_attr( $position ) . '%;background:#000000a3;width:' . (float) $width . '%;height:45px;top:15px!important;" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="' . esc_attr( $title ) . '"></div>';
		$output .= '</div>';

		$output .= '<div class="progress position-relative" style="height: 30px;">';

		foreach ( $rating_categories as $category => $data ) {
			$output .= '<div class="progress-bar" role="progressbar" style="width: ' . esc_attr( $data['width'] ) . ';background:' . esc_attr( $data['color'] ) . ' !important;" aria-valuenow="' . intval( $data['width'] ) . '" aria-valuemin="0" aria-valuemax="110">' . esc_html( $category ) . '</div>';
		}

		$output .= '</div>';

		return $output;
	}
}
