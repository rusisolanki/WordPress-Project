<?php
/**
 * Popular Products Section
 * 
 * @package Blossom_eCommerce
 */

if( blossom_shop_is_woocommerce_activated() ) {
    
    $ed_popular   = get_theme_mod( 'ed_popular_section', false );
    $title        = get_theme_mod( 'popular_sec_title' );
    $content      = get_theme_mod( 'popular_sec_desc' );
    $btn_lbl      = get_theme_mod( 'popular_btn_lbl' );
    $btn_link     = get_theme_mod( 'popular_btn_link' );
    $popular_type = get_theme_mod( 'popular_type', 'views' );

    $ed_crop_all    = get_theme_mod( 'ed_crop_all', false );
    $image_size = ( $ed_crop_all ) ? 'full' : 'blossom-shop-recent';

    $args = array(
        'post_type'           => 'product',                        
        'ignore_sticky_posts' => true,
        'posts_per_page'      => 8,
    );

    if( $popular_type == 'views' ){
        $args['orderby']    = 'meta_value_num';
        $args['meta_key']   = '_blossom_shop_view_count';
    }elseif( $popular_type == 'ratings' ){
        $args['orderby']    = 'meta_value_num';
        $args['meta_key']   = '_wc_average_rating';
    }

    $qry = new WP_Query( $args );

    if( $qry->have_posts() || $title || $content ) { ?>
        <section id="popular_product_section" class="popular-prod-section">
			<div class="container">
				<?php if( $title || $content ){ ?>
	            	<div class="popular-prod-wrap">	
		                <?php
			            if( $title ) echo '<h2 class="section-title">' . esc_html( $title ) . '</h2>';
			            if( $content ) echo '<div class="section-desc">' . wpautop( wp_kses_post(  $content ) ) . '</div>'; 
		        		?>
		    		</div>
		        <?php } ?>
                <div class="popular-prod-grid">
                	<?php
                    while( $qry->have_posts() ){
                        $qry->the_post(); global $product; ?>
                        <div class="item">
                        	<?php
                                $stock = get_post_meta( get_the_ID(), '_stock_status', true );
                                
                                if( $stock == 'outofstock' ){
                                    echo '<span class="outofstock">' . esc_html__( 'Sold Out', 'blossom-ecommerce' ) . '</span>';
                                }else{
                                    woocommerce_show_product_sale_flash();    
                                }
                            ?>	                            
                            <div class="popular-prod-image">
                                <a href="<?php the_permalink(); ?>" rel="bookmark">
                                    <?php 
                                    if( has_post_thumbnail() ){
                                        the_post_thumbnail( $image_size );    
                                    }else{
                                        blossom_shop_pro_get_fallback_svg( $image_size );
                                    }
                                    ?>
                                </a>
                                <?php woocommerce_template_loop_add_to_cart(); ?>
                            </div>
                            
                            <?php                            
                            the_title( '<h3><a href="' . esc_url( get_permalink() ) . '">', '</a></h3>' ); 
                            echo wc_get_rating_html( $product->get_average_rating() );                               
                            woocommerce_template_single_price(); //price                                
                        	?>
                        </div>
                    <?php } ?>
	            </div>
                <?php if( $btn_lbl && $btn_link ) echo '<div class="button-wrap"><a href="' . esc_url( $btn_link ) . '" class="btn-readmore">' . esc_html( $btn_lbl ) . '</a></div>'; ?>
            </div>
        </section>	
    <?php }
}