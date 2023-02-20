<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * After setup theme hook
 */
function blossom_ecommerce_theme_setup(){
    /*
     * Make child theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_child_theme_textdomain('blossom-ecommerce', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'blossom_ecommerce_theme_setup', 100);


function blossom_ecommerce_customize_script(){

    $my_theme = wp_get_theme();
    $version = $my_theme['Version'];
    wp_enqueue_script('blossom-ecommerce-customize', get_stylesheet_directory_uri() . '/js/child-customize.js', array('jquery', 'customize-controls'), $version, true);

}
add_action('customize_controls_enqueue_scripts', 'blossom_ecommerce_customize_script');

/**
 * Load assets.
 */
function blossom_ecommerce_enqueue_styles(){
    $my_theme = wp_get_theme();
    $version = $my_theme['Version'];

    wp_enqueue_style( 'blossom-shop', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'blossom-ecommerce', get_stylesheet_directory_uri() . '/style.css', array('blossom-shop'), $version );
}
add_action('wp_enqueue_scripts', 'blossom_ecommerce_enqueue_styles', 10);

//Remove a function from the parent theme
function blossom_ecommerce_remove_parent_filters()
{ //Have to do it after theme setup, because child theme functions are loaded first
	remove_action('wp_enqueue_scripts', 'blossom_shop_dynamic_css', 99);
}
add_action('init', 'blossom_ecommerce_remove_parent_filters');

/**
 * Customizer Settings
 */
function blossom_ecommerce_customizer_register($wp_customize){

    $wp_customize->add_section( 'theme_info', array(
		'title'       => __( 'Demo & Documentation' , 'blossom-ecommerce' ),
		'priority'    => 6,
	) );
    
    /** Important Links */
	$wp_customize->add_setting( 'theme_info_theme',
        array(
            'default' => '',
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $theme_info = '<p>';
	$theme_info .= sprintf( __( 'Demo Link: %1$sClick here.%2$s', 'blossom-ecommerce' ),  '<a href="' . esc_url( 'https://blossomthemes.com/theme-demo/?theme=blossom-ecommerce' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</p><p>';
    $theme_info .= sprintf( __( 'Documentation Link: %1$sClick here.%2$s', 'blossom-ecommerce' ),  '<a href="' . esc_url( 'https://docs.blossomthemes.com/blossom-ecommerce/' ) . '" target="_blank">', '</a>' );
    $theme_info .= '</p>';

	$wp_customize->add_control( new Blossom_Shop_Note_Control( $wp_customize,
        'theme_info_theme', 
            array(
                'section'     => 'theme_info',
                'description' => $theme_info
            )
        )
    );

    /** Banner Options */
    $wp_customize->add_setting(
        'ed_banner_section',
        array(
            'default'           => 'static_banner',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'ed_banner_section',
            array(
                'label'       => __( 'Banner Options', 'blossom-ecommerce' ),
                'description' => __( 'Choose banner as static image/video or as a slider.', 'blossom-ecommerce' ),
                'section'     => 'header_image',
                'choices'     => array(
                    'no_banner'        => __( 'Disable Banner Section', 'blossom-ecommerce' ),
                    'static_banner'    => __( 'Static/Video CTA Banner', 'blossom-ecommerce' ),
                    'slider_banner'    => __( 'Banner as Slider', 'blossom-ecommerce' ),
                ),
                'priority' => 5 
            )            
        )
    );

    /** Popular Settings */
    $wp_customize->add_section(
        'header_layout_section',
        array(
            'title'    => __( 'Header Layout', 'blossom-ecommerce' ),
            'priority' => 50,
            'panel'    => 'layout_settings',
        )
    );

    /** Page Sidebar layout */
    $wp_customize->add_setting( 
        'header_layout', 
        array(
            'default'           => 'two',
            'sanitize_callback' => 'blossom_shop_sanitize_radio'
        ) 
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Radio_Image_Control(
            $wp_customize,
            'header_layout',
            array(
                'section'     => 'header_layout_section',
                'label'       => __( 'Header Layout', 'blossom-ecommerce' ),
                'description' => __( 'Choose the header page layout for your site.', 'blossom-ecommerce' ),
                'choices'     => array(
                    'two'    => esc_url( get_stylesheet_directory_uri() . '/images/header/two.jpg' ),
                    'three'  => esc_url( get_stylesheet_directory_uri() . '/images/header/three.jpg' ),
                )
            )
        )
    );

    /** Primary Color*/
    $wp_customize->add_setting( 
        'primary_color', 
        array(
            'default'           => '#dde9ed',
            'sanitize_callback' => 'sanitize_hex_color',
        ) 
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
            $wp_customize, 
            'primary_color', 
            array(
                'label'       => __( 'Primary Color', 'blossom-ecommerce' ),
                'description' => __( 'Primary color of the theme.', 'blossom-ecommerce' ),
                'section'     => 'colors',
                'priority'    => 5,
            )
        )
    );
    
    /** Secondary Color*/
    $wp_customize->add_setting( 
        'secondary_color', 
        array(
            'default'           => '#f25529',
            'sanitize_callback' => 'sanitize_hex_color'
        ) 
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
            $wp_customize, 
            'secondary_color', 
            array(
                'label'       => __( 'Secondary Color', 'blossom-ecommerce' ),
                'description' => __( 'Secondary color of the theme.', 'blossom-ecommerce' ),
                'section'     => 'colors',
            )
        )
    );

    /** Primary Font */
    $wp_customize->add_setting(
        'primary_font',
        array(
            'default'           => 'DM Sans',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'primary_font',
            array(
                'label'       => __( 'Primary Font', 'blossom-ecommerce' ),
                'description' => __( 'Primary font of the site.', 'blossom-ecommerce' ),
                'section'     => 'typography_settings',
                'choices'     => blossom_shop_get_all_fonts(),  
            )
        )
    );
    
    /** Secondary Font */
    $wp_customize->add_setting(
        'secondary_font',
        array(
            'default'           => 'Playfair Display',
            'sanitize_callback' => 'blossom_shop_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Blossom_Shop_Select_Control(
            $wp_customize,
            'secondary_font',
            array(
                'label'       => __( 'Secondary Font', 'blossom-ecommerce' ),
                'description' => __( 'Secondary font of the site.', 'blossom-ecommerce' ),
                'section'     => 'typography_settings',
                'choices'     => blossom_shop_get_all_fonts(),  
            )
        )
    );

    $wp_customize->add_setting(
        'ed_localgoogle_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_localgoogle_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __( 'Load Google Fonts Locally', 'blossom-ecommerce' ),
                'description'   => __( 'Enable to load google fonts from your own server instead from google\'s CDN. This solves privacy concerns with Google\'s CDN and their sometimes less-than-transparent policies.', 'blossom-ecommerce' )
            )
        )
    );   

    $wp_customize->add_setting(
        'ed_preload_local_fonts',
        array(
            'default'           => false,
            'sanitize_callback' => 'blossom_shop_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Blossom_Shop_Toggle_Control( 
            $wp_customize,
            'ed_preload_local_fonts',
            array(
                'section'       => 'typography_settings',
                'label'         => __( 'Preload Local Fonts', 'blossom-ecommerce' ),
                'description'   => __( 'Preloading Google fonts will speed up your website speed.', 'blossom-ecommerce' ),
                'active_callback' => 'blossom_shop_ed_localgoogle_fonts'
            )
        )
    );   

    ob_start(); ?>
        
        <span style="margin-bottom: 5px;display: block;"><?php esc_html_e( 'Click the button to reset the local fonts cache', 'blossom-ecommerce' ); ?></span>
        
        <input type="button" class="button button-primary blossom-shop-flush-local-fonts-button" name="blossom-shop-flush-local-fonts-button" value="<?php esc_attr_e( 'Flush Local Font Files', 'blossom-ecommerce' ); ?>" />
    <?php
    $blossom_ecommerce_flush_button = ob_get_clean();

    $wp_customize->add_setting(
        'ed_flush_local_fonts',
        array(
            'sanitize_callback' => 'wp_kses_post',
        )
    );
    
    $wp_customize->add_control(
        'ed_flush_local_fonts',
        array(
            'label'         => __( 'Flush Local Fonts Cache', 'blossom-ecommerce' ),
            'section'       => 'typography_settings',
            'description'   => $blossom_ecommerce_flush_button,
            'type'          => 'hidden',
            'active_callback' => 'blossom_shop_ed_localgoogle_fonts'
        )
    );

    if( blossom_shop_is_woocommerce_activated() ){

        /** Popular Settings */
        $wp_customize->add_section(
            'popular_products_sec',
            array(
                'title'    => __( 'Popular Section', 'blossom-ecommerce' ),
                'priority' => 50,
                'panel'    => 'frontpage_settings',
            )
        );
        
        /** Enable Popular Area */
        $wp_customize->add_setting( 
            'ed_popular_section', 
            array(
                'default'           => false,
                'sanitize_callback' => 'blossom_shop_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new Blossom_Shop_Toggle_Control( 
                $wp_customize,
                'ed_popular_section',
                array(
                    'section'     => 'popular_products_sec',
                    'label'       => __( 'Enable Popular Area', 'blossom-ecommerce' ),
                    'description' => __( 'Enable to show popular section area section in home page.', 'blossom-ecommerce' ),
                )
            )
        );

        /** Popular Section Title  */
        $wp_customize->add_setting(
            'popular_sec_title',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'popular_sec_title',
            array(
                'label'           => __( 'Section Title', 'blossom-ecommerce' ),
                'section'         => 'popular_products_sec',
                'active_callback' => 'blossom_shop_popular_sec_ac',
            )
        );

        $wp_customize->selective_refresh->add_partial('popular_sec_title', array(
            'selector' => '.popular-prod-section .popular-prod-wrap h2.section-title',
            'render_callback' => 'blossom_ecommerce_popular_sec_title',
        ));

        /** Popular Section Content  */
        $wp_customize->add_setting(
            'popular_sec_desc',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'popular_sec_desc',
            array(
                'label'           => __( 'Section Content', 'blossom-ecommerce' ),
                'section'         => 'popular_products_sec',
                'active_callback' => 'blossom_shop_popular_sec_ac',
            )
        );

        $wp_customize->selective_refresh->add_partial('popular_sec_desc', array(
            'selector' => '.popular-prod-section .popular-prod-wrap .section-desc p',
            'render_callback' => 'blossom_ecommerce_popular_sec_desc',
        ));

        $wp_customize->add_setting( 
            'popular_type', 
            array(
                'default'           => 'views',
                'sanitize_callback' => 'blossom_shop_sanitize_radio'
            ) 
        );
        
        $wp_customize->add_control(
            new Blossom_Shop_Radio_Buttonset_Control(
                $wp_customize,
                'popular_type',
                array(
                    'section'	  => 'popular_products_sec',
                    'label'       => __( 'Popular product content filter', 'blossom-ecommerce' ),
                    'description' => __( 'Filter popular products according to views or ratings.', 'blossom-ecommerce' ),
                    'choices'     => array(
                        'views'     => esc_html__( 'Views', 'blossom-ecommerce' ),
                        'ratings'   => esc_html__( 'Ratings', 'blossom-ecommerce' )
                    ),
                    'active_callback' => 'blossom_shop_popular_sec_ac',
                )
            )
        );

        /** Popular Section Button  */
        $wp_customize->add_setting(
            'popular_btn_lbl',
            array(
                'default'           => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'postMessage',
            )
        );
        
        $wp_customize->add_control(
            'popular_btn_lbl',
            array(
                'label'           => __( 'Button Label', 'blossom-ecommerce' ),
                'description'     => __( 'Popular Section View all Products button label', 'blossom-ecommerce' ),
                'section'         => 'popular_products_sec',
                'active_callback' => 'blossom_shop_popular_sec_ac',
            )
        );

        $wp_customize->selective_refresh->add_partial('popular_btn_lbl', array(
            'selector' => '.popular-prod-section .button-wrap a.btn-readmore',
            'render_callback' => 'blossom_ecommerce_popular_btn_lbl',
        ));

        /** Popular Section Button  */
        $wp_customize->add_setting(
            'popular_btn_link',
            array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
            )
        );
        
        $wp_customize->add_control(
            'popular_btn_link',
            array(
                'label'           => __( 'Button Link', 'blossom-ecommerce' ),
                'section'         => 'popular_products_sec',
                'type'            => 'text',
                'active_callback' => 'blossom_shop_popular_sec_ac',
            )
        );

        /** Banner Link */
        $wp_customize->add_setting(
            'banner_link',
            array(
                'default'           => '#',
                'sanitize_callback' => 'esc_url_raw',
            )
        );
        
        $wp_customize->add_control(
            'banner_link',
            array(
                'label'           => __( 'Banner Link', 'blossom-ecommerce' ),
                'section'         => 'header_image',
                'type'            => 'text',
                'active_callback' => 'blossom_shop_banner_ac'
            )
        );

        $wp_customize->add_setting(
            'header_layout_text',
            array(
                'default' => '',
                'sanitize_callback' => 'wp_kses_post'
            )
        );
    
        $wp_customize->add_control(
            new Blossom_Shop_Note_Control(
                $wp_customize,
                'header_layout_text',
                array(
                    'section'       => 'header_settings',
                    'description'   => sprintf(__('%1$sClick here%2$s to configure header layout settings', 'blossom-ecommerce'), '<span class="text-inner-link header_layout_text">', '</span>'),
                )
            )
        );

        $wp_customize->add_setting(
            'header_settings_text',
            array(
                'default' => '',
                'sanitize_callback' => 'wp_kses_post'
            )
        );
    
        $wp_customize->add_control(
            new Blossom_Shop_Note_Control(
                $wp_customize,
                'header_settings_text',
                array(
                    'section'       => 'header_layout_section',
                    'description'   => sprintf(__('%1$sClick here%2$s to configure header layout settings', 'blossom-ecommerce'), '<span class="text-inner-link header_settings_text">', '</span>'),
                )
            )
        );
    }
}
add_action('customize_register', 'blossom_ecommerce_customizer_register', 40);

// Partial Refresh
function blossom_ecommerce_popular_sec_title(){
    return esc_html( get_theme_mod( 'popular_sec_title' ) );
}

function blossom_ecommerce_popular_sec_desc(){
    return esc_html( get_theme_mod( 'popular_sec_desc' ) );
}

function blossom_ecommerce_popular_btn_lbl(){
    return esc_html( get_theme_mod( 'popular_btn_lbl' ) );
}

/**
 * Active Callback for popular section
 */
function blossom_shop_popular_sec_ac( $control ){
    $ed_popular     = $control->manager->get_setting( 'ed_popular_section' )->value();

    if( $ed_popular ) return true;

    return false;
}

/**
 * Function to add the post view count 
 */
function blossom_ecommerce_set_views( $post_id ) {
    if ( in_the_loop() ) {
        $post_id = get_the_ID();
      } 
    else {
        global $wp_query;
        $post_id = $wp_query->get_queried_object_id();
    }
    
    if( is_singular( 'product' ) ){
        $count_key = '_blossom_shop_view_count';
        $count = get_post_meta( $post_id, $count_key, true );
        if( $count == '' ){
            $count = 0;
            delete_post_meta( $post_id, $count_key );
            add_post_meta( $post_id, $count_key, '1' );
        }else{
            $count++;
            update_post_meta( $post_id, $count_key, $count );
        }
    }
}
add_action( 'wp','blossom_ecommerce_set_views' );

/**
 * Function to get the post view count 
 */
function blossom_ecommerce_get_views( $post_id ){
    $count_key = '_blossom_shop_view_count';
    $count = get_post_meta( $post_id, $count_key, true );
    if( $count == '' ){        
        return __( '0 View', 'blossom-ecommerce' );
    }elseif( $count <= 1 ){
        return $count. __(' View', 'blossom-ecommerce' );
    }else{
        return $count. __(' Views', 'blossom-ecommerce' );    
    }    
}

/**
 * Returns Home Sections 
*/
function blossom_shop_get_home_sections(){
    $ed_banner     = get_theme_mod( 'ed_banner_section', 'static_banner' );
    $home_sections = array( 'service', 'recent_product', 'featured', 'cat_one', 'about', 'testimonial', 'cta', 'blog', 'client' );

    $sections = array( 
        'service'     => array( 'sidebar' => 'service' ),
        'recent_product' => array( 'section' => 'recent_product' ),
        'featured'    => array( 'section' => 'featured' ),
        'popular'     => array( 'section' => 'popular' ),
        'cat_one'     => array( 'section' => 'cat_one' ),
        'about'       => array( 'sidebar' => 'about' ),
        'testimonial' => array( 'sidebar' => 'testimonial' ),
        'cta'         => array( 'sidebar' => 'cta' ),
        'blog'        => array( 'section' => 'blog' ), 
        'client'      => array( 'sidebar' => 'client' ), 
    );
    
    $enabled_section = array();
    
    if( $ed_banner == 'static_banner' || $ed_banner == 'slider_banner' || $ed_banner == 'static_nl_banner' ) array_push( $enabled_section, 'banner' );
    
    foreach( $sections as $k => $v ){
        if( array_key_exists( 'sidebar', $v ) ){
            if( is_active_sidebar( $v['sidebar'] ) ) array_push( $enabled_section, $v['sidebar'] );
        }else{
            if( get_theme_mod( 'ed_' . $v['section'] . '_section', false ) ) array_push( $enabled_section, $v['section'] );
        }
    } 
    
    return apply_filters( 'blossom_shop_home_sections', $enabled_section );
}

function  blossom_shop_header(){ 

    $ed_cart        = get_theme_mod( 'ed_shopping_cart', true ); 
    $header_layout  = get_theme_mod( 'header_layout', 'two' );
    ?>

    <header id="masthead" class="site-header header-<?php echo esc_attr( $header_layout ); ?>" itemscope itemtype="http://schema.org/WPHeader">
        <?php if( has_nav_menu( 'secondary' ) || blossom_shop_social_links( false ) ) : ?>
            <div class="header-t">
                <div class="container">
                    <?php blossom_shop_secondary_navigation(); ?>
                    <?php if( blossom_shop_social_links( false ) ) : ?>
                        <div class="right">
                            <?php blossom_shop_social_links(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div><!-- .header-t -->
        <?php endif; ?>
        <div class="header-main">
            <div class="container">
                <?php blossom_shop_site_branding(); ?>
                <?php blossom_shop_primary_nagivation(); ?>
                <div class="right">
                    <?php blossom_shop_header_search(); ?>
                    <?php blossom_shop_user_block(); ?>
                    <?php if( blossom_shop_is_woocommerce_activated() && $ed_cart ) blossom_shop_wc_cart_count(); ?>             
                </div>
            </div>
        </div><!-- .header-main -->
    </header><!-- #masthead -->
<?php }

/**
 * Footer Bottom
*/
function blossom_shop_footer_bottom(){ ?>
    <div class="footer-b">
		<div class="container">
			<div class="site-info">            
            <?php
                blossom_shop_get_footer_copyright();
                echo esc_html__( ' Blossom eCommerce | Developed By ', 'blossom-ecommerce' ); 
                echo '<a href="' . esc_url( 'https://blossomthemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Blossom Themes', 'blossom-ecommerce' ) . '</a>.';                
                printf( esc_html__( ' Powered by %s. ', 'blossom-ecommerce' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'blossom-ecommerce' ) ) .'" target="_blank">WordPress</a>' );
                if ( function_exists( 'the_privacy_policy_link' ) ) {
                    the_privacy_policy_link();
                }
            ?>               
            </div>
            <?php 
                blossom_shop_payment_method();
                blossom_shop_back_to_top(); 
            ?>
		</div>
	</div>
    <?php
}

function blossom_shop_fonts_url(){
    $fonts_url = '';
    
    $primary_font       = get_theme_mod( 'primary_font', 'DM Sans' );
    $ig_primary_font    = blossom_shop_is_google_font( $primary_font );    
    $secondary_font     = get_theme_mod( 'secondary_font', 'Playfair Display' );
    $ig_secondary_font  = blossom_shop_is_google_font( $secondary_font );    
        
    /* Translators: If there are characters in your language that are not
    * supported by respective fonts, translate this to 'off'. Do not translate
    * into your own language.
    */
    $primary    = _x( 'on', 'Primary Font: on or off', 'blossom-ecommerce' );
    $secondary  = _x( 'on', 'Secondary Font: on or off', 'blossom-ecommerce' );    
    
    if ( 'off' !== $primary || 'off' !== $secondary ) {
        
        $font_families = array();
     
        if ( 'off' !== $primary && $ig_primary_font ) {
            $primary_variant = blossom_shop_check_varient( $primary_font, 'regular', true );
            if( $primary_variant ){
                $primary_var = ':' . $primary_variant;
            }else{
                $primary_var = '';    
            }            
            $font_families[] = $primary_font . $primary_var;
        }
         
        if ( 'off' !== $secondary && $ig_secondary_font ) {
            $secondary_variant = blossom_shop_check_varient( $secondary_font, 'regular', true );
            if( $secondary_variant ){
                $secondary_var = ':' . $secondary_variant;    
            }else{
                $secondary_var = '';
            }
            $font_families[] = $secondary_font . $secondary_var;
        }
        
        $font_families = array_diff( array_unique( $font_families ), array('') );
        
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),            
        );
        
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    if( get_theme_mod( 'ed_localgoogle_fonts', false ) ) {
        $fonts_url = blossom_shop_get_webfont_url( add_query_arg( $query_args, 'https://fonts.googleapis.com/css' ) );
    }
     
    return esc_url( $fonts_url );
}

function blossom_ecommerce_dynamic_css(){
    $primary_font    = get_theme_mod( 'primary_font', 'DM Sans' );
    $primary_fonts   = blossom_shop_get_fonts( $primary_font, 'regular' );
    $secondary_font  = get_theme_mod( 'secondary_font', 'Playfair Display' );
    $secondary_fonts = blossom_shop_get_fonts( $secondary_font, 'regular' );
    $font_size       = get_theme_mod( 'font_size', 20);
    
    $site_title_font      = get_theme_mod( 'site_title_font', array( 'font-family'=>'Cormorant', 'variant'=>'regular' ) );
    $site_title_fonts     = blossom_shop_get_fonts( $site_title_font['font-family'], $site_title_font['variant'] );
    $site_title_font_size = get_theme_mod( 'site_title_font_size', 20 );
    
    $primary_color    = get_theme_mod( 'primary_color', '#dde9ed' );
    $secondary_color  = get_theme_mod( 'secondary_color', '#f25529' );
    $site_title_color = get_theme_mod( 'site_title_color', '#000000' );
    
    $rgb = blossom_shop_hex2rgb( blossom_shop_sanitize_hex_color( $primary_color ) );
    $rgb2 = blossom_shop_hex2rgb( blossom_shop_sanitize_hex_color( $secondary_color ) );
     
    $custom_css = '';
    $custom_css .= '

    :root {
        --primary-color: ' . blossom_shop_sanitize_hex_color($primary_color) . ';
		--primary-color-rgb: ' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ';
        --primary-font: ' . esc_html($primary_fonts['font']) . ';
        --secondary-font: ' . esc_html($secondary_fonts['font']) . ';
        --secondary-color: ' . blossom_shop_sanitize_hex_color($secondary_color) . ';
        --secondary-color-rgb: ' . $rgb2[0] . ', ' . $rgb2[1] . ', ' . $rgb2[2] . ';
    }
     
    .content-newsletter .blossomthemes-email-newsletter-wrapper.bg-img:after,
    .widget_blossomthemes_email_newsletter_widget .blossomthemes-email-newsletter-wrapper:after{
        ' . 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.8);' . '
    }
    
    /*Typography*/

    body {
        font-family : ' . esc_html( $primary_fonts['font'] ) . ';
        font-size   : ' . absint( $font_size ) . 'px;        
    }
    
    .header-main .site-branding .site-title, 
    .sticky-header .site-branding .site-title , 
    .header-four .header-t .site-branding .site-title, 
    .header-five .logo-holder .site-branding .site-title, .header-six .logo-holder .site-branding .site-title, 
    .header-eight .logo-holder .site-branding .site-title, 
    .header-eleven .logo-holder .site-branding .site-title {
        font-size   : ' . absint( $site_title_font_size ) . 'px;
        font-family : ' . wp_kses_post( $site_title_fonts['font'] ) . ';
        font-weight : ' . wp_kses_post( $site_title_fonts['weight'] ) . ';
        font-style  : ' . wp_kses_post( $site_title_fonts['style'] ) . ';
    }
    
    .site-title a, .header-main .site-branding .site-title a, 
    .sticky-header .site-branding .site-title a, 
    .header-four .header-t .site-branding .site-title a, 
    .header-five .logo-holder .site-branding .site-title a, 
    .header-six .logo-holder .site-branding .site-title a, 
    .header-eight .logo-holder .site-branding .site-title a, 
    .header-eleven .logo-holder .site-branding .site-title a {
        color: ' . blossom_shop_sanitize_hex_color( $site_title_color ) . ';
    }

    button, input, select, optgroup, textarea, blockquote p + span, 
    .site-banner .banner-caption .meta-wrap > span.byline a, 
    .top-service-section .rtc-itw-inner-holder .widget-title, 
    section.prod-deal-section .title-wrap .section-title, 
    section.about-section .widget .widget-title, 
    section.about-section.style-two .widget .text-holder p, 
    section.cta-section.style-three .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .widget-title, 
    .woocommerce-checkout #primary .woocommerce-checkout #order_review_heading, 
    .woocommerce-checkout #primary .woocommerce-checkout .col2-set .col-1 .woocommerce-billing-fields h3, 
    .cat-tab-section .header-wrap .section-title {
        font-family : ' . wp_kses_post( $primary_fonts['font'] ) . ';
    }

    q, blockquote, .section-title, section[class*="-section"] .widget-title, 
    .yith-wcqv-main .product .summary .product_title, .widget_bttk_author_bio .title-holder, 
    .widget_bttk_popular_post ul li .entry-header .entry-title, .widget_bttk_pro_recent_post ul li .entry-header .entry-title, 
    .blossomthemes-email-newsletter-wrapper .text-holder h3, 
    .widget_bttk_posts_category_slider_widget .carousel-title .title, 
    .additional-post .section-grid article .entry-title, 
    .site-banner .banner-caption .banner-title, 
    .site-banner .banner-caption .meta-wrap > span.byline, 
    section.about-section .widget .text-holder p, 
    section.about-section.style-two .widget .widget-title, 
    section.cta-section .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .widget-title, 
    .blog-section .section-grid .entry-title, 
    .instagram-section .profile-link, 
    section.newsletter-section .newsletter-inner-wrapper .text-holder h3, 
    .recent-prod-section.style-three .recent-prod-feature .product-title-wrap .rp-title, .recent-prod-section.style-four .recent-prod-feature .product-title-wrap .rp-title, .recent-prod-section.style-five .recent-prod-feature .product-title-wrap .rp-title, .recent-prod-section.style-six .recent-prod-feature .product-title-wrap .rp-title, 
    .popular-prod-section.style-three .popular-prod-feature .product-title-wrap .pp-title, .popular-prod-section.style-four .popular-prod-feature .product-title-wrap .pp-title, .popular-prod-section.style-five .popular-prod-feature .product-title-wrap .pp-title, .popular-prod-section.style-six .popular-prod-feature .product-title-wrap .pp-title, 
    .classic-layout .site-main article .entry-title, 
    .grid-layout .site-main article .entry-title, 
    .list-layout .site-main article .entry-title, .page .site-content > .page-header .page-title, 
    .page-template-about section.intro-about-section .widget-title, 
    .page-template-contact .site-main .widget .widget-title, 
    .error404 .site-content > .page-header .page-title, 
    .single .site-content > .page-header .entry-title, 
    .woocommerce-page .site-content > .page-header .page-title, 
    .single-product .site-main div.product div.summary .product_title, 
    .single-product .site-main .related > h2, 
    section[class*="-cat-section"].style-three .cat-feature .product-title-wrap .pp-title, 
    section[class*="-cat-section"].style-four .cat-feature .product-title-wrap .pp-title, 
    section[class*="-cat-section"].style-five .cat-feature .product-title-wrap .pp-title, 
    section[class*="-cat-section"].style-six .cat-feature .product-title-wrap .pp-title {
        font-family : ' . wp_kses_post( $secondary_fonts['font'] ) . ';
    }

    .widget_blossomthemes_stat_counter_widget .blossomthemes-sc-holder .icon-holder, 
    .widget_bttk_posts_category_slider_widget .carousel-title .cat-links a:hover, 
    .widget_bttk_posts_category_slider_widget .carousel-title .title a:hover, 
    .header-six .header-t a:hover, 
    .header-eight .header-t a:hover, .header-ten .header-t a:hover, 
    .header-six .secondary-menu ul li:hover > a, .header-six .secondary-menu ul li.current-menu-item > a, .header-six .secondary-menu ul li.current_page_item > a, .header-six .secondary-menu ul li.current-menu-ancestor > a, .header-six .secondary-menu ul li.current_page_ancestor > a, .header-eight .secondary-menu ul li:hover > a, .header-eight .secondary-menu ul li.current-menu-item > a, .header-eight .secondary-menu ul li.current_page_item > a, .header-eight .secondary-menu ul li.current-menu-ancestor > a, .header-eight .secondary-menu ul li.current_page_ancestor > a, 
    .header-nine .main-navigation ul li:hover > a, .header-nine .main-navigation ul li.current-menu-item > a, .header-nine .main-navigation ul li.current_page_item > a, .header-nine .main-navigation ul li.current-menu-ancestor > a, .header-nine .main-navigation ul li.current_page_ancestor > a, 
    .header-ten .secondary-menu ul li:hover > a, .header-ten .secondary-menu ul li.current-menu-item > a, .header-ten .secondary-menu ul li.current_page_item > a, .header-ten .secondary-menu ul li.current-menu-ancestor > a, .header-ten .secondary-menu ul li.current_page_ancestor > a, .site-banner .banner-caption .banner-title a:hover, 
    .site-banner.banner-three .banner-caption .banner-title a:hover, 
    .blog .site-banner .banner-caption:not(.centered) .banner-title a:hover,
    .entry-content a:hover,
    .entry-summary a:hover,
    .page-content a:hover,
    .comment-content a:hover,
    .widget .textwidget a:hover  {
        color: ' . blossom_shop_sanitize_hex_color( $primary_color ) . ';
    }

    button:hover,
    input[type="button"]:hover,
    input[type="reset"]:hover,
    input[type="submit"]:hover, 
    .edit-link .post-edit-link, 
    .item .recent-prod-image .product_type_external:hover,
    .item .recent-prod-image .product_type_simple:hover,
    .item .recent-prod-image .product_type_grouped:hover,
    .item .recent-prod-image .product_type_variable:hover,
    .item .popular-prod-image .product_type_external:hover,
    .item .popular-prod-image .product_type_simple:hover,
    .item .popular-prod-image .product_type_grouped:hover,
    .item .popular-prod-image .product_type_variable:hover, 
    .widget_bttk_contact_social_links .social-networks li a, 
    .widget_bttk_author_bio .readmore, 
    .widget_bttk_author_bio .author-socicons li a:hover, 
    .widget_bttk_social_links ul li a:hover, 
    .widget_bttk_image_text_widget ul li:hover .btn-readmore, 
    .widget_bttk_author_bio .readmore, 
    .widget_bttk_author_bio .author-socicons li a:hover, 
    .bttk-team-inner-holder ul.social-profile li a:hover, 
    .widget_bttk_icon_text_widget .rtc-itw-inner-holder .text-holder .btn-readmore:hover, 
    .widget_blossomtheme_featured_page_widget .text-holder .btn-readmore:hover, 
    .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta, 
    .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta + .btn-cta:hover, 
    .sticky-t-bar .sticky-bar-content, 
    .header-main .right span.count, 
    .header-main .right .cart-block .widget_shopping_cart .buttons a, 
    .header-main .right .cart-block .widget_shopping_cart .buttons a.checkout:hover, 
    .main-navigation ul ul li:hover > a, 
    .main-navigation ul ul li.current-menu-item > a, 
    .main-navigation ul ul li.current_page_item > a, 
    .main-navigation ul ul li.current-menu-ancestor > a, 
    .main-navigation ul ul li.current_page_ancestor > a, #load-posts a, 
    .posts-navigation .nav-links a, 
    .site-banner .banner-caption .blossomthemes-email-newsletter-wrapper input[type="submit"], 
    .site-banner .owl-dots .owl-dot:hover span, .site-banner .owl-dots .owl-dot.active span, 
    .featured-section .section-block:not(:first-child) .block-title a:hover, 
    .featured-section.style-three .section-block:hover .btn-readmore:hover, 
    section.prod-deal-section .button-wrap .bttn:hover, section.about-section.style-two, 
    .testimonial-section .owl-stage-outer, section.cta-section.style-one .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta + .btn-cta, section.cta-section.style-one .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta:hover, .blog-section .button-wrap .bttn:hover, 
    .popular-prod-section .button-wrap .btn-readmore:hover, 
    .single .site-main article .article-meta .social-list li a:hover, 
    .single .site-main article .entry-footer .cat-tags a:hover, 
    .woocommerce-page .widget_shopping_cart .buttons .button, 
    .woocommerce-page .widget_shopping_cart .buttons .button + .button:hover, 
    .woocommerce-page .widget_shopping_cart .buttons .button + .button:focus, 
    .woocommerce-page .widget_price_filter .ui-slider .ui-slider-range, 
    .woocommerce-page .widget_price_filter .price_slider_amount .button, 
    .tagcloud a:hover, .woocommerce-page .site-content ul.products li.product .product_type_external, .woocommerce-page .site-content ul.products li.product .product_type_simple, .woocommerce-page .site-content ul.products li.product .product_type_grouped, .woocommerce-page .site-content ul.products li.product .product_type_variable, 
    .item .recent-prod-image .product_type_external:hover, .item .recent-prod-image .product_type_simple:hover, .item .recent-prod-image .product_type_grouped:hover, .item .recent-prod-image .product_type_variable:hover, .item .popular-prod-image .product_type_external:hover, .item .popular-prod-image .product_type_simple:hover, .item .popular-prod-image .product_type_grouped:hover, .item .popular-prod-image .product_type_variable:hover, .item .cat-image .product_type_external:hover, .item .cat-image .product_type_simple:hover, .item .cat-image .product_type_grouped:hover, .item .cat-image .product_type_variable:hover, 
    section[class*="-cat-section"] .button-wrap .btn-readmore:hover, 
    .item .product-image .product_type_external:hover, .item .product-image .product_type_simple:hover, .item .product-image .product_type_grouped:hover, .item .product-image .product_type_variable:hover {
        background: ' . blossom_shop_sanitize_hex_color( $primary_color ) . ';
    }

    .item .popular-prod-image .yith-wcwl-add-button .add_to_wishlist:hover, 
    .item .recent-prod-image .yith-wcqv-button:hover,
    .item .popular-prod-image .yith-wcqv-button:hover, 
    .item .recent-prod-image .compare-button a:hover,
    .item .popular-prod-image .compare-button a:hover, 
    .error404 .error-404 .search-form .search-submit:hover, 
    .woocommerce-page .site-content ul.products li.product .yith-wcwl-add-button .add_to_wishlist:hover, 
    .woocommerce-page .site-content ul.products li.product .yith-wcqv-button:hover, 
    .woocommerce-page .site-content ul.products li.product .compare.button:hover, 
    .single-product .site-main div.product div.summary .yith-wcwl-add-button .add_to_wishlist:hover, 
    .single-product .site-main div.product div.summary a.compare:hover, 
    .item .recent-prod-image .yith-wcwl-add-button .add_to_wishlist:hover, 
    .item .popular-prod-image .yith-wcwl-add-button .add_to_wishlist:hover, 
    .item .recent-prod-image .compare-button:hover a:hover, .item .recent-prod-image .compare-button:focus-within a:hover, .item .popular-prod-image .compare-button:hover a:hover, .item .popular-prod-image .compare-button:focus-within a:hover, .item .cat-image .compare-button:hover a:hover, .item .cat-image .compare-button:focus-within a:hover, 
    .item .recent-prod-image .yith-wcwl-add-button .add_to_wishlist:hover, .item .recent-prod-image .yith-wcwl-add-button .add_to_wishlist:focus-within, .item .popular-prod-image .yith-wcwl-add-button .add_to_wishlist:hover, .item .popular-prod-image .yith-wcwl-add-button .add_to_wishlist:focus-within, .item .cat-image .yith-wcwl-add-button .add_to_wishlist:hover, .item .cat-image .yith-wcwl-add-button .add_to_wishlist:focus-within, 
    .item .recent-prod-image .yith-wcqv-button:hover, .item .recent-prod-image .yith-wcqv-button:focus-within, .item .popular-prod-image .yith-wcqv-button:hover, .item .popular-prod-image .yith-wcqv-button:focus-within, .item .cat-image .yith-wcqv-button:hover, .item .cat-image .yith-wcqv-button:focus-within, 
    .item .product-image .compare-button:hover a:hover, .item .product-image .compare-button:focus-within a:hover, 
    .item .product-image .yith-wcwl-add-button .add_to_wishlist:hover, .item .product-image .yith-wcwl-add-button .add_to_wishlist:focus-within, .item .product-image .yith-wcqv-button:hover, .item .product-image .yith-wcqv-button:focus-within {
        background-color: ' . blossom_shop_sanitize_hex_color( $primary_color ) . ';
    }

    .widget_bttk_author_bio .author-socicons li a:hover, 
    .widget_bttk_social_links ul li a, 
    .blossomthemes-email-newsletter-wrapper .img-holder, 
    .widget_bttk_author_bio .author-socicons li a, 
    .bttk-team-inner-holder ul.social-profile li a:hover, .pagination .page-numbers, 
    .author-section .author-content-wrap .social-list li a svg, 
    .site-banner .banner-caption .blossomthemes-email-newsletter-wrapper input[type="submit"], 
    .featured-section.style-three .section-block:hover .btn-readmore:hover, 
    .single .site-main article .article-meta .social-list li a, 
    .single .site-main article .entry-footer .cat-tags a, 
    .woocommerce-page .site-content .woocommerce-pagination a, .woocommerce-page .site-content .woocommerce-pagination span, 
    .single-product .site-main div.product div.summary .yith-wcwl-add-button .add_to_wishlist, 
    .single-product .site-main div.product div.summary a.compare, 
    .tagcloud a:hover {
        border-color: ' . blossom_shop_sanitize_hex_color( $primary_color ) . ';
    }

    section.about-section {
        ' . 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.35);' . '
    }

    section.client-section {
        ' . 'background: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', 0.3);' . '
    }

    blockquote::before {
        background-image: url(' . ' \'data:image/svg+xml; utf-8, <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="' . blossom_shop_hash_to_percent23( blossom_shop_sanitize_hex_color( $primary_color ) ) . '" d="M75.6,40.5a20,20,0,1,1-20.1,20,39.989,39.989,0,0,1,40-40A39.31,39.31,0,0,0,75.6,40.5Zm-30.1,20a20,20,0,0,1-40,0h0a39.989,39.989,0,0,1,40-40,39.31,39.31,0,0,0-19.9,20A19.973,19.973,0,0,1,45.5,60.5Z"></path></svg>\'' . ' );
    }

    a, .dropcap, 
    .yith-wcqv-main .product .summary .product_meta > span a:hover, 
    .woocommerce-error a,
    .woocommerce-info a,
    .woocommerce-message a, 
    .widget_calendar table tbody td a, 
    .header-main .right .cart-block .widget_shopping_cart .cart_list li a:hover, 
    .header-eleven .header-main .right > div .user-block-popup a:hover, 
    .site-banner.banner-six .banner-caption .banner-title a:hover, 
    .site-banner.banner-six .banner-caption .cat-links a:hover, 
    .page-template-contact .site-main .widget_bttk_contact_social_links ul.contact-list li svg, 
    .error404 .error-404 .error-num, 
    .single-product .site-main div.product div.summary .product_meta > span a:hover, 
    .single-product .site-main div.product #reviews #respond .comment-reply-title a:hover, 
    .woocommerce-checkout #primary .woocommerce-info a, 
    .woocommerce-checkout #primary .woocommerce-checkout .woocommerce-checkout-review-order #payment .payment_methods li label .about_paypal:hover, 
    .woocommerce-checkout #primary .woocommerce-checkout .woocommerce-checkout-review-order #payment .place-order a, 
    .woocommerce-order-received .entry-content .woocommerce-order-details .shop_table tr td a:hover, 
    .woocommerce-account .woocommerce-MyAccount-content a, 
    .woocommerce-account .lost_password a:hover, 
    .cat-tab-section .tab-btn-wrap .tab-btn:hover, .cat-tab-section .tab-btn-wrap .tab-btn.active, 
    .item h3 a:hover, .entry-title a:hover, .widget ul li a:hover, 
    .breadcrumb a:hover, .breadcrumb .current,
    .breadcrumb a:hover, .breadcrumb .current, 
    .mega-sub-menu li.mega-menu-item-type-widget li a:hover, 
    .widget_maxmegamenu #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link:hover, 
    .widget_maxmegamenu #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-toggle-on > a.mega-menu-link, 
    .widget_maxmegamenu #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-current-menu-item > a.mega-menu-link, 
    .widget_maxmegamenu #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-current-menu-ancestor > a.mega-menu-link, 
    .widget_maxmegamenu #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item.mega-current-page-ancestor > a.mega-menu-link, 
    #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link:focus, 
    .sticky-t-bar .sticky-bar-content .blossomthemes-email-newsletter-wrapper form input[type=submit]:hover, .sticky-t-bar .sticky-bar-content .blossomthemes-email-newsletter-wrapper form input[type=submit]:active, .sticky-t-bar .sticky-bar-content .blossomthemes-email-newsletter-wrapper form input[type=submit]:focus {
        color: ' . blossom_shop_sanitize_hex_color( $secondary_color ) . ';
    }

    .edit-link .post-edit-link:hover,  
    .yith-wcqv-main .product .summary table.woocommerce-grouped-product-list tbody tr td .button:hover, 
    .yith-wcqv-main .product .summary .single_add_to_cart_button:hover, 
    .widget_calendar table tbody td#today, 
    .widget_bttk_custom_categories ul li a:hover .post-count, 
    .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta:hover, 
    .widget_blossomtheme_companion_cta_widget .blossomtheme-cta-container .btn-cta + .btn-cta, 
    .header-main .right .cart-block .widget_shopping_cart .buttons a:hover, 
    .header-main .right .cart-block .widget_shopping_cart .buttons a.checkout, 
    .pagination .page-numbers.current,
    .pagination .page-numbers:not(.dots):hover, 
    #load-posts a:not(.loading):hover, #load-posts a.disabled, 
    #load-posts a .loading:hover, 
    .posts-navigation .nav-links a:hover, 
    .author-section .author-content-wrap .social-list li a:hover svg, 
    .site-banner .banner-caption .blossomthemes-email-newsletter-wrapper input[type="submit"]:hover, 
    .site-banner.banner-six .banner-caption .btn-readmore:hover, 
    .woocommerce-page .widget_shopping_cart .buttons .button:hover, 
    .woocommerce-page .widget_shopping_cart .buttons .button:focus, 
    .woocommerce-page .widget_shopping_cart .buttons .button + .button, 
    .woocommerce-page .widget_price_filter .price_slider_amount .button:hover, 
    .woocommerce-page .widget_price_filter .price_slider_amount .button:focus, 
    .single-product .site-main div.product div.summary table.woocommerce-grouped-product-list tbody tr td .button:hover, 
    .single-product .site-main div.product div.summary .single_add_to_cart_button:hover, 
    .single-product .site-main div.product .woocommerce-tabs ul.tabs li a:after, 
    .single-product .site-main div.product #reviews #respond .comment-form p.form-submit input[type="submit"]:hover, 
    .woocommerce-cart .site-main .woocommerce .woocommerce-cart-form table.shop_table tbody td.actions > .button:hover, 
    .woocommerce-cart .site-main .woocommerce .cart-collaterals .cart_totals .checkout-button, 
    .woocommerce-checkout #primary .checkout_coupon p.form-row .button:hover, 
    .woocommerce-checkout #primary .woocommerce-checkout .woocommerce-checkout-review-order #payment .payment_methods li input[type="radio"]:checked + label::before, 
    .woocommerce-checkout #primary .woocommerce-checkout .woocommerce-checkout-review-order #payment .place-order .button, 
    .woocommerce-order-received .entry-content .woocommerce-order-details .shop_table thead tr, 
    .woocommerce-wishlist #content table.wishlist_table.shop_table tbody td.product-add-to-cart .button:hover, 
    .woocommerce-account .woocommerce-MyAccount-navigation ul li a:hover, 
    .woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a, 
    .featured-section.style-one .section-block .block-content .block-title a:hover, 
    .main-navigation ul li a .menu-description, 
    .woocommerce-page .site-content ul.products li.product .product_type_external:hover,
    .woocommerce-page .site-content ul.products li.product .product_type_simple:hover,
    .woocommerce-page .site-content ul.products li.product .product_type_grouped:hover,
    .woocommerce-page .site-content ul.products li.product .product_type_variable:hover, 
    .cat-tab-section .tab-btn-wrap .tab-btn::after,
    .cat-tab-section .tab-btn-wrap .tab-btn::after, 
    #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-item > a.mega-menu-link::before, 
    #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link:hover, 
    #mega-menu-wrap-primary #mega-menu-primary > li.mega-menu-flyout ul.mega-sub-menu li.mega-menu-item a.mega-menu-link:focus
    {
        background: ' . blossom_shop_sanitize_hex_color( $secondary_color ) . ';
    }

    .woocommerce #respond input#submit:hover,
    .woocommerce a.button:hover,
    .woocommerce button.button:hover,
    .woocommerce input.button:hover, 
    .mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar, 
    .mCSB_scrollTools .mCSB_dragger:hover .mCSB_dragger_bar, 
    .mCSB_scrollTools .mCSB_dragger:active .mCSB_dragger_bar, 
    .mCSB_scrollTools .mCSB_dragger.mCSB_dragger_onDrag .mCSB_dragger_bar, 
    .woocommerce-page .site-content .woocommerce-pagination .current,
    .woocommerce-page .site-content .woocommerce-pagination a:hover,
    .woocommerce-page .site-content .woocommerce-pagination a:focus, 
    .woocommerce-cart .site-main .woocommerce .woocommerce-cart-form table.shop_table tbody td.actions .coupon .button:hover, 
    .woocommerce-wishlist #content table.wishlist_table.shop_table tbody td a.yith-wcqv-button:hover {
        background-color: ' . blossom_shop_sanitize_hex_color( $secondary_color ) . ';
    }

    .pagination .page-numbers.current,
    .pagination .page-numbers:not(.dots):hover, 
    .author-section .author-content-wrap .social-list li a:hover svg, 
    .site-banner .banner-caption .blossomthemes-email-newsletter-wrapper input[type="submit"]:hover, 
    .site-banner.banner-six .banner-caption .btn-readmore:hover, 
    .woocommerce-page .site-content .woocommerce-pagination .current,
    .woocommerce-page .site-content .woocommerce-pagination a:hover,
    .woocommerce-page .site-content .woocommerce-pagination a:focus, 
    .woocommerce-checkout #primary .woocommerce-checkout .woocommerce-checkout-review-order #payment .payment_methods li input[type="radio"]:checked + label::before {
        border-color: ' . blossom_shop_sanitize_hex_color( $secondary_color ) . ';
    }

    .main-navigation ul li a .menu-description::after {
        border-top-color: ' . blossom_shop_sanitize_hex_color( $secondary_color ) . ';
    }

    .cat-tab-section .tab-content-wrap {
        ' . 'border-top-color: rgba(' . $rgb2[0] . ', ' . $rgb2[1] . ', ' . $rgb2[2] . ', 0.2);' . '
    }

    @media screen and (max-width: 1024px) {
        .main-navigation .close:hover {
            background: ' . blossom_shop_sanitize_hex_color( $primary_color ) . ';
        }
    }';
    wp_add_inline_style( 'blossom-ecommerce', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'blossom_ecommerce_dynamic_css', 99 );