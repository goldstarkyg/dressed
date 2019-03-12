@extends('layouts.app')

@section('template_title')
  Showing Reports
@endsection

@section('template_linked_css')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }

    </style>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            Showing All Reports
                            {{--<a href="/posts/create" class="btn btn-default btn-sm pull-right">--}}
                                {{--<i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>--}}
                                {{--Create New Post--}}
                            {{--</a>--}}
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Photo 1</th>
                                        <th>Photo 2</th>
                                        {{--<th>Style</th>--}}
                                        <th class="hidden-sm hidden-xs hidden-md">Created</th>
                                        <th>Expired Hours</th>
                                        <th>Status</th>
                                        <th>Comment</th>
                                        <th>Actions</th>
                                        {{--<th></th>--}}
                                        {{--<th></th>--}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $num = 0 @endphp
                                    @foreach($posts as $post)
                                        @php $num++ @endphp
                                        <tr>
                                            <td>{{$num}}</td>
                                            <td>{{$post->first_name. " " .$post->last_name}}</td>
                                            <td><?php
                                                echo base64_decode($post->subject, 'utf-8');
                                                ?>
                                            </td>
                                            <td>
                                                @if ($post->photo != "")
                                                    @php $url = url('/')."/img/posts/".$post->photo  @endphp
                                                    <img src="{{ $url }}" class="myImg" onclick="showPhoto('{{ $url }}','<?php
                                                    echo base64_decode($post->subject, 'utf-8');
                                                    ?>')"/>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($post->photo2 != "")
                                                    @php $url = url('/')."/img/posts/".$post->photo2  @endphp
                                                    <img src="{{ $url }}" class="myImg" onclick="showPhoto('{{ $url }}','<?php
                                                    echo base64_decode($post->subject, 'utf-8');
                                                    ?>')"/>
                                                @endif
                                            </td>
                                            {{--<td>{{$post->name}}</td>--}}
                                            <td class="hidden-sm hidden-xs hidden-md">
                                                @php $create_date = date('Y-m-d H:i:s', $post->createdtime)  @endphp
                                                {{$create_date}}
                                            </td>
                                            <td>{{$post->expiredhour}}&nbsp;&nbsp;( h )</td>
                                            <td>
                                                @php $time_expired = $post->createdtime + $post->expiredhour * 3600  @endphp
                                                @php $time_now = time() @endphp
                                                @if ($time_expired >= $time_now)
                                                    Active
                                                @else
                                                    Expired
                                                @endif
                                            </td>
                                            <td>{{$post->comment}}</td>
                                            <td>
                                                {!! Form::open(array('url' => 'reports/' . $post->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')) !!}
                                                    {!! Form::hidden('_method', 'DELETE') !!}
                                                    {!! Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"> Post</span>', array('class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete Post', 'data-message' => 'Are you sure you want to delete this post ?')) !!}
                                                {!! Form::close() !!}
                                            </td>
                                            {{--<td>--}}
                                                {{--<a class="btn btn-sm btn-success btn-block" href="{{ URL::to('users/' . $user->id) }}" data-toggle="tooltip" title="Show">--}}
                                                    {{--<i class="fa fa-eye fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Show</span><span class="hidden-xs hidden-sm hidden-md"> User</span>--}}
                                                {{--</a>--}}
                                            {{--</td>--}}
                                            {{--<td>--}}
                                                {{--<a class="btn btn-sm btn-info btn-block" href="{{ URL::to('users/' . $user->id . '/edit') }}" data-toggle="tooltip" title="Edit">--}}
                                                    {{--<i class="fa fa-pencil fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Edit</span><span class="hidden-xs hidden-sm hidden-md"> User</span>--}}
                                                {{--</a>--}}
                                            {{--</td>--}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal">
        <div id="caption"></div>
        <span class="close">&times;</span>
        <img class="modal-content" id="img01">
    </div>
    <style>
        .myImg { width:50px;height:50px;border-radius:5px;cursor: pointer; transition: 0.3s;}
        .myImg:hover {opacity: 0.7;}
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.8);
        }
        .modal-content { margin: auto;margin-bottom: 100px; display: block; width: 80%; max-width: 700px; }
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
        }
        .modal-content, #caption {
            -webkit-animation-name: zoom;
            -webkit-animation-duration: 0.6s;
            animation-name: zoom;
            animation-duration: 0.6s;
        }
        @-webkit-keyframes zoom {
            from {-webkit-transform:scale(0)}
            to {-webkit-transform:scale(1)}
        }
        @keyframes zoom {
            from {transform:scale(0)}
            to {transform:scale(1)}
        }
        .close {
            position: absolute;
            top: 25px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }
        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
        @media only screen and (max-width: 700px){
            .modal-content {
                width: 100%;
            }
        }
    </style>
    <script>
        var modal = document.getElementById('myModal');

        var img = document.getElementById('myImg');
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");

        function showPhoto(url,name){
            modal.style.display = "block";
            modalImg.src = url;
            captionText.innerHTML = name;
        }

        var span = document.getElementsByClassName("close")[0];

        span.onclick = function() {
            modal.style.display = "none";
        }
    </script>
    @include('modals.modal-delete')

@endsection

@section('footer_scripts')

    @if (count($posts) > 10)
        @include('scripts.datatables')
    @endif
    @include('scripts.delete-modal-script')
    @include('scripts.save-modal-script')
    {{--
        @include('scripts.tooltips')
    --}}
@endsection