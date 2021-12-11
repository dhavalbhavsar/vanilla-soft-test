<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Email;


class EmailAsync extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emailableObject = $this->subject($this->email->subject)->markdown('emails.async');

        $attachements = $this->email->emailAttachments()->get();
        
        if(!empty($attachements)){
            foreach($attachements as $attache){
                $emailableObject->attach(storage_path($attache->image_path.'/'.$attache->name),[
                    'as' => $attache->original_name
                ]);
            }
        }

        return $emailableObject;
    }
}
