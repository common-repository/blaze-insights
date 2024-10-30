<div class="wizard-container">
    <div class="skip"><a class="skip-btn"><?php _e( 'Skip', 'blaze-insights' ) ?></a></div>
    <div class="wizard-steps">
        <div class="step step-1"><?php _e( '1', 'blaze-insights' ) ?></div>
        <div class="step step-2"><?php _e( '2', 'blaze-insights' ) ?></div>
        <div class="step step-3"><?php _e( '3', 'blaze-insights' ) ?></div>
    </div>
    <div class="wizard-content">
        <div class="wizard-step wizard-step-1">
            <h1><?php _e( 'Welcome to Blaze Insights!', 'blaze-insights' ) ?></h1>
            <p><?php _e( 'Please continue to connect Google Analytics with Blaze Insights', 'blaze-insights' ) ?></p>
            <button class="google-signin"><?php _e( 'Connect Google Analytics', 'blaze-insights' ) ?></button>
        </div>
        <div class="wizard-step wizard-step-2">
            <h1><?php _e( 'Select Your View', 'blaze-insights' ) ?></h1>
            <p><?php _e( 'Choose the view you want Blaze Insights to use for your reports.', 'blaze-insights' ) ?></p>
            <p><?php _e( 'This can be changed later.', 'blaze-insights' ) ?></p>
            <div class="view-container">
                <!-- <label for="view-selector">Select a view</label> -->
                <select name="view-selector" id="view-selector"></select>
            </div>
            <button class="select-view-btn"><?php _e( 'Next', 'blaze-insights' ) ?></button>
            <div class="clear"></div>
        </div>
        <div class="wizard-step wizard-step-3">
            <form class="form-set-contact-email">
                <h1><?php _e( 'Select Your Industry', 'blaze-insights' ) ?></h1>
                <p><?php _e( 'Your contact email address is used to keep you up to date with your store performance.', 'blaze-insights' ) ?></p>
                <p class="form-row">
                    <label for="contact-email"><?php _e( 'Contact Email', 'blaze-insights') ?>:</label>
                    <input type="email" name="contact-email" id="contact-email" required placeholder="example@email.com" />
                </p>
                <p class="form-row">
                    <label for="industry-vertical"><?php _e( 'Industry', 'blaze-insights') ?>:</label>
                    <span class="industry-container">
                        <select name="industry-vertical" id="industry-vertical">
                        </select>
                    </span>
                </p>
                <button type="submit" class="set-email-btn"><?php _e( 'Next', 'blaze-insights' ) ?></button>
                <div class="clear"></div>
            </form>
        </div>
    </div>
</div>