<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['super_admin', 'manager', 'kitchen_staff'] as $role) {
            Role::findOrCreate($role, 'web');
        }

        // 1. Super Admin Accounts
        $admin1 = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Kitchen Administrator', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );
        $admin1->syncRoles(['super_admin']);

        $admin2 = User::updateOrCreate(
            ['email' => 'admin@africankitchen.test'],
            ['name' => 'Executive Chef & Admin', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );
        $admin2->syncRoles(['super_admin']);

        // 2. Manager Account
        $manager = User::updateOrCreate(
            ['email' => 'manager@africankitchen.test'],
            ['name' => 'Operations Manager', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );
        $manager->syncRoles(['manager']);

        // 3. Kitchen Staff Account
        $kitchen = User::updateOrCreate(
            ['email' => 'kitchen@africankitchen.test'],
            ['name' => 'Head Line Chef', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );
        $kitchen->syncRoles(['kitchen_staff']);

        // 4. Sample Customer Account
        $customer = User::updateOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Folake Adeleke', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        // 5. System Settings
        $settings = [
            'business_name' => 'His Grace Kitchen',
            'site_name' => 'His Grace Kitchen',
            'base_currency' => 'GBP',
            'display_currencies' => ['GBP', 'USD', 'EUR', 'CAD', 'NGN'],
            'whatsapp_number' => '+447988575682',
            'phone' => '07988575682 / 07508282876',
            'contact_email' => 'gracekitchenltd@gmail.com',
            'instagram_handle' => '@gracekitchenltd',
            'address' => 'Unit 15 Kencot Close, Business Park Kencot Way, DA18 4AB, London, UK',
            'tax_rate' => 0,
            'pay_on_delivery_enabled' => true,
            'diaspora_air_cargo_enabled' => true,
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
