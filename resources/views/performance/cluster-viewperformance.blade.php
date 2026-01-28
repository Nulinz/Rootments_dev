@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
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
            margin: 0px 0px;
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
            /* yellow stars */
        }

        .star-size {
            font-size: 1.5rem !important;
        }

        .table> :not(caption)>*>* {
            background-color: white !important;
        }

        .fs-title {
            font-size: 14px !important;
            font-weight: 600;
        }

        tbody tr td {
            font-size: 14px !important;
        }
    </style>
    <div class="sidebodydiv mb-4 px-5">
        <div class="sidebodyback my-3" onclick="goBack()">
            <div class="backhead">
                <h5 class="m-0"><i class="fas fa-arrow-left"></i></h5>
                <h6 class="m-0">Performance List</h6>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table-striped table">
                <thead>
                    <tr>
                        <th>Metric</th>
                        <th>Current</th>
                        <th>LY MTD</th>
                        <th>L2L</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fs-title">Total Walk-ins</td>
                        <td>{{ $clu_view->total_walkins }}</td>
                        <td>{{ $clu_view->ly_mtd_walkins }}</td>
                        <td>{{ $clu_view->l2l_walkins }}</td>
                    </tr>
                    <tr>
                        <td class="fs-title">Total Bills</td>
                        <td>{{ $clu_view->total_bills }}</td>
                        <td>{{ $clu_view->ly_mtd_bills }}</td>
                        <td>{{ $clu_view->l2l_bills }}</td>
                    </tr>
                    <tr>
                        <td class="fs-title">Total Quantity</td>
                        <td>{{ $clu_view->total_quantity }}</td>
                        <td>{{ $clu_view->ly_mtd_quantity }}</td>
                        <td>{{ $clu_view->l2l_quantity }}</td>
                    </tr>
                    <tr>
                        <td class="fs-title">ABS</td>
                        <td>{{ $clu_view->abs }}</td>
                        <td>{{ $clu_view->ly_mtd_abs }}</td>
                        <td>{{ $clu_view->l2l_abs }}</td>
                    </tr>
                    <tr>
                        <td class="fs-title">KPI Points</td>
                        <td>{{ $clu_view->kpi_points }}</td>
                        <td>{{ $clu_view->ly_mtd_kpi }}</td>
                        <td>{{ $clu_view->l2l_kpi }}</td>
                    </tr>
                    <tr>
                        <td class="fs-title">Conversion %</td>
                        <td>{{ $clu_view->conversion_percent }}</td>
                        <td>{{ $clu_view->ly_mtd_conversion }}</td>
                        <td>{{ $clu_view->l2l_conversion }}</td>
                    </tr>
                    <tr>
                        <td class="fs-title">Target</td>
                        <td>{{ $clu_view->tgt }}</td>
                        <td>{{ $clu_view->tgt_achievement_percent }}</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td class="fs-title">Contribution</td>
                        <td>{{ $clu_view->contribution }}</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td class="fs-title">Total Extra Leaves</td>
                        <td>{{ $clu_view->total_extra_leaves }}</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td class="fs-title">Total Sick Leave</td>
                        <td>{{ $clu_view->total_sick_leaves }}</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <h6 class="my-3">Manager-Rated Metrics</h6>

        <div class="table-wrapper">
            <table class="table-hover table-striped table">
                <thead>
                    <tr>
                        <th>Parameter</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fs-title">Customer Relation</td>
                        <td>
                            <div class="star-rating" style="font-size: 1.5rem; color: gold;">
                                @php $rating = $clu_view->customer_relation ?? 0; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="star-size">{{ $i <= $rating ? '★' : '☆' }}</label>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-title">Team Management</td>
                        <td>
                            <div class="star-rating" style="font-size: 1.5rem; color: gold;">
                                @php $rating = $clu_view->team_management ?? 0; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="star-size">{{ $i <= $rating ? '★' : '☆' }}</label>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-title">Google Review</td>
                        <td>
                            <div class="star-rating" style="font-size: 1.5rem; color: gold;">
                                @php $rating = $clu_view->google_review ?? 0; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="star-size">{{ $i <= $rating ? '★' : '☆' }}</label>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-title">Training Completion</td>
                        <td>
                            <div class="star-rating" style="font-size: 1.5rem; color: gold;">
                                @php $rating = $clu_view->training_completion ?? 0; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="star-size">{{ $i <= $rating ? '★' : '☆' }}</label>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-title">Damage Control</td>
                        <td>
                            <div class="star-rating" style="font-size: 1.5rem; color: gold;">
                                @php $rating = $clu_view->damage_control ?? 0; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="star-size">{{ $i <= $rating ? '★' : '☆' }}</label>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-title">Product Quality</td>
                        <td>
                            <div class="star-rating" style="font-size: 1.5rem; color: gold;">
                                @php $rating = $clu_view->product_quality ?? 0; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="star-size">{{ $i <= $rating ? '★' : '☆' }}</label>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-title">Staff Training</td>
                        <td>
                            <div class="star-rating" style="font-size: 1.5rem; color: gold;">
                                @php $rating = $clu_view->staff_training ?? 0; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="star-size">{{ $i <= $rating ? '★' : '☆' }}</label>
                                @endfor
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="fs-title">Daily Photos</td>
                        <td>
                            <div class="star-rating" style="font-size: 1.5rem; color: gold;">
                                @php $rating = $clu_view->daily_photos ?? 0; @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="star-size">{{ $i <= $rating ? '★' : '☆' }}</label>
                                @endfor
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>

    </div>
@endsection
