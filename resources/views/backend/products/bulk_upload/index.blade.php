@extends('backend.layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">Product Bulk Upload</h5>
        </div>
        <div class="card-body">
            <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                <strong>Step 1:</strong>
                <p>1. Download the skeleton file and fill it with proper data.</p>
                <p>2. You can download the example file to understand how the data must be filled.</p>
                <p>3. Once you have downloaded and filled the skeleton file, upload it in the form below and submit.</p>
                {{-- <p>4. {{translate('After uploading products you need to edit them and set product\'s images and choices')}}.</p> --}}
            </div>
            <br>
            <div class="">
                <a href="{{ asset('assets/download/product_bulk_import_sample.xlsx') }}" download><button class="btn btn-info btn-sm">Download Excel File</button></a>
            </div>
            {{-- <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
                <strong>{{translate('Step 2')}}:</strong>
                <p>1. {{translate('Category and Brand should be in numerical id')}}.</p>
                <p>2. {{translate('You can download the pdf to get Category and Brand id')}}.</p>
            </div>
            <br>
            <div class="">
                <a href="{{ route('pdf.download_category') }}"><button class="btn btn-info">{{translate('Download Category')}}</button></a>
                <a href="{{ route('pdf.download_brand') }}"><button class="btn btn-info">{{translate('Download Brand')}}</button></a>
            </div> --}}
            <br>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">Upload Product File</h5>
            <a href="javascript:void(0);" id="toggleInstructions" class="text-primary">
                <strong>View Import Instructions</strong>
            </a>
        </div>
        <div class="card-body">
            <div id="importInstructions" style="display: none; margin-bottom: 20px;" class="alert shadow">
                <p><strong>Instructions:</strong></p>
                <ol class="pl-3 lh18">
                    <li>
                        SKU - This field is mandatory for every product.
                    </li>
                    <li>
                        Parent SKU - For variant products, the parent product must have a value here. 
                        Entries with the same <code>parent_sku</code> belong to the same product.
                    </li>
                    <li>
                        Category & Brand - Fill with values that already exist in the system.
                    </li>
                    <li>
                        Condition - Allowed values: <code>New</code> / <code>Refurbished </code> / <code>Open Box</code>
                    </li>
                    <li>
                        Specification - Format: <code><i><strong> (specification_name:specification_value)</strong></i></code>.
                        Multiple specifications should be separated by commas. 
                        Only use already added specification values.
                    </li>
                    <li>
                        Tags - Comma-separated values. (Eg: gaming, high-end, RGB )
                    </li>
                    <li>
                        Return / Refund - Allowed values: <code>yes</code> / <code>no</code>.
                    </li>
                    <li>
                        URL Fields : (<code>url_1</code> → Thumbnail image &nbsp; <code>url_2</code> → Gallery photos (multiple images separated by commas))
                    </li>
                    <li>
                        Published - Allowed values: <code>yes</code> / <code>no</code>.
                    </li>
                    <li>
                        Discount Start & End Date - Format: <code>dd/mm/yyyy</code>
                    </li>
                    <li>
                        Discount Type - Allowed values:<code> percentage</code> / <code>flat</code>
                    </li>
                    <li>
                        Video Provider - Allowed values:<code>youtube</code> / <code>vimeo</code>
                    </li>
                    <li>
                        Video Link - Provide the full URL of the video.
                    </li>
                    <li>
                        Attribute - Format:  <code><i><strong>( attribute_name:attribute_value )</strong></i></code>.
                        Multiple attributes should be separated by commas. 
                        Only use already added values.
                    </li>
                    <li>
                        Stock Status - Allowed values: <code>active</code> / <code>inactive</code>
                    </li>
                    <li>
                        Tab Details - Multiple entries allowed. Follow this pattern:
                        <div class="shadow p-2 my-2">
                            Tab 1 heading, Tab 1 description,<br>
                            Tab 2 heading, Tab 2 description, ...<br>
                            Tab x heading, Tab x description
                        </div>
                    </li>
                    <li>
                        Warranty Details - Multiple entries allowed. Follow this pattern:
                        <div class="shadow p-2 my-2">
                            Warranty 1 title, Warranty 1 price, Warranty 1 months, Warranty 1 description,<br>
                            Warranty 2 title, Warranty 2 price, Warranty 2 months, Warranty 2 description, ...<br>
                            Warranty x title, Warranty x price, Warranty x months, Warranty x description
                        </div>
                    </li>
                </ol>
            </div>
            <form class="form-horizontal" action="{{ route('bulk_product_upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-sm-9">
                        <div class="custom-file">
    						<label class="custom-file-label">
    							<input type="file" name="bulk_file" class="custom-file-input form-control form-control-sm" required>
    							<span class="custom-file-name">Choose File</span>
    						</label>
    					</div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-info btn-sm">Upload File</button>
                </div>
            </form>
        </div>
    </div>

@endsection
