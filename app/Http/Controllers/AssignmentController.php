<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CaseModel;
use App\Models\User;
use App\Models\Assignment;
class AssignmentController extends Controller
{

 public function index()
{
    // Fetch all assignments with related cases
    $assignments = Assignment::with('case')->get();

    // Define the available agents
    $agents = ['Agent1', 'Agent2', 'Agent3', 'Agent4'];

    // Fetch all cases for the dropdown
    $cases = CaseModel::all();

    return view('assign.list', compact('assignments', 'agents', 'cases'));
}

/**
 * Store a newly created assignment in the database.
 */
public function assign(Request $request)
{
    $request->validate([
        'case_ids' => 'required|array',
        'agents' => 'required|array',
    ]);

    foreach ($request->case_ids as $case_id) {
        foreach ($request->agents as $agent) {
            Assignment::updateOrCreate(
                ['case_id' => $case_id, 'agent' => $agent],
                ['user_id' => auth()->id()] // Set the user_id to the currently authenticated user
            );
        }
    }

    return redirect()->back()->with('success', 'Cases assigned successfully!');
}



public function calendar()
{
    // Fetch all cases where follow-up dates are assigned
    $cases = CaseModel::whereNotNull('follow_up_date')->get();

    // Map cases to events for the calendar
    $events = $cases->map(function ($case) {
        return [
            'id' => $case->id,                     
            'title' => "Case: {$case->cod_cliente}", 
            'start' => $case->follow_up_date,       
            'allDay' => true,                      
            'color' => '#164da0',                   
        ];
    })->toArray();

    // Pass events to the view
    return view('assign.calender', compact('events'));
}




    
}
