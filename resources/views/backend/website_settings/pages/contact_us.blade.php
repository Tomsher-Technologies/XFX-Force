@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">Edit {{ $page->slug }} Page Information</h1>
            </div>
        </div>
    </div>
    @php
        $settings = $page ? json_decode($page->data, true) : [];
    @endphp
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
            
                <div class="card">
                    <div class="card-header">
                        <input type="hidden" name="type" value="{{ $page->type }}">
                        <h6 class="mb-0">Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Title</label>
                                <input type="text" name="title" value="{{ $settings['title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Sub Title</label>
                                <input type="text" name="sub_title" value="{{ $settings['sub_title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>
                        
                       <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Form Title</label>
                                <input type="text" name="form_title" value="{{ $settings['form_title'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Description</label>
                                <textarea name="google_map_link" class="form-control" rows="5">{{ $settings['google_map_link'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <h6 class="mb-1"><u>Address Section</u> </h6>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Heading</label>
                                <input type="text" name="address_heading" value="{{ $settings['address_heading'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Content</label>
                                <input type="text" name="address_content" value="{{ $settings['address_content'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>
                        
                        <h6 class="mb-1"><u>Phone Section</u> </h6>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Heading</label>
                                <input type="text" name="phone_heading" value="{{ $settings['phone_heading'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Content</label>
                                <input type="text" name="phone_content" value="{{ $settings['phone_content'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>
                       
                        <h6 class="mb-1"><u>Email Section</u> </h6>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Heading</label>
                                <input type="text" name="email_heading" value="{{ $settings['email_heading'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Content</label>
                                <input type="text" name="email_content" value="{{ $settings['email_content'] ?? '' }}" class="form-control form-control-sm">
                            </div>
                        </div>

                    </div>
                </div>
            
            
                @include('backend.inc.page_seo', ['settings' => $settings])


                <div class="text-right mb-2">
                    <button type="submit" class="btn btn-info btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
    
@endsection

@section('script')
    <script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places&v=weekly"></script>
    <script src="https://rawgit.com/Logicify/jquery-locationpicker-plugin/master/dist/locationpicker.jquery.js"></script>

    <script>
        function showPosition(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            loadMap(lat, lng)
        }

        function showPositionerror() {
            loadMap(25.2048, 55.2708)
        }

        function loadMap(lat, lng) {
            $('#us3').locationpicker({
                zoom: 12,
                location: {
                    latitude: lat,
                    longitude: lng
                },
                radius: 0,
                inputBinding: {
                    latitudeInput: $('#us3-lat'),
                    longitudeInput: $('#us3-lon'),
                    radiusInput: $('#us3-radius'),
                    locationNameInput: $('#us3-address')
                },
                enableAutocomplete: true,
                onchanged: function(currentLocation, radius, isMarkerDropped) {
                    // Uncomment line below to show alert on each Location Changed event
                    //alert("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
                }
            });
        }

        $(document).ready(function() {
            loadMap({{ $page->heading4 ?? '25.2048' }}, {{ $page->heading5 ?? '55.2708' }})
            // if (navigator.geolocation) {
            //     console.log(navigator.geolocation);
            //     navigator.geolocation.watchPosition(showPosition, showPositionerror);
            // } else {
            //     console.log("asas");
            //     loadMap(25.2048, 55.2708)
            // }
        });
    </script>
@endsection