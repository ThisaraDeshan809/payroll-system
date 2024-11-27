@extends('layouts.default') @section('content')
    <div class="row">
        <div class="col-xl">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create New Category</h5>
                    <form class="needs-validation" method="post" action="{{ route('category.store') }}" novalidate>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-row">
                            <div class="col-md-4 mb-3">
                                <label for="name">Category Name*</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Category Name" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                                @endif
                                <div class="invalid-feedback">
                                    Category field is required
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="name">Category Icon*</label>
                                <input type="text" class="form-control" id="icon" name="icon"
                                    placeholder="Category Name" value="{{ old('icon') }}" required>
                                @if ($errors->has('icon'))
                                    <span class="text-danger text-left">{{ $errors->first('icon') }}</span>
                                @endif
                                <div class="invalid-feedback">
                                    Category Icon field is required
                                </div>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="description">Description*</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="text-danger text-left">{{ $errors->first('description') }}</span>
                                @endif
                                <div class="invalid-feedback">
                                    Description field is required
                                </div>
                            </div>

                        </div>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
