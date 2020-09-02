@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card">
                    <div class="card-header">{{ __('My sites list:') }}
                    <a href="{{ route('sites.create') }}" class="btn btn-success float-right">Add new site</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(count($sites))
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Url</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($sites as $key => $site)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $site->url }}</td>
                                    <td>{{ $site->name }}</td>
                                    <td><div class="row justify-content-center">
                                            <a href="{{ route('sites.show', $site->id) }}" class="btn btn-primary mr-3">Scripts</a>
                                            <a href="{{ route('sites.edit', $site->id) }}" class="btn btn-success mr-3">Edit</a>
                                            <form method="post" action="{{ route('sites.destroy', $site->id) }}" class="mb-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="confirm('Delete this site?')?true:false"
                                                        class="btn btn-danger ">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @else
                                <p>{{ __('You not have sites.') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection