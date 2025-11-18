<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,jfif,webp|max:2048',
        ]);

        $user = Auth::user();

        // Xóa ảnh cũ
        if ($user->profile && $user->profile->avatar) {
            Storage::disk('public')->delete($user->profile->avatar);
        }

        // Lưu file vào storage/app/public/avatars
        $path = $request->file('avatar')->store('avatars', 'public');

        // Cập nhật DB
        $user->profile()->updateOrCreate(
            ['user_id' => $user->user_id],
            ['avatar' => $path]
        );

        // Trả về JSON để JS cập nhật ảnh
        // return response()->json([
        //     'success' => true,
        //     'avatar_url' => asset('storage/' . $path)
        // ]);
        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->profile && $user->profile->avatar) {
            Storage::disk('public')->delete($user->profile->avatar);
            $user->profile->update(['avatar' => null]);
            return back()->with('success', 'Đã xóa ảnh đại diện!');
        }

        return back()->with('error', 'Không tìm thấy ảnh đại diện!');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'birth' => 'nullable|date',
            'bio' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
        ]);

        $user->update($validated);

        $profileData = $request->only(['bio', 'website']);
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }
}