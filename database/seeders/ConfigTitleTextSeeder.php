<?php

namespace Database\Seeders;

use App\Enums\ConfigEnum;
use App\Models\ConfigTitle;
use Illuminate\Database\Seeder;


class ConfigTitleTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'title' => [
                    'en' => 'Copyright',
                    'ar' => 'حقوق النشر',
                
                ],
                'description' => [
                    'en' => 'Copyright: EAQAR - Real Estate Platform',
                    'ar' => 'حقوق النشر: إقرار - منصة العقارات',
                    
                ],
                'page' => ConfigEnum::FOOTER,
                'key' => ConfigEnum::COPYRIGHT,
            ],

            // About Us page content
            [
                'id' => 2,
                'title' => [
                    'en' => 'About Us',
                    'ar' => 'من نحن',
                    
                ],
                'description' => [
                    'en' => 'Discover your perfect property on EAQAR - your trusted real estate marketplace connecting buyers, sellers, and agents seamlessly.',
                    'ar' => 'اكتشف العقار المثالي على إقرار - منصتك الموثوقة في السوق العقاري التي تربط بين المشترين والبائعين والوكلاء بسلاسة.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_HERO,
            ],
            [
                'id' => 3,
                'title' => [
                    'en' => 'About EAQAR',
                    'ar' => 'عن إقرار',
                    
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_ABOUT_TITLE,
            ],
            [
                'id' => 4,
                'title' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'description' => [
                    'en' => 'EAQAR is a comprehensive real estate platform that simplifies property transactions. Whether you\'re buying, selling, or renting, we connect you with trusted agents and quality properties in one convenient place.',
                    'ar' => 'إقرار هي منصة عقارات شاملة تبسط عمليات البيع والشراء والإيجار. سواء كنت تبحث عن شراء أو بيع أو تأجير عقار، نربطك بوكلاء موثوقين وعقارات عالية الجودة في مكان واحد.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_ABOUT_BODY_1,
            ],
            [
                'id' => 5,
                'title' => [
                    'en' => '',
                    'ar' => '',
                    'fr' => '',
                    'es' => '',
                    'de' => '',
                ],
                'description' => [
                    'en' => 'We believe every buyer deserves transparent information and every property deserves proper exposure. Our platform empowers real estate professionals and buyers with innovative tools and reliable services.',
                    'ar' => 'نؤمن أن كل مشتري يستحق معلومات شفافة وأن كل عقار يستحق العرض المناسب. تمكننا منصتنا محترفي العقارات والمشترين بأدوات مبتكرة وخدمات موثوقة.',
                  ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_ABOUT_BODY_2,
            ],
            [
                'id' => 6,
                'title' => [
                    'en' => 'Services & Features',
                    'ar' => 'الخدمات والميزات',
                ],
                'description' => [
                    'en' => 'Explore our platform features designed to make your real estate journey smooth and successful.',
                    'ar' => 'استكشف ميزات منصتنا المصممة لجعل رحلتك العقارية سلسة وناجحة.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_HIGHLIGHT,
            ],
            [
                'id' => 7,
                'title' => [
                    'en' => 'What We Offer',
                    'ar' => 'ماذا نقدم',
                 ],
                'description' => [
                    'en' => '',
                    'ar' => '',
               ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_OFFER_TITLE,
            ],
            [
                'id' => 8,
                'title' => [
                    'en' => 'Our Vision',
                    'ar' => 'رؤيتنا',
                ],
                'description' => [
                    'en' => 'To be the leading real estate marketplace that connects buyers, sellers, and agents with transparency, innovation, and trust.',
                    'ar' => 'أن نكون منصة العقارات الرائدة التي تربط بين المشترين والبائعين والوكلاء بالشفافية والابتكار والثقة.',
               ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_VISION,
            ],
            [
                'id' => 9,
                'title' => [
                    'en' => 'Our Mission',
                    'ar' => 'رسالتنا',
                ],
                'description' => [
                    'en' => 'To empower the real estate industry by providing advanced technology, comprehensive property listings, and professional tools that make buying, selling, and renting easier than ever.',
                    'ar' => 'تمكين قطاع العقارات من خلال توفير تقنيات متقدمة وقوائم عقارات شاملة وأدوات احترافية تجعل البيع والشراء والإيجار أسهل من أي وقت مضى.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_MISSION,
            ],
            [
                'id' => 10,
                'title' => [
                    'en' => 'Why EAQAR?',
                    'ar' => 'لماذا إقرار؟',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
               ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_TITLE,
            ],
            [
                'id' => 11,
                'title' => [
                    'en' => 'Advanced Search',
                    'ar' => 'بحث متقدم',
                ],
                'description' => [
                    'en' => 'Find your perfect property using intelligent filters and comprehensive search tools that match your specific needs and preferences.',
                    'ar' => 'ابحث عن العقار المثالي باستخدام فلاتر ذكية وأدوات بحث شاملة تناسب احتياجاتك وتفضيلاتك.',
                  ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_SIMPLE_UI,
            ],
            [
                'id' => 12,
                'title' => [
                    'en' => 'Professional Listings',
                    'ar' => 'إدراجات احترافية',
                ],
                'description' => [
                    'en' => 'High-quality property listings with detailed descriptions, professional photos, and virtual tours to showcase your properties effectively.',
                    'ar' => 'إدراجات عقارية عالية الجودة مع وصف مفصل وصور احترافية وجولات افتراضية لعرض العقارات بشكل فعال.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_SMART_TOOLS,
            ],
            [
                'id' => 13,
                'title' => [
                    'en' => 'Verified Agents',
                    'ar' => 'وكلاء موثوقون',
                ],
                'description' => [
                    'en' => 'Connect with verified, professional real estate agents and agencies. All agents on our platform are carefully vetted for quality and reliability.',
                    'ar' => 'تواصل مع وكلاء ووكالات عقारية موثوقة واحترافية. جميع الوكلاء في منصتنا تم التحقق منهم بعناية من حيث الجودة والموثوقية.',
               ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_PRO_WITHOUT_COMPLEXITY,
            ],
            [
                'id' => 14,
                'title' => [
                    'en' => 'For Everyone',
                    'ar' => 'لجميع الجميع',
               ],
                'description' => [
                    'en' => 'Whether you\'re a first-time homebuyer, an experienced investor, or a professional real estate agency, EAQAR serves your needs.',
                    'ar' => 'سواء كنت مشتري منزل للمرة الأولى أو مستثمراً خبيراً أو وكالة عقارات احترافية، إقرار تخدم احتياجاتك.',
                 ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_FOR_INDIVIDUALS_COMPANIES,
            ],
            [
                'id' => 15,
                'title' => [
                    'en' => 'Trusted Partnership',
                    'ar' => 'شراكة موثوقة',
               ],
                'description' => [
                    'en' => 'With transparent processes, secure transactions, and dedicated support, EAQAR is your trusted partner in real estate.',
                    'ar' => 'مع عمليات شفافة ومعاملات آمنة ودعم متخصص، إقرار هي شريكتك الموثوقة في العقارات.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_CONTINUOUS_SUPPORT,
            ],
            [
                'id' => 16,
                'title' => [
                    'en' => 'Start Exploring',
                    'ar' => 'ابدأ الاستكشاف',
                ],
                'description' => [
                    'en' => 'Browse thousands of properties and discover your perfect match today.',
                    'ar' => 'استعرض آلاف العقارات واكتشف ما يناسبك اليوم.',
                 ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CTA,
            ],
            [
                'id' => 17,
                'title' => [
                    'en' => 'Browse Properties',
                    'ar' => 'تصفح العقارات',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
               ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CTA_BUTTON,
            ],
            [
                'id' => 18,
                'title' => [
                    'en' => 'Contact',
                    'ar' => 'التواصل',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
               ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_TITLE,
            ],
            [
                'id' => 19,
                'title' => [
                    'en' => 'Phone',
                    'ar' => 'الهاتف',
              ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                 ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_PHONE_LABEL,
            ],
            [
                'id' => 20,
                'title' => [
                    'en' => 'Email',
                    'ar' => 'البريد الإلكتروني',
               ],
                'description' => [
                    'en' => '',
                    'ar' => '',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_EMAIL_LABEL,
            ],
            [
                'id' => 21,
                'title' => [
                    'en' => 'Not available',
                    'ar' => 'غير متوفر',
               ],
                'description' => [
                    'en' => '',
                    'ar' => '',
              ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_NOT_AVAILABLE,
            ],
            [
                'id' => 22,
                'title' => [
                    'en' => 'Call by phone',
                    'ar' => 'اتصال هاتفي',
                ],
                'description' => [
                    'en' => '',
                    'ar' => '',
              ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_PHONE_ARIA,
            ],
            [
                'id' => 23,
                'title' => [
                    'en' => 'Send email',
                    'ar' => 'إرسال بريد إلكتروني',
               ],
                'description' => [
                    'en' => '',
                    'ar' => '',
              ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CONTACT_EMAIL_ARIA,
            ],
            [
                'id' => 24,
                'title' => [
                    'en' => 'Privacy Policy',
                    'ar' => 'سياسة الخصوصية',
               ],
                'description' => [
                    'en' => '<p>At EAQAR, we respect your privacy and are committed to protecting your personal data.</p><p><strong>What Information We Collect:</strong> We collect account information including name, email, phone number, and property preferences to provide our real estate services.</p><p><strong>How We Use Your Data:</strong> We use your information to: facilitate property searches and listings, connect you with real estate agents, process transactions, send property updates and notifications, improve our platform, and comply with legal obligations.</p><p><strong>Data Protection:</strong> We implement industry-standard security measures to protect your data from unauthorized access, alteration, disclosure, or destruction.</p><p><strong>Your Rights:</strong> You have the right to access, update, or delete your personal data. Contact our support team for any privacy concerns.</p>',
                    'ar' => '<p>في إقرار، نحترم خصوصيتك والتزمنا بحماية بياناتك الشخصية.</p><p><strong>المعلومات التي نجمعها:</strong> نجمع معلومات الحساب بما فيها الاسم والبريد الإلكتروني ورقم الهاتف وتفضيلات العقارات لتقديم خدماتنا العقارية.</p><p><strong>كيفية استخدام بياناتك:</strong> نستخدم معلوماتك لـ: تسهيل البحث والإدراج والعقارات، تربطك بوكلاء العقارات، معالجة المعاملات، إرسال تحديثات وإشعارات العقارات، تحسين منصتنا، والامتثال للالتزامات القانونية.</p><p><strong>حماية البيانات:</strong> نطبق إجراءات أمنية معيارية صناعية لحماية بياناتك من الوصول غير المصرح به أو التعديل أو الكشف أو التدمير.</p><p><strong>حقوقك:</strong> لديك الحق في الوصول أو تحديث أو حذف بياناتك الشخصية. تواصل مع فريق الدعم لديك بشأن أي مخاوف تتعلق بالخصوصية.</p>',
                ],
                'page' => ConfigEnum::PRIVACY_POLICY,
                'key' => ConfigEnum::PRIVACY_POLICY,
            ],
            [
                'id' => 25,
                'title' => [
                    'en' => 'Terms, Conditions and Agreements',
                    'ar' => 'الشروط والأحكام والاتفاقيات',
               ],
                'description' => [
                    'en' => '<p>By accessing and using EAQAR, you agree to be bound by these Terms and Conditions.</p><p><strong>User Responsibilities:</strong></p><ul><li>Provide accurate and complete information when registering your account</li><li>Maintain the security of your account and password</li><li>Use the platform only for lawful purposes</li><li>Respect intellectual property rights of others</li><li>Not engage in fraudulent or deceptive practices</li></ul><p><strong>Property Listings:</strong></p><ul><li>Sellers and agents must provide accurate property information and photographs</li><li>Property descriptions must not contain misleading or false claims</li><li>All pricing information must be current and accurate</li></ul><p><strong>Limitation of Liability:</strong> EAQAR is provided on an \"as-is\" basis. We are not liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the platform.</p><p><strong>Modifications:</strong> We reserve the right to modify these terms at any time. Continued use of the platform signifies your acceptance of updated terms.</p>',
                    'ar' => '<p>بالوصول والاستخدام إقرار، فإنك توافق على الالتزام بهذه الشروط والأحكام.</p><p><strong>مسؤوليات المستخدم:</strong></p><ul><li>تقديم معلومات دقيقة وكاملة عند تسجيل حسابك</li><li>الحفاظ على أمان حسابك وكلمة المرور</li><li>استخدام المنصة فقط لأغراض قانونية</li><li>احترام حقوق الملكية الفكرية للآخرين</li><li>عدم الانخراط في ممارسات احتيالية أو خادعة</li></ul><p><strong>إدراجات العقارات:</strong></p><ul><li>يجب على البائعين والوكلاء تقديم معلومات دقيقة عن العقار والصور</li><li>لا يجب أن تحتوي وصف العقار على ادعاءات مضللة أو خاطئة</li><li>يجب أن تكون جميع معلومات التسعير الحالية والدقيقة</li></ul><p><strong>تحديد المسؤولية:</strong> إقرار توفر على أساس \"كما هي\". لا نتحمل مسؤولية عن أي أضرار غير مباشرة أو عرضية أو خاصة أو بعيدة من استخدامك للمنصة.</p><p><strong>التعديلات:</strong> نحتفظ بالحق في تعديل هذه الشروط في أي وقت. يعني الاستمرار في استخدام المنصة موافقتك على الشروط المحدثة.</p>',
               ],
                'page' => ConfigEnum::TERMS_CONDITIONS_AND_AGREEMENTS,
                'key' => ConfigEnum::TERMS_CONDITIONS_AND_AGREEMENTS,
            ],
        ];


        foreach ($data as $item) {
            $newService = ConfigTitle::updateOrCreate([
                'id' => $item['id'],
            ],[
                'page' => $item['page'],
                'key' => $item['key'],
            ]);
            foreach ($item['title'] as $locale => $translation) {
                $newService->translateOrNew($locale)->title = $translation;
            }
            foreach ($item['description'] as $locale => $translation) {
                $newService->translateOrNew($locale)->description = $translation;
            }
            $newService->save();
        }

    }
}

