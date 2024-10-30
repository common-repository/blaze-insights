<div class="funnel-settings">
    <div class="filter-container">
        <div class="fuzzy-date">
            <strong><?php _e( 'Date Range', 'blaze-insights' ) ?>:</strong>
            <label>
                <input type="radio" name="fuzzy-date" value="30daysAgo" checked required>
                <?php _e( 'Last 30 days', 'blaze-insights' ) ?>
            </label>
            <label>
                <input type="radio" name="fuzzy-date" value="90daysAgo" required>
                <?php _e( 'Last 90 days', 'blaze-insights' ) ?>
            </label>
            <label>
                <input type="radio" name="fuzzy-date" value="180daysAgo" required>
                <?php _e( 'Last 180 days', 'blaze-insights' ) ?>
            </label>
        </div>
        <div class="custom">
            <strong><?php _e( 'Custom Date', 'blaze-insights' ) ?>:</strong>
            <input type="date" name="start-date" required> -
            <input type="date" name="end-date" required>
        </div>
    </div>
</div>