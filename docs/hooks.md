# Hooks

- [Actions](#actions)
- [Filters](#filters)

## Actions

### `pronamic_pay_update_payment`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$payment` |  | 

Source: [src/Payments/PaymentsDataStoreCPT.php](../src/Payments/PaymentsDataStoreCPT.php), [line 239](../src/Payments/PaymentsDataStoreCPT.php#L239-L239)

### `pronamic_pay_new_payment`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$payment` |  | 

Source: [src/Payments/PaymentsDataStoreCPT.php](../src/Payments/PaymentsDataStoreCPT.php), [line 281](../src/Payments/PaymentsDataStoreCPT.php#L281-L281)

### `pronamic_payment_status_update_{$payment->source}_{$old}_to_{$new}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$payment` |  | 
`$can_redirect` |  | 
`$previous_status` |  | 
`$payment->status` |  | 

Source: [src/Payments/PaymentsDataStoreCPT.php](../src/Payments/PaymentsDataStoreCPT.php), [line 882](../src/Payments/PaymentsDataStoreCPT.php#L882-L882)

### `pronamic_payment_status_update_{$payment->source}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$payment` |  | 
`$can_redirect` |  | 
`$previous_status` |  | 
`$payment->status` |  | 

Source: [src/Payments/PaymentsDataStoreCPT.php](../src/Payments/PaymentsDataStoreCPT.php), [line 883](../src/Payments/PaymentsDataStoreCPT.php#L883-L883)

### `pronamic_payment_status_update`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$payment` |  | 
`$can_redirect` |  | 
`$previous_status` |  | 
`$payment->status` |  | 

Source: [src/Payments/PaymentsDataStoreCPT.php](../src/Payments/PaymentsDataStoreCPT.php), [line 884](../src/Payments/PaymentsDataStoreCPT.php#L884-L884)

### `pronamic_pay_privacy_register_exporters`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this` |  | 

Source: [src/PrivacyManager.php](../src/PrivacyManager.php), [line 52](../src/PrivacyManager.php#L52-L52)

### `pronamic_pay_privacy_register_erasers`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$this` |  | 

Source: [src/PrivacyManager.php](../src/PrivacyManager.php), [line 68](../src/PrivacyManager.php#L68-L68)

### `pronamic_pay_license_check`


Source: [src/Admin/AdminSettings.php](../src/Admin/AdminSettings.php), [line 288](../src/Admin/AdminSettings.php#L288-L288)

### `pronamic_subscription_renewal_notice_{$source}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$subscription` |  | 

Source: [src/Subscriptions/SubscriptionsModule.php](../src/Subscriptions/SubscriptionsModule.php), [line 1012](../src/Subscriptions/SubscriptionsModule.php#L1012-L1012)

### `pronamic_pay_new_subscription`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$subscription` |  | 

Source: [src/Subscriptions/SubscriptionsDataStoreCPT.php](../src/Subscriptions/SubscriptionsDataStoreCPT.php), [line 279](../src/Subscriptions/SubscriptionsDataStoreCPT.php#L279-L279)

### `pronamic_subscription_status_update_{$subscription->source}_{$old}_to_{$new}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$subscription` |  | 
`$can_redirect` |  | 
`$previous_status` |  | 
`$subscription->status` |  | 

Source: [src/Subscriptions/SubscriptionsDataStoreCPT.php](../src/Subscriptions/SubscriptionsDataStoreCPT.php), [line 741](../src/Subscriptions/SubscriptionsDataStoreCPT.php#L741-L741)

### `pronamic_subscription_status_update_{$subscription->source}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$subscription` |  | 
`$can_redirect` |  | 
`$previous_status` |  | 
`$subscription->status` |  | 

Source: [src/Subscriptions/SubscriptionsDataStoreCPT.php](../src/Subscriptions/SubscriptionsDataStoreCPT.php), [line 742](../src/Subscriptions/SubscriptionsDataStoreCPT.php#L742-L742)

### `pronamic_subscription_status_update`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$subscription` |  | 
`$can_redirect` |  | 
`$previous_status` |  | 
`$subscription->status` |  | 

Source: [src/Subscriptions/SubscriptionsDataStoreCPT.php](../src/Subscriptions/SubscriptionsDataStoreCPT.php), [line 743](../src/Subscriptions/SubscriptionsDataStoreCPT.php#L743-L743)

## Filters

### `pronamic_payment_source_text_{$source}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$text` |  | 
`$this` |  | 

Source: [src/Payments/Payment.php](../src/Payments/Payment.php), [line 332](../src/Payments/Payment.php#L332-L332)

### `pronamic_payment_source_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$text` |  | 
`$this` |  | 

Source: [src/Payments/Payment.php](../src/Payments/Payment.php), [line 333](../src/Payments/Payment.php#L333-L333)

### `pronamic_payment_redirect_url`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$url` |  | 
`$this` |  | 

Source: [src/Payments/Payment.php](../src/Payments/Payment.php), [line 579](../src/Payments/Payment.php#L579-L579)

### `pronamic_payment_source_description`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$description` |  | 
`$this` |  | 

Source: [src/Payments/Payment.php](../src/Payments/Payment.php), [line 624](../src/Payments/Payment.php#L624-L624)

### `pronamic_payment_source_description_{$this->source}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$description` |  | 
`$this` |  | 

Source: [src/Payments/Payment.php](../src/Payments/Payment.php), [line 625](../src/Payments/Payment.php#L625-L625)

### `pronamic_payment_source_url`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$url` |  | 
`$this` |  | 

Source: [src/Payments/Payment.php](../src/Payments/Payment.php), [line 638](../src/Payments/Payment.php#L638-L638)

### `pronamic_payment_source_url_{$this->source}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$url` |  | 
`$this` |  | 

Source: [src/Payments/Payment.php](../src/Payments/Payment.php), [line 639](../src/Payments/Payment.php#L639-L639)

### `pronamic_payment_provider_url`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$url` |  | 
`$this` |  | 

Source: [src/Payments/Payment.php](../src/Payments/Payment.php), [line 652](../src/Payments/Payment.php#L652-L652)

### `pronamic_payment_provider_url_{$gateway_id}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$url` |  | 
`$this` |  | 

Source: [src/Payments/Payment.php](../src/Payments/Payment.php), [line 670](../src/Payments/Payment.php#L670-L670)

### `wp_doing_cron`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`defined('DOING_CRON') && DOING_CRON` |  | 

Source: [src/Core/Util.php](../src/Core/Util.php), [line 141](../src/Core/Util.php#L141-L141)

### `pronamic_pay_google_analytics_ecommerce_item_name`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$line->get_name()` |  | 
`$line` |  | 

Source: [src/GoogleAnalyticsEcommerce.php](../src/GoogleAnalyticsEcommerce.php), [line 211](../src/GoogleAnalyticsEcommerce.php#L211-L211)

### `pronamic_pay_google_analytics_ecommerce_item_product_category`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$line->get_product_category()` |  | 
`$line` |  | 

Source: [src/GoogleAnalyticsEcommerce.php](../src/GoogleAnalyticsEcommerce.php), [line 247](../src/GoogleAnalyticsEcommerce.php#L247-L247)

### `pronamic_pay_return_should_redirect`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`true` |  | 
`$payment` |  | 

Source: [src/Plugin.php](../src/Plugin.php), [line 434](../src/Plugin.php#L434-L434)

### `pronamic_pay_gateways`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`array()` |  | 

Source: [src/Plugin.php](../src/Plugin.php), [line 591](../src/Plugin.php#L591-L591)

### `pronamic_pay_plugin_integrations`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`array()` |  | 

Source: [src/Plugin.php](../src/Plugin.php), [line 600](../src/Plugin.php#L600-L600)

### `pronamic_payment_gateway_configuration_id`

*Filters the payment gateway configuration ID.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$payment->get_config_id()` |  | 
`$payment` | `\Pronamic\WordPress\Pay\Payments\Payment` | The payment resource data.

Source: [src/Plugin.php](../src/Plugin.php), [line 963](../src/Plugin.php#L963-L969)

### `pronamic_payment_redirect_url_{$source}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$url` |  | 
`$payment` |  | 

Source: [src/Plugin.php](../src/Plugin.php), [line 1163](../src/Plugin.php#L1163-L1163)

### `pronamic_gateway_configuration_display_value`

*Filters the gateway configuration display value.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$display_value` | `string` | Display value.
`$post_id` | `int` | Gateway configuration post ID.

Source: [src/Admin/AdminGatewayPostType.php](../src/Admin/AdminGatewayPostType.php), [line 139](../src/Admin/AdminGatewayPostType.php#L139-L145)

### `pronamic_gateway_configuration_display_value_{$id}`

*Filters the gateway configuration display value.*

The dynamic portion of the hook name, `$id`, refers to the gateway ID.
For example, the gateway ID for Payvision is `payvision`, se the filter
for that gateway would be:
`pronamic_gateway_configuration_display_value_payvision`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$display_value` | `string` | Display value.
`$post_id` | `int` | Gateway configuration post ID.

Source: [src/Admin/AdminGatewayPostType.php](../src/Admin/AdminGatewayPostType.php), [line 147](../src/Admin/AdminGatewayPostType.php#L147-L158)

### `pronamic_subscription_source_text_{$source}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$default_text` |  | 
`$this` |  | 

Source: [src/Subscriptions/Subscription.php](../src/Subscriptions/Subscription.php), [line 367](../src/Subscriptions/Subscription.php#L367-L367)

### `pronamic_subscription_source_text`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$text` |  | 
`$this` |  | 

Source: [src/Subscriptions/Subscription.php](../src/Subscriptions/Subscription.php), [line 368](../src/Subscriptions/Subscription.php#L368-L368)

### `pronamic_subscription_source_description_{$source}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$default_text` |  | 
`$this` |  | 

Source: [src/Subscriptions/Subscription.php](../src/Subscriptions/Subscription.php), [line 383](../src/Subscriptions/Subscription.php#L383-L383)

### `pronamic_subscription_source_description`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$text` |  | 
`$this` |  | 

Source: [src/Subscriptions/Subscription.php](../src/Subscriptions/Subscription.php), [line 384](../src/Subscriptions/Subscription.php#L384-L384)

### `pronamic_subscription_source_url`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$url` |  | 
`$this` |  | 

Source: [src/Subscriptions/Subscription.php](../src/Subscriptions/Subscription.php), [line 397](../src/Subscriptions/Subscription.php#L397-L397)

### `pronamic_subscription_source_url_{$this->source}`

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$url` |  | 
`$this` |  | 

Source: [src/Subscriptions/Subscription.php](../src/Subscriptions/Subscription.php), [line 398](../src/Subscriptions/Subscription.php#L398-L398)

### `pronamic_pay_subscription_next_payment_delivery_date`

*Filters the subscription next payment delivery date.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$next_payment_delivery_date` | `\Pronamic\WordPress\DateTime\DateTime` | Next payment delivery date.
`$subscription` | `\Pronamic\WordPress\Pay\Subscriptions\Subscription` | Subscription.

Source: [src/Subscriptions/SubscriptionHelper.php](../src/Subscriptions/SubscriptionHelper.php), [line 246](../src/Subscriptions/SubscriptionHelper.php#L246-L254)


<p align="center"><a href="https://github.com/pronamic/wp-documentor"><img src="https://cdn.jsdelivr.net/gh/pronamic/wp-documentor@main/logos/pronamic-wp-documentor.svgo-min.svg" alt="Pronamic WordPress Documentor" width="32" height="32"></a><br><em>Generated by <a href="https://github.com/pronamic/wp-documentor">Pronamic WordPress Documentor</a> <code>1.1.0</code></em><p>

