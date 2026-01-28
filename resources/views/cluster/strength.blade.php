@extends ('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_strength.css') }}">

    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Cluster Store Strength</h4>
        </div>

        <!-- Cluster Tabs -->
        @include('generaldashboard.tabs')

        <div class="container px-0 mt-3 listtable">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Stores</th>
                            <th class="thdark">Store Manager</th>
                            <th class="thdark">Asst. Store Manager</th>
                            <th class="thdark">Senior Sales Associate</th>
                            <th class="thdark">Fashion Stylist</th>
                            <th class="thdark">Fashion Consultant</th>
                            <th class="thdark">Quality Control</th>
                            <th class="thdark">Cleaning Staff</th>
                            <th class="thdark">Tailor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($store_list as $store)

                        <tr>
                            <td style="font-size: 13px;">{{ $store['st_name'] }}</td>
                            @foreach ($store['roles'] as $st)
                            <td>{{$st['emp_count']}} / <span class="{{ $st['emp_count'] < $st['req_count'] ? 'red' : 'green' }}">{{$st['req_count']}}</span></td>
                          @endforeach
                        </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>

    </div>

@endsection
