<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendStatisticToStudent extends Mailable
{
    use Queueable, SerializesModels;

    protected $pathFileExcel;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pathFileExcel)
    {
        $this->pathFileExcel = $pathFileExcel;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.send_statistic')
                    ->attach($this->pathFileExcel);
    }
}
