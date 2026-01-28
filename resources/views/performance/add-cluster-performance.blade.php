@extends('layouts.app')
@section('content')
    <style>
        .star {
            font-size: 3rem;
        }

        .star-rating {
            direction: rtl;
            /* display: flex; */
            font-size: 1.5rem;
            text-align: end;
            /* padding: 10px; */
            /* margin:0px 10px; */
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            /* color: #9c9797 !important; */
            cursor: pointer;
            transition: color 0.2s;
            margin: 0px 5px;
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
            /* yellow stars */
        }

        .star-size {
            font-size: 2.2rem !important;
        }
    </style>
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add WorkUpdate</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Workupdate</h4>
        </div>
            <form action="{{ route('performance.cluster_storeperformance') }}" method="post">
                @csrf
                <div class="container-fluid maindiv">
                    <div class="row">
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="manager_id">Manager Name <span>*</span></label>
                            <select name="manager_id" id="manager_id" class="form-select">
                                <option value="" selected disabled>-- Selected --</option>
                                @foreach ($managers as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Total Walk-ins -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="total_walkins">Total Walk-ins</label>
                            <input type="text" class="form-control" name="total_walkins" id="total_walkins" value="0">
                        </div>
                        <!-- LY MTD (Walk-ins) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="ly_mtd_walkins">LY MTD (Walk-ins)</label>
                            <input type="text" class="form-control" name="ly_mtd_walkins" id="ly_mtd_walkins">
                        </div>
                        <!-- L2L (Walk-ins) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="l2l_walkins">L2L (Walk-ins)</label>
                            <input type="text" class="form-control" name="l2l_walkins" id="l2l_walkins">
                        </div>

                        <!-- Total Bills -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="total_bills">Total Bills</label>
                            <input type="text" class="form-control" name="total_bills" id="total_bills">
                        </div>
                        <!-- LY MTD (Bills) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="ly_mtd_bills">LY MTD (Bills)</label>
                            <input type="text" class="form-control" name="ly_mtd_bills" id="ly_mtd_bills">
                        </div>
                        <!-- L2L (Bills) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="l2l_bills">L2L (Bills)</label>
                            <input type="text" class="form-control" name="l2l_bills" id="l2l_bills">
                        </div>

                        <!-- Total Quantity -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="total_quantity">Total Quantity</label>
                            <input type="text" class="form-control" name="total_quantity" id="total_quantity">
                        </div>
                        <!-- LY MTD (Quantity) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="ly_mtd_quantity">LY MTD (Quantity)</label>
                            <input type="text" class="form-control" name="ly_mtd_quantity" id="ly_mtd_quantity">
                        </div>
                        <!-- L2L (Quantity) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="l2l_quantity">L2L (Quantity)</label>
                            <input type="text" class="form-control" name="l2l_quantity" id="l2l_quantity">
                        </div>

                        <!-- ABS -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="abs">ABS</label>
                            <input type="text" class="form-control" name="abs" id="abs">
                        </div>
                        <!-- LY MTD (ABS) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="ly_mtd_abs">LY MTD (ABS)</label>
                            <input type="text" class="form-control" name="ly_mtd_abs" id="ly_mtd_abs">
                        </div>
                        <!-- L2L (ABS) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="l2l_abs">L2L (ABS)</label>
                            <input type="text" class="form-control" name="l2l_abs" id="l2l_abs">
                        </div>

                        <!-- KPI Points -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="kpi_points">KPI Points</label>
                            <input type="text" class="form-control" name="kpi_points" id="kpi_points">
                        </div>
                        <!-- LY MTD (KPI) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="ly_mtd_kpi">LY MTD (KPI)</label>
                            <input type="text" class="form-control" name="ly_mtd_kpi" id="ly_mtd_kpi">
                        </div>
                        <!-- L2L (KPI) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="l2l_kpi">L2L (KPI)</label>
                            <input type="text" class="form-control" name="l2l_kpi" id="l2l_kpi">
                        </div>

                        <!-- Conversion % -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="conversion_percent">Conversion %</label>
                            <input type="text" class="form-control" name="conversion_percent" id="conversion_percent">
                        </div>
                        <!-- LY MTD (Conversion) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="ly_mtd_conversion">LY MTD (Conversion)</label>
                            <input type="text" class="form-control" name="ly_mtd_conversion" id="ly_mtd_conversion">
                        </div>
                        <!-- L2L (Conversion) -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="l2l_conversion">L2L (Conversion)</label>
                            <input type="text" class="form-control" name="l2l_conversion" id="l2l_conversion">
                        </div>

                        <!-- TGT -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="tgt">TGT</label>
                            <input type="text" class="form-control" name="tgt" id="tgt">
                        </div>
                        <!-- TGT Achievement % -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="tgt_achievement_percent">TGT Achievement %</label>
                            <input type="text" class="form-control" name="tgt_achievement_percent" id="tgt_achievement_percent">
                        </div>

                        <!-- Contribution -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="contribution">Contribution</label>
                            <input type="text" class="form-control" name="contribution" id="contribution">
                        </div>

                        <!-- Total Extra Leaves -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="total_extra_leaves">Total Extra Leaves</label>
                            <input type="text" class="form-control" name="total_extra_leaves" id="total_extra_leaves">
                        </div>

                        <!-- Total Sick Leave -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="total_sick_leaves">Total Sick Leave</label>
                            <input type="text" class="form-control" name="total_sick_leaves" id="total_sick_leaves">
                        </div>
                    </div>

                    <!-- Star Ratings Section -->
                    <div class="row">
                        @foreach ([
                'customer_relation' => 'Customer Relation',
                'team_management' => 'Team Management',
                'google_review' => 'Google Review',
                'training_completion' => 'Training Completion',
                'damage_control' => 'Damage Control',
                'product_quality' => 'Product Quality',
                'staff_training' => 'Staff Training',
                'daily_photos' => 'Daily Photos',
            ] as $name => $label)
                            <div class="col-sm-12 col-md-6 col-xl-6 inputs mb-3">
                                <label for="{{ $name }}">{{ $label }} <span>*</span></label>
                                <div class="star-rating">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="{{ $name }}_{{ $i }}" name="{{ $name }}" value="{{ $i }}">
                                        <label class="star-size" for="{{ $name }}_{{ $i }}">&#9733;</label>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                    <a href="">
                        <button type="submit" id="sub" class="formbtn">Save</button>
                    </a>
                </div>
            </form>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>
    {{-- <script>
        function calculateTgtAchievement() {
            let tgt = parseFloat($('#tgt').val()) || 0;
            let totalBills = parseFloat($('#total_bills').val()) || 0;

            if (totalBills !== 0) {
                let result = (tgt / totalBills) * 100;
                $('#tgt_achievement_percent').val(result.toFixed(2));
            } else {
                $('#tgt_achievement_percent').val(0);
            }
        }

        $('#tgt, #total_bills').on('input', calculateTgtAchievement);
    </script> --}}
    <script>
        $('#manager_id').on('change', function() {
            let managerId = $(this).val();
            if (managerId) {
                $.ajax({
                    url: '{{ route('manager.performance.data') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        manager_id: managerId
                    },
                    // success: function(data) {
                    //     $('#total_walkins').val(data.total_walkins);
                    //     $('#ly_mtd_walkins').val(data.ly_mtd_walkins);
                    //     $('#l2l_walkins').val(data.l2l_walkins.toFixed(2));

                    //     $('#total_bills').val(data.total_bills);
                    //     $('#ly_mtd_bills').val(data.ly_mtd_bills);
                    //     $('#l2l_bills').val(data.l2l_bills.toFixed(2));

                    //     $('#total_quantity').val(data.total_quantity);
                    //     $('#ly_mtd_quantity').val(data.ly_mtd_quantity);
                    //     $('#l2l_quantity').val(data.l2l_quantity.toFixed(2));

                    //     $('#abs').val(data.abs);
                    //     $('#ly_mtd_abs').val(data.ly_mtd_abs);
                    //     $('#l2l_abs').val(data.l2l_abs.toFixed(2));

                    //     $('#kpi_points').val(data.kpi_points);
                    //     $('#ly_mtd_kpi').val(data.ly_mtd_kpi);
                    //     $('#l2l_kpi').val(data.l2l_kpi);

                    //     $('#conversion_percent').val(data.conversion_percent);
                    //     $('#ly_mtd_conversion').val(data.ly_mtd_conversion);
                    //     $('#l2l_conversion').val(data.l2l_conversion.toFixed(2));

                    //     $('#tgt').val(data.tgt);
                    //     $('#tgt_achievement_percent').val(data.tgt_achievement_percent);
                    //     $('#contribution').val(data.contribution);
                    //     $('#total_extra_leaves').val(data.total_extra_leaves);
                    //     $('#total_sick_leaves').val(data.total_sick_leaves);
                    // }
                    success: function(data) {
                        $('#total_walkins').val(data.total_walkins ?? 0);
                        $('#ly_mtd_walkins').val(data.ly_mtd_walkins ?? 0);
                        $('#l2l_walkins').val((data.l2l_walkins ?? 0).toFixed(2));

                        $('#total_bills').val(data.total_bills ?? 0);
                        $('#ly_mtd_bills').val(data.ly_mtd_bills ?? 0);
                        $('#l2l_bills').val((data.l2l_bills ?? 0).toFixed(2));

                        $('#total_quantity').val(data.total_quantity ?? 0);
                        $('#ly_mtd_quantity').val(data.ly_mtd_quantity ?? 0);
                        $('#l2l_quantity').val((data.l2l_quantity ?? 0).toFixed(2));

                        $('#abs').val(data.abs ?? 0);
                        $('#ly_mtd_abs').val(data.ly_mtd_abs ?? 0);
                        $('#l2l_abs').val((data.l2l_abs ?? 0).toFixed(2));

                        $('#kpi_points').val(data.kpi_points ?? 0);
                        $('#ly_mtd_kpi').val(data.ly_mtd_kpi ?? 0);
                        $('#l2l_kpi').val((data.l2l_kpi ?? 0).toFixed(2));

                        $('#conversion_percent').val(data.conversion_percent ?? 0);
                        $('#ly_mtd_conversion').val(data.ly_mtd_conversion ?? 0);
                        $('#l2l_conversion').val((data.l2l_conversion ?? 0).toFixed(2));

                        $('#tgt').val(data.tgt ?? 0);
                        $('#tgt_achievement_percent').val(data.tgt_achievement_percent ?? 0);
                        $('#contribution').val(data.contribution ?? 0);

                        $('#total_extra_leaves').val(data.total_extra_leaves ?? 0);
                        $('#total_sick_leaves').val(data.total_sick_leaves ?? 0);
                    }
                });
            }
        });
    </script>
@endsection
