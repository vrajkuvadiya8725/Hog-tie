<?php

namespace Database\Seeders;

use App\Models\ClientQuote;
use App\Models\Faq;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ✅ NEW ADMIN USER
        User::firstOrCreate(
            ['email' => 'admin@hogtie.com'],
            [
                'name' => 'Hog Tie Admin',
                'password' => Hash::make('Admin@123'),
                'is_admin' => true,
            ]
        );

        // 👤 Normal user
        User::firstOrCreate(
            ['email' => 'customer@hogtie.com'],
            [
                'name' => 'Hog Tie Customer',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );

        // ❓ FAQs
        Faq::insert([
            [
                'question' => 'What is the minimum bulk order quantity?',
                'answer' => 'Our minimum bulk order starts from 25 units.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'question' => 'Do you support custom branding?',
                'answer' => 'Yes, we provide logo engraving and custom packaging options.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 💬 Testimonials
        Testimonial::insert([
            [
                'name' => 'Ananya Shah',
                'designation' => 'HR Manager, NovaSoft',
                'message' => 'Hog Tie helped us deliver festival gifts to 300 employees with zero delays.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Rohan Mehta',
                'designation' => 'Procurement Lead, Zenix',
                'message' => 'Excellent quality and smooth coordination from shortlisting to delivery.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 🏢 Client Quotes
        ClientQuote::insert([
            [
                'client_name' => 'Alpha Core',
                'quote' => 'Best gifting partner for our quarterly recognition campaign.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'client_name' => 'Brightline',
                'quote' => 'The team was quick, professional, and very detail-oriented.',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'client_name' => 'Nexora',
                'quote' => 'Highly recommended for premium corporate gift bundles.',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // 🧪 Test user
        User::firstOrCreate(
    ['email' => 'test@example.com'],
    [
        'name' => 'Test User',
        'password' => Hash::make('password123'),
        'is_admin' => false,
    ]
);
    }
}