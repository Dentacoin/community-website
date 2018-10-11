<?php

if ( !function_exists('business_click_feature_slider_array') ) :
  /**
     * Featured Slider array creation
     *
     * @since Business Click 1.0.0
     *
     * @param  $from_slider
     * @return array
     */
    function business_click_feature_slider_array()
    {
      global $business_click_customizer_all_values;
      $slider_excerpt_length      = absint($business_click_customizer_all_values['business-click-excerpt-length']);

      $feature_slideer_array[0]['business-click-feature-title']     = esc_html__('Welcome To Business Click','business-click');
      $feature_slideer_array[0]['business-click-feature-content']   =  esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit.Lorem ipsum dolor sit amet, consectetur adipiscing elit.','business-click');
      $feature_slideer_array[0]['business-click-feature-image']     = get_template_directory_uri()."/assets/img/slider.jpg";
      $feature_slideer_array[0]['business-click-feature-url']       = '';
      
      $reapeated_pages      = array('business-click-page-id');
      $feature_slider_args  = array(); 

      $feature_slider_post_page = evision_customizer_get_repeated_all_value(3,$reapeated_pages);
      if (null !=$feature_slider_post_page ){
        foreach ($feature_slider_post_page as $feature_slider_post_pages){
          if ( 0 !=  $feature_slider_post_pages['business-click-page-id']){
            $feature_slider_page_ids[] = $feature_slider_post_pages['business-click-page-id'];
          }
        }
        if (!empty($feature_slider_page_ids ) ){
          $feature_slider_args = array(
            'post_type'             => 'page',
            'post__in'              => $feature_slider_page_ids,
            'order_by'              => 'post__in',
            'order'                 => 'ASC' 
          );
        }
      }

      if( !empty( $feature_slider_args )){
          // the query
          $business_click_feature_slider_args = new WP_Query( $feature_slider_args );

          if ( $business_click_feature_slider_args->have_posts() ) :
              $i = 0;
              while ( $business_click_feature_slider_args->have_posts() ) : 
                $business_click_feature_slider_args->the_post();
                  $url ='';
                  if(has_post_thumbnail()){
                      $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'business-click-slider-banner-image' );
                      $url = $thumb['0'];
                  }
                  $feature_slideer_array[$i]['business-click-feature-title']         = get_the_title();
                  if (has_excerpt()){
                      $feature_slideer_array[$i]['business-click-feature-content']   = get_the_excerpt();
                  }
                  else{
                      $feature_slideer_array[$i]['business-click-feature-content']   = business_click_words_count( $slider_excerpt_length ,get_the_content());
                  }
                  $feature_slideer_array[$i]['business-click-feature-url']           = esc_url( get_permalink() );
                  $feature_slideer_array[$i]['business-click-feature-image']         = $url;
                  $i++;
              endwhile;
              wp_reset_postdata();
          endif;
      }
      return $feature_slideer_array;
    }
endif;

if (!function_exists('business_click_feature_slider')) :
   /**
     * Featured Slider
     *
     * @since business Click 1.0.0
     *
     * @param null
     * @return null
     *
     */
 function business_click_feature_slider()
 {

  global $business_click_customizer_all_values;
  if(  !$business_click_customizer_all_values['business-click-enbale-slider'] )
  {
    return null;
  }

  $feature_slide_arrays         = business_click_feature_slider_array();
  if ( is_array($feature_slide_arrays) )
  {
    $feature_button_text        = esc_html($business_click_customizer_all_values['business-click-slider-button-text']); ?>

    <section id="evt-banner" class="" style="opacity: 0;">
      <div class="evt-banner-slider">
        <?php
          $resolution_lg = 1600 . 'x' . 660;

          $i = 0;
          foreach ($feature_slide_arrays as $feature_slide_array)
          {
            
            if ( empty($feature_slide_array['business-click-feature-image'] ))
            {
              $feature_slider_image = '';
            }
            else
            {
              $feature_slider_image = $feature_slide_array['business-click-feature-image'];
            }

          ?>

          <div class="evt-banner-image evt-overlay position-relative" <?php if(  !empty($feature_slider_image)) {?> style="background-image: url('<?php echo esc_url($feature_slider_image); ?>');"<?php } ?> >
            <img data-src="holder.js/<?php echo $resolution_lg;?>" style="opacity: 0;" class="holder">

            <div class="container">
              <div class="evt-banner-caption">
                <?php if(  !empty($feature_slide_array['business-click-feature-title']) ) { ?>
                  <h2 class="evt-title text-white mb-4"><?php echo esc_html($feature_slide_array['business-click-feature-title']);?></h2>
                <?php } ?>
                <?php if(  !empty($feature_slide_array['business-click-feature-content']) ) {?>
                  <p><?php echo wp_kses_post($feature_slide_array['business-click-feature-content']);?></p>
                <?php } ?>  
                <?php if(!empty($feature_button_text) ) { ?>
                  <a href="<?php echo esc_url($feature_slide_array['business-click-feature-url']);?>" class="btn"><?php echo esc_html($feature_button_text);?><i class="fas fa-angle-right"></i></a>
                <?php } ?>  
              </div>
            </div>
          </div>
          <?php
          $i++;
        }
        ?>
      </div>
    </section>

  <?php
  }
 }
endif;
add_action('business_click_homepage','business_click_feature_slider',10);
