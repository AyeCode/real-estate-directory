<?php
/**
 * Mortgage Calculator
 *
 * @author    AyeCode Ltd
 * @package   Real_Estate_Directory
 * @version   1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GeoDir_Widget_Mortgage_Calculator class.
 */
class GeoDir_Widget_Mortgage_Calculator extends WP_Super_Duper {

	public $arguments;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$options = array(
			'base_id'          => 'gd_mortgage_calculator',
			'name'             => __( 'GD > Mortgage Calculator', 'real-estate-directory' ),
			'class_name'       => __CLASS__,
			'textdomain'       => GEODIRECTORY_TEXTDOMAIN,
			'block-icon'       => 'fas fa-calculator',
			'block-category'   => 'geodirectory',
			'block-supports'   => array(
				'customClassName' => false,
			),
			'block-keywords'   => "['geodir','mortgage','calculator']",
			'widget_ops'       => array(
				'classname'                   => 'geodir-mortgage-calculator-container' . ( geodir_design_style() ? ' bsui' : '' ),
				'description'                 => esc_html__( 'Adds a mortgage_calculator for real estate.', 'real-estate-directory' ),
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

		$arguments['price'] = array(
			'type'        => 'number',
			'title'       => __( 'Price', 'real-estate-directory' ),
			'placeholder' => '500000',
			'desc'        => __( 'Leave blank to use listing price field', 'real-estate-directory' ),
			'default'     => '',
			'desc_tip'    => true,
			'group'       => __( 'Defaults', 'real-estate-directory' ),
		);

		$arguments['currency_symbol'] = array(
			'type'        => 'text',
			'title'       => __( 'Currency symbol', 'real-estate-directory' ),
			'placeholder' => '$',
			'desc'        => __( 'Leave blank to use country default', 'real-estate-directory' ),
			'default'     => '',
			'desc_tip'    => true,
			'group'       => __( 'Defaults', 'real-estate-directory' ),
//			'element_require' => '[%service_email%]=="1"',
		);

		$arguments['currency_code'] = array(
			'type'            => 'text',
			'title'           => __( 'Currency code', 'real-estate-directory' ),
			'placeholder'     => 'USD',
			'desc'            => __( 'Leave blank to use country default (help with number formatting)', 'real-estate-directory' ),
			'default'         => '',
			'desc_tip'        => true,
			'group'           => __( 'Defaults', 'real-estate-directory' ),
			'element_require' => '[%currency_symbol%]!=""',
		);

		$arguments['down_payment'] = array(
			'type'        => 'text',
			'title'       => __( 'Down payment default', 'real-estate-directory' ),
			'placeholder' => 15,
			'default'     => '',
			'desc_tip'    => true,
			'group'       => __( 'Defaults', 'real-estate-directory' ),
		);

		$arguments['interest_rate'] = array(
			'type'        => 'text',
			'title'       => __( 'Interest rate default', 'real-estate-directory' ),
			'placeholder' => 5,
			'default'     => '',
			'desc_tip'    => true,
			'group'       => __( 'Defaults', 'real-estate-directory' ),
		);

		$arguments['loan_term'] = array(
			'type'        => 'text',
			'title'       => __( 'Loan Term default', 'real-estate-directory' ),
			'placeholder' => 30,
			'default'     => '',
			'desc_tip'    => true,
			'group'       => __( 'Defaults', 'real-estate-directory' ),
		);

		$arguments['property_tax'] = array(
			'type'        => 'text',
			'title'       => __( 'Property Tax default', 'real-estate-directory' ),
			'placeholder' => 400,
			'default'     => '',
			'desc_tip'    => true,
			'group'       => __( 'Defaults', 'real-estate-directory' ),
		);

		$arguments['home_insurance'] = array(
			'type'        => 'text',
			'title'       => __( 'Home Insurance default', 'real-estate-directory' ),
			'placeholder' => 200,
			'default'     => '',
			'desc_tip'    => true,
			'group'       => __( 'Defaults', 'real-estate-directory' ),
		);

		$arguments['pmi'] = array(
			'type'        => 'text',
			'title'       => __( 'PMI default', 'real-estate-directory' ),
			'placeholder' => 200,
			'default'     => '',
			'desc_tip'    => true,
			'group'       => __( 'Defaults', 'real-estate-directory' ),
		);

		$arguments['hoa_fees'] = array(
			'type'        => 'text',
			'title'       => __( 'Monthly HOA Fees', 'real-estate-directory' ),
			'placeholder' => 250,
			'default'     => '',
			'desc_tip'    => true,
			'group'       => __( 'Defaults', 'real-estate-directory' ),
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
		$output = $this->output_html( $instance, $args );
//		$output .= $this->output_js( $instance, $args );

		//$output .= '<div class="" >';


		return $output;
	}

	public function output_html( $instance, $args ) {
		global $gd_post;

		$price = ! empty( $instance['price'] ) ? esc_attr( $instance['price'] ) : '0';
		if ( empty( $price ) ) {
			$price = ! empty( $gd_post->price ) ? absint( $gd_post->price ) : 0;
		}
		$currency_symbol = ! empty( $instance['currency_symbol'] ) ? esc_attr( $instance['currency_symbol'] ) : '$';
		$currency_code   = ! empty( $instance['currency_code'] ) ? esc_attr( $instance['currency_code'] ) : 'USD';
		$down_payment    = ! empty( $instance['down_payment'] ) ? (float) esc_attr( $instance['down_payment'] ) : '15';
		$interest_rate   = ! empty( $instance['interest_rate'] ) ? (float) esc_attr( $instance['interest_rate'] ) : '5';
		$loan_term       = ! empty( $instance['loan_term'] ) ? absint( $instance['loan_term'] ) : '30';
		$property_tax    = ! empty( $instance['property_tax'] ) ? absint( $instance['property_tax'] ) : round( absint( ( $price * 0.02 ) / 12 ) );
		$home_insurance  = ! empty( $instance['home_insurance'] ) ? absint( $instance['home_insurance'] ) : round( absint( ( $price * 0.01 ) / 12 ) );
		$pmi             = ! empty( $instance['pmi'] ) ? absint( $instance['pmi'] ) : round( absint( ( $price * 0.01 ) / 12 ) );
		$hoa_fees        = ! empty( $instance['hoa_fees'] ) ? absint( $instance['hoa_fees'] ) : 220;


		wp_enqueue_script( 'redir-chartjs', plugin_dir_url( REAL_ESTATE_DIRECTORY_PLUGIN_FILE ) . 'assets/js/chart.min.js', array(), '4.3', true );

		wp_add_inline_script( 'redir-chartjs', $this->output_js( $instance, $args ) );

		$wrap_class = sd_build_aui_class( $instance );

		$styles = sd_build_aui_styles( $instance );
		$style  = $styles ? ' style="' . $styles . '"' : '';


		ob_start();
		?>
        <div class="<?php echo esc_attr( $wrap_class ); ?>" <?php echo esc_attr( $style ); ?>>
            <div class="col">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                            <div class="position-relative">
                                <canvas id="geodir_mortgageChart"></canvas>
                                <div id="geodir_totalPayment"
                                     class="position-absolute top-50 start-50 translate-middle text-center h4 "></div>
                                <div class="position-absolute top-50 start-50 translate-middle text-center text-muted mt-4"><?php esc_attr_e( 'Monthly', 'real-estate-directory' ); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div id="legend" class="list-group mt-3 fs-sm list-group-flush rounded">
                            <div class="list-group-item d-flex px-2">
                                <span class="fw-bold"><i
                                            class="far fa-circle text-gray me-1"></i> <?php  esc_attr_e( 'Down Payment', 'real-estate-directory' ); ?></span>
                                <span class="ms-auto calc-dp-value"></span>
                            </div>
                            <div class="list-group-item d-flex px-2">
                                <span class="fw-bold"><i
                                            class="far fa-circle text-gray me-1"></i> <?php  esc_attr_e( 'Loan Amount', 'real-estate-directory' ); ?></span>
                                <span class="ms-auto calc-la-value"></span>
                            </div>
                            <div class="list-group-item d-flex px-2">
                                <span class="fw-bold"><i class="far fa-dot-circle me-1"
                                                         style="color: #36a2eb;"></i> <?php  esc_attr_e( 'Monthly Mortgage Payment', 'real-estate-directory' ); ?></span>
                                <span class="ms-auto calc-mmp-value"></span>
                            </div>
                            <div class="list-group-item d-flex px-2">
                                <span class="fw-bold"><i class="far fa-dot-circle me-1"
                                                         style="color: #ff6384;"></i> <?php  esc_attr_e( 'Property Tax', 'real-estate-directory' ); ?></span>
                                <span class="ms-auto calc-pt-value"></span>
                            </div>
                            <div class="list-group-item d-flex px-2">
                                <span class="fw-bold"><i class="far fa-dot-circle me-1"
                                                         style="color: #ff9f40;"></i> <?php  esc_attr_e( 'Home Insurance', 'real-estate-directory' ); ?></span>
                                <span class="ms-auto calc-hi-value"></span>
                            </div>
                            <div class="list-group-item d-flex px-2">
                                <span class="fw-bold"><i class="far fa-dot-circle me-1"
                                                         style="color: #ffcd56;"></i> <?php  esc_attr_e( 'PMI', 'real-estate-directory' ); ?></span>
                                <span class="ms-auto calc-pmi-value"></span>
                            </div>
                            <div class="list-group-item d-flex px-2">
                                <span class="fw-bold"><i class="far fa-dot-circle me-1"
                                                         style="color: #4bc0c0;"></i> <?php  esc_attr_e( 'Monthly HOA Fees', 'real-estate-directory' ); ?></span>
                                <span class="ms-auto calc-mhf-value"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-5">
                <form id="geodir_mortgageCalculator">
                    <input type="hidden" id="gmc_currency_symbol" value="<?php echo esc_attr( $currency_symbol ); ?>">
                    <input type="hidden" id="gmc_currency_code" value="<?php echo esc_attr( $currency_code ); ?>">
                    <div class="row">
                        <div class="col-lg-4 col-6 mb-3">
                            <label for="loanAmount"
                                   class="form-label fw-bold"><?php  esc_attr_e( 'Property Price', 'real-estate-directory' ); ?>
                                <i
                                        class="far fa-question-circle c-pointer text-muted" data-bs-toggle="tooltip"
                                        data-bs-title="<?php  esc_attr_e( 'This is the price of the property, it does not include additional fees that may be due.', 'real-estate-directory' ); ?>"></i></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text text-muted"><?php echo esc_attr( $currency_symbol ); ?></span>
                                <input type="number" class="form-control" id="gmc_loanAmount"
                                       value="<?php echo absint( $price ); ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 mb-3">
                            <label for="downPayment"
                                   class="form-label fw-bold"><?php  esc_attr_e( 'Down Payment', 'real-estate-directory' ); ?> <i
                                        class="far fa-question-circle c-pointer text-muted" data-bs-toggle="tooltip"
                                        data-bs-title="<?php  esc_attr_e( 'The amount of money you pay upfront towards the mortgage, the more you pay the less your monthly payments will be.', 'real-estate-directory' ); ?>"></i></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text text-muted">%</span>
                                <input type="number" class="form-control" id="gmc_downPayment"
                                       value="<?php echo esc_attr( (float) $down_payment ); ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 mb-3">
                            <label for="interestRate"
                                   class="form-label fw-bold"><?php  esc_attr_e( 'Interest Rate', 'real-estate-directory' ); ?>
                                <i
                                        class="far fa-question-circle c-pointer text-muted" data-bs-toggle="tooltip"
                                        data-bs-title="<?php  esc_attr_e( "This is the cost you pay to borrow money to buy a property It's a percentage of the loan amount. The lower the interest rate, the less you'll pay over the life of the loan.", 'real-estate-directory' ); ?>"></i></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text text-muted">%</span>
                                <input type="number" class="form-control" id="gmc_interestRate"
                                       value="<?php echo esc_attr( (float) $interest_rate ); ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 mb-3">
                            <label for="loanTerm"
                                   class="form-label fw-bold"><?php  esc_attr_e( 'Loan Term (Years)', 'real-estate-directory' ); ?>
                                <i
                                        class="far fa-question-circle c-pointer text-muted" data-bs-toggle="tooltip"
                                        data-bs-title="<?php  esc_attr_e( 'This is how many years the loan lasts, typically the longer it lasts the lower the monthly repayments.', 'real-estate-directory' ); ?>"></i></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text text-muted"><i class="far fa-calendar-alt"></i></span>
                                <input type="number" class="form-control" id="gmc_loanTerm"
                                       value="<?php echo absint( $loan_term ); ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 mb-3">
                            <label for="propertyTax"
                                   class="form-label fw-bold"><?php  esc_attr_e( 'Property Tax', 'real-estate-directory' ); ?> <i
                                        class="far fa-question-circle c-pointer text-muted" data-bs-toggle="tooltip"
                                        data-bs-title="<?php  esc_attr_e( 'This is a monthly estimate of your property tax, it will be different in each location, its used to give you an idea of your total monthly payments.', 'real-estate-directory' ); ?>"></i></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text text-muted"><?php echo esc_attr( $currency_symbol ); ?></span>
                                <input type="number" class="form-control" id="gmc_propertyTax"
                                       value="<?php echo absint( $property_tax ); ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 mb-3">
                            <label for="homeInsurance"
                                   class="form-label fw-bold"><?php  esc_attr_e( 'Home Insurance', 'real-estate-directory' ); ?>
                                <i
                                        class="far fa-question-circle c-pointer text-muted" data-bs-toggle="tooltip"
                                        data-bs-title="<?php  esc_attr_e( 'Insurance that covers loss or damage to the property. It is often a requirement from loan providers.', 'real-estate-directory' ); ?>"></i></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text text-muted"><?php echo esc_attr( $currency_symbol ); ?></span>
                                <input type="number" class="form-control" id="gmc_homeInsurance"
                                       value="<?php echo absint( $home_insurance ); ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 mb-3">
                            <label for="pmi" class="form-label fw-bold"><?php  esc_attr_e( 'PMI', 'real-estate-directory' ); ?>
                                <i
                                        class="far fa-question-circle c-pointer text-muted" data-bs-toggle="tooltip"
                                        data-bs-title="<?php  esc_attr_e( "Private Mortgage Insurance, is a type of insurance that homebuyers might need to pay if their down payment is less than 20% of the home's price. It protects the lender if the buyer can't pay the mortgage. It's usually a part of the monthly mortgage payment until the borrower has enough equity in the home.", 'real-estate-directory' ); ?>"></i></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text text-muted"><?php echo esc_attr( $currency_symbol ); ?></span>
                                <input type="number" class="form-control" id="gmc_pmi"
                                       value="<?php echo absint( $pmi ); ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6 mb-3">
                            <label for="monthlyHOAFees"
                                   class="form-label fw-bold"><?php  esc_attr_e( 'Monthly HOA Fees', 'real-estate-directory' ); ?>
                                <i
                                        class="far fa-question-circle c-pointer text-muted" data-bs-toggle="tooltip"
                                        data-bs-title="<?php  esc_attr_e( 'Homeowners Association fees, are monthly or yearly charges paid by homeowners to a community organization. This money is used for maintaining and improving shared spaces.', 'real-estate-directory' ); ?>"></i></label>
                            <div class="input-group mb-3">
                                <span class="input-group-text text-muted"><?php echo esc_attr( $currency_symbol ); ?></span>
                                <input type="number" class="form-control" id="gmc_monthlyHOAFees"
                                       value="<?php echo absint( $hoa_fees ); ?>" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


		<?php

		return ob_get_clean();
	}

	public function output_js( $instance, $args ) {

		ob_start();
		$currency_symbol = '$';
		?>
        <script>

            // Create our number formatter.
            const gdmc_formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: document.getElementById('gmc_currency_code').value,
                // These options are needed to round to whole numbers
                minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
                maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
            });

            var gdmc_last_pmi;
            var gdmc_chart;

            function geodir_real_estate_mortgage_calculate() {
                var downPayment = document.getElementById('gmc_downPayment').value ? parseFloat(document.getElementById('gmc_downPayment').value) : 0;
                var loanAmount = document.getElementById('gmc_loanAmount').value ? parseFloat(document.getElementById('gmc_loanAmount').value) : 0;
                var loanTerm = document.getElementById('gmc_loanTerm').value ? parseFloat(document.getElementById('gmc_loanTerm').value) : '';
                var interestRate = document.getElementById('gmc_interestRate').value ? parseFloat(document.getElementById('gmc_interestRate').value) / 100 / 12 : 0;
                var propertyTax = document.getElementById('gmc_propertyTax').value ? parseFloat(document.getElementById('gmc_propertyTax').value) : 0;
                var homeInsurance = document.getElementById('gmc_homeInsurance').value ? parseFloat(document.getElementById('gmc_homeInsurance').value) : 0;
                var monthlyHOAFees = document.getElementById('gmc_monthlyHOAFees').value ? parseFloat(document.getElementById('gmc_monthlyHOAFees').value) : 0;
                var downPaymentAmount = loanAmount * (downPayment / 100);

                var pmi = document.getElementById('gmc_pmi').value ? parseFloat(document.getElementById('gmc_pmi').value) : 0;

                // remove PMI if down payment over 20%
                if (downPayment >= 20) {

                    if (pmi > 0) {
                        gdmc_last_pmi = pmi;
                    }
                    pmi = 0;
                    jQuery('#gmc_pmi').val(pmi).prop('disabled', true);
                } else {
                    if (pmi == 0 && gdmc_last_pmi > 0) {
                        pmi = gdmc_last_pmi;
                        jQuery('#gmc_pmi').val(pmi).prop('disabled', false);
                    }
                }

                var monthlyMortgagePayment = (loanAmount - downPaymentAmount) * (interestRate * Math.pow((1 + interestRate), loanTerm * 12)) / (Math.pow((1 + interestRate), loanTerm * 12) - 1);
                var totalMonthlyPayment = monthlyMortgagePayment + propertyTax + homeInsurance + pmi + monthlyHOAFees;

                var ctx = document.getElementById('geodir_mortgageChart').getContext('2d');

                if (gdmc_chart) {
                    gdmc_chart.destroy();
                }

                gdmc_chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Mortgage Payment', 'Property Tax', 'Home Insurance', 'PMI', 'HOA Fees'],
                        datasets: [{
                            data: [monthlyMortgagePayment, propertyTax, homeInsurance, pmi, monthlyHOAFees],
                            //  backgroundColor: ['rgba(75, 192, 192, 1)', 'rgba(192, 75, 75, 1)', 'rgba(75, 75, 192, 1)', 'rgba(192, 192, 75, 1)', 'rgba(75, 192, 75, 1)'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '85%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                document.getElementById('geodir_totalPayment').innerHTML = gdmc_formatter.format(totalMonthlyPayment.toFixed(2));

                jQuery('.calc-dp-value').text(gdmc_formatter.format(downPaymentAmount.toFixed(2)));
                jQuery('.calc-la-value').text(gdmc_formatter.format(loanAmount.toFixed(2) - downPaymentAmount.toFixed(2)));
                jQuery('.calc-mmp-value').text(gdmc_formatter.format(monthlyMortgagePayment.toFixed(2)));
                jQuery('.calc-pt-value').text(gdmc_formatter.format(propertyTax.toFixed(2)));
                jQuery('.calc-hi-value').text(gdmc_formatter.format(homeInsurance.toFixed(2)));
                jQuery('.calc-pmi-value').text(gdmc_formatter.format(pmi.toFixed(2)));
                jQuery('.calc-mhf-value').text(gdmc_formatter.format(monthlyHOAFees.toFixed(2)));
            }

            document.getElementById('geodir_mortgageCalculator').addEventListener('input', geodir_real_estate_mortgage_calculate);

            jQuery(function () {
                geodir_real_estate_mortgage_calculate();
            });

        </script>
		<?php

		return str_replace( array( '<script>', '</script>' ), '', ob_get_clean() );
	}


}
