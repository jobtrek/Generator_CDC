<?php

namespace App\Notifications;

use App\Models\Cdc;
use Illuminate\Notifications\Notification;

class CdcDocxReadyNotification extends Notification
{
    public function __construct(public readonly Cdc $cdc) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'cdc_id'       => $this->cdc->id,
            'cdc_title'    => $this->cdc->title,
            'download_url' => route('cdc.download-file', $this->cdc),
        ];
    }
}
