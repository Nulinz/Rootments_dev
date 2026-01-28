@extends('layouts.app')

@section('content')
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>

    <style>
        .masonry-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            grid-auto-rows: 10px;
            gap: 1rem;
        }

        .masonry-grid>.col {
            break-inside: avoid;
        }

        h5 {
            font-size: 1.1rem;
        }

        .audit-list>li,
        .audit-rw {
            font-size: 14px;
        }

          
    </style>
    <div class="sidebodydiv px-5 py-3">
        <div class="sidebodyhead">
            <h4 class="m-0">
                @php
                    // $store_name = $store_audit->first()->store_name;
                @endphp
                {{ $store_audit->store_name }} Audit Profile
            </h4>
        </div>

        <div class="container-fluid mt-4">
            <div class="row" data-masonry='{"percentPosition": true }'>
                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Store Exterior & Signage</h5>
                            <ul class="border-bottom text-muted audit-list mb-1 px-0 pb-3">
                                <li class="">Exterior Cleanliness -<span>{{ $store_audit->exterior_cleanliness }}</span></li>
                                <li class="">Signage Board Condition -<span> {{ $store_audit->signage_board_condition }}</span></li>
                                <li class="">Entry Display Compliance -<span> {{ $store_audit->entry_display_compliance }}</span></li>
                            </ul>
                            <p class="audit-rw mb-0 pt-2"> {{ $store_audit->exterior_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Vm Standards</h5>
                            <ul class="border-bottom text-muted audit-list mb-1 px-0 pb-3">
                                <li>fixture_cleanliness -<span>{{ $store_audit->fixture_cleanliness }}</span></li>
                                <li>Product Alignment and Color Blocking -<span> {{ $store_audit->product_alignment }}</span></li>
                                <li>Quality of Display Products and Mannequin -<span> {{ $store_audit->mannequin_standards }}</span></li>
                                <li>Lighting & Aesthetics -<span> {{ $store_audit->lighting_aesthetics }}</span></li>
                                <li>Music/Ambience -<span> {{ $store_audit->music_ambience }}</span></li>
                                <li>In-Store Temperature -<span> {{ $store_audit->in_store_temperature }}</span></li>
                            </ul>
                            <p class="audit-rw mb-0 pt-2"> {{ $store_audit->vm_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Staff Presence</h5>
                            <ul class="border-bottom text-muted audit-list mb-1 px-0 pb-3">
                                <li>Uniform & Grooming -<span>{{ $store_audit->uniform_grooming }}</span></li>
                                <li>Badge & Name Display -<span> {{ $store_audit->badge_name_display }}</span></li>
                                <li>Punctuality/Attendance -<span> {{ $store_audit->punctuality_attendance }}</span></li>
                                <li>Staff Professionalism -<span> {{ $store_audit->staff_professionalism }}</span></li>
                            </ul>
                            <p class="audit-rw mb-0 pt-2"> {{ $store_audit->staff_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Guest handling</h5>
                            <ul class="border-bottom text-muted audit-list mb-1 px-0 pb-3">
                                <li>Greeting Etiquette -<span>{{ $store_audit->greeting_etiquette }}</span></li>
                                <li>Customer Waiting Time -<span> {{ $store_audit->customer_waiting_time }}</span></li>
                                <li>Product Presentation & Handling -<span> {{ $store_audit->product_presentation_handling }}</span></li>
                                <li>Style Suggestions -<span> {{ $store_audit->style_suggestions }}</span></li>
                            </ul>
                            <p class="audit-rw mb-0 pt-2"> {{ $store_audit->guest_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Trial Room</h5>
                            <ul class="border-bottom text-muted audit-list mb-1 px-0 pb-3">
                                <li>Cleanliness & Mirror -<span>{{ $store_audit->trialroom_cleanliness_mirror }}</span></li>
                                <li>Privacy Maintenance -<span> {{ $store_audit->privacy_maintenance }}</span></li>
                                <li>Staff Coordination -<span> {{ $store_audit->staff_coordination }}</span></li>
                                <li>Accessories Trial Support -<span> {{ $store_audit->accessories_trial_support }}</span></li>
                            </ul>
                            <p class="audit-rw mb-0 pt-2"> {{ $store_audit->trialroom_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Billing System</h5>
                            <ul class="border-bottom text-muted audit-list mb-1 px-0 pb-3">
                                <li>Accuracy of Billing -<span>{{ $store_audit->billing_accuracy }}</span></li>
                                <li>Discount Application -<span> {{ $store_audit->discount_application }}</span></li>
                                <li>Terms & Conditions Explanation -<span> {{ $store_audit->terms_conditions_explanation }}</span></li>
                            </ul>
                            <p class="audit-rw mb-0 pt-2"> {{ $store_audit->billing_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Inventory System</h5>
                            <ul class="border-bottom text-muted audit-list mb-1 px-0 pb-3">
                                <li>Stock Management -<span>{{ $store_audit->stock_management }}</span></li>
                                <li>Product Tag -<span> {{ $store_audit->product_tag }}</span></li>
                                <li>Defective Products Isolated -<span> {{ $store_audit->defective_products_isolated }}</span></li>
                            </ul>
                            <p class="audit-rw mb-0 pt-2"> {{ $store_audit->inventory_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Returned Garments</h5>
                            <ul class="border-bottom text-muted audit-list mb-1 px-0 pb-3">
                                <li>Cleanliness Check -<span>{{ $store_audit->return_cleanliness_check }}</span></li>
                                <li>Damage Reporting -<span> {{ $store_audit->damage_reporting }}</span></li>
                                <li>Customer Return Delay check -<span> {{ $store_audit->return_delay_check }}</span></li>
                                <li>Hang Returned Products in the Designated Area -<span> {{ $store_audit->hang_in_designated_area }}</span></li>
                            </ul>
                            <p class="audit-rw mb-0 pt-2"> {{ $store_audit->return_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Software usage & Documents</h5>
                            <ul class="border-bottom text-muted audit-list mb-1 px-0 pb-3">
                                <li>Measurement Books, Qc checklist -<span>{{ $store_audit->measurement_books_qc_checklist }}</span></li>
                                <li>Booking, Rent-out & Return Records -<span> {{ $store_audit->booking_rentout_return_records }}</span></li>
                                <li>Task Management App Walk-in Update -<span> {{ $store_audit->task_mgmt_app_update }}</span></li>
                            </ul>
                            <p class="audit-rw mb-0 pt-2"> {{ $store_audit->software_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">SOP Compliance</h5>
                            <ul class="border-bottom text-muted audit-list mb-1 px-0 pb-3">
                                <li>Booking/Rentout/Return Process -<span>{{ $store_audit->sop_booking_rentout_return }}</span></li>
                                <li>Alteration/Repair Handling -<span> {{ $store_audit->sop_alteration_repair_handling }}</span></li>
                                <li>Brand Compliance -<span> {{ $store_audit->sop_brand_compliance }}</span></li>
                                <li>Software Compliance -<span> {{ $store_audit->sop_software_compliance }}</span></li>
                                <li>Store KPI Awareness -<span> {{ $store_audit->sop_kpi_awareness }}</span></li>
                            </ul>
                            <p class="audit-rw mb-0 pt-2"> {{ $store_audit->sop_remarks }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Audit Observation Acknowledged</h5>
                            <ul class="text-muted audit-list mb-0 px-0 pb-0">
                                <li> {{ $store_audit->audit_acknowledged }}</>
                                </li>
                                {{-- <li>Action Plan for Shortfalls -<span> {{ $store_audit->action_plan }}</span></li>
                                <li>Average of total rating -<span> {{ $store_audit->average_rating }}</span></li> --}}
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Action Plan for Shortfalls</h5>
                            <ul class="text-muted audit-list mb-0 px-0 pb-0">
                                {{-- <li>Audit Observation Acknowledged -<span>{{ $store_audit->audit_acknowledged }}</span></li> --}}
                                <li> {{ $store_audit->action_plan }}</>
                                </li>
                                {{-- <li>Average of total rating -<span> {{ $store_audit->average_rating }}</span></li> --}}
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="pb-1">Average Rating</h5>
                            <ul class="text-muted audit-list mb-0 px-0 pb-0">

                                <li>Average of total rating -<span> {{ $store_audit->average_rating }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var elem = document.querySelector('#auditMasonry');
            new Masonry(elem, {
                itemSelector: '.col',
                gutter: 20
            });
        });
    </script>
@endsection
