<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    /********
     * Column:
     * id          =bigint(20):None,
     * name        =varchar(60):None item name, e.g. Paddy,
     * name_bn     =varchar(60):null Bangla name, e.g. ধান,
     * icon        =varchar(16):null emoji,
     * unit        =varchar(20):kg,
     * price       =decimal(10,2):None,
     * price_date  =date:None,
     * addedby_id  =bigint(20):null,
     * created_at  =timestamp:null
     * updated_at  =timestamp:null
     ****/

    // Days back the price board looks for an item's latest prices.
    public const BOARD_DAYS = 60;

    protected $fillable = ['name', 'name_bn', 'icon', 'unit', 'price', 'price_date', 'addedby_id'];

    protected $casts = ['price' => 'float', 'price_date' => 'date'];

    // Latest price of every item with its change since the item's previous price, newest first.
    public static function board(): array
    {
        $rows = static::where('price_date', '>=', now()->subDays(self::BOARD_DAYS)->toDateString())
            ->where('price_date', '<=', now()->toDateString())
            ->orderByDesc('price_date')
            ->get()
            ->groupBy('name');

        $board = [];
        foreach ($rows as $prices) {
            $latest = $prices[0];
            $previous = $prices[1] ?? null;
            $board[] = [
                'name' => $latest->name,
                'name_bn' => $latest->name_bn,
                'icon' => $latest->icon ?: '🌾',
                'unit' => $latest->unit,
                'price' => $latest->price,
                'change' => $previous && $previous->price > 0
                    ? round(($latest->price - $previous->price) / $previous->price * 100, 1)
                    : null,
                'date' => $latest->price_date->toDateString(),
            ];
        }

        usort($board, fn ($a, $b) => strcmp($b['date'], $a['date']) ?: strcmp($a['name'], $b['name']));

        return $board;
    }
}
