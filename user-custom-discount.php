<?php
/**
 * Plugin Name: User Custom Discount
 * Plugin URI: https://www.beeplugin.com/
 * Description: User specific custom discount option.
 * Version: 1.21
 * Author: BeePlugin
 * Author URI: https://www.beeplugin.com
 */
register_activation_hook(__FILE__, 'beeplug_ucdp_beePluginTables');

function beeplug_ucdp_beePluginTables() {
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
  $table_name = $wpdb->prefix . 'customer_discount';
  $sql = "CREATE TABLE `$table_name` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `cust_id` int(11) NOT NULL,
	  `cat_id` int(11) NOT NULL,
	  `scat_id` int(11) NOT NULL,
	  `prod_id` int(11) NOT NULL,
	  `disc_per` int(11) NOT NULL,
	  `is_category` int(11) NOT NULL DEFAULT 0,
	  PRIMARY KEY(id)
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
  ";

  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
}
function beeplug_ucdp_inits() {
	add_action('admin_enqueue_scripts', 'beeplug_ucdp_load_scripts');
}
add_action('init', 'beeplug_ucdp_inits');

function beeplug_ucdp_load_scripts($hook) { 

	wp_enqueue_style('bootstrap_style',plugins_url('css/bootstrap.min.css', __FILE__) );
	wp_enqueue_style('user-css', plugins_url('css/user_style.css', __FILE__) );
	wp_enqueue_script( 'bootstrap-js',  plugins_url('js/bootstrap.bundle.min.js', __FILE__) );
	wp_enqueue_script( 'user-js', plugins_url('js/user_script.js', __FILE__) );
	
}

add_action('admin_menu', 'beeplug_ucdp_setup_menu');
 
function beeplug_ucdp_setup_menu(){
        add_menu_page( 'Manage Customer Discount', 'Manage Customer Discount', 'manage_options', 'user-custom-discount', 'beeplug_ucdp_init' );
        add_submenu_page('user-custom-discount', 'Try Pro version', 'Try Pro version', 'manage_options', 'bee-premium-version','beeplug_premium_version' );
}
function beeplug_ucdp_init(){
			$args = array(
			    'role'    => 'Customer',
			    'orderby' => 'first_name',
			    'order'   => 'ASC'
			);
			$users = get_users( $args );
			$bee_users="";
	?>
		<style type="text/css">
			.bee-addon-content {
			    position: relative;
			    background: #fff;
			    box-shadow: 0 1px 2px 0 rgba(34,36,38,.15);
			    margin: 3rem 0 1rem;
			    padding: 1em;
			    border-radius: 0.28571429rem;
			    border: 1px solid rgba(34,36,38,.15);
			    display: inline-block;
			}
			.bee-plugins {
			    width: 24%;
			    /* padding: 14px; */
			    float: left;
			    margin: 10px 10px 0 0;
			}
			.bee-plugins a {
    			display: inline-block;
			}
			.bee-plugins a img {
    			width: 100%;
			}
			.bee-plugins a img:hover {
    			opacity: 0.8;
			}
			.vi-ui.button {
			    height: auto;
			    cursor: pointer;
			    display: inline-block;
			    min-height: 1em;
			    outline: 0;
			    border: none;
			    vertical-align: baseline;
			    background: #e0e1e2 none;
			    color: rgba(0,0,0,.6);
			    font-family: Lato,'Helvetica Neue',Arial,Helvetica,sans-serif;
			    margin: 0 0.25em 0 0;
			    padding: 0.78571429em 1.5em 0.78571429em;
			    text-transform: none;
			    text-shadow: none;
			    font-weight: 300;
			    line-height: 1em;
			    font-style: normal;
			    text-align: center;
			    text-decoration: none;
			    border-radius: 0.28571429rem;
			    box-shadow: 0 0 0 1px transparent inset, 0 0 0 0 rgba(34,36,38,.15) inset;
			    -webkit-user-select: none;
			    -moz-user-select: none;
			    -ms-user-select: none;
			    user-select: none;
			    -webkit-transition: opacity .1s ease,background-color .1s ease,color .1s ease,box-shadow .1s ease,background .1s ease;
			    transition: opacity .1s ease,background-color .1s ease,color .1s ease,box-shadow .1s ease,background .1s ease;
			    will-change: '';
			    -webkit-tap-highlight-color: transparent;
			}
			.vi-ui.button:hover {
			    background-color: #cacbcd;
			    background-image: none;
			    box-shadow: 0 0 0 1px transparent inset, 0 0 0 0 rgba(34,36,38,.15) inset;
			    color: rgba(0,0,0,.8);
			}
			.vi-ui.green.button, .vi-ui.green.buttons .button {
			    background-color: #21ba45;
			    color: #fff;
			    text-shadow: none;
			    background-image: none;
			}
			.vi-ui.green.button:hover, .vi-ui.green.buttons .button:hover {
			    background-color: #16ab39;
			    color: #fff;
			    text-shadow: none;
			}
			.vi-ui.green.button:active, .vi-ui.green.buttons .button:active {
			    background-color: #198f35;
			    color: #fff;
			    text-shadow: none;
			}
			.vi-ui.icon.button, .vi-ui.icon.buttons .button {
			    padding: 0.78571429em 0.78571429em 0.78571429em;
			}
			.vi-ui.small.button, .vi-ui.small.buttons .button, .vi-ui.small.buttons .or {
			    font-size: .92857143rem;
			}
			.vi-ui.button>.icon:not(.button) {
			    height: 0.85714286em;
			    opacity: .8;
			    margin: 0 0.42857143em 0 -0.21428571em;
			    -webkit-transition: opacity .1s ease;
			    transition: opacity .1s ease;
			    vertical-align: '';
			    color: '';
			}
			.vi-ui.icon.button{position:relative;}
			.vi-ui.icon.button>.icon, .vi-ui.icon.buttons .button>.icon {
			    opacity: .9;
			    margin: 0!important;
			    vertical-align: top;
			}
			.vi-ui.labeled.icon.button>.icon, .vi-ui.labeled.icon.buttons>.button>.icon {
			    position: absolute;
			    height: 100%;
			    line-height: 1;
			    border-radius: 0;
			    border-top-left-radius: inherit;
			    border-bottom-left-radius: inherit;
			    text-align: center;
			    margin: 0;
			    width: 2.57142857em;
			    background-color: rgba(0,0,0,.05);
			    color: '';
			    box-shadow: -1px 0 0 0 transparent inset;
			}
			.vi-ui.labeled.icon.button>.icon, .vi-ui.labeled.icon.buttons>.button>.icon {
			    top: 0;
			    left: 0;
			}
			.vi-ui.labeled.icon.button>.icon:after, .vi-ui.labeled.icon.button>.icon:before, .vi-ui.labeled.icon.buttons>.button>.icon:after, .vi-ui.labeled.icon.buttons>.button>.icon:before {
			    display: block;
			    position: absolute;
			    width: 100%;
			    top: 50%;
			    text-align: center;
			    -webkit-transform: translateY(-50%);
			    transform: translateY(-50%);
			}
			.vi-ui.labeled.icon.button, .vi-ui.labeled.icon.buttons .button {
			    position: relative;
			    padding-left: 4.07142857em!important;
			    padding-right: 1.5em!important;
			}
			.bee-addon-content h3 {
			    font-size: 20px;
			}
			
		</style>
        <div class="container">
			<h3 align="center">Manage User Discount on Categories / SubCategories / Products</h3>
			<h4 align="center">Enter Discount Details</h4>
			<textarea id="txt_holder"></textarea>
			<form method="post" id="insert_form">
				<div class="table-repsonsive">
					<span id="error"></span>
					<table class="table table-bordered" id="item_table">
						<tr>
						   <th>Select User</th>
						   <th><select name="cust_list" class="form-control cust_list">
						   	<option value="">Select Customer</option>
						   	<?php
							foreach ( $users as $user )
							  {
							  	?>
							  	<option value="<?php echo sanitize_text_field($user->ID);?>"><?php echo sanitize_text_field($user->first_name);?> (<?php echo sanitize_email($user->user_email);?>)</option>';
							 <?php
							  }						  
							 
						   	?>
						   	</select>
						   </th>
						   <th>&nbsp;</th>
						   <th>&nbsp;</th>
						   <th>&nbsp;</th>
						   
					  	</tr>
						<tr>
						   <th>Select Category</th>
						   <th>Select Sub Category</th>
						   <th>Select Product</th>
						   <th>Enter Discount %</th>
						   <th><button type="button" name="add" class="btn btn-success btn-sm add">+</button></th>
					  </tr>
					</table>
					<div align="center">
						<input type="hidden" name="action"  value="beeplug_ucdp_discount_insert_action" />
					<input type="submit" name="submit" class="btn btn-info" value="Create Rule" />
					</div>
				</div>
			</form>
		</div>
		<div class="bee-addon-content">
			<h3>MAYBE YOU LIKE &nbsp;&nbsp;&nbsp;&nbsp; <a class="vi-ui button labeled icon small" target="_blank" href="https://www.beeplugin.com/documentation/custom-woocommerce-discount/">
			    <i class="icon dashicons dashicons-book-alt"></i> Documentation </a>			 
			  <a class="vi-ui button labeled icon green small" target="_blank" href="https://www.beeplugin.com/create-ticket/">
			    <i class="icon dashicons dashicons-groups"></i> Request Support </a>
			</h3>
		  <div class="bee-plugins">
		    <a href="https://www.beeplugin.com/custom-woocommerce-discount/" target="_blank">
		      <img title="WooCommerce Custom Discount for User" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/custom-discount-for-user.png" alt="Custom Discount for User">
		    </a>
		  </div>
		  <div class="bee-plugins">
		    <a href="https://www.beeplugin.com/abandoned-shopping-cart/" target="_blank">
		      <img title="WooCommerce Abandoned Shopping Cart Recovery" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/abandoned-shopping-cart.png" alt="Abandoned Shopping Cart">
		    </a>
		  </div>
		  <div class="bee-plugins">
		    <a href="https://www.beeplugin.com/bogo-deals-woocommerce-discount/" target="_blank">
		      <img title="WooCommerce Buy One Get One Free" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/bogo.png" alt="Buy One Get One Free">
		    </a>
		  </div>
		  <div class="bee-plugins">
		    <a href="https://www.beeplugin.com/custom-discount-rule-on-cart-total/" target="_blank">
		      <img title="WooCommerce Custom Discount on Cart Total" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/discount-on-cart-total.png" alt="Custom Discount on Cart Total">
		    </a>
		  </div>
		  <div class="bee-plugins">
		    <a href="https://www.beeplugin.com/user-role-based-discount/" target="_blank">
		      <img title="WooCommerce User Role Based Discount" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/user-role-based-discount.png" alt="User Role Based Discount">
		    </a>
		  </div>
		  <div class="bee-plugins">
		    <a href="https://www.beeplugin.com/custom-discount-on-product-tags/" target="_blank">
		      <img title="WooCommerce Discount on Product Tags" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/discount-on-product-tag.png" alt="Discount on Product Tags">
		    </a>
		  </div>
		  <div class="bee-plugins">
		    <a href="https://www.beeplugin.com/woocommerce-retail-discount-plugin/" target="_blank">
		      <img title="WooCommerce Retail Discount" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/woocommerce-retail-discount.png" alt="WooCommerce Retail Discount">
		    </a>
		  </div>
		  
		  
		</div>
		
<?php 

}


add_action('wp_ajax_beeplug_ucdp_get_discount_list_action', 'beeplug_ucdp_get_discount_list_function');
function beeplug_ucdp_get_discount_list_function(){

	$cust_id=sanitize_text_field($_POST['cust_id']);
	$result_data="";
	$resultArray = array();
	global $wpdb;
	$bee_table = $wpdb->prefix.'customer_discount';
	$user_query = "SELECT * FROM $bee_table WHERE `cust_id` = %d";
	$user_query = $wpdb->prepare($user_query, array($cust_id));
	$result_disc_list = $wpdb->get_results($user_query);
	$disc_count_list=count($result_disc_list);$j=0;
	if($result_disc_list)
	{
		//Get All Category
		$orderby = 'name';
		$order = 'asc';
		$hide_empty = false ;
		$cat_args = array(
		    'orderby'    => $orderby,
		    'order'      => $order,
		    'hide_empty' => $hide_empty,
		    'parent' => 0
		);
		$product_categories = get_terms( 'product_cat', $cat_args );
		foreach ( $result_disc_list as $disc_list )
		{
		   $cat_id=$disc_list->cat_id;
		   $scat_id=$disc_list->scat_id;
		   $prod_id=$disc_list->prod_id;
		   $disc_per=$disc_list->disc_per;		   
		   foreach ($product_categories as $key => $category) {
		   		if($category->term_id==$cat_id){$str="selected";}else{$str="";}
				$bee_cat_list[$j][]=array(
										'cat_id' => $category->term_id,
										'cat_name' => $category->name,
										'cat_sel' => $str, 
									);
			} 	

				$pcat_id=$cat_id;
				$args = array(
			       'hierarchical' => 1,
			       'show_option_none' => '',
			       'hide_empty' => 0,
			       'parent' => $pcat_id,
			       'taxonomy' => 'product_cat'
		   		);
		   		

			  	$subcats = get_categories($args);
			  	if(!empty($subcats)){			  				    
				    foreach ($subcats as $sc) {
				    	if($sc->term_id==$scat_id){$cstr="selected";}else{$cstr="";}
				    	$bee_subcat_list[$j][] = array(
				    							'scat_id' => $sc->term_id,
												'scat_name' => $sc->name,
												'scat_sel' => $cstr,
				    						);			    	
				    }
				}
				else{
						$bee_subcat_list[$j] = [];
				}

		   		if($scat_id==0)
		   			$pdcat_id=$cat_id;
		   		else
		   			$pdcat_id=$scat_id;
		   		
				$args = array(
			    'post_type'             => 'product',
			    'post_status'           => 'publish',
			    'ignore_sticky_posts'   => 1,
			    'posts_per_page'        => '12',
			    'tax_query'             => array(
				        array(
				            'taxonomy'      => 'product_cat',
				            'field' => 'term_id', //This is optional, as it defaults to 'term_id'
				            'terms'         => $pdcat_id,
				            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
				        ),
				        array(
				            'taxonomy'      => 'product_visibility',
				            'field'         => 'slug',
				            'terms'         => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
				            'operator'      => 'NOT IN'
				        )
			    	)
				);
				$loop = new WP_Query($args);
				$result_data.='<option value="">Select Product</option>';
				while ( $loop->have_posts() ) : $loop->the_post(); global $product; 
							if($loop->post->ID==$prod_id){$pstr="selected";}else{$pstr="";}
							$bee_prod_list[$j][] = array(
			    							'prod_id' => $loop->post->ID,
											'prod_name' => $loop->post->post_title,
											'prod_sel' => $pstr,
			    						);
				endwhile; 
		   $bee_discount_data[$j][]=$disc_per;		   
		  $j+=1;
		}
	}
	else
	{

	}  
	//print_r($bee_subcat_list); 
    $resultArray=array('bee_cat' => $bee_cat_list, 'bee_scat' => $bee_subcat_list, 'bee_prod' => $bee_prod_list, 'discount_list' => $bee_discount_data, 'counter' => $disc_count_list);
    echo json_encode($resultArray);
    wp_die();
}

add_action('wp_ajax_beeplug_ucdp_category_action', 'beeplug_ucdp_category_function');
function beeplug_ucdp_category_function(){

	$orderby = 'name';
	$order = 'asc';
	$hide_empty = false ;
	$cat_args = array(
	    'orderby'    => $orderby,
	    'order'      => $order,
	    'hide_empty' => $hide_empty,
	    'parent' => 0
	);
	$catResultArray=array();
	$product_categories = get_terms( 'product_cat', $cat_args );

	if( !empty($product_categories) ){

		foreach ($product_categories as $key => $category) {
			$cat_id[]=$category->term_id;
			$cat_name[]=$category->name;
			
		}

	}
	$catResultArray=array('cat_ids' => $cat_id, 'cat_names' => $cat_name);
	echo json_encode($catResultArray);

    wp_die();

}

add_action('wp_ajax_beeplug_ucdp_subcategory_action', 'beeplug_ucdp_subcategory_function');

function beeplug_ucdp_subcategory_function(){
		$cat_id=sanitize_text_field($_POST['cat_id']);
		$args = array(
	       'hierarchical' => 1,
	       'show_option_none' => '',
	       'hide_empty' => 0,
	       'parent' => $cat_id,
	       'taxonomy' => 'product_cat'
	    );
		$subcat_stat=0;		
		$scatResultArray=array();
  		$subcats = get_categories($args);
    	$sub_cat_count=count($subcats);
    	if($sub_cat_count>0)
    	{	
		     $subcat_stat=1;		    
		      foreach ($subcats as $sc) {
		      	$scat_id[]=$sc->term_id;
				$scat_name[]=$sc->name;
			  }
		}
		else{
			
			$args = array(
		    'post_type'             => 'product',
		    'post_status'           => 'publish',
		    'ignore_sticky_posts'   => 1,
		    'posts_per_page'        => '12',
		    'tax_query'             => array(
		        array(
		            'taxonomy'      => 'product_cat',
		            'field' => 'term_id', //This is optional, as it defaults to 'term_id'
		            'terms'         => $cat_id,
		            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
		        ),
		        array(
		            'taxonomy'      => 'product_visibility',
		            'field'         => 'slug',
		            'terms'         => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
		            'operator'      => 'NOT IN'
		        )
		    )
		);
		$loop = new WP_Query($args);		
		while ( $loop->have_posts() ) : $loop->the_post(); global $product;
			$scat_id[]=$loop->post->ID;
			$scat_name[]=$loop->post->post_title;			
		endwhile;
		}
	  		$scatResultArray=array('scat_ids' => $scat_id, 'scat_names' => $scat_name, 'scat_count' => $sub_cat_count);
    		echo json_encode($scatResultArray);
    		wp_die();
}
add_action('wp_ajax_beeplug_ucdp_product_list_action', 'beeplug_ucdp_product_list_function');

function beeplug_ucdp_product_list_function(){
		$cat_id=sanitize_text_field($_POST['cat_id']);
		$subcatResultArray=array();
		$args = array(
	    'post_type'             => 'product',
	    'post_status'           => 'publish',
	    'ignore_sticky_posts'   => 1,
	    'posts_per_page'        => '12',
	    'tax_query'             => array(
	        array(
	            'taxonomy'      => 'product_cat',
	            'field' => 'term_id', //This is optional, as it defaults to 'term_id'
	            'terms'         => $cat_id,
	            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
	        ),
	        array(
	            'taxonomy'      => 'product_visibility',
	            'field'         => 'slug',
	            'terms'         => 'exclude-from-catalog', // Possibly 'exclude-from-search' too
	            'operator'      => 'NOT IN'
	        )
	    )
	);
	$loop = new WP_Query($args);
	while ( $loop->have_posts() ) : $loop->the_post(); global $product;
		$prod_ids[]=$loop->post->ID;
		$prod_names[]=$loop->post->post_title;		
	endwhile;
	$subcatResultArray=array('prod_ids' => $prod_ids, 'prod_names' => $prod_names); 
    echo json_encode($subcatResultArray);
    wp_die();
}
add_action('wp_ajax_beeplug_ucdp_discount_insert_action', 'beeplug_ucdp_discount_insert');

function beeplug_ucdp_discount_insert(){

		global $wpdb; 		
		$cuid=sanitize_text_field($_POST["cust_list"]);
		//print_r($_POST);exit;
		$bee_table = $wpdb->prefix.'customer_discount';
		$del_query = "DELETE FROM $bee_table WHERE `cust_id` = %d";
		$del_query = $wpdb->prepare($del_query, array($cuid));
		$result_disc_list = $wpdb->query($del_query);
		
		 for($count = 0; $count < count($_POST["item_cat"]); $count++)
		 { 
		 		if($_POST["item_scat"][$count]==""){$scat=0;}else{$scat=sanitize_text_field($_POST["item_scat"][$count]);}
		 		if($_POST["item_prod"][$count]==""){$prod=0;}else{$prod=sanitize_text_field($_POST["item_prod"][$count]);}
		 	$data = array('cust_id'=>sanitize_text_field($_POST["cust_list"]),'cat_id'=>sanitize_text_field($_POST["item_cat"][$count]), 'scat_id'=>$scat, 'prod_id'=>$prod, 'disc_per'=>sanitize_text_field($_POST["disc_per"][$count]));
			$format = array('%d','%d','%d','%d','%d');
		 	$wpdb->insert($bee_table,$data,$format);
            $status = $wpdb->insert_id; 
		  	
			 
		}
		if($status>0)
		{
			echo 'ok';
		}
		wp_die();
		
}
// Variable and simple product displayed prices (removing sale price range)
add_filter( 'woocommerce_get_price_html', 'beeplug_ucdp_custom_get_price_html', 20, 2 );
function beeplug_ucdp_custom_get_price_html( $price, $product ) {

    if( $product->is_type('variable') )
    {
        if( is_user_logged_in() ){
			$s_min=$product->get_variation_sale_price('min');
			$s_max=$product->get_variation_sale_price('max');

			$current_user_id = get_current_user_id();
	    	$pid = $product->get_id();
	    	$product_cats = wp_get_post_terms($pid, 'product_cat');
	    	$cat_id=$product_cats[0]->parent;
	    	$scat_id=$product_cats[0]->term_id;
	    	if($cat_id==0)
	    		$cat_id=$scat_id;

	     	global $wpdb; 
			$bee_table = $wpdb->prefix.'customer_discount';

			$res_sql = 'SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d';
			$res_sql = $wpdb->prepare($res_sql, array($current_user_id,$pid));
			$result_prod = $wpdb->get_results($res_sql);
			
			if ( $result_prod )
			{
				$disc=$result_prod[0]->disc_per;
			}
			else
			{
				
				$scat_sql = 'SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d and scat_id=%d';
				$scat_sql = $wpdb->prepare($scat_sql, array($current_user_id,0,$scat_id));
				$result_scat = $wpdb->get_results($scat_sql);				
				if($result_scat)
				{
					$disc=$result_scat[0]->disc_per;
				}
				else
				{
					$scat_sql = 'SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d and scat_id=%d and cat_id=%d';
					$scat_sql = $wpdb->prepare($scat_sql, array($current_user_id,0,0,$cat_id));
					$result_cat = $wpdb->get_results($scat_sql);
					
					if($result_cat)
					{
						$disc=$result_cat[0]->disc_per;						
					}
					else
					{
						$disc=0;
					}
				}
			}

			$sale_min=$s_min-($s_min*($disc/100));
			$sale_max=$s_max-($s_max*($disc/100));
            $price_min  = wc_get_price_to_display( $product, array( 'price' => $sale_min ) );
            $price_max  = wc_get_price_to_display( $product, array( 'price' => $sale_max ) );
        } else {
            $price_min  = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_regular_price('min') ) );
            $price_max  = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_regular_price('max') ) );
        }

        if( $price_min != $price_max ){
            if( $price_min == 0 && $price_max > 0 )
                $price = wc_price( $price_max );
            elseif( $price_min > 0 && $price_max == 0 )
                $price = wc_price( $price_min );
            else
                $price = wc_format_price_range( $price_min, $price_max );
        } else {
            if( $price_min > 0 )
                $price = wc_price( $price_min);
        }
    }
    elseif( $product->is_type('simple') )
    {
        if( is_user_logged_in() ){
        	$current_user_id = get_current_user_id();
	    	$pid = $product->get_id();
	    	$product_cats = wp_get_post_terms($pid, 'product_cat');
	    	//print_r($product_cats);
	    	$cat_id=$product_cats[0]->parent;
	    	$scat_id=$product_cats[0]->term_id;
	    	if($cat_id==0)
	    		$cat_id=$scat_id;
	    	

	     	global $wpdb;
	     	$bee_table = $wpdb->prefix.'customer_discount';
	     	$res_sql = "SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d";
			$res_sql = $wpdb->prepare($res_sql, array($current_user_id,$pid));
			$result_prod = $wpdb->get_results($res_sql);
			//print_r($result_prod);
			if ( $result_prod )
			{
				$disc=$result_prod[0]->disc_per;
			}
			else
			{
				
				$scat_sql = "SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d and scat_id=%d";
				$scat_sql = $wpdb->prepare($scat_sql, array($current_user_id,0,$scat_id));
				$result_scat = $wpdb->get_results($scat_sql);
				if($result_scat)
				{
					$disc=$result_scat[0]->disc_per;
				}
				else
				{
					//echo "SELECT disc_per FROM $table WHERE cust_id=$current_user_id and prod_id=0 and scat_id=0 and cat_id=$cat_id";
					$scat_sql = "SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d and scat_id=%d and cat_id=%d";
					$scat_sql = $wpdb->prepare($scat_sql, array($current_user_id,0,0,$cat_id));
					$result_cat = $wpdb->get_results($scat_sql);
					if($result_cat)
					{
						$disc=$result_cat[0]->disc_per;					
					}
					else
					{
						$disc=0;
					}
				}
			}
			//echo $product->get_regular_price();
			$sim_price = ($product->get_sale_price()=='') ? $product->get_regular_price() : $product->get_sale_price();
			//$sim_price=$product->get_sale_price();
			$final_price_sim=$sim_price-($sim_price*($disc/100));
            $active_price = wc_get_price_to_display( $product, array( 'price' => $final_price_sim ) );
			
		}
        else{
            $active_price = wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) );
		}

        if( $active_price > 0 )
            $price = wc_price($active_price);
    }
	if( is_user_logged_in() ){
		//$price=$price-($price*(10/100));
		//if($disc>0){$dstr=$disc."% off on Trade Price";}else{$dstr="";}
    	//return $price. " (Excl. GST) ".$dstr;
    	if($disc>0){$dstr=$disc."% off on Actual Price";$strike="<s>".$sim_price."</s> ";}else{$dstr="";$strike="";}
    	return  $strike. $price. " ".$dstr;
	}
	else{
		//return $price." (Excl. GST) <span class='logged'>For trade pricing, please login <a href='https://beeplugin.andolasoft.co.in/my-account/'>here</a> ";
		return $price."<br><span class='logged'>For discount price, please login <a href='".get_site_url()."/my-account/'>here</a> ";
	}	
}

// Product Variation displayed prices
add_filter( 'woocommerce_available_variation', 'beeplug_ucdp_custom_variation_price', 10, 3);
function beeplug_ucdp_custom_variation_price( $data, $product, $variation ) {

    $reg_price = wc_get_price_to_display( $variation, array( 'price' => $variation->get_regular_price() ) );
    $sale_price = wc_get_price_to_display( $variation, array( 'price' => $variation->get_sale_price() ) );

    if( is_user_logged_in() ){

    	$current_user_id = get_current_user_id();
    	$pid = $product->get_id();
    	$product_cats = wp_get_post_terms($pid, 'product_cat');
    	$cat_id=$product_cats[0]->parent;
    	$scat_id=$product_cats[0]->term_id;
    	if($cat_id==0)
	    		$cat_id=$scat_id;

     	global $wpdb;
     	$bee_table = $wpdb->prefix.'customer_discount';
		$res_sql = "SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d";
		$res_sql = $wpdb->prepare($res_sql, array($current_user_id,$pid));
		$result_prod = $wpdb->get_results($res_sql);
		if ( $result_prod )
		{
			$disc=$result_prod[0]->disc_per;
		}
		else
		{
			$scat_sql = "SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d and scat_id=%d";
			$scat_sql = $wpdb->prepare($scat_sql, array($current_user_id,0,$scat_id));
			$result_scat = $wpdb->get_results($scat_sql);
			if($result_scat)
			{
				$disc=$result_scat[0]->disc_per;
			}
			else
			{
				$scat_sql = "SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d and scat_id=%d and cat_id=%d";
				$scat_sql = $wpdb->prepare($scat_sql, array($current_user_id,0,0,$cat_id));
				$result_cat = $wpdb->get_results($scat_sql);

				if($result_cat)
				{
					$disc=$result_cat[0]->disc_per;						
				}
				else
				{
					$disc=0;
				}
			}
		}

		$sale_pricep=$sale_price-($sale_price*($disc/100));
		if($disc>0)
		{
			$ostr=" <span style='color: #0a3a5b;font-weight: bold;'>(Excl. GST)</span> <span class='off_per'>".$disc."% off on Trade Price</span>";
			$data['price_html'] = "<del><span class='off_per'>$".$sale_price."</span></del>".wc_price( $sale_pricep ).$ostr;
		}
		else
		{
			$ostr=" <span style='color: #0a3a5b;font-weight: bold;'>(Excl. GST)</span>";
			if($sale_price==$reg_price)
			{
				$data['price_html'] = wc_price( $reg_price ).$ostr;
			}
			else
			{
				$data['price_html'] = "<del><span class='off_per'>$".$reg_price."</span></del>".wc_price( $sale_pricep ).$ostr;
			}
			
		}
        
	}
    else{
        $data['price_html'] = wc_price( $reg_price );
	}

    return $data;
}

// Set the correct prices in cart
add_action( 'woocommerce_before_calculate_totals', 'beeplug_ucdp_set_item_cart_prices', 1000, 1 );
function beeplug_ucdp_set_item_cart_prices( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    // Loop through cart items

    foreach ( $cart->get_cart() as $cart_item ){
        if( ! is_user_logged_in() ){
            $cart_item['data']->set_price( $cart_item['data']->get_regular_price() );
        }else{


        	$pid = $cart_item['product_id'];
        	$current_user_id = get_current_user_id();
	    	//$pid = $product->get_id();
	    	$product_cats = wp_get_post_terms($pid, 'product_cat');
	    	$cat_id=$product_cats[0]->parent;
	    	$scat_id=$product_cats[0]->term_id;
	    	if($cat_id==0)
	    		$cat_id=$scat_id;

	     	global $wpdb;
	     	$bee_table = $wpdb->prefix.'customer_discount';
			$res_sql = "SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d";
			$result_prod = $wpdb->prepare($res_sql, array($current_user_id,$pid));
			if ( $result_prod )
			{
				$disc=$result_prod[0]->disc_per;
			}
			else
			{
				$scat_sql = "SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d and scat_id=%d";
				$scat_sql = $wpdb->prepare($scat_sql, array($current_user_id,0,$scat_id));
				$result_scat = $wpdb->get_results($scat_sql);
				if($result_scat)
				{
					$disc=$result_scat[0]->disc_per;
				}
				else
				{
					$scat_sql = "SELECT disc_per FROM $bee_table WHERE cust_id=%d and prod_id=%d and scat_id=%d and cat_id=%d";
					$scat_sql = $wpdb->prepare($scat_sql, array($current_user_id,0,0,$cat_id));
					$result_cat = $wpdb->get_results($scat_sql);
					if($result_cat)
					{
						$disc=$result_cat[0]->disc_per;						
					}
					else
					{
						$disc=0;
					}
				}
			}
			//echo $disc;
			$sale_price=$cart_item['data']->get_sale_price();
			$reg_price=$cart_item['data']->get_regular_price();
			if($sale_price>0)
			{
				$final_sale=$sale_price-($sale_price*($disc/100));
			}
			else
			{
				$final_sale=$reg_price-($reg_price*($disc/100));
			}
			//echo $final_sale;						
			$cart_item['data']->set_price( $final_sale );
			//wp_dequeue_script( 'wc-cart-fragments' );
		}
    }
}
// add_action( 'admin_enqueue_scripts', 'bee_scripts', 11 ); 
 
// function bee_scripts() { 
//    wp_enqueue_script( 'bee_js_script', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN', array(), '1.0' );   
// }
// add_action( 'admin_enqueue_styles', 'bee_disable_woocommerce_cart_fragments', 11 ); 
 
// function bee_disable_woocommerce_cart_fragments() { 
//    //wp_dequeue_script( 'wc-cart-fragments' );   
// }
// Remove sale badge
// add_action( 'admin_enqueue_scripts', 'bee_scripts', 11 ); 
 
// function bee_scripts() { 
//    wp_enqueue_script( 'bee_js_script', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN', array(), '1.0' );   
// }
// add_action( 'admin_enqueue_styles', 'bee_disable_woocommerce_cart_fragments', 11 ); 
 
// function bee_disable_woocommerce_cart_fragments() { 
//    //wp_dequeue_script( 'wc-cart-fragments' );   
// }
// Remove sale badge
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );


add_filter( 'woocommerce_cart_item_price', 'beeplug_ucdp_change_item_price', 10, 3 );
function beeplug_ucdp_change_item_price( $price, $cart_item, $cart_item_key ) {

    $price = $cart_item['data']->get_price_html();

    return $price;
}

function beeplug_premium_version(){
?>
	<style type="text/css">
		.tab-pane thead {
		    background: #ff7400 !important;
		    color: #fff !important;
		}
		.tab-pane thead th{
			font-size: 18px;
		}
		.pricing_list_section tr:nth-child(even) {
		    background-color: #ededed;		    
		}
		.table{
			border: #d5d5d5 thin solid;
		}
		table tbody tr td {
		    font-size: 18px;
		    color: #121212;
		}
		.table td:nth-child(2), .table td:nth-child(3){
		    text-align: center;
		}
		th.th-sm:nth-child(2), th.th-sm:nth-child(3), th.th-sm:nth-child(4) {
		    text-align: center;
		}
		td .dashicons.dashicons-saved{
			color: #008a23;
		    font-size: 34px;
		    height: 25px;
		    width: 35px;
		    line-height: 25px;
		}
		td .dashicons.dashicons-dismiss{
			color: #ff2b2b;
		    font-size: 25px;
		    height: 25px;
		    width: 35px;
		    line-height: 25px;
		}
		.freedownload_plugin, .buy_cstm_plugin {
		    color: #fff;
		    border: 1px solid rgb(255 117 0);
		    font-size: 16px;
		    border-radius: 3px;
		    padding: 8px 8px;
		    font-weight: 500;
		    background: rgb(255 117 0);
		    text-decoration: none;
		    width: 100%;
    		display: inline-block;
		}
		.buy_cstm_plugin:hover {
			color: rgb(255 117 0);
		    border: 1px solid rgb(255 117 0);
		    font-size: 16px;
		    border-radius: 3px;
		    padding: 8px 8px;
		    font-weight: 500;
		    background: #fff;
		}
		.bee-addon-content {
			    position: relative;
			    background: #fff;
			    box-shadow: 0 1px 2px 0 rgba(34,36,38,.15);
			    margin: 3rem 0 1rem;
			    padding: 1em;
			    border-radius: 0.28571429rem;
			    border: 1px solid rgba(34,36,38,.15);
			    display: inline-block;
			}
			.bee-plugins {
			    width: 24%;
			    /* padding: 14px; */
			    float: left;
			    margin: 10px 10px 0 0;
			}
			.bee-plugins a {
    			display: inline-block;
			}
			.bee-plugins a img {
    			width: 100%;
			}
			.bee-plugins a img:hover {
    			opacity: 0.8;
			}
			.vi-ui.button {
			    height: auto;
			    cursor: pointer;
			    display: inline-block;
			    min-height: 1em;
			    outline: 0;
			    border: none;
			    vertical-align: baseline;
			    background: #e0e1e2 none;
			    color: rgba(0,0,0,.6);
			    font-family: Lato,'Helvetica Neue',Arial,Helvetica,sans-serif;
			    margin: 0 0.25em 0 0;
			    padding: 0.78571429em 1.5em 0.78571429em;
			    text-transform: none;
			    text-shadow: none;
			    font-weight: 300;
			    line-height: 1em;
			    font-style: normal;
			    text-align: center;
			    text-decoration: none;
			    border-radius: 0.28571429rem;
			    box-shadow: 0 0 0 1px transparent inset, 0 0 0 0 rgba(34,36,38,.15) inset;
			    -webkit-user-select: none;
			    -moz-user-select: none;
			    -ms-user-select: none;
			    user-select: none;
			    -webkit-transition: opacity .1s ease,background-color .1s ease,color .1s ease,box-shadow .1s ease,background .1s ease;
			    transition: opacity .1s ease,background-color .1s ease,color .1s ease,box-shadow .1s ease,background .1s ease;
			    will-change: '';
			    -webkit-tap-highlight-color: transparent;
			}
			.vi-ui.button:hover {
			    background-color: #cacbcd;
			    background-image: none;
			    box-shadow: 0 0 0 1px transparent inset, 0 0 0 0 rgba(34,36,38,.15) inset;
			    color: rgba(0,0,0,.8);
			}
			.vi-ui.green.button, .vi-ui.green.buttons .button {
			    background-color: #21ba45;
			    color: #fff;
			    text-shadow: none;
			    background-image: none;
			}
			.vi-ui.green.button:hover, .vi-ui.green.buttons .button:hover {
			    background-color: #16ab39;
			    color: #fff;
			    text-shadow: none;
			}
			.vi-ui.green.button:active, .vi-ui.green.buttons .button:active {
			    background-color: #198f35;
			    color: #fff;
			    text-shadow: none;
			}
			.vi-ui.icon.button, .vi-ui.icon.buttons .button {
			    padding: 0.78571429em 0.78571429em 0.78571429em;
			}
			.vi-ui.small.button, .vi-ui.small.buttons .button, .vi-ui.small.buttons .or {
			    font-size: .92857143rem;
			}
			.vi-ui.button>.icon:not(.button) {
			    height: 0.85714286em;
			    opacity: .8;
			    margin: 0 0.42857143em 0 -0.21428571em;
			    -webkit-transition: opacity .1s ease;
			    transition: opacity .1s ease;
			    vertical-align: '';
			    color: '';
			}
			.vi-ui.icon.button{position:relative;}
			.vi-ui.icon.button>.icon, .vi-ui.icon.buttons .button>.icon {
			    opacity: .9;
			    margin: 0!important;
			    vertical-align: top;
			}
			.vi-ui.labeled.icon.button>.icon, .vi-ui.labeled.icon.buttons>.button>.icon {
			    position: absolute;
			    height: 100%;
			    line-height: 1;
			    border-radius: 0;
			    border-top-left-radius: inherit;
			    border-bottom-left-radius: inherit;
			    text-align: center;
			    margin: 0;
			    width: 2.57142857em;
			    background-color: rgba(0,0,0,.05);
			    color: '';
			    box-shadow: -1px 0 0 0 transparent inset;
			}
			.vi-ui.labeled.icon.button>.icon, .vi-ui.labeled.icon.buttons>.button>.icon {
			    top: 0;
			    left: 0;
			}
			.vi-ui.labeled.icon.button>.icon:after, .vi-ui.labeled.icon.button>.icon:before, .vi-ui.labeled.icon.buttons>.button>.icon:after, .vi-ui.labeled.icon.buttons>.button>.icon:before {
			    display: block;
			    position: absolute;
			    width: 100%;
			    top: 50%;
			    text-align: center;
			    -webkit-transform: translateY(-50%);
			    transform: translateY(-50%);
			}
			.vi-ui.labeled.icon.button, .vi-ui.labeled.icon.buttons .button {
			    position: relative;
			    padding-left: 4.07142857em!important;
			    padding-right: 1.5em!important;
			}
			.bee-addon-content h3 {
			    font-size: 20px;
			}
	</style>
	<div class="container wrap">
			
			<br>
			<div class="tab-pane active" id="tab4">
			   <h2>Personalized Discounts for Specific Audience with PRO Version</h2>
			   <h3 class="beesub-heading" style="text-align: center;">Free Vs Pro</h3>
			   <div class="table-responsive">
			      <table class="table">
			         <thead>
			            <tr>
			               <th class="th-sm" style="text-align: left;">Compare all features by pricing plan
			               </th>
			               <th class="th-sm"> Free Version</th>
			               <th class="th-sm">Pro Version</th>
			            </tr>
			         </thead>
			         <tbody class="pricing_list_section">
			            <tr>
			               <td>User specific discount.</td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>			            
			            <tr>
			               <td>Product category based discount</td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>Product sub-category based discount</td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>Product specific discount.</td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>Percentage based discount</td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>Fixed cost discount</td>
			               <td>
			                  <i class="dashicons dashicons-dismiss"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>User-role based discount.</td>
			               <td>
			                  <i class="dashicons dashicons-dismiss"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>Store-wide discount.</td>
			               <td>
			                  <i class="dashicons dashicons-dismiss"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>Date-range discount</td>
			               <td>
			                  <i class="dashicons dashicons-dismiss"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>Role based custom registration</td>
			               <td>
			                  <i class="dashicons dashicons-dismiss"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>8 Hrs free customization support</td>
			               <td>
			                  <i class="dashicons dashicons-dismiss"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>Bug fixes in 48 Hrs</td>
			               <td>
			                  <i class="dashicons dashicons-dismiss"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>24*7 free Email Support.</td>
			               <td>
			                  <i class="dashicons dashicons-dismiss"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td> Plugin Installation &amp; Setup</td>
			               <td>
			                  <i class="dashicons dashicons-dismiss"></i>
			               </td>
			               <td>
			                  <i class="dashicons dashicons-saved"></i>
			               </td>
			            </tr>
			            <tr>
			               <td>&nbsp;</td>
			               <td>
			                  &nbsp;
			               </td>
			               <td>
			                  <a id="buy-now" target="_blank" class="buy_cstm_plugin" href="https://www.beeplugin.com/custom-woocommerce-discount/" title="Get Pro" style=" padding: 7px 20px;"> Get Pro</a>
			               </td>
			            </tr>
			         </tbody>
			      </table>
			   </div>
			</div>
			<div class="bee-addon-content">
				<h3>MAYBE YOU LIKE &nbsp;&nbsp;&nbsp;&nbsp; <a class="vi-ui button labeled icon small" target="_blank" href="https://www.beeplugin.com/documentation/custom-woocommerce-discount/">
				    <i class="icon dashicons dashicons-book-alt"></i> Documentation </a>			 
				  <a class="vi-ui button labeled icon green small" target="_blank" href="https://www.beeplugin.com/create-ticket/">
				    <i class="icon dashicons dashicons-groups"></i> Request Support </a>
				</h3>
			  	<div class="bee-plugins">
				    <a href="https://www.beeplugin.com/custom-woocommerce-discount/" target="_blank">
				      <img title="WooCommerce Custom Discount for User" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/custom-discount-for-user.png" alt="Custom Discount for User">
				    </a>
				</div>
				<div class="bee-plugins">
				    <a href="https://www.beeplugin.com/abandoned-shopping-cart/" target="_blank">
				      <img title="WooCommerce Abandoned Shopping Cart Recovery" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/abandoned-shopping-cart.png" alt="Abandoned Shopping Cart">
				    </a>
				</div>
				<div class="bee-plugins">
				    <a href="https://www.beeplugin.com/bogo-deals-woocommerce-discount/" target="_blank">
				      <img title="WooCommerce Buy One Get One Free" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/bogo.png" alt="Buy One Get One Free">
				    </a>
				</div>
				<div class="bee-plugins">
				    <a href="https://www.beeplugin.com/custom-discount-rule-on-cart-total/" target="_blank">
				      <img title="WooCommerce Custom Discount on Cart Total" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/discount-on-cart-total.png" alt="Custom Discount on Cart Total">
				    </a>
				</div>
				<div class="bee-plugins">
				    <a href="https://www.beeplugin.com/user-role-based-discount/" target="_blank">
				      <img title="WooCommerce User Role Based Discount" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/user-role-based-discount.png" alt="User Role Based Discount">
				    </a>
				</div>
				<div class="bee-plugins">
				    <a href="https://www.beeplugin.com/custom-discount-on-product-tags/" target="_blank">
				      <img title="WooCommerce Discount on Product Tags" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/discount-on-product-tag.png" alt="Discount on Product Tags">
				    </a>
				</div>
				<div class="bee-plugins">
				    <a href="https://www.beeplugin.com/woocommerce-retail-discount-plugin/" target="_blank">
				      <img title="WooCommerce Retail Discount" src="https://demo.beeplugin.com/custom_discount_plugin/wp-content/plugins/user-custom-discount-v2.2.3/images/plugins/woocommerce-retail-discount.png" alt="WooCommerce Retail Discount">
				    </a>
				</div>
			</div>
	</div>

<?php

}
add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
	   li#toplevel_page_user-custom-discount ul.wp-submenu li:nth-child(3) a {
	    color: #ff7400 !important;
	    font-weight: bold;
	}
  </style>';
}


?>