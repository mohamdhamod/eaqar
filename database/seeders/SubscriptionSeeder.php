<?php

namespace Database\Seeders;

use App\Models\Configuration;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Syrian Pound (New) configuration
        $sypConfig = Configuration::where('code', 'syp_new')->first();
        if (!$sypConfig) {
            // Create Syrian Pound (New) if it doesn't exist
            $sypConfig = Configuration::create([
                'code' => 'syp_new',
                'key' => 'currency',
                'active' => true,
            ]);
            
            $sypConfig->translateOrNew('en')->fill([
                'name' => 'Syrian Pound (New)',
            ])->save();
            
            $sypConfig->translateOrNew('ar')->fill([
                'name' => 'ليرة سورية جديدة',
            ])->save();
        }

        $subscriptions = [
            [
                'key' => 'free',
                'price' => 0,
                'duration_days' => 30,
                'max_properties' => 5,
                'icon' => 'fa-gift',
                'color' => '#6b7280',
                'active' => true,
                'sort_order' => 0,
                'name_en' => 'Free Plan',
                'description_en' => 'Get started for free',
                'name_ar' => 'الخطة المجانية',
                'description_ar' => 'ابدأ مجاناً',
            ],
            [
                'key' => 'basic',
                'price' => 1200,
                'duration_days' => 30,
                'max_properties' => 100,
                'icon' => 'fa-star',
                'color' => '#667eea',
                'active' => true,
                'sort_order' => 1,
                'name_en' => 'Basic Plan',
                'description_en' => 'Perfect for getting started',
                'name_ar' => 'الخطة الأساسية',
                'description_ar' => 'مثالية للبدء',
            ],
            [
                'key' => 'professional',
                'price' => 2400,
                'duration_days' => 30,
                'max_properties' => 200,
                'icon' => 'fa-rocket',
                'color' => '#764ba2',
                'active' => true,
                'sort_order' => 2,
                'name_en' => 'Professional Plan',
                'description_en' => 'For professional users',
                'name_ar' => 'الخطة الاحترافية',
                'description_ar' => 'للمستخدمين المحترفين',
            ],
            [
                'key' => 'enterprise',
                'price' => 4800,
                'duration_days' => 30,
                'max_properties' => 1000,
                'icon' => 'fa-crown',
                'color' => '#10b981',
                'active' => true,
                'sort_order' => 3,
                'name_en' => 'Enterprise Plan',
                'description_en' => 'For large organizations',
                'name_ar' => 'خطة المؤسسات',
                'description_ar' => 'للمؤسسات الكبيرة',
            ],
        ];

        foreach ($subscriptions as $data) {
            $subscription = Subscription::create([
                'key' => $data['key'],
                'price' => $data['price'],
                'duration_days' => $data['duration_days'],
                'max_properties' => $data['max_properties'],
                'icon' => $data['icon'],
                'color' => $data['color'],
                'active' => $data['active'],
                'sort_order' => $data['sort_order'],
                'slug' => str($data['key'])->slug(),
                'currency_id' => $sypConfig->id,
            ]);

            // Save English translation
            $subscription->translateOrNew('en')->fill([
                'name' => $data['name_en'],
                'description' => $data['description_en'],
            ])->save();

            // Save Arabic translation
            $subscription->translateOrNew('ar')->fill([
                'name' => $data['name_ar'],
                'description' => $data['description_ar'],
            ])->save();
        }
    }
}
