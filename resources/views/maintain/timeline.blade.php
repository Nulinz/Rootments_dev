<div class="sidebodyhead mb-3">
    <h4 class="m-0">Maintenance Task Flow</h4>
    <a href="{{ route('maintain.task',['id'=>$rep[0]->id]) }}">
        <button class="listbtn">+ Add Task</button>
    </a>
</div>

<div class="container maindiv pt-3" style="height: 490px" id="timelinecards">
    <div class="timeline">
        @foreach ($rep_task as $t)


            <div class="entry completed">
                <div class="title">
                    <h3>{{ $t->c_name }}</h3>
                    <h6 class="mb-2">{{ $t->cr_role }}}</h6>
                    {{-- <h6>11: 00 AM</h6> --}}
                </div>
                <div class="entrybody">
                    <div class="taskname mb-1">
                        <div class="tasknameleft">
                            <i class="fa-solid fa-circle text-danger"></i>
                            <h6 class="mb-0">{{ $t->task_title}}</h6>
                        </div>
                        <div class="tasknamefile">
                            <h6 class="mb-0">
                                @if(!is_null($t->task_file))
                                    <a href="{{ asset($t->task_file) }}" data-bs-toggle="tooltip" data-bs-title="Attachment"
                                        download="{{ basename($t->task_file) }}">
                                        <i class="fa-solid fa-paperclip"></i>
                                @endif
                                </a>
                            </h6>
                        </div>
                    </div>
                    <div class="taskcategory mb-1">
                        <h6 class="mb-0">
                            <span class="category">{{ $t->category }}</span> /
                            <span class="subcat">{{ $t->subcategory }}</span>
                        </h6>
                    </div>
                    <div class="taskdescp mb-1">
                        <h6 class="mb-0">{{ $t->task_description }}.</h6>
                        <h5 class="mb-0">{{ $t->assign_name }} - {{ $t->user_role }}</h5>
                    </div>
                    <div class="taskdate mb-2">
                        <h6 class="m-0 startdate">
                            <i class="fa-regular fa-calendar"></i>&nbsp;
                            {{ date("d-m-Y", strtotime($t->start_date))}}
                        </h6>
                        <h6 class="m-0 enddate">
                            <i class="fas fa-flag"></i>&nbsp;
                            {{ date("d-m-Y", strtotime($t->end_date))}}
                        </h6>
                    </div>
                    <div class="taskdate">
                        <h6 class="m-0 startdate">
                            <i class="fas fa-hourglass-start"></i>&nbsp;
                            {{ date("h:i a", strtotime($t->start_time))}}
                        </h6>
                        <h6 class="m-0 enddate">
                            <i class="fas fa-hourglass-end"></i>&nbsp;
                            {{ date("h:i a", strtotime($t->end_time))}}
                        </h6>
                    </div>
                </div>
            </div>

        @endforeach
    </div>
</div>

<!-- Update Setup Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updateModalLabel">Update Setup</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="setupcat">Setup Category <span>*</span></label>
                        <select name="setupcat" id="setupcat" class="form-select" required>
                            <option value="" selected disabled>Select Category</option>
                            <option value="layout_design">Layout & 3D Designing</option>
                            <option value="structural_work">Structural & Civil Work</option>
                            <option value="electrical_installation">Electrical & Lighting Installations</option>
                            <option value="ac_ventilation">Air Conditioning & Ventilation</option>
                            <option value="plumbing_work">Plumbing & Water Supply Setup</option>
                            <option value="branding_work">Branding & Store Front Setup</option>
                            <option value="security_work">Security & Surveillance System Setup</option>
                            <option value="it_work">IT & Digital Infrastructure Setup</option>
                            <option value="furniture_work">Store Furniture & Fittings Setup</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="setupsubcat">Setup Sub Category <span>*</span></label>
                        <select name="setupsubcat" id="setupsubcat" class="form-select" required>
                            <option value="" selected disabled>Select Options</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="remarks">Remarks <span>*</span></label>
                        <textarea rows="3" class="form-control" name="remarks" id="remarks_1"
                            placeholder="Enter Remarks" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="attachment">Attachment</label>
                        <input type="file" class="form-control" name="attachment" id="attachment">
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto">
                        <button type="submit" class="modalbtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updatestsModal" tabindex="-1" aria-labelledby="updatestsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title fs-5" id="updatestsModalLabel">Update Status</h4>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="status">Status <span>*</span></label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="" selected disabled>Select Status</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="remarks">Remarks</label>
                        <textarea rows="3" class="form-control" name="remarks" id="remarks"
                            placeholder="Enter Remarks"></textarea>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto">
                        <button type="submit" class="modalbtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const categories = {
        "layout_design": [
            "Creating 3D layout based on available space",
            "Designing Interior & exterior branding elements",
            "Finalizing Display Unit placements",
            "Approvals & Modification Before Execution"
        ],
        "structural_work": [
            "Flooring Installation",
            "Tiling & Carpentry Work",
            "Ceiling & Partition Installation",
            "Wall Painting & Finishing"
        ],
        "electrical_installation": [
            "Main Power Connection Setup",
            "Electrical Wiring & Power Distribution",
            "Lighting Fixture Installation",
            "Backup Power Setup (Inverter/Generator)"
        ],
        "ac_ventilation": [
            "AC Unit Procurement & Installation",
            "Exhaust & Ventilation Setup"
        ],
        "plumbing_work": [
            "Pipe Fittings & Plumbing Installations",
            "Water Tank & Drainage Setup"
        ],
        "branding_work": [
            "Exterior Signage & Display Board Installation",
            "Interior Branding & Promotional Fixtures",
            "Glass Panel & Partition Installations"
        ],
        "security_work": [
            "CCTV System Installation",
            "Access Control Systems (Door Lock Systems, Biometrics)",
            "Fire Safety & Security Alarm Installation"
        ],
        "it_work": [
            "POS System & Billing Software Installation",
            "WiFi & Network System Setup",
            "Barcode Scanner & Printer Configuration"
        ],
        "furniture_work": [
            "Display Units & Shelving Installation",
            "Office chair and Seating Furniture",
            "Trial Room Setup",
            "Cash Counter & Storage Units"
        ]
    };

    $('#setupcat').change(function () {
        var selectedCategory = $(this).val();

        $('#setupsubcat').empty().append('<option value="" disabled selected>Select Sub Category</option>');

        if (selectedCategory) {
            var subcategories = categories[selectedCategory];

            $.each(subcategories, function (index, subcategory) {
                $('#setupsubcat').append('<option value="' + subcategory + '">' + subcategory + '</option>');
            });
        }
    });
</script>
