@extends('admin.layouts.template',['menu'=>'pl_package_create'])

@section('css')
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/summernote/summernote.css')}}">
@endsection

@section('page-content')
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-body">
                <form method="post" action="{{url('admin/playlist_package/save')}}">
                    @csrf
                    <div class="form-group">
                        <label>Package Name (required *)</label>
                        <input type="text" class="form-control" id="package-name" required name="package_name" value="{{!is_null($package) ? $package->name : ""}}">
                    </div>
                    <div class="form-group">
                        <label>Duration (months)</label>
                        <input type="number" class="form-control" id="duration" required name="duration" value="{{!is_null($package) ? $package->duration : ""}}">
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" class="form-control" id="price" required name="price" value="{{!is_null($package) ? $package->price : ""}}" step="0.01">
                    </div>
                    <input hidden name="id" value="{{$id}}">

                    <div class="form-group">
                        <button class="btn btn-primary">{{is_null($id) ? "Submit" : "Update"}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <div>
        <script src="{{asset('/admin/template/vendor/summernote/summernote.min.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    </div>
@endsection
