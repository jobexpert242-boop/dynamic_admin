<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DefaultMail;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendInvoiceEmail(Request $request, Billing $invoice)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        Mail::to($request->to)
            ->send(new DefaultMail($invoice, $request->subject, $request->message));

        return response()->json(['message' => 'Email sent successfully.']);
    }
}
