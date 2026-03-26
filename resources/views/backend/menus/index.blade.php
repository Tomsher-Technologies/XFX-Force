@extends('backend.layouts.app')

@section('style')
<style>
    .list-group-item {
        position: relative;
        display: block;
        padding: .4rem 1.25rem;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, .125);
    }
    .drag-handle {
        cursor: grab;
    }

    .drag-handle:active {
        cursor: grabbing;
    }
</style>
@endsection


@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center justify-between">
        <div class="col">
            <h3 class="h5">Menu Builder</h3>
        </div>
        @php
            $menuCount = getMenuCount();
        @endphp

        @if($menuCount < 7)
            <div class="col text-right">
                <button id="addMenuBtn" class="btn btn-primary btn-sm">Add New Menu</button>
            </div>
        @endif
        
    </div>
</div>

<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card p-3">

            <!-- MENU TREE -->
            <ul id="menu-tree" class="list-group">
                @foreach($menus as $menu)
                <li data-id="{{ $menu->id }}" class="list-group-item mb-1" style="border-top-width: thin;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-16">
                            <i class="las la-arrows-alt-v drag-handle mr-2 text-muted"></i>
                            <strong>{{ $menu->title }}</strong> ({{ ucfirst($menu->type) }})
                        </span>
                        <span>
                            <button class="btn btn-xs btn-soft-secondary edit-btn" data-id="{{ $menu->id }}" data-type="menu">
                                <i class="las la-edit"></i>
                            </button>

                            <button class="btn btn-xs btn-soft-danger delete-btn" data-id="{{ $menu->id }}" data-type="menu">
                                <i class="las la-trash"></i>
                            </button>

                            @if($menu->type == 'mega' && $menu->sections->count() < 2)
                                <button class="btn btn-xs btn-soft-success add-section-btn" data-id="{{ $menu->id }}" data-type="menu">
                                    <i class="las la-plus"></i> Add Section
                                </button>
                            @endif
                        </span>
                    </div>

                    @if($menu->type == 'mega')
                        <ul class="list-group mt-2 ms-4 section-list">
                            @foreach($menu->sections as $section)
                                <li data-id="{{ $section->id }}" class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fs-13">
                                            <i class="las la-bars drag-handle mr-2 text-muted"></i>
                                            <strong>{{ $section->title }}</strong>
                                        </span>
                                        <span>
                                            <button class="btn btn-xs btn-soft-secondary edit-btn" data-id="{{ $section->id }}" data-type="section">
                                                <i class="las la-edit"></i>
                                            </button>
                                            <button class="btn btn-xs btn-soft-danger delete-btn" data-id="{{ $section->id }}" data-type="section">
                                                <i class="las la-trash"></i>
                                            </button>
                                            <button class="btn btn-xs btn-soft-warning add-item-btn" data-id="{{ $section->id }}" data-type="section">
                                                <i class="las la-plus"></i> Add Item
                                            </button>
                                        </span>
                                    </div>
                                    <ul class="list-group mt-1 ms-3 item-list">
                                        @foreach($section->items as $item)
                                        <li data-id="{{ $item->id }}" class="list-group-item d-flex justify-content-between align-items-center">
                                            <span >
                                                <i class="las la-bars drag-handle mr-2 text-muted"></i> {{ $item->title }}
                                            </span>

                                            <span>
                                                <button class="btn btn-xs btn-soft-secondary edit-btn" data-id="{{ $item->id }}" data-type="item">
                                                    <i class="las la-edit"></i>
                                                </button>
                                                <button class="btn btn-xs btn-soft-danger delete-btn" data-id="{{ $item->id }}" data-type="item">
                                                    <i class="las la-trash"></i>
                                                </button>
                                            </span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    {{-- @else
                    <ul class="list-group mt-2 ms-3">
                        @foreach($menu->items as $item)
                        <li data-id="{{ $item->id }}" class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $item->title }}
                            <span>
                                <button class="btn btn-xs btn-secondary edit-btn" data-id="{{ $item->id }}" data-type="item">
                                    <i class="las la-edit"></i>
                                </button>
                                <button class="btn btn-xs btn-danger delete-btn" data-id="{{ $item->id }}" data-type="item">
                                    <i class="las la-trash"></i>
                                </button>
                            </span>
                        </li>
                        @endforeach
                    </ul> --}}
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<!-- MENU MODAL -->
<!-- MENU MODAL -->


<div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="menuForm">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Menu</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="parent_id" id="parent_id">
                    <input type="hidden" name="parent_type" id="parent_type">

                    <div class="mb-3">
                        <label class="form-label">Menu Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <!-- Menu Style only for main menu -->
                    <div class="mb-3" id="menuStyleField">
                        <label class="form-label">Menu Style</label>
                        <select name="type" class="form-control form-select">
                            <option value="normal">Normal</option>
                            <option value="mega">Mega Menu</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Menu Type</label>
                        <select name="link_type" id="linkType" class="form-control form-select">
                            <option value="custom">Custom</option>
                            <option value="product">Product</option>
                            <option value="category">Category</option>
                            <option value="brand">Brand</option>
                        </select>
                    </div>

                    <!-- CUSTOM LINK -->
                    <div class="mb-3" id="customLinkField">
                        <label class="form-label">Custom Link</label>
                        <input type="text" id="customLinkInput" class="form-control">
                    </div>

                    <!-- DROPDOWN -->
                    <div class="mb-3" id="selectionField" style="display:none;">
                        <label class="form-label">Select Item</label>
                        <select id="linkSelection" class="form-control form-select"></select>
                    </div>

                    <!-- FINAL VALUE (IMPORTANT) -->
                    <input type="hidden" name="link_value" id="finalLinkValue">

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>

                    <!-- FIXED CANCEL BUTTON -->
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        Cancel
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var menuModal = new bootstrap.Modal(document.getElementById('menuModal'));

    // Show modal for adding main menu
    const addBtn = document.getElementById('addMenuBtn');

    if(addBtn){
        addBtn.addEventListener('click', function(){
            resetModal();
            menuModal.show();
        });
    }

    // Conditional fields
    document.getElementById('linkType').addEventListener('change', function(){
        const val = this.value;
        const customField = document.getElementById('customLinkField');
        const selectionField = document.getElementById('selectionField');
        const selectEl = document.getElementById('linkSelection');

        if(val === 'custom'){
            customField.style.display = 'block';
            selectionField.style.display = 'none';
            selectEl.innerHTML = '';
        } else {
            customField.style.display = 'none';
            selectionField.style.display = 'block';
            selectEl.innerHTML = '<option value="">Loading...</option>';

            // Make AJAX call to get items
            fetch(`/admin/menus/get-items/${val}`)
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">-- Select --</option>';
                data.forEach(item => {
                    options += `<option value="${item.id}">${item.name}</option>`;
                });
                selectEl.innerHTML = options;
            });
        }
    });

    // Drag & drop for main menus
    new Sortable(document.getElementById('menu-tree'), {
        animation: 150,
        handle: '.list-group-item',
        onEnd: function(evt){
            updateSortOrder('menu', '#menu-tree > li');
        }
    });

    // Drag & drop for sections (inside mega menu)
    document.querySelectorAll('.section-list').forEach(sectionList=>{
        if(sectionList.querySelectorAll('li').length){ // only if sections exist
            new Sortable(sectionList, {
                animation:150,
                handle: '.list-group-item',
                onEnd: function(evt){
                    const menuId = sectionList.closest('li').dataset.id;
                    updateSortOrder('section', `li[data-id='${menuId}'] > ul > li`);
                }
            });
        }
    });

    // Drag & drop for items (both inside sections and normal menus)
    document.querySelectorAll('.item-list').forEach(itemList => {
        if(itemList.querySelectorAll('li').length){
            new Sortable(itemList, {
                animation:150,
                handle: '.list-group-item',
                onEnd: function(evt){
                    const parentId = itemList.closest('li').dataset.id;
                    updateSortOrder('item', `li[data-id='${parentId}'] > ul > li`);
                }
            });
        }
    });

    // Handle button clicks
    document.addEventListener('click', function(e){
        const btn = e.target.closest('button');
        if(!btn) return;

        const type = btn.dataset.type;
        const id = btn.dataset.id;

        // Delete
        if(btn.classList.contains('delete-btn')){
            swal({
                title: "Are you sure?",
                text: 'Delete this '+type+'?',
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    fetch(`/admin/menus/${type}/${id}`, {
                        method:'DELETE',
                        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
                    }).then(res=>res.json()).then(data=>{
                        if(data.success) btn.closest('li').remove();
                        window.location.reload();
                    });
                }
            });
        }

        // Edit
        if(btn.classList.contains('edit-btn')){
            fetch(`/admin/menus/${type}/${id}/edit`)
            .then(res=>res.json())
            .then(data=>{
                fillModal(data,type,id);
                menuModal.show();
            });
        }

        // Add section
        if(btn.classList.contains('add-section-btn')){
            resetModal();
            document.getElementById('menuForm').dataset.parentId = id;
            document.getElementById('menuForm').dataset.parentType = 'menu-section';
            document.getElementById('menuStyleField').style.display = 'none';
            menuModal.show();
        }

        // Add item
        if(btn.classList.contains('add-item-btn')){
            resetModal();
            document.getElementById('menuForm').dataset.parentId = id;
            document.getElementById('menuForm').dataset.parentType = 'section-item';
            document.getElementById('menuStyleField').style.display = 'none';
            menuModal.show();
        }
    });

    // Modal helpers
    function resetModal(){
        const form = document.getElementById('menuForm');

        form.reset();

        // Remove edit & parent data
        delete form.dataset.editId;
        delete form.dataset.editType;
        delete form.dataset.parentId;
        delete form.dataset.parentType;

        // Reset dropdown & custom fields
        document.getElementById('linkType').value = 'custom';

        document.getElementById('customLinkInput').value = '';
        document.getElementById('finalLinkValue').value = '';

        // Clear dropdown
        const selectEl = document.getElementById('linkSelection');
        selectEl.innerHTML = '';

        // Reset visibility
        document.getElementById('customLinkField').style.display = 'block';
        document.getElementById('selectionField').style.display = 'none';

        // Show menu style (default)
        document.getElementById('menuStyleField').style.display = 'block';

        document.getElementById('linkType').dispatchEvent(new Event('change'));
    }

    document.getElementById('menuModal').addEventListener('hidden.bs.modal', function () {
        resetModal();
    });

    function fillModal(data, type, id){
        const form = document.getElementById('menuForm');
        form.querySelector('[name="title"]').value = data.title;
        const linkType = data.link_type || 'custom';
        form.querySelector('[name="link_type"]').value = linkType;

        // Reset fields
        const customField = document.getElementById('customLinkField');
        const selectionField = document.getElementById('selectionField');
        const selectEl = document.getElementById('linkSelection');
        customField.style.display = 'none';
        selectionField.style.display = 'none';
        selectEl.innerHTML = '';

        if(linkType === 'custom'){
            customField.style.display = 'block';
            document.getElementById('customLinkInput').value = data.link_value || '';
            document.getElementById('finalLinkValue').value = data.link_value || '';
        } else {
            selectionField.style.display = 'block';
            selectEl.innerHTML = '<option value="">Loading...</option>';

            // Fetch items first, then select current value
            fetch(`/admin/menus/get-items/${linkType}`)
                .then(res => res.json())
                .then(items => {
                    let options = '<option value="">-- Select --</option>';
                    items.forEach(item => {
                        options += `<option value="${item.id}" ${item.id == data.link_value ? 'selected' : ''}>${item.name}</option>`;
                    });
                    selectEl.innerHTML = options;
                });
        }

        if(type === 'menu'){
            form.querySelector('[name="type"]').value = data.type || 'normal';
            document.getElementById('menuStyleField').style.display = 'block';
        } else {
            document.getElementById('menuStyleField').style.display = 'none';
        }

        form.dataset.editId = id;
        form.dataset.editType = type;
    }

    // Form submit
    document.getElementById('menuForm').addEventListener('submit', function(e){
    e.preventDefault();

    const linkType = document.getElementById('linkType').value;
    const customInput = document.getElementById('customLinkInput');
    const selectEl = document.getElementById('linkSelection');
    const finalInput = document.getElementById('finalLinkValue');

    // ✅ SET FINAL VALUE CORRECTLY
    if(linkType === 'custom'){
        finalInput.value = customInput.value;
    } else {
        finalInput.value = selectEl.value;
    }

    let data = new FormData(this);
    let url = "{{ route('menus.store') }}";
    let method = 'POST';

    if(this.dataset.editId){
        url = `/admin/menus/${this.dataset.editType}/${this.dataset.editId}`;
            method = 'POST';
            data.append('_method','PUT');
        }

        if(this.dataset.parentId){
            data.append('parent_id',this.dataset.parentId);
            data.append('parent_type',this.dataset.parentType);
        }

        fetch(url,{method:method,body:data})
        .then(res=>res.json())
        .then(resp=>{
            location.reload();
        });
    });

    document.getElementById('linkSelection').addEventListener('change', function(){
        document.getElementById('finalLinkValue').value = this.value;
    });

    function updateSortOrder(type, selector){
        
        const order = [];
        document.querySelectorAll(selector).forEach((el, idx)=>{
            order.push({id: el.dataset.id, sort_order: idx+1});
        });

        fetch('{{ route("menus.updateOrder") }}', {
            method:'POST',
            headers:{
                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                'Content-Type':'application/json'
            },
            body: JSON.stringify({order: order, type: type})
        });
    }
</script>
@endsection