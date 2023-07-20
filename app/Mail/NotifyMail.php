<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\NotifyLogs;


class NotifyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $saveData = [];
        $saveData['request_data'] = json_encode($this->mailData);
        $saveData['send_to'] = !empty($this->mailData['email']) ? $this->mailData['email'] : '';
        $saveData['action'] = !empty($this->mailData['action']) ? $this->mailData['action'] : '';
        $saveData['template_code'] = !empty($this->mailData['template_code']) ? $this->mailData['template_code'] : '';
        $saveData['type'] = 'email';
        $saveData['created_at'] = date('Y-m-d H:i:s');
        $saveData['updated_at'] = date('Y-m-d H:i:s');
        //        pr($saveData);die;
        $notifyLogs = NotifyLogs::create($saveData);
        // dd($this->mailData);
        $return = $this->subject($this->mailData['subject'])->view(['html' => 'email.default']);

        if ($return) {
            $updateData = [];
            $updateData['status'] = 'Success';
            $notifyLogs->update($updateData);
        } else {
            $updateData = [];
            $updateData['status'] = 'Failed';
            $notifyLogs->update($updateData);
        }

        //        return true;
    }
}
