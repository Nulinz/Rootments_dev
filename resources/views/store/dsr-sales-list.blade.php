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

        .table-wrapper {
            overflow-x: auto;
        }
    </style>

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">DSR-Sale List</h4>
            @if ($count == 0)
                <a href="{{ route('dsr.sales.create') }}"><button class="listbtn">+ Add Work Update</button></a>
            @endif
        </div>

        <div class="container-fluid listtable mt-4">
            {{-- <form action="{{ route('store.workupdatelist') }}" method="POST">
                @csrf --}}
            <div class="table-wrapper">
                <table id="example" class="table-hover table-striped mb-0 table">
                    <thead>
                        <tr>
                            <th rowspan="3" style="vertical-align: middle; text-align: start; border-right: 1px solid #888;">Employee Name</th>
                            <th colspan="6" style="border-right: 1px solid #888;">Shoe</th>
                            <th colspan="6" style="border-right: 1px solid #888;">Shirt</th>
                            <th rowspan="3" style="vertical-align: middle; text-align: start;">Created On</th>
                        </tr>
                        <tr>
                            <th colspan="2" style="border-right: 1px solid #888;">Bill</th>
                            <th colspan="2" style="border-right: 1px solid #888;">Qty</th>
                            <th style="border-right: 1px solid #888;">TGT</th>
                            <th style="border-right: 1px solid #888;">ACH %</th>
                            <th colspan="2" style="border-right: 1px solid #888;">Bill</th>
                            <th colspan="2" style="border-right: 1px solid #888;">Qty</th>
                            <th style="border-right: 1px solid #888;">TGT</th>
                            <th style="border-right: 1px solid #888;">ACH %</th>
                        </tr>
                        <tr>
                            <th style="border-right: 1px solid #888;">Ftd</th>
                            <th style="border-right: 1px solid #888;">Mtd</th>
                            <th style="border-right: 1px solid #888;">Ftd</th>
                            <th style="border-right: 1px solid #888;">Mtd</th>
                            <th style="border-right: 1px solid #888;">-</th>
                            <th style="border-right: 1px solid #888;">-</th>
                            <th style="border-right: 1px solid #888;">Ftd</th>
                            <th style="border-right: 1px solid #888;">Mtd</th>
                            <th style="border-right: 1px solid #888;">Ftd</th>
                            <th style="border-right: 1px solid #888;">Mtd</th>
                            <th style="border-right: 1px solid #888;">-</th>
                            <th style="border-right: 1px solid #888;">-</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_shoe_b_ftd = 0;
                            $total_shoe_mtd = 0;
                            $total_shoe_q_ftd = 0;
                            $total_shoe_q_mtd = 0;
                            $total_shoe_tgt = 0;
                            $total_shoe_ach = 0;

                            $total_shirt_b_ftd = 0;
                            $total_shirt_mtd = 0;
                            $total_shirt_q_ftd = 0;
                            $total_shirt_q_mtd = 0;
                            $total_shirt_tgt = 0;
                            $total_shirt_ach = 0;

                        @endphp
                        @foreach ($list as $li)
                            <tr>
                                <td>{{ $li->username }}</td>
                                <td>{{ $li->shoe_bill_ftd }}</td>
                                <td>{{ $li->shoe_bill_mtd }}</td>
                                <td>{{ $li->shoe_qty_ftd }}</td>
                                <td>{{ $li->shoe_qty_mtd }}</td>
                                <td>{{ $li->shoe_tgt }}</td>
                                <td>{{ number_format($li->shoe_ach, 2) }}%</td>
                                <td>{{ $li->shirt_bill_ftd }}</td>
                                <td>{{ $li->shirt_bill_mtd }}</td>
                                <td>{{ $li->shirt_qty_ftd }}</td>
                                <td>{{ $li->shirt_qty_mtd }}</td>
                                <td>{{ $li->shirt_tgt }}</td>
                                <td>{{ number_format($li->shirt_ach, 2) }}%</td>
                                <td>{{ date('d-m-Y', strtotime($li->created_at)) }}</td>
                            </tr>
                            @php
                                $total_shoe_b_ftd += $li->shoe_bill_ftd;
                                $total_shoe_mtd += $li->shoe_bill_mtd;
                                $total_shoe_q_ftd += $li->shoe_qty_ftd;
                                $total_shoe_q_mtd += $li->shoe_qty_mtd;
                                $total_shoe_tgt += $li->shoe_tgt;
                                // $total_shoe_ach += $li->shoe_ach;
                                $total_shoe_ach = $total_shoe_tgt > 0 ? ($total_shoe_q_mtd / $total_shoe_tgt) * 100 : 0;

                                $total_shirt_b_ftd += $li->shirt_bill_ftd;
                                $total_shirt_mtd += $li->shirt_bill_mtd;
                                $total_shirt_q_ftd += $li->shirt_qty_ftd;
                                $total_shirt_q_mtd += $li->shirt_qty_mtd;
                                $total_shirt_tgt += $li->shirt_tgt;
                                // $total_shirt_ach += $li->shirt_ach;
                                $total_shirt_ach = $total_shirt_tgt > 0 ? ($total_shirt_q_mtd / $total_shirt_tgt) * 100 : 0;
                            @endphp
                        @endforeach
                    </tbody>

                    <tfoot>
                        {{-- <tr>
                            <td>Total</td> --}}
                        {{-- Shoe --}}
                        {{-- <td class="p-0"><input type="text" name="total_shoe_b_ftd" class="form-control text-center" value="{{ $total_shoe_b_ftd }}"></td>
                            <td class="p-0"><input type="text" name="total_shoe_mtd" class="form-control text-center" value="{{ $total_shoe_mtd }}"></td>
                            <td class="p-0"><input type="text" name="total_shoe_q_ftd" class="form-control text-center" value="{{ $total_shoe_q_ftd }}"></td>
                            <td class="p-0"><input type="text" name="total_shoe_q_mtd" class="form-control text-center" value="{{ $total_shoe_q_mtd }}"></td>
                            <td class="p-0"><input type="text" name="total_shoe_tgt" class="form-control text-center" value="{{ $total_shoe_tgt }}"></td>
                            <td class="p-0"><input type="text" name="total_shoe_ach" class="form-control text-center" value="{{ number_format($total_shoe_ach, 2) }}"></td> --}}

                        {{-- Shirt --}}
                        {{-- <td class="p-0"><input type="text" name="total_shirt_b_ftd" class="form-control text-center" value="{{ $total_shirt_b_ftd }}"></td>
                            <td class="p-0"><input type="text" name="total_shirt_mtd" class="form-control text-center" value="{{ $total_shirt_mtd }}"></td>
                            <td class="p-0"><input type="text" name="total_shirt_q_ftd" class="form-control text-center" value="{{ $total_shirt_q_ftd }}"></td>
                            <td class="p-0"><input type="text" name="total_shirt_q_mtd" class="form-control text-center" value="{{ $total_shirt_q_mtd }}"></td>
                            <td class="p-0"><input type="text" name="total_shirt_tgt" class="form-control text-center" value="{{ $total_shirt_tgt }}"></td>
                            <td class="p-0"><input type="text" name="total_shirt_ach" class="form-control text-center" value="{{ number_format($total_shirt_ach, 2) }}">
                            </td>

                            <td></td>
                        </tr> --}}
                        <tr>
                            <td>Total</td>

                            {{-- Shoe --}}
                            <td class="text-center">{{ $total_shoe_b_ftd }}</td>
                            <td class="text-center">{{ $total_shoe_mtd }}</td>
                            <td class="text-center">{{ $total_shoe_q_ftd }}</td>
                            <td class="text-center">{{ $total_shoe_q_mtd }}</td>
                            <td class="text-center">{{ $total_shoe_tgt }}</td>
                            <td class="text-center">{{ number_format($total_shoe_ach, 2) }}</td>

                            {{-- Shirt --}}
                            <td class="text-center">{{ $total_shirt_b_ftd }}</td>
                            <td class="text-center">{{ $total_shirt_mtd }}</td>
                            <td class="text-center">{{ $total_shirt_q_ftd }}</td>
                            <td class="text-center">{{ $total_shirt_q_mtd }}</td>
                            <td class="text-center">{{ $total_shirt_tgt }}</td>
                            <td class="text-center">{{ number_format($total_shirt_ach, 2) }}</td>

                            <td></td>
                        </tr>

                    </tfoot>
                </table>
            </div>
        </div>
        {{-- <div class="row mt-3"> --}}
        {{-- <div class="col text-center"> --}}
        {{-- @if ($count_total == 0) --}}
        {{-- <button type="submit" class="btn bg-dark btn-md text-center text-white">Save</button> --}}
        {{-- @endif --}}
        {{-- </div> --}}
        {{-- </div> --}}
        {{-- </form> --}}
    </div>
@endsection
