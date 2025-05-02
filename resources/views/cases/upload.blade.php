@extends('home')

@section('content')
<div class="card">
   
<div class="card-header border-bottom d-flex justify-content-between align-items-center">
    <h1 style="font-size: 1.5rem; font-weight: 600; color: #333;">Cases</h1>

   
        <div class="save-change">
            <button type="button" 
                    class="btn-employee" 
                    style="color: white; background-color: #008B98; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;"
                    data-bs-toggle="modal" 
                    data-bs-target="#createEmployeeModal"
                    onmouseover="this.style.background='#008B98'" 
                    onmouseout="this.style.background='#008B98'">
                Create Case
            </button>
            <button id="bulkDeleteButton" 
                    class="btn-employee" 
                    style="color: white; background-color: red; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;"
                    onclick="confirmBulkDelete()">
                Delete Selected
            </button>
        </div>
  
</div>
  <div class="card-datatable table-responsive">
    <form id="bulkDeleteForm" action="{{ route('cases.bulkDelete') }}" method="POST">
        @csrf
        <table id="example" class="table table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Id</th>
                    <th>Cod Cliente</th>
                    <th>Nombre Cliente</th>
                    <th>Dias Mora</th>
                    <th>Agents</th>
                    <th>Follow Up</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($currentMonthCases->isNotEmpty())
                    @foreach ($currentMonthCases  as $index => $case)
                        <tr>
                            <td><input type="checkbox" name="case_ids[]" value="{{ $case->id }}"></td>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $case->cod_cliente }}</td>
                            <td>{{ $case->nombre_cliente }}</td>
                            <td>{{ $case->dias_mora }}</td>
                            <td>
                                <i class="menu-icon tf-icons ti ti-users" data-bs-toggle="modal" data-bs-target="#assignedEmployeeModal{{ $case->id }}"></i>
                            </td>
                            <td>{{ $case->follow_up_date ? \Carbon\Carbon::parse($case->follow_up_date)->format('Y-m-d') : 'N/A' }}</td>
                            <td>
                                <span 
                                    class="badge" 
                                    style="background-color: #D5D5D5; 
                                           color: 
                                           @switch($case->status)
                                               @case('Sin Gestion') red @break
                                               @case('Gestion en Proceso') orange @break
                                               @case('Anulado') black @break
                                               @case('Completado') green @break
                                               @default black
                                           @endswitch;">
                                    {{ $case->status }}
                                </span>
                            </td>
                            <td>
                <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <!-- Only show Edit and Delete if the user is an admin -->
                                   
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editEmployeModal{{ $case->id }}">
                                            <i class="ti ti-pencil me-1"></i> Edit
                                        </a>
                                        <a class="dropdown-item" href="#" onclick="confirmDelete({{ $case->id }})">
                                            <i class="ti ti-trash me-1"></i> Delete
                                        </a>
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewEmployeModal{{ $case->id }}">
                                             <i class="ti ti-eye me-1"></i> View
                                        </a>
                                        <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#assignEmployeeModal{{$case->id}}">
                                                <i class="ti ti-user-plus me-1"></i> Assign
                                                    </a>
                                   
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $case->id }}">
                                        <i class="ti ti-pencil me-1"></i> Update Status
                                    </a>
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#followUpModal{{ $case->id }}">
                                        <i class="ti ti-pencil me-1"></i> Follow up
                                    </a>
                                    <form id="delete-employe-form-{{ $case->id }}" action="{{ route('cases.destroy', $case->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                   
                                </div>
                            </div>
                        </td>
                    </tr>



<div class="modal fade" id="assignedEmployeeModal{{ $case->id }}" tabindex="-1" aria-labelledby="assignedEmployeeModalLabel{{ $case->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignedEmployeeModalLabel{{ $case->id }}">Assigned Agents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style=" border: none; padding: 10px;"></button>
            </div>
            <div class="modal-body">
                <h6 style="font-weight: bold; margin-bottom: 10px;">Agent Assigned to Case: {{ $case->nombre_cliente }}</h6>
                <ul>
            @php
            
           $assignment = \App\Models\Assignment::where('case_id', $case->id)->get();
                
            @endphp
            
            @foreach($assignment as $agent)
                <li>{{ $agent->agent }}</li>  <!-- Display each agent's name -->
            @endforeach
        </ul>
            </div>
            <div class="modal-footer" style="border-top: 2px solid #ddd;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #008B98; border-color: #008B98;">Close</button>
            </div>
        </div>
    </div>
</div>


    <div class="modal fade" id="followUpModal{{ $case->id }}" tabindex="-1" aria-labelledby="followUpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="followUpModalLabel">Set Follow-Up Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cases.followUp', $case->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Date Picker Input -->
                    <div class="mb-3">
                        <label for="follow_up_date" class="form-label">Follow-Up Date</label>
                        <input type="date" name="follow_up_date" id="follow_up_date" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" style="background-color: #008B98 !important;">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #008B98 !important;">Save Date</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<!--  view model -->
<div class="modal fade" id="viewEmployeModal{{ $case->id }}" tabindex="-1" aria-labelledby="viewEmployeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEmployeModalLabel">View Lead - {{ $case->nombre_cliente }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Items -->
                    <div class="col-md-6 mb-3">
                        <label for="items" class="form-label">Items</label>
                        <input type="number" id="items" class="form-control" value="{{ $case->items }}" readonly>
                    </div>

                    <!-- Prestamo -->
                    <div class="col-md-6 mb-3">
                        <label for="prestamo" class="form-label">Préstamo</label>
                        <input type="text" id="prestamo" class="form-control" value="{{ $case->prestamo }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Cod Cliente -->
                    <div class="col-md-6 mb-3">
                        <label for="cod_cliente" class="form-label">Cod Cliente</label>
                        <input type="text" id="cod_cliente" class="form-control" value="{{ $case->cod_cliente }}" readonly>
                    </div>

                    <!-- Identificacion -->
                    <div class="col-md-6 mb-3">
                        <label for="identificacion" class="form-label">Identificación</label>
                        <input type="text" id="identificacion" class="form-control" value="{{ $case->identificacion }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Nombre Cliente -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre_cliente" class="form-label">Nombre Cliente</label>
                        <input type="text" id="nombre_cliente" class="form-control" value="{{ $case->nombre_cliente }}" readonly>
                    </div>

                    <!-- Saldo -->
                    <div class="col-md-6 mb-3">
                        <label for="saldo" class="form-label">Saldo</label>
                        <input type="number" step="0.01" id="saldo" class="form-control" value="{{ $case->saldo }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Días Mora -->
                    <div class="col-md-6 mb-3">
                        <label for="dias_mora" class="form-label">Días Mora</label>
                        <input type="number" id="dias_mora" class="form-control" value="{{ $case->dias_mora }}" readonly>
                    </div>

                    <!-- Morosidad -->
                    <div class="col-md-6 mb-3">
                        <label for="morosidad" class="form-label">Morosidad</label>
                        <input type="text" id="morosidad" class="form-control" value="{{ $case->morosidad }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Lugar Empleo -->
                    <div class="col-md-6 mb-3">
                        <label for="lugar_empleo" class="form-label">Lugar de Empleo</label>
                        <input type="text" id="lugar_empleo" class="form-control" value="{{ $case->lugar_empleo }}" readonly>
                    </div>

                    <!-- Ocupacion -->
                    <div class="col-md-6 mb-3">
                        <label for="ocupacion" class="form-label">Ocupación</label>
                        <input type="text" id="ocupacion" class="form-control" value="{{ $case->ocupacion }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Provincia -->
                    <div class="col-md-6 mb-3">
                        <label for="prov" class="form-label">Provincia</label>
                        <input type="text" id="prov" class="form-control" value="{{ $case->prov }}" readonly>
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-6 mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea id="direccion" class="form-control" rows="3" readonly>{{ $case->direccion }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                         <input type="text" id="status" class="form-control" value="{{ $case->status }}" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
        <label for="follow_up_date" class="form-label">Follow-Up Date</label>
        <input type="date" id="follow_up_date" class="form-control" value="{{ $case->follow_up_date }}" readonly>
    </div>
            </div>
        </div>
    </div>
</div>


            <!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal{{ $case->id }}" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cases.updateStatus', $case->id) }}" method="POST" id="updateStatusForm{{ $case->id }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Sin Gestion" {{ $case->status == 'Sin Gestion' ? 'selected' : '' }}>Sin Gestion</option>
                            <option value="Gestion en Proceso" {{ $case->status == 'Gestion en Proceso' ? 'selected' : '' }}>Gestion en Proceso</option>
                            <option value="Anulado" {{ $case->status == 'Anulado' ? 'selected' : '' }}>Anulado</option>
                            <option value="Completado" {{ $case->status == 'Completado' ? 'selected' : '' }}>Completado</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" style="background-color: #008B98 !important;">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #008B98 !important;">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Create Assignment Modal -->
<div class="modal fade" id="assignEmployeeModal{{ $case->id }}" tabindex="-1" aria-labelledby="assignEmployeeModalLabel{{ $case->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="assignEmployeeModalLabel{{ $case->id }}">Assign Agents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form method="POST" action="{{ route('assignments.store') }}">
                    @csrf
                    <!-- Hidden Case ID -->
                    <input type="hidden" name="case_id" value="{{ $case->id }}">

                    <!-- Select Agents -->
                    <div class="mb-3">
                        <label for="selectAgents" class="form-label fw-semibold">Select Agents:</label>
                        
                        <!-- Dropdown as a Field -->
                        <div class="dropdown">
                            <button class="form-control dropdown-toggle text-start" type="button" id="selectAgentsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Select Agents
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu w-100" aria-labelledby="selectAgentsDropdown" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($agents as $index => $agent)
                                    <li>
                                        <div class="form-check ms-3">
                                            <input 
                                                type="checkbox" 
                                                name="agent[]" 
                                                value="{{ $agent->id ?? $agent }}" 
                                                id="agent{{ $case->id }}_{{ $index }}" 
                                                class="form-check-input">
                                            <label for="agent{{ $case->id }}_{{ $index }}" class="form-check-label">
                                                {{ $agent->name ?? $agent }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #008B98 !important;">Assign Agents</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


            <!-- Edit Employee Modal -->
            <div class="modal fade" id="editEmployeModal{{ $case->id }}" tabindex="-1" aria-labelledby="editEmployeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeModalLabel">Edit Lead - {{ $case->nombre_cliente }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cases.update', $case->id) }}" method="POST" id="updateForm{{ $case->id }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Items -->
                        <div class="col-md-6 mb-3">
                            <label for="items" class="form-label">Items</label>
                            <input type="number" name="items" id="items" class="form-control" value="{{ $case->items }}" required>
                        </div>

                        <!-- Prestamo -->
                        <div class="col-md-6 mb-3">
                            <label for="prestamo" class="form-label">Préstamo</label>
                            <input type="text" name="prestamo" id="prestamo" class="form-control" value="{{ $case->prestamo }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Cod Cliente -->
                        <div class="col-md-6 mb-3">
                            <label for="cod_cliente" class="form-label">Cod Cliente</label>
                            <input type="text" name="cod_cliente" id="cod_cliente" class="form-control" value="{{ $case->cod_cliente }}" required>
                        </div>

                        <!-- Identificacion -->
                        <div class="col-md-6 mb-3">
                            <label for="identificacion" class="form-label">Identificación</label>
                            <input type="text" name="identificacion" id="identificacion" class="form-control" value="{{ $case->identificacion }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nombre Cliente -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre_cliente" class="form-label">Nombre Cliente</label>
                            <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" value="{{ $case->nombre_cliente }}" required>
                        </div>

                        <!-- Saldo -->
                        <div class="col-md-6 mb-3">
                            <label for="saldo" class="form-label">Saldo</label>
                            <input type="number" step="0.01" name="saldo" id="saldo" class="form-control" value="{{ $case->saldo }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Días Mora -->
                        <div class="col-md-6 mb-3">
                            <label for="dias_mora" class="form-label">Días Mora</label>
                            <input type="number" name="dias_mora" id="dias_mora" class="form-control" value="{{ $case->dias_mora }}" required>
                        </div>

                        <!-- Morosidad -->
                        <div class="col-md-6 mb-3">
                            <label for="morosidad" class="form-label">Morosidad</label>
                            <input type="text" name="morosidad" id="morosidad" class="form-control" value="{{ $case->morosidad }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Lugar Empleo -->
                        <div class="col-md-6 mb-3">
                            <label for="lugar_empleo" class="form-label">Lugar de Empleo</label>
                            <input type="text" name="lugar_empleo" id="lugar_empleo" class="form-control" value="{{ $case->lugar_empleo }}">
                        </div>

                        <!-- Ocupacion -->
                        <div class="col-md-6 mb-3">
                            <label for="ocupacion" class="form-label">Ocupación</label>
                            <input type="text" name="ocupacion" id="ocupacion" class="form-control" value="{{ $case->ocupacion }}">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Provincia -->
                        <div class="col-md-6 mb-3">
                            <label for="prov" class="form-label">Provincia</label>
                            <input type="text" name="prov" id="prov" class="form-control" value="{{ $case->prov }}">
                        </div>

                        <!-- Dirección -->
                        <div class="col-md-6 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea name="direccion" id="direccion" class="form-control" rows="3">{{ $case->direccion }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Sin Gestion" {{ $case->status == 'Sin Gestion' ? 'selected' : '' }}>Sin Gestion</option>
                                <option value="Gestion en Proceso" {{ $case->status == 'Gestion en Proceso' ? 'selected' : '' }}>Gestion en Proceso</option>
                                <option value="Anulado" {{ $case->status == 'Anulado' ? 'selected' : '' }}>Anulado</option>
                                <option value="Completado" {{ $case->status == 'Completado' ? 'selected' : '' }}>Completado</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" style="background-color: #008B98 !important;">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #008B98 !important;">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        @endforeach
    @else
        <tr>
            <td colspan="7" class="text-center">No cases found.</td>
        </tr>
    @endif
</tbody>
</table>
</form>
</div>

</div>
<div class="modal fade" id="createEmployeeModal" tabindex="-1" aria-labelledby="createEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEmployeeModalLabel">Upload Excel File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form enctype="multipart/form-data" action="{{ route('cases.upload') }}" method="post">
                    @csrf

                    <!-- File Upload Field -->
                    <div class="mb-3">
                        <label for="file" class="form-label">Select Excel File</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                    </div>

    
                    <!-- Submit Button -->
                    <div class="d-grid mt-3" style="width: 20%;">
        <button type="submit" class="btn btn-primary" style="width: 100%; text-align: left; background-color: #008B98 !important;" >Submit</button>
    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--this is second table for previous mounth tables-->

<div class="card-datatable table-responsive mt-5">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 1.2rem; font-weight: 600; color: #008B98;">Previous Month's Cases</h2>
        
        <button id="bulkDeleteButton" 
                    class="btn-employee" 
                    style="color: white; background-color: red; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;"
                    onclick="confirmBulkDeletes()">
                Delete Selected
            </button>
    </div>
    
    <form id="bulkDeleteForms" action="{{ route('cases.bulkDelete') }}" method="POST">
        @csrf
    <table id='example1' class="table table-bordered table-hover text-center mt-3">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAllPrevious"></th>
                <th>Id</th>
                <th>Cod Cliente</th>
                <th>Nombre Cliente</th>
                <th>Dias Mora</th>
                <th>Agents</th>
                <th>Follow Up</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($previousMonthCases->isNotEmpty())
                @foreach ($previousMonthCases as $index => $case)
                   <tr>
                    
                        <td><input type="checkbox" name="case_ids[]" value="{{ $case->id }}"></td>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $case->cod_cliente }}</td>
                        <td>{{ $case->nombre_cliente }}</td>
                        <td>{{ $case->dias_mora }}</td>
                        <td>
                            <i class="menu-icon tf-icons ti ti-users" data-bs-toggle="modal" data-bs-target="#assignedEmployeeModal{{ $case->id }}"></i>
                        </td>
                        <td>{{ $case->follow_up_date ? \Carbon\Carbon::parse($case->follow_up_date)->format('Y-m-d') : 'N/A' }}</td>
                        <td>
                            <span 
                                class="badge" 
                                style="background-color: #D5D5D5; 
                                       color: 
                                       @switch($case->status)
                                           @case('Sin Gestion') red @break
                                           @case('Gestion en Proceso') orange @break
                                           @case('Anulado') black @break
                                           @case('Completado') green @break
                                           @default black
                                       @endswitch;">
                                {{ $case->status }}
                            </span>
                        </td>
                        
                        <td>
            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editEmployeModal{{ $case->id }}">
                                        <i class="ti ti-pencil me-1"></i> Edit
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="confirmDelete({{ $case->id }})">
                                        <i class="ti ti-trash me-1"></i> Delete
                                    </a>
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#viewEmployeModal{{ $case->id }}">
                                         <i class="ti ti-eye me-1"></i> View
                                    </a>
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#assignEmployeeModal{{$case->id}}">
                                            <i class="ti ti-user-plus me-1"></i> Assign
                                                </a>
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $case->id }}">
                                        <i class="ti ti-pencil me-1"></i> Update Status
                                    </a>
                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#followUpModal{{ $case->id }}">
                                        <i class="ti ti-pencil me-1"></i> Follow up
                                    </a>
                                    <form id="delete-employe-form-{{ $case->id }}" action="{{ route('cases.destroy', $case->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
             <div class="modal fade" id="assignedEmployeeModal{{ $case->id }}" tabindex="-1" aria-labelledby="assignedEmployeeModalLabel{{ $case->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignedEmployeeModalLabel{{ $case->id }}">Assigned Agents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style=" border: none; padding: 10px;"></button>
            </div>
            <div class="modal-body">
    <h6 style="font-weight: bold; margin-bottom: 10px;">Agent Assigned to Case: {{ $case->nombre_cliente }}</h6>

           
       <ul>
            @php
            
           $assignment = \App\Models\Assignment::where('case_id', $case->id)->get();
                
            @endphp
            
            @foreach($assignment as $agent)
                <li>{{ $agent->agent }}</li>  <!-- Display each agent's name -->
            @endforeach
        </ul>

</div>


            <div class="modal-footer" style="border-top: 2px solid #ddd;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #008B98; border-color: #008B98;">Close</button>
            </div>
        </div>
    </div>
</div>


    <div class="modal fade" id="followUpModal{{ $case->id }}" tabindex="-1" aria-labelledby="followUpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="followUpModalLabel">Set Follow-Up Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cases.followUp', $case->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Date Picker Input -->
                    <div class="mb-3">
                        <label for="follow_up_date" class="form-label">Follow-Up Date</label>
                        <input type="date" name="follow_up_date" id="follow_up_date" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" style="background-color: #008B98 !important;">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #008B98 !important;">Save Date</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<!--  view model -->
<div class="modal fade" id="viewEmployeModal{{ $case->id }}" tabindex="-1" aria-labelledby="viewEmployeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEmployeModalLabel">View Lead - {{ $case->nombre_cliente }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Items -->
                    <div class="col-md-6 mb-3">
                        <label for="items" class="form-label">Items</label>
                        <input type="number" id="items" class="form-control" value="{{ $case->items }}" readonly>
                    </div>

                    <!-- Prestamo -->
                    <div class="col-md-6 mb-3">
                        <label for="prestamo" class="form-label">Préstamo</label>
                        <input type="text" id="prestamo" class="form-control" value="{{ $case->prestamo }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Cod Cliente -->
                    <div class="col-md-6 mb-3">
                        <label for="cod_cliente" class="form-label">Cod Cliente</label>
                        <input type="text" id="cod_cliente" class="form-control" value="{{ $case->cod_cliente }}" readonly>
                    </div>

                    <!-- Identificacion -->
                    <div class="col-md-6 mb-3">
                        <label for="identificacion" class="form-label">Identificación</label>
                        <input type="text" id="identificacion" class="form-control" value="{{ $case->identificacion }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Nombre Cliente -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre_cliente" class="form-label">Nombre Cliente</label>
                        <input type="text" id="nombre_cliente" class="form-control" value="{{ $case->nombre_cliente }}" readonly>
                    </div>

                    <!-- Saldo -->
                    <div class="col-md-6 mb-3">
                        <label for="saldo" class="form-label">Saldo</label>
                        <input type="number" step="0.01" id="saldo" class="form-control" value="{{ $case->saldo }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Días Mora -->
                    <div class="col-md-6 mb-3">
                        <label for="dias_mora" class="form-label">Días Mora</label>
                        <input type="number" id="dias_mora" class="form-control" value="{{ $case->dias_mora }}" readonly>
                    </div>

                    <!-- Morosidad -->
                    <div class="col-md-6 mb-3">
                        <label for="morosidad" class="form-label">Morosidad</label>
                        <input type="text" id="morosidad" class="form-control" value="{{ $case->morosidad }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Lugar Empleo -->
                    <div class="col-md-6 mb-3">
                        <label for="lugar_empleo" class="form-label">Lugar de Empleo</label>
                        <input type="text" id="lugar_empleo" class="form-control" value="{{ $case->lugar_empleo }}" readonly>
                    </div>

                    <!-- Ocupacion -->
                    <div class="col-md-6 mb-3">
                        <label for="ocupacion" class="form-label">Ocupación</label>
                        <input type="text" id="ocupacion" class="form-control" value="{{ $case->ocupacion }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <!-- Provincia -->
                    <div class="col-md-6 mb-3">
                        <label for="prov" class="form-label">Provincia</label>
                        <input type="text" id="prov" class="form-control" value="{{ $case->prov }}" readonly>
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-6 mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea id="direccion" class="form-control" rows="3" readonly>{{ $case->direccion }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                         <input type="text" id="status" class="form-control" value="{{ $case->status }}" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
        <label for="follow_up_date" class="form-label">Follow-Up Date</label>
        <input type="date" id="follow_up_date" class="form-control" value="{{ $case->follow_up_date }}" readonly>
    </div>
            </div>
        </div>
    </div>
</div>


            <!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal{{ $case->id }}" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cases.updateStatus', $case->id) }}" method="POST" id="updateStatusForm{{ $case->id }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Sin Gestion" {{ $case->status == 'Sin Gestion' ? 'selected' : '' }}>Sin Gestion</option>
                            <option value="Gestion en Proceso" {{ $case->status == 'Gestion en Proceso' ? 'selected' : '' }}>Gestion en Proceso</option>
                            <option value="Anulado" {{ $case->status == 'Anulado' ? 'selected' : '' }}>Anulado</option>
                            <option value="Completado" {{ $case->status == 'Completado' ? 'selected' : '' }}>Completado</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" style="background-color: #008B98 !important;">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #008B98 !important;">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Create Assignment Modal -->
<div class="modal fade" id="assignEmployeeModal{{ $case->id }}" tabindex="-1" aria-labelledby="assignEmployeeModalLabel{{ $case->id }}" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="assignEmployeeModalLabel{{ $case->id }}">Assign Agents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form method="POST" action="{{ route('assignments.store') }}">
                    @csrf
                    <!-- Hidden Case ID -->
                    <input type="hidden" name="case_id" value="{{ $case->id }}">

                    <!-- Select Agents -->
                    <div class="mb-3">
                        <label for="selectAgents" class="form-label fw-semibold">Select Agents:</label>
                        
                        <!-- Dropdown as a Field -->
                        <div class="dropdown">
                            <button class="form-control dropdown-toggle text-start" type="button" id="selectAgentsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Select Agents
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu w-100" aria-labelledby="selectAgentsDropdown" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($agents as $index => $agent)
                                    <li>
                                        <div class="form-check ms-3">
                                            <input 
                                                type="checkbox" 
                                                name="agent[]" 
                                                value="{{ $agent->id ?? $agent }}" 
                                                id="agent{{ $case->id }}_{{ $index }}" 
                                                class="form-check-input">
                                            <label for="agent{{ $case->id }}_{{ $index }}" class="form-check-label">
                                                {{ $agent->name ?? $agent }}
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #008B98 !important;">Assign Agents</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




            <!-- Edit Employee Modal -->
            <div class="modal fade" id="editEmployeModal{{ $case->id }}" tabindex="-1" aria-labelledby="editEmployeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeModalLabel">Edit Lead - {{ $case->nombre_cliente }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('cases.update', $case->id) }}" method="POST" id="updateForm{{ $case->id }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Items -->
                        <div class="col-md-6 mb-3">
                            <label for="items" class="form-label">Items</label>
                            <input type="number" name="items" id="items" class="form-control" value="{{ $case->items }}" required>
                        </div>

                        <!-- Prestamo -->
                        <div class="col-md-6 mb-3">
                            <label for="prestamo" class="form-label">Préstamo</label>
                            <input type="text" name="prestamo" id="prestamo" class="form-control" value="{{ $case->prestamo }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Cod Cliente -->
                        <div class="col-md-6 mb-3">
                            <label for="cod_cliente" class="form-label">Cod Cliente</label>
                            <input type="text" name="cod_cliente" id="cod_cliente" class="form-control" value="{{ $case->cod_cliente }}" required>
                        </div>

                        <!-- Identificacion -->
                        <div class="col-md-6 mb-3">
                            <label for="identificacion" class="form-label">Identificación</label>
                            <input type="text" name="identificacion" id="identificacion" class="form-control" value="{{ $case->identificacion }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nombre Cliente -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre_cliente" class="form-label">Nombre Cliente</label>
                            <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" value="{{ $case->nombre_cliente }}" required>
                        </div>

                        <!-- Saldo -->
                        <div class="col-md-6 mb-3">
                            <label for="saldo" class="form-label">Saldo</label>
                            <input type="number" step="0.01" name="saldo" id="saldo" class="form-control" value="{{ $case->saldo }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Días Mora -->
                        <div class="col-md-6 mb-3">
                            <label for="dias_mora" class="form-label">Días Mora</label>
                            <input type="number" name="dias_mora" id="dias_mora" class="form-control" value="{{ $case->dias_mora }}" required>
                        </div>

                        <!-- Morosidad -->
                        <div class="col-md-6 mb-3">
                            <label for="morosidad" class="form-label">Morosidad</label>
                            <input type="text" name="morosidad" id="morosidad" class="form-control" value="{{ $case->morosidad }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Lugar Empleo -->
                        <div class="col-md-6 mb-3">
                            <label for="lugar_empleo" class="form-label">Lugar de Empleo</label>
                            <input type="text" name="lugar_empleo" id="lugar_empleo" class="form-control" value="{{ $case->lugar_empleo }}">
                        </div>

                        <!-- Ocupacion -->
                        <div class="col-md-6 mb-3">
                            <label for="ocupacion" class="form-label">Ocupación</label>
                            <input type="text" name="ocupacion" id="ocupacion" class="form-control" value="{{ $case->ocupacion }}">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Provincia -->
                        <div class="col-md-6 mb-3">
                            <label for="prov" class="form-label">Provincia</label>
                            <input type="text" name="prov" id="prov" class="form-control" value="{{ $case->prov }}">
                        </div>

                        <!-- Dirección -->
                        <div class="col-md-6 mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <textarea name="direccion" id="direccion" class="form-control" rows="3">{{ $case->direccion }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Sin Gestion" {{ $case->status == 'Sin Gestion' ? 'selected' : '' }}>Sin Gestion</option>
                                <option value="Gestion en Proceso" {{ $case->status == 'Gestion en Proceso' ? 'selected' : '' }}>Gestion en Proceso</option>
                                <option value="Anulado" {{ $case->status == 'Anulado' ? 'selected' : '' }}>Anulado</option>
                                <option value="Completado" {{ $case->status == 'Completado' ? 'selected' : '' }}>Completado</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" style="background-color: #008B98 !important;">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color: #008B98 !important;">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        @endforeach
    @else
        <tr>
            <td colspan="7" class="text-center">No cases found.</td>
        </tr>
    @endif
</tbody>
    </table>
    </form>
</div>
</div><script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Handle success messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK'
        });
    @endif

    // Handle error messages
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK'
        });
    @endif

   

 // Confirm bulk delete
    function confirmBulkDelete() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover these entries!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            dangerMode: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    }
    
    
     function confirmBulkDeletes() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover these entries!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            dangerMode: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulkDeleteForms').submit();
            }
        });
    }
    // Confirm single delete
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover this entry!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            dangerMode: true,
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-employe-form-' + id).submit();
            }
        });
    }

    // Select all checkboxes
    document.getElementById('selectAll')?.addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[name="case_ids[]"]');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
    
    
</script>

<!-- Include Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBcDLPzCoHvcLAjs8n3lATJv1z7URQk93jEN0lpR9nSM1pIP" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuWLQjQjzHKAgCf2ZxKcbhs5jqKls8Z8Ncz5e8xAHPc2JOcI9C4OP9e5ab/78N3F" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>



@endsection



