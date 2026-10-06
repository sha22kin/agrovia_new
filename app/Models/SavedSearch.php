<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedSearch extends Model
{
    /********
     * Column:
     * id          =bigint(20):None,
     * user_id     =bigint(20):None,
     * name        =varchar(120):None,
     * keyword     =varchar(120):None,
     * category_id =bigint(20):null attributes.id (type 0),
     * district_id =bigint(20):null countries.id (type 3),
     * min_price   =decimal(12,2):null,
     * max_price   =decimal(12,2):null,
     * alerts      =tinyint(1):1,
     * checked_at  =timestamp:null ads posted after this are "new",
     * created_at  =timestamp:null
     * updated_at  =timestamp:null
     ****/

    // Most searches one user can keep.
    public const LIMIT = 20;

    protected $casts = ['alerts' => 'boolean', 'checked_at' => 'datetime'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Attribute::class, 'category_id');
    }

    public function district(){
        return $this->belongsTo(Country::class, 'district_id');
    }

    // "Paddy under ৳1,600" when the user gives no name.
    public static function defaultName($keyword, $min, $max, $currency): string
    {
        if ($max !== null) {
            return $keyword.' under '.$currency.number_format($max);
        }
        return $min !== null ? $keyword.' from '.$currency.number_format($min) : $keyword;
    }

    // "Category: Crops · Location: Bogura · Price: ৳1,000 - ৳1,600"
    public function criteria($currency): string
    {
        $parts = [];
        $parts[] = 'Category: '.($this->category?->name ?: 'All');
        if ($this->district) {
            $parts[] = 'Location: '.$this->district->name;
        }
        if ($this->min_price !== null && $this->max_price !== null) {
            $parts[] = 'Price: '.$currency.number_format($this->min_price).' - '.$currency.number_format($this->max_price);
        } elseif ($this->max_price !== null) {
            $parts[] = 'Price: up to '.$currency.number_format($this->max_price);
        } elseif ($this->min_price !== null) {
            $parts[] = 'Price: from '.$currency.number_format($this->min_price);
        }

        return implode(' · ', $parts);
    }

    // Query string of the public search page.
    public function searchParams(): array
    {
        return array_filter([
            'search' => $this->keyword,
            'category' => $this->category_id,
            'district' => $this->district_id,
            'min_price' => $this->min_price !== null ? (float) $this->min_price : null,
            'max_price' => $this->max_price !== null ? (float) $this->max_price : null,
        ], fn ($v) => $v !== null && $v !== '');
    }

    public function matches()
    {
        return Post::matching($this->keyword, $this->category_id, $this->district_id, $this->min_price, $this->max_price);
    }

    // Ads that match and were posted since the search was last opened.
    public function newCount(): int
    {
        return $this->matches()->where('created_at', '>', $this->checked_at ?: $this->created_at)->count();
    }
}
