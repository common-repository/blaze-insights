<div class="bi-row primary-insights">
    <div class="bi-dialog">
        <div class="bi-head">
            <h1><?php _e( 'Primary insight', 'blaze-insights' ) ?></h1>
        </div>
        <div class="bi-content">
            <div id="relative-insights"></div>
        </div>
    </div>

    <div class="bi-dialog">
        <div class="bi-head">
            <h1><?php _e( 'Blaze Insights Funnel', 'blaze-insights' ) ?></h1>
        </div>
        <div class="bi-content">
            <div class="bi-relative-conversion">
                <canvas id="funnel-relative" width="100%" height="500"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="bi-row equal">
    <div class="bi-dialog">
        <div class="bi-head">
            <h1><?php _e( 'View Any Product', 'blaze-insights' ) ?></h1>
        </div>
        <div class="bi-content">
            <div class="bi-relative-conversion">
            <canvas id="product-views" width="100%" height="500"></canvas>
            </div>
        </div>
    </div>

    <div class="bi-dialog">
        <div class="bi-head">
            <h1><?php _e( 'Add To Cart', 'blaze-insights' ) ?></h1>
        </div>
        <div class="bi-content">
            <div class="bi-relative-conversion">
                <canvas id="add-to-cart" width="100%" height="500"></canvas>
            </div>
        </div>
    </div>

    <div class="bi-dialog">
        <div class="bi-head">
            <h1><?php _e( 'Checkout', 'blaze-insights' ) ?></h1>
        </div>
        <div class="bi-content">
            <div class="bi-relative-conversion">
                <canvas id="checkout" width="100%" height="500"></canvas>
            </div>
        </div>
    </div>

    <div class="bi-dialog">
        <div class="bi-head">
            <h1><?php _e( 'Place Order', 'blaze-insights' ) ?></h1>
        </div>
        <div class="bi-content">
            <div class="bi-relative-conversion">
            <canvas id="transactions" width="100%" height="500"></canvas>
            </div>
        </div>
    </div>
</div>