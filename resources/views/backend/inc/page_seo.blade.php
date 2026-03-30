        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Seo Details</h6>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="5">{{ $settings['meta_description'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Meta Keywords</label>
                        <input type="text" name="keywords" value="{{ $settings['keywords'] ?? '' }}" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">OG Title</label>
                        <input type="text" name="og_title" value="{{ $settings['og_title'] ?? '' }}" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">OG Description</label>
                        <textarea name="og_description" class="form-control" rows="5">{{ $settings['og_description'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Twitter Title</label>
                        <input type="text" name="twitter_title" value="{{ $settings['twitter_title'] ?? '' }}" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <label class="col-form-label">Twitter Description</label>
                        <textarea name="twitter_description" class="form-control" rows="5">{{ $settings['twitter_description'] ?? '' }}</textarea>
                    </div>
                </div>

            </div>
        </div>