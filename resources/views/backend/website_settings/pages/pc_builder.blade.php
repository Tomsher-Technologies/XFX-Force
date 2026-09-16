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
                <input type="hidden" name="type" value="{{ $page->type }}">
                @include('backend.inc.page_seo', ['settings' => $settings])


                <div class="text-right mb-2">
                    <button type="submit" class="btn btn-info btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            var lang = '{{ $lang }}';

            if(lang == 'ae'){
                setEditorDirection(true);
            }else{
                setEditorDirection(false);
            }
            function setEditorDirection(isRtl) {
                const editor = $('.aiz-text-editor').next('.note-editor').find('.note-editable');
                editor.attr('dir', isRtl ? 'rtl' : 'ltr'); // Set direction
                editor.css('text-align', isRtl ? 'right' : 'left');
            }
        });
    </script>

@endsection
