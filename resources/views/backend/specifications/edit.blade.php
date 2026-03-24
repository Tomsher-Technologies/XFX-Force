@extends('backend.layouts.app')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Specification Builder - <span class="font-italic">{{ $specification->main_title }}</span></h5>
        <a href="{{ route('specifications.index', ['page' => request('page')]) }}"
            class="btn btn-secondary btn-sm" title="Back to list">
            <i class="las la-arrow-left"></i>
        </a>
    </div>

    <div class="card-body">
        <form method="POST"
            action="{{ route('specifications.update', $specification->id) }}">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="main_title" class="form-control form-control-sm" placeholder="Title" required value="{{ $specification->main_title }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="display_title" class="form-control form-control-sm" placeholder="Display Title" required value="{{ $specification->display_title }}">
                </div>

                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm" required>
                        <option value="">Select Status</option>
                        <option value="1" {{ $specification->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $specification->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    <button type="reset" class="btn btn-cancel btn-sm">Cancel</button>
                </div>
            </div>
        </form>
        <hr />

        <form method="POST" action="{{ route('specifications.saveSpecificationDetails', $specification->id) }}">
            @csrf

            <input type="hidden" name="main_specification_id" value="{{ $specification->id }}">
            <div id="main-wrapper">
                <div class="p-3 main-block">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Specifications</h6>

                        <!-- TOP BUTTON -->
                        <button type="button" 
                            class="btn btn-warning btn-xs add-level1 add-top d-none text-white fw-500">
                            Add Specification
                        </button>
                    </div>
                    <div class="level1-wrapper mt-3">
                        <!-- Specification Items list -->
                        @foreach($items as $level1)
                        <div class="shadow-lg p-3 mt-2 ms-4 level1-block"
                            data-id="{{ $level1->id }}">
                            <div class="row align-items-center">
                                <!-- Level 1 Title -->
                                <div class="col-md-3">
                                    <input type="text"
                                        name="specifications[main][children][{{ $level1->id }}][title]"
                                        value="{{ $level1->title }}"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number"
                                        name="specifications[main][children][{{ $level1->id }}][sort_order]"
                                        value="{{ $level1->sort_order }}"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <select class="form-control form-control-sm"
                                        name="specifications[main][children][{{ $level1->id }}][status]" required>
                                        <option value="">Select Status</option>
                                        <option value="1" {{ $level1->status==1?'selected':'' }}>Active</option>
                                        <option value="0" {{ $level1->status==0?'selected':'' }}>Inactive</option>
                                    </select>
                                    <button type="button" class="remove-level1 border-0 bg-transparent">
                                        <i class="las la-trash text-danger fs-20"></i>
                                    </button>
                                </div>
                                <div class="col-md-3 pl-0">
                                    <button type="button"
                                        class="btn btn-success btn-xs add-level2">
                                        Add Items
                                    </button>
                                </div>
                            </div>
                            {{-- LEVEL 2 --}}
                            @if($level1->subItems->count())
                            <h6 class="level2-heading mt-3">Items</h6>
                            @endif
                            <div class="level2-wrapper mt-3 shadow-inset bg-light">
                                @foreach($level1->subItems as $level2)
                                <div class="px-3 py-2 ms-5 level2-block">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <input type="text"
                                                name="specifications[main][children][{{ $level1->id }}][children][{{ $level2->id }}][title]"
                                                value="{{ $level2->title }}"
                                                class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number"
                                                name="specifications[main][children][{{ $level1->id }}][children][{{ $level2->id }}][sort_order]"
                                                value="{{ $level2->sort_order }}"
                                                class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-md-3 d-flex gap-2">
                                            <select class="form-control form-control-sm"
                                                name="specifications[main][children][{{ $level1->id }}][children][{{ $level2->id }}][status]" required>
                                                <option value="">Select Status</option>
                                                <option value="1" {{ $level2->status==1?'selected':'' }}>Active</option>
                                                <option value="0" {{ $level2->status==0?'selected':'' }}>Inactive</option>
                                            </select>

                                            <button type="button"
                                                class="remove-level2 border-0 bg-transparent">
                                                <i class="las la-trash text-danger fs-20"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                        <!-- End Specification Items list -->
                    </div>
                    <div class="text-end mt-3 d-none" id="add-bottom-wrapper">
                        <button type="button"
                            class="btn btn-warning btn-xs add-level1 text-white fw-500">
                            Add Specification
                        </button>
                    </div>
                </div>
            </div>
            <div class="text-right {{ $items->count() ? '' : 'd-none' }}" id="form-actions">
                <button type="submit" class="btn btn-primary btn-sm">
                    Save
                </button>
                <a href="{{ route('specifications.index', ['page' => request('page')]) }}"
                    class="btn btn-cancel btn-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')

<script>
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("add-level1")) {
            let wrapper = e.target
                .closest(".main-block")
                .querySelector(".level1-wrapper");

            let id = Date.now();

            let html = `<div class="shadow-lg p-3 mt-2 ms-4 level1-block" data-id="${id}">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <input type="text"
                                name="specifications[main][children][${id}][title]"
                                class="form-control form-control-sm" placeholder="Specification" required>
                        </div>

                        <div class="col-md-3">
                            <input type="number"
                                value="0"
                                min="0"
                                name="specifications[main][children][${id}][sort_order]"
                                class="form-control form-control-sm" placeholder="Sort Order">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <select class="form-select form-control form-control-sm"
                                name="specifications[main][children][${id}][status]" required>
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>

                            <button type="button" class="border-0 bg-transparent remove-level1">
                            <i class="las la-trash text-danger fs-20"></i>
                                                    </button>
                        </div>
                        <div class="col-md-3 pl-0">
                            <button type="button"
                                class="btn btn-success btn-xs add-level2">
                                Add Items
                            </button>
                        </div>
                    </div>
                    <div class="level2-wrapper mt-3 shadow-inset bg-light"></div>
                </div>`;
            wrapper.insertAdjacentHTML("beforeend", html);
            document.getElementById("form-actions").classList.remove("d-none");
            toggleAddButtonPosition();
        }

        /* ---- ADD LEVEL 3 ---- */
        if (e.target.classList.contains("add-level2")) {
            let wrapper = e.target
                .closest(".level1-block")
                .querySelector(".level2-wrapper");

            if (!e.target.closest(".level1-block").querySelector(".level2-heading")) {
                wrapper.insertAdjacentHTML(
                    "beforebegin",
                    `<h6 class="level2-heading mt-3">Items</h6>`
                );
            }

            let parentId = e.target
                .closest(".level1-block")
                .getAttribute("data-id");

            let id = Date.now();

            let html = `
                <div class="px-3 py-2 ms-5 level2-block">

                <div class="row align-items-center">

                <div class="col-md-3">
                    <input type="text"
                        name="specifications[main][children][${parentId}][children][${id}][title]"
                        class="form-control form-control-sm" placeholder="Item" required>
                </div>

                <div class="col-md-3">
                    <input type="number"
                        value="0"
                        name="specifications[main][children][${parentId}][children][${id}][sort_order]"
                        class="form-control form-control-sm" placeholder="Sort Order" min="0">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <select class="form-select form-control form-control-sm"
                        name="specifications[main][children][${parentId}][children][${id}][status]" required>
                        <option value="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <button type="button"
                        class="border-0 bg-transparent remove-level2">
                        <i class="las la-trash text-danger fs-20"></i>
                    </button>
                </div>

                </div>

                </div>
                `;
            wrapper.insertAdjacentHTML("beforeend", html);
        }

    });

    function toggleActions() {
        let count = document.querySelectorAll(".level1-block").length;

        if (count === 0) {
            document.getElementById("form-actions").classList.add("d-none");
        }
    }

    function toggleAddButtonPosition() {

    let count = document.querySelectorAll('.level1-block').length;

    if(count === 0){
        $('.add-top').removeClass('d-none');
        $('#add-bottom-wrapper').addClass('d-none');
    }else{
        $('.add-top').addClass('d-none');
        $('#add-bottom-wrapper').removeClass('d-none');
    }
}


    /** Removal blocks */
    let pendingDeleteElement = null;
    let headingElement = null;
    $(document).on('click', '.remove-level1', function() {
        pendingDeleteElement = $(this).parents('.level1-block');
        $('#delete-modal').modal('show');
    });

    $(document).on('click', '.remove-level2', function() {
        pendingDeleteElement = $(this).closest('.level2-block');
        headingElement =  $(this).closest('.level1-block');
        $('#delete-modal').modal('show');
    });

    $(document).on('click', '#delete-link', function(e) {
        e.preventDefault();

        if (pendingDeleteElement) {
            pendingDeleteElement.remove();
            pendingDeleteElement = null;
        }
        toggleAddButtonPosition();

        if (headingElement && headingElement.find('.level2-block').length === 0) {
            headingElement.find('.level2-heading').remove();
        }

        toggleActions();

        $('#delete-modal').modal('hide');
    });

    toggleAddButtonPosition();
</script>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection