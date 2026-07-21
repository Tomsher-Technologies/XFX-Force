@extends('backend.layouts.app')

@section('content')
    
    <div class="row">
        <div class="col-lg-6">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">Free Shipping Settings</h5>
                    </div>
                    <form action="{{ route('shipping_configuration.free_shipping') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="card-body">
                            @csrf
                            <input type="hidden" name="type" value="free_shipping">

                            <div class="form-group row mb-2 mt-2">
                                <label class="col-md-4 col-from-label">
                                    Default shipping amount
                                </label>
                                <div class="col-md-8">
                                    <input step="0.01" class="form-control" type="number"
                                        name="default_shipping_amount"
                                        value="{{ get_setting('default_shipping_amount') ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <label class="col-md-4 col-from-label">
                                    Free shipping status
                                </label>
                                <div class="col-md-8">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input name="free_shipping_status"
                                            {{ get_setting('free_shipping_status') == '1' ? 'checked' : '' }} type="checkbox">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-from-label">
                                    Free shipping min amount
                                </label>
                                <div class="col-md-8">
                                    <input step="0.01" class="form-control" type="number"
                                        name="free_shipping_min_amount"
                                        value="{{ get_setting('free_shipping_min_amount') ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group mb-0 text-right">
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">Order Return Time Limit</h5>
                    </div>
                    <form action="{{ route('configuration.return_settings') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="card-body">
                            @csrf
                            <input type="hidden" name="type" value="return_product_limit">

                            <div class="form-group row  mt-2">
                                <label class="col-md-4 col-from-label">
                                    Return Time Limit (Days)
                                </label>
                                <div class="col-md-8">
                                    <input step="1" class="form-control" type="number"
                                        name="default_return_time"
                                        value="{{ get_setting('default_return_time') ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group mb-0 text-right">
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">Default VAT (%)</h5>
                    </div>
                    <form action="{{ route('configuration.vat_settings') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="card-body">
                            @csrf
                            
                            <div class="form-group row  mt-2">
                                <label class="col-md-4 col-from-label">
                                    VAT (%)
                                </label>
                                <div class="col-md-8">
                                    <input class="form-control" type="number" id="default_vat" name="default_vat" required
                                        value="{{ get_setting('default_vat') ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group mb-0 text-right">
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">Estimated Delivery Days</h5>
                    </div>
                    <form action="{{ route('configuration.delivery_settings') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="card-body">
                            @csrf
                            <input type="hidden" name="type" value="delivery_days">

                            <div class="form-group row  mt-2">
                                <label class="col-md-4 col-from-label">
                                    Estimated Delivery Days
                                </label>
                                <div class="col-md-8">
                                    <input step="1" class="form-control" type="number"
                                        name="default_delivery_days"
                                        value="{{ get_setting('default_delivery_days') ?? 0 }}">
                                </div>
                            </div>

                            <div class="form-group mb-0 text-right">
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- invoice logo -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">Invoice Logo</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('configuration.invoice_settings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row  mt-2">
                                <div class="col-md-6">
                                    <label class="form-label" for="default_invoice_logo">Logo</label>
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                        </div>
                                        <div class="form-control file-amount">Choose File</div>
                                        <input type="hidden" name="types[]" value="default_invoice_logo">
                                        <input type="hidden" name="default_invoice_logo" class="selected-files"
                                            value="{{ get_setting('default_invoice_logo') }}">
                                    </div>
                                    <div class="file-preview"></div>
                                </div>
                            </div>

                            <div class="form-group mb-0 text-right">
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

          
            
        </div>

       

        <div class="col-lg-6">

             <!-- COD Additional Charge -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Default COD Additional Charge</h5>
                </div>

                <form action="{{ route('configuration.cod_charge_settings') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">

                        <div class="form-group row mt-2">
                            <label class="col-md-4 col-from-label">
                                COD Additional Charge
                            </label>

                            <div class="col-md-8">
                                <input 
                                    class="form-control" 
                                    type="number" 
                                    id="cod_additional_charge" 
                                    name="cod_additional_charge"
                                    min="0"
                                    step="0.01"
                                    required
                                    value="{{ get_setting('cod_additional_charge') ?? 0 }}">
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary mt-2">
                                Save
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Pickup Location Settings</h5>
                </div>

                <form action="{{ route('configuration.pickup_settings') }}" method="POST">
                    @csrf
                    <div class="card-body">

                        {{-- Address --}}
                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Pickup Address
                            </label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="pickup_address" id="pickup_address" rows="4"
                                    required>{{ get_setting('pickup_address') }}</textarea>
                            </div>
                        </div>

                        {{-- Address Search --}}
                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Search Location
                            </label>
                            <div class="col-md-8">
                                <input type="text" id="map_search" class="form-control"
                                    placeholder="Search address...">
                            </div>
                        </div>

                        {{-- Map --}}
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div id="map" style="width:100%; height:350px;"></div>
                            </div>
                        </div>

                        

                        {{-- Latitude --}}
                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Latitude
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="pickup_latitude"
                                    name="pickup_latitude" readonly
                                    value="{{ get_setting('pickup_latitude') }}">
                            </div>
                        </div>

                        {{-- Longitude --}}
                        <div class="form-group row">
                            <label class="col-md-4 col-from-label">
                                Longitude
                            </label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="pickup_longitude"
                                    name="pickup_longitude" readonly
                                    value="{{ get_setting('pickup_longitude') }}">
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary mt-1">
                                Save
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Default SEO OG Image -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Default SEO OG Image</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('configuration.seo_og_image_settings') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row  mt-2">
                            <div class="col-md-6">
                                <label class="form-label" for="default_seo_og_image">Image</label>
                                <div class="input-group " data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                    </div>
                                    <div class="form-control file-amount">Choose File</div>
                                    <input type="hidden" name="types[]" value="default_seo_og_image">
                                    <input type="hidden" name="default_seo_og_image" class="selected-files"
                                        value="{{ get_setting('default_seo_og_image') }}">
                                </div>
                                <div class="file-preview"></div>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary mt-2">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Methods Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Payment Methods Settings</h5>
                </div>
                <form action="{{ route('configuration.payment_methods_settings') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label class="col-from-label mb-0">Cash On Delivery</label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input name="payment_method_cod"
                                        {{ get_setting('payment_method_cod', 1) == '1' ? 'checked' : '' }} type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-12 mt-2">
                                <textarea name="payment_method_cod_refund_note" class="form-control" rows="4"
                                    placeholder="Refund days note (e.g. Refunds take 1-3 business days)">{{ get_setting('payment_method_cod_refund_note') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label class="col-from-label mb-0">Tabby</label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input name="payment_method_tabby"
                                        {{ get_setting('payment_method_tabby', 1) == '1' ? 'checked' : '' }} type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-12 mt-2">
                                <textarea name="payment_method_tabby_refund_note" class="form-control" rows="4"
                                    placeholder="Refund days note (e.g. Refunds take 5-7 business days)">{{ get_setting('payment_method_tabby_refund_note') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <div class="col-md-6">
                                <label class="col-from-label mb-0">Credit / Debit Card</label>
                            </div>
                            <div class="col-md-6 text-right">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input name="payment_method_card"
                                        {{ get_setting('payment_method_card', 1) == '1' ? 'checked' : '' }} type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="col-md-12 mt-2">
                                <textarea name="payment_method_card_refund_note" class="form-control" rows="4"
                                    placeholder="Refund days note (e.g. Refunds take 3-5 business days)">{{ get_setting('payment_method_card_refund_note') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary mt-2">Save</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

  
@endsection


@section('script')
<script async src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&loading=async&libraries=places"></script>

<script>
    let map;
    let marker;
    let autocomplete;

    function initMap() {

        let defaultLat = parseFloat(document.getElementById('pickup_latitude').value) || 25.2048;
        let defaultLng = parseFloat(document.getElementById('pickup_longitude').value) || 55.2708;

        let defaultLocation = { lat: defaultLat, lng: defaultLng };

        map = new google.maps.Map(document.getElementById("map"), {
            center: defaultLocation,
            zoom: 12,
        });

        marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true
        });

        // Click on map
        map.addListener("click", function (event) {
            placeMarker(event.latLng);
        });

        // Drag marker
        marker.addListener("dragend", function (event) {
            updateInputs(event.latLng);
        });

        // Autocomplete search
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById("map_search")
        );

        autocomplete.addListener("place_changed", function () {
            let place = autocomplete.getPlace();
            if (!place.geometry) return;

            map.setCenter(place.geometry.location);
            marker.setPosition(place.geometry.location);

            updateInputs(place.geometry.location);

            document.getElementById("pickup_address").value = place.formatted_address;
        });
    }

    function placeMarker(location) {
        marker.setPosition(location);
        updateInputs(location);
    }

    function updateInputs(location) {
        document.getElementById("pickup_latitude").value = location.lat();
        document.getElementById("pickup_longitude").value = location.lng();
    }

    window.onload = initMap;
</script>

@endsection