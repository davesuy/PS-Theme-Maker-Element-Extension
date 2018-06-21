<?php

if ( ! class_exists( 'ST_Item_Carousel' ) ) {

	/**
	 * Create Carousel element
	 *
	 * @package  ProStyler Builder Shortcodes
	 * @since    1.0.0
	 */
	class ST_Item_Carousel extends ST_Pb_Shortcode_Child {

		public function __construct() {
			parent::__construct();
		}

		/**
		 * DEFINE configuration information of shortcode
		 */
		public function element_config() {
			$this->config['shortcode'] = strtolower( __CLASS__ );
			$this->config['exception'] = array(
				'data-modal-title' => __( 'Carousel Item', ST_PBL ),

			);

			// Inline edit for sub item
			$this->config['edit_inline'] = true;
		}

		/**
		 * DEFINE setting options of shortcode
		 */
		public function element_items() {
			$this->items = array(
				'Notab' => array(
			array(
						'name'    => __( 'Image File', ST_PBL ),
						'id'      => 'image_file',
						'type'    => 'select_media',
						'std'     => '',
						'class'   => 'jsn-input-large-fluid',
			),
			array(
						'name'  => __( 'Heading', ST_PBL ),
						'id'    => 'heading',
						'type'  => 'text_field',
						'class' => 'input-sm',
						'role'  => 'title',
						'std'   => __( ST_Pb_Utils_Placeholder::add_placeholder( 'Carousel Item %s', 'index' ), ST_PBL ),
			),
			array(
						'name' => __( 'Body', ST_PBL ),
						'id'   => 'body',
						'role' => 'content',
						'type' => 'tiny_mce',
						//'container_class' => 'st_tinymce_replace',
						'std'  => ST_Pb_Helper_Type::lorem_text(12) . '<a href="#"> link</a>',
			),
			array(
						'name'      => __( 'Icon', ST_PBL ),
						'id'        => 'icon',
						'type'      => 'icons',
						'std'       => '',
						'role'      => 'title_prepend',
						'title_prepend_type' => 'icon',
			),
			)
			);
		}

		/**
		 * DEFINE shortcode content
		 *
		 * @param type $atts
		 * @param type $content
		 */
		public function element_shortcode_full( $atts = null, $content = null ) {
			// add params coming from parent to defaults
			$this->config['params']['heading_tag'] = '';
			
			$atts = shortcode_atts( $this->config['params'], $atts );
			extract( $atts );
			$content_class = ! empty( $image_file ) ? 'carousel-caption' : 'carousel-content';
			$img           = ! empty( $image_file ) ? "<img width='{WIDTH}' height='{HEIGHT}' src='$image_file' style='height : {HEIGHT}px;'>" : '';

			// remove image shortcode in content
			// $content = ST_Pb_Helper_Shortcode::remove_wr_shortcodes( $content, 'st_image' );

			$inner_content = '';
			if($content_source == 'page_embed')
			{
				if(isset($page) && trim($page))
				{	
					$inner_content = '<!--page_embed=['.$page.']-->';
				}
			}
			else
			{
				$inner_content = chiedolabs_shortcode_autop( $content );
			}

			ST_Pb_Helper_Functions::heading_icon( $heading, $icon, true );
			$heading       = trim( $heading );
			$inner_content = trim( $inner_content );
			
			if($icon) $icon = "<i class='$icon'></i>";

			if ( empty( $heading ) && empty( $inner_content ) ) {
				$html_content = "";
			} else {
				$html_content = "<div class='$content_class'>";
				$html_content .= ( ! empty( $heading ) ) ? "<".$heading_tag.">$icon$heading</".$heading_tag.">" : '';
				if($content_source == 'page_embed')
				{
					$html_content .= ( ! empty( $inner_content ) ) ? "
						<div class='carousel-page-body'>
							{$inner_content}
						</div>" : '';
				}
				else {
					$html_content .= ( ! empty( $inner_content ) ) ? "<p>{$inner_content}</p>" : '';
				}
				$html_content .= "</div>";
			}

			return "<div class='{active} item'>{$img}{$html_content}</div><!--seperate-->";
		}

	}

}
