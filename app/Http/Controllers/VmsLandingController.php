<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VmsLandingController extends Controller
{
    public static function getCountries(): array
    {
        return [
            'usa' => [
                'name' => 'USA',
                'full' => 'USA',
                'demo_label' => 'Request Demo in USA',
            ],
            'uk' => [
                'name' => 'UK',
                'full' => 'UK',
                'demo_label' => 'Request Demo in UK',
            ],
            'india' => [
                'name' => 'India',
                'full' => 'India',
                'demo_label' => 'Request Demo in India',
            ],
            'bahrain' => [
                'name' => 'Bahrain',
                'full' => 'Bahrain',
                'demo_label' => 'Request Demo in Bahrain',
            ],
            'kuwait' => [
                'name' => 'Kuwait',
                'full' => 'Kuwait',
                'demo_label' => 'Request Demo in Kuwait',
            ],
            'iraq' => [
                'name' => 'Iraq',
                'full' => 'Iraq',
                'demo_label' => 'Request Demo in Iraq',
            ],
            'oman' => [
                'name' => 'Oman',
                'full' => 'Oman',
                'demo_label' => 'Request Demo in Oman',
            ],
            'qatar' => [
                'name' => 'Qatar',
                'full' => 'Qatar',
                'demo_label' => 'Request Demo in Qatar',
            ],
            'saudi-arabia' => [
                'name' => 'Saudi Arabia',
                'full' => 'Saudi Arabia',
                'demo_label' => 'Request Demo in Saudi Arabia',
            ],
            'uae' => [
                'name' => 'UAE',
                'full' => 'UAE',
                'demo_label' => 'Request Demo in UAE',
            ],
            'south-africa' => [
                'name' => 'South Africa',
                'full' => 'South Africa',
                'demo_label' => 'Request Demo in South Africa',
            ],
            'africa' => [
                'name' => 'Africa',
                'full' => 'Africa',
                'demo_label' => 'Request Demo in Africa',
            ],
        ];
    }
    public function country(string $country)
    {
        $country = strtolower(trim($country));

        // Optional aliases
        $aliases = [
            'united-states' => 'usa',
            'us' => 'usa',
            'india' => 'india',
        ];
        $country = $aliases[$country] ?? $country;

        $countries = self::getCountries();

        abort_unless(isset($countries[$country]), 404);

        $c = $countries[$country];

        // ✅ SEO (dynamic)
        $seo = [
            'title' => "Top Visitor Management Software in {$c['name']} | Top Visitor Management System {$c['name']}",
            'description' => "Visitor Management Software in {$c['name']} for offices, corporate parks, factories, manufacturing units, warehouses, cold storage, hospitals, schools, holy places, malls, events, residential societies and public entry gates—single or multi-location control with gate passes, approvals, alerts and real-time visitor logs.",
            'keywords' => "visitor management system {$c['name']}, visitor management software {$c['name']}, single location visitor management {$c['name']}, multi location visitor management {$c['name']}, centralized visitor management platform {$c['name']}, visitor tracking system {$c['name']}, QR check-in system {$c['name']}, OTP visitor entry {$c['name']}, face recognition access control {$c['name']}, contractor management system {$c['name']}, paperless visitor register {$c['name']}",
            'og_image' => asset('images/visitor-management-system-main-img.png'),
        ];

        // ✅ FAQs (dynamic)
        $faqs = [
            [
                'q' => "How is the visitor management system useful in {$c['name']} for the visitor ?",
                'a' => "A Visitor Management System (VMS) is a digital check-in and security solution that records, verifies and manages visitors entering a workplace or facility—replacing paper registers with a faster, safer process.",
            ],
            [
                'q' => "Why companies use Visitor Management System (VMS) in {$c['name']}?",
                'a' => "To provide better security, compliance-ready logs, faster reception and a smoother visitor experience.",
            ],
            [
                'q' => "What are the key features of a visitor system in {$c['name']}?",
                'a' => "Stronger security, compliance-ready audit trails, faster reception and a smooth visitor experience with real-time tracking and smart automation.",
            ],
            [
                'q' => "How does a Visitor Management Systems work in {$c['name']}?",
                'a' => "Visitors register via QR code or manual entry, the host/department approves the request, visitors complete security/safety checks, then the system records Visitor In. At exit, security performs Security Out and the system records Visitor Out with accurate time-stamps—creating a complete, compliance-ready visitor log.",
            ],
            [
                'q' => "Which industries does N&T Software Visitor Management System support in {$c['name']}?",
                'a' => "Manufacturing plants, factories, warehouses, logistics hubs, corporate offices, IT parks, hospitals & clinics, laboratories, schools, colleges & universities, hotels & resorts, malls & retail stores, banks, government offices, construction sites, residential societies, data centers, power plants, cold storage & food processing units and event venues—with workflows configurable as per business needs.",
            ],
            [
                'q' => "Do you provide a custom visitor management software & mobile app as per business need in {$c['name']}?",
                'a' => "Yes. N&T Software provides a custom Visitor Management System and mobile app tailored to your business workflows, security policies, approval process, integrations and reporting needs.",
            ],
        ];

        return view('pages.vms-country', compact('c', 'seo', 'faqs'));
    }

    // ─── Indian States ────────────────────────────────────────────────────────

    public static function getStates(): array
    {
        return [
            // 28 States
            'andhra-pradesh'      => ['name' => 'Andhra Pradesh',      'full' => 'Andhra Pradesh'],
            'arunachal-pradesh'   => ['name' => 'Arunachal Pradesh',   'full' => 'Arunachal Pradesh'],
            'assam'               => ['name' => 'Assam',               'full' => 'Assam'],
            'bihar'               => ['name' => 'Bihar',               'full' => 'Bihar'],
            'chhattisgarh'        => ['name' => 'Chhattisgarh',        'full' => 'Chhattisgarh'],
            'goa'                 => ['name' => 'Goa',                 'full' => 'Goa'],
            'gujarat'             => ['name' => 'Gujarat',             'full' => 'Gujarat'],
            'haryana'             => ['name' => 'Haryana',             'full' => 'Haryana'],
            'himachal-pradesh'    => ['name' => 'Himachal Pradesh',    'full' => 'Himachal Pradesh'],
            'jharkhand'           => ['name' => 'Jharkhand',           'full' => 'Jharkhand'],
            'karnataka'           => ['name' => 'Karnataka',           'full' => 'Karnataka'],
            'kerala'              => ['name' => 'Kerala',              'full' => 'Kerala'],
            'madhya-pradesh'      => ['name' => 'Madhya Pradesh',      'full' => 'Madhya Pradesh'],
            'maharashtra'         => ['name' => 'Maharashtra',         'full' => 'Maharashtra'],
            'manipur'             => ['name' => 'Manipur',             'full' => 'Manipur'],
            'meghalaya'           => ['name' => 'Meghalaya',           'full' => 'Meghalaya'],
            'mizoram'             => ['name' => 'Mizoram',             'full' => 'Mizoram'],
            'nagaland'            => ['name' => 'Nagaland',            'full' => 'Nagaland'],
            'odisha'              => ['name' => 'Odisha',              'full' => 'Odisha'],
            'punjab'              => ['name' => 'Punjab',              'full' => 'Punjab'],
            'rajasthan'           => ['name' => 'Rajasthan',           'full' => 'Rajasthan'],
            'sikkim'              => ['name' => 'Sikkim',              'full' => 'Sikkim'],
            'tamil-nadu'          => ['name' => 'Tamil Nadu',          'full' => 'Tamil Nadu'],
            'telangana'           => ['name' => 'Telangana',           'full' => 'Telangana'],
            'tripura'             => ['name' => 'Tripura',             'full' => 'Tripura'],
            'uttar-pradesh'       => ['name' => 'Uttar Pradesh',       'full' => 'Uttar Pradesh'],
            'uttarakhand'         => ['name' => 'Uttarakhand',         'full' => 'Uttarakhand'],
            'west-bengal'         => ['name' => 'West Bengal',         'full' => 'West Bengal'],
            // 8 Union Territories
            'andaman-and-nicobar-islands' => ['name' => 'Andaman and Nicobar Islands', 'full' => 'Andaman and Nicobar Islands'],
            'chandigarh'          => ['name' => 'Chandigarh',          'full' => 'Chandigarh'],
            'dadra-and-nagar-haveli-and-daman-and-diu' => ['name' => 'Dadra and Nagar Haveli and Daman and Diu', 'full' => 'Dadra and Nagar Haveli and Daman and Diu'],
            'delhi'               => ['name' => 'Delhi',               'full' => 'Delhi'],
            'jammu-and-kashmir'   => ['name' => 'Jammu and Kashmir',   'full' => 'Jammu and Kashmir'],
            'ladakh'              => ['name' => 'Ladakh',              'full' => 'Ladakh'],
            'lakshadweep'         => ['name' => 'Lakshadweep',         'full' => 'Lakshadweep'],
            'puducherry'          => ['name' => 'Puducherry',          'full' => 'Puducherry'],
        ];
    }

    public function state(string $state)
    {
        $state = strtolower(trim($state));

        // Optional aliases
        $aliases = [
            'up'   => 'uttar-pradesh',
            'mp'   => 'madhya-pradesh',
            'ap'   => 'andhra-pradesh',
            'tn'   => 'tamil-nadu',
            'wb'   => 'west-bengal',
            'hp'   => 'himachal-pradesh',
            'j&k'  => 'jammu-and-kashmir',
            'jk'   => 'jammu-and-kashmir',
        ];
        $state = $aliases[$state] ?? $state;

        $states = self::getStates();

        abort_unless(isset($states[$state]), 404);

        $c = $states[$state];

        // SEO (dynamic)
        $seo = [
            'title'       => "Top Visitor Management Software in {$c['name']} | Top Visitor Management System {$c['name']}",
            'description' => "Visitor Management Software in {$c['name']} for offices, corporate parks, factories, manufacturing units, warehouses, cold storage, hospitals, schools, holy places, malls, events, residential societies and public entry gates—single or multi-location control with gate passes, approvals, alerts and real-time visitor logs.",
            'keywords'    => "visitor management system {$c['name']}, visitor management software {$c['name']}, single location visitor management {$c['name']}, multi location visitor management {$c['name']}, centralized visitor management platform {$c['name']}, visitor tracking system {$c['name']}, QR check-in system {$c['name']}, OTP visitor entry {$c['name']}, face recognition access control {$c['name']}, contractor management system {$c['name']}, paperless visitor register {$c['name']}",
            'og_image'    => asset('images/visitor-management-system-main-img.png'),
        ];

        // FAQs (dynamic)
        $faqs = [
            [
                'q' => "How is the visitor management system useful in {$c['name']} for the visitor?",
                'a' => "A Visitor Management System (VMS) is a digital check-in and security solution that records, verifies and manages visitors entering a workplace or facility—replacing paper registers with a faster, safer process.",
            ],
            [
                'q' => "Why companies use Visitor Management System (VMS) in {$c['name']}?",
                'a' => "To provide better security, compliance-ready logs, faster reception and a smoother visitor experience.",
            ],
            [
                'q' => "What are the key features of a visitor system in {$c['name']}?",
                'a' => "Stronger security, compliance-ready audit trails, faster reception and a smooth visitor experience with real-time tracking and smart automation.",
            ],
            [
                'q' => "How does a Visitor Management System work in {$c['name']}?",
                'a' => "Visitors register via QR code or manual entry, the host/department approves the request, visitors complete security/safety checks, then the system records Visitor In. At exit, security performs Security Out and the system records Visitor Out with accurate time-stamps—creating a complete, compliance-ready visitor log.",
            ],
            [
                'q' => "Which industries does N&T Software Visitor Management System support in {$c['name']}?",
                'a' => "Manufacturing plants, factories, warehouses, logistics hubs, corporate offices, IT parks, hospitals & clinics, laboratories, schools, colleges & universities, hotels & resorts, malls & retail stores, banks, government offices, construction sites, residential societies, data centers, power plants, cold storage & food processing units and event venues—with workflows configurable as per business needs.",
            ],
            [
                'q' => "Do you provide a custom visitor management software & mobile app as per business need in {$c['name']}?",
                'a' => "Yes. N&T Software provides a custom Visitor Management System and mobile app tailored to your business workflows, security policies, approval process, integrations and reporting needs.",
            ],
        ];

        return view('pages.vms-state', compact('c', 'seo', 'faqs'));
    }
}
