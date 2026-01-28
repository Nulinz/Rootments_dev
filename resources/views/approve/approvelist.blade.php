@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">


@section('content')
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">Approval List</h4>
        </div>

        <div class="proftabs">
            <ul class="nav nav-tabs d-flex justify-content-start align-items-center gap-md-3 gap-xl-3 border-0" id="myTab"
                role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="profiletabs active" data-url="{{ route('approveleave.index') }}" id="leave-tab"
                        role="tab" data-bs-toggle="tab" type="button" data-bs-target="#leave" aria-controls="leave"
                        aria-selected="true">Leave</button>
                    <!-- <button class="profiletabs active" data-url="{{ route('approveleave.index') }}" id="leave-tab"-->
                    <!--role="tab" data-bs-toggle="tab" type="button" data-bs-target="#leave" aria-controls="leave"-->
                    <!--aria-selected="true">Leave &nbsp;<span class="aprvlcnt">{{ $leave_count }}</span></button>-->
                </li>
                {{-- <li class="nav-item" role="presentation">
                    <button class="profiletabs" id="transfer-tab" data-url="{{ route('approvetransfer.index') }}" role="tab"
                        data-bs-toggle="tab" type="button" data-bs-target="#transfer" aria-controls="transfer"
                        aria-selected="false">Transfer &nbsp;</button>
                    <!--    <button class="profiletabs" id="transfer-tab" data-url="{{ route('approvetransfer.index') }}"-->
                    <!--role="tab" data-bs-toggle="tab" type="button" data-bs-target="#transfer"-->
                    <!--aria-controls="transfer" aria-selected="false">Transfer &nbsp;<span-->
                    <!--    class="aprvlcnt">{{ $transfer_count }}</span></button>-->
                </li> --}}
                <li class="nav-item" role="presentation">
                    <button class="profiletabs" id="resign-tab" data-url="{{ route('approveresgin.index') }}" role="tab"
                        data-bs-toggle="tab" type="button" data-bs-target="#resign" aria-controls="resign"
                        aria-selected="false">Resign</button>
                    <!--<button class="profiletabs" id="resign-tab" data-url="{{ route('approveresgin.index') }}" role="tab"-->
                    <!--data-bs-toggle="tab" type="button" data-bs-target="#resign" aria-controls="resign"-->
                    <!--aria-selected="false">Resign &nbsp;<span class="aprvlcnt">{{ $resign_count }}</span></button>-->
                </li>
                @if(in_array(auth()->user()->role_id, [1,2,3,4,5]))
                <li class="nav-item" role="presentation">
                    <button class="profiletabs" id="recruit-tab" data-url="{{ route('approverecruit.index') }}" role="tab"
                        data-bs-toggle="tab" type="button" data-bs-target="#recruit" aria-controls="recruit"
                        aria-selected="false">Recruit</button>
                    <!--<button class="profiletabs" id="recruit-tab" data-url="{{ route('approverecruit.index') }}"-->
                    <!--role="tab" data-bs-toggle="tab" type="button" data-bs-target="#recruit" aria-controls="recruit"-->
                    <!--aria-selected="false">Recruit &nbsp;<span class="aprvlcnt">{{ $recruit_count }}</span></button>-->
                </li>
                @endif
                @if(hasAccess(auth()->user()->role_id, 'maintain_req'))
                <li class="nav-item" role="presentation">
                    <button class="profiletabs" id="repair-tab" data-url="{{ route('approverepair.index') }}" role="tab"
                        data-bs-toggle="tab" type="button" data-bs-target="#repair" aria-controls="repair"
                        aria-selected="false">Maintenance</button>
                </li>
                @endif
                 @if (hasAccess(auth()->user()->role_id, 'pur_req'))
                    <li class="nav-item" role="presentation">
                        <button class="profiletabs" id="purchase-tab" data-url="{{ route('approvepurchase_order') }}" role="tab" data-bs-toggle="tab" type="button"
                            data-bs-target="#purchase" aria-controls="repair" aria-selected="false">Purchase Order</button>
                    </li>
                @endif
                  @if (hasAccess(auth()->user()->role_id, 'ret_req'))
                    <li class="nav-item" role="presentation">
                        <button class="profiletabs" id="purchase-tab" data-url="{{ route('approve.retirement_list') }}" role="tab" data-bs-toggle="tab" type="button"
                            data-bs-target="#retire" aria-controls="retire" aria-selected="false">Retirement</button>
                    </li>
                @endif
            </ul>
        </div>

        <div class="tab-content" id="tabContentWrapper">

        </div>

    </div>

    <script>
        $(document).ready(function () {

            const loadContent = (url) => {
                $("#tabContentWrapper").html('<p>Loading...</p>');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        $("#tabContentWrapper").html(data);
                    },
                    error: function () {
                        $("#tabContentWrapper").html("<p>Error loading content</p>");
                    }
                });
            };

            $(".profiletabs").on("click", function () {
                $(".profiletabs").removeClass("active");
                $(this).addClass("active");

                const url = $(this).data("url");
                loadContent(url);
            });

            $(".profiletabs.active").trigger("click");
        });
    </script>
@endsection
