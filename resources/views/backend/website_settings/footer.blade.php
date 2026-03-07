@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h4 class="h5">Website Footer</h4>
            </div>
        </div>
    </div>
   
    <div class="card">
        <div class="card-header">
            <h6 class="fw-600 mb-0">Footer Widget</h6>
        </div>
        <div class="card-body">
            <div class="row gutters-10">

                

                <div class="col-lg-6 mx-auto">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Contact Info Widget</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update_settings') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label class="form-label" for="signinSrEmail">Footer Logo</label>
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                        </div>
                                        <div class="form-control file-amount">Choose File</div>
                                        <input type="hidden" name="types[]" value="footer_logo">
                                        <input type="hidden" name="footer_logo" class="selected-files"
                                            value="{{ get_setting('footer_logo') }}">
                                    </div>
                                    <div class="file-preview"></div>
                                </div>

                                <div class="form-group">
                                    <label>Contact Title</label>
                                    <input type="hidden" name="types[]" value="footer_contact_title">
                                    <input type="text" class="form-control" placeholder="Enter.." name="footer_contact_title"
                                        value="{{ get_setting('footer_contact_title') }}">
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Phone</label>
                                        <input type="hidden" name="types[]" value="footer_phone">
                                        <input type="text" class="form-control" placeholder="Enter.." name="footer_phone"
                                            value="{{ get_setting('footer_phone') }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="hidden" name="types[]" value="footer_email">
                                        <input type="text" class="form-control" placeholder="Enter.." name="footer_email"
                                            value="{{ get_setting('footer_email') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Address Title</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="footer_address_title">
                                    <input type="text" class="form-control" placeholder="Enter.." name="footer_address_title"
                                        value="{{ get_setting('footer_address_title') }}">
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="footer_address">
                                    <textarea class="form-control" placeholder="Enter.." name="footer_address" rows="5">{{ get_setting('footer_address') }}</textarea>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mx-auto">
                    <div class="card shadow-none bg-light ">
                        <div class="card-header">
                            <h6 class="mb-0">Other Details </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update_settings') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label class="form-label" for="signinSrEmail">Payment Image</label>
                                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                        </div>
                                        <div class="form-control file-amount">Choose File</div>
                                        <input type="hidden" name="types[]" value="footer_payment_logo">
                                        <input type="hidden" name="footer_payment_logo" class="selected-files"
                                            value="{{ get_setting('footer_payment_logo') }}">
                                    </div>
                                    <div class="file-preview"></div>
                                </div>

                                <div class="form-group">
                                    <label>Copyright Text</label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="frontend_copyright_text">
                                    <input class="form-control" name="frontend_copyright_text" value="{!! get_setting('frontend_copyright_text', null, $lang) !!}">

                                </div>

                                <div class="form-group d-none">
                                    <label>Social Links Title</label>
                                    <div class="input-group form-group">
                                        <input type="hidden" name="types[]" value="social_title">
                                        <input type="text" class="form-control" placeholder="" name="social_title"
                                            value="{{ get_setting('social_title') }}">
                                        
                                    </div>
                                </div>

                                <div class="form-group">
                                <label>Social Links</label>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="lab la-facebook-f"></i></span>
                                    </div>
                                    <input type="hidden" name="types[]" value="facebook_link">
                                    <input type="text" class="form-control" placeholder="http://" name="facebook_link"
                                        value="{{ get_setting('facebook_link') }}">
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="lab la-twitter"></i></span>
                                    </div>
                                    <input type="hidden" name="types[]" value="twitter_link">
                                    <input type="text" class="form-control" placeholder="http://" name="twitter_link"
                                        value="{{ get_setting('twitter_link') }}">
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="lab la-instagram"></i></span>
                                    </div>
                                    <input type="hidden" name="types[]" value="instagram_link">
                                    <input type="text" class="form-control" placeholder="http://" name="instagram_link"
                                        value="{{ get_setting('instagram_link') }}">
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="lab la-youtube"></i></span>
                                    </div>
                                    <input type="hidden" name="types[]" value="youtube_link">
                                    <input type="text" class="form-control" placeholder="http://" name="youtube_link"
                                        value="{{ get_setting('youtube_link') }}">
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="lab la-linkedin-in"></i></span>
                                    </div>
                                    <input type="hidden" name="types[]" value="linkedin_link">
                                    <input type="text" class="form-control" placeholder="http://" name="linkedin_link"
                                        value="{{ get_setting('linkedin_link') }}">
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="lab la-whatsapp"></i></span>
                                    </div>
                                    <input type="hidden" name="types[]" value="whatsapp_link">
                                    <input type="text" class="form-control" placeholder="http://" name="whatsapp_link"
                                        value="{{ get_setting('whatsapp_link') }}">
                                </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 ">
                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Link Widget One</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update_settings') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Title </label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="widget_one">
                                    <input type="text" class="form-control" placeholder="Widget title"
                                        name="widget_one" value="{{ get_setting('widget_one', null, $lang) }}">
                                </div>
                                <div class="form-group">
                                    <label>Links </label>
                                    <div class="w3-links-target">
                                        <input type="hidden" name="types[][{{ $lang }}]"
                                            value="widget_one_labels">
                                        <input type="hidden" name="types[]" value="widget_one_links">
                                        @if (get_setting('widget_one_labels', null, $lang) != null && get_setting('widget_one_labels', null, $lang) != 'null')
                                            @foreach (json_decode(get_setting('widget_one_labels', null, $lang), true) as $key => $value)
                                                <div class="row gutters-5">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="Label"
                                                                name="widget_one_labels[]" value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="http://" name="widget_one_links[]"
                                                                value="{{ json_decode(get_setting('widget_one_links'), true)[$key] }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button"
                                                            class="mt-1 btn btn-icon btn-circle btn-soft-danger btn-sm"
                                                            data-toggle="remove-parent" data-parent=".row">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                        data-content='<div class="row gutters-5">
    										<div class="col-4">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="Label" name="widget_one_labels[]">
    											</div>
    										</div>
    										<div class="col">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="http://" name="widget_one_links[]">
    											</div>
    										</div>
    										<div class="col-auto">
    											<button type="button" class="mt-1 btn btn-icon btn-circle btn-soft-danger btn-sm" data-toggle="remove-parent" data-parent=".row">
    												<i class="las la-times"></i>
    											</button>
    										</div>
    									</div>'
                                        data-target=".w3-links-target" data-max="8">
                                        Add New
                                    </button>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Link Widget Two</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update_settings') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Title </label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="widget_two">
                                    <input type="text" class="form-control" placeholder="Widget title"
                                        name="widget_two" value="{{ get_setting('widget_two', null, $lang) }}">
                                </div>
                                <div class="form-group">
                                    <label>Links </label>
                                    <div class="w3-links-target-2">
                                        <input type="hidden" name="types[][{{ $lang }}]"
                                            value="widget_two_labels">
                                        <input type="hidden" name="types[]" value="widget_two_links">
                                        @if (get_setting('widget_two_labels', null, $lang) != null && get_setting('widget_two_labels', null, $lang) != 'null')
                                            @foreach (json_decode(get_setting('widget_two_labels', null, $lang), true) as $key => $value)
                                                <div class="row gutters-5">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="Label"
                                                                name="widget_two_labels[]" value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="http://" name="widget_two_links[]"
                                                                value="{{ json_decode(get_setting('widget_two_links'), true)[$key] }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button"
                                                            class="mt-1 btn btn-icon btn-circle btn-soft-danger btn-sm"
                                                            data-toggle="remove-parent" data-parent=".row">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                        data-content='<div class="row gutters-5">
    										<div class="col-4">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="Label" name="widget_two_labels[]">
    											</div>
    										</div>
    										<div class="col">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="http://" name="widget_two_links[]">
    											</div>
    										</div>
    										<div class="col-auto">
    											<button type="button" class="mt-1 btn btn-icon btn-circle btn-soft-danger btn-sm" data-toggle="remove-parent" data-parent=".row">
    												<i class="las la-times"></i>
    											</button>
    										</div>
    									</div>'
                                        data-target=".w3-links-target-2" data-max="8">
                                        Add New
                                    </button>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Link Widget Three</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update_settings') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Title </label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="widget_three">
                                    <input type="text" class="form-control" placeholder="Widget title"
                                        name="widget_three" value="{{ get_setting('widget_three', null, $lang) }}">
                                </div>
                                <div class="form-group">
                                    <label>Links </label>
                                    <div class="w3-links-target-3">
                                        <input type="hidden" name="types[][{{ $lang }}]"
                                            value="widget_three_labels">
                                        <input type="hidden" name="types[]" value="widget_three_links">
                                        @if (get_setting('widget_three_labels', null, $lang) != null && get_setting('widget_three_labels', null, $lang) != 'null')
                                            @foreach (json_decode(get_setting('widget_three_labels', null, $lang), true) as $key => $value)
                                                <div class="row gutters-5">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="Label"
                                                                name="widget_three_labels[]" value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="http://" name="widget_three_links[]"
                                                                value="{{ json_decode(get_setting('widget_three_links'), true)[$key] }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button"
                                                            class="mt-1 btn btn-icon btn-circle btn-soft-danger btn-sm"
                                                            data-toggle="remove-parent" data-parent=".row">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                        data-content='<div class="row gutters-5">
    										<div class="col-4">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="Label" name="widget_three_labels[]">
    											</div>
    										</div>
    										<div class="col">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="http://" name="widget_three_links[]">
    											</div>
    										</div>
    										<div class="col-auto">
    											<button type="button" class="mt-1 btn btn-icon btn-circle btn-soft-danger btn-sm" data-toggle="remove-parent" data-parent=".row">
    												<i class="las la-times"></i>
    											</button>
    										</div>
    									</div>'
                                        data-target=".w3-links-target-3" data-max="8">
                                        Add New
                                    </button>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-none bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Link Widget Four</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('business_settings.update_settings') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Title </label>
                                    <input type="hidden" name="types[][{{ $lang }}]" value="widget_four">
                                    <input type="text" class="form-control" placeholder="Widget title"
                                        name="widget_four" value="{{ get_setting('widget_four', null, $lang) }}">
                                </div>
                                <div class="form-group">
                                    <label>Links </label>
                                    <div class="w3-links-target-4">
                                        <input type="hidden" name="types[][{{ $lang }}]"
                                            value="widget_four_labels">
                                        <input type="hidden" name="types[]" value="widget_four_links">
                                        @if (get_setting('widget_four_labels', null, $lang) != null && get_setting('widget_four_labels', null, $lang) != 'null')
                                            @foreach (json_decode(get_setting('widget_four_labels', null, $lang), true) as $key => $value)
                                                <div class="row gutters-5">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="Label"
                                                                name="widget_four_labels[]" value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="http://" name="widget_four_links[]"
                                                                value="{{ json_decode(get_setting('widget_four_links'), true)[$key] }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button"
                                                            class="mt-1 btn btn-icon btn-circle btn-soft-danger btn-sm"
                                                            data-toggle="remove-parent" data-parent=".row">
                                                            <i class="las la-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                                        data-content='<div class="row gutters-5">
    										<div class="col-4">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="Label" name="widget_four_labels[]">
    											</div>
    										</div>
    										<div class="col">
    											<div class="form-group">
    												<input type="text" class="form-control" placeholder="http://" name="widget_four_links[]">
    											</div>
    										</div>
    										<div class="col-auto">
    											<button type="button" class="mt-1 btn btn-icon btn-circle btn-soft-danger btn-sm" data-toggle="remove-parent" data-parent=".row">
    												<i class="las la-times"></i>
    											</button>
    										</div>
    									</div>'
                                        data-target=".w3-links-target-4" data-max="8">
                                        Add New
                                    </button>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 
@endsection


@section('header')
    <style>
        .file-preview-item .thumb {
            -ms-flex: 0 0 50px;
            flex: 0 0 150px;
            max-width: 150px;
            height: 145px;
            width: 150px;
        }
    </style>
@endsection