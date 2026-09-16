<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed demo products, a banner and featured reviews.
     */
    public function run(): void
    {
        $courses = Category::where('slug', 'courses')->first();
        $ebooks = Category::where('slug', 'e-books')->orWhere('slug', 'ebooks')->first();
        if (!$ebooks) {
            $ebooks = Category::where('name', 'eBooks')->first();
        }

        $product1 = Product::updateOrCreate(
            ['slug' => 'ecommerce-masterclass'],
            [
                'category_id' => $courses?->id,
                'title' => 'E-commerce Masterclass',
                'subtitle' => 'Complete guide to selling online in Bangladesh',
                'price' => 1999,
                'old_price' => 2999,
                'discount' => 33,
                'status' => 'available',
                'is_active' => true,
                'sort_order' => 1,
                'description' => "Learn everything you need to build a successful online store.\n\nThis comprehensive course covers product creation, marketing, bKash & Nagad payments, and more.",
                'features' => ['HD Video Tutorials', 'Lifetime Access', 'Downloadable Resources', 'Certificate of Completion'],
                'digital_info' => 'Delivered via email after payment confirmation',
            ]
        );

        $product2 = Product::updateOrCreate(
            ['slug' => 'premium-ebook-bundle'],
            [
                'category_id' => $ebooks?->id,
                'title' => 'Premium eBook Bundle',
                'subtitle' => '50+ best-selling eBooks in one package',
                'price' => 899,
                'old_price' => 1499,
                'discount' => 40,
                'status' => 'available',
                'is_active' => true,
                'sort_order' => 2,
                'description' => "Get 50+ premium eBooks covering business, marketing, productivity, and self-development.",
                'features' => ['PDF Format', 'Instant Access', '50+ eBooks', 'Bonus Content'],
                'digital_info' => 'Delivered via email after payment confirmation',
            ]
        );

        Banner::updateOrCreate(
            ['title' => 'Welcome to Our Digital Store'],
            [
                'subtitle' => 'Premium eBooks, courses & templates for your success',
                'button_text' => 'Shop Now',
                'action_type' => 'product_page',
                'action_product_id' => $product1->id,
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $reviews = [
            ['Mahmud Hasan', 5, 'Excellent quality digital products. Fast service and great support!'],
            ['Nusrat Jahan', 5, 'Really happy with my purchase. The product was exactly as described.'],
            ['Tanvir Ahmed', 4, 'Great value for money. Would definitely buy again.'],
            ['Sadia Islam', 5, 'Superb customer service. Product delivered quickly after payment.'],
            ['Rafiq Uddin', 5, 'Highly recommended store. Legit and reliable.'],
        ];

        foreach ($reviews as $i => [$name, $rating, $text]) {
            Review::updateOrCreate(
                ['customer_name' => $name],
                [
                    'product_id' => $product1->id,
                    'rating' => $rating,
                    'review_text' => $text,
                    'is_featured' => true,
                    'is_active' => true,
                    'sort_order' => $i + 1,
                ]
            );
        }
    }
}
