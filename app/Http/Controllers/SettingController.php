<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index(Request $request) {
        $data = [
            'pageTitle' => 'Settings',
            'settings' => Setting::all()
        ];

        return view('back.pages.settings', $data);
    }

    public function create() {
        $data = [
            'pageTitle' => 'Create Setting'
        ];

        return view('back.pages.create-setting', $data);
    }

    public function storeSetting(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'setting_name' => 'nullable|string|max:255',
            'setting_phone' => 'nullable|string|max:20',
            'setting_email' => 'nullable|email|max:255',
            'setting_address' => 'nullable|string|max:500',
            'setting_gst_no' => 'nullable|string|max:50',
            'setting_website_url' => 'nullable|url|max:255',
            'setting_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'setting_signature.image' => 'The signature must be an image file (png, jpg, jpeg).',
            'setting_signature.mimes' => 'The signature must be a file of type: png, jpg, jpeg.',
            'setting_signature.max' => 'The signature may not be greater than 2MB.',
            'setting_website_url.url' => 'Please enter a valid website URL (including http:// or https://).',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }

        try {
            // Prepare data for storage
            $data = [
                'name' => $request->setting_name,
                'phone' => $request->setting_phone,
                'email' => $request->setting_email,
                'address' => $request->setting_address,
                'gst_no' => $request->setting_gst_no,
                'website_url' => $request->setting_website_url,
            ];

            // Handle signature image upload
            if ($request->hasFile('setting_signature')) {
                // Delete old signature if exists (if updating)
                $existingSetting = Setting::first();
                if ($existingSetting && $existingSetting->signature) {
                    Storage::delete($existingSetting->signature);
                }
                
                // Store new signature
                $signaturePath = $request->file('setting_signature')->store('uploads', 'public');
                $data['signature'] = $signaturePath;
            }

            // Since this is settings (typically single record), use firstOrCreate
            $setting = Setting::firstOrCreate([], $data);
            
            // If setting already exists, update it
            if ($setting->wasRecentlyCreated === false) {
                $setting->update($data);
            }

            return response()->json([
                'status' => 1,
                'msg' => 'Settings saved successfully!'
            ]);

        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 0,
                'msg' => 'Failed to save settings. Please try again.'
            ]);
        }
    }

    public function editSetting(Request $request) {
        $setting_id = $request->id;
        $setting = Setting::findOrFail($setting_id);

        $data = [
            'pageTitle' => 'Edit Setting',
            'setting' => $setting,
        ];

        return view('back.pages.edit-setting', $data);
    }

    public function updateSetting(Request $request)
    {
        $setting = Setting::first();
        
        if (!$setting) {
            return response()->json([
                'status' => 0,
                'msg' => 'Settings not found. Please create settings first.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'setting_name' => 'nullable|string|max:255',
            'setting_phone' => 'nullable|string|max:20',
            'setting_email' => 'nullable|email|max:255',
            'setting_address' => 'nullable|string|max:500',
            'setting_gst_no' => 'nullable|string|max:50',
            'setting_website_url' => 'nullable|url|max:255',
            'setting_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'setting_sgst' => 'nullable|numeric|min:0|max:100',
            'setting_cgst' => 'nullable|numeric|min:0|max:100',
            'setting_igst' => 'nullable|numeric|min:0|max:100',
        ], [
            'setting_email.email' => 'Please enter a valid email address.',
            'setting_website_url.url' => 'Please enter a valid website URL (including http:// or https://).',
            'setting_signature.image' => 'The signature must be an image file.',
            'setting_signature.mimes' => 'The signature must be a file of type: png, jpg, jpeg.',
            'setting_signature.max' => 'The signature may not be greater than 2MB.',
            'setting_sgst.numeric' => 'SGST must be a number.',
            'setting_cgst.numeric' => 'CGST must be a number.',
            'setting_igst.numeric' => 'IGST must be a number.',
            'setting_sgst.min' => 'SGST cannot be less than 0%.',
            'setting_cgst.min' => 'CGST cannot be less than 0%.',
            'setting_igst.min' => 'IGST cannot be less than 0%.',
            'setting_sgst.max' => 'SGST cannot be more than 100%.',
            'setting_cgst.max' => 'CGST cannot be more than 100%.',
            'setting_igst.max' => 'IGST cannot be more than 100%.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'error' => $validator->errors()->toArray()
            ]);
        }

        try {
            $data = [
                'name' => $request->setting_name,
                'phone' => $request->setting_phone,
                'email' => $request->setting_email,
                'address' => $request->setting_address,
                'gst_no' => $request->setting_gst_no,
                'website_url' => $request->setting_website_url,
                'sgst' => $request->setting_sgst ?? 0,
                'cgst' => $request->setting_cgst ?? 0,
                'igst' => $request->setting_igst ?? 0,
            ];

            $signaturePath = null;
            if ($request->hasFile('setting_signature')) {
                if ($setting->signature && Storage::exists('public/' . $setting->signature)) {
                    Storage::delete('public/' . $setting->signature);
                }
                
                $signaturePath = $request->file('setting_signature')->store('uploads', 'public');
                $data['signature'] = $signaturePath;
            }

            $updated = $setting->update($data);

            if ($updated) {
                $response = [
                    'status' => 1,
                    'msg' => 'Settings updated successfully!',
                    'sgst' => number_format($setting->sgst, 2),
                    'cgst' => number_format($setting->cgst, 2),
                    'igst' => number_format($setting->igst, 2),
                ];
                
                if ($signaturePath) {
                    $response['signature_path'] = asset('storage/' . $signaturePath);
                }
                
                return response()->json($response);
            } else {
                return response()->json([
                    'status' => 0,
                    'msg' => 'Failed to update settings. Please try again.'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'msg' => 'An error occurred while updating settings: ' . $e->getMessage()
            ]);
        }
    }

}
