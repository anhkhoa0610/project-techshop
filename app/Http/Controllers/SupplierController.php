<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    // Hiển thị danh sách nhà cung cấp
    public function index()
    {
        $suppliers = Supplier::all();
        return view('crud_suppliers.list', compact('suppliers'));
    }

    // Hiển thị form tạo mới nhà cung cấp
    public function create()
    {
        return view('crud_suppliers.create');
    }

    // Lưu nhà cung cấp mới
    public function post(Request $request)
    {
        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string|max:2000',
        ], [
            'logo.image' => 'Tệp tải lên phải là hình ảnh.',
            'logo.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg hoặc svg.',
            'logo.max' => 'Ảnh không được lớn hơn 2MB.',
            'name.required' => 'Tên nhà cung cấp là bắt buộc.',
            'name.max' => 'Tên nhà cung cấp không được vượt quá 255 ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'description.max' => 'Tiểu sử không được vượt quá 2000 ký tự.',
        ]);

        // Xử lý ảnh nếu có upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['logo'] = $filename;
        } else {
            $validated['logo'] = 'placeholder.png';
        }

        Supplier::create($validated);
        return redirect()->route('supplier.index')->with('success', 'Nhà cung cấp đã được tạo thành công.');
    }
}