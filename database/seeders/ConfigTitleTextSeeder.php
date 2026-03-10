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
                    'en' => 'Copyright: Your Platform',
                    'ar' => 'حقوق النشر: منصتك',
                    
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
                    'en' => 'Your platform helps users achieve their goals with powerful tools and seamless experience.',
                    'ar' => 'منصتك تساعد المستخدمين على تحقيق أهدافهم بأدوات قوية وتجربة سلسة.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_HERO,
            ],
            [
                'id' => 3,
                'title' => [
                    'en' => 'About Our Platform',
                    'ar' => 'عن منصتنا',
                    
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
                    'en' => 'We provide users with powerful tools and reliable services to manage their work efficiently.',
                    'ar' => 'نوفر للمستخدمين أدوات قوية وخدمات موثوقة لإدارة أعمالهم بكفاءة.',
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
                    'en' => 'We believe every user deserves a smooth experience and every business deserves simple, reliable management tools.',
                    'ar' => 'نؤمن أن كل مستخدم يستحق تجربة سلسة وأن كل عمل يستحق أدوات إدارة بسيطة وموثوقة.',
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
                    'en' => 'Explore our platform features designed to simplify your workflow.',
                    'ar' => 'استكشف ميزات منصتنا المصممة لتبسيط سير عملك.',
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
                    'en' => 'To simplify workflows and provide the best user experience.',
                    'ar' => 'تبسيط سير العمل وتقديم أفضل تجربة للمستخدمين.',
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
                    'en' => 'To provide a reliable experience with clear tools, notifications, and user-centric communication.',
                    'ar' => 'تقديم تجربة موثوقة مع أدوات واضحة وإشعارات وتواصل موجه للمستخدمين.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_MISSION,
            ],
            [
                'id' => 10,
                'title' => [
                    'en' => 'Why Our Platform?',
                    'ar' => 'لماذا منصتنا؟',
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
                    'en' => 'Intuitive Interface',
                    'ar' => 'واجهة سهلة الاستخدام',
                ],
                'description' => [
                    'en' => 'A clean, user-friendly interface that helps users navigate and manage their work quickly.',
                    'ar' => 'واجهة نظيفة وسهلة الاستخدام تساعد المستخدمين على التنقل وإدارة أعمالهم بسرعة.',
                  ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_SIMPLE_UI,
            ],
            [
                'id' => 12,
                'title' => [
                    'en' => 'Smart Tools',
                    'ar' => 'أدوات ذكية',
                ],
                'description' => [
                    'en' => 'Flexible tools that adapt to different use cases and requirements.',
                    'ar' => 'أدوات مرنة تتكيف مع حالات الاستخدام والمتطلبات المختلفة.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_SMART_TOOLS,
            ],
            [
                'id' => 13,
                'title' => [
                    'en' => 'Professional Quality',
                    'ar' => 'جودة احترافية',
                ],
                'description' => [
                    'en' => 'A reliable experience for users and businesses, with secure data handling and clear workflows.',
                    'ar' => 'تجربة موثوقة للمستخدمين والأعمال مع حماية للبيانات وسير عمل واضح.',
               ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_PRO_WITHOUT_COMPLEXITY,
            ],
            [
                'id' => 14,
                'title' => [
                    'en' => 'For All Business Sizes',
                    'ar' => 'لجميع أحجام الأعمال',
               ],
                'description' => [
                    'en' => 'Whether you\'re a freelancer or a large organization, our platform scales to meet your needs.',
                    'ar' => 'سواء كنت عاملاً حراً أو منظمة كبيرة، منصتنا تتكيف لتلبية احتياجاتك.',
                 ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_FOR_INDIVIDUALS_COMPANIES,
            ],
            [
                'id' => 15,
                'title' => [
                    'en' => 'Continuous Support',
                    'ar' => 'دعم مستمر',
               ],
                'description' => [
                    'en' => 'Regular updates with new features and language support. Our team is always here to help.',
                    'ar' => 'تحديثات منتظمة مع ميزات جديدة ودعم لغات متعددة. فريقنا موجود دائمًا للمساعدة.',
                ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_WHY_CONTINUOUS_SUPPORT,
            ],
            [
                'id' => 16,
                'title' => [
                    'en' => 'Get Started',
                    'ar' => 'ابدأ الآن',
                ],
                'description' => [
                    'en' => 'Get started today and explore all our features.',
                    'ar' => 'ابدأ اليوم واستكشف جميع ميزاتنا.',
                 ],
                'page' => ConfigEnum::ABOUT_US,
                'key' => ConfigEnum::ABOUT_US_CTA,
            ],
            [
                'id' => 17,
                'title' => [
                    'en' => 'Get Started Free',
                    'ar' => 'ابدأ مجانًا',
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
                    'en' => '<p>We respect your privacy. We collect account information to provide our services.</p><p>We use your data to operate the platform, send notifications, and improve service. We protect data with appropriate security measures.</p><p>If you have questions, please contact support.</p>',
                    'ar' => '<p>نحن نحترم خصوصيتك. نجمع معلومات الحساب لتقديم خدماتنا.</p><p>نستخدم بياناتك لتشغيل المنصة وإرسال الإشعارات وتحسين الخدمة، ونحمي البيانات بإجراءات أمنية مناسبة.</p><p>للاستفسار تواصل معنا.</p>',
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
                    'en' => '<p>By using our platform, you agree to these terms.</p><ul><li>Provide accurate account information.</li><li>Use the platform for legitimate purposes.</li><li>Handle data responsibly.</li></ul><p>We may update these terms as needed.</p>',
                    'ar' => '<p>باستخدام منصتنا، فإنك توافق على هذه الشروط.</p><ul><li>تقديم معلومات حساب دقيقة.</li><li>استخدام المنصة لأغراض مشروعة.</li><li>التعامل مع البيانات بمسؤولية.</li></ul><p>قد نقوم بتحديث هذه الشروط عند الحاجة.</p>',
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

