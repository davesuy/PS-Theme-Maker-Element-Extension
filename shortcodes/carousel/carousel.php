<?php


if ( ! class_exists( 'ST_Carousel' ) ) :

class ST_Carousel extends ST_Pb_Shortcode_Parent {
	/**
	 * Constructor
	 *
	 * @return  void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Configure shortcode.
	 *
	 * @return  void
	 */
	public function element_config() {
		$this->config['shortcode']        = strtolower( __CLASS__ );
		$this->config['name']             = __( 'Carousel', ST_PBL );
		$this->config['cat']              = __( 'Interactive Elements', ST_PBL );
		$this->config['icon']             = 'cbi-Carousel';
		$this->config['has_subshortcode'] = 'ST_Item_' . str_replace( 'ST_', '', __CLASS__ );
		$this->config['description']      = __( 'Rotating circular content with text and images', ST_PBL );

		// Define exception for this shortcode
		$this->config['exception'] = array(
			'admin_assets' => array(
				'st-pb-joomlashine-iconselector-js',
			),
			'frontend_assets' => array(
					// Bootstrap 3
					'st-pb-bootstrap-css',
					'st-pb-bootstrap-js',
					// Font IcoMoon
					'st-pb-font-icomoon-css',
					// Shortcode style
					'carousel_frontend.css',
					'carousel_frontend.js'
				),
			);

		// Use Ajax to speed up element settings modal loading speed
		$this->config['edit_using_ajax'] = true;

		// Edit inline supplement
		add_action( 'st_pb_modal_footer', array( 'ST_Pb_Objects_Modal', '_modal_footer' ) );
	}

	/**
	 * Define shortcode settings.
	 *
	 * @return  void
	 */
	public function element_items() {
		$this->items = array(
			'action' => array(
		array(
					'id'      => 'btn_convert',
					'type'    => 'button_group',
					'bound'   => 0,
					'actions' => array(
		array(
							'std'         => __( 'Tab', ST_PBL ),
							'action_type' => 'convert',
							'action'      => 'carousel_to_tab',
		),
		array(
							'std'         => __( 'Accordion', ST_PBL ),
							'action_type' => 'convert',
							'action'      => 'carousel_to_accordion',
		),
		array(
							'std'         => __( 'List', ST_PBL ),
							'action_type' => 'convert',
							'action'      => 'carousel_to_list',
		),
		)
		),
		),
			'content' => array(

		array(
					'id'            => 'carousel_items',
					'type'          => 'group',
					'shortcode'     => ucfirst( __CLASS__ ),
					'sub_item_type' => $this->config['has_subshortcode'],
					'sub_items'     => array(
		array('std' => ''),
		array('std' => ''),
		),
		),
		),
			'styling' => array(
				array(
							'type' => 'preview',
				),

				ST_Pb_Helper_Type::get_heading_tag('h4'),

				array(
							'name'    => __( 'Alignment', ST_PBL ),
							'id'      => 'align',
							'class'   => 'input-sm',
							'std'     => 'center',
							'type'    => 'radio_button_group',
							'options' => ST_Pb_Helper_Type::get_text_align(),
				),
				array(
							'name'                 => __( 'Dimension', ST_PBL ),
							'container_class'      => 'combo-group dimension-inline',
							'id'                   => 'dimension',
							'type'                 => 'dimension',
							'extended_ids'         => array( 'dimension_width', 'dimension_height', 'dimension_width_unit' ),
							'dimension_width'      => array( 'std' => '' ),
							'dimension_height'     => array( 'std' => '' ),
							'dimension_width_unit' => array(
								'options' => array( 'px' => 'px', '%' => '%' ),
								'std'     => 'px',
							),
							'tooltip' => __( 'Set width and height of element', ST_PBL ),
				),
				array(
							'name'    => __( 'Show Indicator', ST_PBL ),
							'id'      => 'show_indicator',
							'type'    => 'radio',
							'std'     => 'yes',
							'options' => array( 'yes' => __( 'Yes', ST_PBL ), 'no' => __( 'No', ST_PBL ) ),
							'tooltip' => __( 'Round Pagination buttons', ST_PBL ),
				),
				array(
							'name'    => __( 'Show Arrows', ST_PBL ),
							'id'      => 'show_arrows',
							'type'    => 'radio',
							'std'     => 'yes',
							'options' => array( 'yes' => __( 'Yes', ST_PBL ), 'no' => __( 'No', ST_PBL ) ),
							'tooltip' => __( 'Previous & Next buttons', ST_PBL ),
				),
				array(
							'name'       => __( 'Automatic Cycling', ST_PBL ),
							'id'         => 'automatic_cycling',
							'type'       => 'radio',
							'std'        => 'no',
							'options'    => array( 'yes' => __( 'Yes', ST_PBL ), 'no' => __( 'No', ST_PBL ) ),
							'has_depend' => '1',
							'tooltip' => __( 'Automatically run carousel', ST_PBL ),
				),
				array(
							'name' => __( 'Cycling Interval', ST_PBL ),
							'type' => array(
				array(
									'id'         => 'cycling_interval',
									'type'       => 'text_append',
									'type_input' => 'number',
									'class'      => 'input-mini',
									'std'        => '5',
									'append'     => 'second(s)',
									'validate'   => 'number',
				),
				),
							'dependency' => array('automatic_cycling', '=', 'yes'),
							'tooltip' => __( 'Set interval for each cycling', ST_PBL ),
				),
				array(
							'name'       => __( 'Pause on mouse over', ST_PBL ),
							'id'         => 'pause_mouseover',
							'type'       => 'radio',
							'std'        => 'yes',
							'options'    => array( 'yes' => __( 'Yes', ST_PBL ), 'no' => __( 'No', ST_PBL ) ),
							'dependency' => array( 'automatic_cycling', '=', 'yes' ),
							'tooltip' => __( 'Pause cycling on mouse over', ST_PBL ),
				),				
				ST_Pb_Helper_Type::get_apprearing_animations(),
				ST_Pb_Helper_Type::get_animation_speeds(),
			)
		);
	}

	/**
	 * Generate HTML code from shortcode content.
	 *
	 * @param   array   $atts     Shortcode attributes.
	 * @param   string  $content  Current content.
	 *
	 * @return  string
	 */
	public function element_shortcode_full( $atts = null, $content = null ) {
		$arr_params    = shortcode_atts( $this->config['params'], $atts );
		extract( $arr_params );
		$random_id     = ST_Pb_Utils_Common::random_string();
		$carousel_id   = "carousel_$random_id";

		$interval_time = ! empty( $cycling_interval ) ? intval( $cycling_interval ) * 1000 : 5000;
		$interval      = ( $automatic_cycling == 'yes' ) ? $interval_time : 'false';
		$pause         = ( $pause_mouseover == 'yes' ) ? 'pause : "hover"' : '';
		$script        = "<script type='text/javascript'>(function ($){ $( document ).ready(function(){if( $( '#$carousel_id' ).length ){ $( '#$carousel_id' ).carousel( {interval: $interval,$pause} );}});} )( jQuery )</script>";

		$styles        = array();
		if ( ! empty( $dimension_width ) )
		$styles[] = "width : {$dimension_width}{$dimension_width_unit};";
		if ( ! empty( $dimension_height ) )
		$styles[] = "height : {$dimension_height}px;";

		if ( in_array( $align, array( 'left', 'right', 'inherit') ) ) {
			$styles[] = "float : $align;";
		} else if ( $align == 'center' )
		$styles[] = 'margin : 0 auto;';

		$styles = trim( implode( ' ', $styles ) );
		$styles = ! empty( $styles ) ? "style='$styles'" : '';


		$carousel_indicators   = array();
		$carousel_indicators[] = '<ol class="carousel-indicators">';

		$params_to_send = array(
			'heading_tag',
		);

		
		$params_string = ST_Pb_Helper_Functions::make_shortcode_params($params_to_send, $arr_params);
		$content = str_replace('[st_item_carousel', '[st_item_carousel '.$params_string, $content);
		
		$sub_shortcode         = do_shortcode( $content );
		$items                 = explode( '<!--seperate-->', $sub_shortcode );
		$items                 = array_filter( $items );
		$initial_open          = isset( $initial_open ) ? ( ( $initial_open > count( $items ) ) ? 1 : $initial_open ) : 1;
		foreach ( $items as $idx => $item ) {
			$active                = ($idx + 1 == $initial_open) ? 'active' : '';
			$item                  = str_replace( '{active}', $active, $item );
			$item                  = str_replace( '{WIDTH}', ( ! empty( $dimension_width ) ) ? ( string ) $dimension_width : '', $item );
			$item                  = str_replace( '{HEIGHT}', ( ! empty( $dimension_height ) ) ? ( string ) $dimension_height : '', $item );
			$items[$idx]           = $item;
			$active_li             = ($idx + 1 == $initial_open) ? "class='active'" : '';
			$carousel_indicators[] = "<li $active_li></li>";
		}
		$carousel_content      = "<div class='carousel-inner'>" . implode( '', $items ) . '</div>';

		$carousel_indicators[] = '</ol>';
		$carousel_indicators   = implode( '', $carousel_indicators );

		if ( $show_indicator == 'no' )
		$carousel_indicators = '';

		$carousel_navigator = '';
		if ($show_arrows == 'yes')
		$carousel_navigator = "<a class='left carousel-control'><span class='fa fa-chevron-left'></span></a><a class='right carousel-control'><span class='fa fa-chevron-right'></span></a>";

		$html = "<div class='carousel slide' $styles id='$carousel_id'>$carousel_indicators $carousel_content $carousel_navigator</div>";

		return $this->element_wrapper( $html . $script, $arr_params );
	}
}

endif;
