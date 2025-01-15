<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\InvoiceRequest;

class InvoiceController extends Controller
{
    // Get invoices with optional filters, pagination, and joins
    public function index(Request $request)
    {
        try {
            $query = Invoice::with('company', 'customer', 'invoiceItems.item')
            ->select('invoices.*')
            ->join('companies', 'invoices.company_id', '=', 'companies.id')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->leftJoin('items', 'invoice_items.item_id', '=', 'items.id')
            ->groupBy('invoices.id');

            if ($request->has('invoiceid') && $request->invoiceid !== '' && $request->invoiceid !== null) {
                $query->where('invoiceId', 'like', '%' . $request->invoiceid . '%');
            }
            if ($request->has('issueddate') && $request->issueddate !== '' && $request->issueddate !== null) {
                $query->whereDate('issued_date', $request->issueddate);
            }
            if ($request->has('subject') && $request->subject !== '' && $request->subject !== null) {
                $query->where('subject', 'like', '%' . $request->subject . '%');
            }
            if ($request->has('totalitems') && $request->totalitems !== '' && $request->totalitems !== null && $request->totalitems !== 0) {
                $query->havingRaw('COUNT(invoice_items.id) = ?', [$request->totalitems]);
            }
            if ($request->has('customer') && $request->customer !== '' && $request->customer !== null) {
                $query->where('customers.name', 'like', '%' . $request->customer . '%');
            }
            if ($request->has('duedate') && $request->duedate !== '' && $request->duedate !== null) {
                $query->whereDate('due_date', $request->duedate);
            }
            if ($request->has('status') && $request->status !== '' && $request->status !== null) {
                $query->where('status', $request->status);
            }

            // Get the total records before pagination
            $invoices = $query->groupBy('invoices.id')->get();
            $totalRecords = $invoices->count();

            $limit = $request->input('limit', 10);
            $offset = $request->input('offset', 0);
            $invoices = $query->offset($offset)
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
                $invoice->total_items = $invoice->invoiceItems->count();
                unset($invoice->company, $invoice->customer); // to remove nested objects; company and customer
                return $invoice;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Items retrieved successfully',
                'data' => $invoices,
                'total_datas' => $totalRecords
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving invoices',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Store a new invoice
    public function store(InvoiceRequest $request)
    {
        try {
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

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice created successfully',
                'data' => $invoice
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        try {
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

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice retrieved successfully',
                'data' => $invoice
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Update invoice
    public function update(Request $request, $id)
    {
        try {
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
                'status' => $request->status ?? $invoice->status,
            ]);

            // Clear existing invoice items and add updated ones
            $invoice->invoiceItems()->delete();
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

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice updated successfully',
                'data' => $invoice
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete invoice
    public function destroy($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Invoice deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the invoice',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
