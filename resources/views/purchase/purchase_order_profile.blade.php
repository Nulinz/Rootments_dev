@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
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
    </style>
    <div class="sidebodydiv mb-4 px-5">
        <div class="sidebodyback my-3" onclick="goBack()">
            <div class="backhead">
                <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
                <h6 class="m-0">Purchase Order Profile</h6>
            </div>
        </div>

        <div class="mainbdy">

            <!-- Left Content -->
            <div class="contentleft">
                <div class="cards mt-2">
                    <div class="basicdetails mb-2">
                        <div class="maincard row">
                            <div class="cardshead">
                                <div class="col-12 cardsh5">
                                    <h5 class="mb-0">Basic Details</h5>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Vendor Code</h6>
                                    <h5 class="mb-0">{{ $po_view->po_id }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Vendor Name</h6>
                                    <h5 class="mb-0">{{ $po_view->ven_name }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Contact Number</h6>
                                    <h5 class="mb-0">{{ $po_view->contact }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Address</h6>
                                    <h5 class="mb-0">{{ $po_view->address }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Address</h6>
                                    <h5 class="mb-0">{{ date('d-m-Y', strtotime($po_view->date)) }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Address</h6>
                                    <h5 class="mb-0">{{ date('d-m-Y', strtotime($po_view->delivery_date)) }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Address</h6>
                                    <h5 class="mb-0">{{ date('d-m-Y', strtotime($po_view->advance_payment_date)) }}</h5>
                                </div>
                                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                                    <h6 class="mb-1">Balance Payment</h6>
                                    <h5 class="mb-0">{{ date('d-m-Y', strtotime($po_view->balance_payment_date)) }}</h5>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content -->
            <div class="contentright">
                <div class="empdetails">
                    <div class="cards mt-2">

                        <div class="profdetails mb-2">
                            <div class="maincard row">
                                <div class="cardshead">
                                    <div class="col-12 cardsh5">
                                        <h5 class="mb-0">Item Details</h5>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Opening Balance</h6>
                                        <h5 class="mb-0">{{ $po_view->opening_balance }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Balance Type</h6>
                                        <h5 class="mb-0">
                                            @if ($po_view->balance_type == 'to_pay')
                                                To Pay
                                            @else
                                                To Receive
                                            @endif
                                        </h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">GSTIN No</h6>
                                        <h5 class="mb-0">{{ $po_view->gstin_no }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">PAN Number</h6>
                                        <h5 class="mb-0">{{ $po_view->pan_number }}</h5>
                                    </div>

                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Permanent Address</h6>
                                        <h5 class="mb-0">{{ $po_view->permanent_address }}</h5>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-xl-3 mb-3">
                                        <h6 class="mb-1">Shipping Address</h6>
                                        @if ($po_view->permanent_address == $po_view->shipping_address)
                                            <h5 class="mb-0">Same Address </h5>
                                        @else
                                            <h5 class="mb-0">{{ $po_view->shipping_address }}</h5>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="table-wrapper">
                                    <table id="example" class="table-hover table-striped table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Product code</th>
                                                <th>Product</th>
                                                <th>Color</th>
                                                <th>Quantity</th>
                                                <th>Sell Price</th>
                                                <th>Purchse Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($po_item as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->product_code }}</td>
                                                    <td>{{ $item->product }}</td>
                                                    <td>{{ $item->color }}</td>
                                                    <td>{{ $item->qty }}</td>
                                                    <td>{{ $item->selling_price }}</td>
                                                    <td>{{ $item->purchase_price }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>Total: {{ $po_view->overall_total }}</td>

                                                <td>{{ $po_view->total_qty }}</td>
                                                <td>{{ $po_view->total_sel }}</td>
                                                <td>{{ $po_view->total_pur }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function() {
            const table = $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        title: 'Walk-In Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        title: 'Walk-In Report',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });

            // Add table headers as filter options (excluding index column)
            $('#example thead th').each(function(index) {
                const title = $(this).text().trim();
                if (index > 0) { // Skip the first "#" column
                    $('#columnFilter').append(`<option value="${index}">${title}</option>`);
                }
            });

            // Filter rows on dropdown change
            $('#columnFilter').on('change', function() {
                const selectedIndex = $(this).val();

                if (selectedIndex === "All") {
                    table.rows().every(function() {
                        $(this.node()).show(); // Show all rows
                    });
                } else {
                    table.rows().every(function() {
                        const rowData = this.data();
                        const value = rowData[selectedIndex];
                        const shouldShow = value && value.trim() !== '';
                        $(this.node()).toggle(shouldShow);
                    });
                }
            });

            // Search input (optional)
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Export buttons
            $('#excelBtn').on('click', function() {
                table.button(0).trigger();
            });

            $('#printBtn').on('click', function() {
                table.button(1).trigger();
            });
        });
    </script>
@endsection
