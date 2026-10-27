<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Admin User (from .env)
        $adminEmail = env('ADMIN_EMAIL', 'admin@foxal.com');
        $adminPassword = env('ADMIN_PASSWORD', 'Admin@1234');
        $adminName = env('ADMIN_NAME', 'Foxal Admin');

        $admin = User::updateOrCreate(
            ['email' => strtolower($adminEmail)],
            [
                'name' => $adminName,
                'phone' => '9876543210',
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
                'is_active' => true,
                'last_login_at' => now(),
            ]
        );

        // Seed Sample Customer User
        $customer = User::updateOrCreate(
            ['email' => 'customer@foxal.com'],
            [
                'name' => 'Aarav Sharma',
                'phone' => '9812345678',
                'password' => Hash::make('Customer@1234'),
                'role' => 'customer',
                'is_active' => true,
                'last_login_at' => now(),
            ]
        );

        // Seed Sample Address for Customer
        Address::updateOrCreate(
            ['user_id' => $customer->id, 'city' => 'Mumbai'],
            [
                'full_name' => 'Aarav Sharma',
                'phone' => '9812345678',
                'line1' => 'Penthouse 1402, Royale Crest',
                'line2' => 'Bandra West',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400050',
                'is_default' => true,
            ]
        );

        // 2. Seed the 2 Required Sections/Categories: Incense Cones & Incense Sticks
        $catCones = Category::updateOrCreate(
            ['slug' => 'incense-cones'],
            [
                'name' => 'Incense Cones',
                'description' => 'Artisanal dhoop cones crafted with temple botanicals, natural resins, and pure essential oils.',
                'image' => 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $catSticks = Category::updateOrCreate(
            ['slug' => 'incense-sticks'],
            [
                'name' => 'Incense Sticks',
                'description' => 'Hand-rolled luxury agarbatti sticks infused with rare sandalwood, frankincense, and agarwood.',
                'image' => 'https://images.unsplash.com/photo-1602928321679-560bb453f190?w=800',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        // Reassign any old products to cones/sticks, then delete other categories
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::whereNotIn('id', [$catCones->id, $catSticks->id])->delete();
        Product::whereNotIn('category_id', [$catCones->id, $catSticks->id])->delete();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Seed Products with 1 Thumbnail + 2-3 Gallery Images per product
        $productsData = [
            // ================== SECTION 1: INCENSE CONES ==================
            [
                'name' => 'FOXAL Rose Natural Incense Cones',
                'slug' => 'foxal-rose-natural-incense-cones',
                'category_id' => $catCones->id,
                'description' => '100% Charcoal Free Natural Incense Cones. Inspired by Nature. Net Qty: 60 cones. Handcrafted with fresh Damask rose petals, aromatic resins, and natural essential oils. Creates a serene, romantic ambiance.',
                'fragrance' => 'Fresh Damask Rose Petals & Sweet Floral Nectar | 100% Charcoal Free',
                'brand' => 'FOXAL Natural Incense',
                'mrp' => 499.00,
                'price' => 299.00,
                'stock' => 120,
                'sku' => 'FX-CONE-ROSE-01',
                'images' => [
                    '/images/products/foxal-rose-natural-incense-cones.jpg', // Main Packaging Box Thumbnail
                ],
                'is_active' => true,
                'is_featured' => true,
                'avg_rating' => 4.95,
            ],
            [
                'name' => 'FOXAL Jasmine Natural Incense Cones',
                'slug' => 'foxal-jasmine-natural-incense-cones',
                'category_id' => $catCones->id,
                'description' => '100% Charcoal Free Natural Incense Cones. Inspired by Nature. Net Qty: 60 cones. Infused with pure blooming jasmine blossoms, calming botanicals, and holy temple essences. Elevates positivity and peace.',
                'fragrance' => 'Pure Sambac Jasmine & Blooming Night Florals | 100% Charcoal Free',
                'brand' => 'FOXAL Natural Incense',
                'mrp' => 499.00,
                'price' => 299.00,
                'stock' => 110,
                'sku' => 'FX-CONE-JASMINE-02',
                'images' => [
                    '/images/products/foxal-jasmine-natural-incense-cones.jpg', // Main Packaging Box Thumbnail
                ],
                'is_active' => true,
                'is_featured' => true,
                'avg_rating' => 4.92,
            ],
            [
                'name' => 'FOXAL Sandalwood Natural Incense Cones',
                'slug' => 'foxal-sandalwood-natural-incense-cones',
                'category_id' => $catCones->id,
                'description' => 'Backflow Incense Cones: 30N. Inspired by Nature. Soothing aroma, long-lasting, low smoke. Prepared with authentic Mysore sandalwood wood powders and holy temple botanical extracts. Ideal for meditation and prayer.',
                'fragrance' => 'Authentic Mysore Sandalwood & Rich Warm Woods | Inspired by Nature',
                'brand' => 'FOXAL Natural Incense',
                'mrp' => 549.00,
                'price' => 349.00,
                'stock' => 140,
                'sku' => 'FX-CONE-SANDAL-03',
                'images' => [
                    '/images/products/foxal-sandalwood-natural-incense-cones.jpg', // Main Packaging Box Thumbnail
                ],
                'is_active' => true,
                'is_featured' => true,
                'avg_rating' => 4.98,
            ],
            [
                'name' => 'FOXAL Lavender Natural Incense Cones',
                'slug' => 'foxal-lavender-natural-incense-cones',
                'category_id' => $catCones->id,
                'description' => '100% Charcoal Free Natural Incense Cones. Inspired by Nature. Net Qty: 60 cones. Enriched with wild French lavender flowers, therapeutic herbs, and calming floral extracts. Promotes deep relaxation and restful sleep.',
                'fragrance' => 'Calming French Lavender & Herbal Essential Oils | 100% Charcoal Free',
                'brand' => 'FOXAL Natural Incense',
                'mrp' => 499.00,
                'price' => 299.00,
                'stock' => 130,
                'sku' => 'FX-CONE-LAVENDER-04',
                'images' => [
                    '/images/products/foxal-lavender-natural-incense-cones.jpg', // Main Packaging Box Thumbnail
                ],
                'is_active' => true,
                'is_featured' => true,
                'avg_rating' => 4.94,
            ],

            // ================== SECTION 2: INCENSE STICKS ==================
            [
                'name' => 'FOXAL Mogra Premium Incense Sticks',
                'slug' => 'foxal-mogra-premium-incense-sticks',
                'category_id' => $catSticks->id,
                'description' => '100% Charcoal Free Premium Incense Sticks. Fragrance that Brings You Closer to Nature. Infused with natural Indian Mogra flowers, pure botanical extracts, and calming essential oils. Manufactured & Marketed by FOXAL Natural Incense, Ahmedabad, Gujarat.',
                'fragrance' => 'Fresh Indian Jasmine & Mogra Sambac | 100% Charcoal Free',
                'brand' => 'FOXAL Natural Incense',
                'mrp' => 499.00,
                'price' => 299.00,
                'stock' => 150,
                'sku' => 'FX-MOGRA-01',
                'images' => [
                    '/images/products/foxal-mogra-premium-incense-sticks.jpg', // Main Packaging Thumbnail
                    'https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?w=800', // White Mogra Blossoms
                    'https://images.unsplash.com/photo-1602928321679-560bb453f190?w=800', // Burning Incense Ambiance
                    'https://images.unsplash.com/photo-1594035910387-fea47794261f?w=800', // Natural Botanicals
                ],
                'is_active' => true,
                'is_featured' => true,
                'avg_rating' => 4.95,
            ],
            [
                'name' => 'FOXAL Sukhad Premium Incense Sticks',
                'slug' => 'foxal-sukhad-premium-incense-sticks',
                'category_id' => $catSticks->id,
                'description' => '100% Charcoal Free Premium Incense Sticks. Tradition in Every Fragrance. Crafted with genuine Mysore Sandalwood (Sukhad / Chandan) powder and holy temple botanical extracts. Manufactured & Marketed by FOXAL Natural Incense, Ahmedabad, Gujarat.',
                'fragrance' => 'Pure Mysore Sandalwood & Sacred Woods | 100% Charcoal Free',
                'brand' => 'FOXAL Natural Incense',
                'mrp' => 549.00,
                'price' => 349.00,
                'stock' => 140,
                'sku' => 'FX-SUKHAD-02',
                'images' => [
                    '/images/products/foxal-sukhad-premium-incense-sticks.jpg', // Main Packaging Thumbnail
                    'https://images.unsplash.com/photo-1602928321679-560bb453f190?w=800', // Sandalwood Smoke Ambiance
                    'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800', // Sandalwood Bark & Bowl
                    'https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=800', // Meditative Altar
                ],
                'is_active' => true,
                'is_featured' => true,
                'avg_rating' => 4.98,
            ],
            [
                'name' => 'FOXAL Oudh Premium Incense Sticks',
                'slug' => 'foxal-oudh-premium-incense-sticks',
                'category_id' => $catSticks->id,
                'description' => '100% Charcoal Free Premium Incense Sticks. A Fragrance for a Calmer You. Enriched with rare Assam agarwood, smoked Cambodian oudh, and amber resins for a soothing, deep aura. Manufactured & Marketed by FOXAL Natural Incense, Ahmedabad, Gujarat.',
                'fragrance' => 'Smoked Assam Oudh & Dark Amber | 100% Charcoal Free',
                'brand' => 'FOXAL Natural Incense',
                'mrp' => 599.00,
                'price' => 399.00,
                'stock' => 120,
                'sku' => 'FX-OUDH-03',
                'images' => [
                    '/images/products/foxal-oudh-premium-incense-sticks.jpg', // Main Packaging Thumbnail
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800', // Smoked Agarwood Chips
                    'https://images.unsplash.com/photo-1602928321679-560bb453f190?w=800', // Regal Oud Stick Burning
                    'https://images.unsplash.com/photo-1541643600914-78b084683601?w=800', // Oriental Luxury Setting
                ],
                'is_active' => true,
                'is_featured' => true,
                'avg_rating' => 4.92,
            ],
            [
                'name' => 'FOXAL Gugal Premium Incense Sticks',
                'slug' => 'foxal-gugal-premium-incense-sticks',
                'category_id' => $catSticks->id,
                'description' => '100% Charcoal Free Premium Incense Sticks. Pure Fragrance for a Better Tomorrow. Blended with authentic Commiphora Mukul (Sacred Gugal resin), loban, and therapeutic herbs. Manufactured & Marketed by FOXAL Natural Incense, Ahmedabad, Gujarat.',
                'fragrance' => 'Sacred Gugal Resin & Vedic Loban | 100% Charcoal Free',
                'brand' => 'FOXAL Natural Incense',
                'mrp' => 499.00,
                'price' => 299.00,
                'stock' => 160,
                'sku' => 'FX-GUGAL-04',
                'images' => [
                    '/images/products/foxal-gugal-premium-incense-sticks.jpg', // Main Packaging Thumbnail
                    'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800', // Pure Resin & Dhoop Smoke
                    'https://images.unsplash.com/photo-1602928321679-560bb453f190?w=800', // Sacred Burning Smoke
                    'https://images.unsplash.com/photo-1523293182086-7651a899d37f?w=800', // Temple Serenity
                ],
                'is_active' => true,
                'is_featured' => true,
                'avg_rating' => 4.89,
            ],
            [
                'name' => 'FOXAL Kasturi Premium Incense Sticks',
                'slug' => 'foxal-kasturi-premium-incense-sticks',
                'category_id' => $catSticks->id,
                'description' => '100% Charcoal Free Premium Incense Sticks. Pure Moments Naturally Yours. Formulated with authentic divine musk notes, sacred Himalayan herbs, and restorative botanicals. Manufactured & Marketed by FOXAL Natural Incense, Ahmedabad, Gujarat.',
                'fragrance' => 'Divine Kasturi Musk & Himalayan Herbs | 100% Charcoal Free',
                'brand' => 'FOXAL Natural Incense',
                'mrp' => 549.00,
                'price' => 349.00,
                'stock' => 130,
                'sku' => 'FX-KASTURI-05',
                'images' => [
                    '/images/products/foxal-kasturi-premium-incense-sticks.jpg', // Main Packaging Thumbnail
                ],
                'is_active' => true,
                'is_featured' => true,
                'avg_rating' => 4.96,
            ],
            [
                'name' => 'FOXAL Woods Premium Incense Sticks',
                'slug' => 'foxal-woods-premium-incense-sticks',
                'category_id' => $catSticks->id,
                'description' => '100% Charcoal Free Premium Incense Sticks. Nature in Every Breathe. Deep earthy cedarwood, crushed vetiver roots, and aged forest bark for grounded tranquility. Manufactured & Marketed by FOXAL Natural Incense, Ahmedabad, Gujarat.',
                'fragrance' => 'Forest Cedarwood, Vetiver & Earthy Barks | 100% Charcoal Free',
                'brand' => 'FOXAL Natural Incense',
                'mrp' => 529.00,
                'price' => 329.00,
                'stock' => 140,
                'sku' => 'FX-WOODS-06',
                'images' => [
                    '/images/products/foxal-woods-premium-incense-sticks.jpg', // Main Packaging Thumbnail
                ],
                'is_active' => true,
                'is_featured' => true,
                'avg_rating' => 4.94,
            ],
        ];

        // Clean up previous products to align strictly with Incense Cones and Incense Sticks
        Product::whereNotIn('slug', collect($productsData)->pluck('slug'))->forceDelete();

        foreach ($productsData as $data) {
            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            // Add standard variants
            ProductVariant::updateOrCreate(
                ['product_id' => $product->id, 'name' => 'Pack of 30'],
                [
                    'sku' => $product->sku . '-30PK',
                    'price' => $product->price,
                    'mrp' => $product->mrp,
                    'stock' => 60,
                    'is_active' => true,
                ]
            );

            ProductVariant::updateOrCreate(
                ['product_id' => $product->id, 'name' => 'Pack of 60 (Gift Box)'],
                [
                    'sku' => $product->sku . '-60PK',
                    'price' => round($product->price * 1.8, 2),
                    'mrp' => round($product->mrp * 1.8, 2),
                    'stock' => 40,
                    'is_active' => true,
                ]
            );

            Review::updateOrCreate(
                ['product_id' => $product->id, 'user_id' => $customer->id],
                [
                    'rating' => 5,
                    'title' => 'Pure luxury incense experience',
                    'body' => 'Slow burn, no black smoke, and the fragrance lingers for the whole day. Premium quality packaging.',
                    'is_approved' => true,
                ]
            );
        }

        // 4. Seed Sample Coupons
        Coupon::updateOrCreate(
            ['code' => 'WELCOME10'],
            [
                'type' => 'percent',
                'value' => 10.00,
                'min_order_amount' => 300.00,
                'max_discount' => 200.00,
                'usage_limit' => 500,
                'usage_count' => 0,
                'per_user_limit' => 1,
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addYear(),
            ]
        );

        Coupon::updateOrCreate(
            ['code' => 'FOXAL20'],
            [
                'type' => 'percent',
                'value' => 20.00,
                'min_order_amount' => 999.00,
                'max_discount' => 400.00,
                'usage_limit' => 200,
                'usage_count' => 0,
                'per_user_limit' => 1,
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addYear(),
            ]
        );

        // 5. Seed Settings
        Setting::set('shipping_charge', 60.00, 'float');
        Setting::set('free_shipping_threshold', 499.00, 'float');
        Setting::set('gst_rate', 18.00, 'float');
        Setting::set('announcement_text', 'Free luxury incense holder on orders above ₹999.', 'string');
    }
}
