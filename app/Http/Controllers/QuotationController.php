<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class QuotationController extends Controller
{
    public function index(Request $request) {
        $data = [
            'pageTitle' => 'All Quotationa',
            'quotations' => Quotation::latest()->get()
        ];

        return view('back.pages.quotation', $data);
    }

    public function create()
    {
        $clients = Client::all();
        
        $data = [
            'pageTitle' => 'Create Quotation',
            'clients' => $clients,
        ];

        return view('back.pages.create-quotation', $data);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|exists:clients,id',
            'attention' => 'nullable|string|max:255',
            'quotation_for' => 'nullable|string|max:255',
            'date' => 'required|date',
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
                'client_id' => $request->client_id,
                'attention' => $request->attention,
                'quotation_for' => $request->quotation_for,
                'date' => $request->date,
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
            'client_id' => 'required|exists:clients,id',
            'attention' => 'nullable|string|max:255',
            'quotation_for' => 'nullable|string|max:255',
            'date' => 'required|date',
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
                'client_id' => $request->client_id,
                'attention' => $request->attention,
                'quotation_for' => $request->quotation_for,
                'date' => $request->date,
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
}
