@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Employee Termination List</h4>

        </div>

        <div class="container-fluid listtable mt-4">
            <div class="filter-container row mb-3">
                <div class="custom-search-container col-sm-12 col-md-8">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                <div class="select1 col-sm-12 col-md-4 mx-auto">
                    <div class="d-flex gap-3">
                        <!--<a href="" id="print" data-bs-toggle="tooltip" data-bs-title="Print"><img src="{{ asset('assets/images/printer.png') }}"-->
                        <!--    id="print" alt="" height="28px"></a>-->
                        <!--<a href="" id="excel" data-bs-toggle="tooltip" data-bs-title="Excel"><img src="{{ asset('assets/images/excel.png') }}" -->
                        <!--    id="excel" alt="" height="30px"></a>-->
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table-hover table-striped table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Code</th>
                            <th>Full Name</th>
                            <th>Store</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Email ID</th>
                            <th>Contact Number</th>
                            <th>Terminated Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->emp_code }}</td>
                                <td>{{ $item->name }}</td>
                                {{-- @if ($item->store_name) --}}
                                <td>{{ $item->store_name ?? '-' }}</td>
                                {{-- @else --}}
                                {{-- <td> - </td>
                            @endif --}}
                                <td>{{ $item->role_dept }}</td>
                                <td>{{ $item->role }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->contact_no }}</td>
                                <td>{{ $item->ter_date ? date('d-m-Y', strtotime($item->ter_date)) : 'No date' }}</td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('employee.view', ['id' => $item->id ?? 0]) }}" data-bs-toggle="tooltip" data-bs-title="View Profile"><i
                                                class="fas fa-eye"></i></a>

                                        @if (in_array(auth()->user()->role_id, [1, 2, 11, 12]))
                                            <a href="{{ route('impersonate.start', $item->id) }}" target="_blank" data-bs-toggle="tooltip" data-bs-title="Login as user">
                                                <i class="fa-solid fa-right-to-bracket"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

                {{-- <div style="margin-top: 20px;"> --}}
                {{-- @if ($employees instanceof \Illuminate\Pagination\CursorPaginator)
                        {{ $employees->links() }}
                    @endif --}}
                {{-- {{ $employees->withQueryString()->links() }} --}}
                {{-- </div> --}}

            </div>
        </div>
    </div>

    <script>
        document.getElementById("print").addEventListener("click", function(e) {
            e.preventDefault();

            var table = document.querySelector(".example");
            var clonedTable = table.cloneNode(true);

            clonedTable.querySelectorAll("tr").forEach(row => {
                if (row.lastElementChild) {
                    row.removeChild(row.lastElementChild);
                }
            });

            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write(`
        <html>
            <head>
                <title>Employee Lists</title>
                <style>
                    table { width: 100%; border-collapse: collapse; }
                    table, th, td { border: 1px solid black; }
                    th, td { padding: 8px; text-align: left; }
                </style>
            </head>
            <body>${clonedTable.outerHTML}</body>
        </html>
    `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        });

        document.getElementById("excel").addEventListener("click", function(e) {
            e.preventDefault();

            var table = document.querySelector(".example");
            var csv = [];
            var rows = table.querySelectorAll("tr");

            rows.forEach(row => {
                var rowData = [];
                var cells = Array.from(row.children);
                cells.slice(0, -1).forEach(cell => {
                    rowData.push('"' + cell.textContent.trim() + '"');
                });
                csv.push(rowData.join(","));
            });

            var csvBlob = new Blob([csv.join("\n")], {
                type: "text/csv"
            });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(csvBlob);
            link.download = "Employee-List.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
@endsection
