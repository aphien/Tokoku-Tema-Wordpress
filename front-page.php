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
                for ( $i = 1; $i <= 10; $i++ ) {
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
                            <h2>' . sprintf( esc_html__( 'Selamat Datang di %s', 'tokoku' ), esc_html( get_bloginfo( 'name' ) ) ) . '</h2>
                            <p>' . esc_html__( 'Atur banner di Tampilan &rarr; Kustomisasi &rarr; Banner Slider', 'tokoku' ) . '</p>
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
            <div class="section-header product-section-header">
                <h2 class="section-title">Produk Terbaru</h2>
                
                <!-- Mobile & Tablet Category Filter -->
                <div class="product-category-filter">
                    <div class="category-scroll-wrapper">
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'produk' ) ); ?>" class="cat-filter-item active"><?php _e( 'Semua', 'tokoku' ); ?></a>
                        <?php
                        $filter_cats = get_terms( array(
                            'taxonomy'   => 'kategori_produk',
                            'hide_empty' => true,
                        ) );
                        if ( ! empty( $filter_cats ) && ! is_wp_error( $filter_cats ) ) :
                            foreach ( $filter_cats as $fcat ) :
                        ?>
                            <a href="<?php echo esc_url( get_term_link( $fcat ) ); ?>" class="cat-filter-item"><?php echo esc_html( $fcat->name ); ?></a>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <div class="product-grid">
                <?php
                $latest_products = new WP_Query( array(
                    'post_type'      => 'produk',
                    'posts_per_page' => 20,
                ) );

                if ( $latest_products->have_posts() ) :
                    while ( $latest_products->have_posts() ) : $latest_products->the_post();
                        get_template_part( 'template-parts/product-card' );
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<div class="empty-products">
                        <svg viewBox="0 0 24 24" width="60" height="60" stroke="#ccc" stroke-width="1" fill="none"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                        <p>' . sprintf( wp_kses_post( __( 'Belum ada produk. <a href="%s">Tambah produk</a>', 'tokoku' ) ), esc_url( admin_url('post-new.php?post_type=produk') ) ) . '</p>
                    </div>';
                endif;
                ?>
            </div>

            <div class="section-footer">
                <a href="<?php echo esc_url( get_post_type_archive_link( 'produk' ) ); ?>" class="btn-view-all">
                    Lihat Semua Produk
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ====================================================
         CLIENT LOGOS (MARQUEE)
         ==================================================== -->
    <section class="logos-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php _e( 'Partner & Klien Kami', 'tokoku' ); ?></h2>
            </div>
            
            <div class="logo-carousel-wrapper">
                <div class="logo-track">
                    <?php
                    $logos_found = false;
                    for ( $i = 1; $i <= 50; $i++ ) {
                        $logo = get_theme_mod( "tokoku_client_logo_{$i}" );
                        if ( $logo ) {
                            $logos_found = true;
                            echo '<div class="logo-slide"><img src="' . esc_url( $logo ) . '" alt="Client Logo ' . $i . '"></div>';
                        }
                    }
                    
                    // Duplicate for seamless loop if logos exist
                    if ( $logos_found ) {
                        for ( $i = 1; $i <= 50; $i++ ) {
                            $logo = get_theme_mod( "tokoku_client_logo_{$i}" );
                            if ( $logo ) {
                                echo '<div class="logo-slide"><img src="' . esc_url( $logo ) . '" alt="Client Logo ' . $i . '"></div>';
                            }
                        }
                    } else {
                        echo '<div class="logo-slide-placeholder">' . esc_html__( 'Tambahkan logo partner di admin panel.', 'tokoku' ) . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ====================================================
         TESTIMONIALS SLIDER
         ==================================================== -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Ulasan Klien</h2>
            </div>
            
            <div class="testimonials-slider" id="testimonials-slider">
                <div class="testimonials-wrapper">
                    <?php
                    $testis_found = false;
                    for ( $i = 1; $i <= 20; $i++ ) {
                        $name = get_theme_mod( "tokoku_testi_name_{$i}" );
                        $text = get_theme_mod( "tokoku_testi_text_{$i}" );
                        $img  = get_theme_mod( "tokoku_testi_img_{$i}" );
                        
                        if ( $name || $text ) {
                            $testis_found = true;
                            ?>
                            <div class="testimonial-slide">
                                <div class="testimonial-card">
                                    <div class="testimonial-quote">
                                        <svg viewBox="0 0 24 24" width="40" height="40" fill="currentColor" opacity="0.1"><path d="M14.017 21L14.017 18C14.017 16.899 14.899 16.017 16 16.017L19 16.017C19.552 16.017 20 15.569 20 15.017L20 9.017C20 8.465 19.552 8.017 19 8.017L15 8.017C14.448 8.017 14 7.569 14 7.017L14 4.017C14 3.465 14.448 3.017 15 3.017L21 3.017C21.552 3.017 22 3.465 22 4.017L22 15.017C22 18.33 19.33 21 16 21L14.017 21ZM2.017 21L2.017 18C2.017 16.899 2.899 16.017 4 16.017L7 16.017C7.552 16.017 8 15.017L8 9.017C8 8.465 7.552 8.017 7 8.017L3 8.017C2.448 8.017 2 7.569 2 7.017L2 4.017C2 3.465 2.448 3.017 3 3.017L9 3.017C9.552 3.017 10 3.465 10 4.017L10 15.017C10 18.33 7.33 21 4 21L2.017 21Z"></path></svg>
                                    </div>
                                    <p class="testimonial-text">"<?php echo esc_html( $text ); ?>"</p>
                                    <div class="testimonial-author">
                                        <?php if ( $img ) : ?>
                                            <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $name ); ?>" class="author-img">
                                        <?php endif; ?>
                                        <div class="author-info">
                                            <h4 class="author-name"><?php echo esc_html( $name ); ?></h4>
                                            <div class="author-rating">
                                                <?php 
                                                $rating = get_theme_mod( "tokoku_testi_rating_{$i}", 5 );
                                                for ($r = 1; $r <= 5; $r++) {
                                                    $star_class = $r <= $rating ? 'dashicons-star-filled' : 'dashicons-star-empty';
                                                    echo '<span class="dashicons '.$star_class.'"></span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    
                    if ( ! $testis_found ) {
                        echo '<p class="empty-msg">' . esc_html__( 'Belum ada testimoni klien.', 'tokoku' ) . '</p>';
                    }
                    ?>
                </div>
                <div class="testimonial-dots"></div>
            </div>
        </div>
    </section>

    <!-- ====================================================
         LATEST ARTICLES
         ==================================================== -->
    <section class="articles-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Artikel Terbaru</h2>
            </div>

            <div class="articles-slider-container">
                <div class="article-slider" id="article-slider">
                    <div class="article-track">
                        <?php
                        $latest_posts = new WP_Query( array(
                            'post_type'      => 'post',
                            'posts_per_page' => 6,
                        ) );

                        if ( $latest_posts->have_posts() ) :
                            while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
                        ?>
                            <div class="article-slide">
                                <article class="article-card">
                                    <div class="article-card__image">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <?php the_post_thumbnail( 'medium_large' ); ?>
                                        <?php else : ?>
                                            <div class="article-placeholder">
                                                <svg viewBox="0 0 24 24" width="40" height="40" stroke="currentColor" stroke-width="1" fill="none"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="article-card__content">
                                        <div class="article-card__meta">
                                            <span class="article-date"><?php echo get_the_date(); ?></span>
                                        </div>
                                        <h3 class="article-card__title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        <div class="article-card__excerpt">
                                            <?php echo wp_trim_words( get_the_excerpt(), 12 ); ?>
                                        </div>
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm article-read-more">
                                            Baca Selengkapnya
                                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="3" fill="none"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                        </a>
                                    </div>
                                </article>
                            </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<div class="empty-msg">' . esc_html__( 'Belum ada artikel yang dipublikasikan.', 'tokoku' ) . '</div>';
                        endif;
                        ?>
                    </div>
                </div>
                
                <button class="article-slider-btn prev" id="article-prev">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button class="article-slider-btn next" id="article-next">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
                
                <div class="article-slider-dots"></div>
            </div>
        </div>
    </section>
    
    <!-- ====================================================
         FAQ SECTION
         ==================================================== -->
    <section class="faq-section">
        <div class="container container--narrow">
            <div class="section-header text-center">
                <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'tokoku_faq_title', 'Pertanyaan Umum' ) ); ?></h2>
                <p class="section-subtitle"><?php echo esc_html( get_theme_mod( 'tokoku_faq_subtitle', 'Temukan jawaban dari pertanyaan yang paling sering ditanyakan oleh pelanggan kami.' ) ); ?></p>
            </div>

            <div class="faq-accordion">
                <?php
                $faq_found = false;
                for ( $i = 1; $i <= 10; $i++ ) {
                    $question = get_theme_mod( "tokoku_faq_q_{$i}" );
                    $answer   = get_theme_mod( "tokoku_faq_a_{$i}" );
                    
                    if ( $question && $answer ) {
                        $faq_found = true;
                        ?>
                        <div class="faq-item">
                            <div class="faq-question">
                                <h3><?php echo esc_html( $question ); ?></h3>
                                <span class="faq-icon">
                                    <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </span>
                            </div>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    <?php echo wpautop( esc_html( $answer ) ); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                
                if ( ! $faq_found ) {
                    echo '<p class="empty-msg text-center">' . __( 'Belum ada FAQ yang ditambahkan.', 'tokoku' ) . '</p>';
                }
                ?>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
