<?php
/**
 * Custom Meta Boxes for Produk CPT
 * Fields: harga, harga diskon, berat, stok
 *
 * @package TokoKu
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add meta box to produk edit screen
 */
function tokoku_add_produk_meta_boxes() {
    add_meta_box(
        'tokoku_produk_details',
        __( 'Detail Produk', 'tokoku' ),
        'tokoku_produk_meta_box_callback',
        'produk',
        'normal',
        'high'
    );

    add_meta_box(
        'tokoku_produk_gallery',
        __( 'Galeri Produk', 'tokoku' ),
        'tokoku_produk_gallery_callback',
        'produk',
        'side',
        'default'
    );
}
add_action( 'add_meta_boxes', 'tokoku_add_produk_meta_boxes' );

/**
 * Meta box callback - render fields
 */
function tokoku_produk_meta_box_callback( $post ) {
    wp_nonce_field( 'tokoku_save_produk_meta', 'tokoku_produk_nonce' );

    $harga        = get_post_meta( $post->ID, '_produk_harga', true );
    $harga_diskon = get_post_meta( $post->ID, '_produk_harga_diskon', true );
    $sku          = get_post_meta( $post->ID, '_produk_sku', true );
    $berat        = get_post_meta( $post->ID, '_produk_berat', true );
    $stok         = get_post_meta( $post->ID, '_produk_stok', true );
    $wa_text      = get_post_meta( $post->ID, '_produk_whatsapp_text', true );

    ?>
    <style>
        .tokoku-meta-container {
            padding: 10px 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
        .tokoku-meta-section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1d2327;
            margin: 20px 0 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f0f0f1;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .tokoku-meta-section-title:first-child { margin-top: 0; }
        
        .tokoku-meta-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .tokoku-meta-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        
        .tokoku-meta-field label {
            font-weight: 600;
            font-size: 13px;
            color: #50575e;
        }
        
        .tokoku-meta-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .tokoku-meta-icon {
            position: absolute;
            left: 10px;
            color: #949494;
            display: flex;
            align-items: center;
        }
        
        .tokoku-meta-field input[type="text"],
        .tokoku-meta-field input[type="number"],
        .tokoku-meta-field select,
        .tokoku-meta-field textarea {
            width: 100%;
            padding: 8px 12px;
            padding-left: 35px;
            border: 1px solid #dcdcde;
            border-radius: 6px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: border-color 0.2s, box-shadow 0.2s;
            font-size: 14px;
            line-height: 1.4;
        }
        
        .tokoku-meta-field input:focus,
        .tokoku-meta-field select:focus,
        .tokoku-meta-field textarea:focus {
            border-color: #2271b1;
            box-shadow: 0 0 0 1px #2271b1;
            outline: none;
        }

        .tokoku-meta-field textarea {
            padding-left: 12px;
            min-height: 100px;
        }

        .tokoku-meta-field .description {
            font-size: 12px;
            color: #757575;
            margin: 4px 0 0;
            font-style: normal;
        }

        /* Status Badge Colors */
        select#tokoku_stok {
            font-weight: 600;
        }
        select#tokoku_stok[value="tersedia"] { color: #2e7d32; }
        select#tokoku_stok[value="habis"] { color: #d32f2f; }
        select#tokoku_stok[value="preorder"] { color: #ed6c02; }
    </style>

    <div class="tokoku-meta-container">
        <div class="tokoku-meta-section-title">
            <span class="dashicons dashicons-tag"></span> <?php esc_html_e( 'Harga & Inventaris', 'tokoku' ); ?>
        </div>
        
        <div class="tokoku-meta-row" style="grid-template-columns: repeat(3, 1fr);">
            <div class="tokoku-meta-field">
                <label for="tokoku_sku"><?php esc_html_e( 'SKU / Kode Produk', 'tokoku' ); ?></label>
                <div class="tokoku-meta-input-wrapper">
                    <span class="tokoku-meta-icon"><span class="dashicons dashicons-barcode"></span></span>
                    <input type="text" id="tokoku_sku" name="_produk_sku" 
                           value="<?php echo esc_attr( $sku ); ?>" 
                           placeholder="Contoh: PLK-001">
                </div>
            </div>

            <div class="tokoku-meta-field">
                <label for="tokoku_harga"><?php esc_html_e( 'Harga Utama', 'tokoku' ); ?></label>
                <div class="tokoku-meta-input-wrapper">
                    <span class="tokoku-meta-icon"><span class="dashicons dashicons-money-alt"></span></span>
                    <input type="number" id="tokoku_harga" name="_produk_harga" 
                           value="<?php echo esc_attr( $harga ); ?>" 
                           min="0" step="100" placeholder="150000">
                </div>
            </div>

            <div class="tokoku-meta-field">
                <label for="tokoku_harga_diskon"><?php esc_html_e( 'Harga Diskon', 'tokoku' ); ?></label>
                <div class="tokoku-meta-input-wrapper">
                    <span class="tokoku-meta-icon"><span class="dashicons dashicons-cart"></span></span>
                    <input type="number" id="tokoku_harga_diskon" name="_produk_harga_diskon" 
                           value="<?php echo esc_attr( $harga_diskon ); ?>" 
                           min="0" step="100" placeholder="120000">
                </div>
            </div>
        </div>

        <div class="tokoku-meta-row">
            <div class="tokoku-meta-field">
                <label for="tokoku_berat"><?php esc_html_e( 'Berat Produk', 'tokoku' ); ?></label>
                <div class="tokoku-meta-input-wrapper">
                    <span class="tokoku-meta-icon"><span class="dashicons dashicons-performance"></span></span>
                    <input type="text" id="tokoku_berat" name="_produk_berat" 
                           value="<?php echo esc_attr( $berat ); ?>" 
                           placeholder="Contoh: 500 gram, 1 kg">
                </div>
            </div>

            <div class="tokoku-meta-field">
                <label for="tokoku_stok"><?php esc_html_e( 'Status Ketersediaan', 'tokoku' ); ?></label>
                <div class="tokoku-meta-input-wrapper">
                    <span class="tokoku-meta-icon"><span class="dashicons dashicons-archive"></span></span>
                    <select id="tokoku_stok" name="_produk_stok">
                        <option value="tersedia" <?php selected( $stok, 'tersedia' ); ?>><?php esc_html_e( 'Tersedia (Ready Stock)', 'tokoku' ); ?></option>
                        <option value="habis" <?php selected( $stok, 'habis' ); ?>><?php esc_html_e( 'Stok Habis (Out of Stock)', 'tokoku' ); ?></option>
                        <option value="preorder" <?php selected( $stok, 'preorder' ); ?>><?php esc_html_e( 'Pre-Order', 'tokoku' ); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="tokoku-meta-section-title" style="margin-top: 30px;">
            <span class="dashicons dashicons-whatsapp"></span> <?php esc_html_e( 'Kustomisasi Pesanan WhatsApp', 'tokoku' ); ?>
        </div>

        <div class="tokoku-meta-field">
            <label for="tokoku_wa_text"><?php esc_html_e( 'Pesan WhatsApp Khusus untuk Produk Ini', 'tokoku' ); ?></label>
            <textarea id="tokoku_wa_text" name="_produk_whatsapp_text" rows="4" 
                      placeholder="<?php esc_attr_e( 'Contoh: Halo, saya mau tanya produk {produk} yang harga {harga} ini...', 'tokoku' ); ?>"
            ><?php echo esc_textarea( $wa_text ); ?></textarea>
            <p class="description">
                <?php esc_html_e( 'Kosongkan untuk menggunakan template global. Gunakan tag: {produk}, {harga}, {jumlah}.', 'tokoku' ); ?>
            </p>
        </div>
    </div>
    <?php
}

/**
 * Gallery meta box callback
 */
function tokoku_produk_gallery_callback( $post ) {
    wp_nonce_field( 'tokoku_save_gallery', 'tokoku_gallery_nonce' );
    $gallery_ids = get_post_meta( $post->ID, '_produk_gallery', true );
    ?>
    <div id="tokoku-gallery-container">
        <div id="tokoku-gallery-images" style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px;">
            <?php
            if ( $gallery_ids ) {
                $ids = explode( ',', $gallery_ids );
                foreach ( $ids as $id ) {
                    $img = wp_get_attachment_image_src( $id, 'thumbnail' );
                    if ( $img ) {
                        echo '<div class="tokoku-gallery-item" style="position:relative;">';
                        echo '<img src="' . esc_url( $img[0] ) . '" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">';
                        echo '<button type="button" class="tokoku-remove-gallery-img" data-id="' . esc_attr( $id ) . '" style="position:absolute;top:-5px;right:-5px;background:#dc3545;color:#fff;border:none;border-radius:50%;width:18px;height:18px;font-size:11px;cursor:pointer;line-height:1;">&times;</button>';
                        echo '</div>';
                    }
                }
            }
            ?>
        </div>
        <input type="hidden" id="tokoku_gallery_ids" name="_produk_gallery" value="<?php echo esc_attr( $gallery_ids ); ?>">
        <button type="button" id="tokoku-add-gallery-btn" class="button">
            <?php esc_html_e( 'Tambah Gambar', 'tokoku' ); ?>
        </button>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var frame;
        
        $('#tokoku-add-gallery-btn').on('click', function(e) {
            e.preventDefault();
            
            if (frame) {
                frame.open();
                return;
            }
            
            frame = wp.media({
                title: '<?php esc_html_e( 'Pilih Gambar Galeri', 'tokoku' ); ?>',
                multiple: true,
                library: { type: 'image' }
            });
            
            frame.on('select', function() {
                var selection = frame.state().get('selection');
                var currentIds = $('#tokoku_gallery_ids').val();
                var ids = currentIds ? currentIds.split(',') : [];
                
                selection.each(function(attachment) {
                    attachment = attachment.toJSON();
                    ids.push(attachment.id);
                    var thumb = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                    var html = '<div class="tokoku-gallery-item" style="position:relative;">';
                    html += '<img src="' + thumb + '" style="width:60px;height:60px;object-fit:cover;border-radius:4px;">';
                    html += '<button type="button" class="tokoku-remove-gallery-img" data-id="' + attachment.id + '" style="position:absolute;top:-5px;right:-5px;background:#dc3545;color:#fff;border:none;border-radius:50%;width:18px;height:18px;font-size:11px;cursor:pointer;line-height:1;">&times;</button>';
                    html += '</div>';
                    $('#tokoku-gallery-images').append(html);
                });
                
                $('#tokoku_gallery_ids').val(ids.join(','));
            });
            
            frame.open();
        });
        
        $(document).on('click', '.tokoku-remove-gallery-img', function() {
            var removeId = $(this).data('id').toString();
            var currentIds = $('#tokoku_gallery_ids').val().split(',');
            currentIds = currentIds.filter(function(id) { return id !== removeId; });
            $('#tokoku_gallery_ids').val(currentIds.join(','));
            $(this).closest('.tokoku-gallery-item').remove();
        });
    });
    </script>
    <?php
}

/**
 * Save meta box data
 */
function tokoku_save_produk_meta( $post_id ) {
    // Verify nonces
    if ( ! isset( $_POST['tokoku_produk_nonce'] ) || 
         ! wp_verify_nonce( $_POST['tokoku_produk_nonce'], 'tokoku_save_produk_meta' ) ) {
        return;
    }

    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save fields
    $fields = array(
        '_produk_harga'          => 'intval',
        '_produk_harga_diskon'   => 'intval',
        '_produk_sku'            => 'sanitize_text_field',
        '_produk_berat'          => 'sanitize_text_field',
        '_produk_stok'           => 'sanitize_text_field',
        '_produk_whatsapp_text'  => 'sanitize_textarea_field',
    );

    foreach ( $fields as $key => $sanitize_fn ) {
        if ( isset( $_POST[ $key ] ) ) {
            $value = call_user_func( $sanitize_fn, $_POST[ $key ] );
            update_post_meta( $post_id, $key, $value );
        }
    }

    // Save gallery
    if ( isset( $_POST['tokoku_gallery_nonce'] ) && 
         wp_verify_nonce( $_POST['tokoku_gallery_nonce'], 'tokoku_save_gallery' ) ) {
        if ( isset( $_POST['_produk_gallery'] ) ) {
            $gallery = sanitize_text_field( $_POST['_produk_gallery'] );
            update_post_meta( $post_id, '_produk_gallery', $gallery );
        }
    }
}
add_action( 'save_post_produk', 'tokoku_save_produk_meta' );

/**
 * Helper: Get formatted price
 */
function tokoku_get_harga( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $harga        = get_post_meta( $post_id, '_produk_harga', true );
    $harga_diskon = get_post_meta( $post_id, '_produk_harga_diskon', true );
    $mata_uang    = get_theme_mod( 'tokoku_currency', 'Rp' );

    $output = '';

    if ( $harga_diskon && $harga_diskon < $harga ) {
        $diskon_persen = round( ( ( $harga - $harga_diskon ) / $harga ) * 100 );
        $output .= '<span class="price-discount-badge">-' . $diskon_persen . '%</span> ';
        $output .= '<span class="price-original">' . $mata_uang . ' ' . number_format( $harga, 0, ',', '.' ) . '</span> ';
        $output .= '<span class="price-current">' . $mata_uang . ' ' . number_format( $harga_diskon, 0, ',', '.' ) . '</span>';
    } elseif ( $harga ) {
        $output .= '<span class="price-current">' . $mata_uang . ' ' . number_format( $harga, 0, ',', '.' ) . '</span>';
    } else {
        $output .= '<span class="price-current price-contact">' . esc_html__( 'Hubungi Kami', 'tokoku' ) . '</span>';
    }

    return $output;
}

/**
 * Helper: Get stock status
 */
function tokoku_get_stok_status( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $stok = get_post_meta( $post_id, '_produk_stok', true );
    
    if ( ! $stok ) {
        $stok = 'tersedia';
    }

    $statuses = array(
        'tersedia' => array( 'label' => __( 'Tersedia', 'tokoku' ), 'class' => 'stok-tersedia' ),
        'habis'    => array( 'label' => __( 'Habis', 'tokoku' ), 'class' => 'stok-habis' ),
        'preorder' => array( 'label' => __( 'Pre-Order', 'tokoku' ), 'class' => 'stok-preorder' ),
    );

    return isset( $statuses[ $stok ] ) ? $statuses[ $stok ] : $statuses['tersedia'];
}
