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

                    <?php 
                    $video_url = get_post_meta( get_the_ID(), '_produk_video', true );
                    if ( $video_url ) : 
                    ?>
                        <a href="<?php echo esc_url( $video_url ); ?>" target="_blank" class="btn-watch-video">
                            <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                            Tonton Video Produk
                        </a>
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

                    <?php
                    // Get all new meta values
                    $harga          = get_post_meta( get_the_ID(), '_produk_harga', true );
                    $harga_diskon   = get_post_meta( get_the_ID(), '_produk_harga_diskon', true );
                    $multi_pilihan  = get_post_meta( get_the_ID(), '_produk_multi_pilihan', true );
                    $multi_harga    = get_post_meta( get_the_ID(), '_produk_multi_harga', true );
                    $warna          = get_post_meta( get_the_ID(), '_produk_pilihan_warna', true );
                    $catatan        = get_post_meta( get_the_ID(), '_produk_catatan', true );
                    $jumlah_stok    = get_post_meta( get_the_ID(), '_produk_jumlah_stok', true );
                    $berat          = get_post_meta( get_the_ID(), '_produk_berat', true );
                    $marketplace_shopee    = get_post_meta( get_the_ID(), '_produk_marketplace_shopee', true );
                    $marketplace_tokopedia = get_post_meta( get_the_ID(), '_produk_marketplace_tokopedia', true );
                    $marketplace_lazada    = get_post_meta( get_the_ID(), '_produk_marketplace_lazada', true );
                    $marketplace_tiktok    = get_post_meta( get_the_ID(), '_produk_marketplace_tiktok', true );
                    $marketplace_lainnya   = get_post_meta( get_the_ID(), '_produk_marketplace_lainnya', true );

                    $has_marketplace = ($marketplace_shopee || $marketplace_tokopedia || $marketplace_lazada || $marketplace_tiktok || $marketplace_lainnya);
                    $label_khusus   = get_post_meta( get_the_ID(), '_produk_label_khusus', true );
                    $mata_uang      = get_theme_mod( 'tokoku_currency', 'Rp' );
                    $show_price     = get_theme_mod( 'tokoku_show_price', 'yes' );
                    ?>

                    <h1 class="product-title">
                        <?php 
                        if ( $label_khusus ) {
                            echo '<span class="special-label">' . esc_html( $label_khusus ) . '</span>';
                        }
                        the_title(); 
                        ?>
                    </h1>

                    <?php if ( $show_price === 'yes' && ( $harga || $harga_diskon ) ) : ?>
                    <div class="product-price-display">
                        <?php if ( $harga_diskon && $harga_diskon < $harga ) : ?>
                            <span class="price-current"><?php echo esc_html( $mata_uang . ' ' . number_format( $harga_diskon, 0, ',', '.' ) ); ?></span>
                            <span class="price-original"><?php echo esc_html( $mata_uang . ' ' . number_format( $harga, 0, ',', '.' ) ); ?></span>
                            <?php 
                            $diskon_persen = round( ( ( $harga - $harga_diskon ) / $harga ) * 100 );
                            echo '<span class="price-discount-badge">-' . $diskon_persen . '%</span>';
                            ?>
                        <?php else : ?>
                            <span class="price-current"><?php echo esc_html( $mata_uang . ' ' . number_format( $harga, 0, ',', '.' ) ); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ( $multi_pilihan ) : 
                        $pilihan_arr = array_map( 'trim', explode( ',', $multi_pilihan ) );
                        $harga_arr   = $multi_harga ? array_map( 'trim', explode( ',', $multi_harga ) ) : array();
                    ?>
                    <div class="product-variations">
                        <span class="variations-label">Pilihan:</span>
                        <div class="variations-list">
                            <?php foreach ( $pilihan_arr as $index => $pilihan ) : 
                                $harga_varian = isset( $harga_arr[$index] ) && is_numeric($harga_arr[$index]) ? $harga_arr[$index] : '';
                            ?>
                                <button class="btn-variation" <?php if($harga_varian) echo 'data-price="' . esc_attr( $mata_uang . ' ' . number_format($harga_varian, 0, ',', '.') ) . '"'; ?>>
                                    <?php echo esc_html( $pilihan ); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="product-specs-table">
                        <?php
                        $sku = get_post_meta( get_the_ID(), '_produk_sku', true );
                        $stok = tokoku_get_stok_status();
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
                                <?php if ( $jumlah_stok ) echo ' <span class="stock-count">(' . esc_html( $jumlah_stok ) . ')</span>'; ?>
                            </div>
                        </div>
                        
                        <?php if ( $berat ) : ?>
                        <div class="spec-row">
                            <div class="spec-label">Berat</div>
                            <div class="spec-value"><?php echo esc_html( $berat ); ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if ( $warna ) : ?>
                        <div class="spec-row">
                            <div class="spec-label">Warna</div>
                            <div class="spec-value"><?php echo esc_html( $warna ); ?></div>
                        </div>
                        <?php endif; ?>

                        <?php if ( ! empty( $terms ) ) : ?>
                        <div class="spec-row">
                            <div class="spec-label">Kategori</div>
                            <div class="spec-value">
                                <a href="<?php echo esc_url( get_term_link( $terms[0] ) ); ?>"><?php echo esc_html( $terms[0]->name ); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ( $catatan ) : ?>
                    <div class="product-note-box">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        <div class="note-content">
                            <strong>Catatan:</strong> <?php echo nl2br( esc_html( $catatan ) ); ?>
                        </div>
                    </div>
                    <?php endif; ?>

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
                            Pesan via WhatsApp
                        </button>
                        
                        <?php if ( $has_marketplace ) : ?>
                        <div class="marketplace-links">
                            <span class="marketplace-title">Atau Beli di Marketplace:</span>
                            <div class="marketplace-buttons">
                                <?php if ( $marketplace_shopee ) : ?>
                                <a href="<?php echo esc_url( $marketplace_shopee ); ?>" target="_blank" class="btn-marketplace mp-shopee">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Shopee
                                </a>
                                <?php endif; ?>

                                <?php if ( $marketplace_tokopedia ) : ?>
                                <a href="<?php echo esc_url( $marketplace_tokopedia ); ?>" target="_blank" class="btn-marketplace mp-tokopedia">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Tokopedia
                                </a>
                                <?php endif; ?>

                                <?php if ( $marketplace_lazada ) : ?>
                                <a href="<?php echo esc_url( $marketplace_lazada ); ?>" target="_blank" class="btn-marketplace mp-lazada">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> Lazada
                                </a>
                                <?php endif; ?>

                                <?php if ( $marketplace_tiktok ) : ?>
                                <a href="<?php echo esc_url( $marketplace_tiktok ); ?>" target="_blank" class="btn-marketplace mp-tiktok">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> TikTok
                                </a>
                                <?php endif; ?>

                                <?php if ( $marketplace_lainnya ) : ?>
                                <a href="<?php echo esc_url( $marketplace_lainnya ); ?>" target="_blank" class="btn-marketplace mp-lainnya">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg> Lainnya
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
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

                </div>
            </div>

            <div class="product-description-wrapper">
                <div class="product-description">
                    <h3>Deskripsi Produk</h3>
                    <div class="content">
                        <?php the_content(); ?>
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
    margin-bottom: 20px;
    box-shadow: 0 10px 20px var(--shadow);
}
.btn-contact-us:hover { transform: translateY(-3px); box-shadow: 0 15px 30px var(--shadow); opacity: 0.9; color: #fff; }

/* Share */
.product-share { display: flex; align-items: center; gap: 20px; padding: 20px; background: var(--bg2); border-radius: 12px; margin-bottom: 20px; }
.share-label { font-size: 0.85rem; color: var(--text2); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
.share-icons { display: flex; gap: 12px; }
.share-icon { display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 50%; color: #fff; transition: var(--ease); }
.share-icon:hover { transform: scale(1.1); color: #fff; }
.share-icon.fb { background-color: #3b5998; }
.share-icon.tw { background-color: #000000; }
.share-icon.wa { background-color: #25d366; }

.product-description-wrapper { 
    border-top: 1.5px solid var(--border); 
    padding: 60px 0; 
    margin-top: 20px;
    background: var(--bg2);
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
    width: 100vw;
}
.product-description { 
    width: 100%;
    max-width: 1400px; 
    margin: 0 auto; 
    padding: 0 40px;
}
.product-description h3 { 
    font-size: 2.2rem; 
    font-weight: 800; 
    margin-bottom: 50px; 
    color: var(--text); 
    text-align: center; 
    display: block; 
    position: relative;
}
.product-description h3::after { 
    content: ''; 
    position: absolute; 
    bottom: -15px; 
    left: 50%; 
    transform: translateX(-50%); 
    width: 80px; 
    height: 4px; 
    background: var(--primary); 
    border-radius: 2px; 
}
.product-description .content { 
    color: var(--text); 
    line-height: 1.8; 
    font-size: 1.15rem; 
    background: var(--bg);
    padding: 60px;
    border-radius: 24px;
    box-shadow: 0 10px 40px var(--shadow);
    width: 100%;
}
.product-description .content p { margin-bottom: 25px; }
.product-description .content p:last-child { margin-bottom: 0; }

@media (max-width: 768px) {
    .product-description-wrapper { padding: 40px 0; margin-top: 30px; }
    .product-description { padding: 0 15px; }
    .product-description h3 { font-size: 1.8rem; margin-bottom: 35px; }
    .product-description .content { padding: 30px 20px; font-size: 1.05rem; border-radius: 0; box-shadow: none; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); background: transparent; }
}

@media (max-width: 992px) {
    .product-details { grid-template-columns: 1fr; gap: 30px; }
    .product-title { font-size: 1.8rem; }
}

/* New Frontend Details CSS */
.special-label { display: inline-block; background: var(--orange); color: #fff; font-size: 0.8rem; padding: 4px 10px; border-radius: 4px; vertical-align: middle; margin-right: 10px; text-transform: uppercase; letter-spacing: 1px; }

.product-price-display { margin-bottom: 25px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
.product-price-display .price-current { font-size: 1.8rem; font-weight: 800; color: var(--primary); }
.product-price-display .price-original { font-size: 1.1rem; color: var(--text2); text-decoration: line-through; }
.product-price-display .price-discount-badge { background: #ffebee; color: #d32f2f; font-weight: 700; font-size: 0.85rem; padding: 4px 8px; border-radius: 4px; }

.product-variations { margin-bottom: 30px; }
.variations-label { display: block; font-weight: 700; color: var(--text2); margin-bottom: 10px; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }
.variations-list { display: flex; flex-wrap: wrap; gap: 10px; }
.btn-variation { background: #fff; border: 1px solid var(--border); padding: 8px 16px; border-radius: 6px; font-weight: 600; color: var(--text); cursor: pointer; transition: var(--ease); font-size: 0.95rem; }
.btn-variation:hover, .btn-variation.active { border-color: var(--primary); color: var(--primary); background: var(--bg2); }

.stock-count { font-size: 0.85rem; color: var(--text2); font-weight: normal; margin-left: 4px; }

.product-note-box { background: #fff8e1; border-left: 4px solid #ffc107; padding: 15px; border-radius: 8px; display: flex; gap: 12px; margin-bottom: 30px; color: #5c4e16; }
.product-note-box svg { flex-shrink: 0; color: #ffb300; }
.product-note-box .note-content { font-size: 0.95rem; line-height: 1.5; }

.btn-watch-video { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; margin-top: 15px; padding: 12px; background: #fff; border: 1.5px solid var(--border); border-radius: 8px; font-weight: 700; color: var(--text); cursor: pointer; transition: var(--ease); text-decoration: none; }
.btn-watch-video:hover { border-color: #ff0000; color: #ff0000; }

.marketplace-links { margin-bottom: 30px; padding-top: 20px; border-top: 1.5px dashed var(--border); }
.marketplace-title { display: block; font-size: 0.9rem; font-weight: 700; color: var(--text2); margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px; text-align: center; }
.marketplace-buttons { display: flex; flex-direction: column; gap: 10px; }
.btn-marketplace { display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 14px; border-radius: 50px; font-weight: 700; color: #fff; text-decoration: none; transition: var(--ease); font-size: 1.05rem; }
.btn-marketplace:hover { transform: translateY(-2px); opacity: 0.9; color: #fff; }
.mp-shopee { background: #ee4d2d; }
.mp-tokopedia { background: #00aa5b; }
.mp-lazada { background: #000080; }
.mp-tiktok { background: #000000; }
.mp-lainnya { background: #6c757d; }

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const variationBtns = document.querySelectorAll('.btn-variation');
    const priceCurrent = document.querySelector('.product-price-display .price-current');
    
    variationBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all
            variationBtns.forEach(b => b.classList.remove('active'));
            // Add to clicked
            this.classList.add('active');
            
            // Update price if available
            const newPrice = this.getAttribute('data-price');
            if (newPrice && priceCurrent) {
                priceCurrent.textContent = newPrice;
            }
        });
    });
});
</script>

<?php get_footer(); ?>
