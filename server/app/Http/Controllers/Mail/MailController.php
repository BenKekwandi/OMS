<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Send a test email.
     *
     * @return string
     */
    public function sendTestEmail()
    {
        $subject = 'Test';

        try {
            Mail::to('nael.alyousefi@gmail.com')->send(new TestMail($subject));

            return 'Test email sent successfully!';
        } catch (\Exception $e) {
            return 'Error sending test email: '.$e->getMessage();
        }
    }
}
