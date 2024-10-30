<?php if( Blaze_Insights_Update_Checker::isMajor() ): ?>
    <div>
        <div class="notice notice-error" style="margin-top: 20px;">
            <h3><?php _e( 'Update Notice', 'blaze-insights' ) ?></h3>
            <?php _e( Blaze_Insights_Update_Checker::getNotice(), 'blaze-insights' ) ?>
            <p><?php _e( 'Plugin update is required. Please update the plugin to the latest release.', 'blaze-insights' ); ?> <a class="update-blaze-insights-plugin" href="#"><?php _e( 'Update Now.', 'blaze-insights' ) ?></a></p>
        </div>
    </div>
<?php else: ?>
    <?php if( Blaze_Insights_Update_Checker::isMinor() || Blaze_Insights_Update_Checker::isPatch() ): ?>
        <div class="notice notice-info" style="margin-top: 20px;">
            <p><?php _e('A new update for <strong>Blaze Insights - use Google Analytics data to boost conversion rates with WooCommerce</strong> is available.', 'blaze-insights' ) ?> <a class="update-blaze-insights-plugin" href="#"><?php _e( 'Update Now.', 'blaze-insights' ) ?></a></p>
        </div>
    <?php endif; ?>
    <div class="analytics-container">
        <?php require_once "reports.php"; ?>
    </div>
<?php endif; ?>