@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card">
                    <div class="card-header">{{ __('Site') . ' ' . $site->name }}
                        <br>
                        ({{ $site->url }})
                        <a href="{{ route('sites.index') }}" class="btn btn-primary float-right">Back</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(count($scripts))
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Content</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($scripts as $key => $script)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $scripts->name }}</td>
                                        <td>{{ $scripts->content }}</td>
                                        <td><div class="row justify-content-center">
                                                <a href="{{ route('scripts.show', $scripts->id) }}" class="btn btn-primary mr-3">Scripts</a>
                                                <a href="{{ route('scripts.edit', $scripts->id) }}" class="btn btn-success mr-3">Edit</a>
                                                <form method="post" action="{{ route('scripts.destroy', $scripts->id) }}" class="mb-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="confirm('Delete this personnel?')?true:false"
                                                            class="btn btn-danger ">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                                <script-component v-bind:current-user="{{ Auth::user()->id }}" v-bind:site-id="{{$site->id}}"></script-component>

                            @else

                        @endif

                            <script-component v-bind:current-user="{{ Auth::user()->id }}"
                                              v-bind:site-id="{{ $site->id }}"></script-component>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection