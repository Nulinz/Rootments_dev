@extends ('layouts.app')

@section('content')

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Work Update Report</h4>
        </div>
        {{-- @if(request()->isMethod('get')) --}}
        <form action="{{ route('daily.work') }}" method="POST">
            @csrf   
            <div class="container-fluid maindiv bg-white my-3">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" name="date" id="date" value="{{ date("Y-m-d") }}">
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                        <label for="store">Store</label>
                        <select class="form-select" name="store" id="store">
                            <option value="" selected disabled>Select Store</option>
                            @foreach ($stores as $st)
                                 <option value="{{ $st->id }}">{{ $st->store_name }}-{{ $st->store_code}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-12 col-xl-12 mt-3 d-flex justify-content-center align-items-center">
                <button type="submit" class="formbtn">Save</button>
            </div>
        </form>
        {{-- @endif --}}
        @if(request()->isMethod('post'))
        <div class="container-fluid mt-4 listtable">
            <div class="table-wrapper">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th style="width: 100px">Type</th>
                            <th>FTD</th>
                            <th>MTD</th>
                            <th>LY MTD</th>
                            <th>L2L</th>
                            <th>TGT</th>
                            <th>ACH %</th>
                            <th>TGT %</th>
                            <th>ABS</th>
                            <th>CON %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $li)
                        <tr>
                            <td>Bills</td>
                            <td>{{ $li->b_ftd }}</td>
                            <td>{{ $li->b_mtd }}</td>
                            <td>{{ $li->b_ly }}</td>
                            <td>{{ $li->b_ltl }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Quantity</td>
                            <td>{{ $li->q_ftd }}</td>
                            <td>{{ $li->q_mtd }}</td>
                            <td>{{ $li->q_ly }}</td>
                            <td>{{ $li->q_ltl }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Walk-In</td>
                            <td>{{ $li->w_ftd }}</td>
                            <td>{{ $li->w_mtd }}</td>
                            <td>{{ $li->w_ly }}</td>
                            <td>{{ $li->w_ltl }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Loss Of Sales</td>
                            <td>{{ $li->los_ftd }}</td>
                            <td>{{ $li->los_mtd }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ $li->los_abs }}</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>ABS</td>
                            <td>{{ $li->abs_ftd }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ $li->abs_tgt }}</td>
                            <td>{{ $li->abs_ach }}</td>
                            <td>{{ $li->abs_per }}</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Conversion</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>{{ $li->con_per }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

@endsection
