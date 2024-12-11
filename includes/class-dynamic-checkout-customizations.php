<?php

namespace DynamicCheckout;

class WC_Dynamic_Checkout_Customizations {
    
    public function __construct() {
        add_filter('woocommerce_checkout_fields', [$this, 'customize_checkout_fields']);
        add_action('woocommerce_checkout_after_order_review', [$this, 'add_dynamic_recommendation_section']);
        add_action('woocommerce_checkout_process', [$this, 'validate_checkout_fields']);
        add_action('woocommerce_checkout_update_order_meta', [$this, 'update_checkout_order_meta']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
    }

    public function enqueue_styles() {
        wp_enqueue_style('dynamic-checkout-styles', plugin_dir_url(__FILE__) . '../assets/styles.css');
    }
    
    public function customize_checkout_fields($fields) {
        $cart_items = WC()->cart->get_cart();
        foreach ($cart_items as $item) {
            $product = $item['data'];
            $customField = CustomFieldFactory::create($product->get_type(), $fields);
            if ($customField !== null) {
                $fields = $customField->add_fields($fields);
            }
        }
        return $fields;
    }

    public function validate_checkout_fields() {
        $cart_items = WC()->cart->get_cart();
        foreach ($cart_items as $item) {
            $product = $item['data'];
            $customField = CustomFieldFactory::create($product->get_type(), []);
            if ($customField !== null) {
                $customField->validate_custom_checkout_field();
            }
        }
    }

    public function update_checkout_order_meta($order_id) {
        $cart_items = WC()->cart->get_cart();
        foreach ($cart_items as $item) {
            $product = $item['data'];
            $customField = CustomFieldFactory::create($product->get_type(), []);
            if ($customField !== null) {
                $customField->update_order_meta($order_id);
            }
        }
    }
    public function get_recommended_products($purchased_products) {
        $recommended_products = [];
        foreach ($purchased_products as $product_id) {
            $product = wc_get_product($product_id);
            $categories = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'slugs']);

            if (!empty($categories)) {
                $query_args = [
                    'post_type' => 'product',
                    'posts_per_page' => 4,
                    'post__not_in' => $purchased_products,
                    'tax_query' => [
                        [
                            'taxonomy' => 'product_cat',
                            'field'    => 'slug',
                            'terms'    => $categories,
                        ],
                    ],
                ];
                $query = new \WP_Query($query_args);

                if ($query->have_posts()) {
                    foreach ($query->posts as $post) {
                        $recommended_products[] = $post->ID;
                    }
                }
            }
        }

        return array_unique($recommended_products);
    }
    public function add_dynamic_recommendation_section() {
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            $purchased_products = $this->get_customer_purchased_products($user_id);
            $recommended_products = $this->get_recommended_products($purchased_products);
            if (!empty($recommended_products)) {
                echo '<div class="recommendation-section">';
                echo '<h3>You may also like:</h3>';
                echo '<ul class="recommended-products">';
                foreach ($recommended_products as $product) {
                    $product_obj = wc_get_product($product);
                    echo '<li>';
                    echo '<a href="' . get_permalink($product) . '">';
                    echo $product_obj->get_image();
                    echo '<h4>' . $product_obj->get_name() . '</h4>';
                    echo '<p>' . wc_price($product_obj->get_price()) . '</p>';
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul></div>';
            }
        }
    }


    private function get_customer_purchased_products($user_id) {
        $purchased_products = [];
        $orders = wc_get_orders(['customer_id' => $user_id, 'status' => ['completed', 'processing'], 'limit' => -1]);
        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                $purchased_products[] = $item->get_product_id();
            }
        }
        return array_unique($purchased_products);
    }
}
?>
