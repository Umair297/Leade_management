<?php

namespace App\Http\Controllers;
use App\Models\Assignment;  // Import the Assignment model
use App\Models\CaseModel;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CaseController extends Controller
{
   public function index()
    {
        // Get current month's cases
        $currentMonthCases = CaseModel::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'DESC')
            ->get();

        // Get previous month's cases
        $previousMonthCases = CaseModel::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->orderBy('created_at', 'DESC')
            ->get();

        // Agents list (if required)
        $agents = ['Agent1', 'Agent2', 'Agent3', 'Agent4'];

        return view('cases.upload', compact('currentMonthCases', 'previousMonthCases', 'agents'));
    }

    
    public function show($id)
{
    $case = CaseModel::with('agents')->find($id);

    // You can check if the case has agents
    if ($case) {
        dd($case->agents);  // This will output the assigned agents
    }

    return view('your_view', compact('case'));
}

public function storeAssignment(Request $request)
{
     $request->validate([
        'case_id' => 'required',  // Ensure the case exists in the cases table
        
         // Ensure each agent_id exists in the users table
    ]);

   if ($request->has('agent') && is_array($request->agent)) {

        // Loop through the selected agents and assign them to the case
        foreach ($request->agent as $agentId) {
            // Ensure the agent_id is valid (exists in the users table)
            if ($agentId) {
                Assignment::create([
                    'case_id' => $request->case_id,
                    'agent' => $agentId,  // Store the agent's user_id in the assignment
                ]);
            }
        }
   }
        

    return redirect()->back()->with('success', 'Employees assigned successfully!');
}


 

public function upload(Request $request)
{
    // Get the current month
    $currentMonth = Carbon::now()->format('Y-m');

    // Check if a file has already been uploaded in the current month
    $existingUpload = CaseModel::whereDate('created_at', '>=', Carbon::now()->startOfMonth())
                               ->whereDate('created_at', '<=', Carbon::now()->endOfMonth())
                               ->exists();

    if ($existingUpload) {
        // If a file is already uploaded, return with a SweetAlert message
        return redirect()->back()->with('error', 'Excel file has already been uploaded this month.');
    }

    // Validation for the file input
    $validator = Validator::make($request->all(), [
        'file' => 'required|mimes:xlsx,xls',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator);
    }

    // Process the uploaded file
    $file = $request->file('file');
    $data = IOFactory::load($file->getPathname());
    $sheet = $data->getActiveSheet()->toArray(null, true, true, true);

    // Insert data into the database
    foreach ($sheet as $key => $row) {
        if ($key === 1) continue; // Skip header row

        // Validate and clean input data
        $items = is_numeric($row['A']) ? (int)$row['A'] : null;
        $saldo = is_numeric($row['F']) ? (float)$row['F'] : 0;
        $dias_mora = is_numeric($row['G']) ? (int)$row['G'] : 0;

        if ($items === null) {
            \Log::error("Invalid data in 'items' column at row $key: " . $row['A']);
            continue;
        }

        // Default status to "Sin Gestion" if not provided
        $status = $row['M'] ?? 'Completado';  // Get the status from the file, default to 'Completado' if not provided
        $validStatuses = ['Sin Gestion', 'Gestion en Proceso', 'Anulado', 'Completado'];

        // Check if the status is valid
        if (!in_array($status, $validStatuses)) {
            \Log::error("Invalid status at row $key: " . $status);
            // Set a default valid status, you can choose the most appropriate one here
            $status = 'Sin Gestion';
        }

        // Proceed to insert the data into the database
        CaseModel::create([
            'items' => $items,
            'prestamo' => $row['B'],
            'cod_cliente' => $row['C'],
            'identificacion' => $row['D'],
            'nombre_cliente' => $row['E'],
            'saldo' => $saldo,
            'dias_mora' => $dias_mora,
            'morosidad' => $row['H'],
            'lugar_empleo' => $row['I'] ?? null,
            'ocupacion' => $row['J'] ?? null,
            'prov' => $row['K'] ?? null,
            'direccion' => $row['L'] ?? null,
            'status' => $status,  // Use the validated status here
        ]);
    }

    return redirect()->back()->with('success', 'File uploaded and data inserted successfully!');
}


    public function create()
    {
        return view('cases.create');
    }
    public function updateStatus(Request $request, $id)
    {
        // Validate the incoming status
        $request->validate([
            'status' => 'required|in:Sin Gestion,Gestion en Proceso,Anulado,Completado',
        ]);
    
        // Find the case by ID and update the status
        $case = CaseModel::findOrFail($id);
        $case->status = $request->status;
        $case->save();
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Status updated successfully!');
    }
    
    public function followUp(Request $request, $id)
{
    // Validate the input date
    $request->validate([
        'follow_up_date' => 'required|date',
    ]);
    
    // Find the case and update the follow-up date
    $case = CaseModel::findOrFail($id);
    $case->follow_up_date = $request->follow_up_date;
    $case->save();
     // Add the notification (store in session for simplicity)
    $notifications = Session::get('notifications', []);
    $notifications[] = "Follow-up scheduled for Case: {$case->cod_cliente}";
    Session::put('notifications', $notifications);

    // Redirect back with a success message
    return redirect()->route('cases.index')->with('success', 'Follow-up date saved successfully!');
}

    public function edit($id)
    {
        $case = CaseModel::findOrFail($id);
        return view('cases.list', [
            'employe' => $case,
        ]);
    }

    public function update(Request $request, $id)
    {
        $case = CaseModel::findOrFail($id);
    
        $request->validate([
            'cod_cliente' => 'required|string',
            'nombre_cliente' => 'required|string',
            'dias_mora' => 'required|integer',
            'prov' => 'required|string',
            'status' => 'required|in:Sin Gestion,Gestion en Proceso,Anulado,Completado', // Validate status
        ]);
    
        $case->cod_cliente = $request->cod_cliente;
        $case->nombre_cliente = $request->nombre_cliente;
        $case->dias_mora = $request->dias_mora;
        $case->prov = $request->prov;
        $case->status = $request->status;
    
        $case->save();
    
        return redirect()->route('cases.index')->with('success', 'Case updated successfully.');
    }
    

    public function destroy($id)
    {
        $case = CaseModel::findOrFail($id);
        $case->delete();

        return redirect()->route('cases.index')->with('success', 'Case deleted successfully!');
    }
    
    public function bulkDelete(Request $request)
{
    $caseIds = $request->input('case_ids');

    if ($caseIds) {
        CaseModel::whereIn('id', $caseIds)->delete();
        return redirect()->route('cases.index')->with('success', 'Selected cases deleted successfully!');
    }

    return redirect()->route('cases.index')->with('error', 'No cases selected for deletion.');
}

  
    public function bulkDeletes(Request $request)
{
    $caseIds = $request->input('case_ids');

    if ($caseIds) {
        CaseModel::whereIn('id', $caseIds)->delete();
        return redirect()->route('cases.index')->with('success', 'Selected cases deleted successfully!');
    }

    return redirect()->route('cases.index')->with('error', 'No cases selected for deletion.');
}

    
    public function getFollowUpNotifications()
{
    $cases = CaseModel::whereNotNull('follow_up_date')
                      ->whereDate('follow_up_date', '<=', now()) // Only cases due or past follow-up
                      ->get([ 'id','cod_cliente']);

      return response()->json($cases);
}
public function clearNotification($id)
{
    $case = CaseModel::findOrFail($id);
    $case->follow_up_date = null; // Clear the follow-up date
    $case->save();

    return response()->json(['success' => true]);
}

public function clearAllNotifications()
{
    CaseModel::whereNotNull('follow_up_date')->update(['follow_up_date' => null]);
    return response()->json(['success' => true]);
}

}