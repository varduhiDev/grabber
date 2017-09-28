@extends('layouts.grab')
@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.css"/>
@endsection
@section('content')
        <div class="title m-b-md">
            Articles
        </div>
            <button id="getArticles" class="btn btn-primary">Get Articles</button>
        <div class="row">
            @if(Session::has('message'))
                <div class="alert alert-{{ Session::get('message-type') }}">
                    {{ Session::get('message') }}
                </div>
            @endif
        </div>
        @if(count($articles) > 0)
            <div class="table-responsive">
                <table class="table table-responsive"  id="mygrid">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Url</th>
                        <th>Delete</th>
                        <th>Edit</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($articles as $article)
                        <tr>
                            <td>{{$article->id}}</td>
                            <td><img src="{{$article->image}}" alt="main_image"></td>
                            <td>{{$article->title}}</td>
                            <td>{{$article->description}}</td>
                            <td>{{$article->date}}</td>
                            <td><a href="{{$article->url}}" target="_blank">{{$article->url}}</a></td>
                            <td><button type="button" class="btn btn-danger deleteArticle" data-toggle="modal" data-target="#deleteModal" data-id="{{$article->id}}">Delete</button></td>
                            <td><a type="button" class="btn btn-primary editArticle" href="{{url('/edit/'.$article->id)}}">Edit</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <div id="deleteModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete</h4>
                    </div>
                    <div class="modal-body">
                        <p>Do you want to delete article?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="deleteArticle" class="btn btn-danger" data-dismiss="modal">Delete</button>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('javascript')
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
    <script>
        jQuery(document).ready(function ($) {
            $("#mygrid").DataTable();
        })
    </script>
@endsection