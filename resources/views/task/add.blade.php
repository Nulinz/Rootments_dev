@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Task Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Task Details</h4>
        </div>
        <form action="{{ route('task.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="container-fluid maindiv my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="tasktitle">Task Title <span>*</span></label>
                        <input type="text" class="form-control" name="task_title" id="tasktitle"
                            placeholder="Enter Task Title" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="category">Category <span>*</span></label>
                        <select name="category_id" id="category" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($cat as $item)
                                <option value="{{ $item->id }}">{{ $item->category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="subcategory">Sub Category <span>*</span></label>
                        <select name="subcategory_id" id="subcategory" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>

                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="assignto">Assign To <span>*</span></label>
                        <div class="col-sm-12 col-md-12 col-xl-12">
                            <div class="dropdown-center tble-dpd">
                                <button class="w-100 text-start form-select checkdrp" type="button"
                                    data-bs-toggle="dropdown" id="assignto" aria-expanded="false">
                                    Select Options
                                </button>
                                <div class="dropdown-menu w-100 px-3" id="roleDropdownMenu">
                                    @foreach ($assignedRoles->groupBy('role_dept') as $dept => $roles)
                                        <div class="dropdown-header">{{ $dept }}</div>
                                        @foreach ($roles as $role)
                                            <div class="d-flex align-items-center w-100 mt-1">
                                                <label for="role-{{ $role->id }}" class="my-auto fw-bold">{{ $role->role }}</label>
                                            </div>
                                            @php
                                                $user = auth()->user();
                                                $employees = collect();
                                                if ($user->role_id != 11) {
                                                    $employeesQuery = \App\Models\User::where('role_id', $role->id)->where('id', '!=', $user->id);
                                                    if (!in_array($user->dept, ['Admin', 'HR'])) {
                                                        $employeesQuery->where('store_id', $user->store_id);
                                                    }
                                                    $employees = $employeesQuery->get();
                                                } else {
                                                    $emp_clusters = \App\Models\Cluster::where('cl_name', $user->id)->get();
                                                    foreach ($emp_clusters as $emp_cluster) {
                                                        $emp_store = \App\Models\Clusterstore::where('cluster_id', $emp_cluster->id)->get();
                                                        foreach ($emp_store as $store) {
                                                            $storeEmployees = \App\Models\User::where('store_id', $store->store_id)->where('role_id', 12)->get();
                                                            $employees = $employees->merge($storeEmployees);
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <ul class="ms-4 list-unstyled" id="assignDropdown">
                                                <li>
                                                    <div class="d-flex align-items-center w-100 mt-1 mb-2">
                                                        <input type="text" class="form-control" id="assignSearch"
                                                            data-dropdown="assignDropdown" placeholder="Search">
                                                    </div>
                                                </li>
                                                @foreach ($employees as $employee)
                                                    @if($employee->role_id == $role->id)
                                                        <li>
                                                            <input type="checkbox" class="me-2 employee-checkbox role-{{ $role->id }}"
                                                                name="assign_to[]" value="{{ $employee->id }}">
                                                            <label>{{ $employee->name }}</label>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="description">Task Description <span>*</span></label>
                        <textarea rows="1" class="form-control" name="task_description" id="description"
                            placeholder="Enter Task Description" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="additionalinfo">Additional Information</label>
                        <textarea rows="1" class="form-control" name="additional_info" id="additionalinfo"
                            placeholder="Enter Additional Information"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="startdate">Start Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="start_date" id="start_date" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="time">Start Time <span>*</span></label>
                        <input type="time" class="form-control" name="start_time" id="time" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="enddate">End Date <span>*</span></label>
                        <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" min="1000-01-01"
                            max="9999-12-31" name="end_date" id="enddatedate" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="time">End Time <span>*</span></label>
                        <input type="time" class="form-control" name="end_time" id="time" required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="priority">Priority <span>*</span></label>
                        <select class="form-select" name="priority" id="priority" required>
                            <option value="" selected disabled>Select Options</option>
                            <option value="High">High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="file">File</label>
                        <input type="file" class="form-control" name="task_file" id="file_img">
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>
    </div>


    <script>
        $(document).ready(function () {
            $('#category').on('change', function () {
                var categoryId = $(this).val();
                var subcategorySelect = $('#subcategory');

                subcategorySelect.html('<option value="">Select Subcategory</option>');

                if (categoryId) {
                    $.ajax({
                        url: '{{ route('get_sub_cat') }}',
                        type: 'POST',
                        data: {
                            category_id: categoryId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            if (data.length > 0) {
                                $.each(data, function (index, subcategory) {
                                    subcategorySelect.append(
                                        $('<option></option>')
                                            .val(subcategory.id)
                                            .text(subcategory.subcategory)
                                    );
                                });
                            } else {
                                subcategorySelect.append(
                                    '<option value="">No Subcategories Available</option>'
                                );
                            }
                        },
                        error: function () {
                            alert('Failed to fetch subcategories. Please try again.');
                        }
                    });
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Function to update "Select All" checkbox based on individual selections
            function updateSelectAll(roleId) {
                let checkboxes = document.querySelectorAll(".role-" + roleId);
                let selectAll = document.querySelector(".select-all[data-role='" + roleId + "']");
                if (!selectAll) return; // Ensure selectAll exists

                let allChecked = [...checkboxes].every(cb => cb.checked);
                selectAll.checked = allChecked;
            }

            // "Select All" Checkbox Event Listener
            document.querySelectorAll(".select-all").forEach(selectAllCheckbox => {
                selectAllCheckbox.addEventListener("change", function () {
                    let roleId = this.getAttribute("data-role");
                    let checkboxes = document.querySelectorAll(".role-" + roleId);
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                });
            });

            // Individual Employee Checkbox Event Listener
            document.querySelectorAll(".employee-checkbox").forEach(employeeCheckbox => {
                employeeCheckbox.addEventListener("change", function () {
                    let roleId = this.classList[1].split("-")[1]; // Extract role ID from class (e.g., "role-3")
                    updateSelectAll(roleId);
                });
            });
        });
    </script>

    <script>
        document.getElementById("assignSearch").addEventListener("keyup", function () {
            let filter = this.value.toLowerCase();
            let dropdownId = this.getAttribute("data-dropdown");
            let items = document.querySelectorAll(`#${dropdownId} li:not(:first-child)`);

            items.forEach(item => {
                item.classList.toggle("d-none", !item.textContent.toLowerCase().includes(filter));
            });
        });
    </script>

@endsection