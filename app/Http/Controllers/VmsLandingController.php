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
            'ethiopia' => [
                'name' => 'Ethiopia',
                'full' => 'Ethiopia',
                'demo_label' => 'Request Demo in Ethiopia',
            ],
            'ghana' => [
                'name' => 'Ghana',
                'full' => 'Ghana',
                'demo_label' => 'Request Demo in Ghana',
            ],
            'tanzania' => [
                'name' => 'Tanzania',
                'full' => 'Tanzania',
                'demo_label' => 'Request Demo in Tanzania',
            ],
            'kenya' => [
                'name' => 'Kenya',
                'full' => 'Kenya',
                'demo_label' => 'Request Demo in Kenya',
            ],
            'canada' => [
                'name' => 'Canada',
                'full' => 'Canada',
                'demo_label' => 'Request Demo in Canada',
            ],
            'mexico' => [
                'name' => 'Mexico',
                'full' => 'Mexico',
                'demo_label' => 'Request Demo in Mexico',
            ],
            'guatemala' => [
                'name' => 'Guatemala',
                'full' => 'Guatemala',
                'demo_label' => 'Request Demo in Guatemala',
            ],
            'belize' => [
                'name' => 'Belize',
                'full' => 'Belize',
                'demo_label' => 'Request Demo in Belize',
            ],
            'el-salvador' => [
                'name' => 'El Salvador',
                'full' => 'El Salvador',
                'demo_label' => 'Request Demo in El Salvador',
            ],
            'honduras' => [
                'name' => 'Honduras',
                'full' => 'Honduras',
                'demo_label' => 'Request Demo in Honduras',
            ],
            'nicaragua' => [
                'name' => 'Nicaragua',
                'full' => 'Nicaragua',
                'demo_label' => 'Request Demo in Nicaragua',
            ],
            'costa-rica' => [
                'name' => 'Costa Rica',
                'full' => 'Costa Rica',
                'demo_label' => 'Request Demo in Costa Rica',
            ],
            'panama' => [
                'name' => 'Panama',
                'full' => 'Panama',
                'demo_label' => 'Request Demo in Panama',
            ],
            'cuba' => [
                'name' => 'Cuba',
                'full' => 'Cuba',
                'demo_label' => 'Request Demo in Cuba',
            ],
            'haiti' => [
                'name' => 'Haiti',
                'full' => 'Haiti',
                'demo_label' => 'Request Demo in Haiti',
            ],
            'dominican-republic' => [
                'name' => 'Dominican Republic',
                'full' => 'Dominican Republic',
                'demo_label' => 'Request Demo in Dominican Republic',
            ],
            'bahamas' => [
                'name' => 'Bahamas',
                'full' => 'Bahamas',
                'demo_label' => 'Request Demo in Bahamas',
            ],
            'jamaica' => [
                'name' => 'Jamaica',
                'full' => 'Jamaica',
                'demo_label' => 'Request Demo in Jamaica',
            ],
            'barbados' => [
                'name' => 'Barbados',
                'full' => 'Barbados',
                'demo_label' => 'Request Demo in Barbados',
            ],
            'trinidad-and-tobago' => [
                'name' => 'Trinidad and Tobago',
                'full' => 'Trinidad and Tobago',
                'demo_label' => 'Request Demo in Trinidad and Tobago',
            ],
            'antigua-and-barbuda' => [
                'name' => 'Antigua and Barbuda',
                'full' => 'Antigua and Barbuda',
                'demo_label' => 'Request Demo in Antigua and Barbuda',
            ],
            'dubai' => [
                'name' => 'Dubai',
                'full' => 'Dubai',
                'demo_label' => 'Request Demo in Dubai',
            ],
            'abu-dhabi' => [
                'name' => 'Abu Dhabi',
                'full' => 'Abu Dhabi',
                'demo_label' => 'Request Demo in Abu Dhabi',
            ],
            'sharjah' => [
                'name' => 'Sharjah',
                'full' => 'Sharjah',
                'demo_label' => 'Request Demo in Sharjah',
            ],
            'ajman' => [
                'name' => 'Ajman',
                'full' => 'Ajman',
                'demo_label' => 'Request Demo in Ajman',
            ],
            'ras-al-khaimah' => [
                'name' => 'Ras Al Khaimah',
                'full' => 'Ras Al Khaimah',
                'demo_label' => 'Request Demo in Ras Al Khaimah',
            ],
            'al-ain' => [
                'name' => 'Al Ain',
                'full' => 'Al Ain',
                'demo_label' => 'Request Demo in Al Ain',
            ],
            'riyadh' => [
                'name' => 'Riyadh',
                'full' => 'Riyadh',
                'demo_label' => 'Request Demo in Riyadh',
            ],
            'jeddah' => [
                'name' => 'Jeddah',
                'full' => 'Jeddah',
                'demo_label' => 'Request Demo in Jeddah',
            ],
            'dammam' => [
                'name' => 'Dammam',
                'full' => 'Dammam',
                'demo_label' => 'Request Demo in Dammam',
            ],
            'al-khobar' => [
                'name' => 'Al Khobar',
                'full' => 'Al Khobar',
                'demo_label' => 'Request Demo in Al Khobar',
            ],
            'dhahran' => [
                'name' => 'Dhahran',
                'full' => 'Dhahran',
                'demo_label' => 'Request Demo in Dhahran',
            ],
            'makkah' => [
                'name' => 'Makkah',
                'full' => 'Makkah',
                'demo_label' => 'Request Demo in Makkah',
            ],
            'madinah' => [
                'name' => 'Madinah',
                'full' => 'Madinah',
                'demo_label' => 'Request Demo in Madinah',
            ],
            'doha' => [
                'name' => 'Doha',
                'full' => 'Doha',
                'demo_label' => 'Request Demo in Doha',
            ],
            'lusail' => [
                'name' => 'Lusail',
                'full' => 'Lusail',
                'demo_label' => 'Request Demo in Lusail',
            ],
            'algeria' => [
                'name' => 'Algeria',
                'full' => 'Algeria',
                'demo_label' => 'Request Demo in Algeria',
            ],
            'angola' => [
                'name' => 'Angola',
                'full' => 'Angola',
                'demo_label' => 'Request Demo in Angola',
            ],
            'benin' => [
                'name' => 'Benin',
                'full' => 'Benin',
                'demo_label' => 'Request Demo in Benin',
            ],
            'botswana' => [
                'name' => 'Botswana',
                'full' => 'Botswana',
                'demo_label' => 'Request Demo in Botswana',
            ],
            'burkina-faso' => [
                'name' => 'Burkina Faso',
                'full' => 'Burkina Faso',
                'demo_label' => 'Request Demo in Burkina Faso',
            ],
            'burundi' => [
                'name' => 'Burundi',
                'full' => 'Burundi',
                'demo_label' => 'Request Demo in Burundi',
            ],
            'cabo-verde' => [
                'name' => 'Cabo Verde',
                'full' => 'Cabo Verde',
                'demo_label' => 'Request Demo in Cabo Verde',
            ],
            'cameroon' => [
                'name' => 'Cameroon',
                'full' => 'Cameroon',
                'demo_label' => 'Request Demo in Cameroon',
            ],
            'central-african-republic' => [
                'name' => 'Central African Republic',
                'full' => 'Central African Republic',
                'demo_label' => 'Request Demo in Central African Republic',
            ],
            'chad' => [
                'name' => 'Chad',
                'full' => 'Chad',
                'demo_label' => 'Request Demo in Chad',
            ],
            'comoros' => [
                'name' => 'Comoros',
                'full' => 'Comoros',
                'demo_label' => 'Request Demo in Comoros',
            ],
            'republic-of-the-congo' => [
                'name' => 'Republic of the Congo',
                'full' => 'Republic of the Congo',
                'demo_label' => 'Request Demo in Republic of the Congo',
            ],
            'cote-d-ivoire' => [
                'name' => 'Côte d\'Ivoire',
                'full' => 'Côte d\'Ivoire',
                'demo_label' => 'Request Demo in Côte d\'Ivoire',
            ],
            'democratic-republic-of-the-congo' => [
                'name' => 'Democratic Republic of the Congo',
                'full' => 'Democratic Republic of the Congo',
                'demo_label' => 'Request Demo in Democratic Republic of the Congo',
            ],
            'djibouti' => [
                'name' => 'Djibouti',
                'full' => 'Djibouti',
                'demo_label' => 'Request Demo in Djibouti',
            ],
            'egypt' => [
                'name' => 'Egypt',
                'full' => 'Egypt',
                'demo_label' => 'Request Demo in Egypt',
            ],
            'equatorial-guinea' => [
                'name' => 'Equatorial Guinea',
                'full' => 'Equatorial Guinea',
                'demo_label' => 'Request Demo in Equatorial Guinea',
            ],
            'eritrea' => [
                'name' => 'Eritrea',
                'full' => 'Eritrea',
                'demo_label' => 'Request Demo in Eritrea',
            ],
            'eswatini' => [
                'name' => 'Eswatini',
                'full' => 'Eswatini',
                'demo_label' => 'Request Demo in Eswatini',
            ],
            'gabon' => [
                'name' => 'Gabon',
                'full' => 'Gabon',
                'demo_label' => 'Request Demo in Gabon',
            ],
            'the-gambia' => [
                'name' => 'The Gambia',
                'full' => 'The Gambia',
                'demo_label' => 'Request Demo in The Gambia',
            ],
            'guinea' => [
                'name' => 'Guinea',
                'full' => 'Guinea',
                'demo_label' => 'Request Demo in Guinea',
            ],
            'guinea-bissau' => [
                'name' => 'Guinea-Bissau',
                'full' => 'Guinea-Bissau',
                'demo_label' => 'Request Demo in Guinea-Bissau',
            ],
            'lesotho' => [
                'name' => 'Lesotho',
                'full' => 'Lesotho',
                'demo_label' => 'Request Demo in Lesotho',
            ],
            'liberia' => [
                'name' => 'Liberia',
                'full' => 'Liberia',
                'demo_label' => 'Request Demo in Liberia',
            ],
            'libya' => [
                'name' => 'Libya',
                'full' => 'Libya',
                'demo_label' => 'Request Demo in Libya',
            ],
            'madagascar' => [
                'name' => 'Madagascar',
                'full' => 'Madagascar',
                'demo_label' => 'Request Demo in Madagascar',
            ],
            'malawi' => [
                'name' => 'Malawi',
                'full' => 'Malawi',
                'demo_label' => 'Request Demo in Malawi',
            ],
            'mali' => [
                'name' => 'Mali',
                'full' => 'Mali',
                'demo_label' => 'Request Demo in Mali',
            ],
            'mauritania' => [
                'name' => 'Mauritania',
                'full' => 'Mauritania',
                'demo_label' => 'Request Demo in Mauritania',
            ],
            'mauritius' => [
                'name' => 'Mauritius',
                'full' => 'Mauritius',
                'demo_label' => 'Request Demo in Mauritius',
            ],
            'morocco' => [
                'name' => 'Morocco',
                'full' => 'Morocco',
                'demo_label' => 'Request Demo in Morocco',
            ],
            'mozambique' => [
                'name' => 'Mozambique',
                'full' => 'Mozambique',
                'demo_label' => 'Request Demo in Mozambique',
            ],
            'namibia' => [
                'name' => 'Namibia',
                'full' => 'Namibia',
                'demo_label' => 'Request Demo in Namibia',
            ],
            'niger' => [
                'name' => 'Niger',
                'full' => 'Niger',
                'demo_label' => 'Request Demo in Niger',
            ],
            'nigeria' => [
                'name' => 'Nigeria',
                'full' => 'Nigeria',
                'demo_label' => 'Request Demo in Nigeria',
            ],
            'rwanda' => [
                'name' => 'Rwanda',
                'full' => 'Rwanda',
                'demo_label' => 'Request Demo in Rwanda',
            ],
            'sao-tome-and-principe' => [
                'name' => 'São Tomé and Príncipe',
                'full' => 'São Tomé and Príncipe',
                'demo_label' => 'Request Demo in São Tomé and Príncipe',
            ],
            'senegal' => [
                'name' => 'Senegal',
                'full' => 'Senegal',
                'demo_label' => 'Request Demo in Senegal',
            ],
            'seychelles' => [
                'name' => 'Seychelles',
                'full' => 'Seychelles',
                'demo_label' => 'Request Demo in Seychelles',
            ],
            'sierra-leone' => [
                'name' => 'Sierra Leone',
                'full' => 'Sierra Leone',
                'demo_label' => 'Request Demo in Sierra Leone',
            ],
            'somalia' => [
                'name' => 'Somalia',
                'full' => 'Somalia',
                'demo_label' => 'Request Demo in Somalia',
            ],
            'south-sudan' => [
                'name' => 'South Sudan',
                'full' => 'South Sudan',
                'demo_label' => 'Request Demo in South Sudan',
            ],
            'sudan' => [
                'name' => 'Sudan',
                'full' => 'Sudan',
                'demo_label' => 'Request Demo in Sudan',
            ],
            'togo' => [
                'name' => 'Togo',
                'full' => 'Togo',
                'demo_label' => 'Request Demo in Togo',
            ],
            'tunisia' => [
                'name' => 'Tunisia',
                'full' => 'Tunisia',
                'demo_label' => 'Request Demo in Tunisia',
            ],
            'uganda' => [
                'name' => 'Uganda',
                'full' => 'Uganda',
                'demo_label' => 'Request Demo in Uganda',
            ],
            'zambia' => [
                'name' => 'Zambia',
                'full' => 'Zambia',
                'demo_label' => 'Request Demo in Zambia',
            ],
            'russia' => [
                'name' => 'Russia',
                'full' => 'Russia',
                'demo_label' => 'Request Demo in Russia',
            ],
            'germany' => [
                'name' => 'Germany',
                'full' => 'Germany',
                'demo_label' => 'Request Demo in Germany',
            ],
            'france' => [
                'name' => 'France',
                'full' => 'France',
                'demo_label' => 'Request Demo in France',
            ],
            'italy' => [
                'name' => 'Italy',
                'full' => 'Italy',
                'demo_label' => 'Request Demo in Italy',
            ],
            'spain' => [
                'name' => 'Spain',
                'full' => 'Spain',
                'demo_label' => 'Request Demo in Spain',
            ],
            'ukraine' => [
                'name' => 'Ukraine',
                'full' => 'Ukraine',
                'demo_label' => 'Request Demo in Ukraine',
            ],
            'poland' => [
                'name' => 'Poland',
                'full' => 'Poland',
                'demo_label' => 'Request Demo in Poland',
            ],
            'romania' => [
                'name' => 'Romania',
                'full' => 'Romania',
                'demo_label' => 'Request Demo in Romania',
            ],
            'netherlands' => [
                'name' => 'Netherlands',
                'full' => 'Netherlands',
                'demo_label' => 'Request Demo in Netherlands',
            ],
            'belgium' => [
                'name' => 'Belgium',
                'full' => 'Belgium',
                'demo_label' => 'Request Demo in Belgium',
            ],
            'sweden' => [
                'name' => 'Sweden',
                'full' => 'Sweden',
                'demo_label' => 'Request Demo in Sweden',
            ],
            'czech-republic' => [
                'name' => 'Czech Republic',
                'full' => 'Czech Republic',
                'demo_label' => 'Request Demo in Czech Republic',
            ],
            'portugal' => [
                'name' => 'Portugal',
                'full' => 'Portugal',
                'demo_label' => 'Request Demo in Portugal',
            ],
            'greece' => [
                'name' => 'Greece',
                'full' => 'Greece',
                'demo_label' => 'Request Demo in Greece',
            ],
            'hungary' => [
                'name' => 'Hungary',
                'full' => 'Hungary',
                'demo_label' => 'Request Demo in Hungary',
            ],
            'austria' => [
                'name' => 'Austria',
                'full' => 'Austria',
                'demo_label' => 'Request Demo in Austria',
            ],
            'switzerland' => [
                'name' => 'Switzerland',
                'full' => 'Switzerland',
                'demo_label' => 'Request Demo in Switzerland',
            ],
            'belarus' => [
                'name' => 'Belarus',
                'full' => 'Belarus',
                'demo_label' => 'Request Demo in Belarus',
            ],
            'bulgaria' => [
                'name' => 'Bulgaria',
                'full' => 'Bulgaria',
                'demo_label' => 'Request Demo in Bulgaria',
            ],
            'serbia' => [
                'name' => 'Serbia',
                'full' => 'Serbia',
                'demo_label' => 'Request Demo in Serbia',
            ],
            'denmark' => [
                'name' => 'Denmark',
                'full' => 'Denmark',
                'demo_label' => 'Request Demo in Denmark',
            ],
            'norway' => [
                'name' => 'Norway',
                'full' => 'Norway',
                'demo_label' => 'Request Demo in Norway',
            ],
            'finland' => [
                'name' => 'Finland',
                'full' => 'Finland',
                'demo_label' => 'Request Demo in Finland',
            ],
            'slovakia' => [
                'name' => 'Slovakia',
                'full' => 'Slovakia',
                'demo_label' => 'Request Demo in Slovakia',
            ],
            'ireland' => [
                'name' => 'Ireland',
                'full' => 'Ireland',
                'demo_label' => 'Request Demo in Ireland',
            ],
            'croatia' => [
                'name' => 'Croatia',
                'full' => 'Croatia',
                'demo_label' => 'Request Demo in Croatia',
            ],
            'bosnia-and-herzegovina' => [
                'name' => 'Bosnia and Herzegovina',
                'full' => 'Bosnia and Herzegovina',
                'demo_label' => 'Request Demo in Bosnia and Herzegovina',
            ],
            'moldova' => [
                'name' => 'Moldova',
                'full' => 'Moldova',
                'demo_label' => 'Request Demo in Moldova',
            ],
            'lithuania' => [
                'name' => 'Lithuania',
                'full' => 'Lithuania',
                'demo_label' => 'Request Demo in Lithuania',
            ],
            'albania' => [
                'name' => 'Albania',
                'full' => 'Albania',
                'demo_label' => 'Request Demo in Albania',
            ],
            'slovenia' => [
                'name' => 'Slovenia',
                'full' => 'Slovenia',
                'demo_label' => 'Request Demo in Slovenia',
            ],
            'latvia' => [
                'name' => 'Latvia',
                'full' => 'Latvia',
                'demo_label' => 'Request Demo in Latvia',
            ],
            'north-macedonia' => [
                'name' => 'North Macedonia',
                'full' => 'North Macedonia',
                'demo_label' => 'Request Demo in North Macedonia',
            ],
            'estonia' => [
                'name' => 'Estonia',
                'full' => 'Estonia',
                'demo_label' => 'Request Demo in Estonia',
            ],
            'luxembourg' => [
                'name' => 'Luxembourg',
                'full' => 'Luxembourg',
                'demo_label' => 'Request Demo in Luxembourg',
            ],
            'montenegro' => [
                'name' => 'Montenegro',
                'full' => 'Montenegro',
                'demo_label' => 'Request Demo in Montenegro',
            ],
            'malta' => [
                'name' => 'Malta',
                'full' => 'Malta',
                'demo_label' => 'Request Demo in Malta',
            ],
            'iceland' => [
                'name' => 'Iceland',
                'full' => 'Iceland',
                'demo_label' => 'Request Demo in Iceland',
            ],
            'andorra' => [
                'name' => 'Andorra',
                'full' => 'Andorra',
                'demo_label' => 'Request Demo in Andorra',
            ],
            'liechtenstein' => [
                'name' => 'Liechtenstein',
                'full' => 'Liechtenstein',
                'demo_label' => 'Request Demo in Liechtenstein',
            ],
            'monaco' => [
                'name' => 'Monaco',
                'full' => 'Monaco',
                'demo_label' => 'Request Demo in Monaco',
            ],
            'san-marino' => [
                'name' => 'San Marino',
                'full' => 'San Marino',
                'demo_label' => 'Request Demo in San Marino',
            ],
            'holy-see' => [
                'name' => 'Holy See',
                'full' => 'Holy See',
                'demo_label' => 'Request Demo in Holy See',
            ],
            'zimbabwe' => [
                'name' => 'Zimbabwe',
                'full' => 'Zimbabwe',
                'demo_label' => 'Request Demo in Zimbabwe',
            ],
            'dominica' => [
                'name' => 'Dominica',
                'full' => 'Dominica',
                'demo_label' => 'Request Demo in Dominica',
            ],
            'grenada' => [
                'name' => 'Grenada',
                'full' => 'Grenada',
                'demo_label' => 'Request Demo in Grenada',
            ],
            'saint-kitts-and-nevis' => [
                'name' => 'Saint Kitts and Nevis',
                'full' => 'Saint Kitts and Nevis',
                'demo_label' => 'Request Demo in Saint Kitts and Nevis',
            ],
            'saint-lucia' => [
                'name' => 'Saint Lucia',
                'full' => 'Saint Lucia',
                'demo_label' => 'Request Demo in Saint Lucia',
            ],
            'saint-vincent-and-the-grenadines' => [
                'name' => 'Saint Vincent and the Grenadines',
                'full' => 'Saint Vincent and the Grenadines',
                'demo_label' => 'Request Demo in Saint Vincent and the Grenadines',
            ],
            'argentina' => [
                'name' => 'Argentina',
                'full' => 'Argentina',
                'demo_label' => 'Request Demo in Argentina',
            ],
            'bolivia' => [
                'name' => 'Bolivia',
                'full' => 'Bolivia',
                'demo_label' => 'Request Demo in Bolivia',
            ],
            'brazil' => [
                'name' => 'Brazil',
                'full' => 'Brazil',
                'demo_label' => 'Request Demo in Brazil',
            ],
            'chile' => [
                'name' => 'Chile',
                'full' => 'Chile',
                'demo_label' => 'Request Demo in Chile',
            ],
            'colombia' => [
                'name' => 'Colombia',
                'full' => 'Colombia',
                'demo_label' => 'Request Demo in Colombia',
            ],
            'ecuador' => [
                'name' => 'Ecuador',
                'full' => 'Ecuador',
                'demo_label' => 'Request Demo in Ecuador',
            ],
            'guyana' => [
                'name' => 'Guyana',
                'full' => 'Guyana',
                'demo_label' => 'Request Demo in Guyana',
            ],
            'paraguay' => [
                'name' => 'Paraguay',
                'full' => 'Paraguay',
                'demo_label' => 'Request Demo in Paraguay',
            ],
            'peru' => [
                'name' => 'Peru',
                'full' => 'Peru',
                'demo_label' => 'Request Demo in Peru',
            ],
            'suriname' => [
                'name' => 'Suriname',
                'full' => 'Suriname',
                'demo_label' => 'Request Demo in Suriname',
            ],
            'uruguay' => [
                'name' => 'Uruguay',
                'full' => 'Uruguay',
                'demo_label' => 'Request Demo in Uruguay',
            ],
            'venezuela' => [
                'name' => 'Venezuela',
                'full' => 'Venezuela',
                'demo_label' => 'Request Demo in Venezuela',
            ],
            'afghanistan' => [
                'name' => 'Afghanistan',
                'full' => 'Afghanistan',
                'demo_label' => 'Request Demo in Afghanistan',
            ],
            'armenia' => [
                'name' => 'Armenia',
                'full' => 'Armenia',
                'demo_label' => 'Request Demo in Armenia',
            ],
            'azerbaijan' => [
                'name' => 'Azerbaijan',
                'full' => 'Azerbaijan',
                'demo_label' => 'Request Demo in Azerbaijan',
            ],
            'bangladesh' => [
                'name' => 'Bangladesh',
                'full' => 'Bangladesh',
                'demo_label' => 'Request Demo in Bangladesh',
            ],
            'bhutan' => [
                'name' => 'Bhutan',
                'full' => 'Bhutan',
                'demo_label' => 'Request Demo in Bhutan',
            ],
            'brunei' => [
                'name' => 'Brunei',
                'full' => 'Brunei',
                'demo_label' => 'Request Demo in Brunei',
            ],
            'cambodia' => [
                'name' => 'Cambodia',
                'full' => 'Cambodia',
                'demo_label' => 'Request Demo in Cambodia',
            ],
            'cyprus' => [
                'name' => 'Cyprus',
                'full' => 'Cyprus',
                'demo_label' => 'Request Demo in Cyprus',
            ],
            'georgia' => [
                'name' => 'Georgia',
                'full' => 'Georgia',
                'demo_label' => 'Request Demo in Georgia',
            ],
            'indonesia' => [
                'name' => 'Indonesia',
                'full' => 'Indonesia',
                'demo_label' => 'Request Demo in Indonesia',
            ],
            'iran' => [
                'name' => 'Iran',
                'full' => 'Iran',
                'demo_label' => 'Request Demo in Iran',
            ],
            'jordan' => [
                'name' => 'Jordan',
                'full' => 'Jordan',
                'demo_label' => 'Request Demo in Jordan',
            ],
            'kazakhstan' => [
                'name' => 'Kazakhstan',
                'full' => 'Kazakhstan',
                'demo_label' => 'Request Demo in Kazakhstan',
            ],
            'kyrgyzstan' => [
                'name' => 'Kyrgyzstan',
                'full' => 'Kyrgyzstan',
                'demo_label' => 'Request Demo in Kyrgyzstan',
            ],
            'laos' => [
                'name' => 'Laos',
                'full' => 'Laos',
                'demo_label' => 'Request Demo in Laos',
            ],
            'lebanon' => [
                'name' => 'Lebanon',
                'full' => 'Lebanon',
                'demo_label' => 'Request Demo in Lebanon',
            ],
            'malaysia' => [
                'name' => 'Malaysia',
                'full' => 'Malaysia',
                'demo_label' => 'Request Demo in Malaysia',
            ],
            'maldives' => [
                'name' => 'Maldives',
                'full' => 'Maldives',
                'demo_label' => 'Request Demo in Maldives',
            ],
            'mongolia' => [
                'name' => 'Mongolia',
                'full' => 'Mongolia',
                'demo_label' => 'Request Demo in Mongolia',
            ],
            'myanmar' => [
                'name' => 'Myanmar',
                'full' => 'Myanmar',
                'demo_label' => 'Request Demo in Myanmar',
            ],
            'nepal' => [
                'name' => 'Nepal',
                'full' => 'Nepal',
                'demo_label' => 'Request Demo in Nepal',
            ],
            'philippines' => [
                'name' => 'Philippines',
                'full' => 'Philippines',
                'demo_label' => 'Request Demo in Philippines',
            ],
            'singapore' => [
                'name' => 'Singapore',
                'full' => 'Singapore',
                'demo_label' => 'Request Demo in Singapore',
            ],
            'south-korea' => [
                'name' => 'South Korea',
                'full' => 'South Korea',
                'demo_label' => 'Request Demo in South Korea',
            ],
            'sri-lanka' => [
                'name' => 'Sri Lanka',
                'full' => 'Sri Lanka',
                'demo_label' => 'Request Demo in Sri Lanka',
            ],
            'syria' => [
                'name' => 'Syria',
                'full' => 'Syria',
                'demo_label' => 'Request Demo in Syria',
            ],
            'tajikistan' => [
                'name' => 'Tajikistan',
                'full' => 'Tajikistan',
                'demo_label' => 'Request Demo in Tajikistan',
            ],
            'thailand' => [
                'name' => 'Thailand',
                'full' => 'Thailand',
                'demo_label' => 'Request Demo in Thailand',
            ],
            'timor-leste' => [
                'name' => 'Timor-Leste',
                'full' => 'Timor-Leste',
                'demo_label' => 'Request Demo in Timor-Leste',
            ],
            'turkey' => [
                'name' => 'Turkey',
                'full' => 'Turkey',
                'demo_label' => 'Request Demo in Turkey',
            ],
            'turkmenistan' => [
                'name' => 'Turkmenistan',
                'full' => 'Turkmenistan',
                'demo_label' => 'Request Demo in Turkmenistan',
            ],
            'uzbekistan' => [
                'name' => 'Uzbekistan',
                'full' => 'Uzbekistan',
                'demo_label' => 'Request Demo in Uzbekistan',
            ],
            'vietnam' => [
                'name' => 'Vietnam',
                'full' => 'Vietnam',
                'demo_label' => 'Request Demo in Vietnam',
            ],
            'yemen' => [
                'name' => 'Yemen',
                'full' => 'Yemen',
                'demo_label' => 'Request Demo in Yemen',
            ],
            'australia' => [
                'name' => 'Australia',
                'full' => 'Australia',
                'demo_label' => 'Request Demo in Australia',
            ],
            'fiji' => [
                'name' => 'Fiji',
                'full' => 'Fiji',
                'demo_label' => 'Request Demo in Fiji',
            ],
            'kiribati' => [
                'name' => 'Kiribati',
                'full' => 'Kiribati',
                'demo_label' => 'Request Demo in Kiribati',
            ],
            'marshall-islands' => [
                'name' => 'Marshall Islands',
                'full' => 'Marshall Islands',
                'demo_label' => 'Request Demo in Marshall Islands',
            ],
            'micronesia' => [
                'name' => 'Micronesia',
                'full' => 'Micronesia',
                'demo_label' => 'Request Demo in Micronesia',
            ],
            'nauru' => [
                'name' => 'Nauru',
                'full' => 'Nauru',
                'demo_label' => 'Request Demo in Nauru',
            ],
            'new-zealand' => [
                'name' => 'New Zealand',
                'full' => 'New Zealand',
                'demo_label' => 'Request Demo in New Zealand',
            ],
            'palau' => [
                'name' => 'Palau',
                'full' => 'Palau',
                'demo_label' => 'Request Demo in Palau',
            ],
            'papua-new-guinea' => [
                'name' => 'Papua New Guinea',
                'full' => 'Papua New Guinea',
                'demo_label' => 'Request Demo in Papua New Guinea',
            ],
            'samoa' => [
                'name' => 'Samoa',
                'full' => 'Samoa',
                'demo_label' => 'Request Demo in Samoa',
            ],
            'solomon-islands' => [
                'name' => 'Solomon Islands',
                'full' => 'Solomon Islands',
                'demo_label' => 'Request Demo in Solomon Islands',
            ],
            'tonga' => [
                'name' => 'Tonga',
                'full' => 'Tonga',
                'demo_label' => 'Request Demo in Tonga',
            ],
            'tuvalu' => [
                'name' => 'Tuvalu',
                'full' => 'Tuvalu',
                'demo_label' => 'Request Demo in Tuvalu',
            ],
            'vanuatu' => [
                'name' => 'Vanuatu',
                'full' => 'Vanuatu',
                'demo_label' => 'Request Demo in Vanuatu',
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
            'united-kingdom' => 'uk',
            'great-britain' => 'uk',
            'britain' => 'uk',
            'india' => 'india',
        ];
        $country = $aliases[$country] ?? $country;

        $countries = self::getCountries();

        abort_unless(isset($countries[$country]), 404);

        $c = $countries[$country];

        // ✅ SEO (dynamic)
        $seo = [
            'title' => "Top Visitor Management Software (Vms) in {$c['name']} | Top Visitor Management System (Vms) {$c['name']}",
            'description' => "Visitor Management System & Software in {$c['name']} for offices, corporate parks, factories, manufacturing units, warehouses, cold storage, hospitals, schools, holy places, malls, events, residential societies and public entry gates—single or multi-location control with gate passes, approvals, alerts and real-time visitor logs.",
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
            'andhra-pradesh' => ['name' => 'Andhra Pradesh', 'full' => 'Andhra Pradesh'],
            'arunachal-pradesh' => ['name' => 'Arunachal Pradesh', 'full' => 'Arunachal Pradesh'],
            'assam' => ['name' => 'Assam', 'full' => 'Assam'],
            'bihar' => ['name' => 'Bihar', 'full' => 'Bihar'],
            'chhattisgarh' => ['name' => 'Chhattisgarh', 'full' => 'Chhattisgarh'],
            'goa' => ['name' => 'Goa', 'full' => 'Goa'],
            'gujarat' => ['name' => 'Gujarat', 'full' => 'Gujarat'],
            'haryana' => ['name' => 'Haryana', 'full' => 'Haryana'],
            'himachal-pradesh' => ['name' => 'Himachal Pradesh', 'full' => 'Himachal Pradesh'],
            'jharkhand' => ['name' => 'Jharkhand', 'full' => 'Jharkhand'],
            'karnataka' => ['name' => 'Karnataka', 'full' => 'Karnataka'],
            'kerala' => ['name' => 'Kerala', 'full' => 'Kerala'],
            'madhya-pradesh' => ['name' => 'Madhya Pradesh', 'full' => 'Madhya Pradesh'],
            'maharashtra' => ['name' => 'Maharashtra', 'full' => 'Maharashtra'],
            'manipur' => ['name' => 'Manipur', 'full' => 'Manipur'],
            'meghalaya' => ['name' => 'Meghalaya', 'full' => 'Meghalaya'],
            'mizoram' => ['name' => 'Mizoram', 'full' => 'Mizoram'],
            'nagaland' => ['name' => 'Nagaland', 'full' => 'Nagaland'],
            'odisha' => ['name' => 'Odisha', 'full' => 'Odisha'],
            'punjab' => ['name' => 'Punjab', 'full' => 'Punjab'],
            'rajasthan' => ['name' => 'Rajasthan', 'full' => 'Rajasthan'],
            'sikkim' => ['name' => 'Sikkim', 'full' => 'Sikkim'],
            'tamil-nadu' => ['name' => 'Tamil Nadu', 'full' => 'Tamil Nadu'],
            'telangana' => ['name' => 'Telangana', 'full' => 'Telangana'],
            'tripura' => ['name' => 'Tripura', 'full' => 'Tripura'],
            'uttar-pradesh' => ['name' => 'Uttar Pradesh', 'full' => 'Uttar Pradesh'],
            'uttarakhand' => ['name' => 'Uttarakhand', 'full' => 'Uttarakhand'],
            'west-bengal' => ['name' => 'West Bengal', 'full' => 'West Bengal'],
            // 8 Union Territories
            'andaman-and-nicobar-islands' => ['name' => 'Andaman and Nicobar Islands', 'full' => 'Andaman and Nicobar Islands'],
            'chandigarh' => ['name' => 'Chandigarh', 'full' => 'Chandigarh'],
            'dadra-and-nagar-haveli-and-daman-and-diu' => ['name' => 'Dadra and Nagar Haveli and Daman and Diu', 'full' => 'Dadra and Nagar Haveli and Daman and Diu'],
            'delhi' => ['name' => 'Delhi', 'full' => 'Delhi'],
            'jammu-and-kashmir' => ['name' => 'Jammu and Kashmir', 'full' => 'Jammu and Kashmir'],
            'ladakh' => ['name' => 'Ladakh', 'full' => 'Ladakh'],
            'lakshadweep' => ['name' => 'Lakshadweep', 'full' => 'Lakshadweep'],
            'puducherry' => ['name' => 'Puducherry', 'full' => 'Puducherry'],
        ];
    }

    public function state(string $state)
    {
        $state = strtolower(trim($state));

        // Optional aliases
        $aliases = [
            'up' => 'uttar-pradesh',
            'mp' => 'madhya-pradesh',
            'ap' => 'andhra-pradesh',
            'tn' => 'tamil-nadu',
            'wb' => 'west-bengal',
            'hp' => 'himachal-pradesh',
            'j&k' => 'jammu-and-kashmir',
            'jk' => 'jammu-and-kashmir',
        ];
        $state = $aliases[$state] ?? $state;

        $states = self::getStates();

        abort_unless(isset($states[$state]), 404);

        $c = $states[$state];

        // SEO (dynamic)
        $seo = [
            'title'       => "Top Visitor Management Software (Vms) in {$c['name']} | Top Visitor Management System (Vms) {$c['name']}",
            'description' => "Visitor Management Software in {$c['name']} for offices, corporate parks, factories, manufacturing units, warehouses, cold storage, hospitals, schools, holy places, malls, events, residential societies and public entry gates—single or multi-location control with gate passes, approvals, alerts and real-time visitor logs.",
            'keywords' => "visitor management system {$c['name']}, visitor management software {$c['name']}, single location visitor management {$c['name']}, multi location visitor management {$c['name']}, centralized visitor management platform {$c['name']}, visitor tracking system {$c['name']}, QR check-in system {$c['name']}, OTP visitor entry {$c['name']}, face recognition access control {$c['name']}, contractor management system {$c['name']}, paperless visitor register {$c['name']}",
            'og_image' => asset('images/visitor-management-system-main-img.png'),
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

    // ─── International Cities ─────────────────────────────────────────────────

    public static function getCities(): array
    {
        return [
            'new-york' => [
                'name' => 'New York',
                'full' => 'New York City, USA',
                'country' => 'USA',
            ],
            'los-angeles' => [
                'name' => 'Los Angeles',
                'full' => 'Los Angeles, USA',
                'country' => 'USA',
            ],
            'chicago' => [
                'name' => 'Chicago',
                'full' => 'Chicago, USA',
                'country' => 'USA',
            ],
            'houston' => [
                'name' => 'Houston',
                'full' => 'Houston, USA',
                'country' => 'USA',
            ],
        ];
    }

    public function city(string $city)
    {
        $city = strtolower(trim($city));

        $cities = self::getCities();

        abort_unless(isset($cities[$city]), 404);

        $c = $cities[$city];

        // SEO (dynamic)
        $seo = [
            'title' => "Best Visitor Management Software in {$c['name']} | Top Visitor Management System {$c['name']}",
            'description' => "Visitor Management Software in {$c['name']} for offices, corporate parks, factories, manufacturing units, warehouses, cold storage, hospitals, schools, holy places, malls, events, residential societies and public entry gates—single or multi-location control with gate passes, approvals, alerts and real-time visitor logs.",
            'keywords' => "visitor management software {$c['name']}, visitor management system {$c['name']}, single location visitor management {$c['name']}, multi location visitor management {$c['name']}, centralized visitor management platform {$c['name']}, visitor tracking system {$c['name']}, QR check-in system {$c['name']}, OTP visitor entry {$c['name']}, face recognition access control {$c['name']}, contractor management system {$c['name']}, paperless visitor register {$c['name']}",
            'og_image' => asset('images/visitor-management-system-main-img.png'),
        ];

        // FAQs (dynamic)
        $faqs = [
            [
                'q' => "How is the visitor management software useful in {$c['name']} for the visitor?",
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

        return view('pages.vms-city', compact('c', 'seo', 'faqs'));
    }
}
