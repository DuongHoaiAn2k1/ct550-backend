<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PointGiving extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $rank;
    public $point;
    public $user;
    public $month;


    public function __construct($rank, $point, $user, $month)
    {
        $this->rank = $rank;
        $this->point = $point;
        $this->user = $user;
        $this->month = $month;
    }

    public function build()
    {
        return $this->subject('Chúc mừng khách hàng ' . $this->user . ' đã nhận được điểm tích lũy')
            ->view('emails.point_giving')
            ->with(['rank' => $this->rank, 'point' => $this->point, 'user' => $this->user, 'month' => $this->month]);
    }
}
