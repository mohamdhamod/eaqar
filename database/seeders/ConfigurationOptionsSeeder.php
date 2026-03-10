<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuration;
use App\Enums\ConfigurationsTypeEnum;

class ConfigurationOptionsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Operation Types
            [
                'id' => 1,
                'name' => [
                    'en' => 'Sale',
                    'ar' => 'بيع',
                ],
                'key' => ConfigurationsTypeEnum::OPERATION_TYPE,
                'code' => 'sale',
            ],
            [
                'id' => 2,
                'name' => [
                    'en' => 'Rent',
                    'ar' => 'إيجار',
                ],
                'key' => ConfigurationsTypeEnum::OPERATION_TYPE,
                'code' => 'rent',
            ],
            
            // Property Types
            [
                'id' => 3,
                'name' => [
                    'en' => 'Apartment',
                    'ar' => 'شقة',
                ],
                'key' => ConfigurationsTypeEnum::PROPERTY_TYPE,
                'code' => 'apartment',
            ],
            [
                'id' => 4,
                'name' => [
                    'en' => 'House',
                    'ar' => 'منزل',
                ],
                'key' => ConfigurationsTypeEnum::PROPERTY_TYPE,
                'code' => 'house',
            ],
            [
                'id' => 5,
                'name' => [
                    'en' => 'Land',
                    'ar' => 'أرض',
                ],
                'key' => ConfigurationsTypeEnum::PROPERTY_TYPE,
                'code' => 'land',
            ],
            [
                'id' => 6,
                'name' => [
                    'en' => 'Villa',
                    'ar' => 'فيلا',
                ],
                'key' => ConfigurationsTypeEnum::PROPERTY_TYPE,
                'code' => 'villa',
            ],
            [
                'id' => 7,
                'name' => [
                    'en' => 'Office',
                    'ar' => 'مكتب',
                ],
                'key' => ConfigurationsTypeEnum::PROPERTY_TYPE,
                'code' => 'office',
            ],
            [
                'id' => 8,
                'name' => [
                    'en' => 'Commercial Shop',
                    'ar' => 'محل تجاري',
                ],
                'key' => ConfigurationsTypeEnum::PROPERTY_TYPE,
                'code' => 'commercial_shop',
            ],
            [
                'id' => 9,
                'name' => [
                    'en' => 'Clinic',
                    'ar' => 'عيادة',
                ],
                'key' => ConfigurationsTypeEnum::PROPERTY_TYPE,
                'code' => 'clinic',
            ],
            [
                'id' => 10,
                'name' => [
                    'en' => 'Warehouse',
                    'ar' => 'مستودع',
                ],
                'key' => ConfigurationsTypeEnum::PROPERTY_TYPE,
                'code' => 'warehouse',
            ],
            [
                'id' => 11,
                'name' => [
                    'en' => 'Farm',
                    'ar' => 'مزرعة',
                ],
                'key' => ConfigurationsTypeEnum::PROPERTY_TYPE,
                'code' => 'farm',
            ],
            
            // Cities
            [
                'id' => 12,
                'name' => [
                    'en' => 'Ar-Raqqa',
                    'ar' => 'الرقة',
                ],
                'key' => ConfigurationsTypeEnum::CITY,
                'code' => 'ar_raqqa',
            ],
            [
                'id' => 13,
                'name' => [
                    'en' => 'Tal Abyad',
                    'ar' => 'تل ابيض',
                ],
                'key' => ConfigurationsTypeEnum::CITY,
                'code' => 'tal_abyad',
            ],
            [
                'id' => 14,
                'name' => [
                    'en' => 'Al-Tabqa',
                    'ar' => 'الطبقة',
                ],
                'key' => ConfigurationsTypeEnum::CITY,
                'code' => 'al_tabqa',            ],
            
            // Currencies
            [
                'id' => 15,
                'name' => [
                    'en' => 'Syrian Pound (New)',
                    'ar' => 'ليرة سورية جديدة',
                ],
                'key' => ConfigurationsTypeEnum::CURRENCY,
                'code' => 'syp_new',
            ],
            [
                'id' => 16,
                'name' => [
                    'en' => 'Syrian Pound (Old)',
                    'ar' => 'ليرة سورية قديمة',
                ],
                'key' => ConfigurationsTypeEnum::CURRENCY,
                'code' => 'syp_old',
            ],
            [
                'id' => 17,
                'name' => [
                    'en' => 'US Dollar',
                    'ar' => 'دولار أمريكي',
                ],
                'key' => ConfigurationsTypeEnum::CURRENCY,
                'code' => 'usd',            ],
            
            // Currencies
            [
                'id' => 15,
                'name' => [
                    'en' => 'Syrian Pound (New)',
                    'ar' => 'ليرة سورية جديدة',
                ],
                'key' => ConfigurationsTypeEnum::CURRENCY,
                'code' => 'syp_new',
            ],
            [
                'id' => 16,
                'name' => [
                    'en' => 'Syrian Pound (Old)',
                    'ar' => 'ليرة سورية قديمة',
                ],
                'key' => ConfigurationsTypeEnum::CURRENCY,
                'code' => 'syp_old',
            ],
            [
                'id' => 17,
                'name' => [
                    'en' => 'US Dollar',
                    'ar' => 'دولار أمريكي',
                ],
                'key' => ConfigurationsTypeEnum::CURRENCY,
                'code' => 'usd',
            ]
        ];

        // Seed the data into the database
        foreach ($data as $item) {
            $model = Configuration::updateOrCreate([
                'id' => $item['id']
            ], [
                'key' => $item['key'],
                'code' => $item['code'] ?? null,
                'active' => 1,
            ]);

            foreach ($item['name'] as $locale => $translation) {
                $model->translateOrNew($locale)->name = $translation;
            }

            $model->save();
        }
    }
}
