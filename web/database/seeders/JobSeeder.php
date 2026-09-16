<?php

namespace Database\Seeders;

use App\Models\JobPosting;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        JobPosting::updateOrCreate(
            ['slug' => 'junior-trader-dhaka'],
            [
                'title' => 'Junior Trader (Dhaka)',
                'company_name' => 'Capital Markets BD',
                'location' => 'Dhaka, Bangladesh',
                'employment_type' => 'Full-time',
                'salary_range' => '৳25,000 - ৳40,000',
                'application_deadline' => now()->addDays(30),
                'subtitle' => 'Great entry-level opportunity for freshly trained traders to join a professional trading desk.',
                'description' => "We're looking for a motivated Junior Trader to join our proprietary trading team. You'll learn professional trading strategies while contributing to real portfolios.\n\nIdeal for candidates who have completed our trading courses and want to apply their knowledge in a live environment.",
                'requirements' => "Completed a recognized trading certification or course\nBasic understanding of technical and fundamental analysis\nProficiency with trading platforms (MT4/MT5 or TradingView)\nStrong analytical and problem-solving skills\nWillingness to learn and work in a fast-paced environment",
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        JobPosting::updateOrCreate(
            ['slug' => 'forex-trainer-remote'],
            [
                'title' => 'Forex Mentor / Trainer (Remote)',
                'company_name' => 'Digital Trading Academy',
                'location' => 'Remote',
                'employment_type' => 'Part-time',
                'salary_range' => '৳20,000 - ৳35,000',
                'application_deadline' => now()->addDays(21),
                'subtitle' => 'Share your trading expertise and help new students build real-world trading skills online.',
                'description' => "We're expanding our team of online educators and looking for experienced Forex traders to mentor students. You'll host live sessions, create educational content, and provide one-on-one guidance.\n\nThis is a flexible remote role ideal for experienced traders who enjoy teaching.",
                'requirements' => "Minimum 2 years of active trading experience\nExcellent communication and presentation skills in Bangla and/or English\nAbility to explain complex trading concepts simply\nOwn trading account with verified track record preferred\nExperience in mentoring or education is a strong plus",
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        JobPosting::updateOrCreate(
            ['slug' => 'marketing-intern'],
            [
                'title' => 'Digital Marketing Intern',
                'company_name' => 'Digital Trading Academy',
                'location' => 'Dhaka, Bangladesh',
                'employment_type' => 'Internship',
                'salary_range' => '৳10,000 - ৳15,000',
                'application_deadline' => now()->addDays(14),
                'subtitle' => 'Support our marketing team with social media, content creation, and student outreach campaigns.',
                'description' => "Join our marketing team to learn digital marketing in action. You'll work on social media campaigns, write content, manage ads, and analyze performance metrics for a growing ed-tech brand.",
                'requirements' => "Students or recent graduates in Marketing, Communications, or related fields\nFamiliarity with Facebook, Instagram, and YouTube advertising\nBasic copywriting and content creation skills\nInterest in finance and trading is a plus\nSelf-motivated and able to meet deadlines",
                'is_active' => true,
                'sort_order' => 3,
            ]
        );
    }
}
