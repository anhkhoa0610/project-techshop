<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');

        $users = User::when($search, function ($query) use ($search) {
            return $query->where('full_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('address', 'like', "%$search%");
        })
            ->when($roleFilter, function ($query) use ($roleFilter) {
                return $query->where('role', $roleFilter);
            })
            ->orderBy('user_id', 'desc')
            ->latest()
            ->paginate(10);



        return view('crud_user.list', compact('users'));
    }

    /**
     * Hiển thị form tạo người dùng mới.
     */
    public function create()
    {
        return view('crud_user.create');
    }

    /**
     * Lưu người dùng mới.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:100',
                'email' => 'required|string|email|max:100|unique:users',
                'phone' => 'nullable|string|max:10',
                'password' => 'required|string|min:6|confirmed',
                'address' => 'nullable|string|max:255',
                'role' => 'required|in:User,Admin',
                'birth' => 'required|date|before_or_equal:today|after:1900-01-01',
            ], [
                'full_name.required' => 'Vui lòng nhập họ tên.',
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không hợp lệ.',
                'email.unique' => 'Email này đã được đăng ký.',
                'phone.max' => 'Số điện thoại quá dài.',
                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
                'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
                'role.in' => 'Vai trò không hợp lệ.',
                'birth.required' => 'Vui lòng nhập ngày sinh.',
                'birth.before_or_equal' => 'Ngày sinh không thể ở tương lai.',
                'birth.after' => 'Ngày sinh không hợp lệ (phải sau 1900).',
            ]);

            // Tự động gán true nếu email có đuôi @mail.tdc.edu.vn
            $validated['is_tdc_student'] = str_ends_with($request->email, '@mail.tdc.edu.vn') ? 'true' : 'false';
            $validated['password'] = Hash::make($validated['password']);

            User::create($validated);

            return redirect()->route('users.index')
                ->with('success', 'Thêm người dùng thành công!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput()
                ->with('error', 'Có lỗi xảy ra khi nhập dữ liệu.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form chỉnh sửa người dùng.
     */
    public function edit(User $user)
    {
        return view('crud_user.edit', compact('user'));
    }
    public function show(User $user)
    {
        return view('crud_user.read', compact('user'));
    }

    /**
     * Cập nhật người dùng.
     */
    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:100|unique:users,email,' . $user->user_id . ',user_id',
                'phone' => 'nullable|string|max:10',
                'password' => 'nullable|string|min:6|confirmed',
                'address' => 'nullable|string|max:255',
                'role' => 'required|in:User,Admin',
                'birth' => 'required|date|before_or_equal:today|after:1900-01-01',
            ], [
                'full_name.required' => 'Vui lòng nhập họ tên.',
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không hợp lệ.',
                'email.unique' => 'Email này đã tồn tại.',
                'phone.max' => 'Số điện thoại quá dài.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
                'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
                'role.in' => 'Vai trò không hợp lệ.',
                'birth.required' => 'Vui lòng nhập ngày sinh.',
                'birth.before_or_equal' => 'Ngày sinh không thể ở tương lai.',
                'birth.after' => 'Ngày sinh không hợp lệ (phải sau 1900).',
            ]);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // Kiểm tra đuôi email để xác định SV TDC
            $validated['is_tdc_student'] = str_ends_with($request->email, '@mail.tdc.edu.vn') ? 'true' : 'false';

            $user->update($validated);

            return redirect()->route('users.index')
                ->with('success', 'Cập nhật thông tin người dùng thành công!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }

    /**
     * Xóa người dùng.
     */

    public function destroy($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User đã được xóa thành công.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa User: ' . $e->getMessage()
            ], 500);
        }
    }

    //ui user profile
    public function showProfile()
    {
        return view('user.profile');
    }
}
