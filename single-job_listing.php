<?php
/**
 * The template for displaying all single jobs.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WorkScout
 */

get_header(); 
$company_type = get_post_meta($post->ID, '_company_type', TRUE);
$source = get_post_meta($post->ID, '_source', TRUE);
// modify by Jfrost
$overview_elements = Kirki::get_option( 'workscout', 'pp_job_overview',array('date_posted','expiration_date','application_deadline','location','job_title','hours','rate','salary','exp_range'));
// $overview_elements = Kirki::get_option( 'workscout', 'pp_job_overview',array('date_posted','expiration_date','application_deadline','location','job_title','hours','rate','salary'));
?>
<?php while ( have_posts() ) : the_post(); ?>
<!-- Titlebar
================================================== -->
<?php 
$header_image = get_post_meta($post->ID, 'pp_job_header_bg', TRUE); 

if(!empty($header_image)) {
		$transparent_status = get_post_meta($post->ID, 'pp_transparent_header', TRUE); 	
		if($transparent_status == 'on'){ ?>
			<div id="titlebar" class="photo-bg with-transparent-header" style="background: url('<?php echo esc_url($header_image); ?>')">
		<?php } else { ?>
			<div id="titlebar" class="photo-bg" style="background: url('<?php echo esc_url($header_image); ?>')">
		<?php } ?>
	<?php } else { ?>
		<div id="titlebar" class="single">
<?php } ?>

		<div class="container">
			<div class="eleven columns">
		
			<?php
			$terms = get_the_terms( $post->ID, 'job_listing_category' );
									
			if ( $terms && ! is_wp_error( $terms ) ) : 

				$jobcats = array();
			 	
				foreach ( $terms as $term ) {
					$term_link = get_term_link( $term );
					$jobcats[] = '<a href="'.$term_link.'">'.$term->name.'</a>';
				}
									
				$print_cats = join( " / ", $jobcats ); ?>
			 	<?php echo '<span>'.$print_cats.'</span>'; ?>
			<?php 
			endif; ?>
				<h1><?php the_title(); ?> 
				<?php if ( get_option( 'job_manager_enable_types' ) ) { 
					$types = get_the_terms( $post->ID, 'job_listing_type' );
					if ( $types && ! is_wp_error( $types ) ) : 
						foreach ( $types as $type ) { ?>
							<span class="job-type <?php echo sanitize_title( $type->slug ); ?>"><?php echo $type->name; ?></span>
					<?php }
					endif;?>
				<?php }  ?>
				<?php if(workscout_newly_posted()) { echo '<span class="new_job">'.esc_html__('NEW','workscout').'</span>'; } ?>
				</h1>
			</div>

			<div class="five columns">
			<?php do_action('workscout_bookmark_hook') ?>
				
			</div>

		</div>
	</div>

<!-- Content
================================================== -->
<?php 

$layout = Kirki::get_option( 'workscout', 'pp_job_layout' ); ?>
<div class="container <?php echo esc_attr($layout); ?>">
	<div class="sixteen columns">
		<?php do_action('job_content_start'); ?>
	</div>

<?php if(class_exists( 'WP_Job_Manager_Applications' )) : ?>			
	<?php if ( is_position_filled() ) : ?>
			<div class="sixteen columns"><div class="notification closeable notice "><?php esc_html_e( 'This position has been filled', 'workscout' ); ?></div><div class="margin-bottom-35"></div></div>	
	<?php elseif ( ! candidates_can_apply() && 'preview' !== $post->post_status ) : ?>
			<div class="sixteen columns"><div class="notification closeable notice "><?php esc_html_e( 'Applications have closed', 'workscout' ); ?></div></div>	
	<?php endif;?>
<?php endif;?>

	<!-- Recent Jobs -->
	<?php $logo_position = Kirki::get_option( 'workscout','pp_job_list_logo_position', 'left' );?>

	<div class="eleven columns ">
		<div class="padding-right">
			<?php if ( get_the_company_name() ) { ?>
				<!-- Company Info1 -->
				<div class="company-info <?php echo ($logo_position == 'left') ? 'left-company-logo' : 'right-company-logo' ;?>" >
					<?php if(class_exists('Astoundify_Job_Manager_Companies')) { echo workscout_get_company_link(the_company_name('','',false)); } ?>
						<?php ($logo_position == 'left') ? the_company_logo() : the_company_logo('medium'); ?></a>
					<?php if(class_exists('Astoundify_Job_Manager_Companies')) { echo "</a>"; } ?>
					<div class="content">
						<?php if (in_array("job_title", $overview_elements)) : ?>
						<div>
						<h4><span><strong><?php the_title(); ?></strong></span></h4>				
						</div>
						<?php endif;?>
						<div class="com-innd">
						   
						<?php if(class_exists('Astoundify_Job_Manager_Companies')) { echo workscout_get_company_link(the_company_name('','',false)); } ?>
							<?php the_company_name( '<span style="color: #000;font-size: 15px;font-weight: initial;
							">', '</span>' ); ?>
							</br>							
							<?php if(class_exists('Astoundify_Job_Manager_Companies')) { echo "</a>"; } ?>
						<?php the_company_tagline( '<span class="company-tagline">', '</span>' ); ?>
						</div>

						<?php 
						if(get_post_meta( $post->ID, 'package_plan_type', true ) == 'paid '){
						if ( $website = get_the_company_website() ) : ?>
							<span><a class="website" href="<?php echo esc_url( $website ); ?>" target="_blank" rel="nofollow"><i class="fa fa-link"></i> <?php esc_html_e( 'Website', 'workscout' ); ?></a></span>
						<?php endif; }?>
						<?php if ( get_the_company_twitter() ) : ?>
							<span><a href="http://twitter.com/<?php echo get_the_company_twitter(); ?>">
								<i class="fa fa-twitter"></i>
								@<?php echo get_the_company_twitter(); ?>
							</a></span>
						<?php endif; ?>
					
					</div>
					<div class="clearfix"></div>
				</div>
			<?php } ?>

	
			
			<div class="single_job_listing ff" >
				
				<?php if ( get_option( 'job_manager_hide_expired_content', 1 ) && 'expired' === $post->post_status ) : ?>
					<div class="job-manager-info"><?php esc_html_e( 'This listing has expired.', 'workscout' ); ?></div>
				<?php else : ?>
					<div class="job_description">
						
						<?php do_action('workscout_single_job_before_content'); ?>
						<?php the_company_video(); ?>
						<?php /*
					if( !empty($source) && $company_type== 'govt'){?>
						   <p style="font-size:16px;"><strong style="font-size:20px;">Source : </strong><?php echo $source;?>, <?php echo get_the_date( get_option( 'date_format' ), $post->ID );?></p>
					<?php } */?>
						<?php 
						if($company_type != 'govt'){?>
						<h3>Job Descriptions:</h3>
						<?php echo (do_shortcode(apply_filters( 'the_job_description', get_the_content() ))); ?>
						<?php  ?>
						<?php }?>
					</div>
					<?php
						/**
						 * single_job_listing_end hook
						 */
						do_action( 'single_job_listing_end' );
					?>

					<?php 
						$share_options = Kirki::get_option( 'workscout', 'pp_job_share' ); 
						
						if(!empty($share_options)) {
								$id = $post->ID;
							    $title = urlencode($post->post_title);
							    $url =  urlencode( get_permalink($id) );
							    $summary = urlencode(workscout_string_limit_words($post->post_excerpt,20));
							    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'medium' );
							    $imageurl = urlencode($thumb[0]);
							?>
							<ul class="share-post">
								<?php if (in_array("facebook", $share_options)) { ?><li><?php echo '<a target="_blank" class="facebook-share" href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '">Facebook</a>'; ?></li><?php } ?>
								<?php if (in_array("twitter", $share_options)) { ?><li><?php echo '<a target="_blank" class="twitter-share" href="https://twitter.com/share?url=' . $url . '&amp;text=' . esc_attr($summary ). '" title="' . __( 'Twitter', 'workscout' ) . '">Twitter</a>'; ?></li><?php } ?>
								<?php if (in_array("google-plus", $share_options)) { ?><li><?php echo '<a target="_blank" class="google-plus-share" href="https://plus.google.com/share?url=' . $url . '&amp;title="' . esc_attr($title) . '" onclick=\'javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;\'>Google Plus</a>'; ?></li><?php } ?>
								<?php if (in_array("pinterest", $share_options)) { ?><li><?php echo '<a target="_blank"  class="pinterest-share" href="http://pinterest.com/pin/create/button/?url=' . $url . '&amp;description=' . esc_attr($summary) . '&media=' . esc_attr($imageurl) . '" onclick="window.open(this.href); return false;">Pinterest</a>'; ?></li><?php } ?>
								<?php if (in_array("linkedin", $share_options)) { ?><li><?php echo '<a target="_blank"  class="linkedin-share" href="https://www.linkedin.com/cws/share?url=' . $url . '">LinkedIn</a>'; ?></li><?php } ?>

								<!-- <li><a href="#add-review" class="rate-recipe">Add Review</a></li> -->
							</ul>
						<?php } ?>
					<div class="clearfix"></div>

				<?php endif; ?>

				<?php
				$related = Kirki::get_option( 'workscout', 'pp_enable_related_jobs' ); 
				
				 if($related) { get_template_part('template-parts/jobs-related'); }?>

			</div>

		</div>
	</div>


	<!-- Widgets -->
	<div class="five columns" id="job-details">
		<?php 
if ( candidates_can_apply() ) { ?>
<?php 
		$external_apply = get_post_meta( $post->ID, '_apply_link', true ); 
		if(!empty($external_apply)) { 
		echo '
	<style>.sidbr-ss {
	position: relative;
	top: 30px;
	} </style>';
		echo '<a class="button" target="_blank" href="'.esc_url($external_apply).'" style="display:block;text-align:center;margin-bottom:10px;">'.esc_html__( 'Apply Now', 'workscout' ).'</a>';
		} else { ?>  
			<?php if ( $apply = get_the_job_application_method() ) :
				wp_enqueue_script( 'wp-job-manager-job-application' );
				?>
				<div class="job_application application">
					<?php do_action( 'job_application_start', $apply ); ?>
					
					
					<a href="#apply-dialog" class="small-dialog popup-with-zoom-anim button" style="display:block;text-align:center;margin-bottom:10px;"><?php esc_html_e( 'Easy Apply', 'workscout' ); ?></a>

					<div id="apply-dialog" class="small-dialog zoom-anim-dialog mfp-hide apply-popup">
						<div class="small-dialog-headline">
							<h2><?php esc_html_e('Apply For This Job','workscout') ?></h2>
						</div>
						<div class="small-dialog-content">
							<?php
								/**
								 * job_manager_application_details_email or job_manager_application_details_url hook
								 */
								do_action( 'job_manager_application_details_' . $apply->type, $apply );
							?>
						</div>
					</div>
						
						
					<?php do_action( 'job_application_end', $apply ); ?>
				</div>
<?php endif; ?>	
		
		<?php } } ?>
		<div class="sidbr-ss">

		<?php dynamic_sidebar( 'sidebar-job-before' ); ?>
		<!-- Sort by -->
		<div class="widget">
			<h4><?php esc_html_e('Job Overview','workscout') ?></h4>
			
			<div class="job-overview">
				<?php do_action( 'single_job_listing_meta_before' ); ?>
				<ul>
					<?php do_action( 'single_job_listing_meta_start' ); ?>
					<?php if (in_array("date_posted", $overview_elements)) : ?>
					<li>
						<i class="fa fa-calendar"></i>
						<div>
							<strong><?php esc_html_e('Date Posted','workscout'); ?>:</strong>
							<span><?php the_job_publish_date() ?></span>
						</div>
					</li>
					<?php endif; //overview elements ?>
					<?php if (in_array("expiration_date", $overview_elements)) : ?>
					<?php 
					$expired_date = get_post_meta( $post->ID, '_job_expires', true );
					$hide_expiration = get_post_meta( $post->ID, '_hide_expiration', true );
					
					if(empty($hide_expiration )) {
						if(!empty($expired_date)) { ?>
					<li>
						<i class="fa fa-calendar"></i>
						<div>
							<strong><?php esc_html_e('Expiration date','workscout'); ?>:</strong>
							<span><?php echo date_i18n( get_option( 'date_format' ), strtotime( get_post_meta( $post->ID, '_job_expires', true ) ) ) ?></span>
						</div>
					</li>
					<?php }
					} ?>
					<?php endif; //overview elements ?>

					<?php if (in_array("application_deadline", $overview_elements)) : ?>
					<?php 
					if ( $deadline = get_post_meta( $post->ID, '_application_deadline', true ) ) {
						$expiring_days = apply_filters( 'job_manager_application_deadline_expiring_days', 2 );
						$expiring = ( floor( ( time() - strtotime( $deadline ) ) / ( 60 * 60 * 24 ) ) >= $expiring_days );
						$expired  = ( floor( ( time() - strtotime( $deadline ) ) / ( 60 * 60 * 24 ) ) >= 0 );

						echo '<li class="ws-application-deadline ' . ( $expiring ? 'expiring' : '' ) . ' ' . ( $expired ? 'expired' : '' ) . '"><i class="fa fa-calendar"></i>
						<div>
							<strong>' . ( $expired ? __( 'Closed', 'workscout' ) : __( 'Closes', 'workscout' ) ) . ':</strong><span>' . date_i18n(get_option( 'date_format' ), strtotime( $deadline ) ) . '</span></div></li>';
					} ?>
					<?php endif; //overview elements ?>
				
					<?php 
					if (in_array("location", $overview_elements)) : ?>
					<li>
						<i class="fa fa-map-marker"></i>
						<div>
							<strong><?php esc_html_e('Location','workscout'); ?>:</strong>
							<span class="location" ><?php ws_job_location(); ?></span>
						</div>
					</li>
					<?php endif; //overview elements ?>
					
					
					<?php if ( get_option( 'job_manager_enable_types' ) ) {
						$types = get_the_terms( $post->ID, 'job_listing_type' );
					?>
					<li><i class="fa fa-tasks"></i>
						<div>
							<strong><?php esc_html_e('Job Type','workscout'); ?>:</strong>
							<?php if ( $types && ! is_wp_error( $types ) ) : 
								foreach ( $types as $type ) { ?>
									<span><?php echo $type->name; ?></span>
							<?php }
							endif; ?>
						</div>
					</li>
					
					<?php } //overview elements ?>
					
					
					<?php if (in_array("job_title", $overview_elements)) : ?>
					<!--li>
						<i class="fa fa-user"></i>
						<div>
							<strong><?php esc_html_e('Job Title','workscout'); ?>:</strong>
							<span><?php the_title(); ?></span>
						</div>
					</li-->
					<?php endif; //overview elements ?>
					<!-- modify by Jfrost -->
					<?php //if (in_array("exp_range", $overview_elements)) : ?>
					<?php $exp_range = get_post_meta( $post->ID, '_exp_range', true ); 
					 if ( $exp_range ) { ?>
					<li>
						<i class="fa fa-history" style="font-size: 18px;"></i>
						<div>
							<strong><?php esc_html_e('Experience Range','workscout'); ?>:</strong>
							<span>
								<?php 
									$exp_range_arr = array( 
										'any' => 'Any' , 
										'below_1' => 'Below 1 year' ,
										'1_3' => '1 - < 3 years' ,
										'3_5' => '3 - < 5 years' ,
										'5_10' => '5 - < 10 years' , 
										'over_10' => 'Over 10 years');
									echo $exp_range_arr[$exp_range];
								?>
							</span>
						</div>
					</li>
					<?php } ?>
					<?php //endif; //overview elements ?>

					<?php if (in_array("hours", $overview_elements)) : ?>
					<?php $hours = get_post_meta( $post->ID, '_hours', true ); 
					 if ( $hours ) { ?>
					<li>
						<i class="fa fa-clock-o"></i>
						<div>
							<strong><?php esc_html_e('Hours','workscout'); ?>:</strong>
							<span><?php echo esc_html( $hours ) ?><?php esc_html_e('h / week','workscout'); ?></span>
						</div>
					</li>
					<?php } ?>
					<?php endif; //overview elements ?>

					<?php
					$currency_position =  get_option('workscout_currency_position','before');

					if (in_array("rate", $overview_elements)) : ?>
					<?php $rate_min = get_post_meta( $post->ID, '_rate_min', true ); 
					 if ( $rate_min ) { 
					 	$rate_max = get_post_meta( $post->ID, '_rate_max', true );  ?>
					<li>
						<i class="fa fa-money"></i>
						<div>
							<strong><?php esc_html_e('Rate:','workscout'); ?></strong>
							<span>				
								<?php 
								if( $currency_position == 'before' ) { 
                                    echo get_workscout_currency_symbol(); 
                                }  
                                echo esc_html( $rate_min );
                                if( $currency_position == 'after' ) { 
                                    echo get_workscout_currency_symbol(); 
                                }  ?> 
								<?php 
								if(!empty($rate_max)) { 
									if(!empty($rate_min)) { echo '- '; }
									if( $currency_position == 'before' ) { 
	                                    echo get_workscout_currency_symbol(); 
	                                } 
									echo $rate_max;
									if( $currency_position == 'after' ) { 
	                                    echo get_workscout_currency_symbol(); 
	                                } 
                                } ?><?php esc_html_e(' / hour','workscout'); ?>
							</span>
						</div>
					</li>
					<?php } ?>
					<?php endif; //overview elements ?>
					
					<?php if (in_array("salary", $overview_elements)) : ?>
					<?php 
					$salary_min = get_post_meta( $post->ID, '_salary_min', true ); 
					$salary_max = get_post_meta( $post->ID, '_salary_max', true ); 
					 if ( !empty($salary_min) || !empty($salary_max)  ) { ?>
					<li>
						<i class="fa fa-money"></i>
						<div>
							<strong><?php esc_html_e('Salary:','workscout'); ?></strong>
							<span>
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
								if ( $salary_max ) { 
									if ( $salary_min ) { echo ' - '; } 
									if( $currency_position == 'before' ) { 
	                                    echo get_workscout_currency_symbol(); 
	                                }
	                                echo esc_html($salary_max); 
	                                if( $currency_position == 'after' ) { 
	                                    echo get_workscout_currency_symbol(); 
	                                }
								} ?>
							</span>
						</div>
					</li>
					<?php } ?>
					<?php endif; //overview elements ?>
					<?php do_action( 'single_job_listing_meta_end' ); ?>
				</ul>
				
					<?php 
if ( candidates_can_apply() ) { ?>
<?php 
		$external_apply = get_post_meta( $post->ID, '_apply_link', true ); 
		if(!empty($external_apply)) {
			
		} else { ?>
			
			
		<?php } ?>
	

	
	
<?php } ?>
				



			</div>

		</div>
		</div>

		<?php 
		$single_map = Kirki::get_option( 'workscout', 'pp_enable_single_jobs_map' ); 
		$lng = $post->geolocation_long;
		if($single_map && !empty($lng)) :
		?>

			<div class="widget">
				<h4><?php esc_html_e('Job Location','workscout') ?></h4>
				
				<div id="job_map" data-longitude="<?php echo esc_attr( $post->geolocation_long ); ?>" data-latitude="<?php echo esc_attr( $post->geolocation_lat ); ?>">
					
				</div>
			</div>

		<?php 
		endif;
		dynamic_sidebar( 'sidebar-job-after' ); ?>

	</div>
	<!-- Widgets / End -->


</div>
<div class="clearfix"></div>
<div class="margin-top-55"></div>

<?php endwhile; // End of the loop. ?>

<?php get_footer(); ?>
