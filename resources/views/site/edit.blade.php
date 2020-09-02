@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card">
                <div class="card-header">{{ __('Create site:') }}
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="post" action="{{ route('sites.update' , $site->id) }}" class="pl-2 pr-2 ">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="site__url" class="col-sm-3 col-md-4">
                                <span>Url:</span>
                            </label>
                            <input type="text" name="url" required id="site__url"
                                   class="form-control col-sm-9 col-md-7 @if($errors->first('url')) is-invalid @endif"
                                   value="{{ old('url', $site->url) }}">

                        </div>
                        <div class="row mb-3">
                            <label for="site__name" class="col-sm-3 col-md-4">
                                <span>Name: </span>
                            </label>
                            <input type="text" name="name" required id="site__name"
                                   class="form-control col-sm-9 col-md-7 @if($errors->first('name')) is-invalid @endif"
                                   value="{{ old('name', $site->name) }}">
                        </div>
                        <div class="row mt-5">
                            <div class="col-12">
                                <input type="submit" value="Update" class="btn btn-success">
                                <a href="{{ route('sites.index') }}" class="btn btn-danger float-right">Cancel</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection