<?php
//==============================================================================
// Stripe Payment Gateway v210.2
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

$version = 'v210.2';

//------------------------------------------------------------------------------
// Heading
//------------------------------------------------------------------------------
$_['heading_title']						= 'Stripe';
$_['text_stripe']						= '<a target="blank" href="https://stripe.com"><img src="https://stripe.com/img/logo.png" alt="Stripe" title="Stripe" /></a>';

//------------------------------------------------------------------------------
// Extension Settings
//------------------------------------------------------------------------------
$_['tab_extension_settings']			= 'Extension Settings';
$_['heading_extension_settings']		= 'Extension Settings';

$_['entry_status']						= 'Status: <div class="help-text">Set the status for the extension as a whole.</div>';
$_['entry_sort_order']					= 'Sort Order: <div class="help-text">Enter the sort order for the extension, relative to other payment methods.</div>';
$_['entry_title']						= 'Title: <div class="help-text">Enter the title for the payment method displayed to the customer. HTML is supported.</div>';
$_['entry_button_text']					= 'Button Text: <div class="help-text">Enter the text for the order confirmation button.</div>';
$_['entry_button_class']				= 'Button Class: <div class="help-text">Enter the CSS class for buttons in your theme.</div>';
$_['entry_button_styling']				= 'Button Styling: <div class="help-text">Optionally enter extra CSS styling for the button.</div>';

//------------------------------------------------------------------------------
// Order Statuses
//------------------------------------------------------------------------------
$_['tab_order_statuses']				= 'Order Statuses';
$_['heading_order_statuses']			= 'Order Statuses';
$_['help_order_statuses']				= 'Choose the order statuses set when a payment meets each condition. Note: to actually <strong>deny</strong> payments that fail CVC or Zip Checks, you need to enable the appropriate setting in your Stripe admin panel.<br />You can refund a payment by using the link provided in the History tab for the order.';

$_['entry_success_status_id']			= 'Successful Payment:';
$_['entry_street_status_id']			= 'Street Check Failure:';
$_['entry_zip_status_id']				= 'Zip Check Failure:';
$_['entry_cvc_status_id']				= 'CVC Check Failure:';
$_['entry_refund_status_id']			= 'Fully Refunded Payment:';
$_['entry_partial_status_id']			= 'Partially Refunded Payment:';

$_['text_ignore']						= '--- Ignore ---';

//------------------------------------------------------------------------------
// Restrictions
//------------------------------------------------------------------------------
$_['tab_restrictions']					= 'Restrictions';
$_['heading_restrictions']				= 'Restrictions';
$_['help_restrictions']					= 'Set the required cart total and select the eligible stores, geo zones, and customer groups for this payment method.';

$_['entry_min_total']					= 'Minimum Total: <div class="help-text">Enter the minimum order total that must be reached before this payment method becomes active. Leave blank to have no restriction.</div>';
$_['entry_max_total']					= 'Maximum Total: <div class="help-text">Enter the maximum order total that can be reached before this payment method becomes inactive. Leave blank to have no restriction.</div>';

$_['entry_stores']						= 'Store(s): <div class="help-text">Select the stores that can use this payment method.</div>';

$_['entry_geo_zones']					= 'Geo Zone(s): <div class="help-text">Select the geo zones that can use this payment method. The "Everywhere Else" checkbox applies to any locations not within a geo zone.</div>';
$_['text_everywhere_else']				= '<em>Everywhere Else</em>';

$_['entry_customer_groups']				= 'Customer Group(s): <div class="help-text">Select the customer groups that can use this payment method. The "Guests" checkbox applies to all customers not logged in to an account.</div>';
$_['text_guests']						= '<em>Guests</em>';

//------------------------------------------------------------------------------
// Stripe Settings
//------------------------------------------------------------------------------
$_['tab_stripe_settings']				= 'Stripe Settings';
$_['heading_stripe_settings']			= 'Stripe Settings';
$_['help_stripe_settings']				= 'API Keys can be found in your Stripe admin panel under Your Account > Account Settings > API Keys';

$_['entry_test_secret_key']				= 'Test Secret Key:';
$_['entry_test_publishable_key']		= 'Test Publishable Key:';
$_['entry_live_secret_key']				= 'Live Secret Key:';
$_['entry_live_publishable_key']		= 'Live Publishable Key:';

$_['entry_webhook_url']					= 'Webhook URL: <div class="help-text">Copy and paste this URL into your Stripe account, in Your Account > Account Settings > Webhooks. If you change your store&apos;s Encryption Key in System > Settings > Server, remember to also update the webhook URL in Stripe.</div>';

$_['entry_transaction_mode']			= 'Transaction Mode: <div class="help-text">Use "Test" to test payments through Stripe. For more info, visit <a href="https://stripe.com/docs/testing" target="_blank">https://stripe.com/docs/testing</a>. Use "Live" when you&apos;re ready to accept payments.</div>';
$_['text_test']							= 'Test';
$_['text_live']							= 'Live';

$_['entry_charge_mode']					= 'Charge Mode: <div class="help-text">Choose whether to authorize payments and manually capture them later, or to capture (i.e. fully charge) payments when orders are placed. For payments that are only Authorized, you can Capture them by using the link provided in the History tab for the order.<br /><br />If you choose "Authorize if possibly fraudulent, Capture otherwise" then the extension will use your fraud settings to determine whether an order might be fraudulent. If the fraud score is over your threshold, the charge will be Authorized; if under, the charge will be Captured.</div>';
$_['text_authorize']					= 'Authorize';
$_['text_capture']						= 'Capture';
$_['text_fraud_authorize']				= 'Authorize if possibly fraudulent, Capture otherwise';

$_['entry_send_customer_data']			= 'Send Customer Data: <div class="help-text">Sending customer data will create a customer in Stripe when an order is processed, based on the email address for the order. The credit card used will be attached to this customer, allowing you to charge them again in the future in Stripe.</div>';
$_['text_never']						= 'Never';
$_['text_customers_choice']				= 'Customer&apos;s choice';
$_['text_always']						= 'Always';

$_['entry_allow_stored_cards']			= 'Allow Customers to Use Stored Cards: <div class="help-text">If set to "Yes", customers that have cards stored in Stripe will be able to use those cards for future purchases in your store, without having to re-enter the information.</div>';

$_['entry_transaction_description']		= 'Transaction Description: <div class="help-text">Enter the text sent as the Stripe transaction description. You can use the following shortcodes to enter information about the order: [store], [order_id], [amount], [email], [comment], [products]</div>';

$_['heading_currency_settings']			= 'Currency Settings';
$_['help_currency_settings']			= 'Select the currencies that Stripe will charge in, based on the order currency. <a target="_blank" href="https://support.stripe.com/questions/which-currencies-does-stripe-support">See which currencies your country supports</a>';
$_['entry_currencies']					= 'When Orders Are In [currency], Charge In:';
$_['text_currency_disabled']			= '--- Disabled ---';


//------------------------------------------------------------------------------
// Stripe Checkout
//------------------------------------------------------------------------------
$_['tab_stripe_checkout']				= 'Stripe Checkout';
$_['heading_stripe_checkout']			= 'Stripe Checkout';
$_['help_stripe_checkout']				= 'Stripe Checkout uses Stripe&apos;s pop-up for displaying the credit card inputs, validation, and error handling. You can read more about it and view a demo at <a target="_blank" href="https://stripe.com/docs/checkout">https://stripe.com/docs/checkout</a><br />Note: Stripe Checkout does <strong>not</strong> allow customers to use the billing address entered in OpenCart.';

$_['entry_use_checkout']				= 'Use Stripe Checkout Pop-up:';

$_['entry_checkout_remember_me']		= 'Enable "Remember Me" Option: <div class="help-text">This will allow customers to choose whether Stripe remembers them on other sites that use Stripe Checkout. Note: even if enabled, the option will not appear if the customer&apos;s browser is set to block third-party cookies.</div>';

$_['entry_checkout_bitcoin']			= 'Enable Bitcoin Support: <div class="help-text">Make sure you have <a target="_blank" href="https://dashboard.stripe.com/account/bitcoin/enable">enabled the Bitcoin API</a> for your Stripe account if you want to use this option.</div>';

$_['entry_checkout_billing']			= 'Require Billing Address: <div class="help-text">If set to "No", the customer will not enter an address in the pop-up, which means no address will be stored or validated in Stripe.</div>';

$_['entry_checkout_shipping']			= 'Require Shipping Address: <div class="help-text">Only enable this setting if using the Quick Checkout code. If set to "Yes", a shipping method must be applied to the cart first. Note that if the customer applies a shipping method using the estimator and then does not use a matching shipping address in the Stipe Checkout pop-up, the charge will not be processed, to prevent incorrect shipping charges.</div>';

$_['entry_checkout_image']				= 'Pop-up Logo: <div class="help-text">Enter the image path to use for your store logo in the pop-up panel. The minimum recommended size is 128 x 128 pixels.</div>';
$_['text_browse']						= 'Browse';
$_['text_clear']						= 'Clear';
$_['text_image_manager']				= 'Image Manager';

$_['entry_checkout_title']		 		= 'Pop-up Title: <div class="help-text">Enter the title that appears at the top of the pop-up panel. You can use the following shortcodes to enter information about the order: [store], [order_id], [amount], [email], [products]</div>';

$_['entry_checkout_description']		= 'Pop-up Description: <div class="help-text">Optionally enter a description that appears under the pop-up title. You can use the following shortcodes to enter information about the order: [store], [order_id], [amount], [email], [products]</div>';

$_['entry_checkout_button']				= 'Pop-up Button Text: <div class="help-text">Enter the text for the button in the pop-up panel. You can use the [amount] shortcode to enter the order amount.</div>';

$_['entry_quick_checkout']				= 'Quick Checkout:';

//------------------------------------------------------------------------------
// Subscription Products
//------------------------------------------------------------------------------
$_['tab_subscription_products']			= 'Subscription Products';
$_['heading_subscription_products']		= 'Subscription Products';
$_['help_subscription_products']		= 'Subscription products will subscribe the customer to the associated Stripe plan when they are purchased. You can associate a product with a plan by entering the Stripe plan ID in the "Location" field for the product.<br />If the subscription is not set to be charged immediately (i.e. it has a trial period), the amount of the subscription will be taken off their original order, and a new order will be created when the subscription is actually charged to their card.<br />Any time Stripe charges the subscription in the future, a corresponding order will be created in OpenCart.<br />If you have a coupon set up in your Stripe account, you can map an OpenCart coupon to it by using the same coupon code and discount amount. When a customer purchases a subscription product and uses that coupon code, it will pass the code to Stripe to properly adjust the subscription charge.';

$_['entry_subscriptions']				= 'Enable Subscription Products:';
$_['entry_prevent_guests']				= 'Prevent Guests From Purchasing: <div class="help-text">If set to "Yes", only customers with accounts in OpenCart will be allowed to checkout if a subscription product is in the cart.</div>';

$_['entry_current_subscriptions']		= 'Current Subscription Products: <div class="help-text">Products with mismatching prices are highlighted. The customer will always be charged the Stripe plan price, not the OpenCart product price, so you should make sure the price in OpenCart corresponds to the price in Stripe.<br /><br />Note: only plans for your Transaction Mode will be listed. You are currently set to "[transaction_mode]" mode.</div>';

$_['text_thead_opencart']				= 'OpenCart';
$_['text_thead_stripe']					= 'Stripe';
$_['text_product_name']					= 'Product Name';
$_['text_product_price']				= 'Product Price';
$_['text_location_plan_id']				= 'Location / Plan ID';
$_['text_plan_name']					= 'Plan Name';
$_['text_plan_interval']				= 'Plan Interval';
$_['text_plan_charge']					= 'Plan Charge';
$_['text_no_subscription_products']		= 'No Subscription Products';
$_['text_create_one_by_entering']		= 'Create one by entering the Stripe plan ID in the "Location" field for the product';

//------------------------------------------------------------------------------
// Standard Text
//------------------------------------------------------------------------------
$_['copyright']							= '<hr /><div class="text-center" style="margin: 15px">' . $_['heading_title'] . ' (' . $version . ') &copy; <a target="_blank" href="http://www.getclearthinking.com">Clear Thinking, LLC</a></div>';

$_['standard_confirm']					= 'This operation cannot be undone. Continue?';
$_['standard_error']					= '<strong>Error:</strong> You do not have permission to modify ' . $_['heading_title'] . '!';
$_['standard_warning']					= '<strong>Warning:</strong> The number of settings is close to your <code>max_input_vars</code> server value. You should enable auto-saving to avoid losing any data.';
$_['standard_please_wait']				= 'Please wait...';
$_['standard_saving']					= 'Saving...';
$_['standard_saved']					= 'Saved!';
$_['standard_select']					= '--- Select ---';
$_['standard_success']					= 'Success!';

$_['standard_module']					= 'Modules';
$_['standard_shipping']					= 'Shipping';
$_['standard_payment']					= 'Payments';
$_['standard_total']					= 'Order Totals';
$_['standard_feed']						= 'Product Feeds';
$_['text_delete']						= 'Delete';
?>