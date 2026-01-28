@extends('layouts.app')

@section('content')
    <div class="sidebodydiv px-5 py-3 mb-3">
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Settings</h4>
        </div>

        <!-- Tabs -->
        <div class="proftabs">
            <ul class="nav nav-tabs d-flex justify-content-start align-items-center gap-md-3 border-0" id="myTab"
                role="tablist">

                @if(hasAccess($role,'cat/sub'))
                <li class="nav-item" role="presentation">
                    <button class="profiletabs active" data-url="{{ route('category') }}" type="button">Category</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="profiletabs" data-url="{{ route('subcategory') }}" type="button">Sub Category</button>
                </li>
                @endif

                <li class="nav-item" role="presentation">
                    <button class="profiletabs @if(!(hasAccess($role,'cat/sub'))){{'active'}}@endif" data-url="{{ route('password') }}" type="button">Password</button>
                </li>
                {{-- <li class="nav-item" role="presentation">
                    <button class="profiletabs" data-url="{{ route('permission') }}" type="button">Roles &
                        Permissions</button>
                </li> --}}
                @if (hasAccess($role, 'cat/sub') && !in_array($role, [1, 2]))
                <li class="nav-item" role="presentation">
                    <button class="profiletabs" data-url="{{ route('assign.assign_asm') }}" type="button">Assign ASM</button>
                </li>
                @endif
                <!--<li class="nav-item" role="presentation">
                    <button class="profiletabs" data-url="{{ route('theme') }}" type="button">Themes</button>
                </li>-->
            </ul>

        </div>

        <div class="tab-content mt-4" id="myTabContent">
            <div id="tabContentWrapper">
                <!-- Content will be loaded dynamically here -->
            </div>
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
                // console.log(url);

                loadContent(url);
            });

              $(".profiletabs.active").trigger("click");
        });
    </script>
@endsection
