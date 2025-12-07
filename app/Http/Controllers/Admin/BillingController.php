<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\User;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BillingController extends Controller
{
    // Show list
    public function billing(Request $request)
    {
        $search = $request->input('search');

        $query = Billing::with('customer', 'items')->latest();

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhere('invoice', 'like', "%$search%")
                ->orWhereHas('items', function ($q) use ($search) {
                    $q->where('item_name', 'like', "%$search%");
                });
        }

        $invoices = $query->paginate(10)->withQueryString();

        return Inertia::render('Admin/Billing/Billing', [
            'invoices' => $invoices,
            'search' => $search,
        ]);
    }

    // Create form
    public function createInvoice()
    {
        return Inertia::render("Admin/Billing/CreateInvoice", [
            'users' => User::select("id", "name")->get()
        ]);
    }

    // Generate invoice number
    private function generateInvoiceNumber()
    {
        $last = Billing::orderBy("id", "DESC")->first();

        if ($last && $last->invoice) {
            $num = preg_replace("/\D/", "", $last->invoice);
            $next = (int)$num + 1;
        } else {
            $next = 1;
        }

        return str_pad($next, 10, "0", STR_PAD_LEFT);
    }
    // store invoice 
    public function storeInvoice(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'invoice_date' => 'required|date',
            'invoice' => 'nullable|string|unique:billings,invoice',
            'status' => 'required|in:due,unpaid,paid,cancelled',
            'paid' => 'nullable|string',
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable|string',
            'sub_total' => 'required',
            'total_tax' => 'required',
            'total_discount' => 'required',
            'round_value' => 'required',
            'total' => 'required',
            'terms' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.item_code' => 'nullable|string',
            'items.*.item_name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|integer|min:0',
            'items.*.tax' => 'required|integer|min:0',
            'items.*.warranty_date' => 'nullable|date',
            'items.*.imei_sl' => 'nullable|string',
            'items.*.note' => 'nullable|string',
        ]);

        $prefix = SiteSetting::first()->inv_prefix ?? '';
        if (empty($validated['invoice'])) {
            $validated['invoice'] = $prefix . $this->generateInvoiceNumber();
        }

        $billing = Billing::create([
            'customer_id' => $validated['customer_id'],
            'invoice' => $validated['invoice'],
            'invoice_date' => $validated['invoice_date'],
            'status' => $validated['status'],
            'paid' => $validated['paid'],
            'discount' => $validated['discount'] ?? 0,
            'discount_type' => $validated['discount_type'] ?? null,
            'sub_total' => $validated['sub_total'],
            'total_tax' => $validated['total_tax'],
            'total_discount' => $validated['total_discount'],
            'round_value' => $validated['round_value'],
            'total' => $validated['total'],
            'terms' => $validated['terms'],
            'token' => 'token_' . Str::random(20),
        ]);

        foreach ($validated['items'] as $item) {
            BillingItem::create([
                'invoice_id' => $billing->id,
                'item_code' => $item['item_code'],
                'item_name' => $item['item_name'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'tax' => $item['tax'],
                'warranty_date' => $item['warranty_date'],
                'imei_sl' => $item['imei_sl'],
                'note' => $item['note'],
            ]);
        }

        return back()->with('status', 'Billing created successfully.');
    }

    // Edit Invoice
    public function editInvoice($invoice)
    {
        $invoice = Billing::with('items')->where('invoice', $invoice)->firstOrFail();

        return Inertia::render('Admin/Billing/EditInvoice', [
            'invoice' => $invoice,
            'users'   => User::select('id', 'name')->get()
        ]);
    }

    // Update Invoice
    public function updateInvoice(Request $request, $invoice)
    {
        $billing = Billing::where('invoice', $invoice)->firstOrFail();

        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'invoice_date' => 'required|date',
            // 'invoice' => 'required|string|unique:billings,invoice,' . $billing->id,
            'status' => 'required|in:due,unpaid,paid,cancelled',
            'paid' => 'nullable|string',
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable|string',
            'sub_total' => 'required',
            'total_tax' => 'required',
            'total_discount' => 'required',
            'round_value' => 'required',
            'total' => 'required',
            'terms' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.item_code' => 'nullable|string',
            'items.*.item_name' => 'required|string',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|integer|min:0',
            'items.*.tax' => 'required|integer|min:0',
            'items.*.warranty_date' => 'nullable|date',
            'items.*.imei_sl' => 'nullable|string',
            'items.*.note' => 'nullable|string',
        ]);

        $billing->update([
            'customer_id' => $validated['customer_id'],
            // 'invoice' => $validated['invoice'],
            'invoice_date' => $validated['invoice_date'],
            'status' => $validated['status'],
            'paid' => $validated['paid'],
            'discount' => $validated['discount'] ?? 0,
            'discount_type' => $validated['discount_type'] ?? null,
            'sub_total' => $validated['sub_total'],
            'total_tax' => $validated['total_tax'],
            'total_discount' => $validated['total_discount'],
            'round_value' => $validated['round_value'],
            'total' => $validated['total'],
            'terms' => $validated['terms'],
        ]);

        // Delete old items
        $billing->items()->delete();

        // Insert new items
        foreach ($validated['items'] as $item) {
            BillingItem::create([
                'invoice_id' => $billing->id,
                'item_code' => $item['item_code'],
                'item_name' => $item['item_name'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'tax' => $item['tax'],
                'warranty_date' => $item['warranty_date'],
                'imei_sl' => $item['imei_sl'],
                'note' => $item['note'],
            ]);
        }

        return redirect()->route('admin.billing')->with('status', 'Invoice updated successfully.');
    }

    // delete invoice
    public function deleteInvoice(Billing $billing)
    {
        $billing->items()->delete();
        $billing->delete();

        return back()->with('status', 'Billing deleted successfully.');
    }

    // View Invoice (Admin Protected)
    public function viewInvoice($invoice)
    {
        $billing = Billing::with(['items', 'customer'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        // Generate public view URL
        $publicUrl = url('/invoice/' . $billing->invoice . '/' . $billing->token);

        return Inertia::render('Admin/Billing/ViewInvoice', [
            'invoice'    => $billing,
            'public_url' => $publicUrl,
        ]);
    }

    // public invoice 
    public function publicInvoice($invoice, $token)
    {
        $billing = Billing::where('invoice', $invoice)
            ->where('token', $token)
            ->with('items', 'customer')
            ->firstOrFail();

        return Inertia::render("Admin/Billing/PublicView", [
            'invoice' => $billing
        ]);
    }
}
