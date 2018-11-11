<!-- </div> -->
<footer class="site-footer">
    <div class="container">






    <nav class="nav-social">
      <?php
      //$args = array(
          //'theme_location' => 'nav_social',
          //'depth' => 1,
          //'container' => ''
      //);

      //wp_nav_menu($args);

      ?>

      <!-- //ok post_per_page is the number of posts you want to query, keep it -1 when you want to call all the data, because the default is 5
      //post_type is the slug of the post type you want to call, -->

      <ul>
      <?php
      $args = array(
          'post_type' => 'custom-gallery',
          'posts_per_page' => -1,
          'orderby' => 'none',
          'order' => 'DESC'
      );
      $the_query = new WP_Query($args);
      if($the_query->have_posts()):
            while($the_query->have_posts()):$the_query->the_post();
                $post_data = get_post_meta($post->ID);
                ?>
                <li><a href="<?php echo $post_data['wpcf-social-link-url'][0]; ?>" target="_blank">
                  <img src="<?php echo $post_data['wpcf-gallery-image'][0]; ?>" class="social-media-icons" width="38.5"/>
                </a></li>
                <?php
            endwhile;
        endif;

      ?>
    </ul>

    </nav>









    <nav class="nav-secondary">
      <?php

      $args = array(
          'theme_location' => 'nav_secondary',
          'depth' => 1,
          'container' => ''
      );

      wp_nav_menu($args);?>
    </nav>
  </div>
</footer>




<script>

  jQuery(document).ready(function() {
 		jQuery('html').addClass('js');

		 var navToggle = ['<div id="toggle-nav">Menu</div>'].join("");
		 jQuery(".site-header").prepend(navToggle)
  });

  jQuery(function() {
	  var pull 		= jQuery('#toggle-nav');
		  menu 		= jQuery('.site-nav');
		  menuHeight	= menu.height();

	  jQuery(pull).on('click', function(e) {
		  e.preventDefault();
		  menu.slideToggle();
	  });

	  jQuery(window).resize(function(){
		  var w = jQuery(window).width();
		  if(w > 710 && menu.is(':hidden')) {
			  menu.removeAttr('style');
		  }
	  });
  });

</script>






<?php wp_footer();?>
</body>
</html>
