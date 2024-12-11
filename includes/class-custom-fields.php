<?php
namespace DynamicCheckout;

// Abstract Base Class for Custom Fields
abstract class BaseCustomField {
    protected $fields;
    protected $metaKey;

    public function __construct($fields) {
        $this->fields = $fields;
    }

    abstract public function validate_custom_checkout_field();

    public function update_order_meta($order_id) {
        if (!empty($_POST[$this->metaKey])) {
            $order = wc_get_order($order_id);
            $order->update_meta_data($this->metaKey, sanitize_text_field($_POST[$this->metaKey]));
            $order->save_meta_data();
        }
    }
}

// Book Custom Field
class BookCustomField extends BaseCustomField {
    public function add_fields($fields) {
        $fields['billing']['billing_favorite_book_type'] = array(
            'type'        => 'text',
            'label'       => __('Favorite Book Genre', 'woocommerce'),
            'placeholder' => __('Enter your favorite book genre', 'woocommerce'),
            'required'    => true,
            'class'       => array('form-row-wide'),
            'clear'       => true,
        );
        return $fields;
    }

    public function validate_custom_checkout_field() {
        if (empty($_POST['billing_favorite_book_type'])) {
            wc_add_notice(__('Please enter your favorite book genre.'), 'error');
        }
    }
}

// Clothing Custom Field
class ClothingCustomField extends BaseCustomField {
    public function add_fields($fields) {
        $fields['billing']['billing_wear_style'] = array(
            'type'        => 'text',
            'label'       => __('Wear Style', 'woocommerce'),
            'placeholder' => __('Describe your wear style', 'woocommerce'),
            'required'    => true,
            'class'       => array('form-row-wide'),
            'clear'       => true,
        );
        return $fields;
    }

    public function validate_custom_checkout_field() {
        if (empty($_POST['billing_wear_style'])) {
            wc_add_notice(__('Please describe your wear style.'), 'error');
        }
    }
}

// Electronics Custom Field
class ElectronicsCustomField extends BaseCustomField {
    public function add_fields($fields) {
        $fields['billing']['billing_operating_system'] = array(
            'type'        => 'text',
            'label'       => __('Operating System', 'woocommerce'),
            'placeholder' => __('Enter your operating system', 'woocommerce'),
            'required'    => true,
            'class'       => array('form-row-wide'),
            'clear'       => true,
        );
        return $fields;
    }

    public function validate_custom_checkout_field() {
        if (empty($_POST['billing_operating_system'])) {
            wc_add_notice(__('Please enter your operating system.'), 'error');
        }
    }
}
?>
