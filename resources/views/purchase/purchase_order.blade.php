@extends('layouts.app')

@section('content')
    <div class="sidebodydiv mb-3 px-5 py-3">
        <div class="sidebodyback mb-3" onclick="goBack()">
            <div class="backhead">
                <h5><i class="fas fa-arrow-left"></i></h5>
                <h6>Add Purchase Order Form</h6>
            </div>
        </div>
        <div class="sidebodyhead my-3">
            <h4 class="m-0">Purchase Order Details</h4>
        </div>
        <form action="{{ route('purchase.store_purchase_po') }}" method="post" id="c_form">
            @csrf
            <div class="container-fluid maindiv">
                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Store <span class="text-danger">*</span></label>
                        <select name="store_id" id="" class="form-select" required>
                            <option value="" disabled selected>Select Type</option>
                            @foreach ($Stores as $st)
                                <option value="{{ $st->id }}">{{ $st->store_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Vendor</label>
                        <select name="vendor" id="" class="form-select">
                            <option value="" selected disabled>Select Option</option>
                            @foreach ($vendors as $vr)
                                <option value="{{ $vr->id }}">{{ $vr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Contact Number</label>
                        <input type="text" class="form-control" name="contact" readonly required>
                        {{-- <input type="text" class="form-control" name="contact" id="" value="" autofocus required> --}}
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Billing Address</label>
                        <input type="text" class="form-control" name="address" readonly required>

                        {{-- <input type="text" class="form-control" name="address" id="" value="" autofocus required> --}}
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Date</label>
                        <input type="date" class="form-control" name="date" id="" value="" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Expected Delivery Date</label>
                        <input type="date" class="form-control" name="delivery_date" id="" value="" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Expected Advance Payment Date</label>
                        <input type="date" class="form-control" name="advance_payment_date" id="" value="" autofocus required>
                    </div>
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Balance Payment Date</label>
                        <input type="date" class="form-control" name="balance_payment_date" id="" value="" autofocus required>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table id="example" class="table-hover table-striped table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="width: 17%;">Product Code</th>
                                <th>Product</th>
                                <th>Color</th>
                                <th>Quantity</th>
                                <th>Selling Price</th>
                                <th>Purchase Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            <tr class="product-row">
                                <td></td>
                                <td>
                                    <select class="form-select product-code-select" name="product_code[]">
                                        <option value="" disabled selected>Select Code</option>
                                        @foreach ($products as $pr)
                                            <option value="{{ $pr->product_code }}">{{ $pr->product_code }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control" type="text" name="product[]">
                                    <input type="hidden" name="product_id[]">
                                </td>
                                <td><input class="form-control" type="text" name="color[]"></td>
                                <td><input class="form-control" type="text" name="qty[]"></td>
                                <td><input class="form-control" type="text" name="selling[]"></td>
                                <td><input class="form-control" type="text" name="purchase[]"></td>
                                {{-- <td><button class="form-control" type="button" class="btn btn-sm bg-dark addRow text-white">+</button></td> --}}
                                <td>
                                    <button type="button" class="btn btn-sm bg-dark addRow w-100 text-white">+</button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total</td>
                                <td id="footerQty"><strong>0</strong></td>
                                <td id="footerSelling"><strong>0.00</strong></td>
                                <td id="footerPurchase"><strong>0.00</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-4 col-xl-4 inputs mb-3">
                        <label for="storeid">Overall Total (Qty × Purchase)</label>
                        <input type="text" class="form-control" name="overall_total" value="0.00" readonly>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12 col-xl-12 d-flex justify-content-center align-items-center mt-3">
                <a href="">
                    <button type="submit" id="sub" class="formbtn">Save</button>
                </a>
            </div>
        </form>
    </div>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        // add vendor details 
        const vendorsData = @json($vendors);
        document.addEventListener('DOMContentLoaded', function() {
            const vendorSelect = document.querySelector('select[name="vendor"]');
            const contactInput = document.querySelector('input[name="contact"]');
            const addressInput = document.querySelector('input[name="address"]');

            vendorSelect.addEventListener('change', function() {
                const selectedId = this.value;
                const vendor = vendorsData.find(v => v.id == selectedId);

                if (vendor) {
                    contactInput.value = vendor.contact;
                    addressInput.value = vendor.shipping_address;
                } else {
                    contactInput.value = '';
                    addressInput.value = '';
                }
            });
        });
    </script>
    <script>
        const productsData = @json($products);

        document.addEventListener('DOMContentLoaded', function() {

            function createInputRow() {
                const options = productsData.map(p =>
                    `<option value="${p.product_code}">${p.product_code}</option>`
                ).join('');

                return `
        <tr class="product-row">
            <td></td>
            <td>
                <select class="form-select product-code-select" name="product_code[]">
                    <option value="" disabled selected>Select Code</option>
                    ${options}
                </select>
            </td>
            <td><input class="form-control" type="text" name="product[]">
                  <input type="hidden" name="product_id[]">
            </td>
            <td><input class="form-control" type="text" name="color[]"></td>
            <td><input class="form-control" type="text" name="qty[]"></td>
            <td><input class="form-control" type="text" name="selling[]"></td>
            <td><input class="form-control" type="text" name="purchase[]"></td>
            <td>
                <button type="button" class="btn btn-sm btn-dark addRow w-100">+</button>
            </td>
        </tr>`;
            }

            // Initial row (if needed)
            if ($('#productTableBody tr').length === 0) {
                $('#productTableBody').append(createInputRow());
            }

            // Auto-fill on product code select
            $(document).on('change', '.product-code-select', function() {
                const selectedCode = $(this).val();
                const row = $(this).closest('tr');
                const product = productsData.find(p => p.product_code === selectedCode);

                if (product) {
                    row.find('input[name="product[]"]').val(product.product_name);
                    row.find('input[name="color[]"]').val(product.color);
                    row.find('input[name="product_id[]"]').val(product.id);
                }
            });

            // Add Row (filled), then append new blank row
            $(document).on('click', '.addRow', function() {
                const currentRow = $(this).closest('tr');

                // Validate
                const code = currentRow.find('select[name="product_code[]"]').val();
                const product = currentRow.find('input[name="product[]"]').val();
                const color = currentRow.find('input[name="color[]"]').val();
                const qty = currentRow.find('input[name="qty[]"]').val();
                const selling = currentRow.find('input[name="selling[]"]').val();
                const purchase = currentRow.find('input[name="purchase[]"]').val();

                if (!code || !product || !qty || !purchase) {
                    alert("Fill all fields before adding.");
                    return;
                }

                // Convert current row to readonly
                currentRow.find('td:eq(1)').html(`<input type="text" class="form-control" name="product_code[]" value="${code}" readonly>`);
                currentRow.find('input[name="product[]"]').prop('readonly', true);
                currentRow.find('input[name="color[]"]').prop('readonly', true);
                currentRow.find('input[name="qty[]"]').prop('readonly', true);
                currentRow.find('input[name="selling[]"]').prop('readonly', true);
                currentRow.find('input[name="purchase[]"]').prop('readonly', true);
                currentRow.find('.addRow')
                    .removeClass('addRow btn-dark')
                    .addClass('deleteRow btn-danger')
                    .text('Delete');

                // Append new input row
                $('#productTableBody').append(createInputRow());
            });

            // Delete row
            $(document).on('click', '.deleteRow', function() {
                $(this).closest('tr').remove();
            });

        });
    </script>
    <script>
        function updateTotals() {

            let totalQty = 0;
            let totalSelling = 0;
            let totalPurchase = 0;
            let overallTotal = 0;

            document.querySelectorAll('#productTableBody tr').forEach(row => {
                const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
                const selling = parseFloat(row.querySelector('input[name="selling[]"]').value) || 0;
                const purchase = parseFloat(row.querySelector('input[name="purchase[]"]').value) || 0;

                totalQty += qty;
                totalSelling += selling;
                totalPurchase += purchase;

                overallTotal += qty * purchase; // corrected logic
            });

            const tfoot = document.querySelector('#example tfoot tr');
            tfoot.cells[4].innerText = totalQty.toFixed(0);
            tfoot.cells[5].innerText = totalSelling.toFixed(2);
            tfoot.cells[6].innerText = totalPurchase.toFixed(2);

            document.querySelector('input[name="overall_total"]').value = overallTotal.toFixed(2);
        }

        // Recalculate on input change
        document.addEventListener('input', function(e) {
            if (e.target.matches('input[name="qty[]"], input[name="selling[]"], input[name="purchase[]"]')) {
                updateTotals();
            }
        });

        // Also recalculate after row added or deleted
        $(document).on('click', '.addRow, .deleteRow', function() {
            setTimeout(updateTotals, 100); // delay to allow DOM update
        });
    </script>
@endsection
