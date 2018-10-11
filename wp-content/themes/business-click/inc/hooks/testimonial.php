<?php
if( !function_exists('business_click_testimonial_arrays') ) :
	/**
     *Testimonial array creation
     *
     * @since Business Click 1.0.0
     *
     * @param  null
     * @return array
     */
	function business_click_testimonial_arrays(){
		global $business_click_customizer_all_values;
		$testimonila_number_of_word					= absint( $business_click_customizer_all_values['business-click-testimonial-excerpt-length'] );

		$testimonial_arrays	= array();
		$testimonial_page_id			= array();
		$reapeted_page	  				= array('testimonial-page-ids');
		$repeated_designation 			= array('testimonial-designation-ids');
		$testimonial_args 				= array();
		$testimonial_post_page 			= evision_customizer_get_repeated_all_value(2,$reapeted_page);
		$testimonial_post_designation 	= evision_customizer_get_repeated_all_value(2,$repeated_designation);

		if(  null != $testimonial_post_page ){
			foreach($testimonial_post_page as $testimonial_post_pages){
				if( 0 != $testimonial_post_pages['testimonial-page-ids'] ){
					$testimonial_page_id[] = $testimonial_post_pages['testimonial-page-ids'];
				}
			}
			if( !empty($testimonial_page_id) ){
				$testimonial_args = array(
					'post_type'			=> 'page',
					'post__in'			=> $testimonial_page_id,
					'orderby'			=> 'post__in',
					'order'				=> 'ASC'	

				);
			}
		}
		if( !empty( $testimonial_args ) ){
			/*Query start*/
			$testimonial_ars_query 	= new WP_Query($testimonial_args);
			if( $testimonial_ars_query->have_posts()  ) :
				$i = 1;
				while( $testimonial_ars_query->have_posts() ) :
					$testimonial_ars_query->the_post();
					$th_image = false;
		            if(has_post_thumbnail()){
	                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'thumbnail' );
	                    $th_image = $thumb['0'];
		            }

		            $testimonial_arrays[] = array(
		            	'testimonial-title' 			=> get_the_title(),
		            	'testimonial-content' 			=> has_excerpt() ? get_the_excerpt() : business_click_words_count($testimonila_number_of_word,get_the_content() ) ,
		            	'testimonial-url' 				=> esc_url(get_the_permalink()),
		            	'testimonial-image' 			=> $th_image,
		            	'testimonial-designation-ids' 	=> isset( $testimonial_post_designation[$i]['testimonial-designation-ids'] ) ?  $testimonial_post_designation[$i]['testimonial-designation-ids'] : '',
		            	
		            );

		            $i++;
				endwhile;
				wp_reset_postdata();
			endif;
		}
		return $testimonial_arrays;	
	}
endif;

if( !function_exists('testimonial_section') ) :
/**
     *Testimonial array creation
     *
     * @since Business Click 1.0.0
     *
     * @param  null
     * @return null
     */
	function testimonial_section(){
		global $business_click_customizer_all_values;

		if( ! $business_click_customizer_all_values['business-click-testimonila-enable'] ){
			return null;
		}
		$testimonial_select_post					= esc_html($business_click_customizer_all_values['business-click-testimonial-select-for-page'] );
		$tesimonial_pages_array						= business_click_testimonial_arrays($testimonial_select_post);		

		if( is_array($tesimonial_pages_array) )	
		{
			$testimonila_section_title				= esc_html($business_click_customizer_all_values['business-click-testimonial-section-title'] );
			$testimonila_background_image			= esc_url($business_click_customizer_all_values['business-click-testimonial-background-image'] );
			
			?>
			<?php if( '' != $testimonila_section_title || count( $tesimonial_pages_array ) > 0 ) { ?>			
				<section id="evt-testimonials" class="img-cover dark-background" style="background-image: url('<?php echo esc_url($testimonila_background_image);?>');">	
					<div class="evt-img-overlay">
						<div class="container">
							<h2 class="widget-title evision-animate slideInDown"><?php echo esc_html($testimonila_section_title);?></h2>	
						<?php if( count( $tesimonial_pages_array ) > 0 ) {?>
							<div class="row justify-content-center justify-content-lg-start">
								<div class="col-md-10 col-lg-8">
									<div class="evt-testimonials-slider evt-carousel slidesToShow-one evision-animate fadeIn">
										<?php 
										$i = 1;
										foreach( $tesimonial_pages_array as $tesimonial_pages_arrays ){
											
											
											if ( !empty($tesimonial_pages_arrays['testimonial-image'] )){
								              $testimonial_image = $tesimonial_pages_arrays['testimonial-image'];
								            }
								            else{
								              $testimonial_image = '';
								            } ?>
								            <?php if( !empty($tesimonial_pages_arrays['testimonial-image']) || !empty($tesimonial_pages_arrays['testimonial-content']) || !empty($tesimonial_pages_arrays['testimonial-title']) || !empty($tesimonial_pages_arrays['testimonial-designation-ids'])) { ?>
												<div class="evt-box-item-wrap">
													<div class="evt-box-item">
														
														<div class="evt-box-caption text-left">
															<p><?php echo wp_kses_post($tesimonial_pages_arrays['testimonial-content']); ?></p>
															<div class="evt-testimonials-author">
																<div class="profile-img">
																	<img src="<?php echo esc_url($testimonial_image);?>">
																</div>	
																<div class="profile-info">
																	<h3 class="profile-name"><?php echo esc_html($tesimonial_pages_arrays['testimonial-title']);?></h3>	
																	<p class="designation"><?php echo esc_html($tesimonial_pages_arrays['testimonial-designation-ids']); ?></p>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php } ?>
											<?php
											$i++;
									    } ?>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
				</section>
			<?php } ?>

		<?php }
	}
endif;
add_action('business_click_homepage','testimonial_section',70);