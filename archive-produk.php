<?php
/**
 * The template for displaying product archives
 *
 * @package TokoKu
 */

get_header(); ?>

<main id="main-content" class="site-main product-archive">
    <div class="container">
        
        <header class="archive-header">
            <h1 class="archive-title"><?php the_archive_title(); ?></h1>
            
            <div class="archive-filters">
                <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="sort-form">
                    <select name="orderby" onchange="this.form.submit()">
                        <?php
                        $orderby = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'terbaru';
                        $options = array(
                            'terbaru' => 'Terbaru',
                            'termurah' => 'Termurah',
                            'termahal' => 'Termahal',
                            'nama' => 'Nama (A-Z)',
                        );
                        foreach ( $options as $val => $label ) {
                            echo '<option value="' . esc_attr( $val ) . '" ' . selected( $orderby, $val, false ) . '>' . esc_html( $label ) . '</option>';
                        }
                        ?>
                    </select>
                </form>
            </div>
        </header>

        <div class="archive-container">
            <aside class="archive-sidebar">
                <div class="sidebar-widget">
                    <h4 class="widget-title">Kategori</h4>
                    <ul class="category-list">
                        <?php
                        $categories = get_terms( array( 'taxonomy' => 'kategori_produk' ) );
                        foreach ( $categories as $cat ) {
                            echo '<li><a href="' . esc_url( get_term_link( $cat ) ) . '">' . esc_html( $cat->name ) . ' <span class="count">(' . $cat->count . ')</span></a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </aside>

            <div class="archive-content">
                <?php if ( have_posts() ) : ?>
                    <div class="product-grid">
                        <?php
                        while ( have_posts() ) : the_post();
                            get_template_part( 'template-parts/product-card' );
                        endwhile;
                        ?>
                    </div>
                    
                    <div class="pagination">
                        <?php the_posts_pagination(); ?>
                    </div>
                <?php else : ?>
                    <p>Produk tidak ditemukan.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</main>

<style>
.archive-header { margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end; }
.archive-title { font-size: 2.5rem; font-weight: 800; }
.archive-container { display: grid; grid-template-columns: 250px 1fr; gap: 40px; }

.sidebar-widget { background: var(--bg-secondary); padding: 25px; border-radius: var(--radius-md); margin-bottom: 30px; }
.category-list li { margin-bottom: 10px; }
.category-list a { display: flex; justify-content: space-between; color: var(--text-secondary); }
.category-list a:hover { color: var(--accent); }
.count { opacity: 0.5; font-size: 0.85rem; }

@media (max-width: 992px) {
    .archive-container { grid-template-columns: 1fr; }
    .archive-sidebar { display: none; }
}
</style>

<?php get_footer(); ?>
