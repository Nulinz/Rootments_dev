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
                border: 1px solid #888;
                /* Light border between cells */
                font-size: 14px;
            }

            table thead th {
                background-color: #f4f4f4;
                /* Light gray background for headers */
                font-weight: bold;
            }
        </style>
        <div class="sidebodydiv px-5 py-3">
            <div class="sidebodyhead">
                <h4 class="m-0">DSR-Rental List</h4>

                @if ($count == 0)
                    <a href="{{ route('dsr.rental.create') }}">
                        <button class="listbtn">Add update</button>
                    </a>
                @endif

            </div>

            <div class="container-fluid listtable mt-4">

                <div class="table-wrapper">
                    {{-- <form action="{{ route('store.staff_workupdate_list') }}" method="POST"> --}}
                    @csrf
                    <table id="example" class="table-hover table-striped mb-0 table">
                        <thead style="border-top: 1px solid #888;">
                            <tr>
                                <th rowspan="2" style="vertical-align: middle; text-align: start; border-right: 1px solid #888;">Employee Name</th>
                                <th colspan="4" style="border-right: 1px solid #888;">Bill</th>
                                <th colspan="4" style="border-right: 1px solid #888;">Qty</th>
                                <th colspan="4" style="border-right: 1px solid #888;">Value</th>
                                {{-- <th colspan="4" style="border-right: 1px solid #888;">KPI Points</th> --}}
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
                                {{-- <th style="border-right: 1px solid #888;">Ftd</th>
                                <th style="border-right: 1px solid #888;">Mtd</th>
                                <th style="border-right: 1px solid #888;">LY MTD</th>
                                <th style="border-right: 1px solid #888;">L2L</th> --}}
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
                                    <td>{{ number_format($li->b_ltl, 2) }}</td>
                                    <td>{{ $li->q_ftd }}</td>
                                    <td>{{ $li->q_mtd }}</td>
                                    <td>--</td>
                                    <td>{{ number_format($li->q_ltl, 2) }}</td>
                                    <td>{{ $li->v_ftd }}</td>
                                    <td>{{ $li->v_mtd }}</td>
                                    <td>--</td>
                                    <td>--</td>
                                    {{-- <td>{{ $li->k_ftd }}</td>
                                    <td>{{ $li->k_mtd }}</td>
                                    <td>--</td>
                                    <td>--</td> --}}
                                    <td>{{ number_format($li->abs, 2) }}</td>
                                    <td>{{ number_format($li->abv, 2) }}</td>
                                    <td>{{ $li->tgt_value }}</td>
                                    <td>{{ number_format($li->ach_per, 2) . '%' }}</td>
                                    <td>{{ $li->tgt_qty }}</td>
                                    <td>{{ $li->w_ftd }}</td>
                                    <td>{{ $li->w_mtd }}</td>
                                    <td>--</td>
                                    <td>{{ number_format($li->w_ltl, 2) }}</td>
                                    <td>{{ $li->los_ftd }}</td>
                                    <td>{{ $li->los_mtd }}</td>
                                    <td>{{ number_format($li->conversion, 2) . '%' }}</td>
                                    <td>{{ date('d-m-Y', strtotime($li->created_at)) }}</td>
                                </tr>
                                @php
                                    // Summing up the raw values, not the percentages
                                    $total_b_ftd += $li->b_ftd;
                                    $total_b_mtd += $li->b_mtd;
                                    $total_b_ly += $li->b_ly;
                                    $total_b_l2l += $li->b_ltl;
                                    if ($li->b_ly > 0) {
                                        $b_l2l_calc = ($total_b_mtd / $li->b_ly - 1) * 100;
                                    } else {
                                        $b_l2l_calc = 0;
                                    }
                                    $total_q_ftd += $li->q_ftd;
                                    $total_q_mtd += $li->q_mtd;
                                    $total_q_ly += $li->q_ly;
                                    $total_q_l2l += $li->q_ltl;
                                    if ($ly_d->ly_qty > 0) {
                                        $q_l2l_calc = ($total_q_mtd / $ly_d->ly_qty - 1) * 100;
                                    } else {
                                        $q_l2l_calc = 0;
                                    }
                                    $total_v_ftd += $li->v_ftd;
                                    $total_v_mtd += $li->v_mtd;
                                    $total_k_ftd += $li->k_ftd;
                                    $total_k_mtd += $li->k_mtd;
                                    if ($total_b_mtd > 0) {
                                        $total_abs = $total_q_mtd / $total_b_mtd;
                                    } else {
                                        $total_abs = 0;
                                    }

                                    $total_tgt_value += $li->tgt_value;
                                    if ($total_b_mtd > 0) {
                                        $total_abv = $total_v_mtd / $total_b_mtd;
                                    } else {
                                        $total_abv = 0;
                                    }

                                    if ($total_tgt_value > 0) {
                                        $total_ach = ($total_v_mtd / $total_tgt_value) * 100;
                                    } else {
                                        $total_ach = 0;
                                    }
                                    $total_tgt_qty += $li->tgt_qty;
                                    $total_w_ftd += $li->w_ftd;
                                    $total_w_mtd += $li->w_mtd;
                                    $total_w_ly += $li->w_ly;
                                    $total_w_l2l += $li->w_ltl;
                                    if ($ly_d->ly_walk > 0) {
                                        $w_l2l_calc = ($total_w_mtd / $ly_d->ly_walk - 1) * 100;
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
                        <tr>
                            <td>Total</td>
                            <td class="text-center">{{ $total_b_ftd }}</td>
                            <td class="text-center">{{ $total_b_mtd }}</td>
                            <td class="text-center">{{ $ly_d->ly_bill }}</td>
                            <td class="text-center">{{ number_format($b_l2l_calc, 2) }}</td>
                            <td class="text-center">{{ $total_q_ftd }}</td>
                            <td class="text-center">{{ $total_q_mtd }}</td>
                            <td class="text-center">{{ $ly_d->ly_qty }}</td>
                            <td class="text-center">{{ number_format($q_l2l_calc, 2) }}</td>
                            <td class="text-center">{{ $total_v_ftd }}</td>
                            <td class="text-center">{{ $total_v_mtd }}</td>
                            <td></td>
                            <td></td>
                            {{-- <td class="text-center">{{ $total_k_ftd }}</td>
                            <td class="text-center">{{ $total_k_mtd }}</td>
                            <td></td>
                            <td></td> --}}
                            <td class="text-center">{{ number_format($total_abs, 2) }}</td>
                            <td class="text-center">{{ number_format($total_abv, 2) }}</td>
                            @if (empty(optional($targets)->target) || empty(optional($targets)->target_qty))
                                <script>
                                    $(document).ready(function() {
                                        $('#exampleModalCenter').modal('show');
                                    });
                                </script>
                            @endif
                            <td class="text-center">{{ $total_tgt_value }}/{{ $targets->target ?? 0 }}</td>
                            <td class="text-center">{{ number_format($total_ach, 2) }}</td>
                            <td class="text-center">{{ $total_tgt_qty }}/{{ $targets->target_qty ?? 0 }}</td>
                            <td class="text-center">{{ $total_w_ftd }}</td>
                            <td class="text-center">{{ $total_w_mtd }}</td>
                            <td class="text-center">{{ $ly_d->ly_walk }}</td>
                            <td class="text-center">{{ number_format($w_l2l_calc, 2) }}</td>
                            <td class="text-center">{{ $total_l_ftd_calc }}</td>
                            <td class="text-center">{{ $total_l_mtd_calc }}</td>
                            <td class="text-center">{{ number_format($total_conv, 2) }}</td>
                            <td></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalCenterTitle">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center justify-content-between">
                            <h5 class="modal-title" id="exampleModalLongTitle">Missing Targets</h5>
                            <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            Please update the store target first..
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script>
            // $(document).ready(function() {
            //     @if (empty(optional($targets)->target) || empty(optional($targets)->target_qty))
            //         var modal = new bootstrap.Modal(document.getElementById('exampleModalCenter'));
            //         modal.show();
            //     @endif
            // });
        </script>
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    @endsection
