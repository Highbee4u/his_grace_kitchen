<?php

namespace App\Support;

use App\Models\MenuItem;
use App\Models\SiteSetting;

class SeoHelper
{
    /**
     * Generate Schema.org LocalBusiness / Restaurant JSON-LD.
     */
    public static function restaurantSchema(): array
    {
        $siteName = SiteSetting::getSiteName();
        $phone = SiteSetting::get('phone', '+234 800 000 0000');
        $email = SiteSetting::get('contact_email', 'orders@africankitchen.test');
        $address = SiteSetting::get('address', '14 Admiralty Way, Lekki Phase 1, Lagos, Nigeria');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Restaurant',
            'name' => $siteName,
            'image' => asset('images/logo.png'),
            '@id' => url('/'),
            'url' => url('/'),
            'telephone' => $phone,
            'email' => $email,
            'priceRange' => '₦₦ - ₦₦₦₦',
            'servesCuisine' => ['Nigerian', 'West African', 'Traditional African'],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
                'addressLocality' => 'Lekki',
                'addressRegion' => 'Lagos',
                'postalCode' => '105102',
                'addressCountry' => 'NG',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => 6.4474,
                'longitude' => 3.4735,
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => [
                        'Monday',
                        'Tuesday',
                        'Wednesday',
                        'Thursday',
                        'Friday',
                        'Saturday',
                        'Sunday',
                    ],
                    'opens' => '10:00',
                    'closes' => '22:00',
                ],
            ],
            'hasMenu' => url('/menu'),
            'acceptsReservations' => 'True',
            'currenciesAccepted' => 'NGN, GBP, USD, CAD, EUR',
            'paymentAccepted' => 'Cash, Credit Card, Bank Transfer, Paystack, Stripe',
        ];
    }

    /**
     * Generate Schema.org MenuItem / Product JSON-LD for a dish.
     */
    public static function menuItemSchema(MenuItem $item): array
    {
        $priceDecimal = number_format(($item->price_minor ?? 0) / 100, 2, '.', '');

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'MenuItem',
            'name' => $item->name,
            'description' => $item->description ?? $item->name,
            'image' => $item->image_url ?? asset('images/placeholder-dish.jpg'),
            'inLanguage' => 'en',
            'offers' => [
                '@type' => 'Offer',
                'price' => $priceDecimal,
                'priceCurrency' => 'NGN',
                'priceValidUntil' => now()->addYear()->format('Y-m-d'),
                'availability' => $item->is_available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => route('menu.show', $item->slug),
            ],
        ];

        if ($item->is_vegetarian) {
            $schema['suitableForDiet'] = 'https://schema.org/VegetarianDiet';
        }

        if ($item->category) {
            $schema['menuAddOn'] = $item->category->name;
        }

        return $schema;
    }

    /**
     * Generate Schema.org FAQPage JSON-LD.
     *
     * @param  array<int, array{question: string, answer: string}>  $faqs
     */
    public static function faqSchema(array $faqs): array
    {
        $entities = [];

        foreach ($faqs as $faq) {
            if (! empty($faq['question']) && ! empty($faq['answer'])) {
                $entities[] = [
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'],
                    ],
                ];
            }
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }

    /**
     * Generate Schema.org BreadcrumbList JSON-LD.
     *
     * @param  array<int, array{name: string, url: string}>  $breadcrumbs
     */
    public static function breadcrumbSchema(array $breadcrumbs): array
    {
        $items = [];
        $position = 1;

        foreach ($breadcrumbs as $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $crumb['name'],
                'item' => $crumb['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Render an array as a sanitized JSON-LD script tag.
     */
    public static function render(array $data): string
    {
        return '<script type="application/ld+json">'.json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</script>';
    }
}
