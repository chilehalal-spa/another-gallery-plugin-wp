<?php

defined( 'ABSPATH' ) || exit;

class AGP_Shortcodes {

    public function enqueue_frontend_assets() {
        $card_css     = 'assets/css/frontend/agp-card.css';
        $grid_css     = 'assets/css/frontend/agp-grid.css';
        $lightbox_css = 'assets/css/frontend/agp-lightbox.css';
        $lightbox_js  = 'assets/js/agp-lightbox.js';

        // Versión basada en la fecha de modificación del archivo: el caché se invalida solo en cada cambio
        wp_enqueue_style( 'agp-card-css', AGP_PLUGIN_URL . $card_css, [], filemtime( AGP_PLUGIN_PATH . $card_css ) );
        wp_enqueue_style( 'agp-grid-css', AGP_PLUGIN_URL . $grid_css, [], filemtime( AGP_PLUGIN_PATH . $grid_css ) );
        wp_enqueue_style( 'agp-lightbox-css', AGP_PLUGIN_URL . $lightbox_css, [], filemtime( AGP_PLUGIN_PATH . $lightbox_css ) );

        wp_enqueue_script( 'agp-frontend-js', AGP_PLUGIN_URL . $lightbox_js, [], filemtime( AGP_PLUGIN_PATH . $lightbox_js ), true );
    }

    /**
     * Inserta el grid de fotos en el contenido al ver el enlace permanente
     * de una galería, ya que el CPT no tiene editor donde pegar el shortcode.
     *
     * @param string $content Contenido original del post.
     * @return string Contenido con el grid añadido cuando corresponde.
     */
    public function append_gallery_to_content( $content ) {
        if ( is_singular( 'agp_gallery' ) && in_the_loop() && is_main_query() ) {
            $content .= $this->render_gallery( [ 'id' => get_the_ID() ] );
        }
        return $content;
    }

    /**
     * Renderiza la Card de portada.
     */
    public function render_card( $atts ) {
        $atts = shortcode_atts( [ 'id'  => 0, 'url' => '' ], $atts );
        $post_id = intval( $atts['id'] );

        if ( ! $post_id || get_post_type( $post_id ) !== 'agp_gallery' ) return '';

        $title = get_the_title( $post_id );
        $link = ! empty( $atts['url'] ) ? $atts['url'] : get_permalink( $post_id );
        $thumb = get_the_post_thumbnail_url( $post_id, 'full' ); 
        if ( ! $thumb ) $thumb = ''; 

        ob_start();
        ?>
        <div class="agp-card">
            <div class="agp-card-thumb" style="background-image: url('<?php echo esc_url( $thumb ); ?>');"></div>
            <div class="agp-card-body">
                <h3 class="agp-card-title"><?php echo esc_html( $title ); ?></h3>
                <a href="<?php echo esc_url( $link ); ?>" class="agp-btn">Ver Fotos</a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Renderiza la galería Grid estilo Pinterest (Limpia).
     */
    public function render_gallery( $atts ) {
        $post_id = isset( $atts['id'] ) ? intval( $atts['id'] ) : get_the_ID();
        $ids = get_post_meta( $post_id, '_agp_image_ids', true );

        if ( empty( $ids ) ) return '';

        $id_array = explode( ',', $ids );

        ob_start();
        ?>
        <div class="agp-grid-container">
            <?php foreach ( $id_array as $img_id ) :
                $attachment = get_post( $img_id );
                if ( ! $attachment ) continue;

                $thumb = wp_get_attachment_image_url( $img_id, 'medium_large' );
                $full  = wp_get_attachment_image_url( $img_id, 'full' );
                $caption = $attachment->post_excerpt;
            ?>
                <div class="agp-grid-item">
                    <div class="agp-grid-image-wrap">
                        <img src="<?php echo esc_url( $thumb ); ?>" 
                             data-full="<?php echo esc_url( $full ); ?>" 
                             data-desc="<?php echo esc_attr( $caption ); ?>"
                             class="agp-lightbox-trigger" 
                             alt="<?php echo esc_attr( $caption ); ?>">
                    </div>
                    
                    <?php if ( ! empty( $caption ) ) : ?>
                        <div class="agp-grid-caption">
                            <?php echo esc_html( $caption ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div id="agp-modal" class="agp-modal">
            <span class="agp-close">&times;</span>
            <div class="agp-modal-wrapper">
                <img class="agp-modal-content" id="agp-modal-img">
                <div id="agp-caption-text"></div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}