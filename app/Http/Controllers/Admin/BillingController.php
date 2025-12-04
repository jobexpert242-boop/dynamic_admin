<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BillingController extends Controller
{
    public function billing(){
        return Inertia::render('Admin/Billing/Billing');
    }

    public function createInvoice(){
        return Inertia::render('Admin/Billing/CreateInvoice');
    }

    public function storeInvoice(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer',
            'invoice_date' => 'required|date',
            'total' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $billing = Billing::create($validated);

        foreach ($validated['items'] as $item) {
            $billing->items()->create($item);
        }

        return back()->with('status', 'Billing created successfully!');
    }

}
