<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifySummary extends Mailable
{
    use Queueable, SerializesModels;

    protected $attributes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.dashboard.summary-notification')
                    ->from(['address' => 'noreply@ne.clinicalgenome.org', 'name' => 'ClinGen Notification'])
                    ->subject('Gene Notifications Summary from ClinGen for Period Ending ' . $this->attributes['date'])
                    ->with($this->attributes);

    }
}
