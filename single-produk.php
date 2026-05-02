<?php
/**
 * The template for displaying single products
 *
 * @package TokoKu
 */

get_header(); ?>

<main id="main-content" class="site-main single-product">
    <div class="container">
        
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="product-details">
                <div class="product-gallery">
                    <div class="main-image">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'tokoku-product-large' ); ?>
                        <?php elseif ( get_post_meta( get_the_ID(), '_produk_dummy_img', true ) ) : ?>
                            <img src="<?php echo esc_url( get_post_meta( get_the_ID(), '_produk_dummy_img', true ) ); ?>" alt="<?php the_title(); ?>">
                        <?php else : ?>
                            <img src="<?php echo esc_url( TOKOKU_URI . '/assets/images/placeholder.svg' ); ?>" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                    </div>
                    
                    <?php
                    $gallery_ids = get_post_meta( get_the_ID(), '_produk_gallery', true );
                    if ( $gallery_ids ) :
                        $ids = explode( ',', $gallery_ids );
                        ?>
                        <div class="gallery-thumbs">
                            <?php foreach ( $ids as $id ) : ?>
                                <div class="thumb">
                                    <?php echo wp_get_attachment_image( $id, 'thumbnail' ); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-info">
                    <nav class="breadcrumb">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Beranda</a> &raquo;
                        <?php
                        $terms = get_the_terms( get_the_ID(), 'kategori_produk' );
                        if ( ! empty( $terms ) ) {
                            echo '<a href="' . esc_url( get_term_link( $terms[0] ) ) . '">' . esc_html( $terms[0]->name ) . '</a> &raquo; ';
                        }
                        ?>
                        <?php the_title(); ?>
                    </nav>

                    <h1 class="product-title"><?php the_title(); ?></h1>

                    <div class="product-specs-table">
                        <?php
                        $sku = get_post_meta( get_the_ID(), '_produk_sku', true );
                        $stok = tokoku_get_stok_status();
                        $terms = get_the_terms( get_the_ID(), 'kategori_produk' );
                        ?>
                        
                        <?php if ( $sku ) : ?>
                        <div class="spec-row">
                            <div class="spec-label">Kode</div>
                            <div class="spec-value"><?php echo esc_html( $sku ); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="spec-row">
                            <div class="spec-label">Stok</div>
                            <div class="spec-value <?php echo $stok['class'] == 'stok-preorder' ? 'is-preorder' : ''; ?>">
                                <?php if ( $stok['class'] == 'stok-preorder' ) : ?>
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                <?php endif; ?>
                                <?php echo $stok['label']; ?>
                            </div>
                        </div>
                        
                        <?php if ( ! empty( $terms ) ) : ?>
                        <div class="spec-row">
                            <div class="spec-label">Kategori</div>
                            <div class="spec-value">
                                <a href="<?php echo esc_url( get_term_link( $terms[0] ) ); ?>"><?php echo esc_html( $terms[0]->name ); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ( $stok['class'] == 'stok-preorder' ) : ?>
                    <div class="preorder-notice">
                        <div class="notice-title">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            PRE ORDER
                        </div>
                        <p>Hubungi kami untuk informasi lebih lanjut mengenai pemesanan produk ini.</p>
                    </div>
                    <?php endif; ?>

                    <div class="product-actions">
                        <?php
                        $harga = get_post_meta( get_the_ID(), '_produk_harga', true );
                        $harga_diskon = get_post_meta( get_the_ID(), '_produk_harga_diskon', true );
                        $mata_uang = get_theme_mod( 'tokoku_currency', 'Rp' );
                        $show_price = get_theme_mod( 'tokoku_show_price', 'yes' );
                        
                        if ( $show_price === 'yes' ) {
                            $price_val = $harga_diskon ? $mata_uang . ' ' . number_format( $harga_diskon, 0, ',', '.' ) : ($harga ? $mata_uang . ' ' . number_format( $harga, 0, ',', '.' ) : 'Hubungi Kami');
                        } else {
                            $price_val = 'Tanyakan Harga';
                        }
                        ?>
                        <button class="btn btn-primary btn-lg btn-block btn-whatsapp-order btn-contact-us"
                                data-product-id="<?php the_ID(); ?>"
                                data-product-name="<?php the_title(); ?>"
                                data-product-price="<?php echo esc_attr( $price_val ); ?>">
                            <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1" style="margin-right: 8px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            Hubungi Kami
                        </button>
                    </div>

                    <div class="product-share">
                        <span class="share-label">Bagikan ke</span>
                        <div class="share-icons">
                            <?php $current_url = urlencode(get_permalink()); $current_title = urlencode(get_the_title()); ?>
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>" target="_blank" class="share-icon fb" aria-label="Facebook">
                                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                            </a>
                            <!-- Twitter -->
                            <a href="https://twitter.com/intent/tweet?url=<?php echo $current_url; ?>&text=<?php echo $current_title; ?>" target="_blank" class="share-icon tw" aria-label="Twitter">
                                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                            </a>
                            <!-- WhatsApp -->
                            <a href="https://api.whatsapp.com/send?text=<?php echo $current_title . ' ' . $current_url; ?>" target="_blank" class="share-icon wa" aria-label="WhatsApp">
                                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                            </a>
                        </div>
                    </div>

                    <div class="product-description" style="margin-top: 40px;">
                        <h3>Deskripsi Produk</h3>
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <?php
            if ( ! empty( $terms ) ) :
                $related = new WP_Query( array(
                    'post_type' => 'produk',
                    'posts_per_page' => 4,
                    'post__not_in' => array( get_the_ID() ),
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'kategori_produk',
                            'field' => 'term_id',
                            'terms' => $terms[0]->term_id,
                        ),
                    ),
                ) );

                if ( $related->have_posts() ) : ?>
                    <section class="related-products section-padding">
                        <h2 class="section-title">Produk Terkait</h2>
                        <div class="product-grid">
                            <?php while ( $related->have_posts() ) : $related->the_post();
                                get_template_part( 'template-parts/product-card' );
                            endwhile; ?>
                        </div>
                    </section>
                <?php wp_reset_postdata(); endif;
            endif; ?>

        <?php endwhile; ?>

    </div>
</main>

<style>
.single-product { padding: 40px 0; }
.product-details { display: grid; grid-template-columns: 5fr 7fr; gap: 50px; margin-bottom: 60px; }
.main-image { border-radius: var(--radius); overflow: hidden; margin-bottom: 20px; border: 1px solid var(--border); background: var(--bg2); }
.main-image img { width: 100%; height: auto; display: block; transition: var(--ease); }
.gallery-thumbs { display: flex; gap: 12px; }
.gallery-thumbs .thumb { cursor: pointer; border-radius: 8px; overflow: hidden; border: 2px solid transparent; transition: var(--ease); }
.gallery-thumbs .thumb:hover { border-color: var(--primary); }
.gallery-thumbs img { width: 80px; height: 80px; object-fit: cover; display: block; }

.breadcrumb { font-size: 0.85rem; color: var(--text2); margin-bottom: 25px; display: flex; align-items: center; gap: 8px; }
.breadcrumb a { color: var(--text2); text-decoration: none; transition: var(--ease); }
.breadcrumb a:hover { color: var(--primary); }

.product-title { font-size: 2.2rem; font-weight: 800; color: var(--text); margin-bottom: 20px; line-height: 1.2; letter-spacing: -0.5px; }

/* Specs Table */
.product-specs-table { border-top: 1.5px solid var(--border); margin-bottom: 30px; }
.spec-row { display: flex; border-bottom: 1px solid var(--border); padding: 14px 0; align-items: center; }
.spec-label { width: 130px; font-weight: 700; color: var(--text2); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; flex-shrink: 0; }
.spec-value { flex: 1; color: var(--text); font-weight: 600; }
.spec-value a { color: var(--primary); text-decoration: none; }
.spec-value a:hover { text-decoration: underline; }

/* Pre Order styles */
.is-preorder { color: var(--orange); font-weight: 700; display: flex; align-items: center; gap: 6px; }
.preorder-notice { margin-bottom: 30px; padding: 20px; background: var(--bg2); border-left: 4px solid var(--orange); border-radius: 8px; }
.preorder-notice .notice-title { color: var(--orange); font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
.preorder-notice p { color: var(--text2); margin: 0; font-size: 0.9rem; line-height: 1.5; }

/* Button */
.btn-contact-us {
    background: var(--gradient);
    color: #fff;
    font-size: 1.1rem;
    font-weight: 800;
    padding: 16px 30px;
    border-radius: 50px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: none;
    cursor: pointer;
    transition: var(--ease);
    margin-bottom: 30px;
    box-shadow: 0 10px 20px var(--shadow);
}
.btn-contact-us:hover { transform: translateY(-3px); box-shadow: 0 15px 30px var(--shadow); opacity: 0.9; color: #fff; }

/* Share */
.product-share { display: flex; align-items: center; gap: 20px; padding: 20px; background: var(--bg2); border-radius: 12px; margin-bottom: 40px; }
.share-label { font-size: 0.85rem; color: var(--text2); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
.share-icons { display: flex; gap: 12px; }
.share-icon { display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 50%; color: #fff; transition: var(--ease); }
.share-icon:hover { transform: scale(1.1); color: #fff; }
.share-icon.fb { background-color: #3b5998; }
.share-icon.tw { background-color: #000000; }
.share-icon.wa { background-color: #25d366; }

.product-description { border-top: 1.5px solid var(--border); padding-top: 40px; }
.product-description h3 { font-size: 1.4rem; font-weight: 800; margin-bottom: 20px; color: var(--text); position: relative; display: inline-block; }
.product-description h3::after { content: ''; position: absolute; bottom: -8px; left: 0; width: 40px; height: 3px; background: var(--primary); border-radius: 2px; }
.product-description .content { color: var(--text2); line-height: 1.8; font-size: 1.05rem; }

@media (max-width: 992px) {
    .product-details { grid-template-columns: 1fr; gap: 30px; }
    .product-title { font-size: 1.8rem; }
}
</style>

<?php get_footer(); ?>
