<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use App\Traits\FileHandler;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PropertySeeder extends Seeder
{
    use FileHandler;
    /**
     * Property listings data - Syrian cities
     */
    private array $properties = [
        // ─── Al-Raqqa - Apartments for Sale
        [
            'city_id' => 12,
            'operation_type_id' => 1,
            'property_type_id' => 3,
            'currency_id' => 15,
            'title' => ['en' => 'Modern Apartment in Al-Raqqa Center', 'ar' => 'شقة حديثة في وسط الرقة'],
            'description' => ['en' => 'Spacious 3-bedroom apartment in the heart of Al-Raqqa with excellent amenities.', 'ar' => 'شقة واسعة بثلاث غرف نوم في قلب الرقة مع تسهيلات رائعة.'],
            'address' => ['en' => 'Al-Raqqa Center', 'ar' => 'وسط الرقة'],
            'price' => 45000000, 'area' => 180, 'rooms' => 3, 'bathrooms' => 2,
            'floor' => 3, 'total_floors' => 8, 'building_age' => 2,
            'latitude' => 35.9472, 'longitude' => 39.0156,
            'status' => 'active', 'is_featured' => true,
            'images' => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
                'https://images.unsplash.com/photo-1551632786-de41ec56a024?w=800',
            ]
        ],
        [
            'city_id' => 12, 'operation_type_id' => 1, 'property_type_id' => 3, 'currency_id' => 15,
            'title' => ['en' => 'Family Apartment with Balcony', 'ar' => 'شقة عائلية مع شرفة'],
            'description' => ['en' => '4-bedroom apartment with spacious balcony overlooking the city.', 'ar' => 'شقة بأربع غرف نوم مع شرفة واسعة تطل على المدينة.'],
            'address' => ['en' => 'Al-Raqqa Suburb', 'ar' => 'ضواحي الرقة'],
            'price' => 52000000, 'area' => 220, 'rooms' => 4, 'bathrooms' => 2,
            'floor' => 5, 'total_floors' => 10, 'building_age' => 1,
            'latitude' => 35.9523, 'longitude' => 39.0234,
            'status' => 'active', 'is_featured' => true,
            'images' => [
                'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800',
                'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800',
                'https://images.unsplash.com/photo-1512917774080-9aa51dcd814d?w=800',
            ]
        ],
        [
            'city_id' => 12, 'operation_type_id' => 2, 'property_type_id' => 3, 'currency_id' => 15,
            'title' => ['en' => 'Furnished Apartment for Rent', 'ar' => 'شقة مفروشة للإيجار'],
            'description' => ['en' => 'Fully furnished 2-bedroom apartment, ready to move in.', 'ar' => 'شقة مفروشة بغرفتي نوم، جاهزة للانتقال.'],
            'address' => ['en' => 'Al-Raqqa Downtown', 'ar' => 'وسط البلد - الرقة'],
            'price' => 750000, 'area' => 120, 'rooms' => 2, 'bathrooms' => 1,
            'floor' => 2, 'total_floors' => 6, 'building_age' => 3,
            'latitude' => 35.9456, 'longitude' => 39.0145,
            'status' => 'active', 'is_featured' => false,
            'images' => [
                'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800',
                'https://images.unsplash.com/photo-1522174470826-d594865cfc1e?w=800',
            ]
        ],
        [
            'city_id' => 12, 'operation_type_id' => 1, 'property_type_id' => 6, 'currency_id' => 15,
            'title' => ['en' => 'Beautiful Villa with Large Garden', 'ar' => 'فيلا جميلة مع حديقة كبيرة'],
            'description' => ['en' => 'Spacious 4-bedroom villa with private garden and modern facilities.', 'ar' => 'فيلا واسعة بأربع غرف نوم مع حديقة خاصة وتسهيلات حديثة.'],
            'address' => ['en' => 'Al-Raqqa North', 'ar' => 'شمال الرقة'],
            'price' => 125000000, 'area' => 400, 'rooms' => 4, 'bathrooms' => 3,
            'floor' => 0, 'total_floors' => 2, 'building_age' => 4,
            'latitude' => 35.9634, 'longitude' => 39.0089,
            'status' => 'active', 'is_featured' => true,
            'images' => [
                'https://images.unsplash.com/photo-1570129477492-45a003537e1f?w=800',
                'https://images.unsplash.com/photo-1448932223592-d552f08d3cb5?w=800',
                'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800',
            ]
        ],
        [
            'city_id' => 12, 'operation_type_id' => 1, 'property_type_id' => 8, 'currency_id' => 15,
            'title' => ['en' => 'Commercial Shop in City Center', 'ar' => 'محل تجاري في وسط المدينة'],
            'description' => ['en' => 'Prime commercial space perfect for retail business.', 'ar' => 'مساحة تجارية متميزة مثالية للأعمال البيعية.'],
            'address' => ['en' => 'Main Street, Al-Raqqa', 'ar' => 'شارع النيل، الرقة'],
            'price' => 28000000, 'area' => 85, 'rooms' => 0, 'bathrooms' => 1,
            'floor' => 0, 'total_floors' => 1, 'building_age' => 2,
            'latitude' => 35.9475, 'longitude' => 39.0167,
            'status' => 'active', 'is_featured' => false,
            'images' => ['https://images.unsplash.com/photo-1497366216548-37526070297c?w=800']
        ],
        // ─── Tal Abyad
        [
            'city_id' => 13, 'operation_type_id' => 1, 'property_type_id' => 3, 'currency_id' => 15,
            'title' => ['en' => 'Modern Apartment in Tal Abyad', 'ar' => 'شقة حديثة في تل ابيض'],
            'description' => ['en' => '3-bedroom apartment in prime location with excellent view.', 'ar' => 'شقة بثلاث غرف نوم في موقع متميز مع إطلالة رائعة.'],
            'address' => ['en' => 'Tal Abyad Center', 'ar' => 'وسط تل ابيض'],
            'price' => 38000000, 'area' => 160, 'rooms' => 3, 'bathrooms' => 2,
            'floor' => 4, 'total_floors' => 8, 'building_age' => 2,
            'latitude' => 36.7569, 'longitude' => 38.4645,
            'status' => 'active', 'is_featured' => false,
            'images' => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
            ]
        ],
        [
            'city_id' => 13, 'operation_type_id' => 2, 'property_type_id' => 3, 'currency_id' => 15,
            'title' => ['en' => 'Furnished Apartment for Rent in Tal Abyad', 'ar' => 'شقة مفروشة للإيجار في تل ابيض'],
            'description' => ['en' => 'Comfortable furnished apartment with all utilities included.', 'ar' => 'شقة مفروشة مريحة مع جميع المرافق المضمنة.'],
            'address' => ['en' => 'Tal Abyad Neighborhood', 'ar' => 'حي تل ابيض'],
            'price' => 600000, 'area' => 110, 'rooms' => 2, 'bathrooms' => 1,
            'floor' => 2, 'total_floors' => 5, 'building_age' => 1,
            'latitude' => 36.7543, 'longitude' => 38.4612,
            'status' => 'active', 'is_featured' => false,
            'images' => ['https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800']
        ],
        [
            'city_id' => 13, 'operation_type_id' => 1, 'property_type_id' => 4, 'currency_id' => 15,
            'title' => ['en' => 'Land Plot for Development', 'ar' => 'قطعة أرض للتطوير'],
            'description' => ['en' => 'Agricultural and residential land with good location.', 'ar' => 'أرض زراعية وسكنية مع موقع جيد.'],
            'address' => ['en' => 'Tal Abyad Outskirts', 'ar' => 'ضواحي تل ابيض'],
            'price' => 15000000, 'area' => 800, 'rooms' => 0, 'bathrooms' => 0,
            'floor' => 0, 'total_floors' => 1, 'building_age' => 0,
            'latitude' => 36.7623, 'longitude' => 38.4721,
            'status' => 'active', 'is_featured' => false,
            'images' => ['https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=800']
        ],
        [
            'city_id' => 13, 'operation_type_id' => 1, 'property_type_id' => 2, 'currency_id' => 15,
            'title' => ['en' => 'Residential House in Tal Abyad', 'ar' => 'منزل سكني في تل ابيض'],
            'description' => ['en' => '3-bedroom house with courtyard and modern kitchen.', 'ar' => 'منزل بثلاث غرف نوم مع فناء وحمام مطبخ حديث.'],
            'address' => ['en' => 'Tal Abyad Residential Area', 'ar' => 'منطقة سكنية - تل ابيض'],
            'price' => 55000000, 'area' => 250, 'rooms' => 3, 'bathrooms' => 2,
            'floor' => 0, 'total_floors' => 2, 'building_age' => 3,
            'latitude' => 36.7534, 'longitude' => 38.4598,
            'status' => 'active', 'is_featured' => false,
            'images' => [
                'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800',
                'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800',
            ]
        ],
        // ─── Al-Tabqa
        [
            'city_id' => 14, 'operation_type_id' => 1, 'property_type_id' => 3, 'currency_id' => 15,
            'title' => ['en' => 'Apartment in Al-Tabqa Center', 'ar' => 'شقة في وسط الطبقة'],
            'description' => ['en' => 'Spacious 3-bedroom apartment with parking.', 'ar' => 'شقة واسعة بثلاث غرف نوم مع موقف سيارات.'],
            'address' => ['en' => 'Al-Tabqa Downtown', 'ar' => 'وسط البلد - الطبقة'],
            'price' => 40000000, 'area' => 170, 'rooms' => 3, 'bathrooms' => 2,
            'floor' => 3, 'total_floors' => 7, 'building_age' => 2,
            'latitude' => 35.4304, 'longitude' => 38.5471,
            'status' => 'active', 'is_featured' => true,
            'images' => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
            ]
        ],
        [
            'city_id' => 14, 'operation_type_id' => 2, 'property_type_id' => 3, 'currency_id' => 15,
            'title' => ['en' => 'Furnished Apartment in Al-Tabqa', 'ar' => 'شقة مفروشة في الطبقة'],
            'description' => ['en' => '2-bedroom furnished apartment ready for move-in.', 'ar' => 'شقة مفروشة بغرفتي نوم جاهزة للانتقال.'],
            'address' => ['en' => 'Al-Tabqa Suburb', 'ar' => 'ضواحي الطبقة'],
            'price' => 680000, 'area' => 100, 'rooms' => 2, 'bathrooms' => 1,
            'floor' => 1, 'total_floors' => 4, 'building_age' => 1,
            'latitude' => 35.4267, 'longitude' => 38.5423,
            'status' => 'active', 'is_featured' => false,
            'images' => ['https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800']
        ],
        [
            'city_id' => 14, 'operation_type_id' => 1, 'property_type_id' => 8, 'currency_id' => 15,
            'title' => ['en' => 'Commercial Shop in Al-Tabqa', 'ar' => 'محل تجاري في الطبقة'],
            'description' => ['en' => 'Commercial space in busy commercial area.', 'ar' => 'مساحة تجارية في منطقة تجارية مزدحمة.'],
            'address' => ['en' => 'Al-Tabqa Market', 'ar' => 'سوق الطبقة'],
            'price' => 24000000, 'area' => 75, 'rooms' => 0, 'bathrooms' => 1,
            'floor' => 0, 'total_floors' => 1, 'building_age' => 2,
            'latitude' => 35.4312, 'longitude' => 38.5489,
            'status' => 'active', 'is_featured' => false,
            'images' => ['https://images.unsplash.com/photo-1497366216548-37526070297c?w=800']
        ],
        [
            'city_id' => 14, 'operation_type_id' => 1, 'property_type_id' => 9, 'currency_id' => 15,
            'title' => ['en' => 'Medical Clinic Space', 'ar' => 'مساحة عيادة طبية'],
            'description' => ['en' => 'Equipped medical clinic ready for operation.', 'ar' => 'عيادة طبية مجهزة وجاهزة للتشغيل.'],
            'address' => ['en' => 'Al-Tabqa Medical District', 'ar' => 'الحي الطبي - الطبقة'],
            'price' => 18000000, 'area' => 120, 'rooms' => 0, 'bathrooms' => 2,
            'floor' => 1, 'total_floors' => 3, 'building_age' => 1,
            'latitude' => 35.4345, 'longitude' => 38.5512,
            'status' => 'active', 'is_featured' => false,
            'images' => ['https://images.unsplash.com/photo-1497366216548-37526070297c?w=800']
        ],
    ];

    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        
        if (empty($users)) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        $extendedProperties = $this->generateAdditionalProperties();
        $allProperties = array_merge($this->properties, $extendedProperties);

        foreach ($allProperties as $index => $propertyData) {
            $images = $propertyData['images'];
            unset($propertyData['images']);

            // Extract translated fields before creating the model
            // Astrotomic Translatable cannot receive array values via Property::create()
            $translations = [
                'en' => [
                    'title'       => $propertyData['title']['en'],
                    'description' => $propertyData['description']['en'] ?? null,
                    'address'     => $propertyData['address']['en'] ?? null,
                ],
                'ar' => [
                    'title'       => $propertyData['title']['ar'],
                    'description' => $propertyData['description']['ar'] ?? null,
                    'address'     => $propertyData['address']['ar'] ?? null,
                ],
            ];
            unset($propertyData['title'], $propertyData['description'], $propertyData['address']);

            $propertyData['user_id'] = $users[array_rand($users)];
            $propertyData['slug'] = Str::slug($translations['en']['title'] . '-' . ($index + 1));
            $propertyData['active'] = true;
            $propertyData['sort_order'] = $index + 1;

            $property = Property::create($propertyData);

            // Save translations via Astrotomic Translatable
            foreach ($translations as $locale => $trans) {
                $t = $property->translateOrNew($locale);
                $t->title       = $trans['title'];
                $t->description = $trans['description'];
                $t->address     = $trans['address'];
                $t->save();
            }

            foreach ($images as $imageIndex => $imageUrl) {
                $storedPath = null;
                try {
                    $response = Http::timeout(20)->get($imageUrl);
                    if ($response->successful()) {
                        $storedPath = $this->storeImage($response->body(), 'properties', 'jpg');
                    } else {
                        $this->command->warn("Failed to download image (HTTP {$response->status()}): {$imageUrl}");
                    }
                } catch (\Exception $e) {
                    $this->command->warn("Could not download image: {$imageUrl} — {$e->getMessage()}");
                }

                PropertyImage::create([
                    'property_id' => $property->id,
                    'image'       => $storedPath,
                    'is_main'     => $imageIndex === 0,
                    'sort_order'  => $imageIndex,
                ]);
            }
        }

        $this->command->info('Successfully created ' . count($allProperties) . ' properties with images.');
    }

    private function generateAdditionalProperties(): array
    {
        $cities = [12, 13, 14];
        $operationTypes = [1, 2];
        $propertyTypes = [3, 4, 6, 8, 9, 10, 11];
        $currencies = [15, 16, 17]; // Syrian Pound New, Old, USD
        
        $unsplashImages = [
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
            'https://images.unsplash.com/photo-1551632786-de41ec56a024?w=800',
            'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800',
            'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=800',
            'https://images.unsplash.com/photo-1512917774080-9aa51dcd814d?w=800',
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800',
            'https://images.unsplash.com/photo-1570129477492-45a003537e1f?w=800',
            'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=800',
        ];

        $neighborhoods = [
            12 => ['وسط الرقة', 'شمال الرقة', 'جنوب الرقة', 'حي الرقة الجديد', 'ضواحي الرقة'],
            13 => ['وسط تل ابيض', 'حي تل ابيض', 'ضواحي تل ابيض', 'شارع النيل'],
            14 => ['وسط الطبقة', 'ضواحي الطبقة', 'حي الطبقة الجديد', 'الحي الشرقي'],
        ];

        $properties = [];
        $count = count($this->properties);
        
        while ($count < 100) {
            $cityId = $cities[array_rand($cities)];
            $operationTypeId = $operationTypes[array_rand($operationTypes)];
            $propertyTypeId = $propertyTypes[array_rand($propertyTypes)];
            $currencyId = $currencies[array_rand($currencies)];
            
            $basePrice = match($propertyTypeId) {
                3 => $operationTypeId === 1 ? 40000000 : 700000,
                4 => 60000000,
                6 => 100000000,
                8 => 25000000,
                9 => 18000000,
                10 => 50000000,
                11 => 30000000,
                default => 40000000
            };
            
            $price = $basePrice + rand(-5000000, 10000000);
            if ($operationTypeId === 2) $price = rand(500000, 1500000);
            
            $area = match($propertyTypeId) {
                3 => rand(100, 200),
                4 => rand(200, 400),
                6 => rand(300, 600),
                8 => rand(50, 150),
                9 => rand(100, 250),
                10 => rand(200, 500),
                11 => rand(500, 2000),
                default => rand(100, 300)
            };

            $rooms = match($propertyTypeId) {
                3 => rand(1, 4),
                4 => rand(2, 5),
                6 => rand(3, 6),
                8, 9, 10, 11 => 0,
                default => rand(1, 3)
            };

            $floor = ($propertyTypeId === 6 || $propertyTypeId === 4) ? 0 : rand(0, 10);
            $totalFloors = ($propertyTypeId === 6 || $propertyTypeId === 4) ? 2 : rand(3, 12);

            $neighborhood = $neighborhoods[$cityId][array_rand($neighborhoods[$cityId])];

            $properties[] = [
                'city_id' => $cityId,
                'operation_type_id' => $operationTypeId,
                'property_type_id' => $propertyTypeId,
                'currency_id' => $currencyId,
                'title' => [
                    'en' => $this->generateTitle($propertyTypeId, $operationTypeId),
                    'ar' => $this->generateArabicTitle($propertyTypeId, $operationTypeId)
                ],
                'description' => [
                    'en' => $this->generateDescription($propertyTypeId, $rooms),
                    'ar' => $this->generateArabicDescription($propertyTypeId, $rooms)
                ],
                'address' => [
                    'en' => $neighborhood . ' - ' . $this->getCityName($cityId),
                    'ar' => $neighborhood . ' - ' . $this->getCityNameAr($cityId)
                ],
                'price' => $price,
                'area' => $area,
                'rooms' => $rooms,
                'bathrooms' => $rooms > 0 ? ceil($rooms / 1.5) : ($propertyTypeId === 8 || $propertyTypeId === 9 ? 1 : 0),
                'floor' => $floor,
                'total_floors' => $totalFloors,
                'building_age' => rand(0, 8),
                'latitude' => $this->generateLatitude($cityId),
                'longitude' => $this->generateLongitude($cityId),
                'status' => rand(0, 10) > 2 ? 'active' : 'hidden',
                'is_featured' => rand(0, 100) > 85,
                'images' => [
                    $unsplashImages[array_rand($unsplashImages)],
                    $unsplashImages[array_rand($unsplashImages)],
                ]
            ];

            $count++;
        }

        return $properties;
    }

    private function generateTitle(int $typeId, int $operationId): string
    {
        $types = [3 => 'Apartment', 4 => 'House', 6 => 'Villa', 8 => 'Commercial Shop', 9 => 'Clinic', 10 => 'Warehouse', 11 => 'Farm'];
        $adjectives = ['Modern', 'Spacious', 'Comfortable', 'Beautiful', 'Premium', 'Elegant', 'New', 'Renovated'];
        $action = $operationId === 1 ? 'For Sale' : 'For Rent';
        return $adjectives[array_rand($adjectives)] . ' ' . $types[$typeId] . ' ' . $action;
    }

    private function generateArabicTitle(int $typeId, int $operationId): string
    {
        $types = [3 => 'شقة', 4 => 'منزل', 6 => 'فيلا', 8 => 'محل تجاري', 9 => 'عيادة', 10 => 'مستودع', 11 => 'مزرعة'];
        $adjectives = ['حديثة', 'واسعة', 'مريحة', 'جميلة', 'متميزة', 'أنيقة', 'جديدة', 'مجددة'];
        $action = $operationId === 1 ? 'للبيع' : 'للإيجار';
        return $adjectives[array_rand($adjectives)] . ' ' . $types[$typeId] . ' ' . $action;
    }

    private function generateDescription(int $typeId, int $rooms): string
    {
        $descriptions = [
            'Well-maintained property in excellent condition.',
            'Prime location with good access to services.',
            'Recently renovated with modern finishes.',
            'Ideal for families or business purposes.',
            'Ready for immediate occupancy.',
            'Great investment opportunity in growing area.',
        ];
        return $descriptions[array_rand($descriptions)];
    }

    private function generateArabicDescription(int $typeId, int $rooms): string
    {
        $descriptions = [
            'عقار مصان بحالة ممتازة.',
            'موقع متميز مع سهولة الوصول للخدمات.',
            'تم تجديده مؤخراً بتشطيبات حديثة.',
            'مثالي للعائلات أو الأغراض التجارية.',
            'جاهز للانتقال الفوري.',
            'فرصة استثمار رائعة في منطقة نامية.',
        ];
        return $descriptions[array_rand($descriptions)];
    }

    private function getCityName(int $cityId): string
    {
        return match($cityId) {
            12 => 'Al-Raqqa',
            13 => 'Tal Abyad',
            14 => 'Al-Tabqa',
            default => 'Syria'
        };
    }

    private function getCityNameAr(int $cityId): string
    {
        return match($cityId) {
            12 => 'الرقة',
            13 => 'تل ابيض',
            14 => 'الطبقة',
            default => 'سوريا'
        };
    }

    private function generateLatitude(int $cityId): float
    {
        return match($cityId) {
            12 => 35.9472 + (rand(-500, 500) / 10000),
            13 => 36.7569 + (rand(-500, 500) / 10000),
            14 => 35.4304 + (rand(-500, 500) / 10000),
        };
    }

    private function generateLongitude(int $cityId): float
    {
        return match($cityId) {
            12 => 39.0156 + (rand(-500, 500) / 10000),
            13 => 38.4645 + (rand(-500, 500) / 10000),
            14 => 38.5471 + (rand(-500, 500) / 10000),
        };
    }
}
