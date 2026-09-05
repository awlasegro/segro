<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class EmailController extends Controller
{
    public function SendEmail()
{
    // Email recipient
    $toMail = "anonymousnoobslayer@gmail.com";

    // User name for personalization
    $userName = "NoobSlayer";

    // Send the welcome email using the WelcomeEmail Mailable
    Mail::to($toMail)->send(new WelcomeEmail($userName));

    // Return response
    return response()->json(['message' => 'Email sent successfully!']);
}

}
