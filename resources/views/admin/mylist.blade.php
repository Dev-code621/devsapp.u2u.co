@extends('admin.layouts.template',['menu'=>'mylist'])

@section('css')
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/summernote/summernote.css')}}">
@endsection

@section('page-content')
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-body">
                <form method="post" action="{{url('admin/mylist/save')}}">
                    @csrf
                    <div class="form-group">
                        <label>Page Content (required *)</label>
                        <textarea id="section-content" class="form-control" required name="section-content">
                            {{is_null($mylist_content) ? "" : $mylist_content->contents}}
                        </textarea>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <div>
        <script src="{{asset('/admin/template/vendor/summernote/summernote.min.js')}}"></script>
        <script src="{{asset('/admin/template/vendor/summernote-image-attribute-editor-master/summernote-image-attributes.js')}}"></script>

        <script>
            $(document).ready(function () {
                $('#section-content').summernote({
                    height:400,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['insert', ['link', 'picture', 'video','table']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                    ],

                });
            })
        </script>
    </div>
@endsection
