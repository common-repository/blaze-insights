=== Blaze Insights - use Google Analytics data to get more sales from WooCommerce ===
Contributors: linksync
Tags: google analytics, woocommerce, ecommerce, user experience, conversion rate, woocommerce reports
Requires at least: 4.4
Tested up to: 5.8.1
Stable tag: 1.1.5
Requires PHP: 7.0
License: GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html)

Is your Google Analytics providing little to no value?

There are actually powerful insights to be seen in the raw data. Insights that allow you to take real, actionable steps to improve your bottom line. This is what Blaze Insights is all about.

It’s not just another plugin which shows you the same information you can already see in Google Analytics.

It’s also not just a bunch of charts that you have to figure out all by yourself. 

When the insights are applied, this is powerful stuff. It could transform your sales and ultimately your business.

== Description ==

## How does it work?
You probably already know that customers go through the following steps to make a purchase:
1. View a product
2. Add the product to their cart
3. Checkout
4. Place their order

Blaze Insights allows you to see:
1. Which step you're losing customers at.
2. Whether or not that’s “normal”.
3. How much money you’re missing out on because of it.
4. How to fix it.

== Installation ==

= Minimum Requirements =

* WordPress 5.7.1 or greater
* WooCommerce 2.6.10 or greater
* PHP version 7.0 or greater (PHP 7.0 or greater is recommended)
* MySQL version 5.0 or greater (MySQL 5.6 or greater is recommended)

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of Blaze Insights, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “Blaze Insights” and click Search Plugins. Once you’ve found our plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading our plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).



== Frequently Asked Questions ==

= Is Blaze Insights Really Free? =
Yes.

= Do I need a Google Analytics account =
Yes. You need an existing Google Analytics account with Enhanced Ecommerce enabled in order to benefit from Blaze Insights.

= What sort of customer service and support can I expect from Blaze Online? =
Glad you asked. We provide support via chat, phone and email and every person working at Blaze Online is committed to providing first-rate customer service, so we’ll do everything in our earthly powers to answer any questions or resolve any issues you might have.


== Screenshots ==

1. Blaze Insights Funnel: This shows the percentage of your users that continued from each stage of the buying journey to the next.
2. View any product: When you make changes to product discovery, use this to see what impact the changes had on the percentage of users viewing a product.
3. Add to cart: When you make changes to the product detail page, use this to see what impact the changes had on the percentage of users adding to cart.
4. Checkout: When you make changes to the cart experience, use this to see what impact the changes had on the percentage of users that continue to checkout.
5. Place order: When you make changes to the checkout experience, use this to see what impact the changes had on the percentage of users that placed an order.

== Changelog ==

= [1.1.5] - 2021-11-25 =
#### Fixed 
* Only make calls to the API once authenticated to google.

#### Changed 
* Update heading wordings.
* Update plugin description and screenshots.

= [1.1.4] - 2021-11-23 =
#### Fixed 
* Redirect to wrong url after setting up wizard.

#### Changed 
* Updated colors used on charts and annotations.
* Can now update the plugin on the main dashboard.

#### Removed
* Sessions (Pie chart) and Absolute funnel removed.
* Dev: Removed unused codes.

= [1.1.3] - 2021-11-18 =
#### Fixed 
* Fix patch method to use PATCH endpoint.

= [1.1.2] - 2021-11-16 =
#### Changed 
* Generating charts based on new API response.
* UI: Combine Primary insights and Blaze insights funnel.

= [1.1.1] - 2021-11-11 =
#### Fixed
* Make sure that the force wizard setup is only shown on which browser activates it.
* Replace guzzle with php curl for handling request for PHP 7.0.

#### Add 
* Show error messages when there's an issue along the wizard setup steps.

#### Changed 
* Hide admin_notices on the dashboard.

= [1.1.0] - 2021-11-09 =
#### Added
* Add request polling for new reports. This will keep on fetching the updated data a maximum of 5 times from the API.
* Industry field on the wizard's step 3 form.
* Create new endpoint for getting current view with the industry field.
* Benchmark data to all funnels.

#### Changed 
* Update changelog format using [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).
* Update charts data based on new API response.
* Update set google view PUT endpoint.
* Add secondary color, update pie chart color.
* Move the blaze insights head before the notices.
* UI Improvements.

= [1.0.2] - 2021-10-24 =
#### Fixed
* Issue with update checker logic after plugin update/install.

#### Removed
* Dev: remove unnecessary comments.


= [1.0.1] - 2021-10-22 =
#### Added
* Add version check in plugin and show upgrade dialog.

#### Changed 
* Updated product name to 'Blaze Insights - use Google Analytics data to boost conversion rates with WooCommerce'.

#### Removed
* Checking of woocommerce as dependency on activation.

#### Fixed
* Fix typo on first FAQ answers.

= [1.0.0] - 2021-10-21 =
* Initial release.

== Upgrade Notice ==

= 1.1.0 =
Additional benchmarking feature, data updates from the API.

= 1.0.2 =
Fixes the notice on dashboard that makes the app unaccessable after plugin update/install.

= 1.0.0 =
Initial plugin release.