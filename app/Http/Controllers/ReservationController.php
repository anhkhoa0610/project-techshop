<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    // POST /reservations/release
    public function release(Request $request)
    {
        $temporaryOrderId = $request->input('temporary_order_id');
        if ($temporaryOrderId) {
            Reservation::releaseByTemporaryOrderId($temporaryOrderId);
            return response()->json(['success' => true, 'message' => 'Reservations released']);
        }

        // Nếu không có temporary_order_id, chấp nhận user_id để xóa reservation của user
        if ($request->filled('user_id')) {
            Reservation::where('user_id', $request->input('user_id'))->delete();
            return response()->json(['success' => true, 'message' => 'User reservations released']);
        }

        return response()->json(['success' => false, 'message' => 'No identifier provided'], 400);
    }
}
