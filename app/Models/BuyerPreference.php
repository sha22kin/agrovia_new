<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerPreference extends Model
{
    /********
     * Column:
     * id         =bigint(20):None,
     * user_id    =bigint(20):None unique,
     * interests  =varchar(300):null comma separated products,
     * quality    =varchar(30):null one of QUALITIES,
     * quantity   =varchar(30):null one of QUANTITIES,
     * budget_min =decimal(12,2):null,
     * budget_max =decimal(12,2):null,
     * delivery   =varchar(30):null one of DELIVERIES,
     * created_at =timestamp:null
     * updated_at =timestamp:null
     ****/

    public const QUALITIES = ['Premium Quality', 'Standard Quality', 'Economy'];

    public const QUANTITIES = ['Small (up to 100 kg)', 'Medium (100 - 1000 kg)', 'Bulk (1000+ kg)'];

    public const DELIVERIES = ['Within Bangladesh', 'Within my district', 'Pickup only'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    // "10,000 - 5,00,000" style label for the budget, null when none is set.
    public function budgetLabel($currency): ?string
    {
        $min = $this->budget_min !== null ? $currency.' '.number_format($this->budget_min) : null;
        $max = $this->budget_max !== null ? $currency.' '.number_format($this->budget_max) : null;

        if ($min && $max) {
            return $min.' - '.$max;
        }
        if ($min) {
            return 'From '.$min;
        }
        return $max ? 'Up to '.$max : null;
    }

    // Turns "Paddy, maize ,, Paddy" into "Paddy, maize".
    public static function cleanInterests($text): ?string
    {
        $items = collect(explode(',', (string) $text))->map(fn ($i) => trim($i))->filter()->unique(fn ($i) => mb_strtolower($i))
            ->map(fn ($i) => mb_substr($i, 0, 40))->take(20);

        return $items->isEmpty() ? null : $items->implode(', ');
    }
}
