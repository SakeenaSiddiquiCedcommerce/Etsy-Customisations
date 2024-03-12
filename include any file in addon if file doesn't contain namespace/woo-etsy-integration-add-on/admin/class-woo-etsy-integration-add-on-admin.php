<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cedcommerce.com/
 * @since      1.0.0
 *
 * @package    Woo_Etsy_Integration_Add_On
 * @subpackage Woo_Etsy_Integration_Add_On/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woo_Etsy_Integration_Add_On
 * @subpackage Woo_Etsy_Integration_Add_On/admin
 * @author     cedcommerce <dev.ambikesh@gmail.com>
 */
class Woo_Etsy_Integration_Add_On_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	public $var_img_data;
	public $offering_var_array;
	private $l_id;
	public $var_array = array();
	public $last_image_delete = false;
	public $ced_e_payload = array();
	public $pro_data = array();
	public $product_type = '';
	// public $product_data = array();
    // public $formatted_data = array();
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		// add_filter('ced_etsy_add_size_chart_image_section', array( $this, 'size_chart_image_section' ), 10, 1);
		// add_filter('ced_etsy_modify_pro_title', array( $this, 'modifying_product_title' ), 99,  3);
		// add_filter('ced_etsy_modify_pro_dec', array( $this, 'modifying_product_description' ), 99,  3);
		add_filter('ced_etsy_modify_pro_tags', array( $this, 'modifying_product_tags' ), 99,  2);
		add_filter( 'ced_etsy_modify_template_fields', array( $this, 'modifying_template_fields' ), 99, 2 );
		add_filter('ced_etsy_modify_product_export_settings', array($this, 'modifying_product_export_setting'), 99, 1 );
		add_filter('ced_etsy_modify_formated_data_for_variations', array($this, 'modifying_formated_data_for_variations'), 99, 2 );
		add_filter('ced_etsy_modify_ced_variation_details', array( $this, 'modifying_variation_details' ), 99, 4 );
        // add_filter('ced_etsy_modify_pro_quantity', array( $this, 'modifying_pro_quantity' ), 99, 3);
		add_filter('ced_etsy_modify_product_upload', array( $this, 'modifying_product_upload' ), 99, 3);
        // add_filter('ced_etsy_modify_pro_price', array($this, 'modifying_pro_price'), 99, 2);
		add_filter('ced_etsy_modify_upload_variation_sku_to_etsy', array( $this, 'modifying_update_variation_sku_to_etsy' ),99 ,5 );
		add_filter('ced_etsy_modify_product_image_upload', array( $this, 'modifying_product_image_upload' ),99,  4 );
		add_filter('ced_etsy_modify_image_update', array( $this, 'modifying_product_image_update' ), 99, 2 );
		// add_filter( 'ced_etsy_modify_settings_tab', array( $this, 'modifying_settings_tabs' ), 2, 99 );
		// add_filter( 'ced_etsy_modify_settings_fields', array( $this, 'modifying_settings_tab_fields' ), 2, 99 );
		// add_filter( 'ced_etsy_modify_inventory_update', array( $this, 'modifying_inventory_update' ), 99, 3);
// 		add_filter('ced_etsy_modify_variation_update', array( $this, 'modifying_variation_update' ), 99, 5 );
		add_filter('ced_etsy_modify_create_variable_product', array($this, 'modifying_create_variable_product'), 99, 4);
		add_filter('ced_etsy_modify_insert_product_variation', array($this, 'modifying_insert_product_variation'), 99, 5);
		add_filter('ced_etsy_modify_create_product_images', array($this, 'modifying_create_product_images'), 99, 3);
		add_filter('ced_etsy_modify_assign_var_images', array($this, 'modifying_assign_var_images'), 99, 4);
	   
	}



   /**
    * Modifies settings tab fields for WooCommerce Etsy Integration.
    * This function adds a new field to select WooCommerce global attributes for uploading images on variations.
    * @param  : $tab_fiels is an array of global setting fields 
    * @return : This function will return an array with modified setting fields
    */



   // public function modifying_settings_tab_fields( $tab_fiels ){
   // 	global $wc_product_attributes;
   // 	$attribute_taxonomies = wc_get_attribute_taxonomies();
   // 	if ( ! empty( $attribute_taxonomies ) ) {
   // 		foreach ( $attribute_taxonomies as $attr_taxonomy ) { 
   // 			$all_attributes[wc_attribute_taxonomy_name( $attr_taxonomy->attribute_name )] = $attr_taxonomy->attribute_label ? $attr_taxonomy->attribute_label : $attr_taxonomy->attribute_name;
   // 		}
   // 	}
   // 	$tab_fiels['ced_etsy_select_global_attributes'] = array(
   // 		array(
   // 			'label'   => __( 'Select Woocommerce Global Attribute To Upload Images', 'woocommerce-etsy-integration' ),
   // 			'tooltip' => 'Choose the global attirbute to upload the image on the variations.',
   // 			'type'    => 'select',
   // 			'name'    => 'woo_global_attributes',
   // 			'is_multi'=>true,
   // 			'options' => $all_attributes,
   // 		)
   // 	);
   // 	return $tab_fiels;
   // }



   /** 
    * Modifies the tab sections for global settings page
    * This fuction will add an extra tab section for variation image details on global settings
    * @param  : $tabs is an array of global settings tabs
    * @return : This fuction 
    */



   // public function modifying_settings_tabs( $tabs ) {
   // 	$tabs['ced_etsy_select_global_attributes'] = array(
   // 		'name' => __( 'Select Global Attributes', 'woocommerce-etsy-integration' ),
   // 		'desc' => 'This is proudct import setting where you can set setting for importing products',

   // 	);
   // 	return $tabs;
   // }



   /** 
    * This function is used to modify the product export settting function on view settings page
    * @param  : $shop_name is a variable contaning only shop name
    *  
    */


     public function modifying_product_export_setting ($shop_name) {
     	?>
     	<div class="ced-etsy-integ-wrapper">
     		<input class="ced-faq-trigger" id="ced-etsy-pro-exprt-wrapper" type="checkbox" checked /><label class="ced-etsy-settng-title" for="ced-etsy-pro-exprt-wrapper"  checked><?php esc_html_e( 'Product Export Settings', 'woocommerce-etsy-integration' ); ?></label>
     		<div class="ced-etsy-settng-content-wrap">
     			<div class="ced-etsy-settng-content-holder">
     				<div class="ced-form-accordian-wrap">
     					<div class="wc-progress-form-content woocommerce-importer">
     						<header>
     							<table class="form-table ced-settings widefat">
     								<tbody>
     									<?php wp_nonce_field( 'global_settings', 'global_settings_submit' ); ?>
     									<?php
  								/**
  								 * -------------------------------------
  								 *  INCLUDING PRODUCT FIELDS ARRAY FILE
  								 * -------------------------------------
  								 */

  								$shop_name                   = isset( $_GET['shop_name'] ) ? sanitize_text_field( wp_unslash( $_GET['shop_name'] ) ) : '';
  								$product_field_instance      = \Cedcommerce\Template\Ced_Template_Product_Fields::get_instance();
  								$settings                    = $product_field_instance->get_custom_products_fields();
  								$product_fields['required']  = isset( $settings['required'] ) ? $settings['required'] : array();
  								$product_fields['variation'] = isset( $settings['variation'] ) ? $settings['variation'] : array();
  								$ced_etsy_global_data        = get_option( 'ced_etsy_global_settings', array() );
  								$setup_wiz_gnrl_stngs        = get_option( 'ced_etsy_setup_wiz_req_attrs_' . $shop_name, array() );
  								$setup_wiz_req_attr          = isset( $setup_wiz_gnrl_stngs['ced_etsy_setup_wiz_req_attr'] ) ? $setup_wiz_gnrl_stngs['ced_etsy_setup_wiz_req_attr'] : array();
  								$saved_pro_datas             = isset( $ced_etsy_global_data[ $shop_name ]['product_data'] ) ? $ced_etsy_global_data[ $shop_name ]['product_data'] : array();

  								if ( ! empty( $product_fields ) ) {
  									echo '<input type="hidden" value="' . esc_url( admin_url( 'admin.php?page=sales_channel&channel=etsy&section=add-shipping-profile&shop_name=' . $shop_name ) ) . '" id="ced_create_new_shipping_profile" >';
  									echo "<table class='form-table ced-settings widefat' style='' id='required' class='ced_etsy_setting_body'>";
  									?>
  									<tr valign="top">
  										<th colspan="" scope="row" class="titledesc rquired"><label for="woocommerce_currency"><?php esc_html_e( 'Required Attributes', 'woocommerce-etsy-integration' ); ?></label></th>
  										<th colspan="" scope="row" class="titledesc" ><label for="woocommerce_currency"><?php esc_html_e( 'Default Value', 'woocommerce-etsy-integration' ); ?></label></th>
  										<th></th>
  									</tr>
  									<?php
  									foreach ( $product_fields as $field_datas => $field_data_mul) {
  										foreach($field_data_mul as $field_data){
  											if ( '_umb_etsy_category' == $field_data['id'] ) {
  												continue;
  											}
  											$field_id = isset( $field_data['id'] ) ? $field_data['id'] : '';
  											echo '<tr class="form-field _umb_id_type_field" valign="top">';
  											$label        = isset( $field_data['fields']['label'] ) ? $field_data['fields']['label'] : '';
  											$field_id     = trim( $field_id, '_' );
  											$category_id  = '';
  											$product_id   = '';
  											$market_place = 'ced_etsy_required_common';
  											$description  = isset( $field_data['fields']['description'] ) ? $field_data['fields']['description'] : '';
  											$required     = isset( $field_data['fields']['is_required'] ) ? (bool) $field_data['fields']['is_required'] : '';
  											$index_to_use = 0;
  											$default = isset( $setup_wiz_req_attr[ $field_data['fields']['id'] ] ) && ! empty( $setup_wiz_req_attr[ $field_data['fields']['id'] ] ) ? $setup_wiz_req_attr[ $field_data['fields']['id'] ] : '';
  											if ( empty( $default ) ) {
  												$default = isset( $saved_pro_datas[ $field_data['fields']['id'] ]['default'] ) ? $saved_pro_datas[ $field_data['fields']['id'] ]['default'] : $field_data['fields']['default'];
  											}

  											$field_value = array(
  												'case'  => 'profile',
  												'value' => trim( $default ),
  											);

  											$value_for_dropdown = isset( $field_data['fields']['options'] ) ? $field_data['fields']['options'] : array();
  											$product_field_instance->renderDropdownHTML( $field_id, $label, $value_for_dropdown, $category_id, $product_id, $market_place, $description, $index_to_use, $field_value, $required, $field_data['id'] );
  											echo '</tr>';
  										}
  									}
  									echo '</tbody>';
  									echo '</table>';
  								}
  								?>
  							</header>
  						</div>
  					</div>
  				</div>
  			</div>
  		</div>
  		<?php
  	}


   





   /** 
    * This function will return formatted data for listings
    * @param  : $product_id is a parameter containing woocommerce products id
    * @param  : $shop_name is a parameter containing shop name
    * @return : This function will return the formatted data for the product that you go to upload on Etsy
    * 
    */



   public function modifying_formated_data_for_variations( $product_id, $shop_name) {
   	$ced_e_payload  = new Cedcommerce\Product\Ced_Product_Payload( $product_id , $shop_name );
   	$ced_e_payload->ced_etsy_check_profile( $product_id, $shop_name );
   	$ced_e_payload->product_id       = $product_id;
   	$product_field_instance = \Cedcommerce\Template\Ced_Template_Product_Fields::get_instance();
   	$etsy_data_field        = $product_field_instance->get_custom_products_fields();
   
   	$ced_e_payload->pro_data         = array();
   	$sections               = array( 'required', 'recommended', 'optional', 'shipping', 'personalization' );

   	$ced_e_payload->is_downloadable = isset( $ced_e_payload->product['downloadable'] ) ? $ced_e_payload->product['downloadable'] : 0;
   	if ( $ced_e_payload->is_downloadable ) {
   		$ced_e_payload->s = isset( $ced_e_payload->product['downloads'] ) ? $ced_e_payload->product['downloads'] : array();
   	}
   	foreach ( $etsy_data_field[ 'variation' ] as $section_attributes ) {
   		$meta_key = $section_attributes['id'];
      				$pro_val  = get_post_meta( $product_id, $meta_key, true );// getting info from product level

      				if ( '' == $pro_val ) {
      					$pro_val = $ced_e_payload->fetch_meta_value( $product_id, $meta_key );// getting info from profile level
      				}
      				if ( '' == $pro_val ) {
      					$pro_val = isset( $ced_e_payload->ced_global_settings['product_data'][ $meta_key ]['default'] ) ? $ced_e_payload->ced_global_settings['product_data'][ $meta_key ]['default'] : '';// getting info from global level
      				}
      				if ( '' == $pro_val ) {
      					$metakey = isset( $ced_e_payload->ced_global_settings['product_data'][ $meta_key ]['metakey'] ) ? $ced_e_payload->ced_global_settings['product_data'][ $meta_key ]['metakey'] : '';// getting info from global level
      					if ( ! empty( $metakey ) ) {
      						$pro_val = $ced_e_payload->fetch_meta_value( $product_id, $metakey );// getting info from global level
      					}
      				}

      				$this->var_img_data[ trim( str_replace( '_ced_etsy_', ' ', $meta_key ) ) ] = ! empty( $pro_val ) ? $pro_val : '';



      			}

      			foreach ( $sections as $section ) {
      				foreach ( $etsy_data_field[ $section ] as $section_attributes ) {

      					$ced_etsy_settings_category = get_option( 'ced_etsy_settings_category', array() );
      					if ( isset( $ced_etsy_settings_category[ $section ] ) ) {
      						$ced_e_payload->{$section} = true;
      					} else {
      						$ced_e_payload->{$section} = false;
      					}

      					$meta_key = $section_attributes['id'];
      				$pro_val  = get_post_meta( $product_id, $meta_key, true );// getting info from product level
      				if ( '' == $pro_val ) {
      					$pro_val = $ced_e_payload->fetch_meta_value( $product_id, $meta_key );// getting info from profile level
      				}
      				if ( '' == $pro_val ) {
      					$pro_val = isset( $ced_e_payload->ced_global_settings['product_data'][ $meta_key ]['default'] ) ? $ced_e_payload->ced_global_settings['product_data'][ $meta_key ]['default'] : '';// getting info from global level
      				}
      				if ( '' == $pro_val ) {
      					$metakey = isset( $ced_e_payload->ced_global_settings['product_data'][ $meta_key ]['metakey'] ) ? $ced_e_payload->ced_global_settings['product_data'][ $meta_key ]['metakey'] : '';// getting info from global level
      					if ( ! empty( $metakey ) ) {
      						$pro_val = $ced_e_payload->fetch_meta_value( $product_id, $metakey );// getting info from global level
      					}
      				}
      				$ced_e_payload->pro_data[ trim( str_replace( '_ced_etsy_', ' ', $meta_key ) ) ] = ! empty( $pro_val ) ? $pro_val : '';

      				$this->pro_data[ trim( str_replace( '_ced_etsy_', ' ', $meta_key ) ) ] = ! empty( $pro_val ) ? $pro_val : '';

      			}
      		}

      		if ( ! $ced_e_payload->is_profile_assing ) {
      			$ced_e_payload->error['has_error'] = true;
      			$ced_e_payload->error['error']     = 'Template not assigned';
      			return $ced_e_payload->error;
      		}

      		if ( ! $ced_e_payload->prepare_required_fields() ) {
      			return $ced_e_payload->error;
      		}

      		$ced_e_payload->prepare_rec_opt_ship_per_fields();

      		if ( $ced_e_payload->is_downloadable ) {
      			$ced_e_payload->product_arguements['type'] = 'download';
      		}
      		
      		return $ced_e_payload->product_arguements;
      	}




   /** 
    * This function will add an extra field on product field array
    * @param  : $required_fields is an array of products fields
    * @return : This funcion will return  an array of product fields with an extra field of size chart url  into optional section  on both places(Global settings and Profile edit page)
    */



	// public function size_chart_image_section($required_fields) {
	// 	$additional_filld = array(
	// 					'type'     => '_text_input',
	// 					'id'       => '_ced_etsy_custom_image',
	// 					'fields'   => array(
	// 						'id'          => '_ced_etsy_custom_image',
	// 						'label'       => __( 'Size Chart URL ', 'woocommerce-etsy-integration' ),
	// 						'desc_tip'    => true,
	// 						'description' => __( 'Enter size chart static url' ),
	// 						'type'        => 'text',
	// 						'is_required' => false,
	// 						'class'       => 'wc_input_price',
	// 						'default'     => '',
	// 					),
	// 					'required' => false,
	// 				);
	// 	$required_fields['optional'][] = $additional_filld;
	// 	return $required_fields;
	// }





    /** 
     * This function will return two variation fields with variation section on global setting and profile edit section
     * @param  : $fields is an array of product fields for global setting and  profile edit page
     * @return : This function will return an array with variation field
     * 
     */ 




    public function modifying_template_fields( $fields ){
    	$attributes = array();
    	foreach (wc_get_attribute_taxonomies() as $value) {
    		$attributes[$value->attribute_label] = $value->attribute_label;
    	}
    	$fields['variation'] = array(
    		array(
    			'type'   => '_select',
    			'id'     => '_ced_etsy_variation_images',
    			'fields' => array(
    				'id'          => '_ced_etsy_variation_images',
    				'label'       => __( 'Sync variation images', 'woocommerce-etsy-integration' ),
    				'desc_tip'    => true,
    				'description' => __( 'Variation images are also synced during product upload process. Default is no.', 'woocommerce-etsy-integration' ),
    				'type'        => 'select',
    				'options'     => array(
    					'yes'  => 'Yes',
    					'no' => 'No',
    				),
    				'is_required' => false,
    				'class'       => 'wc_input_price',
    				'default'     => 'no',
    				'show_mapping'     => false,
    			),
    		),
    		array(
    			'type'   => '_select',
    			'id'     => '_ced_etsy_variation_images_attr',
    			'fields' => array(
    				'id'          => '_ced_etsy_variation_images_attr',
    				'label'       => __( 'Image attribute', 'woocommerce-etsy-integration' ),
    				'desc_tip'    => true,
    				'description' => __( 'Choose the variation attribute you want to link your photos to. Keep in mind: Photos help shoppers feel more confident about buying your item.', 'woocommerce-etsy-integration' ),
    				'type'        => 'select',
    				'options'     =>  $attributes,
    				'is_required' => false,
    				'class'       => 'wc_input_price',
    				'default'     => '',
    				'show_mapping'     => false,
    			),
    		),
    	);


    	return $fields;

    }




   /** 
    * This function will add a field of global attribute to upload the variation images
    * @param  : $woo_required_fields is an array of products filed 
    * @return : This function will return an array og product field with an extra variation image attributes field
    * 
    */



   // public function woocommerce_img_attr_field_on_glbal_setting($woo_required_fields) {
   // 	global $wc_product_attributes;
   // 	$attribute_taxonomies = wc_get_attribute_taxonomies();
   // 	if ( ! empty( $attribute_taxonomies ) ) {
   // 		foreach ( $attribute_taxonomies as $attr_taxonomy ) { 
   // 			$all_attributes[wc_attribute_taxonomy_name( $attr_taxonomy->attribute_name )] = $attr_taxonomy->attribute_label ? $attr_taxonomy->attribute_label : $attr_taxonomy->attribute_name;
   // 		}
   // 	}
   // 	$global_setting = array(
   // 		'label'   => __( 'Select Woocommerce Global Attribute To Upload Images', 'woocommerce-etsy-integration' ),
   // 		'tooltip' => 'Choose the global attirbute to upload the image on the variations.',
   // 		'type'    => 'select',
   // 		'name'    => 'woo_global_attributes',
   // 		'is_multi'=>true,
   // 		'options' => $all_attributes,
   // 	);
   // 	$woo_required_fields['ced_etsy_select_global_attributes'][] = $global_setting;
   // 	return $woo_required_fields;
   // }





  /** 
   * Modify the product image  upload function for
   * @param :  $p_id parameter contain Woocommerce product id  
   * @param :  $shop_name parameter contain shop name 
   * @param :  $listing_id parameter contain Etsy id of listing
   * 
   */ 




  public function modifying_product_image_upload( $p_id, $shop_name, $listing_id) {
  	$upload            = new \Cedcommerce\Product\Ced_Product_Upload();
  	$shop_id           =  get_etsy_shop_id($shop_name);
  	if ( empty( $p_id ) || empty( $shop_name ) ) {
  		return;
  	}
  	$this->ced_product = isset( $this->ced_product ) ? $this->ced_product : wc_get_product( $p_id );
  	$prnt_img_id       = get_post_thumbnail_id( $p_id );
  	if ( WC()->version < '3.0.0' ) {
  		$attachment_ids = $this->ced_product->get_gallery_attachment_ids();
  	} else {
  		$attachment_ids = $this->ced_product->get_gallery_image_ids();
  	}
  	$previous_thum_ids = get_post_meta( $p_id, 'ced_etsy_previous_thumb_ids' . $listing_id, true );
  	if ( empty( $previous_thum_ids ) || ! is_array( $previous_thum_ids ) ) {
  		$previous_thum_ids = array();
  	}
  	$attachment_ids = array_slice( $attachment_ids, 0, 9 );
  	if ( ! empty( $attachment_ids ) ) {
  		foreach ( array_reverse( $attachment_ids ) as $attachment_id ) {
  			if ( isset( $previous_thum_ids[ $attachment_id ] ) ) {
  				continue;
  			}

						/*
						|=======================
						| UPLOAD GALLERY IMAGES
						|=======================
						*/
						$image_result = $upload->do_image_upload( $listing_id, $p_id, $attachment_id, $shop_name );
						if ( isset( $image_result['listing_image_id'] ) ) {
							$previous_thum_ids[ $attachment_id ] = $image_result['listing_image_id'];
							update_post_meta( $p_id, 'ced_etsy_previous_thumb_ids' . $listing_id, $previous_thum_ids );
						}
					}
				}

				if($this->last_image_delete) {
					$etsy_images = etsy_request()->get( "application/listings/{$listing_id}/images", $shop_name );
					$etsy_images = isset( $etsy_images['results'] ) ? $etsy_images['results'] : array();
					if(isset($etsy_images) && !empty($etsy_images) && is_array($etsy_images)) {
						$main_image_id = end($etsy_images)['listing_image_id'];
						do_action( 'ced_etsy_refresh_token', $shop_name );
						$action   = "application/shops/{$shop_id}/listings/{$listing_id}/images/{$main_image_id}";
						$response = etsy_request()->delete( $action, $shop_name );
					}

				}

				/*
				|===================
				| UPLOAD MAIN IMAGE
				|===================
				*/
				if ( ! isset( $previous_thum_ids[ $prnt_img_id ] ) ) {
					$image_result = $upload->do_image_upload( $listing_id, $p_id, $prnt_img_id, $shop_name );
					if ( isset( $image_result['listing_image_id'] ) ) {
						$previous_thum_ids[ $prnt_img_id ] = $image_result['listing_image_id'];
						update_post_meta( $p_id, 'ced_etsy_previous_thumb_ids' . $listing_id, $previous_thum_ids );
					}
				}
			}





     /** 
      * Updating the inventory information of the product
      * @param  : $product_id contain Woocommerce product id
      * @param  : $listing id contain Etsy id of listing
      * @param  : $shop_name contain shop name
      * @param  : $offering_payload contain an array of payload
      * @param  : $is_sync contain Boolean value 
      * @return : Method will return  the response after updating the inventory details
      */ 






     // public function modifying_variation_update( $product_id, $listing_id, $shop_name, $offerings_payload, $is_sync ){
     // 	do_action( 'ced_etsy_refresh_token', $shop_name );
     // 	$response = etsy_request()->put( "application/listings/{$listing_id}/inventory", $offerings_payload, $shop_name );
     // 	return $response;
     // }






	 /** 
	  * Update the inventory details of product and also assign variation image to all variation behalf of sku
	  * @param  : $product_id contain Woocommerce product id
	  * @param  : $listing id contain Etsy id of listings
	  * @param  : $shop_name contain shop name
	  * @param  : $var_payload contain an array of payload
	  * @param  : $is_sync contain Boolean value
	  * @return : This method will return the response after assigning the image
	  */ 






	 public function modifying_update_variation_sku_to_etsy( $product_id, $listing_id, $shop_name, $var_payload, $is_sync ){
	 	$request       = new \Cedcommerce\EtsyManager\Ced_Etsy_Request();
	 	do_action( 'ced_etsy_refresh_token', $shop_name );
	 	$response = $request->put( "application/listings/{$listing_id}/inventory", $var_payload, $shop_name );
	 	if ( isset( $response['products'][0]['product_id'] ) ) {
	 		update_post_meta( $product_id, 'ced_etsy_last_updated' . $shop_name, gmdate( 'l jS \of F Y h:i:s A' ) );
	 		if ( isset( $response['products'][0]['product_id'] ) && !empty($this->var_array)) {
	 			$variation_images_attr = $this->var_img_data;
	 			$previous_thum_ids = get_post_meta( $product_id, 'ced_etsy_previous_thumb_ids' . $listing_id, true );
	 			if( empty($previous_thum_ids) ) {
	 				$previous_thum_ids = array();
	 			}
	 			update_post_meta( $product_id, 'ced_etsy_last_updated' . $shop_name, gmdate( 'l jS \of F Y h:i:s A' ) );
	 			$var_result         = isset( $response['products'] ) ? $response['products'] : array();
	 			$woo_thumb_id       = get_post_thumbnail_id( $product_id );
	 			$primay_etsy_img_id = isset( $previous_thum_ids[$woo_thumb_id] ) ? $previous_thum_ids[$woo_thumb_id] : '';
	 			$var_image_array    = array();
	 			$data               = array();
	 			foreach ( $var_result as $var_offerings ) {
	 				$prev_val_id = "";
	 				foreach($var_offerings['property_values'] as  $prop_data ) {
	 					if( $variation_images_attr['variation_images_attr'] == $prop_data['property_name']) {
	 						if ( $prev_val_id == $prop_data['property_name'] ) {
	 							continue;
	 						}

	 						if(array_key_exists($prop_data['value_ids'][0], $var_image_array)){
	 							continue;
	 						}

	 						if ( isset( $this->var_array[ $var_offerings['sku'] ] ) && ! empty( $this->var_array[ $var_offerings['sku'] ] ) ) {
	 							$var_image_array[$prop_data['value_ids'][0]] = array(
	 								'property_id' => $prop_data['property_id'],
	 								'value_id'    => $prop_data['value_ids'][0],
	 								'image_id'    => $this->var_array[ $var_offerings['sku'] ],
	 							);
	 						}
	 						else{
	 							$var_image_array[$prop_data['value_ids'][0]] = array(
	 								'property_id' => $prop_data['property_id'],
	 								'value_id'    => $prop_data['value_ids'][0],
	 								'image_id'    => $primay_etsy_img_id,
	 							);
	 						}

	 						$prev_val_id = $prop_data['property_name'];
	 					}

	 				}

	 			}

	 			if ( isset( $var_image_array ) && ! empty( $var_image_array ) ) {
	 				$var_params         = array(
	 					'variation_images' => array_values($var_image_array),
	 				);

	 				$shop_id          = get_etsy_shop_id( $shop_name );
	 				$a_action         = "application/shops/{$shop_id}/listings/{$listing_id}/variation-images";
	 				$var_img_response = etsy_request()->post( $a_action ,$var_params, $shop_name );

	 			}

	 		}

	 	}
	 	if ( ! $is_sync ) {
	 		return $response;
	 	}

	 }






    /** 
     * 
     * Method will modify the function of image update 
     * @param : $product_ids contain array of ids
     * @param : $shop_name contain shop name 
     * 
     */ 





    public function modifying_product_image_update( $product_ids, $shop_name ) {
    	if ( ! is_array( $product_ids ) ) {
    		$product_ids = array( $product_ids );
    	}
    	$shop_id      = get_etsy_shop_id( $shop_name );
    	$notification = array();
    	if ( is_array( $product_ids ) && ! empty( $product_ids ) ) {
    		foreach ( $product_ids as $pr_id ) {
    			$_product = wc_get_product( $pr_id );
    			$listing_id = get_post_meta( $pr_id, '_ced_etsy_listing_id_' . $shop_name, true );
    			update_post_meta( $pr_id, 'ced_etsy_previous_thumb_ids' . $listing_id, array() );
    			$etsy_images = etsy_request()->get( "application/listings/{$listing_id}/images", $shop_name );
    			$etsy_images = isset( $etsy_images['results'] ) ? $etsy_images['results'] : array();
    			foreach ( $etsy_images as $key => $image_info ) {
    				$main_image_id = isset( $image_info['listing_image_id'] ) ? $image_info['listing_image_id'] : '';
    				do_action( 'ced_etsy_refresh_token', $shop_name );
    				$action   = "application/shops/{$shop_id}/listings/{$listing_id}/images/{$main_image_id}";
    				$response = etsy_request()->delete( $action, $shop_name );
    			}
    			$upload            = new \Cedcommerce\Product\Ced_Product_Upload();
    			if ( 'variable' == $_product->get_type() ) {
					// $payload           = new \Cedcommerce\Product\Ced_Product_Payload( $pr_id , $shop_name );
    				$offerings_payload = $this->modifying_variation_details( $pr_id, $shop_name, false );
    				$upload->update_variation_sku_to_etsy($pr_id, $listing_id, $shop_name, $offerings_payload, false);
    			}
    			$this->last_image_delete = true;
    			$upload->ced_etsy_prep_and_upload_img( $pr_id, $shop_name, $listing_id);
    			$notification['status']  = 200;
    			$notification['message'] = 'Image updated successfully';
    		}
    	}
    	return $notification;
    }






	 /** 
	  * Modifying variation details
	  * @param  : $product_id contain Woocommerce product id
	  * @param  : $shop_name contain shop name
	  * @param  : $is_sync contain Boolean values
	  * @param  : $is_update contain Boolean value
	  * @return : Method will return the variation informations
	  */ 





	 public function modifying_variation_details( $product_id, $shop_name , $is_sync, $is_update = false) {
	 	$ced_e_payload         = new Cedcommerce\Product\Ced_Product_Payload( $product_id , $shop_name );
	 	$property_ids          = array();
	 	$product               = wc_get_product( $product_id );
	 	$variations            = $product->get_available_variations();
	 	$listing_id            = get_post_meta($product_id,'_ced_etsy_listing_id_' . $shop_name , true);
	 	$attributes            = array();
	 	$parent_sku            = get_post_meta( $product_id, '_sku', true );
	 	$var_img               = array();
	 	$parent_image_id       = get_post_thumbnail_id( $product_id );
	 	$parent_attributes     = $product->get_variation_attributes();
	 	$possible_combinations = array_values( wc_array_cartesian(( $parent_attributes )) );
	 	$no_property_to_use    = count($parent_attributes);
	 	$com_to_be_prepared    = array();
	 	foreach ( $possible_combinations as $po_attr => $po_values ) {
	 		$att_name_po = '';
	 		$po_values   = array_reverse( $po_values );

	 		foreach ( $po_values as $kk => $po_value ) {
	 			if ( ! isset( $parent_attributes[ $kk ] ) ) {
	 				continue;
	 			}
	 			$att_name_po .= $po_value . '~';
	 		}
	 		$com_to_be_prepared[ trim( strtolower( $att_name_po ) ) ] = trim( strtolower( $att_name_po ) );
	 	}

	 	foreach ( $variations as $variation ) {

	 		$var_id               = $variation['variation_id'];
	 		$attribute_one_mapped = false;
	 		$attribute_two_mapped = false;
	 		$var_product          = wc_get_product( $variation['variation_id'] );
	 		$attributes           = $var_product->get_variation_attributes();
	 		$count                = 0;
	 		$property_values      = array();
	 		$offerings            = array();
	 		$var_array            = array();
	 		$_count               = 0;
	 		$var_att_array        = '';
	 		foreach ( $attributes as $property_name => $property_value ) {
	 			$product_terms = get_the_terms( $product_id, $property_name );
	 			if ( is_array( $product_terms ) && ! empty( $product_terms ) ) {
	 				foreach ( $product_terms as $tempkey => $tempvalue ) {
	 					if ( $tempvalue->slug == $property_value ) {
	 						$property_value = $tempvalue->name;
	 						break;
	 					}
	 				}
	 			}
	 			$_count ++;
	 			$property_id = 513;
	 			if(!$attribute_one_mapped) {
	 				$property_name_one = ucwords( str_replace( array( 'attribute_pa_', 'attribute_' ), array( '', '' ), $property_name ) );
	 				$attribute_one_mapped = true;
	 			}

	 			if ( $count > 0 ) {
	 				if(!$attribute_two_mapped) {
	 					$property_name_two = ucwords( str_replace( array( 'attribute_pa_', 'attribute_' ), array( '', '' ), $property_name ) );
	 				}
	 				$property_id = 514;
	 				$attribute_two_mapped = true;
	 			}

	 			$property_values[] = array(
	 				'property_id'   => (int) $property_id,
	 				'value_ids'     => array( $property_id ),
	 				'property_name' => ucwords( str_replace( array( 'attribute_pa_', 'attribute_' ), array( '', '' ), $property_name ) ),
	 				'values'        => array( ucwords( strtolower( $property_value ) ) ),

	 			);

	 			$var_att_array .= $property_value . '~';
	 			$count++;
	 			$property_ids[] = $property_id;
	 		}

	 		if ( isset( $com_to_be_prepared[ strtolower( $var_att_array ) ] ) ) {
	 			unset( $com_to_be_prepared[ strtolower( $var_att_array ) ] );
	 		}

	 		$this->modifying_formated_data_for_variations( $var_id, $shop_name );
			// $price        = $ced_e_payload->get_price();
			// $var_quantity = $ced_e_payload->get_quantity();
	 		$price = isset( $this->pro_data['price'] ) ? $this->pro_data['price'] : '';
	 		if ( 'variable' == $ced_e_payload->ced_pro_type($product_id) ) {
				// $variations = $ced_e_payload->prod_obj->get_available_variations();
				// if ( isset( $variations['0']['display_regular_price'] ) ) {
				// 	$price = $variations['0']['display_regular_price'];
				// }
	 			$price = get_post_meta($var_id, '_regular_price', true);
	 			if(empty($price)){
	 				$price = get_post_meta($var_id, '_price', true);
	 			}

	 		}

	 		$price        = ! empty( $price ) ? $price : '';
	 		$markup_type  = $this->pro_data['markup_type'];
	 		$markup_value = (float) $this->pro_data['markup_value'];
	 		if ( ! empty( $markup_type ) && '' !== $markup_value ) {
	 			$price = ( 'Fixed_Increased' == $markup_type ) ? ( (float) $price + $markup_value ) : ( (float) $price + ( ( $markup_value / 100 ) * (float) $price ) );
	 		}

	 		$price = (float) $price;
	 		$quantity = isset( $this->pro_data['stock'] ) ? $this->pro_data['stock'] : '';
	 		if ( '' === $quantity ) {
	 			$quantity = get_post_meta( $var_id, '_stock', true );
				// if ( 'variable' == $ced_e_payload->product_type ) {
				// 	$quantity = 1;
				// }
	 			$manage_stock = get_post_meta( $var_id, '_manage_stock', true );
	 			$stock_status = get_post_meta( $var_id, '_stock_status', true );
	 			if ( 'instock' == $stock_status && 'no' == $manage_stock ) {
	 				$quantity = ( '' !== $this->pro_data['default_stock'] ) ? $this->pro_data['default_stock'] : 0;
	 			}

	 			if ( $quantity > 999 ) {
	 				$quantity = 999;
	 			}

	 			if ( $quantity <= 0 ) {
	 				$quantity = 0;
	 			}
	 		}
	 		$var_quantity = $quantity;
	 		$var_sku      = $variation['sku'];
	 		if ( empty( $var_sku ) || strlen( $var_sku ) > 32 || $parent_sku == $var_sku ) {
	 			$var_sku = (string) $variation['variation_id'];
	 		}

	 		$list_var_imgs         = $this->var_img_data;
	 		if(isset( $list_var_imgs['variation_images'] ) && 'yes'==  $list_var_imgs['variation_images'] && !empty( $list_var_imgs['variation_images_attr'] ) && ! $is_update) {
	 			$_thumbnail_id     = get_post_meta( $var_id, '_thumbnail_id', true );
	 			$previous_thum_ids = get_post_meta( $product_id, 'ced_etsy_previous_thumb_ids' . $listing_id, true );
	 			if ( empty( $previous_thum_ids ) ) {
	 				$previous_thum_ids = array();
	 			}
	 			if ( ! empty( $_thumbnail_id )  ) {
	 				if ( isset( $previous_thum_ids[ $_thumbnail_id ] ) && ! empty( $previous_thum_ids[ $_thumbnail_id ] ) ) {
	 					$this->var_array[ $var_sku ] = $previous_thum_ids[ $_thumbnail_id ];
	 				} else {
	 					$image_result = ( new \Cedcommerce\Product\Ced_Product_Upload( $shop_name ) )->do_image_upload( $listing_id, $product_id, $_thumbnail_id , $shop_name );
	 					if ( isset( $image_result['listing_image_id'] ) ) {
	 						$previous_thum_ids[ $_thumbnail_id ] =  $image_result['listing_image_id'] ;
	 						update_post_meta( $product_id, 'ced_etsy_previous_thumb_ids' . $listing_id, $previous_thum_ids );
	  					// $this->var_array[ $var_sku ] =  $image_result['listing_image_id'] ;
	 						$this->var_array[ $var_sku ] =  $image_result['listing_image_id'] ;
	 					}
	 				}

	 			}
	 		}


	 		$p_manage_stock      = get_post_meta( $product_id, '_manage_stock', true );
	 		$p_stock_status      = get_post_meta( $product_id, '_stock_status', true );
	 		$manage_stock        = get_post_meta( $var_id, '_manage_stock', true );
	 		$stock_status        = get_post_meta( $var_id, '_stock_status', true );
	 		$manage_at_var_level = true;

	 		if ( 'no' == $manage_stock && 'instock' == $stock_status && 'instock' == $p_stock_status && 'yes' == $p_manage_stock ) {
	 			$var_quantity        = get_post_meta( $product_id, '_stock', true );
	 			$manage_at_var_level = false;
	 		}

	 		if ( $var_quantity > 999 ) {
	 			$var_quantity = 999;
	 		}

	 		if ( $var_quantity <= 0 ) {
	  		// $var_quantity = 0;
	 			$offer_info[] = array(
	 				'sku'             => $var_sku,
	 				'property_values' => $property_values,
	 				'offerings'       => array(
	 					array(
	 						'price'      => (float) $price,
	 						'quantity'   => 0,
	 						'is_enabled' => 1,
	 					),
	 				),
	 			);
	 		} else {
	 			$offerings      = array(
	 				array(
	 					'price'      => (float) $price,
	 					'quantity'   => (int) $var_quantity,
	 					'is_enabled' => 1,
	 				),
	 			);
	 			$variation_info = array(
	 				'sku'             => $var_sku,
	 				'property_values' => $property_values,
	 				'offerings'       => $offerings,
	 			);
	 			$offer_info[]   = $variation_info;
	 		}


	 	}


	 	foreach ( $com_to_be_prepared as $combination ) {
	 		$property_values_remaining = array_values( array_filter( explode( '~', $combination ) ) );
	 		if ( isset( $property_values_remaining[1] ) ) {
	 			$offer_info[] = array(
	 				'sku'             => '',
	 				'property_values' => array(
	 					array(
	 						'property_id'   => (int) 513,
	 						'value_ids'     => array( 513 ),
	 						'property_name' => $property_name_one,
	 						'values'        => array(
	 							isset( $property_values_remaining[0] ) ? ucwords( strtolower( $property_values_remaining[0] ) ) : '',
	 						),
	 					),
	 					array(
	 						'property_id'   => (int) 514,
	 						'value_ids'     => array( 514 ),
	 						'property_name' => $property_name_two,
	 						'values'        => array(
	 							isset( $property_values_remaining[1] ) ? ucwords( strtolower( $property_values_remaining[1] ) ) : '',
	 						),
	 					),
	 				),
	 				'offerings'       => array(
	 					array(
	 						'price'      => (float) $price,
	 						'quantity'   => 0,
	 						'is_enabled' => 1,
	 					),
	 				),
	 			);
	 		} elseif ( isset( $property_values_remaining[0] ) ) {
	 			$offer_info[] = array(

	 				'sku'             => '',
	 				'property_values' => array(
	 					array(
	 						'property_id'   => (int) 513,
	 						'value_ids'     => array( 513 ),
	 						'property_name' => $property_name_one,
	 						'values'        => array(
	 							isset( $property_values_remaining[0] ) ? ucwords( strtolower( $property_values_remaining[0] ) ) : '',
	 						),
	 					),

	 				),
	 				'offerings'       => array(
	 					array(
	 						'price'      => (float) $price,
	 						'quantity'   => 0,
	 						'is_enabled' => 1,
	 					),
	 				),
	 			);
	 		}
	 	}

	 	$property_ids = array_unique( $property_ids );
	 	$property_ids = implode( ',', $property_ids );
	 	$payload      = array(
	 		'products'          => $offer_info,
	 		'price_on_property' => $property_ids,
	 		'sku_on_property'   => $property_ids,

	 	);

	 	if ( $manage_at_var_level ) {
	 		$payload['quantity_on_property'] = $property_ids;
	 	}
	 	return $payload;
	 }

   



   /** 
    * Modifying Product quantity while uploading product to Etsy
    * @param  : $product_id contain Woocommerce product id
    * @param  : $shop_name contain shop name
    * @param  : $default contain a default value
    * @return : This method will return the quantity for product
    * 
    */ 




	// 	public function modifying_pro_quantity($product_id, $shop_name, $default = 1) {
	// 		// $ced_e_payload = new Cedcommerce\Product\Ced_Product_Payload( $product_id , $shop_name );
	// 		// $ced_e_payload = $this->ced_e_payload;
	// 		$quantity = isset( $ced_e_payload->pro_data['stock'] ) ? $ced_e_payload->pro_data['stock'] : '';
	// 		if ( '' === $quantity ) {
	// 			$quantity = get_post_meta( $product_id, '_stock', true );
	// 			if ( 'variable' == $ced_e_payload->product_type ) {
	// 				$quantity = 1;
	// 			}
	// 			$manage_stock = get_post_meta( $product_id, '_manage_stock', true );
	// 			$stock_status = get_post_meta( $product_id, '_stock_status', true );
	// 			if ( 'instock' == $stock_status && 'no' == $manage_stock ) {
	// 				$quantity = ( '' !== $ced_e_payload->pro_data['default_stock'] ) ? $ced_e_payload->pro_data['default_stock'] : $default;
	// 			}

	// 			if ( $quantity > 999 ) {
	// 				$quantity = 999;
	// 			}

	// 			if ( $quantity <= 0 ) {
	// 				$quantity = 0;
	// 			}
	// 		}

	  //    /** Alter etsy product qty
	  //    		 *
	  //    		 * @since 2.0.0
	  //    		 */
	  //    return $quantity;
	  // }





   /** 
    * Modifying product upload function
    * @param : $pro_ids contain an array of products id
    * @param : $shop_name contain shop name
    * @param : $is_sync contain Boolean value 
   */





   public function modifying_product_upload($pro_ids, $shop_name, $is_sync) {
   	$upload        = new \Cedcommerce\Product\Ced_Product_Upload();
   	if ( '' == $shop_name || empty( $shop_name ) ) {
   		return;
   	}

   	$notification = array();
   	foreach ( $pro_ids as $key => $pr_id ) {
   		$already_uploaded = get_post_meta( $pr_id, '_ced_etsy_listing_id_' . $shop_name, true );
   		if ( $already_uploaded ) {
   			continue;
   		}
   		$upload->ced_product  = wc_get_product( absint( $pr_id ) );
   		$pro_type           = $upload->ced_product->get_type();
   		$delete_instance    = new \Cedcommerce\Product\Ced_Product_Delete();
   		$payload            = new \Cedcommerce\Product\Ced_Product_Payload( $pr_id, $shop_name );
   		$payload->is_upload = true;
   		if ( 'variable' == $pro_type ) {
   			$upload->data = $payload->get_formatted_data( $pr_id, $shop_name );
 			// $this->formatted_data = $upload->data;

   			if ( isset( $upload->data['has_error'] ) ) {
   				$notification['status']  = 400;
   				$notification['message'] = $upload->data['error'];
   			} else {
   				$upload->doupload( $pr_id, $shop_name );
   				$response = $upload->upload_response;
   				if ( isset( $response['listing_id'] ) ) {
   					$this->l_id = isset( $response['listing_id'] ) ? $response['listing_id'] : '';
   					update_post_meta( $pr_id, '_ced_etsy_listing_id_' . $shop_name, $this->l_id );
   					update_post_meta( $pr_id, '_ced_etsy_url_' . $shop_name, $response['url'] );
   					update_post_meta( $pr_id, '_ced_etsy_listing_data_' . $shop_name, json_encode( $response ) );
   					$offerings_payload        = $payload->ced_variation_details( $pr_id, $shop_name, false );
						// $this->offering_var_array = $this->var_array;
   					$var_response      = $upload->update_variation_sku_to_etsy( $pr_id, $this->l_id, $shop_name, $offerings_payload, false );
   					$upload->ced_etsy_prep_and_upload_img( $pr_id, $shop_name, $this->l_id );
   					if ( ! isset( $var_response['products'][0]['product_id'] ) ) {
   						$upload->data['variation'] = $offerings_payload;
   						$response                = $var_response;
   						$notification['status']  = 400;
   						$notification['message'] = isset( $var_response['error'] ) ? $var_response['error'] : '';
   						$delete_instance->ced_etsy_delete_product( array( $pr_id ), $shop_name, false );
   						continue;
   					} else {
   						if ( 'active' == $payload->get_state() ) {
   							$activate = ( new \Cedcommerce\Product\Ced_Product_Update( $pr_id, $shop_name ) )->ced_etsy_activate_product( $pr_id, $shop_name );
   						}
   						$notification['status']  = 200;
   						$notification['message'] = 'product uploaded successfully';
   					}
   				} elseif ( isset( $response['error'] ) ) {
   					$notification['status']  = 400;
   					$notification['message'] = $response['error'];
   				} else {
   					$notification['status']  = 400;
   					$notification['message'] = json_encode( $response );
   				}

   				global $activity;
   				$activity->action        = 'Upload';
   				$activity->type          = 'product';
   				$activity->input_payload = $upload->data;
   				$activity->response      = $response;
   				$activity->post_id       = $pr_id;
   				$activity->shop_name     = $shop_name;
   				$activity->is_auto       = $is_sync;
   				$activity->post_title    = $upload->data['title'];
   				$activity->execute();
   			}
   		} elseif ( 'simple' == $pro_type ) {
   			$upload->data = $this->modifying_formated_data_for_variations( $pr_id, $shop_name );
   			if ( isset( $upload->data['has_error'] ) ) {
   				$notification['status']  = 400;
   				$notification['message'] = $upload->data['error'];
   			} else {
   				$upload->doupload( $pr_id, $shop_name );
   				$response = $upload->upload_response;
   				if ( isset( $response['listing_id'] ) ) {
   					update_post_meta( $pr_id, '_ced_etsy_listing_id_' . $shop_name, $response['listing_id'] );
   					update_post_meta( $pr_id, '_ced_etsy_url_' . $shop_name, $response['url'] );
   					update_post_meta( $pr_id, '_ced_etsy_listing_data_' . $shop_name, json_encode( $response ) );
   					$this->l_id = $response['listing_id'];
   					$upload->ced_etsy_prep_and_upload_img( $pr_id, $shop_name, $this->l_id );
   					if ( 'active' == $payload->get_state() ) {
   						$activate = ( new \Cedcommerce\Product\Ced_Product_Update( $pr_id, $shop_name ) )->ced_etsy_activate_product( $pr_id, $shop_name );
   					}
   					$activate = ( new \Cedcommerce\Product\Ced_Product_Update( $pr_id, $shop_name ) )->ced_etsy_update_inventory( $pr_id, $shop_name );
   					if ( $payload->is_downloadable ) {
   						$upload->ced_upload_downloadable( $pr_id, $shop_name, $response['listing_id'], $payload->downloadable_data );
   					}

   					$notification['status']  = 200;
   					$notification['message'] = 'Product uploaded successfully';
   				} elseif ( isset( $response['error'] ) ) {
   					$notification['status']  = 400;
   					$notification['message'] = $response['error'];
   				} else {
   					$notification['status']  = 400;
   					$notification['message'] = json_encode( $response );
   				}

   				global $activity;
   				$activity->action        = 'Upload';
   				$activity->type          = 'product';
   				$activity->input_payload = $upload->data;
   				$activity->response      = $response;
   				$activity->post_id       = $pr_id;
   				$activity->shop_name     = $shop_name;
   				$activity->is_auto       = $is_sync;
   				$activity->post_title    = $upload->data['title'];
   				$activity->execute();
   			}
   		} else {
   			$notification['status']  = 400;
   			$notification['message'] = $pro_type . ' product type not supported.';
   		}
   	}
   	return $notification;
   }




   /** 
    * Modifying inventory update function
    * @param : $product_ids contain an array of Woocommerce Products id
    * @param : $shop_name contain shop name
    * @param : $is_sync contain Boolean values
    * 
    */




   // public function modifying_inventory_update($product_ids, $shop_name, $is_sync){
   // 	$update = new \Cedcommerce\Product\Ced_Product_Update();
   // 	if ( ! is_array( $product_ids ) ) {
   // 		$product_ids = array( $product_ids );
   // 	}
   // 	$notification = array();
   // 	$shop_name    = empty( $shop_name ) ? $update->shop_name : $shop_name;
   // 	$product_ids  = empty( $product_ids ) ? $update->product_id : $product_ids;
   // 	foreach ( $product_ids as $product_id ) {
   // 		$_product = wc_get_product( $product_id );
   // 		$product_data = $_product->get_data();
   // 		if ( empty( $update->listing_id ) ) {
   // 			$update->listing_id = get_post_meta( $product_id, '_ced_etsy_listing_id_' . $shop_name, true );
   // 		}
   // 		$payload = new \Cedcommerce\Product\Ced_Product_Payload( $product_id, $shop_name );
   // 		if ( 'variable' == $_product->get_type() ) {
   // 			$offerings_payload = $this->modifying_variation_details( $product_id, $shop_name, false);
   // 			$input_payload    = $offerings_payload;
   // 			$response          = $update->update_variation_sku_to_etsy( $product_id, $update->listing_id, $shop_name, $offerings_payload, false );
   // 		} else {
   // 			$this->modifying_formated_data_for_variations( $product_id, $shop_name );
   // 			$sku      = get_post_meta( $product_id, '_sku', true );
   // 			$response = etsy_request()->get( 'application/listings/' . (int) $update->listing_id . '/inventory', $shop_name );
   // 			if ( isset( $response['products'][0] ) ) {
   // 				if ( (int) $payload->get_quantity() <= 0 ) {
   // 					$response = $update->ced_etsy_deactivate_product( $product_id, $shop_name );
   // 					update_post_meta( $product_id, '_ced_etsy_listing_data_' . $shop_name, json_encode( $response ) );
   // 					$input_payload = array( $update->listing_id );
   // 				} else {
   // 					$product_payload = $response;
 	// 				// $product_payload['products'][0]['offerings'][0]['quantity'] = (int) $payload->get_quantity();
 	// 				// $product_payload['products'][0]['offerings'][0]['quantity'] = (int) $payload->get_quantity();
 	// 				// $product_payload['products'][0]['offerings'][0]['price']    = (float) $payload->get_price();
   // 					$price = isset( $this->pro_data['price'] ) ? $this->pro_data['price'] : '';
   // 					if ( 'variable' == $payload->ced_pro_type($product_id) ) {
 	// 					// $variations = $ced_e_payload->prod_obj->get_available_variations();
 	// 					// if ( isset( $variations['0']['display_regular_price'] ) ) {
 	// 					// 	$price = $variations['0']['display_regular_price'];
 	// 					// }
   // 						$price = get_post_meta($product_id, '_regular_price', true);
   // 						if(empty($price)){
   // 							$price = get_post_meta($product_id, '_price', true);
   // 						}

   // 					}

   // 					$price        = ! empty( $price ) ? $price : $product_data['price'];
   // 					$markup_type  = $this->pro_data['markup_type'];
   // 					$markup_value = (float) $this->pro_data['markup_value'];
   // 					if ( ! empty( $markup_type ) && '' !== $markup_value ) {
   // 						$price = ( 'Fixed_Increased' == $markup_type ) ? ( (float) $price + $markup_value ) : ( (float) $price + ( ( $markup_value / 100 ) * (float) $price ) );
   // 					}

   // 					$price = (float) $price;
   // 					$product_payload['products'][0]['offerings'][0]['price'] = $price;
   // 					$quantity = isset( $this->pro_data['stock'] ) ? $this->pro_data['stock'] : '';
   // 					if ( '' === $quantity ) {
   // 						$quantity = get_post_meta( $product_id, '_stock', true );
 	// 					// if ( 'variable' == $ced_e_payload->product_type ) {
 	// 					// 	$quantity = 1;
 	// 					// }
   // 						$manage_stock = get_post_meta( $product_id, '_manage_stock', true );
   // 						$stock_status = get_post_meta( $product_id, '_stock_status', true );
   // 						if ( 'instock' == $stock_status && 'no' == $manage_stock ) {
   // 							$quantity = ( '' !== $this->pro_data['default_stock'] ) ? $this->pro_data['default_stock'] : 0;
   // 						}

   // 						if ( $quantity > 999 ) {
   // 							$quantity = 999;
   // 						}

   // 						if ( $quantity <= 0 ) {
   // 							$quantity = 0;
   // 						}
   // 					}
   // 					$product_payload['products'][0]['offerings'][0]['quantity'] = $quantity;
   // 					$product_payload['products'][0]['sku']                      = (string) $sku;
   // 					unset( $product_payload['products'][0]['is_deleted'] );
   // 					unset( $product_payload['products'][0]['product_id'] );
   // 					unset( $product_payload['products'][0]['offerings'][0]['is_deleted'] );
   // 					unset( $product_payload['products'][0]['offerings'][0]['offering_id'] );
 	// 				/** Refresh token
	// 	 			 *
	// 	 			 * @since 2.0.0
	// 	 			 */
 	// 				do_action( 'ced_etsy_refresh_token', $shop_name );
 	// 				$input_payload = $product_payload;
 	// 				$response      = etsy_request()->put( 'application/listings/' . (int) $update->listing_id . '/inventory', $product_payload, $shop_name );
 	// 			}
 	// 		}
 	// 	}

 	// 	global $activity;
 	// 	$activity->action        = 'Update';
 	// 	$activity->type          = 'product_inventory';
 	// 	$activity->input_payload = $input_payload;
 	// 	$activity->response      = $response;
 	// 	$activity->post_id       = $product_id;
 	// 	$activity->shop_name     = $shop_name;
 	// 	$activity->post_title    = $_product->get_title();
 	// 	$activity->is_auto       = $is_sync;
 	// 	$activity->execute();

 	// 	if ( isset( $response['products'][0] ) ) {
 	// 		$notification['status']  = 200;
 	// 		$notification['message'] = 'Product inventory updated successfully';
 	// 	} elseif ( isset( $response['listing_id'] ) ) {
 	// 		$notification['status']  = 200;
 	// 		$notification['message'] = 'Product deactivated on etsy';
 	// 	} elseif ( isset( $response['error'] ) ) {
 	// 		$notification['status']  = 400;
 	// 		$notification['message'] = $response['error'];
 	// 	} else {
 	// 		$notification['status']  = 400;
 	// 		$notification['message'] = json_encode( $response );
 	// 	}
 	// }
 	// return $notification;

  // }







  // public function modifying_header_for_importer_section($shop_name) {
  // 	return array(
  // 			'overview'     => __( 'Overview', 'woocommerce-etsy-integration' ),
  // 			'settings'     => __( 'Settings', 'woocommerce-etsy-integration' ),
  // 			'templates'    => __( 'Templates', 'woocommerce-etsy-integration' ),
  // 			'products'     => __( 'Products', 'woocommerce-etsy-integration' ),
  // 			'orders'       => __( 'Orders', 'woocommerce-etsy-integration' ),
  // 			'importer'     => __('importer', 'woocommerce-etsy-integration'),
  // 			'timeline'     => __( 'Timeline', 'woocommerce-etsy-integration' ),
  // 			'profile-edit' => __( 'Profile Edit', 'woocommerce-etsy-integration' ),
  // 		);
  // }





  /** 
   * Modifying product price 
   * @param : $product_id contain Woocommerce product id
   * @param : $shop_name contain shop name
   */ 





 // public function modifying_pro_price($product_id, $shop_name) {

 // 	// echo "\n Filter : Product ID :  " . $product_id;
 // 	// echo "\n Filter : shop_name ID :  " . $shop_name;
 // 	// ini_set('display_errors', 1);
 // 	// ini_set('display_startup_errors', 1);
 // 	// error_reporting(E_ALL);

 // 	$ced_e_payload = new Cedcommerce\Product\Ced_Product_Payload( $product_id , $shop_name );
 // 	/*$product_type  =*/ $ced_e_payload->ced_pro_type($product_id);
 // 	// $pro_data = $ced_e_payload->get_formatted_data($product_id, $shop_name);
 // 	$price = isset( $ced_e_payload->pro_data['price'] ) ? $ced_e_payload->pro_data['price'] : '';

 // 	if ( 'variable' == $ced_e_payload->product_type ) {
 // 		$variations = $ced_e_payload->prod_obj->get_available_variations();
 // 		if ( isset( $variations['0']['display_regular_price'] ) ) {
 // 			$price = $variations['0']['display_regular_price'];
 // 		}
 // 	}

 // 	$price        = ! empty( $ced_e_payload->product['price'] ) ? $ced_e_payload->product['price'] : $price;
 // 	$markup_type  = !empty($ced_e_payload->pro_data['markup_type']) ? $ced_e_payload->pro_data['markup_type'] : '';
 // 	$markup_value = !empty($ced_e_payload->pro_data['markup_value']) ? (float) $ced_e_payload->pro_data['markup_value'] : '';
 // 	if ( ! empty( $markup_type ) && '' !== $markup_value ) {
 // 		$price = ( 'Fixed_Increased' == $markup_type ) ? ( (float) $price + $markup_value ) : ( (float) $price + ( ( $markup_value / 100 ) * (float) $price ) );
 // 	}

 // 	$price = (float) $price;
 //      // $price = 130; //Testing

 //      if ( '' != (float) round( $price, 2 ) ) {
 //      	/** Alter etsy product price
 //      		 *
 //      		 * @since 2.0.0
 //      		 */
 //      	return apply_filters( 'ced_etsy_price', (float) round( $price, 2 ), $product_id, $shop_name );
 //      }

 //      return false;
 //  }

	public function modifying_product_tags( $product_id, $shop_name ) {
	$get_tags = ! empty( $this->pro_data['tags'] ) ? $this->pro_data['tags'] : array();
		$tag_info = array();
		if ( ! empty( $get_tags ) ) {
			$explode_materials = array_filter( explode( ',', $get_tags ) );
			foreach ( $explode_materials as $key_tags => $tag_name ) {
				$tag_name = trim( $tag_name );
				$tag_name = str_replace( ' ', '-', $tag_name );
				// $tag_name = preg_replace( '/[^A-Za-z0-9\-]/', '', $tag_name );
				$tag_name = str_replace( '-', ' ', $tag_name );
				if ( $key_tags <= 12 && strlen( $tag_name ) <= 20 ) {
					$tag_info[] = $tag_name;
				}
			}

			$tag_info = array_filter( array_values( array_unique( $tag_info ) ) );
			if ( ! empty( $tag_info ) ) {
				return $tag_info;
			}
		}

		if ( empty( $get_tags ) ) {
			$get_tags = get_the_terms( $product_id, 'product_tag' );
			if ( isset( $get_tags ) && ! empty( $get_tags ) && is_array( $get_tags ) ) {
				foreach ( $get_tags as $tag_key => $tags ) {
					$tag_name = $tags->name;
					$tag_name = str_replace( ' ', '-', $tag_name );
// 					$tag_name = preg_replace( '/[^A-Za-z0-9\-]/', '', $tag_name );
					$tag_name = str_replace( '-', ' ', $tag_name );
					if ( $tag_key <= 12 && strlen( $tag_name ) <= 20 ) {
						$tag_info[] = $tag_name;
					}
				}
				return $tag_info;
			}
		}
		return false;
	}


	// public function modifying_product_description(  $pro_id, $shop_name  ) {
	// 	$ced_e_payload   = new Cedcommerce\Product\Ced_Product_Payload( $pro_id , $shop_name );
	// 	$ced_e_payload->ced_etsy_check_profile( $pro_id, $shop_name );
	// 	$pro_levl_checking = $ced_e_payload->profile_data['_ced_etsy_description']['metakey'];
	// 	if($pro_levl_checking != 'null') {
	// 		$description = get_post_meta( $ced_e_payload->product_id, $pro_levl_checking, true );

	// 	} else {
	// 		$woo_pro_des = $this->get_product_description_using_product_id($ced_e_payload->product_id);
	// 		$woo_pro_short_des = $this->get_product_short_description_using_product_id($ced_e_payload->product_id);

	// 		$replace_words = [
	// 			'MORE ITEMS WITH THIS PRINT',
	// 			'MORE Chevrolet-THEMED ITEMS'
	// 		];
	// 		$woo_pro_des = str_replace(  $replace_words , '' , $woo_pro_des );

	// 		$description  = $woo_pro_des ."<br>". $woo_pro_short_des ;
	// 		$search_desc  = $ced_e_payload->fetch_meta_value( $ced_e_payload->product_id, '_ced_etsy_search_desc_text' );

	// 		if ( ! empty( $search_desc ) ) {
	// 			$search_desc = explode( ',', $search_desc );
	// 			if ( is_array( $search_desc ) && ! empty( $search_desc ) ) {
	// 				foreach ( $search_desc as $dkeyword ) {

	// 					$dkeyword = explode(";", $dkeyword);
	// 					$dkeyword_from = isset($dkeyword[0]) ? trim( $dkeyword[0]) : ''; 
	// 					$dkeyword_to = isset($dkeyword[1]) ? trim($dkeyword[1]) : ' '; 
	// 					$description = str_replace(  $dkeyword_from , $dkeyword_to , $description );
	// 					$description = str_replace( strtolower( $dkeyword_from ), $dkeyword_to, $description );
	// 					$description = str_replace( strtoupper( $dkeyword_from ), $dkeyword_to, $description );
	// 					$description = str_replace( ucwords( $dkeyword_from ), $dkeyword_to, $description );
	// 				}
	// 			}
	// 		}

	// 	}
	// 	if ( '' != trim( strip_tags( $description ) ) ) {
	// 		return apply_filters( 'ced_etsy_description', (string) trim( strip_tags( html_entity_decode( $description ) ) ), $ced_e_payload->product_id, $ced_e_payload->shop_name );
	// 	}
	// 	return false;
	// }


	// public function get_product_description_using_product_id($produc_id) {
	// 	ob_start();
	// 	global $post;
	// 	$post = get_post($produc_id);
	// 	setup_postdata( $post );
	// 	the_content();
	// 	wp_reset_postdata( $post );
	// 	$product_description = ob_get_contents();
	// 	ob_end_clean();
	// 	return $product_description;
	// }

	// public function get_product_short_description_using_product_id($produc_id) {
	// 	ob_start();
	// 	global $post;
	// 	$post = get_post($produc_id);
	// 	setup_postdata( $post );
	// 	the_excerpt();
	// 	wp_reset_postdata( $post );
	// 	$product_description = ob_get_contents();
	// 	ob_end_clean();
	// 	return $product_description;
	// }

	// public function modifying_product_title( $pro_id, $shop_name ) {

	// 	$ced_e_payload = new Cedcommerce\Product\Ced_Product_Payload( $pro_id , $shop_name );
	// 	$title         = isset( $ced_e_payload->pro_data['title'] ) ? $ced_e_payload->pro_data['title'] : '';
	// 	$title         = ! empty( $title ) ? $title : $ced_e_payload->product['name'];
	// 	$title         = $ced_e_payload->pro_data['title_pre'] . ' ' . $title . ' ' . $ced_e_payload->pro_data['title_post'];
	// 	$search_title  = $ced_e_payload->fetch_meta_value( $ced_e_payload->product_id, '_ced_etsy_search_title_text' );
	// 	if ( ! empty( $search_title ) ) {
	// 		$search_title = explode( ',', $search_title );
	// 		if ( is_array( $search_title ) && ! empty( $search_title ) ) {
	// 			foreach ( $search_title as $tkeyword ) {

	// 				$tkeyword = explode(";", $tkeyword);
	// 				$tkeyword_from = isset($tkeyword[0]) ? trim($tkeyword[0]) : ''; 
	// 				$tkeyword_to = isset($tkeyword[1]) ? trim($tkeyword[1]) : ' '; 

	// 				$title = str_replace( $tkeyword_from, $tkeyword_to, $title );
	// 				$title = str_replace( strtolower( $tkeyword_from ), $tkeyword_to, $title );
	// 				$title = str_replace( strtoupper( $tkeyword_from ), $tkeyword_to, $title );
	// 				$title = str_replace( ucwords( $tkeyword_from ), $tkeyword_to, $title );
	// 			}
	// 		}
	// 	}

	// 	if ( '' != trim( $title ) ) {
	// 		return apply_filters( 'ced_etsy_title', (string) trim( $title ), $ced_e_payload->product_id, $ced_e_payload->shop_name );
	// 	}
	// 	return false;
	// }



   public function modifying_insert_product_variation($post_id, $variations, $available_attributes, $shop_name ='', $listing_id = '') {

   	   do_action( 'ced_etsy_refresh_token', $shop_name );
   	   $shop_id               = get_etsy_shop_id( $shop_name );
   	   $variations_images     = etsy_request()->get( '/application/shops/'. $shop_id . '/listings/'. $listing_id . '/variation-images', $shop_name );
   	   $variations_images     = isset( $variations_images['results'] ) ? $variations_images['results'] : array();
   	   $stored_var_attach_ids = get_post_meta( $post_id, '_ced_etsy_var_attachement_ids_' . $shop_name , true );
   	   $stored_var_attach_ids = !empty( $stored_var_attach_ids ) ? $stored_var_attach_ids : array();
   	   $parent_qty            = 0;

   	   foreach ( $variations as $index => $variation ) {
   	   	$variation_post = array(
   	   		'post_title'  => 'Variation #' . $index . ' of ' . count( $variations ) . ' for product#' . $post_id,
   	   		'post_name'   => 'product-' . $post_id . '-variation-' . $index,
   	   		'post_status' => 'publish',
   	   		'post_parent' => $post_id,
   	   		'post_type'   => 'product_variation',
   	   		'guid'        => home_url() . '/?product_variation=product-' . $post_id . '-variation-' . $index,
   	   	);

   	   	$variation_post_id = wp_insert_post( $variation_post );

   	   	foreach ( $available_attributes as $key => $value ) {
   	   		$values = array();
   	   		foreach ( $variations as $key1 => $value1 ) {
   	   			$values[] = $value1['attributes'][ $value ];
   	   		}
   	   		$values          = array_unique( $values );
   	   		$array[ $value ] = array_values( $values );
   	   		$newvalues       = array();
   	   		foreach ( $values as $key => $mwvalue ) {
   	   			$newvalues[] = $mwvalue;
   	   		}
   	   		wp_set_object_terms( $variation_post_id, $newvalues, $value );
   	   	}

   	   	foreach ( $variation['attributes'] as $attribute => $value ) {
   	   		$attr = strtolower( $attribute );
   	   		$attr = str_replace( ' ', '-', $attr );
   	   		$attr = sanitize_title( $attr );

   	   		update_post_meta( $variation_post_id, 'attribute_' . $attr, $value );

   	   		$thedata = array(
   	   			$attr => array(
   	   				'name'         => $value,
   	   				'value'        => '',
   	   				'is_visible'   => '1',
   	   				'is_variation' => '1',
   	   				'is_taxonomy'  => '1',
   	   			),
   	   		);

   	   		update_post_meta( $variation_post_id, '_product_attributes', $thedata );

   	   	}
   	   	if ( $variation['quantity'] > 0 ) {
   	   		$parent_qty = $parent_qty + (int) $variation['quantity'];
   	   		update_post_meta( $variation_post_id, '_stock_status', 'instock' );
   	   		update_post_meta( $variation_post_id, '_stock', $variation['quantity'] );
   	   		update_post_meta( $variation_post_id, '_manage_stock', 'yes' );
   	   		update_post_meta( $post_id, '_stock_status', 'instock' );
   	   	} else {
   	   		update_post_meta( $variation_post_id, '_stock_status', 'outofstock' );
   	   	}

   	   	update_post_meta( $variation_post_id, '_price', str_replace( ',', '', $variation['price'] ) );
   	   	update_post_meta( $variation_post_id, '_regular_price', str_replace( ',', '', $variation['price'] ) );
   	   	update_post_meta( $variation_post_id, '_sku', str_replace( ',', '', $variation['sku'] ) );
   	   	$this->modifying_assign_var_images( $variation_post_id, $variations_images, $stored_var_attach_ids, $variation['value_ids'] );

   	   }

   	   if ( $parent_qty > 0 ) {
   	   	update_post_meta( $post_id, '_stock', (int) $parent_qty );
   	   }

   }



   public function modifying_create_variable_product($product, $product_details, $image_details, $shop_name) {
   	ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
		$plugin_dir = WP_PLUGIN_DIR . '/woocommerce-etsy-integration';
		require_once $plugin_dir . '/admin/ced-builder/product/class-ced-product-import.php';
		$ced_product_import = Ced_Product_Import::get_instance();
   	$etsy_variation_products    = $product_details;
   	$saved_global_settings_data = get_option( 'ced_etsy_global_settings', '' );
   	$ced_etsy_target_lang       = isset( $saved_global_settings_data[ $shop_name ]['ced_etsy_target_lang'] ) ? $saved_global_settings_data[ $shop_name ]['ced_etsy_target_lang'] : '';
   	$import_product_status      = isset( $saved_global_settings_data[ $shop_name ]['import_product_status'] ) ? $saved_global_settings_data[ $shop_name ]['import_product_status'] : 'publish';

   	$t_title = '';
   	$t_desc  = '';
   	$t_tags  = array();

   	if ( ! empty( $ced_etsy_target_lang ) ) {
   		$e_l_id       = $product['listing_id'];
   		$shop_id      = get_etsy_shop_id( $shop_name );
   		$translations = etsy_request()->get( "application/shops/{$shop_id}/listings/{$e_l_id}/translations/{$ced_etsy_target_lang}", $shop_name );

   		$t_title = isset( $translations['title'] ) ? $translations['title'] : '';
   		$t_desc  = isset( $translations['description'] ) ? $translations['description'] : '';
   		$t_tags  = isset( $translations['tags'] ) ? $translations['tags'] : array();
   	}

   	$product_id = wp_insert_post(
   		array(
   			'post_title'   => ! empty( $t_title ) ? $t_title : $product['title'],
   			'post_status'  => $import_product_status,
   			'post_type'    => 'product',
   			'post_content' => ! empty( $t_desc ) ? $t_desc : $product['description'],
   		)
   	);

   	if ( $product_id ) {
   		$imported_pros = get_option( 'ced_etsy_imported_products_' . $shop_name, 0 );
   		update_option( 'ced_etsy_imported_products_' . $shop_name, $imported_pros++ );
   	}

   	$_weight = isset( $product['item_weight'] ) ? $product['item_weight'] : 0;
   	$_length = isset( $product['item_length'] ) ? $product['item_length'] : 0;
   	$_width  = isset( $product['item_width'] ) ? $product['item_width'] : 0;
   	$_height = isset( $product['item_height'] ) ? $product['item_height'] : 0;
   	update_post_meta( $product_id, '_ced_etsy_listing_data_' . $shop_name, json_encode( $product ) );
   	wp_set_object_terms( $product_id, 'variable', 'product_type' );
   	update_post_meta( $product_id, '_visibility', 'visible' );
   	update_post_meta( $product_id, '_ced_etsy_listing_id_' . $shop_name, $product['listing_id'] );
   	update_post_meta( $product_id, '_ced_etsy_auto_imported_' . $shop_name, $product['listing_id'] );
   	update_post_meta( $product_id, '_ced_etsy_url_' . $shop_name, $product['url'] );
   	update_post_meta( $product_id, 'ced_etsy_product_data', $product );
   	update_post_meta( $product_id, 'ced_etsy_product_inventory', $product_details );
   	update_post_meta( $product_id, '_stock_status', 'instock' );
   	update_post_meta( $product_id, '_weight', $_weight );
   	update_post_meta( $product_id, '_length', $_length );
   	update_post_meta( $product_id, '_width', $_width );
   	update_post_meta( $product_id, '_height', $_height );
   	foreach ( $etsy_variation_products[0]['property_values'] as $key => $value ) {
   		$avaliable_variation_attributes[] = $value['property_name'];
   	}
   	$attr_value = array();
   	foreach ( $etsy_variation_products as $key => $value ) {
   		foreach ( $value['property_values'] as $key1 => $value1 ) {
   			$variations[ $key ]['attributes'][ $value1['property_name'] ] = $value1['values'][0];
   			$attr_value[ $value1['property_name'] ][]                     = $value1['values'][0];
				$variations[ $key ]['value_ids'][]                            = $value1['value_ids'][0];

   		}
   		$variations[ $key ]['price']    = $value['offerings'][0]['price']['amount'] / $value['offerings'][0]['price']['divisor'];
   		$variations[ $key ]['quantity'] = $value['offerings'][0]['quantity'];
   		$variations[ $key ]['sku']      = $value['sku'];
   	}


   		
      // print_r($variations);
      // die('testingsss');

   	foreach ( $avaliable_variation_attributes as $key => $value ) {
   		$data['attribute_names'][]    = $value;
   		$data['attribute_position'][] = $key;
   		$values                       = array();
   		foreach ( $variations as $key1 => $value1 ) {
   			$values[] = $value1['attributes'][ $value ];
   		}
   		$values                         = array_unique( $values );
   		$data['attribute_values'][]     = implode( '|', $values );
   		$data['attribute_visibility'][] = 1;
   		$data['attribute_variation'][]  = 1;
   	}
   	if ( isset( $data['attribute_names'], $data['attribute_values'] ) ) {
   		$attribute_names         = $data['attribute_names'];
   		$attribute_values        = $data['attribute_values'];
   		$attribute_visibility    = isset( $data['attribute_visibility'] ) ? $data['attribute_visibility'] : array();
   		$attribute_variation     = isset( $data['attribute_variation'] ) ? $data['attribute_variation'] : array();
   		$attribute_position      = $data['attribute_position'];
   		$attribute_names_max_key = max( array_keys( $attribute_names ) );

   		for ( $i = 0; $i <= $attribute_names_max_key; $i++ ) {
   			if ( empty( $attribute_names[ $i ] ) || ! isset( $attribute_values[ $i ] ) ) {
   				continue;
   			}
   			$attribute_id   = 0;
   			$attribute_name = wc_clean( $attribute_names[ $i ] );

   			if ( 'pa_' === substr( $attribute_name, 0, 3 ) ) {
   				$attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
   			}

   			$options = isset( $attribute_values[ $i ] ) ? $attribute_values[ $i ] : '';

   			if ( is_array( $options ) ) {
   				$options = wp_parse_id_list( $options );
   			} else {
   				$options = 0 < $attribute_id ? wc_sanitize_textarea( wc_sanitize_term_text_based( $options ) ) : wc_sanitize_textarea( $options );
   				$options = wc_get_text_attributes( $options );
   			}

   			if ( empty( $options ) ) {
   				continue;
   			}

   			$attribute = new WC_Product_Attribute();
   			$attribute->set_id( $attribute_id );
   			$attribute->set_name( $attribute_name );
   			$attribute->set_options( $options );
   			$attribute->set_position( $attribute_position[ $i ] );
   			$attribute->set_visible( isset( $attribute_visibility[ $i ] ) );
   			$attribute->set_variation( isset( $attribute_variation[ $i ] ) );
   			$attributes[] = $attribute;
   		}
   	}

   	$product_type = 'variable';
   	$classname    = WC_Product_Factory::get_product_classname( $product_id, $product_type );
   	$_product     = new $classname( $product_id );
   	$_product->set_attributes( $attributes );
   	$_product->save();

   	$ced_product_import->insert_product_category( $product_id, $product, $product_details );
   	$ced_product_import->insert_product_tags( $product_id, $product, $t_tags );

   	if ( isset( $image_details ) ) {
   		$this->modifying_create_product_images( $product_id, $image_details, $shop_name);
   	}
   	
   	$this->modifying_insert_product_variation( $product_id, $variations, $avaliable_variation_attributes, $shop_name, $product['listing_id'] );

   }



   public function modifying_create_product_images($product_id, $images, $shop_name = '') {
     $var_attachement_id = get_post_meta( $product_id, '_ced_etsy_var_attachement_ids_' . $shop_name, true );
     $var_attachement_id = !empty( $var_attachement_id )  && is_array( $var_attachement_id ) ? $var_attachement_id : array();
     foreach ( $images as $key1 => $value1 ) {
     	$image_url        = $value1['url_fullxfull'];
     	$image_name       = explode( '/', $image_url );
     	$image_name       = $image_name[ count( $image_name ) - 1 ];
     	$upload_dir       = wp_upload_dir();
     	$image_url        = str_replace( 'https', 'http', $image_url );
     	$image_data       = file_get_contents( $image_url );
     	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
     	$filename         = basename( $unique_file_name );
     	if ( wp_mkdir_p( $upload_dir['path'] ) ) {
     		$file = $upload_dir['path'] . '/' . $filename;
     	} else {
     		$file = $upload_dir['basedir'] . '/' . $filename;
     	}
     	file_put_contents( $file, $image_data );
     	$wp_filetype = wp_check_filetype( $filename, null );
     	$attachment  = array(
     		'post_mime_type' => $wp_filetype['type'],
     		'post_title'     => sanitize_file_name( $filename ),
     		'post_content'   => '',
     		'post_status'    => 'inherit',
     	);
     	$attach_id   = wp_insert_attachment( $attachment, $file, $product_id );
     	require_once ABSPATH . 'wp-admin/includes/image.php';
     	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
     	wp_update_attachment_metadata( $attach_id, $attach_data );
     	$var_attachement_id[$value1['listing_image_id']] = $attach_id;
     	if ( 0 == $key1 ) {
     		set_post_thumbnail( $product_id, $attach_id );
     	} else {
     		$image_ids[] = $attach_id;
     	}
     }

     // VAR IMAGE TO ASSING
     update_post_meta( $product_id, '_ced_etsy_var_attachement_ids_' . $shop_name, $var_attachement_id );
     // PRODUCT GALLERY IMAGES
     if ( ! empty( $image_ids ) ) {
     	update_post_meta( $product_id, '_product_image_gallery', implode( ',', $image_ids ) );

     }
   }


   public function modifying_assign_var_images($var_id, $variations_images, $stored_var_attach_ids, $value_ids) {
   	// print_r($var_id);
   	// print_r($variations_images);
   	// print_r($stored_var_attach_ids);
   	// print_r($value_ids);
   	// echo "<br/>";
   	// die('variationTesting');
      $attachment_id           = '';
      $found                   = false;
      $value_ids               = empty( $value_ids ) ? array() : $value_ids;
      $variations_images_count = count($variations_images);
      $value_ids_count         = count($value_ids); 
      for ($i_k = 0; $i_k < $variations_images_count; $i_k++) { 
      	$i_info = $variations_images[$i_k]; 
      	for ($j = 0; $j < $value_ids_count; $j++) { 
      		$value_id = $value_ids[$j];
      		if ($value_id === $i_info['value_id'] ) { 
      			$attachment_id = isset( $stored_var_attach_ids[$i_info['image_id']] ) ? $stored_var_attach_ids[$i_info['image_id']] : '';
      			$found = true;
      			break;
      		}
      	}
      	if ($found) {
      	break;
      	}
      }
      $variation = new WC_Product_Variation( $var_id );
      $variation->set_image_id( $attachment_id );
      $variation->save();
      return true;
   }



	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Etsy_Integration_Add_On_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Etsy_Integration_Add_On_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-etsy-integration-add-on-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Etsy_Integration_Add_On_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Etsy_Integration_Add_On_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-etsy-integration-add-on-admin.js', array( 'jquery' ), $this->version, false );

	}





	

}
