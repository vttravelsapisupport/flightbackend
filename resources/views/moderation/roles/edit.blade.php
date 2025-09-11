@extends('layouts.app')
@section('title','Roles')
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice{
            background-color: #e4e4e4;
            border: 1px solid #aaa;
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 5px;
            margin-top: 5px;
            font-size: 16px;
            padding: 10px 10px !important;
        }
        .w-22{
            width: 22%;
        }
        .inline-block {
            display: inline-block !important;
        }
    </style>
@endsection
@section('contents')
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Edit Role</h4>
                <p class="card-description">
                    Edit Role of <strong>{{ $details->name }}</strong>
                </p>

                <form class="forms-sample" method="POST" action="{{ route('roles.update',$details->id) }}">
                    @csrf
                    @method('put')
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" placeholder="Enter the Role Name" name="name" value="{{ $details->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Permissions</label>
                        <div class="col-sm-9">
                            {{-- <select class="js-example-basic-multiple form-control" name="permissions[]" multiple="multiple">--}}
                                {{-- @foreach($permissions as $value)--}}
                                {{--<option value="{{ $value->name }}" @if(in_array($value->name, $rolePermissions)) selected @endif>{{ $value->name }}</option>--}}
                                {{-- @endforeach--}}
                            {{-- </select>--}}
                            @foreach($permissions as $value)
                            <div class="form-check text-capitalize w-22 inline-block m-0 pl-4">
                                <input class="form-check-input" type="checkbox" value="{{ $value->name }}" id="flexCheckDefault" name="permissions[]" @if(in_array($value->name, $rolePermissions)) checked @endif>
                                <label class="form-check-label ml-1" for="flexCheckDefault">
                                    {{ $value->name }}
                                </label>
                            </div>
                            @endforeach

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mr-2 btn-sm">Save</button>

                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>

@endsection
