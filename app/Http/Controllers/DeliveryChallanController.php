<?php

namespace App\Http\Controllers;

use App\Mail\DeliveryChallanMail;
use App\Models\Client;
use App\Models\DeliveryChallan;
use App\Models\DeliveryChallanItem;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DeliveryChallanController extends Controller
{

    public function index(Request $request) {
        $data = [
            'pageTitle' => 'All Delivery Challans',
            'challans' => DeliveryChallan::with('client')->latest()->get()
        ];

        return view('back.pages.delivery-challans', $data);
    }

    // Show create form
    public function create()
    {
        $lastChallan = DeliveryChallan::latest('id')->first();
        $nextChallanNumber = $lastChallan ? ((int)$lastChallan->challan_number + 1) : 0001;
        $clients = Client::all();
        
        $data = [
            'pageTitle' => 'Create Delivery Challan',
            'clients' => $clients,
            'challanNumber' => $nextChallanNumber,
        ];

        return view('back.pages.create-delivery-challan', $data);
    }

    // Store new delivery challan
    public function storeChallan(Request $request)
    {
        // Validation
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'challan_number' => 'required|unique:delivery_challans',
            'challan_date' => 'required|date',
            'vehicle_no' => 'nullable|string|max:50',
            'delivery_partner_phone' => 'nullable|string|max:20',
            'consignee_address' => 'nullable|string',
            'particular' => 'required|array',
            'particular.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0',
            'total_amount' => 'required|array',
            'total_amount.*' => 'required|numeric|min:0',
        ], [
            'client_id.required' => 'Please select a client',
            'challan_number.required' => 'Challan number is required',
            'challan_number.unique' => 'This challan number already exists',
            'challan_date.required' => 'Challan date is required',
            'particular.*.required' => 'Each item must have a description',
            'quantity.*.required' => 'Each item must have a quantity',
            'total_amount.*.required' => 'Each item must have a total amount',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total amount
            $totalAmount = array_sum($request->total_amount);

            // Create delivery challan
            $challan = DeliveryChallan::create([
                'challan_number' => $request->challan_number,
                'client_id' => $request->client_id,
                'challan_date' => $request->challan_date,
                'vehicle_no' => $request->vehicle_no,
                'delivery_partner_phone' => $request->delivery_partner_phone,
                'consignee_address' => $request->consignee_address,
                'total_amount' => $totalAmount,
            ]);

            // Create items
            foreach ($request->particular as $key => $particular) {
                DeliveryChallanItem::create([
                    'delivery_challan_id' => $challan->id,
                    'particular' => $particular,
                    'quantity' => $request->quantity[$key],
                    'total_amount' => $request->total_amount[$key],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.all-challans')->with('success', 'Delivery Challan created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delivery Challan creation failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to create delivery challan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Show edit form
    public function editDelievryChallan($id)
    {
        $challan = DeliveryChallan::with(['items', 'client'])->findOrFail($id);
        $clients = Client::all();

        $data = [
            'pageTitle' => 'Edit Delivery Challan',
            'challan' => $challan,
            'clients' => $clients,
            'challanItems' => $challan->items,
        ];

        return view('back.pages.edit-delivery-challan', $data);
    }

    // Update delivery challan
    public function updateDeliveryChallan(Request $request, $id)
    {
        $challan = DeliveryChallan::findOrFail($id);

        // Validation
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'challan_number' => 'required|unique:delivery_challans,challan_number,' . $id,
            'challan_date' => 'required|date',
            'vehicle_no' => 'nullable|string|max:50',
            'delivery_partner_phone' => 'nullable|string|max:20',
            'consignee_address' => 'nullable|string',
            'particular' => 'required|array',
            'particular.*' => 'required|string',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0',
            'total_amount' => 'required|array',
            'total_amount.*' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Calculate total amount
            $totalAmount = array_sum($request->total_amount);

            // Update delivery challan
            $challan->update([
                'client_id' => $request->client_id,
                'challan_number' => $request->challan_number,
                'challan_date' => $request->challan_date,
                'vehicle_no' => $request->vehicle_no,
                'delivery_partner_phone' => $request->delivery_partner_phone,
                'consignee_address' => $request->consignee_address,
                'total_amount' => $totalAmount,
            ]);

            // Delete existing items
            $challan->items()->delete();

            // Create new items
            foreach ($request->particular as $key => $particular) {
                DeliveryChallanItem::create([
                    'delivery_challan_id' => $challan->id,
                    'particular' => $particular,
                    'quantity' => $request->quantity[$key],
                    'total_amount' => $request->total_amount[$key],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.all-challans')->with('success', 'Delivery Challan updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delivery Challan update failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to update delivery challan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function viewDeliveryChallan($id)
    {
        $challan = DeliveryChallan::with(['client', 'items'])->findOrFail($id);
        $challanItems = $challan->items;
        
        $data = [
            'pageTitle' => 'Delivery Challan Details - ' . $challan->challan_number,
            'challan' => $challan,
            'challanItems' => $challanItems,
        ];
        
        return view('back.pages.delivery-challan-details', $data);
    }

    // Delete delivery challan
    public function destroy($id)
    {
        try {
            $challan = DeliveryChallan::findOrFail($id);
            $challan->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Delivery Challan deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Delivery Challan deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => 0,
                'message' => 'Failed to delete delivery challan: ' . $e->getMessage()
            ]);
        }
    }

    // Download PDF
    public function downloadPDF($id)
    {
        $challan = DeliveryChallan::with(['items', 'client'])->findOrFail($id);
        $settings = \App\Models\Setting::first();

        $pdf = SnappyPdf::loadView('back.pdf.delivery-challan-pdf', compact('challan', 'settings'))
                ->setOption('enable-local-file-access', true)
                ->setOption('margin-top', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0)
                ->setOption('margin-right', 0)
                ->setOption('page-size', 'A4')
                ->setOption('orientation', 'Portrait')
                ->setOption('disable-smart-shrinking', true);

        return $pdf->download('delivery-challan-' . $challan->challan_number . '.pdf');
    }

    public function viewPDF($id)
    {
        $challan = DeliveryChallan::with(['items', 'client'])->findOrFail($id);
        $settings = \App\Models\Setting::first();

        $pdf = SnappyPdf::loadView('back.pdf.delivery-challan-pdf', compact('challan', 'settings'))
                ->setOption('enable-local-file-access', true)
                ->setOption('margin-top', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0)
                ->setOption('margin-right', 0)
                ->setOption('page-size', 'A4')
                ->setOption('orientation', 'Portrait')
                ->setOption('disable-smart-shrinking', true);

        return $pdf->inline('delivery-challan-' . $challan->challan_number . '.pdf');
    }


    public function emailDeliveryChallan(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'challan_id' => 'required|exists:delivery_challans,id',
                'recipient_email' => 'required|email',
                'cc_email' => 'nullable|email',
                'email_message' => 'nullable|string|max:1000',
            ]);

            // Find the delivery challan
            $challan = DeliveryChallan::with(['items', 'client'])->findOrFail($validated['challan_id']);
            $settings = \App\Models\Setting::first();

            // Generate PDF
            $pdf = SnappyPdf::loadView('back.pdf.delivery-challan-pdf', compact('challan', 'settings'))
                    ->setOption('enable-local-file-access', true)
                    ->setOption('margin-top', 0)
                    ->setOption('margin-bottom', 0)
                    ->setOption('margin-left', 0)
                    ->setOption('margin-right', 0)
                    ->setOption('page-size', 'A4')
                    ->setOption('orientation', 'Portrait')
                    ->setOption('disable-smart-shrinking', true);

            // Create temp directory if it doesn't exist
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Save PDF temporarily
            $tempPath = $tempDir . '/delivery-challan-' . $challan->challan_number . '-' . time() . '.pdf';
            $pdf->save($tempPath);

            // Get custom message if provided
            $customMessage = $validated['email_message'] ?? null;

            // Build mail with recipients
            $mail = Mail::to($validated['recipient_email']);
            
            // Add CC if provided
            if (!empty($validated['cc_email'])) {
                $mail->cc($validated['cc_email']);
            }
            
            // Send the email
            $mail->send(new DeliveryChallanMail($challan, $tempPath, $customMessage));

            // Clean up temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            return response()->json([
                'status' => 1,
                'message' => 'Delivery challan emailed successfully to ' . $validated['recipient_email']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Delivery Challan Email Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 0,
                'message' => 'Failed to send email. Please try again later.'
            ], 500);
        }
    }

    public function export()
    {
        $challans = DeliveryChallan::with(['client', 'items'])->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Delivery Challans');

        // ── Color palette ──────────────────────────────────────────
        $headerBg    = '1F4E79'; // dark blue  – main title
        $subHeaderBg = '2E75B6'; // mid blue   – challan header
        $itemHeaderBg= 'BDD7EE'; // light blue – column headers
        $altRowBg    = 'F2F7FC'; // very light – alternating rows
        $totalBg     = 'D6E4F0'; // summary rows

        // ── Helper: borders ────────────────────────────────────────
        $border = function(string $range) use ($sheet) {
            $sheet->getStyle($range)->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('AAAAAA'));
        };

        // ── Helper: style header ───────────────────────────────────
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

        // ── Column widths ──────────────────────────────────────────
        $cols = ['A'=>5,'B'=>20,'C'=>15,'D'=>25,'E'=>25,'F'=>18,'G'=>18,'H'=>18,'I'=>18];
        foreach ($cols as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // ════════════════════════════════════════════════════════════
        // SHEET TITLE
        // ════════════════════════════════════════════════════════════
        $sheet->mergeCells('A1:I1');
        $sheet->setCellValue('A1', 'DELIVERY CHALLAN EXPORT REPORT — Generated: ' . now()->format('d M Y, H:i'));
        $styleHeader('A1', $headerBg);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $row = 3;

        foreach ($challans as $challan) {
            $client = $challan->client ?? $challan->getClient ?? null;

            // ── Challan header block ───────────────────────────────
            $sheet->mergeCells("A{$row}:I{$row}");
            $label = 'Challan #' . $challan->challan_number
                    . '   |   Client: '  . ($client->name ?? 'N/A')
                    . '   |   Date: '    . $challan->challan_date->format('d M Y')
                    . ($challan->vehicle_no ? '   |   Vehicle: ' . $challan->vehicle_no : '')
                    . ($challan->delivery_partner_phone ? '   |   Partner Ph: ' . $challan->delivery_partner_phone : '');
            $sheet->setCellValue("A{$row}", $label);
            $styleHeader("A{$row}", $subHeaderBg);
            $sheet->getRowDimension($row)->setRowHeight(22);
            $row++;

            // ── Client / delivery info row ─────────────────────────
            $sheet->mergeCells("A{$row}:D{$row}");
            $sheet->setCellValue("A{$row}",
                'Client: ' . ($client->name ?? 'N/A')
                . ($client->phone   ? ' | Ph: '    . $client->phone   : '')
                . ($client->email   ? ' | Email: ' . $client->email   : '')
                . ($client->address ? ' | '        . $client->address : '')
            );
            $sheet->getStyle("A{$row}")->getFont()->setItalic(true)->setSize(9);

            $sheet->mergeCells("E{$row}:I{$row}");
            $sheet->setCellValue("E{$row}",
                'Delivery Address: ' . ($challan->consignee_address ?? $client->address ?? 'Same as client address')
                . ($challan->vehicle_no            ? ' | Vehicle: '    . $challan->vehicle_no            : '')
                . ($challan->delivery_partner_phone? ' | Partner Ph: ' . $challan->delivery_partner_phone: '')
            );
            $sheet->getStyle("E{$row}")->getFont()->setItalic(true)->setSize(9);
            $sheet->getRowDimension($row)->setRowHeight(18);
            $row++;

            // ── Items column headers ───────────────────────────────
            $itemHeaders = ['#', 'Particular', 'Quantity', 'Total Amount (₹)'];
            $itemCols    = ['A', 'B', 'C', 'D'];
            foreach ($itemHeaders as $i => $h) {
                $sheet->setCellValue($itemCols[$i] . $row, $h);
            }
            $sheet->getStyle("A{$row}:D{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($itemHeaderBg);
            $sheet->getStyle("A{$row}:D{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}:D{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Summary headers (right side)
            $sheet->setCellValue("F{$row}", 'Summary');
            $sheet->setCellValue("G{$row}", 'Value');
            $sheet->getStyle("F{$row}:G{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($itemHeaderBg);
            $sheet->getStyle("F{$row}:G{$row}")->getFont()->setBold(true);
            $sheet->getStyle("F{$row}:G{$row}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $border("A{$row}:I{$row}");
            $row++;

            // ── Line items ─────────────────────────────────────────
            $items = $challan->items ?? collect();
            $itemStartRow = $row;

            foreach ($items as $idx => $item) {
                $isAlt = $idx % 2 === 1;
                $sheet->setCellValue("A{$row}", $idx + 1);
                $sheet->setCellValue("B{$row}", $item->particular);
                $sheet->setCellValue("C{$row}", $item->quantity);
                $sheet->setCellValue("D{$row}", $item->total_amount);

                $sheet->getStyle("C{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle("D{$row}")->getNumberFormat()->setFormatCode('#,##0.00');

                if ($isAlt) {
                    $sheet->getStyle("A{$row}:D{$row}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($altRowBg);
                }
                $border("A{$row}:D{$row}");
                $row++;
            }

            // ── Summary panel (right side, aligned to items) ───────
            $summaryRow = $itemStartRow;

            $summaryItems = [
                ['Total Items',   $items->count()],
                ['Grand Total',   $challan->total_amount],
            ];

            foreach ($summaryItems as [$label2, $val]) {
                $sheet->setCellValue("F{$summaryRow}", $label2);
                $sheet->setCellValue("G{$summaryRow}", $val);
                $sheet->getStyle("F{$summaryRow}:G{$summaryRow}")->getFont()->setBold(true);

                if ($label2 === 'Grand Total') {
                    $sheet->getStyle("F{$summaryRow}:G{$summaryRow}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($totalBg);
                    $sheet->getStyle("G{$summaryRow}")->getNumberFormat()->setFormatCode('#,##0.00');
                }
                $border("F{$summaryRow}:G{$summaryRow}");
                $summaryRow++;
            }

            $row = max($row, $summaryRow);
            $row += 2; // gap between challans
        }

        // ── Freeze top rows ────────────────────────────────────────
        $sheet->freezePane('A3');

        // ── Stream response ────────────────────────────────────────
        $filename = 'delivery_challans_' . date('Y-m-d_H-i-s') . '.xlsx';

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