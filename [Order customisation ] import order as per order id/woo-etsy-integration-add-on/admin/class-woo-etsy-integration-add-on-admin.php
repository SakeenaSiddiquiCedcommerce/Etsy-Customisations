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
       ini_set('display_errors', 0);
       ini_set('display_startup_errors', 0);
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_filter('ced_etsy_modify_order_page', array( $this, 'modifying_order_page_for_input_box' ), 99,  1);
		
	}




   /**
    * Modifies settings tab fields for WooCommerce Etsy Integration.
    * This function adds a new field to select WooCommerce global attributes for uploading images on variations.
    * @param  : $tab_fiels is an array of global setting fields 
    * @return : This function will return an array with modified setting fields
    */


   public function modifying_order_page_for_input_box($shop_name) {
    ?>
 
   	<input type="text" name="ced_etsy_fetch_order_by_order_id"  id ="ced_etsy_fetch_order_by_order_id" style = "float: left;">
    <input type="submit" value="Fetch by Etsy OrderId" class="button-primary alignright" style = "float: left;" id="ced_etsy_etsy_orders_by_order_id" data-shopname='<?php echo $shop_name; ?>'>
      <?php
   	
   }

   public function ced_etsy_bulk_import_order_by_order_id() {
   	$ced_etsy_order = new Ced_Order_Get();
   	$shop_name  = isset($_POST['shop_name']) ? $_POST['shop_name'] : '';
   	$orders     = isset($_POST['order_ids']) ? $_POST['order_ids'] : '';
   	if($orders == '') {
   		return;
   	}
   	$shop_id    =   etsy_shop_id($shop_name);
   	if(empty($shop_name)) {
   		return;
   	}
   	$order_id_array = explode(",", $orders);
   	if(!is_array($order_id_array)) {
   		$order_id_array[] = $order_id_array;
   	}
   	foreach ($order_id_array as $order_key => $order_value) {
   		do_action('ced_etsy_refresh_token', $shop_name);
   		$action = "application/shops/{$shop_id}/receipts/{$order_value}";
   		$result[] = etsy_request()->get( $action, $shop_name);
   		if(!empty($result)) {
   			$res    = $ced_etsy_order->createLocalOrder($result,  $shop_name);
   			
   		}

   	}

   	$status = 200;
   	$message = 'Your Etsy order has been successfully fetched. You can review the process in the timeline.';
   	echo json_encode(
   		array(
   			'status'  => $status,
   			'message' => $message,
   		)
   	);
   	wp_die();
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
