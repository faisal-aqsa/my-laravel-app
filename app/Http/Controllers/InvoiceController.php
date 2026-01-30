<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Setting;
use App\Mail\InvoiceMail;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index(Request $request) {
        $data = [
            'pageTitle' => 'All Invoice',
            'invoices' => Invoice::all()
        ];

        return view('back.pages.invoice', $data);
    }

    public function create(Request $request) {
        $lastInvoice = Invoice::latest('id')->first();
        $nextInvoiceNumber = $lastInvoice ? ($lastInvoice->invoice_number + 1) : 1001;
        $settings = Setting::first();
    
        // If no settings exist, create default
        if (!$settings) {
            $settings = new Setting();
            $settings->sgst = 2.5;
            $settings->cgst = 2.5;
            $settings->igst = 5;
        }
        $data = [
            'pageTitle' => 'Create Invoice',
            'clients' => Client::all(),
            'invoiceNumber' => $nextInvoiceNumber,
            'settings' => $settings
        ];

        return view('back.pages.create-invoice', $data);
    }

    public function storeInvoice(Request $request)
    {
        // Get tax rates from settings
        $settings = Setting::first();
        $sgstRate = $settings->sgst ?? 0;
        $cgstRate = $settings->cgst ?? 0;
        $igstRate = $settings->igst ?? 0;
        
        // Validation outside try-catch
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'invoice_number' => 'required|unique:invoices',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'particular' => 'required|array',
            'particular.*' => 'required|string',
            'hsn_no' => 'nullable|array',
            'hsn_no.*' => 'nullable|string|max:10',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
            'total_price' => 'required|array',
            'total_price.*' => 'required|numeric|min:0',
            // New fields
            'grand_total' => 'required|numeric|min:0',
            'po_number' => 'nullable|string|max:100',
            'vehicle_no' => 'nullable|string|max:50',
            'e_way_bill_no' => 'nullable|string|max:50',
            'consignee_address' => 'nullable|string',
            'is_sgst' => 'boolean',
            'is_cgst' => 'boolean',
            'is_gst' => 'boolean',
            'is_igst' => 'boolean',
        ], [
            'invoice_number.unique' => 'This invoice number already exists',
            'particular.*.required' => 'Each item must have a description',
            'quantity.*.required' => 'Each item must have a quantity',
            'unit_price.*.required' => 'Each item must have a unit price',
            'grand_total.required' => 'Grand total is required',
        ]);
        
        try {
            // Calculate subtotal
            $subtotal = array_sum($request->total_price);
            
            $invoice = Invoice::create([
                'client_id' => $request->client_id,
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'status' => 'pending',
                'total_amount' => $subtotal,
                'grand_total' => $request->grand_total,
                'paid_amount' => 0,
                'is_sgst' => $request->boolean('is_sgst'),
                'is_cgst' => $request->boolean('is_cgst'),
                'is_gst' => $request->boolean('is_gst'),
                'is_igst' => $request->boolean('is_igst'),
                'consignee_address' => $request->consignee_address,
                'e_way_bill_no' => $request->e_way_bill_no,
                'vehicle_no' => $request->vehicle_no,
                'po_number' => $request->po_number,
            ]);
        
            foreach ($request->particular as $key => $particular) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'particular' => $particular,
                    'hsn_no' => $request->hsn_no[$key] ?? null,
                    'quantity' => $request->quantity[$key],
                    'unit_price' => $request->unit_price[$key],
                    'total_price' => $request->total_price[$key],
                ]);
            }

            return redirect()->route('admin.all-invoices')->with('success', 'Invoice Created Successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create invoice: ' . $e->getMessage())
                ->withInput();
        }
    }

    // public function downloadPDF($id)
    // {
    //     $invoice = Invoice::with(['invoiceItems', 'getClient'])->findOrFail($id);

    //     $pdf = Pdf::loadView('back.pdf.invoice-pdf', compact('invoice'));

    //     return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    // }

    public function downloadPDF($id)
    {
        $invoice = Invoice::with(['invoiceItems', 'getClient'])->findOrFail($id);
        
        $pdf = SnappyPdf::loadView('back.pdf.invoice-pdf', compact('invoice'))
            ->setOption('enable-local-file-access', true)
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('page-size', 'A4')
            ->setOption('disable-smart-shrinking', true);
        
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function viewPDF($id)
    {
        $invoice = Invoice::with(['invoiceItems', 'getClient'])->findOrFail($id);
        
        $pdf = SnappyPdf::loadView('back.pdf.invoice-pdf', compact('invoice'))
            ->setOption('enable-local-file-access', true)
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0)
            ->setOption('page-size', 'A4')
            ->setOption('orientation', 'Portrait')
            ->setOption('disable-smart-shrinking', true);
        
        // Display inline in browser
        return $pdf->inline('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function editInvoice(Request $request) {
        $invoice_id = $request->id;
        $invoice = Invoice::with('invoiceItems')->findOrFail($invoice_id);
        $clients = Client::all();

        $data = [
            'pageTitle' => 'Edit Invoice',
            'invoice' => $invoice,
            'clients' => $clients,
            'invoiceItems' => $invoice->invoiceItems,
        ];

        return view('back.pages.edit-invoice', $data);
    }

    public function updateInvoice(Request $request) {
        $invoice_id = $request->invoice_id;
        $invoice = Invoice::findOrFail($invoice_id);
        
        // Get tax rates from settings
        $settings = Setting::first();

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date',
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $invoice_id,
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'particular' => 'required|array',
            'particular.*' => 'required|string',
            'hsn_no' => 'nullable|array',
            'hsn_no.*' => 'nullable|string|max:10',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'unit_price' => 'required|array',
            'unit_price.*' => 'required|numeric|min:0',
            'total_price' => 'required|array',
            'total_price.*' => 'required|numeric|min:0',
            // New fields
            'grand_total' => 'required|numeric|min:0',
            'po_number' => 'nullable|string|max:100',
            'vehicle_no' => 'nullable|string|max:50',
            'e_way_bill_no' => 'nullable|string|max:50',
            'consignee_address' => 'nullable|string',
            'is_sgst' => 'boolean',
            'is_cgst' => 'boolean',
            'is_gst' => 'boolean',
            'is_igst' => 'boolean',
        ], [
            'invoice_number.unique' => 'This invoice number already exists',
            'particular.*.required' => 'Each item must have a description',
            'quantity.*.required' => 'Each item must have a quantity',
            'unit_price.*.required' => 'Each item must have a unit price',
            'grand_total.required' => 'Grand total is required',
        ]);

        try {
            // Calculate subtotal
            $subtotal = array_sum($request->total_price);

            $invoice->update([
                'client_id' => $request->client_id,
                'invoice_date' => $request->invoice_date,
                'invoice_number' => $request->invoice_number,
                'due_date' => $request->due_date,
                'total_amount' => $subtotal,
                'grand_total' => $request->grand_total,
                'is_sgst' => $request->boolean('is_sgst'),
                'is_cgst' => $request->boolean('is_cgst'),
                'is_gst' => $request->boolean('is_gst'),
                'is_igst' => $request->boolean('is_igst'),
                'consignee_address' => $request->consignee_address,
                'e_way_bill_no' => $request->e_way_bill_no,
                'vehicle_no' => $request->vehicle_no,
                'po_number' => $request->po_number,
            ]);

            InvoiceItem::where('invoice_id', $invoice_id)->delete();

            foreach ($request->particular as $key => $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'particular' => $item,
                    'hsn_no' => $request->hsn_no[$key] ?? null,
                    'quantity' => $request->quantity[$key],
                    'unit_price' => $request->unit_price[$key],
                    'total_price' => $request->total_price[$key],
                ]);
            }

            return redirect()->route('admin.all-invoices')->with('success', 'Invoice Updated Successfully');
        } catch(\Exception $e) {
           
            return redirect()->back()->with('error', 'Failed to update invoice: ' . $e->getMessage())->withInput();
        }   
    }

    public function updateInvoicePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
            'amount_to_pay' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_type' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]);
        }

        try {
            $invoice = Invoice::findOrFail($request->invoice_id);
            
            // Calculate new paid amount
            $newPaidAmount = $invoice->paid_amount + $request->amount_to_pay;
            
            // Validate that new paid amount doesn't exceed grand total
            if ($newPaidAmount > $invoice->grand_total) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Payment cannot exceed grand total!'
                ]);
            }
            
            // Update invoice
            $invoice->paid_amount = $newPaidAmount;
            $invoice->paid_date = $request->payment_date;
            
            // Update status based on payment
            if ($newPaidAmount >= $invoice->grand_total) {
                $invoice->status = 'paid';
            } elseif ($newPaidAmount > 0) {
                $invoice->status = 'partial_paid';
            } else {
                $invoice->status = 'pending';
            }
            
            // Check if overdue
            if ($invoice->due_date < now() && $invoice->status != 'paid') {
                $invoice->status = 'overdue';
            }
            
            $invoice->save();
            
            // Create payment record (optional)
            // PaymentHistory::create([
            //     'invoice_id' => $invoice->id,
            //     'amount' => $request->amount_to_pay,
            //     'payment_date' => $request->payment_date,
            //     'payment_type' => $request->payment_type,
            //     'notes' => $request->notes,
            //     'previous_balance' => $invoice->paid_amount - $request->amount_to_pay,
            //     'new_balance' => $invoice->grand_total - $newPaidAmount,
            // ]);

            return response()->json([
                'status' => 1,
                'message' => 'Payment updated successfully!',
                'invoice_id' => $invoice->id,
                'data' => [
                    'paid_amount' => $invoice->paid_amount,
                    'grand_total' => $invoice->grand_total,
                    'status' => $invoice->status,
                ]
            ]);

        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 0,
                'message' => 'Failed to update payment: ' . $e->getMessage()
            ]);
        }
    }
    
    public function emailInvoice(Request $request)
    {
        try {
            $validated = $request->validate([
                'invoice_id' => 'required|exists:invoices,id',
                'recipient_email' => 'required|email',
                'cc_email' => 'nullable|email',
                'email_message' => 'nullable|string|max:1000',
            ]);

            $invoice = Invoice::with(['invoiceItems', 'getClient'])->findOrFail($validated['invoice_id']);

            $pdf = SnappyPdf::loadView('back.pdf.invoice-pdf', compact('invoice'))
                ->setOption('enable-local-file-access', true)
                ->setOption('margin-top', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0)
                ->setOption('margin-right', 0)
                ->setOption('page-size', 'A4');

            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $tempPath = $tempDir . '/invoice-' . $invoice->invoice_number . '-' . time() . '.pdf';
            $pdf->save($tempPath);

            $customMessage = $validated['email_message'] ?? null;

            // ✅ CORRECT WAY - Build the mail instance first, then send
            $mail = Mail::to($validated['recipient_email']);
            
            if (!empty($validated['cc_email'])) {
                $mail->cc($validated['cc_email']);
            }
            
            $mail->send(new InvoiceMail($invoice, $tempPath, $customMessage));

            // Clean up temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            return response()->json([
                'status' => 1,
                'message' => 'Invoice emailed successfully to ' . $validated['recipient_email']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Invoice Email Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 0,
                'message' => 'Failed to send email. Please try again later.'
            ], 500);
        }
    }
}
