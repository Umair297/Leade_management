@extends('home')

@section('content')
<div class="card">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
        <h1 style="font-size: 1.5rem; font-weight: 600; color: #333;">Assign Cases</h1>
        <button type="button" 
                class="btn-employee" 
                style="color: white; background-color: #008B98; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;"
                data-bs-toggle="modal" 
                data-bs-target="#createAssignmentModal">
            Assign Case
        </button>
    </div>
    <div class="card-datatable table-responsive text-nowrap">
        <table class="table" id="example">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Case Name</th>
                    <th>Assigned Agent</th>
                </tr>
            </thead>
           <tbody>
    @if ($assignments->isNotEmpty())
        @php $counter = 1; @endphp
        @foreach ($assignments->groupBy('case_id') as $caseAssignments)
            <tr>
                <td>{{ $counter++ }}</td> <!-- Incrementing Counter -->
                <td>{{ $caseAssignments->first()->case->nombre_cliente ?? 'N/A' }}</td>
                <td>
                    {{ $caseAssignments->pluck('agent')->join(', ') }}
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="3" class="text-center">No assignments found.</td>
        </tr>
    @endif
</tbody>

        </table>
    </div>
</div>

<!-- Create Assignment Modal -->
<div class="modal fade" id="createAssignmentModal" tabindex="-1" aria-labelledby="createAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
            <style>
    /* Style the dropdown button */
    .dropdown-toggle {
        width: 100%;
        border: none !important;
        text-align: left;
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dropdown-menu {
        width: 100%;
        border: none !important;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .dropdown-item {
        color: black;
    }

    .dropdown-item input {
        margin-right: 10px;
    }

    .dropdown-item:hover {
        background-color: #f0f0f0;
    }
</style>

<form action="{{ route('assignments.assign') }}" method="POST">
    @csrf

    <!-- Select Cases -->
    <div class="mb-3">
        <label for="case_ids" class="form-label" style="font-size: 15px;">Select Cases:</label>
        <div class="dropdown">
            <button class="btn btn-white dropdown-toggle" type="button" id="casesDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #333 !important;">
                Select Cases
            </button>
            <ul class="dropdown-menu" aria-labelledby="casesDropdown" style="width: 100%; max-height: 200px; overflow-y: auto;">
                @foreach ($cases as $case)
                <li>
                    <label class="dropdown-item">
                        <input type="checkbox" name="case_ids[]" value="{{ $case->id }}" class="form-check-input me-2 case-checkbox">
                        {{ $case->nombre_cliente }}
                    </label>
                </li>
                @endforeach
            </ul>
        </div>
        <input type="text" id="selectedCases" class="form-control mt-2" placeholder="Selected Cases" readonly>
    </div>

    <!-- Select Agents -->
    <div class="mb-3">
        <label for="agents" class="form-label" style="font-size: 15px;">Select Agents:</label>
        <div class="dropdown">
            <button class="btn btn-white dropdown-toggle" type="button" id="agentsDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #333 !important;">
                Select Agents
            </button>
            <ul class="dropdown-menu" aria-labelledby="agentsDropdown" style="width: 100%; max-height: 200px; overflow-y: auto;">
                @foreach ($agents as $agent)
                <li>
                    <label class="dropdown-item">
                        <input type="checkbox" name="agents[]" value="{{ $agent }}" class="form-check-input me-2 agent-checkbox">
                        {{ $agent }}
                    </label>
                </li>
                @endforeach
            </ul>
        </div>
        <input type="text" id="selectedAgents" class="form-control mt-2" placeholder="Selected Agents" readonly>
    </div>

    <!-- Submit Button -->
    <div class="d-grid mt-3">
        <button type="submit" class="btn btn-primary" style="background-color: #008B98; border-color: #008B98;">Assign</button>
    </div>
</form>



            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const caseCheckboxes = document.querySelectorAll('.case-checkbox');
        const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
        const selectedCases = document.getElementById('selected-cases');
        const selectedEmployees = document.getElementById('selected-employees');

        // Update selected cases display
        caseCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const selected = Array.from(caseCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.parentElement.textContent.trim());
                selectedCases.innerText = `Selected Cases: ${selected.join(', ') || 'None'}`;
            });
        });

        // Update selected employees display
        employeeCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const selected = Array.from(employeeCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.parentElement.textContent.trim());
                selectedEmployees.innerText = `Selected Employees: ${selected.join(', ') || 'None'}`;
            });
        });
    });
    
    
    document.addEventListener("DOMContentLoaded", function () {
    // Select Cases Logic
    const caseCheckboxes = document.querySelectorAll(".case-checkbox");
    const selectedCasesInput = document.getElementById("selectedCases");

    caseCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            let selectedCases = [];
            caseCheckboxes.forEach(cb => {
                if (cb.checked) {
                    selectedCases.push(cb.parentElement.textContent.trim());
                }
            });
            // Show selected with ellipsis if needed
            selectedCasesInput.value = formatWithEllipsis(selectedCases);
        });
    });

    // Select Agents Logic
    const agentCheckboxes = document.querySelectorAll(".agent-checkbox");
    const selectedAgentsInput = document.getElementById("selectedAgents");

    agentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener("change", function () {
            let selectedAgents = [];
            agentCheckboxes.forEach(cb => {
                if (cb.checked) {
                    selectedAgents.push(cb.parentElement.textContent.trim());
                }
            });
            selectedAgentsInput.value = formatWithEllipsis(selectedAgents);
        });
    });

    // Function to format values with ellipsis (e.g., "Agent 1, Agent 2...")
    function formatWithEllipsis(items, limit = 3) {
        if (items.length > limit) {
            return items.slice(0, limit).join(", ") + " ...";
        }
        return items.join(", ");
    }
});

</script>
@if (session('success'))
<script>
    swal({
        title: "Success!",
        text: "{{ session('success') }}",
        icon: "success",
        button: "OK",
    });
</script>
@endif
@endsection

<!-- Include Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBcDLPzCoHvcLAjs8n3lATJv1z7URQk93jEN0lpR9nSM1pIP" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuWLQjQjzHKAgCf2ZxKcbhs5jqKls8Z8Ncz5e8xAHPc2JOcI9C4OP9e5ab/78N3F" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
