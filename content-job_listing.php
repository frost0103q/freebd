<?php global $post; 
$position = Kirki::get_option( 'workscout','pp_job_list_logo_position', 'left' );
$layout = Kirki::get_option( 'workscout','pp_jobs_old_layout', false );
$_color_job_type = Kirki::get_option( 'workscout','pp_maps_marker_color_job_type', false );
if ( get_option( 'job_manager_enable_types' ) ) {
	$types = get_the_terms( $post->ID, 'job_listing_type' );
	if ( $types && ! is_wp_error( $types ) && $_color_job_type) : 
		$single_type = $types[0]; 
		$markercolor = get_term_meta( $single_type->term_id, 'color', true );
	endif;
}
if(!$layout) {
$membertype = get_post_meta($post->ID,'package_plan_type',true);
?>
<style>
.paidjobs .free-jobs {
    display: none !important;
}
.freesection .paid-jobs {
    display: none !important;
}
</style>
<!-- Listing -->
<li data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" <?php if(isset($markercolor)) { echo 'data-color="#'.$markercolor.'"'; } ?> class="<?php if($membertype == 'paid '){ echo 'paid-jobs'; } else if($membertype == 'free'){ echo 'free-jobs'; } ?> home-jobs matchheight">
	
<a href="<?php the_job_permalink(); ?>" <?php job_listing_class($position); ?> data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>">
	<div class="listing-logo">
		<?php ($position == 'left') ? the_company_logo() : the_company_logo('medium'); ?>
	</div>
	<div class="listing-title">
	
		<h4><?php echo wp_trim_words(get_the_title(), 2,  ''); ?> 
			<?php 
					
					if ( $types && ! is_wp_error( $types ) ) : 
						foreach ( $types as $type ) { ?>
							<span class="job-type <?php echo sanitize_title( $type->slug ); ?>"><?php echo $type->name; ?></span>
					<?php }
					endif;?>
			
		</h4>
		<ul class="listing-icons">
			<?php do_action( 'workscout_job_listing_meta_start' ); ?>
				
			<?php $job_meta = Kirki::get_option( 'workscout','pp_meta_job_list',array('company','location','rate','salary') ); ?>
			<?php if (in_array("company", $job_meta) && get_the_company_name()) { ?>
				<li><i class="ln ln-icon-Management"></i> <?php  the_company_name();?></li>
			<?php } ?>
			
			
			
			<?php if (in_array("location", $job_meta)){ ?>

				<li> <i class="ln ln-icon-Map2"></i> <?php echo wp_trim_words(get_post_meta( $post->ID, '_job_location', true ), 2,  '');  ?> </li>
			<?php } ?>
			
			<?php 
				$currency_position =  get_option('workscout_currency_position','before');

				    $rate_min = get_post_meta( $post->ID, '_rate_min', true ); 				
				if ( $rate_min && in_array("rate", $job_meta)) { 
					$rate_max = get_post_meta( $post->ID, '_rate_max', true );  ?>
					<li><i class="ln ln-icon-Money-2"></i>  <?php 
						if( $currency_position == 'before' ) { 
                            echo get_workscout_currency_symbol(); 
                        } 
						echo esc_html( $rate_min ); 
						if( $currency_position == 'after' ) { 
                            echo get_workscout_currency_symbol(); 
                        }
						if(!empty($rate_max)) { 
							echo '- ';
							if( $currency_position == 'before' ) { 
                                echo get_workscout_currency_symbol(); 
                            } 	
							echo $rate_max;
							if( $currency_position == 'after' ) { 
                                echo get_workscout_currency_symbol(); 
                            } 
						} ?> <?php esc_html_e('/ hour','workscout'); ?>
					</li>
				<?php } ?>
				<?php 
				$salary_min = get_post_meta( $post->ID, '_salary_min', true ); 
				$salary_max = get_post_meta( $post->ID, '_salary_max', true );
				if( in_array("salary", $job_meta) ) :
					if ( !empty($salary_min) || !empty($salary_max)  ) { ?>
						<li><i class="ln ln-icon-Money-2"></i> 
							<?php 
							if ( $salary_min ) { 
								if( $currency_position == 'before' ) { 
	                                echo get_workscout_currency_symbol(); 
	                            } 	
								echo esc_html( $salary_min ); 
								if( $currency_position == 'after' ) { 
	                                echo get_workscout_currency_symbol(); 
	                            }
							} 
							if($salary_max) { if ( $salary_min ) { echo ' - '; } 
								if( $currency_position == 'before' ) { 
	                                echo get_workscout_currency_symbol(); 
	                            } 
								echo $salary_max;
								if( $currency_position == 'after' ) { 
	                                echo get_workscout_currency_symbol(); 
	                            }
							} ?>
						</li>
					<?php } 
				endif; ?>

				<!-- modify by Jfrost -->
				<?php
					$exp_range = get_post_meta( $post->ID, '_exp_range', true );
					if ( $exp_range ) { ?>
					<li>
						<i class="fa fa-history" style="font-size: 18px;"></i>
						<?php 
						$exp_range_arr = array( 
							'any' => 'Any' , 
							'below_1' => 'Below 1 year' ,
							'1_3' => '1 - < 3 years' ,
							'3_5' => '3 - < 5 years' ,
							'5_10' => '5 - < 10 years' , 
							'over_10' => 'Over 10 years');
						echo $exp_range_arr[$exp_range];  ?>
					</li>
					<?php 
					}
				?>
			<?php 
			 if (in_array("date", $job_meta)) {
				if(workscout_newly_posted()) { 
					 echo '<li><div class="listing-date new"> '.esc_html__('new','workscout').'</div></li>'; 
				} else { ?>
					<li><div class="listing-date"> <?php the_job_publish_date() ?></div></li>
				<?php	} 
			}  ?>
			<?php  if (in_array("deadline", $job_meta)) { ?>
					<?php 
					if ( $deadline = get_post_meta( $post->ID, '_application_deadline', true ) ) {
						$expiring_days = apply_filters( 'job_manager_application_deadline_expiring_days', 2 );
						$expiring = ( floor( ( time() - strtotime( $deadline ) ) / ( 60 * 60 * 24 ) ) >= $expiring_days );
						$expired  = ( floor( ( time() - strtotime( $deadline ) ) / ( 60 * 60 * 24 ) ) >= 0 );?>
						<li><div class="listing-date">
						<?php  echo  ( $expired ? __( 'Closed', 'workscout' ) : __( 'Closes', 'workscout' ) ) .': ' . date_i18n( get_option( 'date_format' ), strtotime( $deadline ) ) ?>
							</div></li>
				<?php } 
				}?>				

				<?php if (in_array("expires", $job_meta)) { ?>
					<li><div class="listing-date">
					<?php esc_html_e( 'Expires', 'workscout' ) ?>:  <?php echo date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, '_job_expires', true ) ) ) ?>
					</div></li>
				<?php } ?>
				<div class="listing-desc"><?php the_excerpt(); ?></div>
			
			
		</ul>
		
	</div>
</a>
</li>
<?php 	
} else { ?>

<li <?php job_listing_class($position); ?> data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>" <?php if(isset($markercolor)) { echo 'data-color="#'.$markercolor.'"'; } ?>>
	<a href="<?php the_job_permalink(); ?>">
		<?php 
		
		($position == 'left') ? the_company_logo() : the_company_logo('medium'); ?>
		<div class="job-list-content">
			<h4><?php the_title(); ?> 
			<?php if ( get_option( 'job_manager_enable_types' ) ) { 
					$types = get_the_terms( $post->ID, 'job_listing_type' );
					if ( $types && ! is_wp_error( $types ) ) : 
						foreach ( $types as $type ) { ?>
							<span class="job-type <?php echo sanitize_title( $type->slug ); ?>"><?php echo $type->name; ?></span>
					<?php }
					endif;?>
				<?php } ?>
			<?php if(workscout_newly_posted()) { echo '<span class="new_job">'.esc_html__('NEW','workscout').'</span>'; } ?> 
			</h4>

			<div class="job-icons">
				<?php do_action( 'workscout_job_listing_meta_start' ); ?>
				
				<?php $job_meta = Kirki::get_option( 'workscout','pp_meta_job_list',array('company','location','rate','salary') ); ?>
				
				<?php if (in_array("company", $job_meta) && get_the_company_name()) { ?>
					<span class="ws-meta-company-name"><i class="fa fa-briefcase"></i> <?php the_company_name();?></span>
				<?php } ?>
				
				<?php if (in_array("location", $job_meta)) { ?>
					<span class="ws-meta-job-location"><i class="fa fa-map-marker"></i> <?php ws_job_location( false ); ?></span>
				<?php } ?>
				
				<?php 
				$currency_position =  get_option('workscout_currency_position','before');

				$rate_min = get_post_meta( $post->ID, '_rate_min', true ); 
				if ( $rate_min && in_array("rate", $job_meta)) { 
					$rate_max = get_post_meta( $post->ID, '_rate_max', true );  ?>
					<span class="ws-meta-rate">
						<i class="fa fa-money"></i> <?php 
						if( $currency_position == 'before' ) { 
                            echo get_workscout_currency_symbol(); 
                        } 
						echo esc_html( $rate_min ); 
						if( $currency_position == 'after' ) { 
                            echo get_workscout_currency_symbol(); 
                        }
						if(!empty($rate_max)) { 
							echo '- ';
							if( $currency_position == 'before' ) { 
                                echo get_workscout_currency_symbol(); 
                            } 	
							echo $rate_max;
							if( $currency_position == 'after' ) { 
                                echo get_workscout_currency_symbol(); 
                            } 
						} ?> <?php esc_html_e('/ hour','workscout'); ?>
					</span>
				<?php } ?>

				<?php 
				$salary_min = get_post_meta( $post->ID, '_salary_min', true ); 
				$salary_max = get_post_meta( $post->ID, '_salary_max', true );
				if( in_array("salary", $job_meta) ) :
					if ( !empty($salary_min) || !empty($salary_max)  ) { ?>
						<span class="ws-meta-salary">
							<i class="fa fa-money"></i>
							<?php 
							if ( $salary_min ) { 
								if( $currency_position == 'before' ) { 
	                                echo get_workscout_currency_symbol(); 
	                            } 	
								echo esc_html( $salary_min ); 
								if( $currency_position == 'after' ) { 
	                                echo get_workscout_currency_symbol(); 
	                            }
							} 
							if($salary_max) { if ( $salary_min ) { echo ' - '; } 
								if( $currency_position == 'before' ) { 
	                                echo get_workscout_currency_symbol(); 
	                            } 
								echo $salary_max;
								if( $currency_position == 'after' ) { 
	                                echo get_workscout_currency_symbol(); 
	                            }
							} ?>
						</span>
					<?php } 
				endif; ?>
				
				<?php if (in_array("date", $job_meta)) { ?>
					<span class="ws-meta-job-date"><i class="fa fa-calendar"></i> <?php the_job_publish_date() ?></span>
				<?php } ?>
								
				<?php if (in_array("deadline", $job_meta)) { ?>
					<?php 
					if ( $deadline = get_post_meta( $post->ID, '_application_deadline', true ) ) {
						$expiring_days = apply_filters( 'job_manager_application_deadline_expiring_days', 2 );
						$expiring = ( floor( ( time() - strtotime( $deadline ) ) / ( 60 * 60 * 24 ) ) >= $expiring_days );
						$expired  = ( floor( ( time() - strtotime( $deadline ) ) / ( 60 * 60 * 24 ) ) >= 0 );?>
						<span class="ws-meta-job-deadline"><i class="fa fa-calendar-times-o"></i> 
						<?php  echo  ( $expired ? __( 'Closed', 'workscout' ) : __( 'Closes', 'workscout' ) ) .': ' . date_i18n( get_option( 'date_format' ), strtotime( $deadline ) ) ?>
						</span>
				<?php } 
				}?>				

				<?php if (in_array("expires", $job_meta)) { ?>
					<span class="ws-meta-job-expires"><i class="fa fa-calendar-check-o"></i> 
					<?php esc_html_e( 'Expires', 'workscout' ) ?>:  <?php echo date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, '_job_expires', true ) ) ) ?>
					</span>
				<?php } ?>
				
				<?php do_action( 'workscout_job_listing_meta_end' ); ?>
			</div>
			<div class="listing-desc"><?php the_excerpt(); ?>
				
			</div>
			
				
		</div>
	</a>
<div class="clearfix"></div>
</li>
<?php } ?>