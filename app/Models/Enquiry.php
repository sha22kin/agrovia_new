<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    /********
     * Column:
     * id          =bigint(20):None,
     * user_id     =bigint(20):null the buyer,
     * category_id =bigint(20):null,
     * title       =varchar(191):None,
     * description =text:null,
     * quantity    =decimal(12,2):null,
     * unit        =varchar(20):null,
     * status      =varchar(10):open open, closed
     * approval    =varchar(10):pending pending, approved, rejected (admin review)
     * reject_reason =text:null why the admin rejected it
     * expires_at  =timestamp:null an open enquiry stops taking quotes after this
     * created_at  =timestamp:null
     * updated_at  =timestamp:null
     ****/

    // Filters of the buyer's enquiry list. Open = approved, open and no quote yet, quoted = open with quotes.
    public const FILTERS = ['pending' => 'Pending Approval', 'open' => 'Open', 'quoted' => 'Quotes Received', 'closed' => 'Closed', 'rejected' => 'Rejected'];

    // Admin review: sellers only see approved enquiries.
    public const APPROVALS = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'];

    // Days an enquiry stays open before it expires (the buyer can renew it).
    public const EXPIRY_DAYS = 30;

    protected $casts = ['expires_at' => 'datetime'];

    public const UNITS = ['kg', 'ton', 'maund', 'bag', 'piece', 'dozen', 'litre', 'unit'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->belongsTo(Attribute::class, 'category_id');
    }

    // Category buyers asked for most in the last 7 days, with its change against the 7 days before.
    public static function topDemand(): ?array
    {
        $weekAgo = now()->subDays(7);
        $top = static::approved()->whereNotNull('category_id')->where('created_at', '>=', $weekAgo)
            ->selectRaw('category_id, count(*) as total')
            ->groupBy('category_id')->orderByDesc('total')
            ->with('category:id,name')
            ->first();

        if (!$top || !$top->category) {
            return null;
        }

        $previous = static::approved()->where('category_id', $top->category_id)
            ->whereBetween('created_at', [now()->subDays(14), $weekAgo])
            ->count();

        return [
            'name' => $top->category->name,
            'count' => (int) $top->total,
            'change' => $previous ? round(($top->total - $previous) / $previous * 100) : null,
        ];
    }

    public function quotes(){
        return $this->hasMany(EnquiryQuote::class);
    }

    public function scopeApproved($query){
        return $query->where('approval', 'approved');
    }

    // What sellers see (RFQ page, Buyer Requirements): approved by the admin and still taking quotes.
    public function scopePublished($query){
        return $query->approved()->active();
    }

    // Still taking quotes: open and not expired.
    public function scopeActive($query){
        return $query->where('status', 'open')->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    // An expired enquiry counts as closed in the filters.
    public function scopeFilter($query, $filter){
        return match ($filter) {
            'pending' => $query->where('approval', 'pending'),
            'rejected' => $query->where('approval', 'rejected'),
            'open' => $query->published()->whereDoesntHave('quotes'),
            'quoted' => $query->published()->whereHas('quotes'),
            'closed' => $query->where(fn ($q) => $q->where('status', 'closed')->orWhere('expires_at', '<=', now())),
            default => $query,
        };
    }

    public function isPending(): bool
    {
        return $this->approval == 'pending';
    }

    public function isRejected(): bool
    {
        return $this->approval == 'rejected';
    }

    // Back to the admin's review queue (new enquiry, or an edit of one).
    public function sendForReview(): void
    {
        $this->approval = 'pending';
        $this->reject_reason = null;
    }

    public function isClosed(): bool
    {
        return $this->status == 'closed';
    }

    public function isExpired(): bool
    {
        return !$this->isClosed() && $this->expires_at && $this->expires_at->isPast();
    }

    // [label, badge css class] of the My Ads list.
    public function adStatus(): array
    {
        if ($this->isPending()) {
            return ['Pending Approval', 'badge-pending'];
        }
        if ($this->isRejected()) {
            return ['Rejected', 'badge-cancelled'];
        }
        if ($this->isClosed()) {
            return ['Closed', 'badge-unknown'];
        }
        return $this->isExpired() ? ['Expired', 'badge-expired'] : ['Active', 'badge-active'];
    }

    // "8000 kg", null when no quantity was given.
    public function quantityLabel(): ?string
    {
        if ($this->quantity === null) {
            return null;
        }
        $number = rtrim(rtrim(number_format($this->quantity, 2, '.', ''), '0'), '.');
        return trim($number.' '.$this->unit);
    }

    // [text, css class] shown on the right of an enquiry row.
    public function statusInfo(): array
    {
        if ($this->isPending()) {
            return ['Pending Approval', 'text-warning'];
        }
        if ($this->isRejected()) {
            return ['Rejected', 'text-danger'];
        }
        if ($this->isClosed()) {
            return ['Closed', 'text-muted'];
        }
        if ($this->isExpired()) {
            return ['Expired', 'text-muted'];
        }
        $quotes = $this->quotes_count ?? $this->quotes()->count();
        if ($quotes) {
            return [$quotes.' '.($quotes > 1 ? 'Quotes' : 'Quote').' Received', 'text-success'];
        }
        return ['Awaiting Quotes', 'text-warning'];
    }
}
