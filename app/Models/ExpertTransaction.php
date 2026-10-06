<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertTransaction extends Model
{
    /********
     * Column:
     * id              =bigint(20):None,
     * expert_id       =bigint(20):None,
     * type            =varchar(15):None consultation, fee, withdrawal, adjustment,
     * amount          =decimal(12,2):None positive = income, negative = money out,
     * description     =varchar(191):null,
     * consultation_id =bigint(20):null the consultation the income / fee is for,
     * status          =varchar(10):completed completed, pending, paid, rejected, cancelled
     * created_at      =timestamp:null
     * updated_at      =timestamp:null
     ****/

    // Withdrawals with these statuses do not count in the balance (the money is back).
    public const NOT_COUNTED = ['rejected', 'cancelled'];

    public function expert(){
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function consultation(){
        return $this->belongsTo(Consultation::class);
    }

    public function isIncome(): bool
    {
        return $this->amount > 0;
    }

    public function isCounted(): bool
    {
        return !in_array($this->status, self::NOT_COUNTED);
    }

    // "+৳ 500" or "-৳ 20,000"
    public function signedAmount($currency): string
    {
        return ($this->amount >= 0 ? '+' : '-').$currency.' '.number_format(abs($this->amount));
    }

    // The money the expert can withdraw now.
    public static function balanceOf($expertId): float
    {
        return (float) static::where('expert_id', $expertId)->whereNotIn('status', self::NOT_COUNTED)->sum('amount');
    }

    // Withdrawals asked for and not paid yet.
    public static function pendingWithdrawalsOf($expertId): float
    {
        return abs((float) static::where('expert_id', $expertId)->where('type', 'withdrawal')->where('status', 'pending')->sum('amount'));
    }

    // Writes the income of a completed consultation (and the platform fee when there is a commission) once.
    public static function recordConsultation(Consultation $consultation): void
    {
        if ($consultation->status != 'completed' || $consultation->fee <= 0) {
            return;
        }

        $name = $consultation->farmer?->name ?: 'Farmer';
        $exists = fn ($type) => static::where('expert_id', $consultation->expert_id)->where('consultation_id', $consultation->id)->where('type', $type)->exists();

        // The rows carry the day the consultation was completed.
        if (!$exists('consultation')) {
            $row = new static();
            $row->expert_id = $consultation->expert_id;
            $row->type = 'consultation';
            $row->amount = $consultation->fee;
            $row->description = 'Consultation — '.$name;
            $row->consultation_id = $consultation->id;
            $row->created_at = $consultation->updated_at ?: now();
            $row->save();
        }

        $percent = (float) config('expert.commission_percent');
        if ($percent > 0 && !$exists('fee')) {
            $row = new static();
            $row->expert_id = $consultation->expert_id;
            $row->type = 'fee';
            $row->amount = -round($consultation->fee * $percent / 100, 2);
            $row->description = 'Platform service fee ('.rtrim(rtrim(number_format($percent, 2), '0'), '.').'%) — '.$name;
            $row->consultation_id = $consultation->id;
            $row->created_at = $consultation->updated_at ?: now();
            $row->save();
        }
    }

    // Completed consultations that have no income row yet (done before the earnings page existed) get one.
    public static function syncFor($expertId): void
    {
        $known = static::where('expert_id', $expertId)->where('type', 'consultation')->whereNotNull('consultation_id')->select('consultation_id');

        Consultation::where('expert_id', $expertId)->where('status', 'completed')->where('fee', '>', 0)
            ->whereNotIn('id', $known)->with('farmer:id,name')->get()
            ->each(fn ($consultation) => static::recordConsultation($consultation));
    }
}
