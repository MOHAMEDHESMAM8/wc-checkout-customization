<?php
namespace DynamicCheckout;

class CustomFieldFactory {
    public static function create($product_type, $fields) {
        switch ($product_type) {
            case 'book':
                return new BookCustomField($fields);
            case 'clothe':
                return new ClothingCustomField($fields);
            case 'electronic':
                return new ElectronicsCustomField($fields);
            default:
                return null;
        }
    }
}
