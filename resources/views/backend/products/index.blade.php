@extends('backend.layouts.app')

@section('content')
<style>
    .bread .breadcrumb {
        all: unset;
    }

    .bread .breadcrumb li {
        display: inline-block;
    }

    .bread nav {
        display: inline-block;
        max-width: 250px;
    }

    .bread .breadcrumb-item+.breadcrumb-item::before {
        content: ">";
    }

    .breadcrumb-item+.breadcrumb-item {
        padding-left: 0;
    }

    .bread a {
        pointer-events: none;
        cursor: sw-resize;
    }
</style>
    <div class="aiz-titlebar text-left mt-2 ">
        <div class="row align-items-center">
            <div class="col-auto">
                <h5 class="h5">All products</h5>
            </div>
            @can('add_product')
                <div class="col text-right">
                    <a href="{{ route('products.create') }}" class="btn btn-circle btn-info btn-sm">
                        <span>Add New Product</span>
                    </a>
                </div>    
            @endif
        </div>
    </div>
    <br>

    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                {{-- <div class="col">
                    <h5 class="mb-md-0 h5">Products</h5>
                </div> --}}

               
                <div class="col-md-3 bootstrap-select">
                    
                    <select class="form-control form-control-sm aiz-selectpicker mb-md-0" data-live-search="true"
                            name="category" id="" data-selected={{ $category }}>
                        <option value="0">All</option>
                        @foreach (getAllCategories()->where('parent_id', 0) as $item)
                            <option value="{{ $item->id }}" @if( $category == $item->id)  {{ 'selected' }} @endif )>{{ $item->name }}</option>
                            @if ($item->child)
                                @foreach ($item->child as $cat)
                                    @include('backend.categories.menu_child_category', [
                                        'category' => $cat,
                                        'old_data' => $category,
                                    ])
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 bootstrap-select">
                    <select class="form-control form-control-sm aiz-selectpicker mb-md-0" name="type" id="type"
                        onchange="sort_products()">
                        <option value="">Sort By</option>
                        <option value="rating,desc"
                            @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'desc') selected @endif @endisset>
                            Rating (High > Low)</option>
                        <option value="rating,asc"
                            @isset($col_name, $query) @if ($col_name == 'rating' && $query == 'asc') selected @endif @endisset>
                            Rating (Low > High)</option>
                        <option
                            value="num_of_sale,desc"@isset($col_name, $query) @if ($col_name == 'num_of_sale' && $query == 'desc') selected @endif @endisset>
                            Num of Sale (High > Low)</option>
                        <option
                            value="num_of_sale,asc"@isset($col_name, $query) @if ($col_name == 'num_of_sale' && $query == 'asc') selected @endif @endisset>
                            Num of Sale (Low > High)</option>
                        <option
                            value="unit_price,desc"@isset($col_name, $query) @if ($col_name == 'unit_price' && $query == 'desc') selected @endif @endisset>
                            Base Price (High > Low)</option>
                        <option
                            value="unit_price,asc"@isset($col_name, $query) @if ($col_name == 'unit_price' && $query == 'asc') selected @endif @endisset>
                            Base Price (Low > High)</option>
                        <option
                            value="status,1"@isset($col_name, $query) @if ($col_name == 'status' && $query == '1') selected @endif @endisset>
                            Published</option>
                        <option
                            value="status,0"@isset($col_name, $query) @if ($col_name == 'status' && $query == '0') selected @endif @endisset>
                            Unpublished</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control form-control-sm" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="Type & Enter">
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-info btn-sm" type="submit">Filter</button>
                    <a href="{{ route('products.all') }}" class="btn btn-cancel btn-sm">Reset</a>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            {{-- <th>
                                <div class="form-group">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            </th> --}}
                            <th>#</th>
                            <th>{{ trans('messages.name') }}</th>
                            <th >{{ trans('messages.category') }}</th>
                            <th >{{ trans('messages.info') }}</th>
                            <th class="text-center">{{ trans('messages.total_stock') }}</th>
                            {{-- <th data-breakpoints="lg">{{translate('Todays Deal')}}</th> --}}
                            <th class="text-center">{{ trans('messages.published') }}</th>
                            {{-- <th data-breakpoints="lg">{{translate('Featured')}}</th> --}}
                            <th class="text-center">{{ trans('messages.options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                {{-- <td>
                                    <div class="form-group d-inline-block">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-one" name="id[]"
                                                value="{{ $product->id }}">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </td> --}}
                                <td>
                                    <div class="row gutters-5 w-200px w-md-250px mw-100">

                                        @if ($product->thumbnail_img)
                                            <div class="col-auto">
                                                <img src="{{ product_image_url($product->thumbnail_img, 300) }}" alt="Image" class="size-50px img-fit">
                                            </div>
                                        @endif


                                        <div class="col">
                                            <span class="text-muted text-truncate-2">{{ $product->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="bread">
                                    {{ Breadcrumbs::render('product_admin', $product) }}
                                </td>
                                <td>
                                    <strong>{{ trans('messages.no_of_sale') }}:</strong> {{ $product->num_of_sale }}
                                    {{ trans('messages.times') }} <br>
                                    {{-- <strong>{{ translate('Base Price') }}:</strong> --}}
                                    {{-- {{ single_price($product->unit_price) }} </br> --}}
                                    <strong>{{ trans('messages.rating') }}:</strong> {{ $product->rating }} <br>
                                    <strong>{{ trans('messages.sku') }}:</strong> {{ $product->sku }} <br>
                                </td>
                                <td class="text-center">
                                    @php
                                        $qty = 0;
                                        if ($product->product_type == 1) {
                                            foreach ($product->stocks->where('type','variant') as $key => $stock) {
                                                $qty += $stock->qty;
                                                echo $stock->sku . ' - <b>' . $stock->qty . '</b><br>';
                                            }
                                        } else {
                                            $qty = optional($product->stocks->first())->qty;
                                            echo $product->stocks->first()->sku . ' - <b>'.$qty.'</b>';
                                        }
                                    @endphp
                                    @if ($qty <= $product->low_stock_quantity)
                                        <span class="badge badge-inline badge-danger">Low</span>
                                    @endif
                                </td>
                             
                                <td class="text-center">
                                    @can('edit_product')
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_published(this)" value="{{ $product->id }}"
                                                type="checkbox" <?php if ($product->published == 1) {
                                                    echo 'checked';
                                                } ?>>
                                            <span class="slider round"></span>
                                        </label>
                                    @else
                                        @if ($product->published == 1)
                                            <span class="badge badge-inline badge-success">Published</span>
                                        @else
                                            <span class="badge badge-inline badge-danger">Unpublished</span>
                                        @endif
                                    @endcan
                                    
                                </td>
                              
                                <td class="text-center">
                                    {{-- <a class="btn btn-soft-success btn-icon btn-circle"
                                        href="{{ route('product', $product->slug) }}" target="_blank" title="View">
                                        <i class="las la-eye"></i>
                                    </a> --}}
                                    @can('edit_product')
                                        <a class="btn btn-soft-primary btn-icon btn-circle" href="{{ route('products.edit', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                        title="Edit">
                                            <i class="las la-edit"></i>
                                        </a>
                                    @endcan
                                    
                                    @can('delete_product')
                                        
                                    
                                        {{-- <a class="btn btn-soft-warning btn-icon btn-circle" href="{{route('products.duplicate', ['id'=>$product->id, 'type'=>$type]  )}}" title="Duplicate">
                                            <i class="las la-copy"></i>
                                        </a> --}}
                                        {{-- <a href="#" class="btn btn-soft-danger btn-icon btn-circle confirm-delete"
                                            data-href="{{ route('products.destroy', $product->id) }}" title="Delete">
                                            <i class="las la-trash"></i>
                                        </a> --}}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $products->appends(request()->input())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </form>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        function update_published(el) {
            if (el.checked) {
                var status = 1;
            } else {
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Published products updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }

        function sort_products(el) {
            $('#sort_products').submit();
        }

    </script>
@endsection
