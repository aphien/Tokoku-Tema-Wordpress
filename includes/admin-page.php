<?php
/**
 * TokoKu Dashboard Page
 *
 * @package TokoKu
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Admin Menu
 */
function tokoku_admin_menu() {
    add_menu_page(
        __( 'TokoKu Settings', 'tokoku' ),
        'TokoKu',
        'manage_options',
        'tokoku-settings',
        'tokoku_settings_page_html',
        'dashicons-store',
        2
    );
}
add_action( 'admin_menu', 'tokoku_admin_menu' );

/**
 * Settings Page HTML
 */
function tokoku_settings_page_html() {
    ?>
    <div class="wrap tokoku-admin-wrap">
        <div class="tokoku-admin-header">
            <div class="tokoku-admin-logo">
                <span class="dashicons dashicons-store"></span>
                <h1>TokoKu Theme Dashboard</h1>
            </div>
            <div class="tokoku-admin-version">v1.2.0</div>
        </div>

        <div class="tokoku-admin-content">
            <div class="tokoku-admin-main">
                <div class="tokoku-admin-card welcome-card">
                    <h2>Selamat Datang di TokoKu!</h2>
                    <p>Terima kasih telah menggunakan tema TokoKu. Tema ini dirancang untuk kemudahan pengelolaan toko online berbasis WhatsApp yang premium dan responsif.</p>
                    <div class="tokoku-admin-actions">
                        <a href="<?php echo admin_url( 'customize.php?autofocus[panel]=tokoku_panel' ); ?>" class="button button-primary button-hero">Buka Pengaturan Tema</a>
                        <a href="<?php echo admin_url( 'post-new.php?post_type=produk' ); ?>" class="button button-secondary button-hero">Tambah Produk Baru</a>
                    </div>
                </div>

                <div class="tokoku-admin-grid">
                    <div class="tokoku-admin-card">
                        <h3><span class="dashicons dashicons-admin-appearance"></span> Tampilan</h3>
                        <p>Atur warna utama, logo, dan mode tampilan (Light/Dark) melalui Customizer.</p>
                        <a href="<?php echo admin_url( 'customize.php?autofocus[section]=tokoku_appearance' ); ?>">Atur Tampilan &rarr;</a>
                    </div>
                    <div class="tokoku-admin-card">
                        <h3><span class="dashicons dashicons-whatsapp"></span> WhatsApp</h3>
                        <p>Hubungkan nomor WhatsApp Anda dan sesuaikan template pesan otomatis.</p>
                        <a href="<?php echo admin_url( 'customize.php?autofocus[section]=tokoku_whatsapp' ); ?>">Atur WhatsApp &rarr;</a>
                    </div>
                    <div class="tokoku-admin-card">
                        <h3><span class="dashicons dashicons-images-alt2"></span> Banner Slider</h3>
                        <p>Unggah dan kelola banner promosi di halaman depan website Anda.</p>
                        <a href="<?php echo admin_url( 'customize.php?autofocus[section]=tokoku_slider' ); ?>">Atur Banner &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="tokoku-admin-sidebar">
                <div class="tokoku-admin-card">
                    <h3>Statistik Cepat</h3>
                    <ul class="tokoku-stats">
                        <li>
                            <strong><?php echo wp_count_posts( 'produk' )->publish; ?></strong>
                            <span>Produk Aktif</span>
                        </li>
                        <li>
                            <strong><?php echo wp_count_terms( 'kategori_produk' ); ?></strong>
                            <span>Kategori</span>
                        </li>
                    </ul>
                </div>
                <div class="tokoku-admin-card support-card">
                    <h3>Butuh Bantuan?</h3>
                    <p>Jika Anda mengalami kendala atau membutuhkan fitur tambahan, jangan ragu untuk menghubungi tim pengembang.</p>
                    <a href="https://wa.me/6281234567890" target="_blank" class="button button-secondary">Hubungi Developer</a>
                </div>
                <div style="text-align:center; padding:10px; opacity:0.5; font-size:11px;">
                    TokoKu Theme by m.alfiandiismet
                </div>
            </div>
        </div>
    </div>

    <style>
        .tokoku-admin-wrap { margin: 20px 20px 0 0; max-width: 1200px; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; }
        .tokoku-admin-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px; background: #fff; padding: 20px 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .tokoku-admin-logo { display: flex; align-items: center; gap: 15px; }
        .tokoku-admin-logo .dashicons { font-size: 32px; width: 32px; height: 32px; color: #007bff; }
        .tokoku-admin-logo h1 { margin: 0; font-size: 24px; font-weight: 800; color: #1d2327; }
        .tokoku-admin-version { background: #e7f3ff; color: #007bff; padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 13px; }

        .tokoku-admin-content { display: grid; grid-template-columns: 1fr 300px; gap: 25px; }
        .tokoku-admin-card { background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .tokoku-admin-card h3 { margin-top: 0; display: flex; align-items: center; gap: 10px; font-size: 18px; color: #1d2327; }
        .tokoku-admin-card h3 .dashicons { color: #007bff; }
        .tokoku-admin-card p { color: #646970; line-height: 1.6; }
        .tokoku-admin-card a { text-decoration: none; font-weight: 600; color: #007bff; transition: 0.2s; }
        .tokoku-admin-card a:hover { color: #0056b3; }

        .welcome-card { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: #fff; }
        .welcome-card h2 { margin-top: 0; font-size: 28px; font-weight: 800; color: #fff; }
        .welcome-card p { color: rgba(255,255,255,0.9); font-size: 16px; margin-bottom: 25px; }
        
        .tokoku-admin-actions { display: flex; gap: 15px; }
        .tokoku-admin-actions .button-primary { background: #fff !important; color: #007bff !important; border: none !important; box-shadow: none !important; }
        .tokoku-admin-actions .button-secondary { background: rgba(255,255,255,0.2) !important; color: #fff !important; border: 1px solid rgba(255,255,255,0.3) !important; }

        .tokoku-admin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        
        .tokoku-stats { list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .tokoku-stats li { background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; }
        .tokoku-stats li strong { display: block; font-size: 24px; color: #007bff; }
        .tokoku-stats li span { font-size: 12px; color: #646970; text-transform: uppercase; font-weight: 700; }

        .support-card { background: #fffbeb; border-left: 4px solid #f59e0b; }
        .support-card h3 { color: #92400e; }
        .support-card .button-secondary { width: 100%; text-align: center; padding: 8px !important; margin-top: 10px; }

        @media (max-width: 782px) {
            .tokoku-admin-content { grid-template-columns: 1fr; }
            .tokoku-admin-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        }
    </style>
    <?php
}
