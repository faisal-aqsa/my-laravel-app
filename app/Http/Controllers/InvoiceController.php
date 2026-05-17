<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PaymentHistory;
use App\Models\Setting;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceController extends Controller
{
    public function index(Request $request) {
        $data = [
            'pageTitle' => 'All Invoice',
            'invoices' => Invoice::latest()->get()
        ];

        return view('back.pages.invoice', $data);
    }

    public function create(Request $request) {
        $lastInvoice = Invoice::latest('id')->first();
        $nextInvoiceNumber = $lastInvoice ? ($lastInvoice->invoice_number + 1) : 0001;
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
            'is_performa_invoice' => 'boolean',
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
                'is_performa_invoice' => $request->boolean('is_performa_invoice'),
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
            'is_performa_invoice' => 'boolean',
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
                'is_performa_invoice' => $request->boolean('is_performa_invoice'),
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

    public function viewInvoice($id)
    {
        $invoice = Invoice::with(['getClient', 'invoiceItems'])->findOrFail($id);
        $invoiceItems = $invoice->invoiceItems;
        
        $data = [
            'pageTitle' => 'Invoice Details - ' . $invoice->invoice_number,
            'invoice' => $invoice,
            'invoiceItems' => $invoiceItems,
        ];
        
        return view('back.pages.invoice-details', $data);
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
            PaymentHistory::create([
                'invoice_id' => $invoice->id,
                'amount' => $request->amount_to_pay,
                'payment_date' => $request->payment_date,
                'payment_type' => $request->payment_type,
                'notes' => $request->notes,
                'previous_balance' => $invoice->paid_amount - $request->amount_to_pay,
                'new_balance' => $invoice->grand_total - $newPaidAmount,
            ]);

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
                    ->setOption('page-size', 'A4')
                    ->setOption('orientation', 'Portrait')
                    ->setOption('disable-smart-shrinking', true);

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

    public function paymentHistory(Request $request)
    {
        $data = [
            'pageTitle' => 'Payment History',
            'payments' => PaymentHistory::with('invoice')
                ->latest()
                ->get()
        ];

        return view('back.pages.payment-history', $data);
    }

    public function deleteInvoice($id)
    {
        try {
            $challan = Invoice::findOrFail($id);
            $challan->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Invoice deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Invoice deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => 0,
                'message' => 'Failed to delete invoice: ' . $e->getMessage()
            ]);
        }
    }

    public function updateInvoiceStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:invoices,id',
            'status' => 'required|in:pending,paid,partial_paid,overdue',
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
            
            // Update status
            $invoice->status = $request->status;
            $invoice->save();
            
            // Log the status change
            Log::info('Invoice status updated', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'old_status' => $invoice->getOriginal('status'),
                'new_status' => $invoice->status,
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'Invoice status updated successfully!',
                'data' => [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'status' => $invoice->status,
                    'paid_amount' => $invoice->paid_amount,
                    'grand_total' => $invoice->grand_total,
                    'remaining' => $invoice->grand_total - $invoice->paid_amount,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Invoice status update failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => 0,
                'message' => 'Failed to update invoice status: ' . $e->getMessage()
            ]);
        }
    }

    public function export()
    {
        $invoices = Invoice::with(['getClient', 'invoiceItems'])->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Invoices');

        // ── Color palette ─────────────────────────────────────────
        $headerBg   = '1F4E79'; // dark blue  – section headers
        $subHeaderBg= '2E75B6'; // mid blue   – column headers
        $itemHeaderBg='BDD7EE'; // light blue – item sub-headers
        $altRowBg   = 'F2F7FC'; // very light – alternating rows
        $totalBg    = 'D6E4F0'; // summary rows

        // ── Helper: apply border to a range ───────────────────────
        $border = function(string $range) use ($sheet) {
            $sheet->getStyle($range)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('AAAAAA'));
        };

        // ── Helper: style a header row ─────────────────────────────
        $styleHeader = function(string $range, string $bg, bool $white = true) use ($sheet) {
            $style = $sheet->getStyle($range);
            $style->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($bg);
            $style->getFont()->setBold(true);
            if ($white) $style->getFont()->getColor()->setRGB('FFFFFF');
            $style->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setWrapText(true);
        };

        // ════════════════════════════════════════════════════════════
        // SHEET TITLE
        // ════════════════════════════════════════════════════════════
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'INVOICE EXPORT REPORT — Generated: ' . now()->format('d M Y, H:i'));
        $styleHeader('A1', $headerBg);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // ── Column widths ──────────────────────────────────────────
        $cols = ['A'=>5,'B'=>18,'C'=>15,'D'=>22,'E'=>22,'F'=>18,'G'=>14,
                'H'=>14,'I'=>14,'J'=>14,'K'=>14,'L'=>12,'M'=>14,'N'=>20];
        foreach ($cols as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $row = 3; // start data from row 3

        foreach ($invoices as $invoice) {
            $client = $invoice->getClient;

            // ── Invoice header block ───────────────────────────────
            $sheet->mergeCells("A{$row}:N{$row}");
            $label = ($invoice->is_performa_invoice ? '[PROFORMA] ' : '[TAX INVOICE] ')
                    . 'Invoice #' . $invoice->invoice_number
                    . '   |   Client: ' . ($client->name ?? 'N/A')
                    . '   |   Date: ' . $invoice->invoice_date->format('d M Y')
                    . '   |   Due: '  . $invoice->due_date->format('d M Y')
                    . '   |   Status: ' . ucfirst(str_replace('_', ' ', $invoice->status));
            $sheet->setCellValue("A{$row}", $label);
            $styleHeader("A{$row}", $subHeaderBg);
            $sheet->getRowDimension($row)->setRowHeight(22);
            $row++;

            // ── Client / shipping info row ─────────────────────────
            $sheet->mergeCells("A{$row}:G{$row}");
            $sheet->setCellValue("A{$row}",
                'Bill To: ' . ($client->name ?? 'N/A')
                . ' | ' . ($client->factory_address ?? '')
                . ($client->gst_no ? ' | GST: ' . $client->gst_no : '')
                . ($client->phone   ? ' | Ph: '  . $client->phone   : '')
            );
            $sheet->getStyle("A{$row}")->getFont()->setItalic(true)->setSize(9);

            $sheet->mergeCells("H{$row}:N{$row}");
            $sheet->setCellValue("H{$row}",
                'Ship To: ' . ($invoice->consignee_address ?: 'Same as billing')
                . ($invoice->po_number    ? ' | PO: '       . $invoice->po_number    : '')
                . ($invoice->vehicle_no   ? ' | Vehicle: '  . $invoice->vehicle_no   : '')
                . ($invoice->e_way_bill_no? ' | E-Way: '    . $invoice->e_way_bill_no: '')
            );
            $sheet->getStyle("H{$row}")->getFont()->setItalic(true)->setSize(9);
            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;

            // ── Items column headers ───────────────────────────────
            $itemHeaders = ['#','Particular','HSN No','Qty','Unit Price (₹)','Total (₹)'];
            $itemCols    = ['A','B','C','D','E','F'];
            foreach ($itemHeaders as $i => $h) {
                $sheet->setCellValue($itemCols[$i] . $row, $h);
            }
            $sheet->getStyle("A{$row}:F{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($itemHeaderBg);
            $sheet->getStyle("A{$row}:F{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}:F{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Tax / summary headers (right side)
            $sheet->setCellValue("H{$row}", 'Tax Type');
            $sheet->setCellValue("I{$row}", 'Rate (%)');
            $sheet->setCellValue("J{$row}", 'Amount (₹)');
            $sheet->setCellValue("L{$row}", 'Summary');
            $sheet->setCellValue("M{$row}", 'Amount (₹)');
            $sheet->getStyle("H{$row}:M{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($itemHeaderBg);
            $sheet->getStyle("H{$row}:M{$row}")->getFont()->setBold(true);
            $border("A{$row}:N{$row}");
            $row++;

            // ── Line items ─────────────────────────────────────────
            $itemStartRow = $row;
            foreach ($invoice->invoiceItems as $idx => $item) {
                $isAlt = $idx % 2 === 1;
                $lineTotal = $item->quantity * $item->unit_price;
                $sheet->setCellValue("A{$row}", $idx + 1);
                $sheet->setCellValue("B{$row}", $item->particular);
                $sheet->setCellValue("C{$row}", $item->hsn_no ?? 'N/A');
                $sheet->setCellValue("D{$row}", $item->quantity);
                $sheet->setCellValue("E{$row}", $item->unit_price);
                $sheet->setCellValue("F{$row}", $lineTotal);

                $sheet->getStyle("E{$row}:F{$row}")->getNumberFormat()
                    ->setFormatCode('#,##0.00');
                $sheet->getStyle("D{$row}")->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                if ($isAlt) {
                    $sheet->getStyle("A{$row}:F{$row}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($altRowBg);
                }
                $border("A{$row}:F{$row}");
                $row++;
            }
            $itemEndRow = $row - 1;

            // ── Tax info (right panel, aligned to items area) ──────
            $taxRow = $itemStartRow;
            $settings = Setting::first();

            if ($invoice->is_sgst) {
                $rate   = $invoice->sgst_rate ?? $settings->sgst;
                $amount = ($invoice->total_amount * $rate) / 100;
                $sheet->setCellValue("H{$taxRow}", 'SGST');
                $sheet->setCellValue("I{$taxRow}", $rate . '%');
                $sheet->setCellValue("J{$taxRow}", $amount);
                $sheet->getStyle("J{$taxRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                $taxRow++;
            }
            if ($invoice->is_cgst) {
                $rate   = $invoice->cgst_rate ?? $settings->cgst;
                $amount = ($invoice->total_amount * $rate) / 100;
                $sheet->setCellValue("H{$taxRow}", 'CGST');
                $sheet->setCellValue("I{$taxRow}", $rate . '%');
                $sheet->setCellValue("J{$taxRow}", $amount);
                $sheet->getStyle("J{$taxRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                $taxRow++;
            }
            if ($invoice->is_igst) {
                $rate   = $invoice->igst_rate ?? $settings->igst;
                $amount = ($invoice->total_amount * $rate) / 100;
                $sheet->setCellValue("H{$taxRow}", 'IGST');
                $sheet->setCellValue("I{$taxRow}", $rate . '%');
                $sheet->setCellValue("J{$taxRow}", $amount);
                $sheet->getStyle("J{$taxRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                $taxRow++;
            }
            if (!$invoice->is_sgst && !$invoice->is_cgst && !$invoice->is_igst) {
                $sheet->setCellValue("H{$taxRow}", 'No Tax');
                $taxRow++;
            }

            // ── Amount summary (right panel) ───────────────────────
            $summaryRow = $itemStartRow;
            $remaining  = $invoice->grand_total - ($invoice->paid_amount ?? 0);

            $summaryItems = [
                ['Subtotal',    $invoice->total_amount],
                ['Grand Total', $invoice->grand_total],
                ['Paid Amount', $invoice->paid_amount ?? 0],
                ['Remaining',   $remaining],
            ];
            foreach ($summaryItems as [$label2, $val]) {
                $sheet->setCellValue("L{$summaryRow}", $label2);
                $sheet->setCellValue("M{$summaryRow}", $val);
                $sheet->getStyle("M{$summaryRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("L{$summaryRow}:M{$summaryRow}")->getFont()->setBold(true);
                if ($label2 === 'Grand Total') {
                    $sheet->getStyle("L{$summaryRow}:M{$summaryRow}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($totalBg);
                }
                if ($label2 === 'Remaining' && $remaining > 0) {
                    $sheet->getStyle("M{$summaryRow}")->getFont()->getColor()->setRGB('C00000');
                }
                $border("L{$summaryRow}:M{$summaryRow}");
                $summaryRow++;
            }

            $row = max($row, $taxRow, $summaryRow);
            $row += 2; // gap between invoices
        }

        // ── Freeze top rows ────────────────────────────────────────
        $sheet->freezePane('A3');

        // ── Stream response ────────────────────────────────────────
        $filename = 'invoices_' . date('Y-m-d_H-i-s') . '.xlsx';

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control'       => 'max-age=0',
        ]);
    }
}
