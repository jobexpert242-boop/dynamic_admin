<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DefaultMail;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendInvoiceEmail(Request $request, $id)
    {
        $request->validate([
            "to" => "required|email",
            "subject" => "required",
            "message" => "required",
        ]);

        $invoice = Billing::with('customer')->findOrFail($id);

        Mail::to($request->to)->send(
            new DefaultMail(
                $request->subject,
                $request->message,
                asset('storage/images/logo.jpg')
            )
        );

        return back()->with('status', 'Email sent successfully');
    }
}
