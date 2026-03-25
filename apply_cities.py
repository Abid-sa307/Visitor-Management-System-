import os

controller_path = r'c:\laragon\www\Visitor-Management-System-\app\Http\Controllers\VmsLandingController.php'
config_path = r'c:\laragon\www\Visitor-Management-System-\config\vms-geo.php'
cities_txt_path = r'C:\Users\ABUBAKAR MALEK\.gemini\antigravity\brain\26b766a0-57b5-4ef6-8fcc-3bdec1559e75\generated_cities.txt'

with open(cities_txt_path, 'r') as f:
    cities_txt = f.read()

# 1. Update VmsLandingController.php
with open(controller_path, 'r', encoding='utf-8') as f:
    controller_content = f.read()

# Add countries to getCountries()
country_injection = """            'japan' => [
                'name' => 'Japan',
                'full' => 'Japan',
                'demo_label' => 'Request Demo in Japan',
            ],
            'taiwan' => [
                'name' => 'Taiwan',
                'full' => 'Taiwan',
                'demo_label' => 'Request Demo in Taiwan',
            ],
            'hong-kong' => [
                'name' => 'Hong Kong',
                'full' => 'Hong Kong',
                'demo_label' => 'Request Demo in Hong Kong',
            ],
"""
search_country = """            'vanuatu' => [
                'name' => 'Vanuatu',
                'full' => 'Vanuatu',
                'demo_label' => 'Request Demo in Vanuatu',
            ],
"""
controller_content = controller_content.replace(search_country, search_country + country_injection)

# Replace getCities() contents
# We find public static function getCities(): array { return [ ... ]; }
import re
cities_pattern = re.compile(r'(public static function getCities\(\): array\s*\{\s*return \[)(.*?)(\s*\];\s*\})', re.DOTALL)
controller_content = cities_pattern.sub(r'\1\n' + cities_txt + r'\3', controller_content)

with open(controller_path, 'w', encoding='utf-8') as f:
    f.write(controller_content)

print(f"Updated {controller_path}")

# 2. Update config/vms-geo.php
with open(config_path, 'r', encoding='utf-8') as f:
    config_content = f.read()

config_injection = """        'japan' => ['name' => 'Japan', 'local_compliance' => 'APPI (Act on the Protection of Personal Information) Compliant'],
        'taiwan' => ['name' => 'Taiwan', 'local_compliance' => 'PDPA (Personal Data Protection Act) Compliant'],
        'hong-kong' => ['name' => 'Hong Kong', 'local_compliance' => 'PDPO (Personal Data (Privacy) Ordinance) Compliant'],
"""
search_config = """        'venezuela' => ['name' => 'Venezuela', 'local_compliance' => 'Constitutional Data Privacy Standards Compliant'],
"""
config_content = config_content.replace(search_config, search_config + config_injection)

with open(config_path, 'w', encoding='utf-8') as f:
    f.write(config_content)

print(f"Updated {config_path}")
