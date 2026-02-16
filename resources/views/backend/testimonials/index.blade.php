@extends('backend.layouts.app', ['body_class' => '', 'title' => 'Testimonials'])
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="h4">{{ trans('messages.all').' Testimonials' }}</h5>
            </div>
            @can('add_testimonials')
            <div class="col-md-6 text-md-right">
                <a href="{{ route('testimonials.create') }}" class="btn btn-primary btn-sm">
                    <span>{{ trans('messages.add_new').' Testimonial' }}</span>
                </a>
            </div>
            @endcan
        </div>
    </div>

    <div class="row">
        @if ($testimonials)
            <div class="col-lg-12 col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <table class="table aiz-table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Name </th>
                                    <th class="text-left">Sub Title</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Sort Order</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($testimonials as $key=>$test)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 + ($testimonials->currentPage() - 1) * $testimonials->perPage() }}</td>
                                        <td>
                                            {{ $test->name }}
                                        </td>
                                        <td class="text-left">{{ $test->sub_title }}</td>
                                        <td class="text-center">{{ $test->type }}</td>
                                        <td class="text-center">
                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                <input onchange="update_status(this)" value="{{ $test->id }}"
                                                    type="checkbox" <?php if ($test->status == 1) {
                                                        echo 'checked';
                                                    } ?>>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">{{ $test->sort_order }}</td>
                                        <td class="d-flex gap-2 justify-content-center footable-last-visible">
                                            @can('edit_testimonials')
                                            <a href="{{ route('testimonials.edit', $test) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm"><i class="las la-edit"></i></a>
                                            @endcan
                                            @can('delete_testimonials')
                                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('testimonials.delete', $test->id) }}" title="Delete">
                                                <i class="las la-trash"></i>
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="aiz-pagination float-right">
                            {{ $testimonials->appends(request()->input())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script>
        function update_status(el) {

            var status = 0

            if (el.checked) {
                status = 1;
            }

            $.post('{{ route('testimonials.update-status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', 'Status updated successfully');
                } else {
                    AIZ.plugins.notify('danger', 'Something went wrong');
                }
            });
        }
    </script>
@endsection
