<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $html;

    /**
     * Create a new message instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($html, $subject)
    {
        $this->html    = $html;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.emailTemplate')->with(['html' => $this->html])->subject($this->subject);
    }
}

?>
