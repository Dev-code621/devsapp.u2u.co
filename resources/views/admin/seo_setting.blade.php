@extends('admin.layouts.template',['menu'=>'seo-setting'])

@section('css')
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/summernote/summernote.css')}}">
    <style>
        .page-meta-content-wrapper{
            border: 1px solid;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: #eee;
        }

    </style>
@endsection

@section('page-content')
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-body">
                <form method="post" action="{{url('admin/saveSeoSetting')}}">
                    @csrf
                    <div class="page-meta-content-wrapper">
                        <div class="form-group">
                            <label>News Page Title</label>
                            <input type="text" name="news_meta_title" value="{{$news_meta_title}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>News Page Meta Keyword (required *)</label>
                            <textarea  class="form-control" required name="news_meta_keyword">{{$news_meta_keyword}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>News Page Meta Description (required *)</label>
                            <textarea  class="form-control" required name="news_meta_content">{{$news_meta_content}}</textarea>
                        </div>
                    </div>

                    <div class="page-meta-content-wrapper">
                        <div class="form-group">
                            <label>Support Page Title</label>
                            <input type="text" name="support_meta_title" value="{{$support_meta_title}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Support Page Meta Keywords (required *)</label>
                            <textarea  class="form-control" required name="support_meta_keyword">{{$support_meta_keyword}} </textarea>
                        </div>
                        <div class="form-group">
                            <label>Support Page Meta Content (required *)</label>
                            <textarea  class="form-control" required name="support_meta_content">{{$support_meta_content}} </textarea>
                        </div>
                    </div>
                    <div class="page-meta-content-wrapper">
                        <div class="form-group">
                            <label>Instruction Page Title</label>
                            <input type="text" name="instruction_meta_title" value="{{$instruction_meta_title}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Instruction Page Meta Keywords (required *)</label>
                            <textarea  class="form-control" required name="instruction_meta_keyword">{{$instruction_meta_keyword}} </textarea>
                        </div>
                        <div class="form-group">
                            <label>Instruction Page Meta Content (required *)</label>
                            <textarea  class="form-control" required name="instruction_meta_content">{{$instruction_meta_content}} </textarea>
                        </div>
                    </div>
                    <div class="page-meta-content-wrapper">
                        <div class="form-group">
                            <label>My List Page Title</label>
                            <input type="text" name="mylist_meta_title" value="{{$mylist_meta_title}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>My List Page Meta Keyword (required *)</label>
                            <textarea  class="form-control" required name="mylist_meta_keyword">{{$mylist_meta_keyword}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>My List Page Meta Content (required *)</label>
                            <textarea  class="form-control" required name="mylist_meta_content">{{$mylist_meta_content}}</textarea>
                        </div>
                    </div>

                    <div class="page-meta-content-wrapper">
                        <div class="form-group">
                            <label>Activation Page Title</label>
                            <input type="text" name="activation_meta_title" value="{{$activation_meta_title}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Activation Page Meta Keyword (required *)</label>
                            <textarea  class="form-control" required name="activation_meta_keyword">{{$activation_meta_keyword}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Activation Page Meta Content (required *)</label>
                            <textarea  class="form-control" required name="activation_meta_content">{{$activation_meta_content}}</textarea>
                        </div>
                    </div>
                    <div class="page-meta-content-wrapper">
                        <div class="form-group">
                            <label>Terms Page Title</label>
                            <input type="text" name="terms_meta_title" value="{{$terms_meta_title}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Terms Page Meta Keyword (required *)</label>
                            <textarea  class="form-control" required name="terms_meta_keyword">{{$terms_meta_keyword}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Terms Page Meta Content (required *)</label>
                            <textarea  class="form-control" required name="terms_meta_content">{{$terms_meta_content}}</textarea>
                        </div>
                    </div>

                    <div class="page-meta-content-wrapper">
                        <div class="form-group">
                            <label>Privacy Page Title</label>
                            <input type="text" name="privacy_meta_title" value="{{$privacy_meta_title}}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Privacy Page Meta Keyword (required *)</label>
                            <textarea  class="form-control" required name="privacy_meta_keyword">{{$privacy_meta_keyword}}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Privacy Page Meta Content (required *)</label>
                            <textarea  class="form-control" required name="privacy_meta_content">{{$privacy_meta_content}}</textarea>
                        </div>
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
    </div>
@endsection
