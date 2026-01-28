@extends('layouts.app')

@section('content')

    <style>
        .table th {
            text-align: center !important;
        }
    </style>

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Abstract Update List</h4>
        </div>

        <div class="container-fluid mt-4 listtable">
            <div class="table-wrapper">
                <table class="table table-hover table-striped" style="width: 1850px; overflow-x: auto;">
                    <thead>
                        <tr>
                            <th style="border-right: 1px solid #888;">Store</th>

                            <th colspan="3" style="border-right: 1px solid #888;">Bills</th>
                            <th colspan="3" style="border-right: 1px solid #888;">Quantity</th>
                            <th colspan="3" style="border-right: 1px solid #888;">Walk-In</th>
                            <th colspan="3" style="border-right: 1px solid #888;">Loss Of Sale</th>
                            <th colspan="4" style="border-right: 1px solid #888;">ABS</th>
                            <th style="text-align: center;">Conversion</th>
                        </tr>
                        <tr class="tr-div">
                            <th style="width: 450px; border-right: 1px solid #888;"></th>


                            <th style="width: 200px; border-right: 1px solid #888;">MTD</th>
                            <th style="width: 200px; border-right: 1px solid #888;">LY MTD</th>
                            <th style="width: 200px; border-right: 1px solid #888;">L2L</th>

                            <th style="width: 200px; border-right: 1px solid #888;">MTD</th>
                            <th style="width: 200px; border-right: 1px solid #888;">LY MTD</th>
                            <th style="width: 200px; border-right: 1px solid #888;">L2L</th>
                            <th style="width: 200px; border-right: 1px solid #888;">FTD</th>
                            <th style="width: 200px; border-right: 1px solid #888;">MTD</th>
                            <th style="width: 200px; border-right: 1px solid #888;">LY MTD</th>
                            <th style="width: 200px; border-right: 1px solid #888;">L2L</th>
                            <th style="width: 200px; border-right: 1px solid #888;">FTD</th>
                            <th style="width: 200px; border-right: 1px solid #888;">MTD</th>
                            <th style="width: 200px; border-right: 1px solid #888;">ABS</th>
                            <th style="width: 200px; border-right: 1px solid #888;">FTD</th>
                            <th style="width: 200px; border-right: 1px solid #888;">TGT</th>
                            <th style="width: 200px; border-right: 1px solid #888;">ACH %</th>
                            <th style="width: 200px; border-right: 1px solid #888;">TGT %</th>
                            <th style="text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $li)
                        <tr>
                            <td>{{ $li->store_code }} <br> {{ $li->store_name }} </td>

                            <td class="text-end">{{ $li->b_mtd }}</td>
                            <td class="text-end">{{ $li->b_ly }}</td>
                            <td class="text-end">{{ $li->b_ltl }}</td>

                            <td class="text-end">{{ $li->q_mtd }}</td>
                            <td class="text-end">{{ $li->q_ly }}</td>
                            <td class="text-end">{{ $li->q_ltl }}</td>

                            <td class="text-end">{{ $li->w_mtd }}</td>
                            <td class="text-end">{{ $li->w_ly }}</td>
                            <td class="text-end">{{ $li->w_ltl }}</td>
                            <td class="text-end">{{ $li->los_ftd }}</td>
                            <td class="text-end">{{ $li->los_mtd }}</td>
                            <td class="text-end">{{ $li->los_abs }}</td>
                            <td class="text-end">{{ $li->abs_ftd }}</td>
                            <td class="text-end">{{ $li->abs_tgt }}</td>
                            <td class="text-end">{{ $li->abs_ach }}</td>
                            <td class="text-end">{{ $li->abs_per }}</td>
                            <td class="text-end">{{ $li->con_per }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
