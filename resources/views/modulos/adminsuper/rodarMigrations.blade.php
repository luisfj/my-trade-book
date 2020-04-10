@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12"><h1>Rodar Migrations</h1></div>
    </div>

    <hr class="bg-warning">


    <a href="{{route("admin.rodar.migration")}}" class="btn btn-info form-control" style="width: 120px !important; height: auto; padding-top: 20px;" >
        <i class="material-icons md-20">play_circle_outline</i>
        <label>Migration</label>
    </a>

    <a href="{{route("admin.rollback.migration")}}" class="btn btn-warning form-control" style="width: 120px !important; height: auto; padding-top: 20px;">
        <i class="material-icons ">play_circle_outline</i>
        <label style="padding-left:5px;padding-right:5px;">Rollback</label>
    </a>

    <a href="{{route("admin.seeds.migration")}}" class="btn btn-success form-control" style="width: 120px !important; height: auto; padding-top: 20px;" >
        <i class="material-icons ">play_circle_outline</i>
        <label>Rodar Seeds</label>
    </a>

@endsection


