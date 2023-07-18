@extends('admin.layouts.template',['menu'=>'crypto-coin-list-setting'])

@section('css')
    <link rel="stylesheet" href="{{asset('/admin/template/vendor/summernote/summernote.css')}}">
    <style>
        .price-item-container{
            padding-right:20px;
            margin-left: 0 !important;
            margin-right:0 !important;
        }
        .price-delete-icon{
            right:10px;
            color: #c00c06;
            top: 40px;
            cursor:pointer;
        }
        .price-add-icon{
            top: 10px;
            right: 10px;
            color: #2eaf0e;
            font-size: 35px;
            /* box-shadow: 0 0 5px #0e860e; */
            border-radius: 40px;
            height: 40px;
            width: 40px;
            text-align: center;
            line-height: 40px;
            cursor:pointer;
        }
        .price-add-icon:hover{
            color: #31e00e
        }

        .prices-container{
            background: #eee;
            box-shadow: 0 0 5px #333;
            padding:20px;
            border-radius: 10px;
            font-size:20px;
            padding-top:40px;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-body">
                <form method="post" action="{{url('admin/saveCoinList')}}">
                    @csrf
                    <div class="form-group prices-container position-relative" id="vue">
                        @verbatim
                            <i class="fa fa-plus-circle price-add-icon position-absolute" @click="addItem()"></i>
                            <div v-for="(item, index) in item_list" class="row price-item-container position-relative">
                                <i class="fa fa-trash position-absolute price-delete-icon" @click="deleteItem(index)" v-if="item_list.length>1"></i>
                                <div class="form-group col-6">
                                    <label>Code</label>
                                    <input type="text" class="form-control" name="codes[]" required v-model="item.code">
                                </div>
                                <div class="form-group col-6">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="names[]" required v-model="item.name">
                                </div>
                            </div>
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
        <script src="{{asset('/admin/template/vendor/summernote/summernote.min.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script>
            let app=new Vue({
                el:"#vue",
                data:{
                    item_list:[]
                },
                mounted(){
                    let item_list=<?=json_encode(!is_null($coin_list) ?  $coin_list->data : []);?>;
                    if(item_list.length==0){
                        item_list.push({
                            code:"",
                            name:""
                        })
                    }
                    this.item_list=item_list;
                },
                methods:{
                    addItem(){
                        this.item_list.push({
                            code:"",
                            name:""
                        })
                    },
                    deleteItem(index){
                        this.item_list.splice(index,1);
                    }
                }
            });

        </script>
    </div>
@endsection
