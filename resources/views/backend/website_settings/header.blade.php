@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">Website Header</h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12 mx-auto">
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">Header Setting</h6>
			</div>
			<div class="card-body">
				<div class="row gutters-10">
					<div class="col-lg-12 mx-auto">
						<div class="card shadow-none bg-light">
							<div class="card-header">
								<h6 class="mb-0">Info Widget</h6>
							</div>
							<div class="card-body">
								<form action="{{ route('business_settings.update_settings') }}" method="POST"
									enctype="multipart/form-data">
									@csrf

									<div class="row">
										<div class="form-group col-md-6">
											<label class="form-label" for="signinSrEmail">Logo</label>
											<div class="input-group " data-toggle="aizuploader" data-type="image">
												<div class="input-group-prepend">
													<div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
												</div>
												<div class="form-control file-amount">Choose File</div>
												<input type="hidden" name="types[]" value="header_logo">
												<input type="hidden" name="header_logo" class="selected-files"
													value="{{ get_setting('header_logo') }}">
											</div>
											<div class="file-preview"></div>
										</div>

										<div class="form-group col-md-6">
											<label>Address</label>
											<input type="hidden" name="types[]" value="header_address">
											<input type="text" class="form-control" placeholder="Enter.." name="header_address"
												value="{{ get_setting('header_address') }}">
										</div>
										
										<div class="form-group col-md-6">
											<label>Email</label>
											<input type="hidden" name="types[]" value="header_email">
											<input type="text" class="form-control" placeholder="Enter.." name="header_email"
												value="{{ get_setting('header_email') }}">
										</div>
									
										<div class="form-group col-md-6">
											<label>Phone</label>
											<input type="hidden" name="types[]" value="header_phone">
											<input type="text" class="form-control" placeholder="Enter.." name="header_phone"
												value="{{ get_setting('header_phone') }}">
										</div>
										
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
	</div>
</div>

@endsection
