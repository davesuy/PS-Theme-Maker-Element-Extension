<?php
if (!class_exists('ST_BlogExcerptAllowedTags')):
/**
 *
 * @package  ProStyler Builder Shortcodes
 * @since    1.0.0
 */
	class ST_BlogExcerptAllowedTags extends ST_Pb_Shortcode_Element {
		/**
		 * Constructor
		 *
		 * @return  void
		 */
		public function __construct() {
			parent::__construct();

			add_action( 'wp_enqueue_scripts', array($this,'enqueue_multiple' ));

		}

		public function enqueue_multiple() {

			wp_enqueue_style( 'style-blogat', plugin_dir_url(  __FILE__).'assets/css/blogexcerptallowedtags_frontend.css');
			wp_enqueue_script ( 'custom-script-blogat', plugin_dir_url(  __FILE__).'assets/js/blogexcerptallowedtags_frontend.js');
		}
		/**
		 * Configure shortcode.
		 *
		 * @return  void
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower(__CLASS__);
			$this->config['name'] = __('Blog v2 Excerpt Allowed Tags', ST_PBL);
			$this->config['cat'] = __('Content', ST_PBL);
			$this->config['icon'] = 'cbi-Blog';
			$this->config['description'] = __('Easily embed blog posts in your pages with allowed tags on Excerpt', ST_PBL);
			// Define exception for this shortcode
			$this->config['exception'] = array(
				'admin_assets' => array(
					// Shortcode initialization
					'blog2_frontend.js',
				),
				'frontend_assets' => array(
					'cbb-isotope-js',
					'blog2_frontend.css',
					'blog2_frontend.js',
					'st-pb-font-icomoon-css',
				),
			);
			// Use Ajax to speed up element settings modal loading speed
			$this->config['edit_using_ajax'] = true;
		}
		/**
		 * Define shortcode settings.
		 *
		 * @return  void
		 */
		public function element_items() {
			$categories = get_categories();
			$categories_list = array();
			$categories_list[''] = 'All';
			if ($categories) {
				foreach ($categories as $cat) {
					$categories_list[$cat->slug] = $cat->name;
				}
			}
			//echo '<pre>'.print_r($categories_list, TRUE).'</pre>';
			$this->items['content'] = array(
				array(
					'name' => __('Order By', ST_PBL),
					'id' => 'order_by',
					'type' => 'select',
					'std' => 'date',
					'options' => array(
						"date" => "Date",
						'ID' => 'ID',
						'author' => 'Author',
						'title' => 'Title',
						'modified' => 'Date Modified',
						'rand' => 'Random',
						'comment_count' => '# of Comments',
						'menu_order' => 'Menu order',
					),
				),
				array(
					'name' => __('Order', ST_PBL),
					'id' => 'order',
					'type' => 'select',
					'std' => 'DESC',
					'options' => array(
						"DESC" => "Descending",
						"ASC" => "ascending",
					),
				),
				array(
					'name' => __('Count (Per page if pagination shown)', ST_PBL),
					'id' => 'count',
					'type' => 'text_append',
					'type_input' => 'number',
					'class' => 'input-mini',
					'std' => '10',
					'append' => 'posts',
					'validate' => 'number',
				),
				array(
					'name' => __('Filter by Tags (e.g tag1,tag2) - Optional', ST_PBL),
					'id' => 'tags',
					'type' => 'text_field',
					'std' => '',
					'tooltip' => __('Limit by Tags.', ST_PBL),
				),
				array(
					'name' => __('Filter by Category', ST_PBL),
					'id' => 'category',
					'type' => 'select',
					'std' => '',
					'options' => $categories_list,
				),
			);
			$this->items['styling'] = array(
				array(
					'type' => 'preview',
				),
				array(
					'name' => __('Columns', ST_PBL),
					'id' => 'columns',
					'type' => 'select',
					'std' => '3',
					'options' => array(
						"1" => "1",
						"2" => "2",
						"3" => "3",
						"4" => "4",
						"5" => "5",
					),
					'dependency' => array('layout_mode', '!=', 'list'),
				),
				array(
					'name' => __('Show Pagination', ST_PBL),
					'id' => 'pagination',
					'type' => 'radio',
					'std' => 'yes',
					'options' => array('yes' => __('Yes', ST_PBL), 'no' => __('No', ST_PBL)),
				),
				array(
					'name' => __('Layout mode', ST_PBL),
					'id' => 'layout_mode',
					'type' => 'select',
					'std' => 'masonry',
					'options' => array(
						"masonry" => "Masonry",
						"fitRows" => "Fit rows",
						"list" => "List",
						"image_grid" => "Image Grid",
						"image_grid_masonry" => "Image Grid Masonry",
					),
					'has_depend' => '1',
				),
				array(
						'name' => __('Alignment', ST_PBL),
						'id' => 'caption_align',
						'class' => 'input-sm',
						'std' => 'flex-end',
						'type' => 'select',
						'options' => array('flex-start' => __('Top', ST_PBL), 'center' => __('Center', ST_PBL), 'flex-end' => __('Bottom', ST_PBL), 'stretch' => __('Stretch to height', ST_PBL)),
						'css' => array(
							array('.cbb-blog2-item.image_grid', 'align-items'),
							array('.cbb-blog2-item.image_grid', 'display', 'flex'),
							array('.cbb-blog2-item.image_grid_masonry', 'align-items'),
							array('.cbb-blog2-item.image_grid_masonry', 'display', 'flex'),
						),
						'dependency' => array(
							array('layout_mode', '!=', 'masonry'),
							array('layout_mode', '!=', 'fitRows'),
							array('layout_mode', '!=', 'list'),
						),
					),
				array(
					'name' => __('Excerpt text length', ST_PBL),
					'id' => 'excerpt_length',
					'type' => 'text_append',
					'type_input' => 'number',
					'class' => 'input-mini',
					'std' => '150',
					'append' => 'characters',
					'validate' => 'number',
				),
				array(
					'name' => __('Elements', ST_PBL),
					'id' => 'elements',
					'type' => 'items_list',
					'std' => 'image__#__title__#__author__#__date__#__excerpt__#__category_btn__#__comments',
					'options' => array(
						'image' => __('Image', ST_PBL),
						'title' => __('Title', ST_PBL),
						'author' => __('Author', ST_PBL),
						'date' => __('Date', ST_PBL),
						'excerpt' => __('Excerpt', ST_PBL),
						'category_btn' => __('Category', ST_PBL),
						'comments' => __('Comments', ST_PBL),
					),
					'options_type' => 'checkbox',
					'popover_items' => array('title', 'button'),
					'tooltip' => __('Elements to display on promotion box', ST_PBL),
					'style' => array('height' => '200px'),
					'container_class' => 'unsortable content-elements',
				),
				array(
					'type' => 'skip_to_next_section',
				),
				ST_Pb_Helper_Type::get_shadow_type('shadow', '.cbb-blog2-item', array(
                    array('.cbb-blog2-item', 'border-bottom-width', '1px', array(
                        array('shadow', '!=', ''),
                        array('border_width', '=', ''),
                    )),
				)),
				array(
					'name' => 'Font Styles',
					'type' => 'group_open',
				),
				ST_Pb_Helper_Type::get_heading_tag('h3'),
				ST_Pb_Helper_Type::get_font_size('%id% h1.cbb-blog2-title, %id% h2.cbb-blog2-title, %id% h3.cbb-blog2-title, %id% h4.cbb-blog2-title, %id% h5.cbb-blog2-title, %id% h6.cbb-blog2-title'),
				ST_Pb_Helper_Type::get_custom_fonts_option(),
				ST_Pb_Helper_Type::get_custom_fontface(),
				ST_Pb_Helper_Type::get_custom_font_attributes('%id% h1.cbb-blog2-title, %id% h2.cbb-blog2-title, %id% h3.cbb-blog2-title, %id% h4.cbb-blog2-title, %id% h5.cbb-blog2-title, %id% h6.cbb-blog2-title',false),
				ST_Pb_Helper_Type::get_line_height('%id% h1.cbb-blog2-title, %id% h2.cbb-blog2-title, %id% h3.cbb-blog2-title, %id% h4.cbb-blog2-title, %id% h5.cbb-blog2-title, %id% h6.cbb-blog2-title'),
				ST_Pb_Helper_Type::get_letter_spacing('%id% h1.cbb-blog2-title, %id% h2.cbb-blog2-title, %id% h3.cbb-blog2-title, %id% h4.cbb-blog2-title, %id% h5.cbb-blog2-title, %id% h6.cbb-blog2-title'),
				array(
					'name' => __('Author/Date font size', ST_PBL),
					'id' => 'author_date_font_size',
					'type' => 'text_append',
					'type_input' => 'number',
					'class' => 'input-mini',
					'std' => '',
					'append' => 'px',
					'validate' => 'number',
					'css' => array(
						array('%id% p.byline', 'font-size'),
					),
				),
				array(
					'name' => __('Excerpt font size', ST_PBL),
					'id' => 'excerpt_font_size',
					'type' => 'text_append',
					'type_input' => 'number',
					'class' => 'input-mini',
					'std' => '',
					'append' => 'px',
					'validate' => 'number',
					'css' => array(
						array('%id% div.cbb-blog2-text', 'font-size'),
					),
				),
				array(
					'type' => 'group_close',
				),
				array(
					'name' => 'Colors',
					'type' => 'group_open',
				),
				array(
					'name' => __('Title color', ST_PBL),
					'id' => 'title_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('Applies to Body Title', ST_PBL),
					'css' => array('.cbb-blog2-item .cbb-blog2-title a', 'color'),
				),
				array(
					'name' => __('Links color', ST_PBL),
					'id' => 'link_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('Applies to Body links, Author and Date', ST_PBL),
					'css' => array(
						array('.cbb-blog2-item-inner .byline', 'color'),
						array('.cbb-blog2-item-inner .cbb-blog2-text a', 'color'),
					)
				),
				array(
					'name' => __('Text color', ST_PBL),
					'id' => 'text_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('Applies to Body text', ST_PBL),
					'css' => array(
						array('.cbb-blog2-item', 'color'),
						array('.cbb-blog2-item .byline', 'color'),
					),
				),
				array(
					'name' => __('Background color', ST_PBL),
					'id' => 'bg_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('Applies to Body', ST_PBL),
					'tooltip' => __('', ST_PBL),
					'css' => array(
						array('.cbb-blog2-item', 'background-color', array('opacity_from' => 'caption_bg_opacity')),
						array('.cbb-blog2-item-inner', 'background-color', array('opacity_from' => 'caption_bg_opacity')),
					),
				),
				array(
						'name' => __('Background opacity', ST_PBL),
						'id' => 'caption_bg_opacity',
						'type' => 'slider',
						'class' => 'st-slider',
						'std_min' => '0',
						'std_max' => '100',
						'std' => '100',
						'css' => array(''),
				),
				array(
					'type' => 'group_close',
				),
				array(
					'name' => 'Links',
					'type' => 'group_open',
				),
				array(
					'name' => __('Button Link style', ST_PBL),
					'id' => 'read_more_style',
					'type' => 'select',
					'std' => 'dots',
					'options' => array(
						"dots" => "Dots [...]",
						"button" => "Button",
					),
					'has_depend' => 1,
				),
				array(
					'name' => __('Button Text', ST_PBL),
					'id' => 'rmb_text',
					'type' => 'text_field',
					'std' => 'Read more',
					'dependency' => array('read_more_style', '=', 'button'),
				),
				array(
					'name' => __('Button Style', ST_PBL),
					'id' => 'rmb_style',
					'type' => 'select',
					'std' => 'default',
					'options' => array(
						'default' => __('Default', ST_PBL),
						'solid' => __('Solid', ST_PBL),
						'default-no-fill' => __('Outlined', ST_PBL),
						'rounded' => __('Rounded Solid', ST_PBL),
						'rounded-no-fill' => __('Rounded Outlined', ST_PBL),
					),
					'dependency' => array('read_more_style', '=', 'button'),
				),
				array(
					'name' => __('Button Size', ST_PBL),
					'id' => 'button_size',
					'type' => 'select',
					'class' => 'input-sm',
					'std' => ST_Pb_Helper_Type::get_first_option(ST_Pb_Helper_Type::get_button_size()),
					'options' => ST_Pb_Helper_Type::get_button_size(),
					'dependency' => array('read_more_style', '=', 'button'),
				),
				array(
					'name' => __('Button Color', ST_PBL),
					'id' => 'rmb_color',
					'type' => 'select',
					'std' => ST_Pb_Helper_Type::get_first_option(ST_Pb_Helper_Type::get_button_color()),
					'options' => ST_Pb_Helper_Type::get_button_color(),
					'tooltip' => __('Select the color of the button', ST_PBL),
					'dependency' => array('read_more_style', '=', 'button'),
					'has_depend' => '1',
				),
				array(
					'name' => __('Button Custom color', ST_PBL),
					'id' => 'rmb_custom_color',
					'type' => 'color_picker',
					'std' => '',
					'css' => array(
						array('.cbb-blog2-layout-links a.cbb-blog2-btn', 'background-color', '%value%', array(
							array('rmb_style', '!=', 'default-no-fill'),
							array('rmb_style', '!=', 'rounded-no-fill'),
						)),
						array('.cbb-blog2-layout-links a.cbb-blog2-btn', 'border-color', '%value%'),
					),
					'dependency' => array(
						array('read_more_style', '=', 'button'),
						array('rmb_color', '=', 'btn-default'),
					),
				),
				array(
					'name' => __('Button Text color', ST_PBL),
					'id' => 'rmb_text_color',
					'type' => 'color_picker',
					'std' => '',
					'css' => array(
						array('.cbb-blog2-layout-links a.cbb-blog2-btn', 'color', '%value%'),
					),
					'dependency' => array(
						array('read_more_style', '=', 'button'),
						array('rmb_color', '=', 'btn-default'),
					),
				),
				array(
					'name' => __('Button Custom hover color', ST_PBL),
					'id' => 'rmb_custom_h_color',
					'type' => 'color_picker',
					'std' => '',
					'css' => array(
						array('.cbb-blog2-layout-links a.cbb-blog2-btn:hover', 'background-color', '%value% !important'),
						array('.cbb-blog2-layout-links a.cbb-blog2-btn:hover', 'border-color', '%value% !important'),
					),
					'dependency' => array(
						array('read_more_style', '=', 'button'),
						array('rmb_color', '=', 'btn-default'),
					),
				),
				array(
					'name' => __('Button Hover Text color', ST_PBL),
					'id' => 'rmb_h_text_color',
					'type' => 'color_picker',
					'std' => '',
					'css' => array(
						array('.cbb-blog2-layout-links a.cbb-blog2-btn:hover', 'color', '%value%'),
					),
					'dependency' => array(
						array('read_more_style', '=', 'button'),
						array('rmb_color', '=', 'btn-default'),
					),
				),
				array(
					'name' => __('Comment color', ST_PBL),
					'id' => 'comment_color',
					'type' => 'color_picker',
					'std' => '#000000',
					'tooltip' => __('Applies to Comment', ST_PBL),
					'css' => array(
						array('.cbb-blog2-layout-links p.cbb-blog2-comment-number', 'color'),
						array('.cbb-blog2-layout-links i.cbb-blog2-comment-icon', 'color'),
					),
				),
				array(
					'name' => __('Custom Styling for Category Tag', ST_PBL),
					'id' => 'custom_tag',
					'type' => 'radio',
					'std' => 'no',
					'options' => array('no' => __('No', ST_PBL), 'yes' => __('Yes', ST_PBL)),
					'has_depend' => '1',
				),
				array(
					'name' => __('Category Style', ST_PBL),
					'id' => 'cat_style',
					'type' => 'select',
					'std' => 'default',
					'options' => array(
						'default' => __('Default', ST_PBL),
						'solid' => __('Solid', ST_PBL),
						'default-no-fill' => __('Outlined', ST_PBL),
						'rounded' => __('Rounded Solid', ST_PBL),
						'rounded-no-fill' => __('Rounded Outlined', ST_PBL),
					),
					'dependency' => array('custom_tag', '=', 'yes'),
				),
				array(
					'name' => __('Category Size', ST_PBL),
					'id' => 'cat_size',
					'type' => 'select',
					'class' => 'input-sm',
					'std' => ST_Pb_Helper_Type::get_first_option(ST_Pb_Helper_Type::get_button_size()),
					'options' => ST_Pb_Helper_Type::get_button_size(),
					'dependency' => array('custom_tag', '=', 'yes'),
				),
				array(
					'name' => __('Category Color', ST_PBL),
					'id' => 'cat_color',
					'type' => 'select',
					'std' => ST_Pb_Helper_Type::get_first_option(ST_Pb_Helper_Type::get_button_color()),
					'options' => ST_Pb_Helper_Type::get_button_color(),
					'tooltip' => __('Select the color of the button', ST_PBL),
					'dependency' => array('custom_tag', '=', 'yes'),
					'has_depend' => '1',
				),
				array(
					'name' => __('Category Custom color', ST_PBL),
					'id' => 'cat_custom_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('Applies to Category Tag', ST_PBL),
					'css' => array(
						array('.cbb-blog2-layout-links a.cbb-blog2-category-btn', 'background-color', '%value%', array(
							array('cat_style', '!=', 'default-no-fill'),
							array('cat_style', '!=', 'rounded-no-fill'),
						)),
						array('.cbb-blog2-layout-links a.cbb-blog2-category-btn:hover', 'background-color', '%value%'),
						array('.cbb-blog2-layout-links a.cbb-blog2-category-btn', 'border-color', '%value%'),
					),
					'dependency' => array(
						array('custom_tag', '=', 'yes'),
						array('cat_color', '=', 'btn-default'),
					),
				),
				array(
					'name' => __('Category Text color', ST_PBL),
					'id' => 'cat_text_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('Applies to Category Text', ST_PBL),
					'css' => array(
						array('.cbb-blog2-layout-links a.cbb-blog2-category-btn', 'color', '%value%'),
					),
					'dependency' => array(
						array('custom_tag', '=', 'yes'),
						array('cat_color', '=', 'btn-default'),
					),
				),
				array(
					'name' => __('Category hover color', ST_PBL),
					'id' => 'cat_h_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('Applies to Category Tage When Hover', ST_PBL),
					'css' => array(
						array('.cbb-blog2-layout-links a.cbb-blog2-category-btn:hover', 'background-color', '%value%'),
						array('.cbb-blog2-layout-links a.cbb-blog2-category-btn:hover', 'border-color', '%value%'),
					),
					'dependency' => array(
						array('custom_tag', '=', 'yes'),
						array('cat_color', '=', 'btn-default'),
					),
				),
				array(
					'name' => __('Category Hover Text color', ST_PBL),
					'id' => 'cat_h_text_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('Applies to Category Text When Hover', ST_PBL),
					'css' => array(
						array('.cbb-blog2-layout-links a.cbb-blog2-category-btn:hover', 'color', '%value%'),
					),
					'dependency' => array(
						array('custom_tag', '=', 'yes'),
						array('cat_color', '=', 'btn-default'),
					),
				),
				array(
					'type' => 'group_close',
				),
				array(
					'name' => 'Hover styles',
					'type' => 'group_open',
				),
				array(
					'name' => __('Title color', ST_PBL),
					'id' => 'hover_title_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('', ST_PBL),
					'css' => array('.cbb-blog2-item-inner:hover .cbb-blog2-title a', 'color'),
				),
				array(
					'name' => __('Comments color', ST_PBL),
					'id' => 'hover_link_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('Applies to Body text links, Author and Date', ST_PBL),
					'css' => array('.cbb-blog2-item-inner:hover .cbb-blog2-comment > *', 'color'),
				),
				array(
					'name' => __('Text color', ST_PBL),
					'id' => 'hover_text_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('', ST_PBL),
					'css' => array(
						array('.cbb-blog2-item-inner .cbb-blog2-text:hover', 'color'),
					),
				),
				array(
					'name' => __('Background color', ST_PBL),
					'id' => 'hover_bg_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('', ST_PBL),
					'css' => array(
						array('.cbb-blog2-item-inner:hover', 'background-color', array('opacity_from' => 'hover_bg_opacity')),
					),
				),
				array(
						'name' => __('Background opacity', ST_PBL),
						'id' => 'hover_bg_opacity',
						'type' => 'slider',
						'class' => 'st-slider',
						'std_min' => '0',
						'std_max' => '100',
						'std' => '100',
						'css' => array(''),
				),
				array(
					'name' => __('Border color', ST_PBL),
					'id' => 'hover_border_color',
					'type' => 'color_picker',
					'std' => '',
					'tooltip' => __('', ST_PBL),
					'css' => array('.cbb-blog2-item:hover', 'border-color'),
				),
				ST_Pb_Helper_Type::get_shadow_type('hover_shadow', '.cbb-blog2-item:hover'),
				array(
					'type' => 'group_close',
				),
				array(
					'name' => 'Padding',
					'type' => 'group_open',
				),
				array(
					'name' => 'Title Padding',
					'type' => 'group_open',
				),
				array(
					'name' => __('', ST_PBL),
					'container_class' => 'combo-group',
					'id' => 'title_padding',
					'type' => 'margin',
					'extended_ids' => array('title_padding_top', 'title_padding_bottom', 'title_padding_right', 'title_padding_left'),
					'title_padding_top' => array('std' => 0),
					'title_padding_bottom' => array('std' => 15),
					'title_padding_right' => array('std' => 10),
					'title_padding_left' => array('std' => 10),
				),
				array(
					'type' => 'group_close',
				),
				array(
					'name' => 'Excerpt Padding',
					'type' => 'group_open',
				),
				array(
					'name' => __('', ST_PBL),
					'container_class' => 'combo-group',
					'id' => 'excerpt_padding',
					'type' => 'margin',
					'extended_ids' => array('excerpt_padding_top', 'excerpt_padding_bottom', 'excerpt_padding_right', 'excerpt_padding_left'),
					'excerpt_padding_top' => array('std' => 5),
					'excerpt_padding_bottom' => array('std' => 15),
					'excerpt_padding_right' => array('std' => 10),
					'excerpt_padding_left' => array('std' => 10),
				),
				array(
					'type' => 'group_close',
				),
				array(
					'name' => 'Links Padding',
					'type' => 'group_open',
				),
				array(
					'name' => __('', ST_PBL),
					'container_class' => 'combo-group',
					'id' => 'links_padding',
					'type' => 'margin',
					'extended_ids' => array('links_padding_top', 'links_padding_bottom', 'links_padding_right', 'links_padding_left'),
					'links_padding_top' => array('std' => 0),
					'links_padding_bottom' => array('std' => 0),
					'links_padding_right' => array('std' => 0),
					'links_padding_left' => array('std' => 0),
				),
				array(
					'type' => 'group_close',
				),
				array(
					'name' => 'Image Padding',
					'type' => 'group_open',
				),
				array(
					'name' => __('', ST_PBL),
					'container_class' => 'combo-group',
					'id' => 'image_padding',
					'type' => 'margin',
					'extended_ids' => array('image_padding_top', 'image_padding_bottom', 'image_padding_right', 'image_padding_left'),
					'image_padding_top' => array('std' => 0),
					'image_padding_bottom' => array('std' => 0),
					'image_padding_right' => array('std' => 0),
					'image_padding_left' => array('std' => 0),
					'dependency' => array(
						array('layout_mode', '!=', 'masonry'),
						array('layout_mode', '!=', 'fitRows'),
						array('layout_mode', '!=', 'list'),
					),
				),
				//Because some posts dont have featured image so to apply only those that have featured image,we cann't use live css
				array(
					'type' => 'group_close',
				),
				array(
					'type' => 'group_close',
				),
				array(
					'name' => __('Border', ST_PBL),
					'type' => array(
						array(
							'id' => 'border_width',
							'type' => 'text_append',
							'type_input' => 'number',
							'class' => 'input-mini',
							'std' => '',
							'append' => 'px',
							'validate' => 'number',
							'parent_class' => 'combo-item input-append-inline',
							'css' => array(
								array('.cbb-blog2-item', 'border-width'),
								array('.cbb-blog2-item-inner', 'border-width'),
							),
						),
						array(
							'id' => 'border_style',
							'type' => 'select',
							'class' => 'input-sm st-border-type',
							'std' => '',
							'options' => ST_Pb_Helper_Type::get_border_styles(),
							'parent_class' => 'combo-item',
							'css' => array(
								array('.cbb-blog2-item', 'border-style'),
								array('.cbb-blog2-item-inner', 'border-style'),
							),
						),
						array(
							'id' => 'border_color',
							'type' => 'color_picker',
							'std' => '',
							'parent_class' => 'combo-item',
							'css' => array('.cbb-blog2-item', 'border-color'),
						),
					),
					'container_class' => 'combo-group',
				),
				array(
					'name' => __('Image Height', ST_PBL),
					'id' => 'img_height',
					'type' => 'text_append',
					'type_input' => 'number',
					'class' => 'input-mini',
					'std' => '',
					'append' => 'px',
					'validate' => 'number',
					'tooltip' => __('Set the height of image', ST_PBL),
					'css' => array(
						array('%id% .cbb-blog2-item img', 'height'),
						array('%id% .cbb-blog2-item.image_grid', 'height','%value% !important'),
						array('%id% .cbb-blog2-item.image_grid_masonry', 'height','%value% !important'),
					),
				),
				array(
					'name' => __('Rounded corners', ST_PBL),
					'id' => 'border_radius',
					'type' => 'text_append',
					'type_input' => 'number',
					'class' => 'input-mini',
					'std' => '',
					'append' => 'px',
					'validate' => 'number',
					'css' => array(
						array('.cbb-blog2-item', 'border-radius'),
						array('.cbb-blog2-item img', 'border-top-left-radius'),
						array('.cbb-blog2-item img', 'border-top-right-radius'),
						array('.cbb-blog2-item-inner', 'border-bottom-left-radius'),
						array('.cbb-blog2-item-inner', 'border-bottom-right-radius'),
					),
					'dependency' => array('layout_mode', '!=', 'list'),
				),
				array(
					'name' => __('Spacing between posts', ST_PBL),
					'id' => 'spacing',
					'type' => 'text_append',
					'type_input' => 'number',
					'class' => 'input-mini',
					'std' => '10',
					'append' => 'px',
					'validate' => 'number',
					'tooltip' => __('Left right spacing between posts', ST_PBL),
				),
				ST_Pb_Helper_Type::get_apprearing_animations(),
				ST_Pb_Helper_Type::get_animation_speeds(),
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
		public function element_shortcode_full($atts = null, $content = null) {
			$script = '';
			if (!empty($atts) AND is_array($atts)) {
				if (!isset($attrs['font_size_value_'])) {
					$attrs['font_size_value_'] = '';
				}
			}
			echo (in_array('image', $elements));
			$arr_params = (shortcode_atts($this->config['params'], $atts));
			extract($arr_params);
			$elements = explode('__#__', $elements);
			$this->id = $arr_params['elm_id'];
			$this->columns = (int) $columns;
			$page = get_query_var('paged');
			if (!$page) {
				$page = get_query_var('page');
			}
			$page = !empty($page) ? intval($page) : 1;
			$args = array(
				'posts_per_page' => $count,
				'offset' => $count * ($page - 1),
				'orderby' => $order_by,
				'order' => $order,
				'include' => '',
				'exclude' => '',
				'meta_key' => '',
				'meta_value' => '',
				'post_type' => 'post',
				'post_mime_type' => '',
				'post_parent' => '',
				'post_status' => 'publish',
				'suppress_filters' => true,
			);
			if ($category) {
				if( is_numeric( $category ) ) {
					// older blog used category ID instead of slug
					$args['cat'] = $category;
				} else {
					$args['category_name'] = $category;
				}
			}
			if ($tags) {
				$args['tag'] = $tags;
			}
			global $post;
			$the_query = get_posts($args);
			if (!$the_query) {
				return __('Sorry, no posts matched the criteria.', ST_PBL);
			}
			$columns_class = 'col-md-' . floor(12 / $columns);
			$columns_class_xs = '';
			if ($columns > 3) {
				$columns_class_xs = 'col-sm-6 col-xs-12';
			} elseif ($columns > 1) {
			$columns_class_xs = 'col-sm-6 col-xs-12';
		}
		$isotope_layout_mode = $layout_mode;
		if ($layout_mode == 'image_grid') {
			$isotope_layout_mode = 'fitRows';
		}
		if ($layout_mode == 'image_grid_masonry') {
			$isotope_layout_mode = 'masonry';
		}
		if ($layout_mode == 'list') {
			$isotope_layout_mode = '';
			$columns_class = $columns_class_xs = '';
		}
		$this->layout_mode = $layout_mode;
		$output = '<div id="' . $this->id . '" class="cbb-blog2-instance" data-layout_mode="' . $isotope_layout_mode . '">';
		$output .= '<ul id="cbb-blog2-' . $this->id . '" class="cbb-blog2 cbb-blog2-layout-' . $layout_mode . ' row ' . implode(' cbb-blog2-has-', $elements) . '">';
		$output .= $items;
		foreach ($the_query as $post): setup_postdata($post);
			$image = '';
			if (in_array('image', $elements)) {
				$image = $this->post_image($columns, $layout_mode);
			}
			$output .= '<li class="isotope-blog2-item ' . $columns_class . ' ' . $columns_class_xs . '">';
			
			preg_match_all('/(src|width|height)=("[^"]*")/i', $image, $result);
			$search = array('=','"');
			$change = array(':',''); 
			$image_grid_src = str_replace('src=','',$result[0][0]);
			$image_grid_height  = str_replace($search,$change,$result[0][2]);
			$image_pad = 'style="padding-top:'.$image_padding_top.'px;padding-right:'.$image_padding_right.'px;padding-bottom:'.$image_padding_bottom.'px;padding-left:'.$image_padding_left.'px;"';
			if ($layout_mode == 'image_grid' || $layout_mode == 'image_grid_masonry') {
				if ($image_grid_height != 'height:0') {
					if ($excerpt_length > 400 && $columns > 2) {
						$image_grid_height = 'height:auto;';
					}
					$image_grid_style = "style='background: url(". $image_grid_src .") no-repeat center;background-size: cover;border:0;". $image_grid_height ."px;margin:".$spacing."px'";
				 } 
				 else {				 	
				 	$image_grid_style = 'style="margin:'.$spacing.'px;"';
				}
				$output .= "<div class='cbb-blog2-item " . $layout_mode . "' " . $image_grid_style . ">";
			}
			else {
				$output .= '<div class="cbb-blog2-item" style="margin:'.$spacing.'px;">';
			}
			if ($image) {
				if ($layout_mode != 'image_grid' && $layout_mode != 'image_grid_masonry') {
					if ($image_grid_height != 'height:0') {
						$output .= '<a class="cbb-blog2-img " '.$image_pad.' href="' . get_the_permalink() . '">' . $image . '</a>';
					}
				}
			}
			$output .= '<div class="cbb-blog2-item-inner ' . ((!$image) ? 'cbb-blog2-item-no-image' : '') . '">';
			if (in_array('title', $elements)) {
				// For custom Title Style
				if (!empty($arr_params) AND is_array($arr_params)) {
					if ($arr_params['font'] == 'inherit' || $arr_params['font'] == 'Inherit') {
						unset($arr_params['font_face_type']);
						unset($arr_params['font_face_value']);
						unset($arr_params['font_size_value_']);
						unset($arr_params['font_style']);
						unset($arr_params['color']);
						if ($arr_params['normal_font_color']) {
							$arr_params['color'] = $arr_params['normal_font_color'];
						}
					}
					if (isset($arr_params['font_size_value_']) && $arr_params['font_size_value_'] == '') {
						unset($arr_params['font_size_value_']);
					}
					if ($arr_params['border_bottom_width_value_'] == '') {
						unset($arr_params['border_bottom_width_value_']);
						unset($arr_params['border_bottom_style']);
						unset($arr_params['border_bottom_color']);
					}
					if ($arr_params['padding_bottom_value_'] == '') {
						unset($arr_params['padding_bottom_value_']);
					}
					if ($arr_params['text_align'] == 'inherit' || $arr_params['text_align'] == 'Inherit') {
						unset($arr_params['text_align']);
					}
				}
				$font_style = ST_Pb_Helper_Functions::get_font_family($arr_params);
				$title_pad = 'padding-top:'.$title_padding_top.'px;padding-right:'.$title_padding_right.'px;padding-bottom:'.$title_padding_bottom.'px;padding-left:'.$title_padding_left.'px;';
				$blog_title_font_style = 'style="'.$font_style[0].';'.$title_pad.'"';
				$output .= '<'.$heading_tag.' class="cbb-blog2-title"'.$blog_title_font_style.'><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></'.$heading_tag.'>';
				$output .= $font_style[1];
			}
			if (in_array('author', $elements) or in_array('date', $elements)) {
				$output .= '<p class="byline vcard" style="padding-top:'.$excerpt_padding_top.'px;padding-right:'.$excerpt_padding_right.'px;padding-left:'.$excerpt_padding_left.'px;">';
				if (in_array('author', $elements) && function_exists('get_the_author_posts_link')) {
					$output .= 'by <span class="author">' . get_the_author_posts_link() . '</span>';
				}
				if (in_array('author', $elements) && in_array('date', $elements)) {
					$output .= ' - ';
				}
				if (in_array('date', $elements)) {
					$output .= '<time class="updated" datetime="' . esc_attr(get_the_date('c')) . '">' . get_the_time(get_option('date_format')) . '</time>';
				}
				$output .= '</span>';
			}
			if (in_array('excerpt', $elements)) {
				if ($post->post_excerpt) {
					$excerpt = $post->post_excerpt;
				} else {
					$excerpt = $post->post_content;
					$excerpt_tab_content = ST_Pb_Helper_Shortcode::get_builder_tab_content(
						null,
						$post,
						null,
						null,
						null,
						false// dont process content as we only use it to make excerpt
					);
					if ($excerpt_tab_content) {
						// only get text element
						// TO-DO: dont match till ending [/st_text]
						preg_match_all('/\[st_text[^\]]*]([^\[]*)\[\/st_text[^\]]*]/uis', $excerpt_tab_content, $matches);
						$excerpt = implode('<br />', $matches[1]);
					}
				}
				$excerpt_pad = 'style="padding-right:'.$excerpt_padding_right.'px;padding-bottom:'.$excerpt_padding_bottom.'px;padding-left:'.$excerpt_padding_left.'px;"';
			}
			//For Category Option
			if (in_array('category_btn', $elements)) {
				// Defining Categories
				$blog2_categories = get_the_terms(get_the_ID(),'category');
				$blog2_categories_link = get_category_link($blog2_categories[0]->term_id);
				$categories_name = $blog2_categories[0]->name;
				$cat_disabled = '';
				if ($categories_name == '') {
					$cat_disabled = 'style=display:none;';
				}
				$blog2_categories_output = '<div class="cbb-blog2-layout-category-btn"'. $cat_disabled .'><a href="'.$blog2_categories_link.'"class="cbb-blog2-category-btn btn-custom '.$cat_size.' btn-style-'. $cat_style .' '.$cat_color.'">' . $categories_name . '</a>';
			}
			//For Comment Option
			if (in_array('comments', $elements)) {
				$blog2_comments = get_comments_number();
				if ($blog2_comments == 0) {
					$blog2_comments = "No Comments";
				} else if ($blog2_comments == 1) {
					$blog2_comments .= " Comment";	
				} else if ($blog2_comments > 1) {
					$blog2_comments .= " Comments";	
				}
				$blog2_comments_output = '<div class="cbb-blog2-comment"><i class="cbb-blog2-comment-icon fa fa-comments-o"></i><p class="cbb-blog2-comment-number">'. $blog2_comments .'</p></div>';
			}
			if (in_array('category_btn', $elements) || in_array('comments', $elements) || $read_more_style == 'button') {
				$links_pad = 'style="padding-top:'.$links_padding_top.'px;padding-right:'.$links_padding_right.'px;padding-bottom:'.$links_padding_bottom.'px;padding-left:'.$links_padding_left.'px;"';
			}
				$excerpt_end = '';
				$excerpt_url = get_the_permalink();		
				$excerpt_end_btn = 'style=';
				if ($excerpt_url) {
					switch ($read_more_style) {
					case 'button':
						$btn_class = 'cbb-blog2-btn btn-custom '.$button_size;
						$btn_class .= ' btn-style-' . $rmb_style;
						$btn_class .= ' ' . $rmb_color;
						if (!(in_array('category_btn', $elements)) && !(in_array('comments', $elements))) {
							$btn_aln_left = 'btn-left';
						}
						$excerpt_end = '<div class="cbb-blog2-layout-links '.$btn_aln_left.'"'.$links_pad.'><div class="cbb-blog2-layout-btn"'.$excerpt_end_btn.'><a href="' . $excerpt_url . '" class="' . $btn_class . '">' . $rmb_text . '</a></div>';
						break;
					default:
						$excerpt_end = '<a href="' . $excerpt_url . '">[...]</a>';
						break;
					}
				}
				// $output .= '<div class="cbb-blog2-text" '.$excerpt_pad.'>' . $this->the_excerpt_max_charlength($excerpt_length, strip_tags(do_shortcode($excerpt)), $excerpt_end, $read_more_style) . '</div>';

				$output .= '<div class="cbb-blog2-text" '.$excerpt_pad.'>' . $excerpt . '</div>';
				if ($read_more_style == 'dots') {
					$excerpt_end_btn .= '"display:none";';
				}
				if ($read_more_style != 'dots') {
					$output .= $excerpt_end;
				}
				else {
					$output .= '<div class="cbb-blog2-layout-links cbb-blog2-btn-dots" '.$links_pad.'>';
				}
				$output .= $blog2_comments_output;
				$output .= $blog2_categories_output;
			$output .= '</div>';
			$output .= '</div></li>';
		endforeach;
		$output .= '</ul>';
		if ($pagination == 'yes' && function_exists("emm_paginate")) {
			$args['posts_per_page'] = '-1';
			$args['offset'] = '0';
			$the_query = get_posts($args);
			$pages = intval(ceil(count($the_query) / $count));
			$output .= emm_paginate(array(
				'page' => $page,
				'pages' => $pages,
				'echo' => 0,
			));
		}
		$output .= '</div>';
		wp_reset_postdata();
		$html_elements = $output;
		//echo '<pre>'.print_r($arr_params, TRUE).'</pre>';
		return $this->element_wrapper($script . $html_elements, $arr_params);
	}
	public function post_image($columns) {
		$attach_id = get_post_thumbnail_id(get_the_ID());
		if (!$attach_id) {
			return;
		}
		$img_size = $this->get_size($columns);
		$post_thumbnail = ST_Pb_Helper_Functions::getImageBySize(array(
			'attach_id' => $attach_id,
			'thumb_size' => $img_size,
			'class' => $thumb_style,
		));
		if (!$post_thumbnail) {
			return;
		}
		return $post_thumbnail['thumbnail'];
	}
	public function get_size($columns, $container_size = 1100) {
		$size = $container_size / $columns;
		$size = floor($size);
		$size_h = $size_w = $size;
		if ($size_h > 400) {
			$size_h = 400;
		}
		return $size_w . 'x' . $size_h;
	}
	// public function the_excerpt_max_charlength($charlength, $excerpt, $excerpt_end = '', $read_more_style = '') {
	// 	$out = '';
	// 	if (!$charlength) {
	// 		return;
	// 	}
	// 	$charlength++;
	// 	if (mb_strlen($excerpt) > $charlength) {
	// 		$subex = mb_substr($excerpt, 0, $charlength - 5);
	// 		$exwords = explode(' ', $subex);
	// 		$excut = -(mb_strlen($exwords[count($exwords) - 1]));
	// 		if ($excut < 0) {
	// 			$out .= mb_substr($subex, 0, $excut);
	// 		} else {
	// 			$out .= $subex;
	// 		}
	// 		if ($excerpt_end) {
	// 			if ($read_more_style == 'dots') {					
	// 				$out .= $excerpt_end;
	// 			}
	// 		} else {
	// 			$out .= '[...]';
	// 		}
	// 	} else {
	// 		$out .= $excerpt;
	// 	}
	// 	return $out;
	// }
}
endif;