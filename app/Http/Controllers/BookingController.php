<?php

namespace App\Http\Controllers;

use App\Models\ScheduledClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create()
    {
        $scheduledClasses = ScheduledClass::upcoming()
            ->with('classType', 'instructor')
            ->notBooked()
            ->oldest()->get();

        return view('member.book', compact('scheduledClasses'));
    }

    public function store(Request $request)
    {
        Auth::user()->bookings()->attach($request->scheduled_class_id);

        return to_route('booking.index')->with('success', 'Class booked successfully.');
    }

    public function index()
    {
        $bookings = Auth::user()->bookings()->upcoming()->get();

        return view('member.upcoming', compact('bookings'));
    }

    public function destroy(int $id)
    {
        Auth::user()->bookings()->detach($id);

        return to_route('booking.index')->with('success', 'Class cancelled successfully.');
    }
}
