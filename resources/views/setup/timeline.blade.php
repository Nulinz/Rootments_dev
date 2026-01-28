<div class="sidebodyhead mb-3">
    <h4 class="m-0">Setup Flow</h4>
    <a data-bs-toggle="modal" data-bs-target="#updateModal"><button class="listbtn">+ Add Setup</button></a>
</div>

<div class="maindiv p-3">
    <div class="container" style="height: 490px" id="timelinecards">
        <div class="timeline">

            @foreach ($list as $sl)
            <div class="entry completed">
                <div class="title">
                    <h3>Category</h3>
                    <h6 class="text-success">{{ $sl->cat ?? null }}</h6>
                </div>
                <div class="entrybody">
                    <div class="taskname">
                        <div class="tasknameleft">

                            <h6 class="mb-0">{{ $sl->sub ?? null }}</h6>

                        </div>
                        <div class="tasknamefile">
                            @if(!is_null($sl->file))
                            <a href="{{ asset($sl->file) }}" data-bs-toggle="tooltip" data-bs-title="Attachment" download="{{ basename($sl->file) }}"><i
                                    class="fa-solid fa-paperclip"></i></a>
                                    @endif
                        </div>
                    </div>
                    <div class="taskdescp mb-2">
                        <h6 class="mb-0">{{ $sl->remark ?? null }}</h6>
                        <h6 class="mb-0"><span class="text-dark fw-bold">@if(!is_null($sl->s_remark))Remarks:</span> {{ $sl->s_remark ?? null }}@endif</h6>
                    </div>
                    <div class="taskdate">
                        <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp; {{ date("d-m-Y",strtotime($sl->created_at)) }}</h6>
                        <div>

                            @if($sl->status=='Active')
                            <a  class="mb-0" data-bs-toggle="modal" data-bs-target="#updatestsModal" ><button
                                    class="listtdbtn" data-id="{{ $sl->id }}">Update</button></a>
                            @else
                            <button class="listtdbtn" >{{ $sl->status }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
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
                <form action="{{ route('set.liststore') }}" method="POST" enctype="multipart/form-data" id="c_form">
                    @csrf
                    <input type="hidden" class="form-control" name="set_id" value="{{ $set_id }}">
                    <div class="col-sm-12 col-md-12 mb-3">
                        <label for="setupcat">Setup Category <span>*</span></label>
                        <select name="setupcat" id="setupcat" class="form-select" required>
                            <option value="" selected disabled>Select Category</option>
                            <option value="Layout & 3D Designing">Layout & 3D Designing</option>
                            <option value="Structural & Civil Work">Structural & Civil Work</option>
                            <option value="Electrical & Lighting Installations">Electrical & Lighting Installations</option>
                            <option value="Air Conditioning & Ventilation">Air Conditioning & Ventilation</option>
                            <option value="Plumbing & Water Supply Setup">Plumbing & Water Supply Setup</option>
                            <option value="Branding & Store Front Setup">Branding & Store Front Setup</option>
                            <option value="Security & Surveillance System Setup">Security & Surveillance System Setup</option>
                            <option value="IT & Digital Infrastructure Setup">IT & Digital Infrastructure Setup</option>
                            <option value="Store Furniture & Fittings Setup">Store Furniture & Fittings Setup</option>
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
                        <button type="submit" id="sub" class="modalbtn">Update</button>
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
                <form action="{{ route('liststore.update') }}" method="POST" id="c_form1">
                    @csrf
                    <input type="hidden" class="form-control" name="e_id" id="e_id">
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
                        <textarea rows="3" class="form-control" name="s_remark" id="remarks"
                            placeholder="Enter Remarks">nil</textarea>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto">
                        <button type="submit" id="sub1" class="modalbtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/form_script.js') }}"></script>

<script>
    $('.listtdbtn').on('click',function(){
        var set_id = $(this).data('id');

        $('#e_id').val(set_id);

    });
</script>

<script>
    const categories = {
        "Layout & 3D Designing": [
            "Creating 3D layout based on available space",
            "Designing Interior & exterior branding elements",
            "Finalizing Display Unit placements",
            "Approvals & Modification Before Execution"
        ],
        "Structural & Civil Work": [
            "Flooring Installation",
            "Tiling & Carpentry Work",
            "Ceiling & Partition Installation",
            "Wall Painting & Finishing"
        ],
        "Electrical & Lighting Installations": [
            "Main Power Connection Setup",
            "Electrical Wiring & Power Distribution",
            "Lighting Fixture Installation",
            "Backup Power Setup (Inverter/Generator)"
        ],
        "Air Conditioning & Ventilation": [
            "AC Unit Procurement & Installation",
            "Exhaust & Ventilation Setup"
        ],
        "Plumbing & Water Supply Setup": [
            "Pipe Fittings & Plumbing Installations",
            "Water Tank & Drainage Setup"
        ],
        "Branding & Store Front Setup": [
            "Exterior Signage & Display Board Installation",
            "Interior Branding & Promotional Fixtures",
            "Glass Panel & Partition Installations"
        ],
        "Security & Surveillance System Setup": [
            "CCTV System Installation",
            "Access Control Systems (Door Lock Systems, Biometrics)",
            "Fire Safety & Security Alarm Installation"
        ],
        "IT & Digital Infrastructure Setup": [
            "POS System & Billing Software Installation",
            "WiFi & Network System Setup",
            "Barcode Scanner & Printer Configuration"
        ],
        "Store Furniture & Fittings Setup": [
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
