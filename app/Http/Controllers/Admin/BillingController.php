<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DefaultMail;
use App\Models\Billing;
use App\Models\BillingItem;
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        if ($request->action === 'save_close') {
            return redirect()->route('admin.invoice.view', $billing->invoice)
                ->with('status', 'Invoice created successfully.');
        }

        return back()->with('status', 'Invoice created successfully.');
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

        if ($request->action === 'save_close') {
            return redirect()->route('admin.invoice.view', $billing->invoice)
                ->with('status', 'Invoice updated successfully.');
        }

        return back()->with('status', 'Invoice updated successfully.');
    }

    // delete invoice
    public function deleteInvoice($invoice)
    {
        $billing = Billing::where('invoice', $invoice)->firstOrFail();
        $billing->items()->delete();
        Transaction::where('invoice_id', $billing->invoice)->delete();
        $billing->delete();
        return back()->with('status', 'Billing deleted successfully.');
    }

    // View Invoice 
    public function viewInvoice($invoice)
    {
        $billing = Billing::with(['items', 'customer'])
            ->where('invoice', $invoice)
            ->firstOrFail();
        $transactions = Transaction::where('invoice_id', $billing->invoice)
            ->orderBy('id', 'desc')
            ->get();

        // Generate public view URL
        $publicUrl = url('/invoice/' . $billing->invoice . '/' . $billing->token);
        $publicPrint = url('/invoice/print/' . $billing->invoice . '/' . $billing->token);

        return Inertia::render('Admin/Billing/ViewInvoice', [
            'invoice'    => $billing,
            'public_url' => $publicUrl,
            'transactions' => $transactions,
            'publicPrint' => $publicPrint
        ]);
    }

    // public invoice 
    public function publicInvoice($invoice, $token)
    {
        $billing = Billing::where('invoice', $invoice)
            ->where('token', $token)
            ->with('items', 'customer')
            ->firstOrFail();
        $transactions = Transaction::where('invoice_id', $billing->invoice)
            ->orderBy('id', 'desc')
            ->get();

        $publicUrl = url('/invoice/print/' . $billing->invoice . '/' . $billing->token);

        return Inertia::render("Admin/Billing/PublicView", [
            'invoice' => $billing,
            'transactions' => $transactions,
            'publicUrl' => $publicUrl,
        ]);
    }

    // public print 
    public function publicInvoicePrint($invoice, $token)
    {
        $billing = Billing::where('invoice', $invoice)
            ->where('token', $token)
            ->with('items', 'customer')
            ->firstOrFail();
        $transactions = Transaction::where('invoice_id', $billing->invoice)
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Admin/Billing/Print', [
            'invoice' => $billing,
            'transactions' => $transactions
        ]);
    }

    // download pdf 
    public function downloadInvoicePdf($invoice)
    {
        $billing = Billing::with(['items', 'customer'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        $transactions = Transaction::where('invoice_id', $billing->invoice)
            ->orderBy('id', 'desc')
            ->get();

        $site = SiteSetting::first();

        $pdf = Pdf::loadView('pdf.billing', [
            'invoice'      => $billing,
            'transactions' => $transactions,
            'site'         => $site,
        ])->setPaper('A4')->set_option('isRemoteEnabled', true);

        return $pdf->stream('Invoice_' . $billing->invoice . '.pdf');
    }

    // download pdf 
    public function downloadInvoicePdf2($invoice)
    {
        $billing = Billing::with(['items', 'customer'])
            ->where('invoice', $invoice)
            ->firstOrFail();

        $transactions = Transaction::where('invoice_id', $billing->invoice)
            ->orderBy('id', 'desc')
            ->get();

        $site = SiteSetting::first();

        $pdf = Pdf::loadView('pdf.billing', [
            'invoice'      => $billing,
            'transactions' => $transactions,
            'site'         => $site,
        ])->setPaper('A4')->set_option('isRemoteEnabled', true);
        return $pdf->download('Invoice_' . $billing->invoice . '.pdf');
    }


    // invoice update status 
    public function invoiceUpdateStatus(Request $request, $invoice)
    {
        $billing = Billing::where('invoice', $invoice)->firstOrFail();
        $billing->status = $request->status;
        $billing->save();

        return redirect()->back()->with('status', 'Status updated successfully!');
    }

    // store transtiction 
    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|string|exists:billings,invoice',
            'type' => 'required|in:debit,credit',
            'category_id' => 'required|integer',
            'payer' => 'required|string',
            'payee' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string',
            'status' => 'required|in:success,failed',
            'payment_method' => 'required|string',
            'payment_info' => 'required|string',
        ]);

        $billing = Billing::where('invoice', $validated['invoice_id'])->firstOrFail();

        Transaction::create([
            'invoice_id'     => $billing->invoice,
            'type'           => $validated['type'],
            'category_id'    => $validated['category_id'],
            'payer'          => $validated['payer'],
            'payee'          => $validated['payee'],
            'payer_id'       => auth()->id(),
            'payee_id'       => $billing->customer_id,
            'amount'         => $validated['amount'],
            'note'           => $validated['note'],
            'status'         => $validated['status'],
            'payment_method' => $validated['payment_method'],
            'payment_info'   => $validated['payment_info'],
        ]);

        // Update paid amount ONLY when success
        if ($validated['status'] === 'success') {
            $billing->paid = ($billing->paid ?? 0) + $validated['amount'];
            $billing->save();
        }

        return back()->with('status', 'Payment added successfully.');
    }

    // public function sendTestEmail()
    // {
    //     $body = '<h2>Hello John Doe</h2><p>Your invoice has been generated successfully.</p>
    //          <a href="#" class="btn">View Invoice</a>';

    //     Mail::to('jobexpert242@gmail.com')->send(new DefaultMail('Invoice Generated', $body));
    //     return 'Email Sent!';
    // }

    // billoing item
    public function billingItem(Request $request)
    {
        $search = $request->input('search');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if (!$search && !$dateFrom && !$dateTo) {
            return Inertia::render('Admin/Billing/BillingItem', [
                'billingItems' => [
                    'data' => [],
                    'total' => 0,
                    'links' => [],
                ],
                'search' => "",
                'date_from' => "",
                'date_to' => "",
            ]);
        }

        $query = BillingItem::with('billing');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('item_code', 'like', "%$search%")
                    ->orWhere('item_name', 'like', "%$search%")
                    ->orWhere('imei_sl', 'like', "%$search%")
                    ->orWhereHas('billing', function ($bill) use ($search) {
                        $bill->where('invoice', 'like', "%$search%");
                    });
            });
        }

        if ($dateFrom) {
            $query->whereDate('warranty_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('warranty_date', '<=', $dateTo);
        }

        $billingItems = $query->paginate(10)->withQueryString();

        return Inertia::render('Admin/Billing/BillingItem', [
            'billingItems' => $billingItems,
            'search' => $search,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);
    }
}
