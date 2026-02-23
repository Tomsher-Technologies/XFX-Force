@extends('backend.layouts.app')
@section('content')

@php
    $home = \App\Models\Page::where('type', 'home')->first();
    $homeSettings = $home ? json_decode($home->data, true) : [];
@endphp
<div class="row">
    <div class="col-xl-12 mx-auto">
        <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Home Page Settings</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">Home Sliders</label>
                            <select name="home_slider[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Sliders" data-selected="{{ json_encode($homeSettings['home_slider'] ?? []) }}">
                                @foreach ($sliders as $key => $slider)
                                <option value="{{ $slider->id }}">{{ $slider->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="col-from-label">Banners</label>
                            <select name="home_banners[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Banners" data-selected="{{ json_encode($homeSettings['home_banners'] ?? []) }}">
                                <option value="1">Banner 1</option>
                                <option value="2">Banner 2</option>
                                <option value="3">Banner 3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shop By categories section starts -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Shop By Categories</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">Main Title</label>
                            <input type="text" name="category_title" value="{{ $homeSettings['category_title'] ?? '' }}" class="form-control form-control-sm form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="col-from-label">Categories</label>
                            <select name="categories[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Categories" data-selected="{{ json_encode($homeSettings['categories'] ?? []) }}">
                                @foreach ($categories as $key => $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shop By categories section ends -->
            <!-- Play Without Limits, High - Performance Gaming PCS (Featured products) Section -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Featured Products</h6>
                </div>
                <div class="card-body">

                    {{-- Main Title --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Main Title</label>
                            <input type="text"
                                name="featured_products_title"
                                value="{{ $homeSettings['featured_products_title'] ?? '' }}"
                                class="form-control form-control-sm">
                        </div>
                    </div>

                    {{-- ================= NEW ARRIVALS ================= --}}
                    <div class="new-arrival-repeater border shadow p-3 mb-3">
                        <strong>New Arrivals</strong>
                        <hr>

                        <div data-repeater-list="new_arrivals">

                        @php
                            $newArrivals = $homeSettings['new_arrivals'] ?? [];
                        @endphp

                        @forelse($newArrivals as $item)
                            <div data-repeater-item>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label>Product</label>
                                        <select class="form-control form-control-sm aiz-selectpicker"
                                                name="featured_new_product_id"
                                                data-live-search="true">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ ($item['featured_new_product_id'] ?? '') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Image</label>
                                        <input type="text"
                                            name="featured_new_image"
                                            value="{{ $item['featured_new_image'] ?? '' }}"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Title</label>
                                        <input type="text"
                                            name="featured_new_title"
                                            value="{{ $item['featured_new_title'] ?? '' }}"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2 pr-0">
                                        <label>Sub Title</label>
                                        <input type="text"
                                            name="featured_new_sub_title"
                                            value="{{ $item['featured_new_sub_title'] ?? '' }}"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-1">
                                        <label class="d-block">&nbsp;</label>
                                        <button type="button"
                                                data-repeater-delete
                                                class="btn btn-danger btn-icon btn-sm">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div data-repeater-item>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label>Product</label>
                                        <select class="form-control form-control-sm aiz-selectpicker"
                                                name="featured_new_product_id"
                                                data-live-search="true">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Image</label>
                                        <input type="text"
                                            name="featured_new_image"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Title</label>
                                        <input type="text"
                                            name="featured_new_title"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2 pr-0">
                                        <label>Sub Title</label>
                                        <input type="text"
                                            name="featured_new_sub_title"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-1">
                                        <label class="d-block">&nbsp;</label>
                                        <button type="button"
                                                data-repeater-delete
                                                class="btn btn-danger btn-icon btn-sm">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforelse

                    </div>

                        <div class="text-right">
                            <button type="button"
                                    data-repeater-create
                                    class="btn btn-primary btn-xs">
                                Add More
                            </button>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- ================= POPULAR PRODUCTS ================= --}}
                    <div class="popular-products-repeater border shadow p-3 mb-3">
                        <strong>Popular Products</strong>
                        <hr>

                        <div data-repeater-list="popular_products">

                        @php
                            $popularProducts = $homeSettings['popular_products'] ?? [];
                        @endphp

                        @forelse($popularProducts as $item)
                            <div data-repeater-item>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label>Product</label>
                                        <select class="form-control form-control-sm aiz-selectpicker"
                                                name="featured_popular_product_id"
                                                data-live-search="true">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ ($item['featured_popular_product_id'] ?? '') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Image</label>
                                        <input type="text"
                                            name="featured_popular_image"
                                            value="{{ $item['featured_popular_image'] ?? '' }}"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Title</label>
                                        <input type="text"
                                            name="featured_popular_title"
                                            value="{{ $item['featured_popular_title'] ?? '' }}"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2 pr-0">
                                        <label>Sub Title</label>
                                        <input type="text"
                                            name="featured_popular_sub_title"
                                            value="{{ $item['featured_popular_sub_title'] ?? '' }}"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-1">
                                        <label class="d-block">&nbsp;</label>
                                        <button type="button"
                                                data-repeater-delete
                                                class="btn btn-danger btn-icon btn-sm">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div data-repeater-item>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label>Product</label>
                                        <select class="form-control form-control-sm aiz-selectpicker"
                                                name="featured_popular_product_id"
                                                data-live-search="true">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Image</label>
                                        <input type="text"
                                            name="featured_popular_image"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Title</label>
                                        <input type="text"
                                            name="featured_popular_title"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-2 pr-0">
                                        <label>Sub Title</label>
                                        <input type="text"
                                            name="featured_popular_sub_title"
                                            class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-1">
                                        <label class="d-block">&nbsp;</label>
                                        <button type="button"
                                                data-repeater-delete
                                                class="btn btn-danger btn-icon btn-sm">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforelse

                    </div>

                        <div class="text-right">
                            <button type="button"
                                    data-repeater-create
                                    class="btn btn-primary btn-xs">
                                Add More
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Play Without Limits, High - Performance Gaming PCS Section end -->
            <!-- Upcoming Products Section starts -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Upcoming Products</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">Main Title</label>
                            <input type="text" name="upcoming_products_title" value="{{ $homeSettings['upcoming_products_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">New Arrivals</label>
                            <select name="upcoming_new_products[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Sliders" data-selected="{{ json_encode($homeSettings['upcoming_new_products'] ?? []) }}">
                                @foreach ($products as $key => $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="col-from-label">Popular Products</label>
                            <select name="upcoming_popular_products[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Sliders" data-selected="{{ json_encode($homeSettings['upcoming_popular_products'] ?? []) }}">
                                @foreach ($products as $key => $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Upcoming products section ends -->
            <!-- Middle Banner section starts -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Middle Banners</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">Main Title</label>
                            <input type="text" name="middle_banner_title" value="{{ $homeSettings['middle_banner_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="col-from-label">Banners</label>
                            <select name="middle_banners[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Banners" data-selected="{{ json_encode($homeSettings['middle_banners'] ?? []) }}">
                                <option value="1">Banner 1</option>
                                <option value="2">Banner 2</option>
                                <option value="3">Banner 3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Middle Banner section ends -->
             <!-- Featured Custom Pre - Build Gaming PCs (Middle featured Products)-->
              <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Middle Featured Products</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">Main Title</label>
                            <input type="text" name="middle_featured_products_title" value="{{ $homeSettings['middle_featured_products_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">New Arrivals</label>
                            <select name="middle_featured_new_arrivals[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Sliders" data-selected="{{ json_encode($homeSettings['middle_featured_new_arrivals'] ?? []) }}">
                                @foreach ($products as $key => $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="col-from-label">Popular Products</label>
                            <select name="middle_featured_popular_products[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Sliders" data-selected="{{ json_encode($homeSettings['middle_featured_popular_products'] ?? []) }}">
                                @foreach ($products as $key => $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
              <!-- Featured Custom Pre - Build Gaming PCs ends -->
               <!-- Middle Full Banner section starts -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Middle Full Banner</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">Main Title</label>
                            <input type="text" name="middle_full_banner_title" value="{{ $homeSettings['middle_full_banner_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="col-from-label">Banners</label>
                            <select name="middle_full_banner[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Banners" data-selected="{{ json_encode($homeSettings['middle_full_banner'] ?? []) }}">
                                <option value="1">Banner 1</option>
                                <option value="2">Banner 2</option>
                                <option value="3">Banner 3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Middle Full Banner section ends -->
            <!-- Best Deals Section starts -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Best Deals</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">Main Title</label>
                            <input type="text" name="best_deals_title" value="{{ $homeSettings['best_deals_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="col-from-label">Products</label>
                            <select name="best_deals_products[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Sliders" data-selected="{{ json_encode($homeSettings['best_deals_products'] ?? []) }}">
                                @foreach ($products as $key => $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Best Deals section ends -->

            <!-- Power Your Passion: Gaming & Pro Grade Builds Section starts (Product Gallery) -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Product Gallery</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">Main Title</label>
                            <input type="text" name="product_gallery_title" value="{{ $homeSettings['product_gallery_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="col-from-label">Products</label>
                            <select name="product_gallery_products[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Sliders" data-selected="{{ json_encode($homeSettings['product_gallery_products'] ?? []) }}" data-max-options="1">
                                @foreach ($products as $key => $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Power Your Passion: Gaming & Pro Grade Builds section ends -->

            <!-- Graphic Cards Section starts -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Graphic Cards</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="col-from-label">Main Title</label>
                            <input type="text" name="graphic_cards_title" value="{{ $homeSettings['graphic_cards_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="col-from-label">Products</label>
                            <select name="graphic_cards_products[]" class="form-control form-control-sm aiz-selectpicker" multiple data-actions-box="true" data-live-search="true" title="Select Sliders" data-selected="{{ json_encode($homeSettings['graphic_cards_products'] ?? []) }}">
                                @foreach ($products as $key => $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Graphic Cards section ends -->
            <!-- Testimonials Section starts -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Testimonials</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-from-label">Main Title</label>
                            <input type="text" name="testimonials_title" value="{{ $homeSettings['testimonials_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-from-label">Rating Count</label>
                            <input type="text" name="testimonials_rating_count" value="{{ $homeSettings['testimonials_rating_count'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="col-from-label">Rating Title</label>
                            <input type="text" name="testimonials_rating_title" value="{{ $homeSettings['testimonials_rating_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="col-from-label">Customer Count</label>
                            <input type="text" name="testimonials_customer_count" value="{{ $homeSettings['testimonials_customer_count'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="col-from-label">Customer Title</label>
                            <input type="text" name="testimonials_customer_title" value="{{ $homeSettings['testimonials_customer_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Testimonials section ends -->

            <!-- Home Footer Section starts -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Footer Section</h6>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-from-label">Main Title</label>
                            <input type="text" name="footer_title" value="{{ $homeSettings['footer_title'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="col-from-label">Image</label>
                            <input type="file" name="footer_image" value="{{ $homeSettings['footer_image'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="col-from-label">Content</label>
                            <input type="text" name="footer_content" value="{{ $homeSettings['footer_content'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="col-from-label">Button Text</label>
                            <input type="text" name="footer_button_text" value="{{ $homeSettings['footer_button_text'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="col-from-label">Button Link</label>
                            <input type="text" name="footer_button_link" value="{{ $homeSettings['footer_button_link'] ?? '' }}" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Home Footer section ends -->

            <div class="text-right">
                <button type="submit" class="btn btn-info">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    // $(document).ready(function() {
    //     AIZ.plugins.bootstrapSelect('refresh');

    //     $('.aiz-selectpicker').on('shown.bs.select', function() {
    //         var select = $(this);
    //         var selectedOptions = select.find('option:selected').detach();
    //         select.prepend(selectedOptions);
    //         select.selectpicker('refresh');
    //     });
    // });

    $('.remove-galley').on('click', function() {
        thumbnail = $(this)
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{ route('page.delete_image') }}",
            data: {
                url: $(thumbnail).data('url'),
                id: '{{ $page->id }}'
            },
            success: function(data) {
                if (data == 1) {
                    $(thumbnail).closest('.file-preview-item').remove();
                    AIZ.plugins.notify('success', "{{ trans('messages.image').trans('messages.deleted_msg') }}");
                } else {
                    AIZ.plugins.notify('danger', "{{ trans('messages.something_went_wrong')}}");
                }

            }
        });
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"
    integrity="sha512-foIijUdV0fR0Zew7vmw98E6mOWd9gkGWQBWaoA1EOFAx+pY+N8FmmtIYAVj64R98KeD2wzZh1aHK0JSpKmRH8w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script type="text/javascript">
    $(function() {

        // Initialize New Arrivals Repeater
        $('.new-arrival-repeater').repeater({
            initEmpty: false,
            defaultValues: {},
            show: function() {
                $(this).slideDown();
                $(this).find('.bootstrap-select').find('.btn.dropdown-toggle').remove();
                $(this).find('.aiz-selectpicker').selectpicker('refresh');
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        // Initialize Popular Products Repeater
        $('.popular-products-repeater').repeater({
            initEmpty: false,
            defaultValues: {},
            show: function() {
                $(this).slideDown();
                $(this).find('.bootstrap-select').find('.btn.dropdown-toggle').remove();
                $(this).find('.aiz-selectpicker').selectpicker('refresh');
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    });
</script>
@endsection