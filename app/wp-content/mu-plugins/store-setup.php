<?php
/**
 * Plugin Name: Auto Store Setup
 * Description: Automatically sets up WooCommerce store with demo products, pages and navigation
 */

// Force Storefront theme
add_filter('pre_option_template', function() { return 'storefront'; });
add_filter('pre_option_stylesheet', function() { return 'storefront'; });
add_filter('pre_option_current_theme', function() { return 'Storefront'; });

// Run setup after WordPress fully loads
add_action('wp_loaded', 'auto_store_setup_run', 1);

function auto_store_setup_run() {
    if (!function_exists('WC') && !class_exists('WooCommerce')) return;
    if (get_option('auto_store_setup_done_v3')) return;

    // --- Activate WooCommerce plugin ---
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        activate_plugin('woocommerce/woocommerce.php');
    }

    // --- Site Settings ---
    update_option('blogname', 'ShopZone - Premium Store');
    update_option('blogdescription', 'Discover amazing products at unbeatable prices');

    // --- Create Pages ---
    $pages = [
        'home'        => ['Home',       '<div class="hero-section" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:80px 40px;text-align:center;border-radius:12px;margin-bottom:40px"><h1 style="font-size:3em;margin-bottom:16px">Welcome to ShopZone</h1><p style="font-size:1.4em;opacity:.9">Premium products delivered to your door</p><a href="/shop" style="background:#fff;color:#764ba2;padding:16px 40px;border-radius:50px;text-decoration:none;font-weight:700;font-size:1.1em;display:inline-block;margin-top:24px">Shop Now</a></div>[products limit="8" columns="4" orderby="date"]'],
        'shop'        => ['Shop',       '[products limit="12" columns="3" orderby="date"]'],
        'about'       => ['About Us',   '<div style="max-width:800px;margin:auto"><h1>About ShopZone</h1><p style="font-size:1.1em;line-height:1.8">We are a premium e-commerce store offering a wide range of products including electronics, clothing, and home goods. Our mission is to deliver quality products at the best prices with fast shipping.</p><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:30px;margin-top:40px"><div style="text-align:center;padding:30px;background:#f8f9fa;border-radius:12px"><h3 style="color:#764ba2">10K+</h3><p>Happy Customers</p></div><div style="text-align:center;padding:30px;background:#f8f9fa;border-radius:12px"><h3 style="color:#764ba2">500+</h3><p>Products</p></div><div style="text-align:center;padding:30px;background:#f8f9fa;border-radius:12px"><h3 style="color:#764ba2">50+</h3><p>Brands</p></div></div></div>'],
        'contact'     => ['Contact',    '<div style="max-width:700px;margin:auto"><h1>Contact Us</h1><p>Have a question? We\'d love to hear from you.</p><div style="background:#f8f9fa;padding:40px;border-radius:12px;margin-top:30px"><p><strong>📧 Email:</strong> support@shopzone.com</p><p><strong>📞 Phone:</strong> +1 (800) 123-4567</p><p><strong>🕒 Hours:</strong> Mon–Fri, 9am–6pm</p><p><strong>📍 Address:</strong> 123 Commerce Street, New York, NY 10001</p></div>[contact-form][contact-field label="Name" type="name" required="true" /][contact-field label="Email" type="email" required="true" /][contact-field label="Message" type="textarea" required="true" /][/contact-form]'],
        'faq'         => ['FAQ',        '<div style="max-width:800px;margin:auto"><h1>Frequently Asked Questions</h1><details style="margin:16px 0;padding:20px;background:#f8f9fa;border-radius:8px"><summary style="cursor:pointer;font-weight:700;font-size:1.05em">How long does shipping take?</summary><p style="margin-top:12px">Standard shipping takes 3–5 business days. Express shipping (1–2 days) is also available at checkout.</p></details><details style="margin:16px 0;padding:20px;background:#f8f9fa;border-radius:8px"><summary style="cursor:pointer;font-weight:700;font-size:1.05em">What is your return policy?</summary><p style="margin-top:12px">We offer a 30-day hassle-free return policy. Items must be in original condition. Contact us to start a return.</p></details><details style="margin:16px 0;padding:20px;background:#f8f9fa;border-radius:8px"><summary style="cursor:pointer;font-weight:700;font-size:1.05em">Do you ship internationally?</summary><p style="margin-top:12px">Yes! We ship to over 80 countries worldwide. International shipping takes 7–14 business days.</p></details><details style="margin:16px 0;padding:20px;background:#f8f9fa;border-radius:8px"><summary style="cursor:pointer;font-weight:700;font-size:1.05em">How can I track my order?</summary><p style="margin-top:12px">Once your order ships, you will receive a tracking number via email. You can use it on our Order Tracking page.</p></details><details style="margin:16px 0;padding:20px;background:#f8f9fa;border-radius:8px"><summary style="cursor:pointer;font-weight:700;font-size:1.05em">What payment methods do you accept?</summary><p style="margin-top:12px">We accept all major credit cards (Visa, Mastercard, Amex), PayPal, and Apple Pay.</p></details></div>'],
    ];

    $page_ids = [];
    foreach ($pages as $slug => $data) {
        $existing = get_page_by_path($slug);
        if ($existing) {
            $page_ids[$slug] = $existing->ID;
        } else {
            $page_ids[$slug] = wp_insert_post([
                'post_title'   => $data[0],
                'post_content' => $data[1],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_name'    => $slug,
            ]);
        }
    }

    // Set homepage
    if (!empty($page_ids['home'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_ids['home']);
    }

    // WooCommerce: set shop, cart, checkout, my-account pages
    $wc_pages = [
        'shop'     => ['woocommerce_shop_page_id',     'Shop'],
        'cart'     => ['woocommerce_cart_page_id',     'Cart'],
        'checkout' => ['woocommerce_checkout_page_id', 'Checkout'],
        'myaccount'=> ['woocommerce_myaccount_page_id','My Account'],
    ];
    foreach ($wc_pages as $slug => [$option, $title]) {
        $existing = get_page_by_path($slug);
        $pid = $existing ? $existing->ID : wp_insert_post([
            'post_title'   => $title,
            'post_content' => $slug === 'cart' ? '[woocommerce_cart]' : ($slug === 'checkout' ? '[woocommerce_checkout]' : ($slug === 'myaccount' ? '[woocommerce_my_account]' : '')),
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_name'    => $slug,
        ]);
        update_option($option, $pid);
        $page_ids[$slug] = $pid;
    }

    // --- Product Categories ---
    $cats = [
        'Electronics' => 'electronics',
        'Clothing'    => 'clothing',
        'Home & Garden'=> 'home-garden',
        'Sports'      => 'sports',
    ];
    $cat_ids = [];
    foreach ($cats as $name => $slug) {
        $term = get_term_by('slug', $slug, 'product_cat');
        if (!$term) {
            $res = wp_insert_term($name, 'product_cat', ['slug' => $slug]);
            $cat_ids[$slug] = is_wp_error($res) ? 0 : $res['term_id'];
        } else {
            $cat_ids[$slug] = $term->term_id;
        }
    }

    // --- Demo Products ---
    $products = [
        ['Wireless Noise-Cancelling Headphones', '149.99', '199.99', 'electronics', 'Experience crystal-clear audio with active noise cancellation. 30-hour battery life, premium sound quality, foldable design perfect for travel.', 4.8, 124],
        ['Smart Watch Pro X',                   '299.99', '399.99', 'electronics', 'Stay connected with this premium smartwatch. Features heart rate monitoring, GPS, sleep tracking, and 7-day battery life.', 4.7, 89],
        ['4K Wireless Webcam',                  '89.99',  '119.99', 'electronics', 'Ultra HD 4K webcam with built-in microphone and noise cancellation. Perfect for video calls, streaming and content creation.', 4.6, 203],
        ['Premium Cotton Hoodie',               '59.99',  '79.99',  'clothing',    'Ultra-soft 100% organic cotton hoodie. Available in 8 colors. Machine washable, pre-shrunk, relaxed fit for all-day comfort.', 4.9, 512],
        ['Athletic Running Shoes',              '129.99', '159.99', 'sports',      'Lightweight performance running shoes with responsive cushioning. Breathable mesh upper, durable rubber sole, ideal for road and trail running.', 4.8, 341],
        ['Slim Fit Chino Pants',                '49.99',  '64.99',  'clothing',    'Classic slim-fit chinos in stretch fabric for ultimate comfort. Perfect for casual or smart-casual occasions. Available in 6 colors.', 4.5, 278],
        ['Aromatherapy Diffuser',               '34.99',  '49.99',  'home-garden', 'Ultrasonic essential oil diffuser with 7 LED color modes. Covers up to 300 sq ft. Auto shut-off, whisper-quiet operation.', 4.7, 467],
        ['Bamboo Desk Organizer Set',           '29.99',  '39.99',  'home-garden', 'Eco-friendly bamboo desk organizer with 5 compartments. Keeps your workspace tidy and stylish. Great gift idea.', 4.6, 189],
        ['Yoga Mat Premium',                    '44.99',  '59.99',  'sports',      'Non-slip 6mm thick yoga mat with alignment lines. Eco-friendly TPE material, includes carry strap. Perfect for yoga, pilates and stretching.', 4.8, 623],
        ['Stainless Steel Water Bottle',        '24.99',  '34.99',  'sports',      'Vacuum insulated stainless steel bottle keeps drinks cold 24 hrs, hot 12 hrs. BPA-free, leak-proof lid. 32oz capacity.', 4.9, 891],
    ];

    foreach ($products as [$title, $price, $regular, $cat_slug, $desc, $rating, $count]) {
        if (get_page_by_title($title, OBJECT, 'product')) continue;

        $product = new WC_Product_Simple();
        $product->set_name($title);
        $product->set_description($desc);
        $product->set_short_description(wp_trim_words($desc, 15));
        $product->set_regular_price($regular);
        $product->set_sale_price($price);
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        $product->set_manage_stock(true);
        $product->set_stock_quantity(rand(20, 200));
        $product->set_average_rating($rating);
        $product->set_review_count($count);

        if (!empty($cat_ids[$cat_slug])) {
            $product->set_category_ids([$cat_ids[$cat_slug]]);
        }

        $product->save();
    }

    // --- Navigation Menu ---
    $menu_name = 'Main Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);
    if ($menu_exists) wp_delete_nav_menu($menu_exists->term_id);

    $menu_id = wp_create_nav_menu($menu_name);
    $menu_items = [
        ['Home',        $page_ids['home']   ?? 0],
        ['Shop',        $page_ids['shop']   ?? 0],
        ['About Us',    $page_ids['about']  ?? 0],
        ['Contact',     $page_ids['contact']?? 0],
        ['FAQ',         $page_ids['faq']    ?? 0],
    ];
    foreach ($menu_items as [$label, $pid]) {
        if (!$pid) continue;
        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-title'     => $label,
            'menu-item-object'    => 'page',
            'menu-item-object-id' => $pid,
            'menu-item-type'      => 'post_type',
            'menu-item-status'    => 'publish',
        ]);
    }

    // Assign menu to primary location
    $locations = get_theme_mod('nav_menu_locations') ?: [];
    $locations['primary'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);

    // --- WooCommerce Settings ---
    update_option('woocommerce_currency', 'USD');
    update_option('woocommerce_enable_reviews', 'yes');
    update_option('woocommerce_enable_review_rating', 'yes');
    update_option('woocommerce_catalog_columns', 3);
    update_option('woocommerce_catalog_rows', 4);

    // Storefront customizer
    set_theme_mod('storefront_header_background_color', '#1a1a2e');
    set_theme_mod('storefront_header_text_color', '#ffffff');
    set_theme_mod('storefront_header_link_color', '#e0e0e0');
    set_theme_mod('storefront_accent_color', '#764ba2');
    set_theme_mod('storefront_button_background_color', '#764ba2');
    set_theme_mod('storefront_button_text_color', '#ffffff');

    // Flush rewrite rules
    flush_rewrite_rules();

    update_option('auto_store_setup_done_v3', true);
}
