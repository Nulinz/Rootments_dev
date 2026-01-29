    @extends('layouts.app')

    @section('content')
        <style>
            .dt-buttons {
                display: none !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                font-size: 14px;
            }

            div.dataTables_wrapper div.dataTables_info {
                font-size: 14px;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            table th,
            table td {
                padding: 8px 20px !important;
                /* vertical: 8px, horizontal: 12px */
                text-align: center;
                vertical-align: middle;
                border: 1px solid #888 !important;
                /* Light border between cells */
                font-size: 14px;
            }

            table thead th {
                background-color: #f4f4f4;
                /* Light gray background for headers */
                font-weight: bold;
            }

            .table-wrapper {
                overflow-x: auto;
                white-space: nowrap;
            }

            .scroll-track {
                width: 100%;
                height: 10px;
                background: #eee;
                border-radius: 5px;
                margin-top: 8px;
                position: relative;
            }

            .scroll-thumb {
                height: 100%;
                background: #c4cad0;
                border-radius: 5px;
                width: 100px;
                cursor: grab;
                position: absolute;
                left: 0;
                transition: background 0.2s;
            }

            .scroll-thumb:active {
                cursor: grabbing;
                background: #181818;
            }
        </style>
        <div class="sidebodydiv px-5 py-3">
            <div class="sidebodyhead">
                <h4 class="m-0">DSR-Rental Report</h4>
            </div>

            {{-- @if (request()->isMethod('GET')) --}}
            <form action="{{ route('dsr.rental.report') }}" method="POST">
                @csrf
                <div class="container-fluid maindiv my-3">
                    <div class="row">
                        {{-- <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="month">Month</label>
                            <input type="month" class="form-control" name="month" id="month" value="{{ date('Y-m-d') }}">
                        </div> --}}
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" name="date" id="date">
                        </div>

                        <!-- Store Dropdown -->
                        <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                            <label for="storeDropdownBtn">Store <span>*</span></label>
                            <div class="col-sm-12 col-md-12 col-xl-12">
                                <div class="dropdown w-100" data-bs-auto-close="outside">
                                    <!-- Trigger styled like a <select> -->
                                    <button class="form-select text-start" style="font-size: 14px" type="button" id="storeDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                        Select Options
                                    </button>

                                    <!-- Dropdown menu with checkboxes -->
                                    <ul class="dropdown-menu w-100 px-2" aria-labelledby="storeDropdownBtn" id="storeDropdown">
                                        @foreach ($store as $st)
                                            <li>
                                                <label class="d-flex align-items-center">
                                                    <input type="checkbox" class="store-checkbox me-2" name="store_list[]" value="{{ $st->stores_id }}"
                                                        {{ auth()->user()->role_id == 12 && auth()->user()->store_id == $st->stores_id ? 'checked' : '' }}>
                                                    {{ $st->stores_name }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- Hidden required field for validation -->
                                <input type="text" id="storeRequired" required hidden>
                            </div>
                        </div>

                        @if (!in_array(auth()->user()->role_id, [1, 2, 6, 11, 66]))
                            <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                                <label for="employeeDropdownBtn">Employee (Optional)</label>
                                <div class="col-sm-12 col-md-12 col-xl-12">
                                    <div class="dropdown w-100" data-bs-auto-close="outside">
                                        <!-- Trigger styled like a <select> -->
                                        <button class="form-select text-start" style="font-size: 14px" type="button" id="employeeDropdownBtn" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            Select Employee
                                        </button>

                                        <!-- Dropdown with checkboxes -->
                                        <ul class="dropdown-menu w-100 px-2" aria-labelledby="employeeDropdownBtn" id="employeeDropdown">
                                            <li><label class="d-flex align-items-center fw-bold">
                                                    <input type="checkbox" id="selectAllEmployees" class="me-2"> Select All
                                                </label></li>
                                            <!-- Employees will be loaded here -->
                                        </ul>
                                    </div>

                                    <!-- Hidden field for validation -->
                                    <input type="text" id="employeeRequired" hidden>
                                </div>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <p class="text-muted mb-0" style="font-size:13.5px;"><span class="text-danger">Note:</span> To view all employee data, do not select any employee from the
                                dropdown.</p>
                        </div>

                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-xl-12 w-50 d-flex justify-content-center align-items-center mx-auto mt-3">
                    <button type="submit" class="formbtn">Save</button>
                </div>

            </form>
            {{-- @endif --}}
            @if (request()->isMethod('post'))
                <div class="container-fluid listtable mt-4">
                    <div class="filter-container row mb-3">
                        <div class="custom-search-container col-sm-12 col-md-8">
                            {{-- <select class="headerDropdown form-select filter-option">
                                <option value="All" selected>All</option>
                            </select> --}}

                            <input type="text" id="customSearch" class="form-control filterInput" placeholder="Search">
                        </div>
                        <div class="select1 col-sm-12 col-md-4 mx-auto">
                            <div class="d-flex gap-3">
                                <a id="printBtn"><img src="{{ asset('assets/images/printer.png') }}" id="print" alt="" height="28px" data-bs-toggle="tooltip"
                                        data-bs-title="Print"></a>
                                <a id="excelBtn"><img src="{{ asset('assets/images/excel.png') }}" id="excel" alt="" height="30px" data-bs-toggle="tooltip"
                                        data-bs-title="Excel"></a>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="table-wrapper">
                        <table id="example" class="table-hover table-striped table-bordered mb-0"> --}}
                    <div class="table-wrapper" id="scrollable-table">
                        <table id="example" class="table-hover table-striped table-bordered mb-0">

                            <thead style="border-top: 1px solid #888;">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle; text-align: start; border-right: 1px solid #888;">
                                        {{ in_array(auth()->user()->role_id, [1, 2, 6, 11, 66]) ? 'Store Name' : 'Employee Name' }}
                                    </th>
                                    {{-- <th rowspan="2" style="vertical-align: middle; text-align: start; border-right: 1px solid #888;">Employee Name</th> --}}
                                    <th colspan="4" style="border-right: 1px solid #888;">Bill</th>
                                    <th colspan="4" style="border-right: 1px solid #888;">Qty</th>
                                    <th colspan="4" style="border-right: 1px solid #888;">Value</th>
                                    <!--<th colspan="4" style="border-right: 1px solid #888;">KPI Points</th>-->
                                    <th style="border-right: 1px solid #888;">ABS</th>
                                    <th style="border-right: 1px solid #888;">ABV</th>
                                    <th style="border-right: 1px solid #888;">Value</th>
                                    <th style="border-right: 1px solid #888;"></th>
                                    <th style="border-right: 1px solid #888;">Qty</th>
                                    <th colspan="4" style="border-right: 1px solid #888;">Walkin</th>
                                    <th colspan="2" style="border-right: 1px solid #888;">Loss of sales</th>
                                    <th style="border-right: 1px solid #888;">Conversion</th>
                                    <th rowspan="2">Created on</th>
                                </tr>
                                <tr>
                                    <th style="border-right: 1px solid #888;">Ftd</th>
                                    <th style="border-right: 1px solid #888;">Mtd</th>
                                    <th style="border-right: 1px solid #888;">LY MTD</th>
                                    <th style="border-right: 1px solid #888;">L2L</th>
                                    <th style="border-right: 1px solid #888;">Ftd</th>
                                    <th style="border-right: 1px solid #888;">Mtd</th>
                                    <th style="border-right: 1px solid #888;">LY MTD</th>
                                    <th style="border-right: 1px solid #888;">L2L</th>
                                    <th style="border-right: 1px solid #888;">Ftd</th>
                                    <th style="border-right: 1px solid #888;">Mtd</th>
                                    <th style="border-right: 1px solid #888;">LY MTD</th>
                                    <th style="border-right: 1px solid #888;">L2L</th>
                                    <!--<th style="border-right: 1px solid #888;">Ftd</th>-->
                                    <!--<th style="border-right: 1px solid #888;">Mtd</th>-->
                                    <!--<th style="border-right: 1px solid #888;">LY MTD</th>-->
                                    <!--<th style="border-right: 1px solid #888;">L2L</th>-->
                                    <th style="border-right: 1px solid #888;"></th>
                                    <th style="border-right: 1px solid #888;"></th>
                                    <th style="border-right: 1px solid #888;">TGT</th>
                                    <th style="border-right: 1px solid #888;">Ach %</th>
                                    <th style="border-right: 1px solid #888;">TGT</th>
                                    <th style="border-right: 1px solid #888;">Ftd</th>
                                    <th style="border-right: 1px solid #888;">Mtd</th>
                                    <th style="border-right: 1px solid #888;">LY MTD</th>
                                    <th style="border-right: 1px solid #888;">L2L</th>
                                    <th style="border-right: 1px solid #888;">FTD</th>
                                    <th style="border-right: 1px solid #888;">MTD</th>
                                    <th style="border-right: 1px solid #888;">CON %</th>
                                </tr>
                            </thead>
                            @if (in_array(auth()->user()->role_id, [1, 2, 6, 11, 66]))
                                <tbody>
                                    @php
                                        $total_b_ftd = 0;
                                        $total_b_mtd = 0;
                                        $total_b_ly = 0;
                                        $total_b_l2l = 0;
                                        $b_l2l_calc = 0;
                                        $total_q_ftd = 0;
                                        $total_q_mtd = 0;
                                        $total_q_ly = 0;
                                        $total_q_l2l = 0;
                                        $q_l2l_calc = 0;
                                        $total_v_ftd = 0;
                                        $total_v_mtd = 0;
                                        $total_v_ly = 0;
                                        $total_v_l2l = 0;
                                        $v_l2l_calc = 0;
                                        $total_k_ftd = 0;
                                        $total_k_mtd = 0;
                                        $total_k_ly = 0;
                                        $total_k_l2l = 0;
                                        $k_l2l_calc = 0;
                                        $total_abs = 0;
                                        $total_abv = 0;
                                        $total_tgt_value = 0;
                                        $total_ach = 0;
                                        $total_tgt_qty = 0;
                                        $total_w_ftd = 0;
                                        $total_w_mtd = 0;
                                        $total_w_ly = 0;
                                        $total_w_l2l = 0;
                                        $w_l2l_calc = 0;
                                        $total_l_ftd = 0;
                                        $total_l_ftd_calc = 0;
                                        $total_l_mtd = 0;
                                        $total_l_mtd_calc = 0;
                                        $total_conv = 0;
                                    @endphp

                                    @foreach ($list as $li)
                                        <tr>
                                            <td>{{ $li->username }}</td>
                                            <td>{{ $li->b_ftd }}</td>
                                            <td>{{ $li->b_mtd }}</td>
                                            <td>{{ $li->ly_bill }}</td>
                                            @php
                                                if ($li->ly_bill > 0) {
                                                    $b_l2l = ($li->b_mtd / $li->ly_bill - 1) * 100;
                                                } else {
                                                    $b_l2l = 0;
                                                }
                                            @endphp
                                            <td>{{ number_format($b_l2l) }}%</td>
                                            <td>{{ $li->q_ftd }}</td>
                                            <td>{{ $li->q_mtd }}</td>
                                            <td>{{ $li->ly_qty }}</td>
                                            @php
                                                if ($li->ly_qty > 0) {
                                                    $q_l2l = ($li->q_mtd / $li->ly_qty - 1) * 100;
                                                } else {
                                                    $q_l2l = 0;
                                                }
                                            @endphp
                                            <td>{{ number_format($q_l2l) }}%</td>
                                            <td>{{ $li->v_ftd }}</td>
                                            <td>{{ $li->v_mtd }}</td>
                                            <td>{{ $li->v_ly }}</td>
                                            <td>{{ $li->v_ltl }}%</td>
                                            <!--<td>{{ $li->k_ftd }}</td>-->
                                            <!--<td>{{ $li->k_mtd }}</td>-->
                                            <!--<td>{{ $li->k_lymtd }}</td>-->
                                            <!--<td>{{ $li->k_ltl }}%</td>-->
                                            @php
                                                if ($li->b_mtd > 0) {
                                                    $t_abs = $li->q_mtd / $li->b_mtd;
                                                } else {
                                                    $t_abs = 0;
                                                }
                                            @endphp
                                            <td>{{ number_format($t_abs, 2) }}</td>
                                            @php
                                                if ($li->b_mtd > 0) {
                                                    $t_abv = $li->v_mtd / $li->b_mtd;
                                                } else {
                                                    $t = 0;
                                                }
                                            @endphp
                                            <td>{{ number_format($t_abv, 2) }}</td>
                                            <td>{{ $li->tgt_value }}</td>
                                            @php
                                                if ($li->tgt_value > 0) {
                                                    $t_ach = ($li->v_mtd / $li->tgt_value) * 100;
                                                } else {
                                                    $t_ach = 0;
                                                }
                                            @endphp
                                            <td>{{ number_format($t_ach, 2) . '%' }}</td>
                                            <td>{{ $li->tgt_qty }}</td>
                                            <td>{{ $li->w_ftd }}</td>
                                            <td>{{ $li->w_mtd }}</td>
                                            <td>{{ $li->ly_walk }}</td>
                                            @php
                                                if ($li->ly_walk > 0) {
                                                    $w_l2l = ($li->w_mtd / $li->ly_walk - 1) * 100;
                                                } else {
                                                    $w_l2l = 0;
                                                }
                                            @endphp
                                            <td>{{ number_format($w_l2l, 2) }}%</td>
                                            <td>{{ $li->los_ftd }}</td>
                                            <td>{{ $li->los_mtd }}</td>
                                            @php
                                                if ($li->w_mtd > 0) {
                                                    $t_con = ($li->b_mtd / $li->w_mtd) * 100;
                                                } else {
                                                    $t_con = 0;
                                                }
                                            @endphp

                                            <td>{{ number_format($t_con, 2) . '%' }}</td>
                                            <td>{{ date('d-m-Y', strtotime($li->created_at)) }}</td>
                                        </tr>
                                        @php
                                            // Summing up the raw values, not the percentages
                                            $total_b_ftd += $li->b_ftd;
                                            $total_b_mtd += $li->b_mtd;
                                            $total_b_ly += (int) ($li->ly_bill ?? 0);
                                            $total_b_l2l += $b_l2l;
                                            //$b_l2l_calc = ($total_b_mtd / $total_b_ly - 1) * 100;
                                            if ($total_b_ly > 0) {
                                                $b_l2l_calc = ($total_b_mtd / $total_b_ly - 1) * 100;
                                            } else {
                                                $b_l2l_calc = 0;
                                            }
                                            $total_q_ftd += $li->q_ftd;
                                            $total_q_mtd += $li->q_mtd;
                                            $total_q_ly += (int) ($li->ly_qty ?? 0);
                                            $total_q_l2l += $q_l2l;
                                            //$q_l2l_calc = ($total_q_mtd / $total_q_ly - 1) * 100;
                                            if ($total_q_ly > 0) {
                                                $q_l2l_calc = ($total_q_mtd / $total_q_ly - 1) * 100;
                                            } else {
                                                $q_l2l_calc = 0;
                                            }
                                            $total_v_ftd += $li->v_ftd;
                                            $total_v_mtd += $li->v_mtd;
                                            $total_v_ly += $li->v_ly;
                                            $total_v_l2l += $li->v_ltl;

                                            $total_k_ftd += $li->k_ftd;
                                            $total_k_mtd += $li->k_mtd;
                                            $total_k_ly += $li->k_lymtd;
                                            $total_k_l2l += $li->k_ltl;
                                            if ($total_b_mtd > 0) {
                                                $total_abs = $total_q_mtd / $total_b_mtd;
                                            } else {
                                                $total_abs = 0;
                                            }
                                            if ($total_b_mtd > 0) {
                                                $total_abv = $total_v_mtd / $total_b_mtd;
                                            } else {
                                                $total_abv = 0;
                                            }
                                            $total_tgt_value += $li->tgt_value;
                                            if ($total_tgt_value > 0) {
                                                $total_ach = ($total_v_mtd / $total_tgt_value) * 100;
                                            } else {
                                                $total_ach = 0;
                                            }
                                            $total_tgt_qty += $li->tgt_qty;
                                            $total_w_ftd += $li->w_ftd;
                                            $total_w_mtd += $li->w_mtd;
                                            $total_w_ly += (int) ($li->ly_walk ?? 0);
                                            $total_w_l2l += $w_l2l;
                                            //$w_l2l_calc = ($total_w_mtd / $total_w_ly - 1) * 100;

                                            if ($total_w_ly > 0) {
                                                $w_l2l_calc = ($total_w_mtd / $total_w_ly - 1) * 100;
                                            } else {
                                                $w_l2l_calc = 0;
                                            }
                                            $total_l_ftd += $li->los_ftd;
                                            $total_l_ftd_calc = $total_w_ftd - $total_b_ftd;
                                            $total_l_mtd += $li->los_mtd;
                                            $total_l_mtd_calc = $total_w_mtd - $total_b_mtd;
                                            if ($total_w_mtd > 0) {
                                                $total_conv = ($total_b_mtd / $total_w_mtd) * 100;
                                            } else {
                                                $total_conv = 0;
                                            }
                                        @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td class="text-center">{{ $total_b_ftd }}</td>
                                        <td class="text-center">{{ $total_b_mtd }}</td>
                                        <td class="text-center">{{ $total_b_ly }}</td>
                                        <td class="text-center">{{ number_format($b_l2l_calc, 2) }}%</td>
                                        <td class="text-center">{{ $total_q_ftd }}</td>
                                        <td class="text-center">{{ $total_q_mtd }}</td>
                                        <td class="text-center">{{ $total_q_ly }}</td>
                                        <td class="text-center">{{ number_format($q_l2l_calc, 2) }}%</td>
                                        <td class="text-center">{{ $total_v_ftd }}</td>
                                        <td class="text-center">{{ $total_v_mtd }}</td>
                                        <td class="text-center">{{ $total_v_ly }}</td>
                                        <td class="text-center">{{ $total_v_l2l }}%</td>
                                        <!--<td class="text-center">{{ $total_k_ftd }}</td>-->
                                        <!--<td class="text-center">{{ $total_k_mtd }}</td>-->
                                        <!--<td class="text-center">0</td>-->
                                        <!--<td class="text-center">0%</td>-->
                                        <td class="text-center">{{ number_format($total_abs, 2) }}</td>
                                        <td class="text-center">{{ number_format($total_abv, 2) }}</td>
                                        <td class="text-center">{{ $total_tgt_value }}</td>
                                        <td class="text-center">{{ number_format($total_ach, 2) }}%</td>
                                        <td class="text-center">{{ $total_tgt_qty }}</td>
                                        <td class="text-center">{{ $total_w_ftd }}</td>
                                        <td class="text-center">{{ $total_w_mtd }}</td>
                                        <td class="text-center">{{ $total_w_ly }}</td>
                                        <td class="text-center">{{ number_format($w_l2l_calc, 2) }}%</td>
                                        <td class="text-center">{{ $total_l_ftd_calc }}</td>
                                        <td class="text-center">{{ $total_l_mtd_calc }}</td>
                                        <td class="text-center">{{ number_format($total_conv, 2) }}%</td>
                                        <td></td>
                                </tfoot>
                            @endif

                            @if (auth()->user()->role_id == 12)
                                <tbody>
                                    @php
                                        $total_b_ftd = 0;
                                        $total_b_mtd = 0;
                                        $total_b_ly = 0;
                                        $total_b_l2l = 0;
                                        $b_l2l_calc = 0;
                                        $total_q_ftd = 0;
                                        $total_q_mtd = 0;
                                        $total_q_ly = 0;
                                        $total_q_l2l = 0;
                                        $q_l2l_calc = 0;
                                        $total_v_ftd = 0;
                                        $total_v_mtd = 0;
                                        $total_v_ly = 0;
                                        $total_v_l2l = 0;
                                        $v_l2l_calc = 0;
                                        $total_k_ftd = 0;
                                        $total_k_mtd = 0;
                                        $total_k_ly = 0;
                                        $total_k_l2l = 0;
                                        $k_l2l_calc = 0;
                                        $total_abs = 0;
                                        $total_abv = 0;
                                        $total_tgt_value = 0;
                                        $total_ach = 0;
                                        $total_tgt_qty = 0;
                                        $total_w_ftd = 0;
                                        $total_w_mtd = 0;
                                        $total_w_ly = 0;
                                        $total_w_l2l = 0;
                                        $w_l2l_calc = 0;
                                        $total_l_ftd = 0;
                                        $total_l_ftd_calc = 0;
                                        $total_l_mtd = 0;
                                        $total_l_mtd_calc = 0;
                                        $total_conv = 0;
                                    @endphp

                                    @foreach ($list as $li)
                                        <tr>
                                            <td>{{ $li->username }}</td>
                                            <td>{{ $li->b_ftd }}</td>
                                            <td>{{ $li->b_mtd }}</td>
                                            <td>--</td>
                                            <td>--</td>
                                            <td>{{ $li->q_ftd }}</td>
                                            <td>{{ $li->q_mtd }}</td>
                                            <td>--</td>
                                            <td>--</td>
                                            <td>{{ $li->v_ftd }}</td>
                                            <td>{{ $li->v_mtd }}</td>
                                            <td>{{ $li->v_ly }}</td>
                                            <td>-</td>
                                            <!--<td>{{ $li->k_ftd }}</td>-->
                                            <!--<td>{{ $li->k_mtd }}</td>-->
                                            <!--<td>{{ $li->k_lymtd }}</td>-->
                                            <!--<td>{{ number_format($li->k_ltl, 2) }}</td>-->
                                            <td>{{ number_format($li->abs, 2) }}</td>
                                            <td>{{ number_format($li->abv, 2) }}</td>
                                            <td>{{ $li->tgt_value }}</td>
                                            <td>{{ number_format($li->ach_per, 2) . '%' }}</td>
                                            <td>{{ $li->tgt_qty }}</td>
                                            <td>{{ $li->w_ftd }}</td>
                                            <td>{{ $li->w_mtd }}</td>
                                            <td>--</td>
                                            <td>--</td>
                                            <td>{{ $li->los_ftd }}</td>
                                            <td>{{ $li->los_mtd }}</td>
                                            <td>{{ number_format($li->conversion, 2) . '%' }}</td>
                                            <td>{{ date('d-m-Y', strtotime($li->created_at)) }}</td>
                                        </tr>
                                        @php

                                            $total_b_ftd += $li->b_ftd;
                                            $total_b_mtd += $li->b_mtd;
                                            $total_b_ly += $li->b_ly;
                                            $total_b_l2l += $li->b_ltl;
                                            $b_l2l_calc = $ly_d->ly_bill != 0 ? ($total_b_mtd / $ly_d->ly_bill - 1) * 100 : 0;
                                            $total_q_ftd += $li->q_ftd;
                                            $total_q_mtd += $li->q_mtd;
                                            $total_q_ly += $li->q_ly;
                                            $total_q_l2l += $li->q_ltl;
                                            $q_l2l_calc = $ly_d->ly_qty != 0 ? ($total_q_mtd / $ly_d->ly_qty - 1) * 100 : 0;
                                            $total_v_ftd += $li->v_ftd;
                                            $total_v_mtd += $li->v_mtd;
                                            $total_v_ly += $li->v_ly;
                                            $total_v_l2l += $li->v_ltl;
                                            $total_k_ftd += $li->k_ftd;
                                            $total_k_mtd += $li->k_mtd;
                                            $total_k_ly += $li->k_lymtd;
                                            $total_k_l2l += $li->k_ltl;
                                            $total_abs = $total_b_mtd != 0 ? $total_q_mtd / $total_b_mtd : 0;
                                            $total_abv = $total_b_mtd != 0 ? $total_v_mtd / $total_b_mtd : 0;
                                            $total_tgt_value += $li->tgt_value;
                                            $total_ach = $total_tgt_value != 0 ? ($total_v_mtd / $total_tgt_value) * 100 : 0;
                                            $total_tgt_qty += $li->tgt_qty;
                                            $total_w_ftd += $li->w_ftd;
                                            $total_w_mtd += $li->w_mtd;
                                            $total_w_ly += $li->w_ly;
                                            $total_w_l2l += $li->w_ltl;
                                            // $w_l2l_calc = $ly_d->ly_walk != 0 ? ($total_w_mtd / $ly_d->ly_walk - 1) * 100 : 0;
                                            $w_l2l_calc = (float) $ly_d->ly_walk != 0 ? ((float) $total_w_mtd / (float) $ly_d->ly_walk - 1) * 100 : 0;

                                            $total_l_ftd += $li->los_ftd;
                                            $total_l_ftd_calc = $total_w_ftd - $total_b_ftd;
                                            $total_l_mtd += $li->los_mtd;
                                            $total_l_mtd_calc = $total_w_mtd - $total_b_mtd;
                                            $total_conv = $total_w_mtd != 0 ? ($total_b_mtd / $total_w_mtd) * 100 : 0;
                                        @endphp
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td>Total</td>
                                        <td class="text-center">{{ $total_b_ftd }}</td>
                                        <td class="text-center">{{ $total_b_mtd }}</td>
                                        <td class="text-center">{{ $ly_d->ly_bill }}</td>
                                        <td class="text-center">{{ number_format($b_l2l_calc, 2) }}%</td>
                                        <td class="text-center">{{ $total_q_ftd }}</td>
                                        <td class="text-center">{{ $total_q_mtd }}</td>
                                        <td class="text-center">{{ $ly_d->ly_qty }}</td>
                                        <td class="text-center">{{ number_format($q_l2l_calc, 2) }}%</td>
                                        <td class="text-center">{{ $total_v_ftd }}</td>
                                        <td class="text-center">{{ $total_v_mtd }}</td>
                                        <td class="text-center">{{ $total_v_ly }}</td>
                                        <td class="text-center">{{ number_format($total_v_l2l, 2) }}%</td>
                                        <!--<td class="text-center">{{ $total_k_ftd }}</td>-->
                                        <!--<td class="text-center">{{ $total_k_mtd }}</td>-->
                                        <!--<td class="text-center">{{ $total_k_ly }}</td>-->
                                        <!--<td class="text-center">{{ number_format($total_k_l2l, 2) }}%</td>-->
                                        <td class="text-center">{{ number_format($total_abs, 2) }}</td>
                                        <td class="text-center">{{ number_format($total_abv, 2) }}</td>
                                        {{-- <td></td> --}}
                                        <td class="text-center">{{ $total_tgt_value }}</td>
                                        <td class="text-center">{{ number_format($total_ach, 2) }}%</td>
                                        {{-- <td></td> --}}
                                        <td class="text-center">{{ $total_tgt_qty }}</td>
                                        <td class="text-center">{{ $total_w_ftd }}</td>
                                        <td class="text-center">{{ $total_w_mtd }}</td>
                                        <td class="text-center">{{ $ly_d->ly_walk }}</td>
                                        <td class="text-center">{{ number_format($w_l2l_calc, 2) }}%</td>
                                        <td class="text-center">{{ $total_l_ftd_calc }}</td>
                                        <td class="text-center">{{ $total_l_mtd_calc }}</td>
                                        <td class="text-center">{{ number_format($total_conv, 2) }}%</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    <div class="scroll-track" id="scroll-track">
                        <div class="scroll-thumb" id="scroll-thumb"></div>
                    </div>
                </div>
            @endif
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- DataTables + Buttons -->
        <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
        {{-- <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet"> --}}

        {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>/ --}}
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

        {{-- scroll thumb --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const table = document.getElementById("scrollable-table");
                const track = document.getElementById("scroll-track");
                const thumb = document.getElementById("scroll-thumb");

                const updateThumb = () => {
                    const scrollWidth = table.scrollWidth;
                    const clientWidth = table.clientWidth;
                    const scrollLeft = table.scrollLeft;

                    const thumbWidth = Math.max((clientWidth / scrollWidth) * track.offsetWidth, 50);
                    thumb.style.width = `${thumbWidth}px`;
                    thumb.style.left = `${(scrollLeft / (scrollWidth - clientWidth)) * (track.offsetWidth - thumbWidth)}px`;
                };

                table.addEventListener("scroll", updateThumb);
                window.addEventListener("resize", updateThumb);
                updateThumb();

                let isDragging = false;
                let startX, startLeft;

                thumb.addEventListener("mousedown", (e) => {
                    isDragging = true;
                    startX = e.clientX;
                    startLeft = parseFloat(thumb.style.left) || 0;
                    thumb.style.cursor = "grabbing";
                });

                document.addEventListener("mousemove", (e) => {
                    if (!isDragging) return;
                    const dx = e.clientX - startX;
                    const newLeft = Math.min(Math.max(startLeft + dx, 0), track.offsetWidth - thumb.offsetWidth);
                    thumb.style.left = `${newLeft}px`;
                    const scrollRatio = newLeft / (track.offsetWidth - thumb.offsetWidth);
                    table.scrollLeft = scrollRatio * (table.scrollWidth - table.clientWidth);
                });

                document.addEventListener("mouseup", () => {
                    isDragging = false;
                    thumb.style.cursor = "grab";
                });
            });
        </script>

        <script>
            // $(document).ready(function() {
            //     const table = $('#example').DataTable({
            //         dom: 'Bfrtip',
            //         ordering: false, // ✅ Disable sorting
            //         buttons: [{
            //                 extend: 'excelHtml5',
            //                 title: 'DSR-Rental Report',
            //                 footer: true,
            //                 exportOptions: {
            //                     columns: ':visible'
            //                 }
            //             },
            //             {
            //                 extend: 'print',
            //                 title: 'DSR-Rental Report',
            //                 footer: true,
            //                 exportOptions: {
            //                     columns: ':visible'
            //                 }
            //             }
            //         ]
            //     });

            //     // ✅ Removed custom column filter code (filterSet, headerDropdown)

            //     // Search input
            //     $('#customSearch').on('keyup', function() {
            //         table.search(this.value).draw();
            //     });

            //     // Export triggers
            //     $('#excelBtn').on('click', function() {
            //         table.button(0).trigger();
            //     });

            //     $('#printBtn').on('click', function() {
            //         table.button(1).trigger();
            //     });
            // });
            $(document).ready(function() {

                const flattenedHeaders = [
                    'Store/Employee Name',
                    'Bill-Ftd', 'Bill-Mtd', 'Bill-LY', 'Bill-LTL',
                    'Qty-Ftd', 'Qty-Mtd', 'Qty-LY', 'Qty-LTL',
                    'Value-Ftd', 'Value-Mtd', 'Value-LY', 'Value-LTL',
                    'ABS', 'ABV',
                    'Tgt-Value', 'Ach %', 'Tgt-Qty',
                    'Walkin-Ftd', 'Walkin-Mtd', 'Walkin-LY', 'Walkin-LTL',
                    'Loss-Ftd', 'Loss-Mtd',
                    'Conversion',
                    'Created On'
                ];

                const table = $('#example').DataTable({
                    dom: 'Bfrtip',
                    ordering: false,
                    buttons: [{
                            extend: 'excelHtml5',
                            title: 'DSR-Rental Report',
                            footer: true,
                            exportOptions: {
                                columns: ':visible', // all visible columns
                                format: {
                                    header: function(data, columnIdx) {
                                        return flattenedHeaders[columnIdx] || data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'print',
                            title: 'DSR-Rental Report',
                            footer: true,
                            exportOptions: {
                                columns: ':visible',
                                format: {
                                    header: function(data, columnIdx) {
                                        return flattenedHeaders[columnIdx] || data;
                                    }
                                }
                            }
                        }
                    ]
                });

                // Custom search
                $('#customSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Export triggers
                $('#excelBtn').on('click', function() {
                    table.button(0).trigger();
                });

                $('#printBtn').on('click', function() {
                    table.button(1).trigger();
                });

            });
        </script>

        <script>
            $(document).ready(function() {
                const userRoleId = {{ auth()->user()->role_id }};
                const userStoreId = {{ auth()->user()->store_id ?? 'null' }};

                // ✅ Load employees dynamically based on store
                function loadEmployees(storeId) {
                    if (!storeId) return;

                    $.ajax({
                        url: "{{ route('get.employees.by.store') }}",
                        type: "GET",
                        data: {
                            store_id: storeId
                        },
                        success: function(data) {
                            const $dropdown = $('#employeeDropdown');
                            $dropdown.find('li:not(:first)').remove(); // keep only "Select All"

                            if (data.length > 0) {
                                data.forEach(emp => {
                                    $dropdown.append(`
                            <li>
                                <label class="d-flex align-items-center">
                                    <input type="checkbox" class="employee-checkbox me-2" name="employee_list[]" value="${emp.id}">
                                    ${emp.name}
                                </label>
                            </li>
                        `);
                                });
                            } else {
                                $dropdown.append('<li><span class="text-muted">No employees found</span></li>');
                            }

                            updateEmployeeButtonText();
                        }
                    });
                }

                // ✅ Handle store checkbox change
                $(document).on('change', '.store-checkbox', function() {
                    const checkedStores = $('.store-checkbox:checked').map(function() {
                        return $(this).val();
                    }).get();

                    if (checkedStores.length === 1) {
                        loadEmployees(checkedStores[0]);
                    } else {
                        $('#employeeDropdown').find('li:not(:first)').remove();
                        $('#employeeDropdownBtn').text('Select Employee');
                    }
                });

                // ✅ Auto-load for role_id = 12
                if (userRoleId === 12 && userStoreId) {
                    loadEmployees(userStoreId);

                    // Auto-check the user's store
                    const $storeCheckbox = $(`.store-checkbox[value="${userStoreId}"]`);
                    if ($storeCheckbox.length) {
                        $storeCheckbox.prop('checked', true);
                        $('#storeDropdownBtn').text($storeCheckbox.closest('label').text().trim());
                    }
                }

                // ✅ Handle "Select All" checkbox
                $(document).on('change', '#selectAllEmployees', function() {
                    const isChecked = $(this).is(':checked');
                    $('.employee-checkbox').prop('checked', isChecked);
                    updateEmployeeButtonText();
                });

                // ✅ When individual employee checkbox changes
                $(document).on('change', '.employee-checkbox', function() {
                    const allChecked = $('.employee-checkbox').length === $('.employee-checkbox:checked').length;
                    $('#selectAllEmployees').prop('checked', allChecked);
                    updateEmployeeButtonText();
                });

                // ✅ Update dropdown button text dynamically
                function updateEmployeeButtonText() {
                    const selected = $('.employee-checkbox:checked');
                    const total = $('.employee-checkbox').length;

                    if (selected.length === 0) {
                        $('#employeeDropdownBtn').text('Select Employee');
                    } else if (selected.length === total) {
                        $('#employeeDropdownBtn').text('All Employees Selected');
                    } else {
                        $('#employeeDropdownBtn').text(selected.length + ' Selected');
                    }

                    // Update hidden required field
                    $('#employeeRequired').val(selected.length ? 'valid' : '');
                }

                // ✅ Store selection validation
                const checkboxes = document.querySelectorAll(".store-checkbox");
                const hiddenRequired = document.getElementById("storeRequired");

                function validateStoreSelection() {
                    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                    hiddenRequired.value = anyChecked ? "valid" : "";
                }

                checkboxes.forEach(cb => cb.addEventListener("change", validateStoreSelection));
                validateStoreSelection();
            });
        </script>

    @endsection
