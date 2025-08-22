<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enquiry;
class EnquiryController extends Controller
{
     // عرض كل الاستفسارات
    public function index()
    {
        return Enquiry::all();
    }

    // رفع استفسار جديد
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'content' => 'nullable|string',
        ]);

        $enquiry = Enquiry::create([
            'user_id' => $request->user_id,
            'content' => $request->content,
        ]);

        return response()->json($enquiry, 201);
    }

    // عرض استفسار واحد
    public function show(Enquiry $enquiry)
    {
        return response()->json($enquiry);
    }

    // تحديث استفسار
    public function update(Request $request, Enquiry $enquiry)
    {
        $enquiry->update($request->only('content'));
        return response()->json($enquiry);
    }

    // حذف استفسار
    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();
        return response()->json(null, 204);
    }
}
