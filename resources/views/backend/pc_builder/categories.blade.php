@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">PC Builder Settings</h5>
    </div>

    <form method="POST" id="pcBuilderForm" action="{{ route('pc-builder.categories.store') }}">
        @csrf

        <div id="categoryContainer">

        {{-- EXISTING CATEGORIES --}}
        @foreach($settings as $setting)
        <div class="card shadow-sm mb-3 category-card" data-id="{{ $setting->category_id }}">
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group  d-flex align-items-center">
                        <span class="drag-handle mr-2 mt-4">☰</span>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="medium ">Category</label>
                        <select class=" form-control form-control-sm new-category-select" name="categories[{{ $setting->category_id }}][category_id]" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat['id'] }}"
                                    {{ $cat['id'] == $setting->category_id ? 'selected' : '' }}>
                                    {{ $cat['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label class="medium ">Min Select</label>
                        <input type="number" class="form-control form-control-sm"
                            name="categories[{{ $setting->category_id }}][min_select]"
                            value="{{ $setting->min_select }}">
                    </div>

                    <div class="form-group col-md-2">
                        <label class="medium ">Max Select</label>
                        <input type="number" class="form-control form-control-sm"
                            name="categories[{{ $setting->category_id }}][max_select]"
                            value="{{ $setting->max_select }}" placeholder="Unlimited">
                    </div>

                    <div class="form-group col-md-2">
                        <label class="medium ">Sort Order</label>
                        <input type="number" class="form-control form-control-sm sort-order"
                            name="categories[{{ $setting->category_id }}][sort_order]"
                            value="{{ $setting->sort_order }}">
                    </div>

                    <div class="form-group col-md-2 m-auto">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-category mt-2">Remove</button>
                    </div>
                </div>

                
            </div>
        </div>
        @endforeach

        </div>

        <div class="text-right mb-3">
            <button type="button" class="btn btn-outline-primary btn-sm" id="addCategoryBtn">
                + Add Category
            </button>
        </div>

        <button class="btn btn-primary btn-sm mb-3">Save Settings</button>
    </form>

@endsection

@section('style')
    <style>
        .drag-handle {
            cursor: grab;
            color: #9ca3af;
            font-size: 16px;
        }
        .drag-handle:hover {
            color: #4f46e5;
        }

        /* Make dropdown full width */
        .new-category-select, select.form-control {
            width: 100% !important;
        }

        /* Subcategory container styles */
        .subcategories-container {
            /* background: #f8f9fa; */
            border-radius: 6px;
            /* padding: 10px; */
            /* display: flex; */
            flex-wrap: wrap;
            gap: 2%;
        }
        .subcategories-container .custom-control {
            flex: 0 0 auto;
        }
        .custom-control-label::before {
            top: 0;
        }
        .custom-control-label::after {
            top: 0;
        }
        .custom-switch .custom-control-label::before {
            left: 2rem;
            width: 1.75rem;
            pointer-events: all;
            border-radius: .5rem;
            top: 2rem;
        }
        .custom-switch .custom-control-label::after {
            top: calc(2rem + 2px);
            left: calc(2rem + 2px);
            width: calc(1rem - 4px);
            height: calc(1rem - 4px);
        }

        .subcat-checkbox:disabled + label {
            opacity: 0.5;
            cursor: not-allowed;
        }

    </style>
@endsection

@section('script')
    <script src="{{ asset('assets/js/Sortable.min.js') }}"></script> 

    <script>
        let categories = @json($categories);
        let newIndex = 0;

        /* ADD CATEGORY */
        document.getElementById('addCategoryBtn').addEventListener('click', () => {
            newIndex++;

            let select = `<select class=" form-control form-control-sm new-category-select" name="categories[new${newIndex}][category_id]" required>
                <option value="">Select Category</option>`;
            categories.forEach(cat => {
                select += `<option value="${cat.id}">${cat.name}</option>`;
            });
            select += `</select>`;

            let html = `
            <div class="card shadow-sm mb-3 category-card" data-index="${newIndex}">
                <div class="card-body">
                    <div class="form-row">

                        <div class="form-group  d-flex align-items-center">
                            <span class="drag-handle mr-2 mt-4">☰</span>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="medium ">Category</label>
                            ${select}
                        </div>

                        <div class="form-group col-md-2">
                            <label class="medium ">Min Select</label>
                            <input type="number" name="categories[new${newIndex}][min_select]"
                                value="0" class="form-control form-control-sm">
                        </div>

                        <div class="form-group col-md-2">
                            <label class="medium ">Max Select</label>
                            <input type="number" name="categories[new${newIndex}][max_select]"
                                class="form-control form-control-sm" placeholder="Unlimited">
                        </div>

                        <div class="form-group col-md-2">
                            <label class="medium ">Sort Order</label>
                            <input type="number" name="categories[new${newIndex}][sort_order]"
                                class="form-control form-control-sm sort-order" value="0">
                        </div>

                       
                        <div class="form-group col-md-2 m-auto">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-category">Remove</button>
                        </div>
                    </div>
                </div>
            </div>`;

            document.getElementById('categoryContainer')
                .insertAdjacentHTML('beforeend', html);

            updateSortOrder();
        });

        /* REMOVE CATEGORY */
        document.addEventListener('click', e => {
            if (e.target.classList.contains('remove-category')) {
                e.target.closest('.category-card').remove();
                updateSortOrder();
            }
        });


        /* FORM SUBMIT VALIDATION */
        $('#pcBuilderForm').on('submit', function (e) {
            e.preventDefault();

            let form = $(this);
            let valid = true;

            if (!valid) return;

            // AJAX submit
            $.ajax({
                url: form.attr('action'),
                type: "POST",
                data: form.serialize(),
                beforeSend: function () {
                    $('button[type="submit"]').prop('disabled', true).text('Saving...');
                },
                success: function (response) {
                    if (response.status) {
                        AIZ.plugins.notify('success', response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        AIZ.plugins.notify('danger', response.message || 'Failed to save settings');
                    }
                },
                error: function (xhr) {
                    if (xhr.responseJSON?.errors) {
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            AIZ.plugins.notify('danger', value[0]);
                        });
                    } else {
                        AIZ.plugins.notify('danger', 'Something went wrong.');
                    }
                },
                complete: function () {
                    $('button[type="submit"]').prop('disabled', false).text('Save Settings');
                }
            });
        });

        /* DRAG & DROP */
        new Sortable(document.getElementById('categoryContainer'), {
            animation: 150,
            handle: '.drag-handle',
            onEnd: updateSortOrder
        });

        function updateSortOrder() {
            let order = 1;
            document.querySelectorAll('.sort-order').forEach(input => {
                input.value = order++;
            });
        }
    </script>

@endsection
