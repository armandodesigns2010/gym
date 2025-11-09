<?php

namespace App\Http\Controllers;

use App\Events\ClassCanceled;
use App\Models\ClassType;
use App\Models\ScheduledClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduledClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scheduledClasses = ScheduledClass::whereBelongsTo(Auth::user(), 'instructor')->upcoming()->oldest('date_time')->get();

        return view('instructor.upcoming', compact('scheduledClasses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classTypes = ClassType::all();

        return view('instructor.schedule', compact('classTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $date_time = $request->input('date') . ' ' . $request->input('time');

        $request->merge([
            'date_time' => $date_time,
            'instructor_id' => Auth::id(),
        ]);

        $validated = $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'class_type_id' => 'required|exists:class_types,id',
            'date_time' => 'required|unique:scheduled_classes,date_time|after:now',
        ]);

        ScheduledClass::create($validated);

        return to_route('schedule.index')->with('success', 'Class scheduled successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduledClass $schedule)
    {
//        if ($schedule->instructor->isNot(Auth::user())) {
//            abort(403);
//        }

        if (Auth::user()->cannot('delete', $schedule)) {
            abort(403);
        }

        ClassCanceled::dispatch($schedule);

        $schedule->members()->detach();

        $schedule->delete();

        return to_route('schedule.index')->with('success', 'Class deleted successfully.');
    }
}
