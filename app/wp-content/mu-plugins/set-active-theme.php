<?php
/*
Plugin Name: Force Storefront Theme
Description: Forces Storefront as default theme for WooCommerce testing
*/
add_filter("pre_option_template", function() { return "storefront"; });
add_filter("pre_option_stylesheet", function() { return "storefront"; });
add_filter("pre_option_current_theme", function() { return "Storefront"; });
