<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\DeliveryChallan;
use App\Mail\DeliveryChallanMail;
use Illuminate\Support\Facades\DB;
use App\Models\DeliveryChallanItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\Snappy\Facades\SnappyPdf;

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
        $nextChallanNumber = $lastChallan ? ((int)$lastChallan->challan_number + 1) : 1001;
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
}