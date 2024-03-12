<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( is_array( $activeMarketplaces ) && ! empty( $activeMarketplaces ) ) {

	?>
	<div class="ced-marketplaces-heading-main-wrapper ced_etsy_setting_header cedcommerce-top-border">
		<div class="ced-marketplaces-heading-wrapper ">
			<h2><?php esc_html_e( 'Active Marketplaces', 'woocommerce-etsy-integration' ); ?></h2>
		</div>
	</div>
	<div class="ced-marketplaces-card-view-wrapper ced_etsy_body">
		<?php
		foreach ( $activeMarketplaces as $key => $value ) {
			$url = admin_url( 'admin.php?page=' . esc_attr( $value['menu_link'] ) );
			?>
			<div class="ced-marketplace-card <?php echo esc_attr( $value['name'] ); ?>">
				<a href="<?php echo esc_attr( $url ); ?>">
					<div class="thumbnail">
						<div class="thumb-img">
							<img class="img-responsive center-block integration-icons" src="<?php echo esc_attr( $value['card_image_link'] ); ?>" height="100" width="200" alt="how to sell on vip marketplace">
						</div>
					</div>
					<div class="mp-label"><?php echo esc_attr( $value['name'] ); ?></div>
				</a>
			</div>
			<?php
		}
		?>
	</div>
	<?php


}



$fetchedCategories = $this->etsy_cat_obj->get_etsy_categories( 'GoToStar' );

$prepared_data_1 = $prepared_data_2 = $prepared_data_3 = $prepared_data_4 = $prepared_data_5 = $prepared_data_6 = $prepared_data_7 = [];
echo '<pre>';

foreach($fetchedCategories['results'] as $key => $level_1){

	if($level_1['level'] == 1){
		$prepared_data_1[] = [
			'id' => $level_1['id'],
			'level' => $level_1['level'],
			'name' => $level_1['name'],
			'parent_id' => $level_1['parent_id'] ? $level_1['parent_id']  :'',
			'full_path_taxonomy_ids' => [$level_1['id']],
		];


		// $main_data_1 = array($level_1['id'] => $level_1['name'])

		$data_1[$level_1['id']] = $level_1['name'];

		

		if(count($level_1['children'])){

			foreach($level_1['children'] as $key => $level_2){

				if($level_2['level'] == 2){
					$prepared_data_2[] = [
						'id' => $level_2['id'],
						'level' => $level_2['level'],
						'name' => $level_2['name'],
						'parent_id' => $level_2['parent_id'] ? $level_2['parent_id']  :'',
						'full_path_taxonomy_ids' => [$level_2['parent_id'], $level_2['id']],
					];

					// $data_2[$level_2['id']] = $level_2['name']; 
					
					$data_1[$level_2['id']] = $level_2['name'];
					// $result=array_merge($data_1, $data_2);

					if(count($level_2['children'])){

						foreach($level_2['children'] as $key => $level_3){

							if($level_3['level'] == 3){
								$prepared_data_3[] = [
									'id' => $level_3['id'],
									'level' => $level_3['level'],
									'name' => $level_3['name'],
									'parent_id' => $level_3['parent_id'] ? $level_3['parent_id']  :'',
									'full_path_taxonomy_ids' => [$level_2['parent_id'],$level_3['parent_id'], $level_3['id']],
								];

								$data_1[$level_3['id']] = $level_3['name'];


								if(count($level_3['children'])){

									foreach($level_3['children'] as $key => $level_4){

										if($level_4['level'] == 4){
											$prepared_data_4[] = [
												'id' => $level_4['id'],
												'level' => $level_4['level'],
												'name' => $level_4['name'],
												'parent_id' => $level_4['parent_id'] ? $level_4['parent_id']  :'',
												'full_path_taxonomy_ids' => [$level_2['parent_id'],$level_3['parent_id'],$level_4['parent_id'], $level_4['id']],
											];

											$data_1[$level_4['id']] = $level_4['name'];


											if(count($level_4['children'])){

												foreach($level_4['children'] as $key => $level_5){

													if($level_5['level'] == 5){
														$prepared_data_5[] = [
															'id' => $level_5['id'],
															'level' => $level_5['level'],
															'name' => $level_5['name'],
															'parent_id' => $level_5['parent_id'] ? $level_5['parent_id']  :'',
															'full_path_taxonomy_ids' => [$level_2['parent_id'], $level_3['parent_id'], $level_4['parent_id'], $level_5['parent_id'], $level_5['id']],
														];

														$data_1[$level_5['id']] = $level_5['name'];


														if(count($level_5['children'])){

															foreach($level_5['children'] as $key => $level_6){

																if($level_6['level'] == 6){
																	$prepared_data_6[] = [
																		'id' => $level_6['id'],
																		'level' => $level_6['level'],
																		'name' => $level_6['name'],
																		'parent_id' => $level_6['parent_id'] ? $level_6['parent_id']  :'',
																		'full_path_taxonomy_ids' =>[$level_2['parent_id'], $level_3['parent_id'], $level_4['parent_id'], $level_5['parent_id'],$level_6['parent_id'], $level_6['id']],
														];

																	$data_1[$level_6['id']] = $level_6['name'];


																	if(count($level_6['children'])){

																		foreach($level_6['children'] as $key => $level_7){

																			if($level_7['level'] == 7){
																				$prepared_data_7[] = [
																					'id' => $level_7['id'],
																					'level' => $level_7['level'],
																					'name' => $level_7['name'],
																					'parent_id' => $level_7['parent_id'] ? $level_7['parent_id']  :'',
																					'full_path_taxonomy_ids' => [$level_2['parent_id'], $level_3['parent_id'], $level_4['parent_id'], $level_5['parent_id'],$level_6['parent_id'],$level_7['parent_id'], $level_7['id']],
																				];

																					$data_1[$level_7['id']] = $level_7['name'];


																				if(count($value['children'])){

																					foreach($value['children'] as $key => $value){

																						}
																					

																				}
																			}
																		}

																	}
																}

															}

														}
													}

												}

											}
										}

									}

								}
							}
						}

					}
				}

			}

		}
	}


	
}


// print $data_1 to get updated categories json.
print_r(json_encode($data_1) );

// print $prepared_data_1 to get updated categories json.
print_r(json_encode($prepared_data_1) );
// print $prepared_data_2 to get updated categories json.
print_r(json_encode($prepared_data_2) );
// print $prepared_data_3 to get updated categories json.
print_r(json_encode($prepared_data_3) );
// print $prepared_data_4 to get updated categories json.
print_r(json_encode($prepared_data_4) );
// print $prepared_data_5 to get updated categories json.
print_r(json_encode($prepared_data_5) );
// print $prepared_data_6 to get updated categories json.
print_r(json_encode($prepared_data_6) );
// print $prepared_data_7 to get updated categories json.
print_r(json_encode($prepared_data_7) );

?>