@extends('admin.layouts.template',['menu'=>'profile'])

@section('css')

@endsection

@section('page-content')
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-body">
                <form method="post" action="{{url('admin/updateProfile')}}">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" value="{{$user->name}}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{$user->email}}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password (leave as blank if you don't want to change)</label>
                        <input type="password" name="password" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Password Confirm (leave as blank if you don't want to change)</label>
                        <input type="password" name="password_confirm" value="" class="form-control">
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


