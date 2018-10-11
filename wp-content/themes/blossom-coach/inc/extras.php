<?php
/**
 * Blossom Coach Standalone Functions.
 *
 * @package Blossom_Coach
 */

if ( ! function_exists( 'blossom_coach_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time.
 */
function blossom_coach_posted_on() {
	$ed_updated_post_date = get_theme_mod( 'ed_post_update_date', true );
    $on = __( 'on ', 'blossom-coach' );
    
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		if( $ed_updated_post_date ){
            $time_string = '<time class="entry-date published updated" datetime="%3$s" itemprop="dateModified">%4$s</time></time><time class="updated" datetime="%1$s" itemprop="datePublished">%2$s</time>';
            $on = __( 'updated on ', 'blossom-coach' );		  
		}else{
            $time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';  
		}        
	}else{
	   $time_string = '<time class="entry-date published updated" datetime="%1$s" itemprop="datePublished">%2$s</time><time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>';   
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
    
    $posted_on = sprintf( '%1$s %2$s', esc_html( $on ), '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>' );
	
	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'blossom_coach_posted_by' ) ) :
/**
 * Prints HTML with meta information for the current author.
 */
function blossom_coach_posted_by() {
	$byline = sprintf(
		/* translators: %s: post author. */
		esc_html_x( 'by %s', 'post author', 'blossom-coach' ),
		'<span class="author" itemprop="name"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" itemprop="url">' . esc_html( get_the_author() ) . '</a></span>' 
    );
	echo '<span class="byline" itemprop="author" itemscope itemtype="https://schema.org/Person">' . $byline . '</span>';
}
endif;

if( ! function_exists( 'blossom_coach_comment_count' ) ) :
/**
 * Comment Count
*/
function blossom_coach_comment_count(){
    if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="post-comment">';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'blossom-coach' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		echo '</span>';
	}    
}
endif;

if ( ! function_exists( 'blossom_coach_category' ) ) :
/**
 * Prints categories
 */
function blossom_coach_category(){
	// Hide category and tag text for pages.
	if( 'post' === get_post_type() ){
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ' ', 'blossom-coach' ) );
		if( $categories_list ){
			echo '<div class="category" itemprop="about">' . $categories_list . '</div>';
		}
	}
}
endif;

if ( ! function_exists( 'blossom_coach_tag' ) ) :
/**
 * Prints tags
 */
function blossom_coach_tag(){
	// Hide category and tag text for pages.
	if( 'post' === get_post_type() ){
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html_x( ' ', 'list item separator', 'blossom-coach' ) );
		if( $tags_list ){			
            echo '<div class="tags" itemprop="about">' . $tags_list . '</div>';
		}
	}
}
endif;

if( ! function_exists( 'blossom_coach_get_posts_list' ) ) :
/**
 * Returns Latest, Related & Popular Posts
*/
function blossom_coach_get_posts_list( $status ){
    global $post;
    
    $args = array(
        'post_type'           => 'post',
        'posts_status'        => 'publish',
        'ignore_sticky_posts' => true,
        'posts_per_page'      => 6
    );
    
    switch( $status ){
        case 'latest':        
        $title       = __( 'Latest Posts', 'blossom-coach' );
        $class       = 'latest-articles';
        $title_class = 'latest-title';
        $image_size  = 'blossom-coach-blog';
        break;
        
        case 'related':
        $args['post__not_in']   = array( $post->ID );
        $args['orderby']        = 'rand';
        $title                  = get_theme_mod( 'related_post_title', __( 'Related Post', 'blossom-coach' ) );
        $class                  = 'related-articles';
        $title_class            = 'related-title';
        $image_size             = 'blossom-coach-blog';
        $related_tax            = get_theme_mod( 'related_taxonomy', 'cat' );
        if( $related_tax == 'cat' ){
            $cats = get_the_category( $post->ID );        
            if( $cats ){
                $c = array();
                foreach( $cats as $cat ){
                    $c[] = $cat->term_id; 
                }
                $args['category__in'] = $c;
            }
        }elseif( $related_tax == 'tag' ){
            $tags = get_the_tags( $post->ID );
            if( $tags ){
                $t = array();
                foreach( $tags as $tag ){
                    $t[] = $tag->term_id;
                }
                $args['tag__in'] = $t;  
            }
        }
        break;        
    }
    
    $qry = new WP_Query( $args );
    
    if( $qry->have_posts() ){ ?>    
        <div class="<?php echo esc_attr( $class ); ?>">
    		<?php if( $title ) echo '<h3 class="' . esc_attr( $title_class ) . '"><span>' . esc_html( $title ) . '</span></h3>'; ?>
			<div class="clearfix">
            <?php while( $qry->have_posts() ){ $qry->the_post(); ?>
            <div class="article-block">
				<figure class="post-thumbnail">
                    <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                        <?php
                            if( has_post_thumbnail() ){
                                the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                            }else{ ?>
                                <img src="<?php echo esc_url( get_template_directory_uri() . '/images/' . $image_size . '.jpg' ); ?>" alt="<?php the_title_attribute(); ?>" itemprop="image" />
                            <?php 
                            }
                        ?>
                    </a>
                </figure><!-- .post-thumbnail -->
				<header class="entry-header">
					<?php
                        the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
                    ?>                        
				</header><!-- .entry-header -->
			</div>
			<?php } ?>
            </div><!-- .clearfix -->
    	</div><!-- .related-articles/latest-articles -->
        <?php
        wp_reset_postdata();
    }
}
endif;

if( ! function_exists( 'blossom_coach_primary_menu_fallback' ) ) :
/**
 * Fallback for primary menu
*/
function blossom_coach_primary_menu_fallback(){
    if( current_user_can( 'manage_options' ) ){
        echo '<ul id="primary-menu" class="menu">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Click here to add a menu', 'blossom-coach' ) . '</a></li>';
        echo '</ul>';
    }
}
endif;

if( ! function_exists( 'blossom_coach_social_links' ) ) :
/**
 * Social Links 
*/
function blossom_coach_social_links( $echo = true ){ 
    $social_links = get_theme_mod( 'social_links' );
    $ed_social    = get_theme_mod( 'ed_social_links', true ); 
    
    if( $ed_social && $social_links && $echo ){ ?>
    <ul class="social-icons">
    	<?php 
        foreach( $social_links as $link ){
    	   if( $link['link'] ){ ?>
            <li><a href="<?php echo esc_url( $link['link'] ); ?>" target="_blank" rel="nofollow"><i class="<?php echo esc_attr( $link['font'] ); ?>"></i></a></li>    	   
            <?php
            } 
        } 
        ?>
	</ul>
    <?php    
    }elseif( $ed_social && $social_links ){
        return true;
    }else{
        return false;
    }
    ?>
    <?php                                
}
endif;

if( ! function_exists( 'blossom_coach_theme_comment' ) ) :
/**
 * Callback function for Comment List *
 * 
 * @link https://codex.wordpress.org/Function_Reference/wp_list_comments 
 */
function blossom_coach_theme_comment( $comment, $args, $depth ){
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	
    <?php if ( 'div' != $args['style'] ) : ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body" itemscope itemtype="http://schema.org/UserComments">
	<?php endif; ?>
    	
        <footer class="comment-meta">
            <div class="comment-author vcard">
                <?php 
                    if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); 
                    printf( __( '<b class="fn" itemprop="creator" itemscope itemtype="http://schema.org/Person">%s <span class="says">says:</span></b>', 'blossom-coach' ), get_comment_author_link() ); 
                ?>
        	</div><!-- .comment-author vcard -->
            <div class="comment-metadata commentmetadata">
                <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
            		<time itemprop="commentTime" datetime="<?php echo esc_attr( get_gmt_from_date( get_comment_date() . get_comment_time(), 'Y-m-d H:i:s' ) ); ?>"><?php printf( esc_html__( '%1$s at %2$s', 'blossom-coach' ), get_comment_date(),  get_comment_time() ); ?></time>
                </a>
        	</div>
            <?php if( $comment->comment_approved == '0' ) : ?>
        		<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'blossom-coach' ); ?></p>
        	<?php endif; ?>
            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        	</div>
        </footer>
        
        <div class="comment-content" itemprop="commentText"><?php comment_text(); ?></div>    
        
	<?php if ( 'div' != $args['style'] ) : ?>
    </div><!-- .comment-body -->
	<?php endif; ?>
    
<?php
}
endif;

if( ! function_exists( 'blossom_coach_sidebar' ) ) :
/**
 * Return sidebar layouts for pages/posts
*/
function blossom_coach_sidebar( $class = false ){
    global $post;
    $return = false;
    $page_layout = get_theme_mod( 'page_sidebar_layout', 'right-sidebar' ); //Default Layout Style for Pages
    $post_layout = get_theme_mod( 'post_sidebar_layout', 'right-sidebar' ); //Default Layout Style for Posts
    $layout      = get_theme_mod( 'layout_style', 'right-sidebar' ); //Default Layout Style for Styling Settings
    
    if( is_singular( array( 'page', 'post' ) ) ){         
        if( get_post_meta( $post->ID, '_blossom_coach_sidebar_layout', true ) ){
            $sidebar_layout = get_post_meta( $post->ID, '_blossom_coach_sidebar_layout', true );
        }else{
            $sidebar_layout = 'default-sidebar';
        }
        
        if( is_page() ){
            if( is_active_sidebar( 'sidebar' ) ){
                if( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $page_layout == 'no-sidebar' ) ){
                    $return = $class ? 'full-width' : false;
                }elseif( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $page_layout == 'centered' ) ){
                    $return = $class ? 'full-width centered' : false;
                }elseif( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
                    $return = $class ? 'rightsidebar' : 'sidebar';
                }elseif( ( $sidebar_layout == 'default-sidebar' && $page_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
                    $return = $class ? 'leftsidebar' : 'sidebar';
                }
            }else{
                $return = $class ? 'full-width' : false;
            }
        }elseif( is_single() ){
            if( is_active_sidebar( 'sidebar' ) ){
                if( $sidebar_layout == 'no-sidebar' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'no-sidebar' ) ){
                    $return = $class ? 'full-width' : false;
                }elseif( $sidebar_layout == 'centered' || ( $sidebar_layout == 'default-sidebar' && $post_layout == 'centered' ) ){
                    $return = $class ? 'full-width centered' : false;
                }elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'right-sidebar' ) || ( $sidebar_layout == 'right-sidebar' ) ){
                    $return = $class ? 'rightsidebar' : 'sidebar';
                }elseif( ( $sidebar_layout == 'default-sidebar' && $post_layout == 'left-sidebar' ) || ( $sidebar_layout == 'left-sidebar' ) ){
                    $return = $class ? 'leftsidebar' : 'sidebar';
                }
            }else{
                $return = $class ? 'full-width' : false;
            }
        }
    }elseif( blossom_coach_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() || get_post_type() == 'product' ) ){
        if( $layout == 'no-sidebar' ){
            $return = $class ? 'full-width' : false;
        }elseif( is_active_sidebar( 'shop-sidebar' ) ){            
            if( $class ){
                if( $layout == 'right-sidebar' ) $return = 'rightsidebar'; //With Sidebar
                if( $layout == 'left-sidebar' ) $return = 'leftsidebar';
            }         
        }else{
            $return = $class ? 'full-width' : false;
        } 
    }elseif( is_404() ){
        $return = $class ? 'full-width' : false;
    }else{
        if( $layout == 'no-sidebar' ){
            $return = $class ? 'full-width' : false;
        }elseif( is_active_sidebar( 'sidebar' ) ){            
            if( $class ){
                if( $layout == 'right-sidebar' ) $return = 'rightsidebar'; //With Sidebar
                if( $layout == 'left-sidebar' ) $return = 'leftsidebar';
            }else{
                $return = 'sidebar';    
            }                         
        }else{
            $return = $class ? 'full-width' : false;
        } 
    }    
    return $return; 
}
endif;

if( ! function_exists( 'blossom_coach_get_posts' ) ) :
/**
 * Fuction to list Custom Post Type
*/
function blossom_coach_get_posts( $post_type = 'post' ){    
    $args = array(
    	'posts_per_page'   => -1,
    	'post_type'        => $post_type,
    	'post_status'      => 'publish',
    	'suppress_filters' => true 
    );
    $posts_array = get_posts( $args );
    
    // Initate an empty array
    $post_options = array();
    $post_options[''] = __( ' -- Choose -- ', 'blossom-coach' );
    if ( ! empty( $posts_array ) ) {
        foreach ( $posts_array as $posts ) {
            $post_options[ $posts->ID ] = $posts->post_title;
        }
    }
    return $post_options;
    wp_reset_postdata();
}
endif;

if( ! function_exists( 'blossom_coach_get_categories' ) ) :
/**
 * Function to list post categories in customizer options
*/
function blossom_coach_get_categories( $select = true, $taxonomy = 'category', $slug = false ){    
    /* Option list of all categories */
    $categories = array();
    
    $args = array( 
        'hide_empty' => false,
        'taxonomy'   => $taxonomy 
    );
    
    $catlists = get_terms( $args );
    if( $select ) $categories[''] = __( 'Choose Category', 'blossom-coach' );
    foreach( $catlists as $category ){
        if( $slug ){
            $categories[$category->slug] = $category->name;
        }else{
            $categories[$category->term_id] = $category->name;    
        }        
    }
    
    return $categories;
}
endif;

if( ! function_exists( 'blossom_coach_get_home_sections' ) ) :
/**
 * Returns Home Sections 
*/
function blossom_coach_get_home_sections(){
    $ed_banner = get_theme_mod( 'ed_banner_section', 'slider_banner' );
    $sections = array( 
        'client'      => array( 'sidebar' => 'client' ),
        'about'       => array( 'sidebar' => 'about' ),
        'cta'         => array( 'sidebar' => 'cta' ),
        'testimonial' => array( 'sidebar' => 'testimonial' ),
        'service'     => array( 'sidebar' => 'service' ),
        'blog'        => array( 'section' => 'blog' ),
        'simple-cta'  => array( 'sidebar' => 'simple-cta' ),
        'contact'     => array( 'sidebar' => 'contact' ), 
    );
    
    $enabled_section = array();
    
    if( $ed_banner == 'static_nl_banner' || $ed_banner == 'slider_banner' ) array_push( $enabled_section, 'banner' );
    
    foreach( $sections as $k => $v ){
        if( array_key_exists( 'sidebar', $v ) ){
            if( is_active_sidebar( $v['sidebar'] ) ) array_push( $enabled_section, $v['sidebar'] );
        }else{
            if( get_theme_mod( 'ed_' . $v['section'] . '_section', true ) ) array_push( $enabled_section, $v['section'] );
        }
    }  
    
    return apply_filters( 'blossom_coach_home_sections', $enabled_section );
}
endif;

if( ! function_exists( 'blossom_coach_escape_text_tags' ) ) :
/**
 * Remove new line tags from string
 *
 * @param $text
 * @return string
 */
function blossom_coach_escape_text_tags( $text ) {
    return (string) str_replace( array( "\r", "\n" ), '', strip_tags( $text ) );
}
endif;

/**
 * Is Blossom Theme Toolkit active or not
*/
function blossom_coach_is_bttk_activated(){
    return class_exists( 'Blossomthemes_Toolkit' ) ? true : false;
}

/**
 * Is BlossomThemes Email Newsletters active or not
*/
function blossom_coach_is_btnw_activated(){
    return class_exists( 'Blossomthemes_Email_Newsletter' ) ? true : false;        
}

/**
 * Query WooCommerce activation
 */
function blossom_coach_is_woocommerce_activated() {
	return class_exists( 'woocommerce' ) ? true : false;
}

/**
 * Check if Contact Form 7 Plugin is installed
*/
function blossom_coach_is_cf7_activated(){
    return class_exists( 'WPCF7' ) ? true : false;
}