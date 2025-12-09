<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 6px; font-size: 12px; }
        .no-border td { border: none; }
        .header-table td { vertical-align: top; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bg-gray { background-color: #f3f4f6; }
        .rounded { border-radius: 4px; }
        .status-paid { background-color: #22c55e; color: white; padding: 2px 4px; border-radius: 3px; font-size: 10px; }
        .status-due { background-color: #eab308; color: white; padding: 2px 4px; border-radius: 3px; font-size: 10px; }
        .status-unpaid { background-color: #ef4444; color: white; padding: 2px 4px; border-radius: 3px; font-size: 10px; }
        .status-cancelled { background-color: #6b7280; color: white; padding: 2px 4px; border-radius: 3px; font-size: 10px; }
        .terms-box { background-color: #f3f4f6; padding: 10px; border-radius: 4px; margin-top: 20px; }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 500px;
            text-align: center;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            font-weight: 800;
            color: #000000;
            opacity: 0.1;
            z-index: 0;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div class="watermark">{{ $invoice->status }}</div>
@php
    function statusColor($status) {
        return match ($status) {
            'paid' => 'status-paid',
            'due' => 'status-due',
            'unpaid' => 'status-unpaid',
            'cancelled' => 'status-cancelled',
            default => ''
        };
    }
@endphp

<!-- Container -->
<table class="no-border" width="100%">
    <tr>
        <td>
            <h2>Invoice # {{ $invoice->invoice }}</h2>
        </td>
        <td class="text-right">
            <img src="{{ public_path('storage/images/logo.jpg') }}" alt="logo" height="50">
            <div>
                {{ $site->company_location }}<br>
                Contact: <span style="color: rgb(0, 107, 0)">{{ $site->company_contact }}</span><br>
                Email: <span style="color: rgb(0, 107, 0)">{{ $site->company_email }}</span><br>
                Web: <span style="color: rgb(0, 107, 0)">{{ $site->company_web }}</span>
            </div>
        </td>
    </tr>
</table>

<!-- Invoice To & Summary -->
<table class="no-border" width="100%">
    <tr>
        <td width="50%">
            <strong>Invoice To:</strong><br>
            <strong>Name : </strong>{{ $invoice->customer->name }}<br>
            <strong>Address : </strong>{{ $invoice->address }}<br>
            <strong>Phone : </strong> <br>
            <strong>Email : </strong>{{ $invoice->customer->email }}
        </td>
        <td width="50%">
            <table style="border: 1px solid rgb(209, 209, 209);">
                <tr>
                    <td style="border: 1px solid rgb(209, 209, 209);">Invoice:</td>
                    <td class="text-right" style="border: 1px solid rgb(209, 209, 209);"># {{ $invoice->invoice }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid rgb(209, 209, 209);">Status:</td>
                    <td class="text-right" style="border: 1px solid rgb(209, 209, 209);"><span class="{{ statusColor($invoice->status) }}">{{ ucfirst($invoice->status) }}</span></td>
                </tr>
                <tr>
                    <td style="border: 1px solid rgb(209, 209, 209);">Invoice Date:</td>
                    <td class="text-right" style="border: 1px solid rgb(209, 209, 209);">{{ $invoice->invoice_date }}</td>
                </tr>
                <tr>
                    <td>Due Date:</td>
                    <td class="text-right" style="border: 1px solid rgb(209, 209, 209);">{{ $invoice->updated_at->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid rgb(209, 209, 209);">Amount Due:</td>
                    <td class="text-right" style="border: 1px solid rgb(209, 209, 209);">{{ $site->currency }} {{ number_format($invoice->sub_total - $invoice->paid, 2) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Invoice Items -->
<h3>Invoice Items</h3>
<table>
    <thead>
        <tr class="bg-gray">
            <th>#</th>
            <th>Item Name</th>
            <th class="text-center">Price</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
    @foreach($invoice->items as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->item_name }}</td>
            <td class="text-center">{{ $site->currency }} {{ number_format($item->price,2) }}</td>
            <td class="text-center">{{ $item->qty }}</td>
            <td class="text-right">{{ $site->currency }} {{ number_format($item->price * $item->qty,2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Totals -->
<table class="text-right" style="float:right; margin-top:10px; width:300px;">
    <tr>
        <td>Subtotal:</td>
        <td>{{ $site->currency }} {{ number_format($invoice->sub_total,2) }}</td>
    </tr>
    <tr>
        <td>Discount:</td>
        <td>{{ $site->currency }} {{ number_format($invoice->total_discount,2) }}</td>
    </tr>
    <tr>
        <td>Tax:</td>
        <td>{{ $site->currency }} {{ number_format($invoice->total_tax,2) }}</td>
    </tr>
    <tr>
        <td>Total:</td>
        <td>{{ $site->currency }} {{ number_format($invoice->total,2) }}</td>
    </tr>
    <tr>
        <td>Paid:</td>
        <td>{{ $site->currency }} {{ number_format($invoice->paid,2) }}</td>
    </tr>
    <tr>
        <td><strong>Amount Due:</strong></td>
        <td><strong>{{ $site->currency }} {{ number_format($invoice->sub_total - $invoice->paid,2) }}</strong></td>
    </tr>
</table>
<div style="clear:both;"></div>

<!-- Transactions -->
<h3>Related Transactions</h3>
<table>
    <thead>
        <tr class="bg-gray">
            <th>#</th>
            <th>Name</th>
            <th>Date</th>
            <th>Invoice</th>
            <th>Account</th>
            <th>Status</th>
            <th class="text-right">Amount</th>
        </tr>
    </thead>
    <tbody>
    @forelse($transactions as $k => $t)
        <tr>
            <td>{{ $k + 1 }}</td>
            <td>{{ $t->payee ?? 'Customer' }}</td>
            <td>{{ $t->created_at->format('Y-m-d') }}</td>
            <td>{{ $t->invoice_id }}</td>
            <td>{{ ucfirst($t->payment_method) }}</td>
            <td>{{ ucfirst($t->status) }}</td>
            <td class="text-right">{{ $site->currency }} {{ number_format($t->amount,2) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center" style="color:red;">No transactions found.</td>
        </tr>
    @endforelse
    </tbody>
</table>

<!-- Terms -->
<div class="terms-box">
    <h4>Invoice Terms & Conditions</h4>
    {!! $invoice->terms !!}
</div>

</body>
</html>
