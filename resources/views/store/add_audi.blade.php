@extends('layouts.app')

@section('content')
    <style>
        .star {
            font-size: 3rem;
        }

        .star-rating {
            direction: rtl;
            /* display: flex; */
            font-size: 2rem;
            text-align: center;
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
            margin: 0px 5px;
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
            /* yellow stars */
        }

        .star-size {
            font-size: 2.5rem !important;
        }
    </style>
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Audit Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Store Audit</h4>
        </div>
        <form action="{{ route('store.store_audit') }}" method="post" id="c_form">
            @csrf
            <div class="container-fluid maindiv p-3">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-xl-4 inputs mb-3">
                        <label for="staffname">Store<span>*</span></label>
                        <select class="form-select form-select-lg" name="store_id" id="storeDropdown" required>
                            <option value="" selected disabled>Select Store</option>
                            @foreach ($store as $st)
                                <option value="{{ $st->stores_id }}">{{ $st->stores_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Store Exterior & Signage --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">Store Exterior & Signage</h6>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Exterior Cleanliness<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="exterior_cleanliness_{{ $i }}" name="exterior_cleanliness" value="{{ $i }}" required>
                                    <label class="star-size" for="exterior_cleanliness_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Signage Board Condition<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="signage_board_condition_{{ $i }}" name="signage_board_condition" value="{{ $i }}" required>
                                    <label class="star-size" for="signage_board_condition_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Entry Display Compliance<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="entry_display_compliance_{{ $i }}" name="entry_display_compliance" value="{{ $i }}" required>
                                    <label class="star-size" for="entry_display_compliance_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 inputs mb-3">
                        <label class="d-block">Remarks <span>*</span></label>
                        <textarea name="store_signage_remarks" class="form-control" rows="1" required></textarea>
                    </div>
                </div>

                {{-- Vm Standards --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">VM Standards</h6>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Floor Cleanliness<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="floor_cleanliness_{{ $i }}" name="floor_cleanliness" value="{{ $i }}" required>
                                    <label class="star-size" for="floor_cleanliness_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Product Alignment and Color Blocking<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="product_alignment_{{ $i }}" name="product_alignment" value="{{ $i }}" required>
                                    <label class="star-size" for="product_alignment_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Quality of Display Products and Mannequins<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="display_quality_{{ $i }}" name="display_quality" value="{{ $i }}" required>
                                    <label class="star-size" for="display_quality_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Lighting & Aesthetics<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="lighting_aesthetics_{{ $i }}" name="lighting_aesthetics" value="{{ $i }}" required>
                                    <label class="star-size" for="lighting_aesthetics_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Music/Ambience<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="music_ambience_{{ $i }}" name="music_ambience" value="{{ $i }}" required>
                                    <label class="star-size" for="music_ambience_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">In-Store Temperature<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="in_store_temperature_{{ $i }}" name="in_store_temperature" value="{{ $i }}" required>
                                    <label class="star-size" for="in_store_temperature_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 inputs my-3">
                        <label class="d-block">Remarks <span>*</span></label>
                        <textarea name="vm_standards_remarks" class="form-control" rows="1" required></textarea>
                    </div>
                </div>

                {{-- Staff Presence --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">Staff Presence</h6>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Uniform & Grooming<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="uniform_grooming_{{ $i }}" name="uniform_grooming" value="{{ $i }}" required>
                                    <label class="star-size" for="uniform_grooming_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Badge & Name Display<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="badge_name_display_{{ $i }}" name="badge_name_display" value="{{ $i }}" required>
                                    <label class="star-size" for="badge_name_display_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Punctuality/Attendance<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="punctuality_attendance_{{ $i }}" name="punctuality_attendance" value="{{ $i }}" required>
                                    <label class="star-size" for="punctuality_attendance_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Staff Professionalism<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="staff_professionalism_{{ $i }}" name="staff_professionalism" value="{{ $i }}" required>
                                    <label class="star-size" for="staff_professionalism_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 inputs my-3">
                        <label class="d-block">Remarks <span>*</span></label>
                        <textarea name="staff_remarks" class="form-control" rows="1" required></textarea>
                    </div>
                </div>

                {{-- Guest handling --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">Guest handling</h6>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Greeting Etiquette<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="greeting_etiquette_{{ $i }}" name="greeting_etiquette" value="{{ $i }}" required>
                                    <label class="star-size" for="greeting_etiquette_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Customer Waiting Time<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="customer_waiting_time_{{ $i }}" name="customer_waiting_time" value="{{ $i }}" required>
                                    <label class="star-size" for="customer_waiting_time_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Product Presentation & Handling<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="product_presentation_{{ $i }}" name="product_presentation" value="{{ $i }}" required>
                                    <label class="star-size" for="product_presentation_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Style Suggestions<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="style_suggestions_{{ $i }}" name="style_suggestions" value="{{ $i }}" required>
                                    <label class="star-size" for="style_suggestions_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 inputs my-3">
                        <label class="d-block">Remarks <span>*</span></label>
                        <textarea name="guest_remarks" class="form-control" rows="1" required></textarea>
                    </div>
                </div>

                {{-- Trial Room --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">Trial Room</h6>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Cleanliness & Mirror<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="cleanliness_mirror_{{ $i }}" name="cleanliness_mirror" value="{{ $i }}" required>
                                    <label class="star-size" for="cleanliness_mirror_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Privacy Maintenance<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="privacy_maintenance_{{ $i }}" name="privacy_maintenance" value="{{ $i }}" required>
                                    <label class="star-size" for="privacy_maintenance_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Staff Coordination<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="staff_coordination_{{ $i }}" name="staff_coordination" value="{{ $i }}" required>
                                    <label class="star-size" for="staff_coordination_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Accessories Trial Support<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="accessories_trial_support_{{ $i }}" name="accessories_trial_support" value="{{ $i }}"
                                        required>
                                    <label class="star-size" for="accessories_trial_support_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 inputs my-3">
                        <label class="d-block">Remarks <span>*</span></label>
                        <textarea name="trial_room_remarks" class="form-control" rows="1" required></textarea>
                    </div>
                </div>

                {{-- Billing System --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">Billing System</h6>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Accuracy of Billing<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="accuracy_billing_{{ $i }}" name="accuracy_billing" value="{{ $i }}" required>
                                    <label class="star-size" for="accuracy_billing_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Discount Application<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="discount_application_{{ $i }}" name="discount_application" value="{{ $i }}" required>
                                    <label class="star-size" for="discount_application_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Terms & Conditions Explanation<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="terms_conditions_{{ $i }}" name="terms_conditions" value="{{ $i }}" required>
                                    <label class="star-size" for="terms_conditions_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 inputs my-3">
                        <label class="d-block">Remarks <span>*</span></label>
                        <textarea name="billing_remarks" class="form-control" rows="1" required></textarea>
                    </div>
                </div>

                {{-- Inventory System --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">Inventory System</h6>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Stock Management<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="stock_management_{{ $i }}" name="stock_management" value="{{ $i }}" required>
                                    <label class="star-size" for="stock_management_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Product Tag<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="product_tag_{{ $i }}" name="product_tag" value="{{ $i }}" required>
                                    <label class="star-size" for="product_tag_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Defective Products Isolated<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="defective_isolated_{{ $i }}" name="defective_isolated" value="{{ $i }}" required>
                                    <label class="star-size" for="defective_isolated_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 inputs my-3">
                        <label class="d-block">Remarks <span>*</span></label>
                        <textarea name="inventory_remarks" class="form-control" rows="1" required></textarea>
                    </div>
                </div>

                {{-- Returned Garments --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">Returned Garments</h6>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Cleanliness Check<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="cleanliness_check_{{ $i }}" name="cleanliness_check" value="{{ $i }}" required>
                                    <label class="star-size" for="cleanliness_check_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Damage Reporting<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="damage_reporting_{{ $i }}" name="damage_reporting" value="{{ $i }}" required>
                                    <label class="star-size" for="damage_reporting_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Customer Return Delay check<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="return_delay_check_{{ $i }}" name="return_delay_check" value="{{ $i }}" required>
                                    <label class="star-size" for="return_delay_check_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Hang Returned Products in the Designated Area<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="hang_returned_products_{{ $i }}" name="hang_returned_products" value="{{ $i }}" required>
                                    <label class="star-size" for="hang_returned_products_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 inputs my-3">
                        <label class="d-block">Remarks <span>*</span></label>
                        <textarea name="returned_garments_remarks" class="form-control" rows="1" required></textarea>
                    </div>
                </div>

                {{-- Software usage& Documents --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">Software usage & Documents</h6>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Measurement Books, Qc checklist<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="measurement_qc_{{ $i }}" name="measurement_qc" value="{{ $i }}" required>
                                    <label class="star-size" for="measurement_qc_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Booking, Rent-out & Return Records<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="booking_records_{{ $i }}" name="booking_records" value="{{ $i }}" required>
                                    <label class="star-size" for="booking_records_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Task Management App Walk-in Update<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="task_app_update_{{ $i }}" name="task_app_update" value="{{ $i }}" required>
                                    <label class="star-size" for="task_app_update_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 inputs my-3">
                        <label class="d-block">Remarks <span>*</span></label>
                        <textarea name="software_remarks" class="form-control" rows="1" required></textarea>
                    </div>
                </div>

                {{-- SOP Compliance --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">SOP Compliance</h6>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Booking/Rentout/Return Process<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="booking_process_{{ $i }}" name="booking_process" value="{{ $i }}" required>
                                    <label class="star-size" for="booking_process_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Alteration/Repair Handling<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="repair_handling_{{ $i }}" name="repair_handling" value="{{ $i }}" required>
                                    <label class="star-size" for="repair_handling_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Brand Compliance<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="brand_compliance_{{ $i }}" name="brand_compliance" value="{{ $i }}" required>
                                    <label class="star-size" for="brand_compliance_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Software Compliance (Billing & Walkin)<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="software_compliance_{{ $i }}" name="software_compliance" value="{{ $i }}" required>
                                    <label class="star-size" for="software_compliance_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Store KPI Awareness<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="kpi_awareness_{{ $i }}" name="kpi_awareness" value="{{ $i }}" required>
                                    <label class="star-size" for="kpi_awareness_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 inputs my-3">
                        <label class="d-block">Remarks <span>*</span></label>
                        <textarea name="sop_remarks" class="form-control" rows="1" required></textarea>
                    </div>
                </div>

                {{-- Auditor remarks --}}
                <div class="row mt-4">
                    <h6 class="fw-bold mb-4">Auditor Remarks</h6>

                    <div class="col-sm-12 col-md-6 col-xl-6 inputs my-3">
                        <label class="d-block">Audit Observation Acknowledged <span>*</span></label>
                        <textarea name="audit_acknowledged" class="form-control" rows="1" required></textarea>
                    </div>

                    <div class="col-sm-12 col-md-6 col-xl-6 inputs my-3">
                        <label class="d-block">Action Plan for Shortfalls <span>*</span></label>
                        <textarea name="action_plan" class="form-control" rows="1" required></textarea>
                    </div>

                    {{-- <div class="col-sm-12 col-md-4 col-xl-4 inputs d-flex justify-content-center my-3">
                        <div>
                            <label class="d-block text-center">Average of total rating<span>*</span></label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="average_rating_{{ $i }}" name="average_rating" value="{{ $i }}">
                                    <label class="star-size" for="average_rating_{{ $i }}">&#9733;</label>
                                @endfor
                            </div>
                        </div>
                    </div> --}}
                </div>

            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center my-3">
                <button type="submit" id="sub" class="formbtn">Save</button>
            </div>

        </form>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>
@endsection
