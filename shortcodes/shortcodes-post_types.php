<?php


if ( ! function_exists( 'shaplatools_image_gallery' ) ) :
/**
 * Output Slider
 *
 * @since 1.0
 */
function shaplatools_image_gallery() {

	$images_size 	= get_post_meta( get_the_ID(), '_shaplatools_available_image_size', true );
	$images_ids 	= get_post_meta( get_the_ID(), '_shaplatools_image_gallery', true );
	$images_ids 	= explode(',', $images_ids );
	$images_ids 	= array_filter( $images_ids );

	ob_start();
	if( $images_ids ) : ?>

		<ul id="shapla-slider-<?php echo get_the_ID(); ?>" class="shapla-slider">
			<?php
				foreach ($images_ids as $images_id) {
					if(!$images_id) continue;
					$src = wp_get_attachment_image_src( $images_id, $images_size );
					echo "<li><img src='{$src[0]}' width='{$src[1]}' height='{$src[2]}'></li>";
				}
			?>
		</ul>
	    <script type="text/javascript">
			jQuery(document).ready(function($) {
	  			if( $().responsiveSlides ){
			        $("#shapla-slider-<?php echo get_the_ID(); ?>").responsiveSlides({
			            auto: true,
			            timeout: 4000,
			            nav: true,
			            speed: 500,
			            maxwidth: 1170,
			            pager: true,
			            namespace: "shapla-slides"
			        });
			    }
			});
	    </script><?php

	elseif( has_post_thumbnail() ):

		the_post_thumbnail('full');

	endif;

	return ob_get_clean();

}
endif;

if ( ! function_exists( 'shapla_thumbnail_gallery' ) ) :
/**
 * Output Thumbnail Gallery
 *
 * @since 1.0
 */
function shapla_thumbnail_gallery() {

	if( function_exists( 'shaplatools_image_gallery' ) ) {
		
		echo shaplatools_image_gallery();

	}
}
endif;


if ( ! function_exists( 'shapla_filterable_portfolio' ) ) :

function shapla_filterable_portfolio( $atts, $content = null ){

	extract(shortcode_atts(array(
        'thumbnail' =>'2',
        'thumbnail_size' =>'large'
    ), $atts));

	ob_start();
	?>
	<!--#container -->
	<div id="shaplatools-portfolio">

		<div id="filter" class="filter">
			<?php
	            $terms = get_terms("skill");    //To get custom taxonomy catagory name
	            $count = count($terms);
	            echo '<ul>';
	                if ( $count > 0 ){
	                    echo '<li><a class="active" href="#" data-group="all">'.__('All','shapla').'</a></li>';
	                    foreach ( $terms as $term ) {
	                        $termname = strtolower($term->name);
	                        $termname = str_replace(' ', '-', $termname);
	                        echo '<li><a href="#" data-group="'.$termname.'">'.$term->name.'</a></li>';
	                    }
	                }
	            echo "</ul>";
	        ?>
		</div>

		<div id="grid" class="row grid">
		    <?php

		    	$args = array(
		    		'post_type' => 'portfolio',
		    		'posts_per_page' => -1
		    	);

		    	$loop = new WP_Query( $args );
            ?>
            <?php 
            	if ( $loop->have_posts() ) :
                while ( $loop->have_posts() ) : $loop->the_post();
            ?>
                     
            <?php
                $terms = get_the_terms( get_the_ID(), 'skill' );   //To get custom taxonomy catagory name
                                     
                if ( $terms && ! is_wp_error( $terms ) ) :
                    $links = array();
 
                    foreach ( $terms as $term ) {
                        $links[] = $term->name;
                    }
                    
                    $links = str_replace(' ', '-', $links);
                    $tax = join( " ", $links );

                    $tax = strtolower($tax);
                    $tax = json_encode(explode(' ', $tax));
                else :
                    $tax = '';
                endif;
            ?>
			<div id="portfolio-<?php the_ID(); ?>" class="item portfolio_col_<?php echo $thumbnail; ?>" data-groups='<?php echo $tax; ?>'>
				<div class="single-portfolio-item">
					<div class="portfolio-f-image">
						<?php the_post_thumbnail( $thumbnail_size ); ?>
						<div class="portfolio-hover">
				        	<a href="#" class="portfolio-title-link"><?php the_title(); ?></a>
				            <a href="<?php the_permalink(); ?>" class="view-details-link">See details</a>
						</div>
					</div>
				</div>
			</div>
                         
            <?php endwhile; else: ?>
                     
                <?php _e( 'It looks like nothing was found at this location.', 'shapla' ); ?>
                         
            <?php endif;wp_reset_query(); ?>
		</div>

	</div>
	<!-- /#container -->
	<?php
	return ob_get_clean();
}
endif;
add_shortcode( 'shapla_portfolio', 'shapla_filterable_portfolio' );



/**add the shortcode for the slider- for use in editor**/
if( ! function_exists('shapla_image_slider' ) ) :

function shapla_image_slider( $atts, $content=null ){

    extract(shortcode_atts(array(
        'id' => ''
    ), $atts));


	$img_size    	= esc_attr( get_post_meta( $id, '_shapla_slide_img_size', true ) );
	$theme    		= esc_attr( get_post_meta( $id, '_shapla_slide_theme', true ) );
	$transition    	= esc_attr( get_post_meta( $id, '_shapla_slide_transition', true ) );
	$slices    		= esc_attr( get_post_meta( $id, '_shapla_slide_slices', true ) );

	$boxcols    	= esc_attr( get_post_meta( $id, '_shapla_slide_boxcols', true ) );
	$boxrows    	= esc_attr( get_post_meta( $id, '_shapla_slide_boxrows', true ) );
	$anim_speed    	= esc_attr( get_post_meta( $id, '_shapla_slide_animation_speed', true ) );
	$pause_time    	= esc_attr( get_post_meta( $id, '_shapla_slide_pause_time', true ) );
	$start    		= esc_attr( get_post_meta( $id, '_shapla_slide_start', true ) );

	$thumb_nav    	= ( get_post_meta( $id, '_shapla_slide_thumb_nav', true ) == 'on' ) ? 'true' : 'false';
	$dir_nav    	= ( get_post_meta( $id, '_shapla_slide_dir_nav', true ) == 'on') ? 'true' : 'false';
	$ctrl_nav    	= ( get_post_meta( $id, '_shapla_slide_ctrl_nav', true ) == 'on') ? 'true' : 'false';
	$hover_pause    = ( get_post_meta( $id, '_shapla_slide_hover_pause', true ) == 'on') ? 'true' : 'false';
	$transition_man = ( get_post_meta( $id, '_shapla_slide_transition_man', true ) == 'on') ? 'true' : 'false';
	$start_rand    	= ( get_post_meta( $id, '_shapla_slide_start_rand', true ) == 'on') ? 'true' : 'false';

	$image_ids   	= explode(',', get_post_meta( $id, '_shapla_image_ids', true) );

	if( $image_ids[0] !== "" ) :
	
		$slider  = '<section id="slide">';
		$slider .= '<div class="slider-wrapper theme-'.$theme.'">';
		$slider .= '<div id="shapla-slide-'.$id.'" class="nivoSlider">';

			foreach ( $image_ids as $image ) {
				
				if(!$image) continue;
				$src = wp_get_attachment_image_src( $image, $img_size );
				$thumb = wp_get_attachment_image_src( $image, array(50, 50) );
				$caption = get_post( $image )->post_excerpt ? get_post( $image )->post_excerpt : '';
				$description = get_post( $image )->post_content ? get_post( $image )->post_content : '';
				
				if (!filter_var($description, FILTER_VALIDATE_URL) === false) {
					$slider .='<a href="'.$description.'"><img src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" data-thumb="'.$thumb[0].'" alt="" title="'.$caption.'"></a>';
				} else {
					$slider .= '<img src="'.$src[0].'" width="'.$src[1].'" height="'.$src[2].'" data-thumb="'.$thumb[0].'" alt="" title="'.$caption.'">';
				}
			}

		$slider .= '</div></div>';	
		$slider .= '<script>
						jQuery(window).load(function($){
							jQuery("#shapla-slide-'.$id.'").nivoSlider({
								effect: "'.$transition.'",
								slices: '.$slices.',
								boxCols: '.$boxcols.',
								boxRows: '.$boxrows.',
								animSpeed: '.$anim_speed.',
								pauseTime: '.$pause_time.',
								startSlide: '.$start.',
								directionNav: '.$dir_nav.',
								controlNav: '.$ctrl_nav.',
								controlNavThumbs: '.$thumb_nav.',
								pauseOnHover: '.$hover_pause.',
								manualAdvance: '.$transition_man.',
								randomStart: '.$start_rand.',
							});
						});
					</script>';
		$slider .= '</section>';
		return $slider;

	endif;

}
endif;
add_shortcode('shapla_slide', 'shapla_image_slider');

if( ! function_exists('shapla_testimonials' ) ) :

function shapla_testimonials($posts_per_page = -1, $orderby = 'none'){
	
	ob_start();

	$args = array(
		'posts_per_page' => (int) $posts_per_page,
		'post_type' => 'testimonial',
		'orderby' => $orderby,
		'no_found_rows' => true
	);

	$query = new WP_Query( $args  );

	if ( $query->have_posts() ):
		while ( $query->have_posts() ) : $query->the_post();

		$client_name 	= get_post_meta( get_the_ID(), '_shapla_testimonial_client', true );
		$client_source 	= get_post_meta( get_the_ID(), '_shapla_testimonial_source', true );
		$client_link 	= get_post_meta( get_the_ID(), '_shapla_testimonial_url', true );

		?>
			<!-- SINGLE FEEDBACK -->
			<div class="shapla-testimonial">
				<div class="client-pic">
                    <?php
                        if ( has_post_thumbnail() ) {
                            the_post_thumbnail( array(64,64));
                        }
                    ?>
				</div>
				<div class="box">
					<p class="message">
						<?php echo get_the_content(); ?>
					</p>
				</div>
				<div class="client-info">
					<div class="client-name colored-text strong">
						<?php echo (!empty($client_name)) ? $client_name : ''; ?>
					</div>
					<div class="company">
						<a href="<?php echo (!empty($client_link)) ? $client_link : '#'; ?>" target="_blank">
							<?php echo (!empty($client_source)) ? $client_source : ''; ?>
						</a>
					</div>
				</div>
			</div>
			<!-- SINGLE FEEDBACK -->
		<?php
		endwhile;
	endif;wp_reset_query();

	return ob_get_clean();
	
}

endif;

if( ! function_exists('shapla_testimonials_slide' ) ) :

function shapla_testimonials_slide($id = null, $posts_per_page = -1, $items_desktop = 4, $items_tablet = 3, $items_tablet_small = 2, $items_mobile = 1, $orderby = 'none'){
	ob_start();
	$id = rand(0, 99);
	?>
	<div class="row">
	    <div id="testimonials-<?php echo $id; ?>" class="owl-carousel">
	    	<?php echo shapla_testimonials($posts_per_page, $orderby); ?>
	    </div>
	</div>
    <script type="text/javascript">
		jQuery(document).ready(function($) {
  			$('#testimonials-<?php echo $id; ?>').owlCarousel({
				items : <?php echo $items_desktop; ?>,
				nav : true,
				dots: false,
				loop : true,
				autoplay: true,
				autoplayHoverPause: true,
				responsiveClass:true,
			    responsive:{
			        320:{ items:<?php echo $items_mobile; ?> }, // Mobile portrait
			        600:{ items:<?php echo $items_tablet_small; ?> }, // Small tablet portrait
			        768:{ items:<?php echo $items_tablet; ?> }, // Tablet portrait
			        979:{ items:<?php echo $items_desktop; ?> }  // Desktop
			    }
			});
		});
    </script>
	<?php
	$feedback = ob_get_clean();
	return $feedback;
}

endif;

if( ! function_exists('shapla_testimonials_slide_shortcode' ) ) :
function shapla_testimonials_slide_shortcode( $atts, $content = null ){
	extract(shortcode_atts(array(
                        'id' => rand(0, 99),
                        'posts_per_page' => -1,
                        'items_desktop' => 4,
                        'items_tablet' => 3,
                        'items_tablet_small' => 2,
                        'items_mobile' => 1
                ), $atts));

	return shapla_testimonials_slide($id, $posts_per_page, $items_desktop, $items_tablet, $items_tablet_small, $items_mobile );
}
add_shortcode( 'shapla_testimonials', 'shapla_testimonials_slide_shortcode' );
endif;

if( ! function_exists('shapla_teams' ) ) :

function shapla_teams(){
	
	ob_start();

	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'team',
		'orderby' => 'none',
		'no_found_rows' => true
	);

	$query = new WP_Query( $args  );

	if ( $query->have_posts() ):
		while ( $query->have_posts() ) : $query->the_post();


		$designation = get_post_meta( get_the_ID(), '_shapla_team_designation', true );
		$description = get_post_meta( get_the_ID(), '_shapla_team_description', true );

		$designation = ( empty( $designation ) ) ? '' : $designation;
		$description = ( empty( $description ) ) ? '' : $description;

		?>
			<!-- SINGLE TEAM -->
			<div class="shapla-team">
				<div class="box">
					<div class="team-pic">
	                    <?php
	                        if ( has_post_thumbnail() ) {
	                            the_post_thumbnail( 'thumbnail' );
	                        }
	                    ?>
					</div>
					<div class="team-info">
						<div class="team-name">
							<?php the_title(); ?>
						</div>
						<div class="company">
							<?php echo $designation; ?>
						</div>
					</div>
					<p class="message">
						<?php echo $description; ?>
					</p>
				</div>
			</div>
			<!-- SINGLE TEAM -->
		<?php
		endwhile;
	endif;wp_reset_query();

	$team = ob_get_clean();
	return $team;
}

endif;

if( ! function_exists('shapla_teams_slide' ) ) :

function shapla_teams_slide( $atts, $content = null ){
	extract(shortcode_atts(array(
        'id' 					=> rand(0, 99),
        'items_desktop' 		=> 4,
        'items_tablet' 			=> 3,
        'items_tablet_small' 	=> 2,
        'items_mobile' 			=> 1
    ), $atts));

	ob_start();
	?>
	<div class="row">
	    <div id="teams-<?php echo $id; ?>" class="owl-carousel">
	    	<?php echo shapla_teams(); ?>
	    </div>
	</div>
    <script type="text/javascript">
		jQuery(document).ready(function($) {
  			$('#teams-<?php echo $id; ?>').owlCarousel({
				items : <?php echo $items_desktop; ?>,
				nav : true,
				dots: false,
				loop : true,
				autoplay: true,
				autoplayHoverPause: true,
				responsiveClass:true,
			    responsive:{
			        320:{ items:<?php echo $items_mobile; ?> }, // Mobile portrait
			        600:{ items:<?php echo $items_tablet_small; ?> }, // Small tablet portrait
			        768:{ items:<?php echo $items_tablet; ?> }, // Tablet portrait
			        979:{ items:<?php echo $items_desktop; ?> }  // Desktop
			    }
			});
		});
    </script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'shapla_teams', 'shapla_teams_slide' );

endif;


if (!function_exists('shapla_feature')):

function shapla_feature( $atts, $content = null ){

	extract(shortcode_atts(array(
        'thumbnail' =>'3'
    ), $atts));

	ob_start();

	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'feature',
		'orderby' => 'none',
		'no_found_rows' => true
	);

	$query = new WP_Query( $args  );

	if ( $query->have_posts() ):
		?><div class="shapla-features"><?php
		while ( $query->have_posts() ) : $query->the_post();


		$fa_icon = get_post_meta( get_the_ID(), '_shapla_feature_fa_icon', true );
		$fa_icon = ( empty( $fa_icon ) ) ? '' : $fa_icon;

		?>
			<!-- SINGLE FEATURE -->
			<div class="feature item portfolio_col_<?php echo $thumbnail; ?>">
					<div class="icon">
	                    <?php
	                        if ( has_post_thumbnail() ) {
	                            the_post_thumbnail( 'thumbnail' );
	                        } else {
	                        	echo '<i class="'.$fa_icon.'"></i>';
	                        }
	                    ?>
					</div>
					<h4 class="feature_title"><?php the_title(); ?></h4>
					<?php the_content(); ?>
			</div>
			<!-- SINGLE FEATURE -->
		<?php
		endwhile;
		?></div><?php
	endif;wp_reset_query();

	return ob_get_clean();

}
add_shortcode( 'shapla_feature', 'shapla_feature' );
endif;