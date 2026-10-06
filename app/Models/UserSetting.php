<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    /********
     * Column:
     * id                =bigint(20):None,
     * user_id           =bigint(20):None unique,
     * notify_orders     =tinyint(1):1,
     * notify_enquiries  =tinyint(1):1,
     * notify_price_drop =tinyint(1):0,
     * notify_sms        =tinyint(1):1,
     * notify_marketing  =tinyint(1):0,
     * profile_visible   =tinyint(1):1,
     * show_contact      =tinyint(1):0,
     * language          =varchar(5):en one of LANGUAGES,
     * currency          =varchar(5):BDT one of CURRENCIES,
     * created_at        =timestamp:null
     * updated_at        =timestamp:null
     ****/

    // key => [label, description, on by default]
    public const NOTIFICATIONS = [
        'notify_orders' => ['Order Updates', 'Get notified when an order status changes', true],
        'notify_enquiries' => ['Enquiry Responses', 'Alerts when sellers respond to your enquiries', true],
        'notify_price_drop' => ['Price Drop Alerts', 'Notify when wishlist items drop in price', false],
        'notify_sms' => ['SMS Notifications', 'Receive important alerts via SMS', true],
        'notify_marketing' => ['Marketing Emails', 'Offers, tips and product news from Agroviea', false],
    ];

    public const PRIVACY = [
        'profile_visible' => ['Profile Visibility', 'Allow sellers to view your buying profile', true],
        'show_contact' => ['Show Contact Number', 'Display your phone number on enquiries', false],
    ];

    // The switches of the seller dashboard say what they mean for a seller.
    public static function notifications($role = 'buyer'): array
    {
        // An expert has three switches.
        if ($role == 'expert') {
            return [
                'notify_orders' => ['New Consultation Bookings', 'Alert when a farmer books a session', true],
                'notify_enquiries' => ['New Q&A Questions', 'Alert for questions matching your specialization', true],
                'notify_sms' => ['SMS Alerts', 'Receive booking alerts via SMS', true],
            ];
        }
        if ($role != 'seller') {
            return self::NOTIFICATIONS;
        }

        return array_replace(self::NOTIFICATIONS, [
            'notify_orders' => ['New Orders', 'Get notified when a buyer orders from you', true],
            'notify_enquiries' => ['Buyer Requirements', 'Alerts when buyers post requirements you can quote on', true],
            'notify_price_drop' => ['Ad Status Updates', 'Get notified when one of your ads is approved or rejected', false],
        ]);
    }

    public static function privacy($role = 'buyer'): array
    {
        // An expert has no privacy switches.
        if ($role == 'expert') {
            return [];
        }
        if ($role != 'seller') {
            return self::PRIVACY;
        }

        return array_replace(self::PRIVACY, [
            'profile_visible' => ['Profile Visibility', 'Allow buyers to view your seller profile', true],
            'show_contact' => ['Show Contact Number', 'Display your phone number on your ads and quotes', false],
        ]);
    }

    public const LANGUAGES = ['en' => 'English', 'bn' => 'বাংলা (Bengali)'];

    public const CURRENCIES = ['BDT' => 'BDT (৳)', 'USD' => 'USD ($)'];

    protected $casts = [
        'notify_orders' => 'boolean', 'notify_enquiries' => 'boolean', 'notify_price_drop' => 'boolean',
        'notify_sms' => 'boolean', 'notify_marketing' => 'boolean', 'profile_visible' => 'boolean', 'show_contact' => 'boolean',
    ];

    // The switches that can be turned on and off.
    public static function toggleKeys(): array
    {
        return array_keys(self::NOTIFICATIONS + self::PRIVACY);
    }

    // The user's settings, or unsaved ones holding the defaults.
    public static function forUser($userId): self
    {
        $setting = static::where('user_id', $userId)->first();
        if ($setting) {
            return $setting;
        }

        $setting = new static();
        $setting->user_id = $userId;
        foreach (self::NOTIFICATIONS + self::PRIVACY as $key => [$label, $desc, $default]) {
            $setting->$key = $default;
        }
        $setting->language = 'en';
        $setting->currency = 'BDT';

        return $setting;
    }
}
