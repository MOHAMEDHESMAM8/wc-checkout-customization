# WooCommerce Dynamic Checkout Customizations Plugin

## Description
The WooCommerce Dynamic Checkout Customizations plugin allows you to personalize the WooCommerce checkout process based on the products in the customer's cart. It provides dynamic recommendations, custom checkout fields, and order meta updates to enhance user experience and engagement.

## Features
- **Dynamic Checkout Fields**: Automatically add custom checkout fields based on the products in the cart.
- **Dynamic Recommendations**: Display recommended products during checkout to boost cross-selling opportunities.
- **Validation and Meta Updates**: Validate custom checkout fields and save the data to order metadata.
- **Custom Styling**: Enqueue custom styles for the checkout page.

## Plugin Structure
The plugin follows a structured and modular approach to ensure scalability and maintainability. Key components include:

- **Main Plugin File**: Initializes the plugin and loads required dependencies.
- **Includes Directory**: Contains core classes:
  - `class-custom-fields.php`: Handles custom field logic.
  - `class-custom-field-factory.php`: Implements the Factory design pattern to dynamically create custom fields based on product types.
  - `class-dynamic-checkout-customizations.php`: Main class that manages the dynamic checkout customizations.
- **Assets Directory**: Stores CSS styles for checkout customizations.

## Object-Oriented Programming (OOP) Concepts
The plugin is built using modern OOP principles to enhance code readability and maintainability:
- **Encapsulation**: Keeps methods and properties organized within classes.
- **Inheritance**: Allows custom field classes to extend base functionality.
- **Interfaces**: Ensures that all custom field classes implement a consistent interface for adding and validating fields.
- **Factory Design Pattern**: Dynamically generates appropriate custom field objects based on the product type.

## Installation
1. Download the plugin and unzip the folder.
2. Upload the plugin folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage
1. Customize the checkout fields dynamically based on the cart items.
2. Display dynamic product recommendations below the order review section during checkout.
3. Save and validate custom checkout field data for better order handling.

## Code Highlights
### Main Functionalities
#### Enqueue Styles
```php
public function enqueue_styles() {
    wp_enqueue_style('dynamic-checkout-styles', plugin_dir_url(__FILE__) . '../assets/styles.css');
}
```
Adds custom styles to the checkout page.

#### Customize Checkout Fields
```php
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
```
Dynamically adds custom fields based on the products in the cart.

#### Dynamic Recommendations
```php
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
```
Displays a recommendation section with products that match the user's purchase history.

## Requirements
- WordPress 5.0 or higher
- WooCommerce 4.0 or higher

## Support
For support, please open an issue in the [GitHub repository](https://github.com/your-repo-link).

## Contributing
1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Open a Pull Request.

## License
This plugin is licensed under the MIT License. See the LICENSE file for details.

---

Enjoy customizing your WooCommerce checkout with the WooCommerce Dynamic Checkout Customizations plugin!

