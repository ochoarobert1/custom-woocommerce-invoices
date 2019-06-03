<?php

function cwoo_invoices_metabox() {

    $screens = array( 'shop_order' );

    foreach ( $screens as $screen ) {
        add_meta_box(
            'cwoo_invoices_main',
            __( 'Documentos Asignados', 'custom-woocommerce-invoices' ),
            'cwoo_invoices_main_callback',
            $screen
        );
    }

    add_filter( 'postbox_classes_shop_order_cwoo_invoices_main', 'cwoo_invoices_metabox_classes' );
}

add_action( 'add_meta_boxes', 'cwoo_invoices_metabox' );

function cwoo_invoices_metabox_classes( $classes=array() ) {
    if( !in_array( 'cwoo_invoices_metabox', $classes ) )
        $classes[] = 'cwoo_invoices_metabox';
    return $classes;
}

function cwoo_invoices_main_callback($post) {
    // Add a nonce field so we can check for it later.
    wp_nonce_field( 'cwoo_invoices_nonce', 'cwoo_invoices_nonce' );

    $value = get_post_meta( $post->ID, '_global_notice', true );

    ob_start();
    /* PERFORM COMLEX QUERY, ECHO RESULTS, ETC. */
?>
<div class="cwoo_invoices_inside_metabox">
    <div class="cwoo_invoices_invoices_item">
        <label for="">documento</label>
        <button class="cwoo_select_btn"><i class="dashicons dashicons-visibility"></i></button>
        <input type="hidden" name="cwoo_file" value="elid">
        <div class="cwoo_invoices_actions">
            <button><i class="dashicons dashicons-welcome-write-blog"></i></button><button><i class="dashicons dashicons-trash"></i></button>
        </div>
    </div>
</div>
<div class="cwoo_invoices_add_buttons">
    <button class="cwoo_add_documents"><?php _e('Agregar Nuevo Documento'); ?></button>
</div>
<?php
    $content = ob_get_contents();
    ob_end_clean();


    echo $content;
}

function cwoo_invoices_save_meta_box_data( $post_id ) {

    if ( ! isset( $_POST['cwoo_invoices_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['cwoo_invoices_nonce'], 'cwoo_invoices_nonce' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['post_type'] ) && 'shop_order' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    }
    else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    if ( ! isset( $_POST['global_notice'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['global_notice'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, '_global_notice', $my_data );
}

add_action( 'save_post', 'cwoo_invoices_save_meta_box_data' );

function cwoo_invoices_before_post( $content ) {

    global $post;

    // retrieve the global notice for the current post
    $global_notice = esc_attr( get_post_meta( $post->ID, '_global_notice', true ) );

    $notice = "<div class='sp_global_notice'>$global_notice</div>";

    return $notice . $content;

}

add_filter( 'the_content', 'cwoo_invoices_before_post' );
