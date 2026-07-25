@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Edit Customer</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif

                    <form class="form-horizontal" action="{{ route('customers.update', $customer) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Name</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Name" id="name" name="name" class="form-control"
                                    value="{{ $customer->name }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Email <span class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <input type="email" placeholder="Email" id="email" name="email" class="form-control"
                                    value="{{ old('email', $customer->email) }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Phone number</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="Phone number" class="form-control"
                                    value="{{ $customer->phone }}" name="phone">
                            </div>
                        </div>

                        <hr>
                        <h6>Reset Password</h6>
                        <span>Fill only if you want to reset password</span>


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Password</label>
                            <div class="col-md-9">
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control password" type="password" name="password"
                                        placeholder="Password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary passwordToggle" type="button">
                                            <i class="las la-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Confirm Password</label>
                            <div class="col-md-9">
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control password" name="password_confirmation"
                                        placeholder="Confirm Password">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary passwordToggle" type="button">
                                            <i class="las la-eye"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>



            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">Address</h5>
                </div>
                <div class="card-body">
                    <div class="row gutters-10">
                        @if ($customer->addresses->count() > 0)
                            @foreach ($customer->addresses as $index => $address)
                                <div class="col-lg-6">
                                    <div class="border p-3 rounded mb-3">
                                        <div>
                                            <span class="w-50 fw-600">Name:</span>
                                            <span class="ml-2">{{ $address->name }}</span>
                                        </div>
                                        <div>
                                            <span class="w-50 fw-600">Address:</span>
                                            <span class="ml-2">{{ $address->address }}</span>
                                        </div>
                                        <div>
                                            <span class="w-50 fw-600">Postal code:</span>
                                            <span class="ml-2">{{ $address->postal_code }}</span>
                                        </div>
                                        <div>
                                            <span class="w-50 fw-600">City:</span>
                                            <span class="ml-2">{{ is_object($address->city) ? ($address->city->name ?? '') : ($address->city ?? '') }}</span>
                                        </div>
                                        <div>
                                            <span class="w-50 fw-600">State:</span>
                                            <span class="ml-2">{{ is_object($address->state) ? ($address->state->name ?? '') : ($address->state_name ?? $address->state ?? '') }}</span>
                                        </div>
                                        <div>
                                            <span class="w-50 fw-600">Country:</span>
                                            <span class="ml-2">{{ is_object($address->country) ? ($address->country->name ?? '') : ($address->country_name ?? $address->country ?? '') }}</span>
                                        </div>
                                        <div>
                                            <span class="w-50 fw-600">Phone:</span>
                                            <span class="ml-2">{{ $address->phone }}</span>
                                        </div>
                                        @if ($address->set_default)
                                            <div class="mt-2">
                                                <span class="badge badge-inline badge-primary">Default</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12 text-center text-muted py-3">
                                No addresses found.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
