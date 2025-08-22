<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnquiryResponse;

class EnquiryResponseController extends Controller
{
    public function index()
    {
        return EnquiryResponse::all();
    }

    // إضافة رد جديد
    public function store(Request $request)
    {
        $request->validate([
            'enquiry_id' => 'required|integer',
            'is_admin' => 'nullable|boolean',
            'content' => 'nullable|string',
        ]);

        $response = EnquiryResponse::create([
            'enquiry_id' => $request->enquiry_id,
            'is_admin' => $request->is_admin ?? false,
            'content' => $request->content,
        ]);

        return response()->json($response, 201);
    }

    // عرض رد واحد
    public function show(EnquiryResponse $enquiryResponse)
    {
        return response()->json($enquiryResponse);
    }

    // تحديث رد
    public function update(Request $request, EnquiryResponse $enquiryResponse)
    {
        $enquiryResponse->update($request->only('content', 'is_admin'));
        return response()->json($enquiryResponse);
    }

    // حذف رد
    public function destroy(EnquiryResponse $enquiryResponse)
    {
        $enquiryResponse->delete();
        return response()->json(null, 204);
    }
}
