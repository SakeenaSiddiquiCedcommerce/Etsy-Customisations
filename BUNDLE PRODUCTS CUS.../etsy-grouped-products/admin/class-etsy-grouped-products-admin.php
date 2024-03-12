<?php

use Cedcommerce\Product;

use Cedcommerce\EtsyManager\Ced_Etsy_Request as Etsy_Request;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://cedcommerce.com
 * @since      1.0.0
 *
 * @package    Etsy_Grouped_Products
 * @subpackage Etsy_Grouped_Products/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Etsy_Grouped_Products
 * @subpackage Etsy_Grouped_Products/admin
 * @author     cedcommerce <plugins@cedcommerce.com>
 */
class Etsy_Grouped_Products_Admin {

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
	}

	/**
	 ****************************************************************
		 * Function for preparing group product data to be uploaded.
		 * **********************************************************
		 *
		 * @since 1.0.0
		 * @param array  $upload_instane upload instance
		 * @param array  $payload payload
		 * @param integer  $pr_id Checked Product ids
		 * @param string  $pro_type Checked Product type
		 * @param string  $shop_name Active Shop Name
		 *
		 * @return notifications
		 */
	public function ced_etsy_manage_group_product( $upload_instane, $payload, $pr_id, $pro_type, $shop_name) {

		if( $upload_instane->ced_product->is_type( 'grouped' ) && $upload_instane->ced_product->has_child()){
			$notification = array();
			$child_ids =  $upload_instane->ced_product->get_children(); 
			if ( count( $child_ids )  ) {
				$payload->grouped_product    = true;
				$payload->grouped_product_id = $pr_id;
				$first_product            = array_shift( $child_ids );
				$upload_instane->data = $payload->get_formatted_data( $first_product, $shop_name );
				if ( isset( $upload_instane->data['has_error'] ) ) {
					$notification['status']  = 400;
					$notification['message'] = $upload_instane->data['error'];
				} else {
					$upload_instane->doupload( $first_product, $shop_name );
					$response = $upload_instane->upload_response;
					if ( isset( $response['listing_id'] ) ) {
						$l_id = isset( $response['listing_id'] ) ? $response['listing_id'] : '';
						update_post_meta( $pr_id, '_ced_etsy_listing_id_' . $shop_name, $l_id );
						update_post_meta( $pr_id, '_ced_etsy_url_' . $shop_name, $response['url'] );
						update_post_meta( $pr_id, '_ced_etsy_listing_data_' . $shop_name, json_encode( $response ) );
						$offerings_payload = $this->ced_etsy_prepare_group_pro_as_variations( $pr_id, $shop_name, $upload_instane, $payload );
						$var_response      = $this->group_update_variation_sku_to_etsy( $upload_instane, $pr_id, $l_id, $shop_name, $offerings_payload, false );
						if ( ! isset( $var_response['products'][0]['product_id'] ) ) {
							$upload_instane->data['variation'] = $offerings_payload;
							$response                = $var_response;
							$notification['status']  = 400;
							$notification['message'] = isset( $var_response['error'] ) ? $var_response['error'] : '';
							$delete_instance->ced_etsy_delete_product( array( $pr_id ), $shop_name, false );
										// continue;
						} else {
							$upload_instane->ced_etsy_prep_and_upload_img( $pr_id, $shop_name, $l_id );
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
					$activity->input_payload = $upload_instane->data;
					$activity->response      = $response;
					$activity->post_id       = $pr_id;
					$activity->shop_name     = $shop_name;
					$activity->is_auto       = $is_sync;
					$activity->post_title    = $upload_instane->data['title'];
					$activity->execute();
					
				}

				return $notification;
			}
		}


		

	}

	/**
	 * ********************************************************
	 * GET VARIATION DATA TO UPDATE ON ETSY FOR GROUP PRODUCTS
	 * ********************************************************
	 *
	 * @since 1.0.0
	 *
	 * @param integar $pr_id checked product id.
	 * @param string $shop_name active shop name.
	 * @param array $upload_instane instance.
	 * @param array $payload payload.
	 *
	 * @link  http://www.cedcommerce.com/
	 * @return $payload
	 */
	public function ced_etsy_prepare_group_pro_as_variations( $pr_id = '', $shop_name = '', $upload_instane='', $payload='' ){
		$property_ids   = array();
		$product        = wc_get_product( $pr_id );
		$setPropertyIds = array();
		$final_attribute_variation_final = array();
		$childrens     = array_slice( $product->get_children() , 0 );
		foreach ( $childrens as $child ) {

			$child_product       = wc_get_product( $child );
			$title               = $child_product->get_title();
			$sku                 = $child_product->get_sku();
			$main_prod_price     = $child_product->get_price();
			$quantity            = $child_product->get_stock_quantity();
			$attribute_one       = $title;
			$attribute_two       = '';
			$attribute_one_value = $title;
			if( !empty( get_post_meta( $child, '_option_name', true )) ) {
				$attribute_one_value = get_post_meta( $child, '_option_name', true );
			}
			$attribute_two_value = '';
			$attribute_one_mapped = false;
			$attribute_two_mapped = false;
			$final_etsy_product_property_valuedem = array();
			$var_att_array                        = '';
			$property_id   = 514;
			$property_name       = 'Choice';
			$variation_key_value = $attribute_one_value;
			if ( isset( $variation_key_value ) && ! empty( $variation_key_value ) ) {
				if ( ! $attribute_one_mapped ) {
					$attribute_one_mapped = true;
					$property_id_one      = $property_id;
					$property_name_one    = ucwords($property_name );
				} else {
					$attribute_two_mapped = true;
					$property_id_two      = $property_id;
					$property_name_two    = ucwords( $property_name );
				}
				$final_attribute_variation         = array();
				$final_etsy_product_property_value = array();
				$var_att_array                    .= $variation_key_value . '~';
				$setPropertyIds[]                  = (int) $property_id;
				$final_etsy_product_property_value = array(
					'property_id'   => (int) $property_id,
					'property_name' => ucwords($property_name),
					'values'        => array( strtoupper( strtoupper( $variation_key_value ) ) ),
				);

				$payload->get_formatted_data( $child, $shop_name );
				$price        = $payload->get_price();
				$var_quantity = $payload->get_quantity();


				if ( $var_quantity < 1 ) {
					$var_quantity = 0;
				}

				$variation_max_qty                      = $var_quantity;
				$final_etsy_product_property_valuedem[] = $final_etsy_product_property_value;
				if ( $variation_max_qty <= 0 ) {
					$product_enable    = 0;
					$variation_max_qty = 0;
				} else {
					$product_enable = 1;
				}
				$product_enable = 1;
				$final_etsy_product_offering = array(
					array(
						'price'      => (float) $price,
						'quantity'   => (int) $variation_max_qty,
						'is_enabled' => $product_enable,
					),
				);

				$parent       = wc_get_product($child);
				$parent_sku   = $parent->get_sku();
				$var_sku      = $sku;
				if ( empty( $var_sku ) || strlen( $var_sku ) > 32 || $parent_sku == $var_sku ) {
					$var_sku = $parent_sku;
				}

				$final_attribute_variation = array(
					'sku'             => $var_sku,
					'property_values' => $final_etsy_product_property_valuedem,
					'offerings'       => $final_etsy_product_offering,
				);
			}


			$property_ids[] = $property_id;
			$final_attribute_variation_final[] = isset( $final_attribute_variation ) ? $final_attribute_variation : '';
		}

		$property_ids = array_unique( $property_ids );
		$property_ids = implode( ',', $property_ids );
		$payload      = array(
			'products'             => $final_attribute_variation_final,
			'price_on_property'    => $property_ids,
			'quantity_on_property' => $property_ids,
			'sku_on_property'      => $property_ids,
		);

		return $payload;
	}


	/**
	 * ********************************************************
	 * UPDATE INVENTORY ON ETSY FOR GROUP PRODUCTS
	 * ********************************************************
	 *
	 * @since 1.0.0
	 *
	 * @param integar $product_id Product  ids.
	 * @param string $shop_name active shop name.
	 * @param array $upload_instane instance.
	 * @param array $payload payload.
	 * @param integer $listing_id listing id.
	 *
	 * @link  http://www.cedcommerce.com/
	 * @return $payload
	 */
	public function ced_etsy_manage_inventory_group_pro_call ($upload_instane, $payload, $product_id, $_product, $shop_name, $listing_id){
		if ( 'grouped' == $_product->get_type() ) {
			$payload->grouped_product    = true;
			$payload->grouped_product_id = $product_id;
			$offerings_payload = $this->ced_etsy_prepare_group_pro_as_variations( $product_id, $shop_name, $upload_instane, $payload );
			$input_payload     = $offerings_payload;
			$response          = $this->ced_update_variation_sku_to_etsy_group_pro_call(  $product_id, $listing_id, $shop_name, $offerings_payload, false );

			return $response;
		}

	}

	/**
	 * ********************************************************
	 * UPDATE SKU ON ETSY FOR GROUP PRODUCTS
	 * ********************************************************
	 *
	 * @since 1.0.0
	 *
	 * @param integar $product_id Product  ids.
	 * @param string $shop_name active shop name.
	 * @param array $offerings_payload offerings_payload.
	 * @param integer $listing_id listing id.
	 *
	 * @link  http://www.cedcommerce.com/
	 * @return $payload
	 */
	public function ced_update_variation_sku_to_etsy_group_pro_call( $product_id = '', $listing_id = '', $shop_name = '', $offerings_payload = '', $is_sync = false ) {
			/** Refresh token
					 *
					 * @since 2.0.0
					 */
			do_action( 'ced_etsy_refresh_token', $shop_name );
			$response = etsy_request()->put( "application/listings/{$listing_id}/inventory", $offerings_payload, $shop_name );
			return $response;
		}



	/**
		 * *****************************************
		 * UPDATE VARIATION SKU TO ETSY SHOP
		 * *****************************************
		 *
		 * @since 1.0.0
		 *
		 * @param array  $upload_instane instance.
		 * @param integer  $listing_id Product lsting  ids.
		 * @param integer  $product_id Product  ids.
		 * @param string $shop_name Active shopName.
		 * @param array $offerings_payload offerings payload.
		 * @param boolean $is_sync sync.
		 * 
		 * @link  http://www.cedcommerce.com/
		 * @return $reponse
		 */

	public function group_update_variation_sku_to_etsy( $upload_instane='', $product_id = '', $listing_id = '', $shop_name = '', $offerings_payload = '', $is_sync = false ) {
			/** Refresh token
				 *
				 * @since 2.0.0
				 */
			do_action( 'ced_etsy_refresh_token', $shop_name );
			$response = $upload_instane->put( "application/listings/{$listing_id}/inventory", $offerings_payload, $shop_name );
			if ( isset( $response['products'][0]['product_id'] ) ) {
				update_post_meta( $product_id, 'ced_etsy_last_updated' . $shop_name, gmdate( 'l jS \of F Y h:i:s A' ) );
			}
			if ( ! $is_sync ) {
				return $response;
			}
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
		 * defined in Etsy_Grouped_Products_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Etsy_Grouped_Products_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/etsy-grouped-products-admin.css', array(), $this->version, 'all' );

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
		 * defined in Etsy_Grouped_Products_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Etsy_Grouped_Products_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/etsy-grouped-products-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
		 * *****************************************
		 * ADD GROUP PRODUCT TYPE IN WC_Product_Query
		 * *****************************************
		 *
		 * @since 1.0.0
		 * @param array  $need_append_product_types .
		 * 
		 * @link  http://www.cedcommerce.com/
		 * @return $need_append_product_types
		 */
	public function ced_append_group_product_type_details ($need_append_product_types){
		$product_types  = get_terms( 'product_type', array( 'hide_empty' => false ) );
		foreach ( $product_types as $key => $value ) {
			if ( 'grouped' == $value->name ) {
				$need_append_product_types[] = $value->name;
			}

		}

		return $need_append_product_types;
	}

	/**
		 * *****************************************
		 * ADD GROUP PRODUCT TYPE IN STATUS FILTERS
		 * *****************************************
		 *
		 * @since 1.0.0
		 * @param array  $need_append_product_types .
		 * 
		 * @link  http://www.cedcommerce.com/
		 * @return $need_append_product_types
		 */
	public function ced_append_group_product_type_filter ($need_append_product_types){
		$product_types  = get_terms( 'product_type', array( 'hide_empty' => false ) );
		foreach ( $product_types as $key => $value ) {
			if ( 'grouped' == $value->name ) {
				$need_append_product_types[ $value->term_id ] = ucfirst( $value->name );
			}

		}

		return $need_append_product_types;
	}


}
