<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    // Get invoices with optional filters, pagination, and joins
    public function index(Request $request)
    {
        $query = Invoice::with('company', 'customer', 'invoiceItems.item')
            ->select('invoices.*')
            ->join('companies', 'invoices.company_id', '=', 'companies.id')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->leftJoin('items', 'invoice_items.item_id', '=', 'items.id');

        if ($request->has('invoiceid')) {
            $query->where('invoiceId', 'like', '%' . $request->invoiceid . '%');
        }
        if ($request->has('issueddate')) {
            $query->whereDate('issued_date', $request->issueddate);
        }
        if ($request->has('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
        }
        if ($request->has('totalitems')) {
            $query->havingRaw('COUNT(invoice_items.id) = ?', [$request->totalitems]);
        }
        if ($request->has('customer')) {
            $query->where('customers.name', 'like', '%' . $request->customer . '%');
        }
        if ($request->has('duedate')) {
            $query->whereDate('due_date', $request->duedate);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $limit = $request->input('limit', 15);
        $offset = $request->input('offset', 0);

        $invoices = $query->groupBy('invoices.id')
            ->offset($offset)
            ->limit($limit)
            ->get();

        // Format the result for each invoice
        $invoices = $invoices->map(function ($invoice) {
            $invoice->company_name = $invoice->company->name;
            $invoice->company_address = $invoice->company->address;
            $invoice->company_email = $invoice->company->email;
            $invoice->customer_name = $invoice->customer->name;
            $invoice->customer_address = $invoice->customer->address;
            $invoice->customer_email = $invoice->customer->email;
            $invoice->items = $invoice->invoiceItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_id' => $item->item->id,
                    'item_name' => $item->item->name,
                    'item_type' => $item->item->type,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'amount' => $item->amount,
                ];
            });
            $invoice->total_items = $invoice->invoiceItems->count();
            return $invoice;
        });

        return response()->json($invoices);
    }

    // Store a new invoice
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric',
            'items.*.amount' => 'required|numeric',
            'issued_date' => 'required|date',
            'due_date' => 'required|date',
            'sub_total' => 'required|numeric',
            'tax' => 'required|numeric',
            'grand_total' => 'required|numeric',
        ]);

        $invoiceId = $this->generateInvoiceId();

        // Default status logic
        $status = $request->grand_total > 0 ? 'unpaid' : 'draft';

        // Create the invoice
        $invoice = Invoice::create([
            'invoiceId' => $invoiceId,
            'company_id' => $request->company_id,
            'customer_id' => $request->customer_id,
            'subject' => $request->subject,
            'issued_date' => $request->issued_date,
            'due_date' => $request->due_date,
            'sub_total' => $request->sub_total,
            'tax' => $request->tax,
            'grand_total' => $request->grand_total,
            'status' => $status,
        ]);

        // Store invoice items
        foreach ($request->items as $itemData) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_id' => $itemData['item_id'],
                'quantity' => $itemData['quantity'],
                'unit_price' => $itemData['unit_price'],
                'amount' => $itemData['amount'],
            ]);
        }

        // Load the invoice with associated data
        $invoice->load('company', 'customer', 'invoiceItems.item');

        return response()->json($invoice, 201);
    }

    // Generate Invoice ID
    private function generateInvoiceId()
    {
        $lastInvoice = Invoice::whereMonth('created_at', Carbon::now()->month)
            ->orderBy('created_at', 'desc')
            ->first();

        $lastInvoiceId = $lastInvoice ? (int)substr($lastInvoice->invoiceId, -4) : 0;
        $newInvoiceId = str_pad($lastInvoiceId + 1, 4, '0', STR_PAD_LEFT);

        return $newInvoiceId;
    }

    // Show specific invoice
    public function show($id)
    {
        $invoice = Invoice::with('company', 'customer', 'invoiceItems.item')
            ->findOrFail($id);

        $invoice->company_name = $invoice->company->name;
        $invoice->company_address = $invoice->company->address;
        $invoice->company_email = $invoice->company->email;
        $invoice->customer_name = $invoice->customer->name;
        $invoice->customer_address = $invoice->customer->address;
        $invoice->customer_email = $invoice->customer->email;
        $invoice->items = $invoice->invoiceItems->map(function ($item) {
            return [
                'id' => $item->id,
                'item_id' => $item->item->id,
                'item_name' => $item->item->name,
                'item_type' => $item->item->type,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'amount' => $item->amount,
            ];
        });
        $invoice->total_items = $invoice->invoiceItems->count();

        return response()->json($invoice);
    }

    // Update invoice
    public function update(Request $request, $id)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric',
            'items.*.amount' => 'required|numeric',
            'issued_date' => 'required|date',
            'due_date' => 'required|date',
            'sub_total' => 'required|numeric',
            'tax' => 'required|numeric',
            'grand_total' => 'required|numeric',
        ]);

        $invoice = Invoice::findOrFail($id);

        // Update the invoice
        $invoice->update([
            'company_id' => $request->company_id,
            'customer_id' => $request->customer_id,
            'subject' => $request->subject,
            'issued_date' => $request->issued_date,
            'due_date' => $request->due_date,
            'sub_total' => $request->sub_total,
            'tax' => $request->tax,
            'grand_total' => $request->grand_total,
            'status' => $request->grand_total > 0 ? 'unpaid' : 'draft',
        ]);

        // Update invoice items
        foreach ($request->items as $itemData) {
            $invoiceItem = InvoiceItem::where('invoice_id', $invoice->id)
                ->where('item_id', $itemData['item_id'])
                ->first();

            if ($invoiceItem) {
                $invoiceItem->update([
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'amount' => $itemData['amount'],
                ]);
            } else {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'amount' => $itemData['amount'],
                ]);
            }
        }

        // Reload invoice with related data
        $invoice->load('company', 'customer', 'invoiceItems.item');

        return response()->json($invoice);
    }

    // Delete invoice
    public function destroy($id)
    {
        Invoice::destroy($id);
        return response()->json(null, 204);
    }
}
