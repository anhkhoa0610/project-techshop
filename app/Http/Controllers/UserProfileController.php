<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; // Thêm thư viện này để xử lý file thủ công

class UserProfileController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,jfif,webp|max:2048',
           
        ]);
         if (!str_starts_with($request->file('avatar')->getMimeType(), 'image/')) {
            return response()->json(['success' => false, 'message' => 'Chỉ chấp nhận file ảnh có đuôi jpeg,png,jpg,gif,jfif,webp!'], 400);
        }
        $user = Auth::user();

        // 1. Xử lý xóa ảnh cũ nếu có (trong thư mục public/images)
        if ($user->profile && $user->profile->avatar) {
            $oldPath = public_path('images/' . $user->profile->avatar);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        // 2. Lưu ảnh mới vào public/images
        $file = $request->file('avatar');
        // Tạo tên file độc nhất: timestamp_tên_gốc
        $filename = time() . '_' . $file->getClientOriginalName();
        // Di chuyển file vào public/images
        $file->move(public_path('images'), $filename);

        // 3. Cập nhật Database (chỉ lưu tên file)
        $user->profile()->updateOrCreate(
            ['user_id' => $user->user_id], // Kiểm tra lại khóa ngoại của bạn là id hay user_id
            ['avatar' => $filename]
        );

        // 4. TRẢ VỀ JSON (Bắt buộc để JS không bị lỗi)
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật ảnh đại diện thành công!',
            'avatar_url' => asset('images/' . $filename) // Trả về đường dẫn đầy đủ để hiển thị ngay
        ]);
    }

    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->profile && $user->profile->avatar) {
            // Xóa file trong public/images
            $path = public_path('images/' . $user->profile->avatar);
            if (File::exists($path)) {
                File::delete($path);
            }
            
            // Cập nhật DB về null
            $user->profile->update(['avatar' => null]);
            
            return back()->with('success', 'Đã xóa ảnh đại diện!');
        }

        return back()->with('error', 'Không tìm thấy ảnh đại diện!');
    }

    public function updateProfile(Request $request)
    {
        // ... (Giữ nguyên phần này như cũ)
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'birth' => 'nullable|date',
            'bio' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
        ]);

        $user->update([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'birth' => $validated['birth'],
        ]);

        $profileData = $request->only(['bio', 'website']);
        $user->profile()->updateOrCreate(
            ['user_id' => $user->user_id],
            $profileData
        );

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
}