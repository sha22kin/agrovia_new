<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

// Tells a seller that the admin approved or rejected one of their ads (with the reason when rejected).
// Stored in the notifications table and shown by the bell of the seller dashboard.
class AdStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Post $ad, public string $status, public ?string $reason = null)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $approved = $this->status == 'approved';

        return [
            'ad_id' => $this->ad->id,
            'ad_name' => $this->ad->name,
            'status' => $this->status,
            'title' => $approved ? 'Your ad was approved' : 'Your ad was rejected',
            'message' => $approved
                ? '"'.$this->ad->name.'" is now live in the marketplace.'
                : '"'.$this->ad->name.'" was not approved. Edit it and submit again.',
            'reason' => $this->reason,
        ];
    }

    // Sends the notification only when the ad really moved to a new state and has a seller.
    public static function send(Post $ad, string $status, ?string $reason = null): void
    {
        if ($ad->user) {
            $ad->user->notify(new static($ad, $status, $reason));
        }
    }
}
