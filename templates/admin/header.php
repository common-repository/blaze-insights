<div class="bi-heading">
    <div class="left">
        <h1>
            <img src="<?php echo BLAZE_INSIGHTS_PLUGIN_URL ?>/assets/img/blaze-online-logo.png" alt="Blaze Online Logo">
            <?php _e( 'Blaze Insights', 'blaze-insights' ) ?> <span><?php esc_html_e( $view_object['webPropertyId'] . ' (' . $view_object['websiteURL'] . ' ' . $view_object['name'] . ')' ) ?></span>
        </h1>
    </div>
    <div class="right">
        <?php require_once "funnel-settings.php"; ?>
    </div>
</div>