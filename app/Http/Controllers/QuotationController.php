<?php

namespace App\Http\Controllers;

use App\Mail\QuotationMail;
use App\Models\Client;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Setting;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class QuotationController extends Controller
{
    public function index(Request $request) {
        $data = [
            'pageTitle' => 'All Quotations',
            'quotations' => Quotation::latest()->get()
        ];

        return view('back.pages.quotation', $data);
    }

    public function create()
    {
        $clients = Client::all();
        $lastQuotation = Quotation::latest('id')->first();
        $nextQuotationNumber = $lastQuotation ? ($lastQuotation->quotation_number + 1) : 1001;
        
        $data = [
            'pageTitle' => 'Create Quotation',
            'clients' => $clients,
            'quotationNumber' => $nextQuotationNumber,
        ];

        return view('back.pages.create-quotation', $data);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'quotation_number' => 'required|unique:quotations',
            'attention' => 'nullable|string|max:255',
            'quotation_for' => 'nullable|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'particular' => 'required|array',
            'particular.*' => 'required|string',
            'gsm' => 'nullable|array',
            'gsm.*' => 'nullable|string|max:50',
            'base_price' => 'required|array',
            'base_price.*' => 'required|numeric|min:0',
            'is_tax_included' => 'boolean',
            'is_delivery_charges_included' => 'boolean',
            'is_printing_included' => 'boolean',
            'is_plate_and_punch' => 'boolean',
            'is_lamination' => 'boolean',
        ], [
            'client_id.required' => 'Please select a client',
            'date.required' => 'Quotation date is required',
            'particular.*.required' => 'Each item must have a description',
            'base_price.*.required' => 'Each item must have a base price',
            'base_price.*.numeric' => 'Base price must be a number',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }

        DB::beginTransaction();

        try {
            // Create quotation
            $quotation = Quotation::create([
                'quotation_number' => $request->quotation_number,
                'client_id' => $request->client_id,
                'attention' => $request->attention,
                'quotation_for' => $request->quotation_for,
                'date' => $request->date,
                'notes' => $request->notes,
                'is_tax_included' => $request->boolean('is_tax_included'),
                'is_delivery_charges_included' => $request->boolean('is_delivery_charges_included'),
                'is_printing_included' => $request->boolean('is_printing_included'),
                'is_plate_and_punch' => $request->boolean('is_plate_and_punch'),
                'is_lamination' => $request->boolean('is_lamination'),
            ]);

            // Create items
            foreach ($request->particular as $key => $particular) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'particular' => $particular,
                    'gsm' => $request->gsm[$key] ?? null,
                    'base_price' => $request->base_price[$key],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 1,
                'msg' => 'Quotation created successfully!',
                'redirect' => route('admin.all-quotations')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quotation creation failed: ' . $e->getMessage());

            return response()->json([
                'status' => 0,
                'msg' => 'Failed to create quotation: ' . $e->getMessage()
            ]);
        }
    }

    // Show edit form
    public function edit($id)
    {
        $quotation = Quotation::with(['items', 'client'])->findOrFail($id);
        $clients = Client::all();

        $data = [
            'pageTitle' => 'Edit Quotation',
            'quotation' => $quotation,
            'clients' => $clients,
            'quotationItems' => $quotation->items,
        ];

        return view('back.pages.edit-quotation', $data);
    }

    // Update quotation
    public function update(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'quotation_number' => 'required|unique:quotations,quotation_number,' . $id,
            'client_id' => 'required|exists:clients,id',
            'attention' => 'nullable|string|max:255',
            'quotation_for' => 'nullable|string|max:255',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'particular' => 'required|array',
            'particular.*' => 'required|string',
            'gsm' => 'nullable|array',
            'gsm.*' => 'nullable|string|max:50',
            'base_price' => 'required|array',
            'base_price.*' => 'required|numeric|min:0',
            'is_tax_included' => 'boolean',
            'is_delivery_charges_included' => 'boolean',
            'is_printing_included' => 'boolean',
            'is_plate_and_punch' => 'boolean',
            'is_lamination' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }

        DB::beginTransaction();

        try {
            // Update quotation
            $quotation->update([
                'quotation_number' => $request->quotation_number,
                'client_id' => $request->client_id,
                'attention' => $request->attention,
                'quotation_for' => $request->quotation_for,
                'date' => $request->date,
                'notes' => $request->notes,
                'is_tax_included' => $request->boolean('is_tax_included'),
                'is_delivery_charges_included' => $request->boolean('is_delivery_charges_included'),
                'is_printing_included' => $request->boolean('is_printing_included'),
                'is_plate_and_punch' => $request->boolean('is_plate_and_punch'),
                'is_lamination' => $request->boolean('is_lamination'),
            ]);

            // Delete existing items
            $quotation->items()->delete();

            // Create new items
            foreach ($request->particular as $key => $particular) {
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'particular' => $particular,
                    'gsm' => $request->gsm[$key] ?? null,
                    'base_price' => $request->base_price[$key],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 1,
                'msg' => 'Quotation updated successfully!',
                'redirect' => route('admin.all-quotations')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quotation update failed: ' . $e->getMessage());

            return response()->json([
                'status' => 0,
                'msg' => 'Failed to update quotation: ' . $e->getMessage()
            ]);
        }
    }

    public function viewQuotationDetails($id)
    {
        $quotation = Quotation::with(['client', 'items'])->findOrFail($id);
        $quotationItems = $quotation->items;
        
        $subtotal = $quotationItems->sum('base_price');
        $quotation->subtotal = $subtotal;
        
        $tax_rate = 18;
        $quotation->tax_rate = $tax_rate;
        $quotation->tax = $quotation->is_tax_included ? ($subtotal * $tax_rate / 100) : 0;
        
        $quotation->delivery_charges = $quotation->is_delivery_charges_included ? 500 : 0; // Set your logic
        $quotation->printing_charges = $quotation->is_printing_included ? 300 : 0; // Set your logic
        
        $quotation->discount = 0;
        $quotation->grand_total = $subtotal - $quotation->discount + $quotation->tax + 
                                $quotation->delivery_charges + $quotation->printing_charges;
        
        $quotation->grand_total_in_words = $this->numberToWords($quotation->grand_total);
        
        $data = [
            'pageTitle' => 'Quotation Details - #' . $quotation->id,
            'quotation' => $quotation,
            'quotationItems' => $quotationItems,
        ];
        
        return view('back.pages.quotation-details', $data);
    }

    private function numberToWords($number)
    {
        return 'Rupees ' . number_format($number, 2) . ' only';
    }

    // Delete quotation
    public function destroy($id)
    {
        try {
            $quotation = Quotation::findOrFail($id);
            $quotation->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Quotation deleted successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error('Quotation deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => 0,
                'message' => 'Failed to delete quotation: ' . $e->getMessage()
            ]);
        }
    }

    public function viewPDF($id)
    {
        $quotation = Quotation::with(['items', 'client'])->findOrFail($id);
        $settings = Setting::first();
        
        $pdf = SnappyPdf::loadView('back.pdf.quotation-pdf', compact('quotation', 'settings'))
                ->setOption('enable-local-file-access', true)
                ->setOption('margin-top', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0)
                ->setOption('margin-right', 0)
                ->setOption('page-size', 'A4')
                ->setOption('orientation', 'Portrait')
                ->setOption('disable-smart-shrinking', true);
        
        // Display inline in browser
        return $pdf->inline('quotation-' . $quotation->id . '.pdf');
    }

    public function downloadPDF($id)
    {
        $quotation = Quotation::with(['items', 'client'])->findOrFail($id);
        $settings = Setting::first();
        
        $pdf = SnappyPdf::loadView('back.pdf.quotation-pdf', compact('quotation', 'settings'))
                ->setOption('enable-local-file-access', true)
                ->setOption('margin-top', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0)
                ->setOption('margin-right', 0)
                ->setOption('page-size', 'A4')
                ->setOption('orientation', 'Portrait')
                ->setOption('disable-smart-shrinking', true);
        
        return $pdf->download('quotation-' . $quotation->id . '.pdf');
    }

    public function emailQuotation(Request $request)
    {
        try {
            $validated = $request->validate([
                'quotation_id'    => 'required|exists:quotations,id',
                'recipient_email' => 'required|email',
                'cc_email'        => 'nullable|email',
                'email_message'   => 'nullable|string|max:1000',
            ]);

            $quotation = Quotation::with(['items', 'client'])->findOrFail($validated['quotation_id']);
            $settings  = Setting::first();

            // Generate PDF (mirrors your downloadPDF method exactly)
            $pdf = SnappyPdf::loadView('back.pdf.quotation-pdf', compact('quotation', 'settings'))
                        ->setOption('enable-local-file-access', true)
                        ->setOption('margin-top', 0)
                        ->setOption('margin-bottom', 0)
                        ->setOption('margin-left', 0)
                        ->setOption('margin-right', 0)
                        ->setOption('page-size', 'A4')
                        ->setOption('orientation', 'Portrait')
                        ->setOption('disable-smart-shrinking', true);

            // Create temp directory if needed
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $tempPath = $tempDir . '/quotation-' . $quotation->id . '-' . time() . '.pdf';
            $pdf->save($tempPath);

            // Build mail and send
            $mail = Mail::to($validated['recipient_email']);

            if (!empty($validated['cc_email'])) {
                $mail->cc($validated['cc_email']);
            }

            $mail->send(new QuotationMail($quotation, $tempPath, $validated['email_message'] ?? null));

            // Clean up temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            return response()->json([
                'status'  => 1,
                'message' => 'Quotation emailed successfully to ' . $validated['recipient_email']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => 0,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Quotation Email Error: ' . $e->getMessage());

            return response()->json([
                'status'  => 0,
                'message' => 'Failed to send email. Please try again later.'
            ], 500);
        }
    }
}
