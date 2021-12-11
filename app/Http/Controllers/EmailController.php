<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Email;
use App\Http\Requests\EmailRequest;

use App\Jobs\SendEmail;
use App\Models\EmailAttachment;

class EmailController extends Controller
{
    public function send(EmailRequest $request)
    {
    	foreach ($request->emails as $emailObject) {

    		$email = Email::create([
    			'email' => $emailObject['email'],
    			'subject' => $emailObject['subject'],
    			'body' => $emailObject['body']
    		]);

    		//Save Attachment in storage

    		if(!empty($emailObject['attachment'])){

    			if(array_key_exists('file_name',$emailObject['attachment'])){

    				// This condition for single attachment

    				$storeFile = storeBase64File($emailObject['attachment']);

    				$emailAttachment = new EmailAttachment;
	    			$emailAttachment->name = $storeFile['file_name'];
	    			$emailAttachment->original_name = $emailObject['attachment']['file_name'];
	    			$emailAttachment->image_path = $storeFile['file_path'];
	    			
	    			$email->emailAttachments()->save($emailAttachment);

    			} else {

    				// This condition for multiple attachment

    				foreach ($emailObject['attachment'] as $attachment) {

    					$storeFile = storeBase64File($attachment);

	    				$emailAttachment = new EmailAttachment;
		    			$emailAttachment->name = $storeFile['file_name'];
		    			$emailAttachment->original_name = $attachment['file_name'];
		    			$emailAttachment->image_path = $storeFile['file_path'];

		    			$email->emailAttachments()->save($emailAttachment);
    				}
    			}
    		}

    		SendEmail::dispatch($email);
    	}

        return response()->json([
            'success'   => true,
            'message'   => 'Email send successfully.'
        ]);
    }

    public function list()
    {
        $emails = Email::with('emailAttachments')->get(['id','email','subject','body']);

        return response()->json([
            'success'   => true,
            'message'   => 'Email list successfully.',
            'data'      => $emails
        ]);
    }

    public function download($attachmentId)
    {
        $emailAttachment = EmailAttachment::findOrFail($attachmentId);

        $path = storage_path('app/'.$emailAttachment->name);

        if (file_exists($path)) {
            return \Response::download($path, $emailAttachment->original_name);
        }

        return response()->json([
            'success'   => fail,
            'message'   => 'File not not found.',
        ]);
        
    }

}
