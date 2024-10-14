<?php

// App\Mail\AffiliateRequestRejected.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AffiliateRequestRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reason)
    {
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Yêu cầu trở thành người tiếp thị bị từ chối')
            ->view('emails.affiliate_request_rejected')
            ->with(['reason' => $this->reason]);
    }
}
