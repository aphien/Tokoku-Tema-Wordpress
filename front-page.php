<?php
/**
 * The front page template file
 *
 * @package TokoKu
 */

get_header(); ?>

<main id="main-content" class="site-main">
    
    <!-- ====================================================
         HERO BANNER SLIDER
         ==================================================== -->
    <section class="hero-slider-section">
        <div class="slider-container" id="home-slider">
            <div class="slider-wrapper">
                <?php
                $has_slides = false;
                for ( $i = 1; $i <= 3; $i++ ) {
                    $img  = get_theme_mod( "tokoku_slide_image_{$i}" );
                    $link = get_theme_mod( "tokoku_slide_link_{$i}" );
                    
                    if ( $img ) {
                        $has_slides = true;
                        echo '<div class="slide">';
                        if ( $link ) echo '<a href="' . esc_url( $link ) . '">';
                        echo '<img src="' . esc_url( $img ) . '" alt="Banner ' . $i . '">';
                        if ( $link ) echo '</a>';
                        echo '</div>';
                    }
                }
                
                if ( ! $has_slides ) {
                    echo '<div class="slide slide--placeholder">
                        <div class="slide-placeholder-inner">
                            <h2>Selamat Datang di ' . get_bloginfo( 'name' ) . '</h2>
                            <p>Atur banner di Tampilan &rarr; Kustomisasi &rarr; Banner Slider</p>
                        </div>
                    </div>';
                }
                ?>
            </div>
            
            <button class="slider-btn slider-prev" aria-label="Previous">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button class="slider-btn slider-next" aria-label="Next">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>
            <div class="slider-dots"></div>
        </div>
    </section>

    <!-- ====================================================
         CATEGORY GRID
         ==================================================== -->
    <section id="categories" class="categories-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Kategori Produk</h2>
            </div>
            <div class="category-grid">
                <?php
                $categories = get_terms( array(
                    'taxonomy'   => 'kategori_produk',
                    'hide_empty' => false,
                    'number'     => 6,
                ) );

                if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :
                    foreach ( $categories as $cat ) :
                ?>
                    <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="category-item">
                        <div class="category-icon">
                            <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        </div>
                        <span class="category-name"><?php echo esc_html( $cat->name ); ?></span>
                        <span class="category-count"><?php echo $cat->count; ?> Produk</span>
                    </a>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- ====================================================
         FEATURED PRODUCTS
         ==================================================== -->
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Produk Terbaru</h2>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'produk' ) ); ?>" class="view-all-link">
                    Lihat Semua
                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </div>

            <div class="product-grid">
                <?php
                $latest_products = new WP_Query( array(
                    'post_type'      => 'produk',
                    'posts_per_page' => 8,
                ) );

                if ( $latest_products->have_posts() ) :
                    while ( $latest_products->have_posts() ) : $latest_products->the_post();
                        get_template_part( 'template-parts/product-card' );
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<div class="empty-products">
                        <svg viewBox="0 0 24 24" width="60" height="60" stroke="#ccc" stroke-width="1" fill="none"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        <p>Belum ada produk. <a href="' . admin_url('post-new.php?post_type=produk') . '">Tambah produk</a></p>
                    </div>';
                endif;
                ?>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
