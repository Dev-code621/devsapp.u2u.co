@extends('admin.layouts.template',['menu'=>'app_background-setting'])

@section('css')
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/summernote/summernote.css')}}">
    <style>
        #vue{
            max-width: 1000px;
        }
        .page-meta-content-wrapper{
            border: 1px solid;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            background: #eee;
            padding-right:40px;
        }
        .theme-background-preview{
            cursor: pointer;
        }
        .theme-background-preview img{
            width:100%;
        }
        .image-file-upload-text.position-absolute {
            bottom: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
        }
        .add-theme-icon-wrapper {
            right: 0;
            font-size: 30px;
            color: #09bf09;
            height: 35px;
            width: 35px;
            text-align: center;
            line-height: 38px;
            box-shadow: 0 0 5px #aaa;
            border-radius: 30px;
            cursor: pointer;
        }
        .theme-delete-icon-wrapper {
            right: 10px;
            top: 50px;
            font-size: 20px;
            color: #cc0000;
            cursor: pointer;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-title">App Backgrounds</div>
            <div class="panel-body">
                <form method="post" action="{{url('admin/saveThemes')}}" enctype="multipart/form-data">
                    @csrf
                    <div id="vue">
                        @verbatim
                            <div class="position-relative" style="height:50px;">
                                <div class="position-absolute add-theme-icon-wrapper" @click="addTheme()">
                                    <i class="fa fa-plus"></i>
                                </div>
                            </div>
                            <div class="page-meta-content-wrapper position-relative" v-for="(item, index) in themes">
                                <div class="position-absolute theme-delete-icon-wrapper" @click="deleteTheme(index)" v-if="themes.length>1">
                                    <i class="fa fa-trash"></i>
                                </div>
                                <div class="form-group">
                                    <label>Theme Name</label>
                                    <input type="text" class="form-control" v-model="item.name" :name="'theme-name-'+index" required>
                                </div>
                                <input type="text" hidden :value="item.origin_url" :name="'theme-origin_url-'+index">
                                <div class="form-group">
                                    <label :for="'theme-image-'+index">
                                        <div class="theme-background-preview relative">
                                            <img :src="item.url" :id="'theme-image-preview-'+index">
                                            <div class="image-file-upload-text position-absolute">Click Here To Upload</div>
                                        </div>
                                    </label>
                                    <input type="file" class="form-control" style="display:none" :id="'theme-image-'+index"
                                           :name="'theme-image-'+index" :multiple="false" @change="fileChange($event, index)">
                                </div>

                            </div>
                            <input hidden name="theme_count" :value="themes.length">
                        @endverbatim
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
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script>
            var app=new Vue({
                el:"#vue",
                data:{
                    themes:JSON.parse(`<?php echo $themes; ?>`)
                },
                methods:{
                    fileChange:function(event, index){
                        var url="";
                        var files=$(event.target).prop('files');
                        if(typeof files[0]!="undefined")
                        {
                            url=URL.createObjectURL(files[0]);
                            this.themes[index].url=url;
                            $('#theme-image-preview-'+index).attr('src',url);
                        }
                    },
                    addTheme(){
                        this.themes.push({
                            'name':'',
                            'url':'https://dummyimage.com/1920x1080/fff/aaa'
                        })
                    },
                    deleteTheme(index){
                        this.themes.splice(index,1);
                    }

                }
            })
        </script>
    </div>
@endsection
