<?php

namespace App\Models\Cdc;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cdc extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): CdcMailMessage
    {
        $url = url('/invoice/'.$this->invoice->id);

        return (new CdcMailMessage)
            ->subject('Invoice Paid')
            ->markdown('mail.invoice.paid', ['url' => $url]);
    }

public function routingCdcNoptifications(object $notification): string
    {
        return $this->email;
    }

}
