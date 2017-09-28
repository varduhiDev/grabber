@extends('layouts.grab')

@section('content')
    <div class="title m-b-md">
        Edit Article
    </div>
    <div class="row">
        @if(Session::has('message'))
            <div class="alert alert-{{ Session::get('message-type') }}">
                {{ Session::get('message') }}
            </div>
        @endif
    </div>
    <div class="row">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/article/update/'.$article->id) }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input id='editId' type="hidden" name="id" value="" />
            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                <label for="image" class="col-md-4 control-label">Image</label>

                <div class="col-md-6">
                    <input id="image" type="file" name="image">

                    @if ($errors->has('image'))
                        <span class="help-block">
                                <strong>{{ $errors->first('image') }}</strong>
                            </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                <label for="title" class="col-md-4 control-label">Title</label>

                <div class="col-md-6">
                    <input id="title" type="text" class="form-control" name="title" value="{{ !is_null(old('title')) ? old('title') : $article->title }}" required>

                    @if ($errors->has('title'))
                        <span class="help-block">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                <label for="description" class="col-md-4 control-label">Description</label>

                <div class="col-md-6">
                    <textarea rows="4" cols="50" id="description" class="form-control" name="description" required>{{ !is_null(old('description')) ? old('description') : $article->description }}</textarea>

                    @if ($errors->has('description'))
                        <span class="help-block">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </div>
        </form>
    </div>
@endsection