@extends('admin.layouts.template',['menu'=>'language-code'])
@section('page-content')
    <style>
        #edit-language-modal{
            z-index: 1701;
        }
    </style>
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-heading">
                <h3 class="panel-title">Language Code Lists</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <div class="text-right mb-10">
                        <button class="btn btn-primary" id="add-new-btn">Add New</button>
                    </div>
                    <table class="table" id="instruction-tags-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($languages as $item)
                            <tr>
                                <td>{{$item->code}}</td>
                                <td>{{$item->name}}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editLanguage(this, {{$item->id}})"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteTag(this,{{$item->id}})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <a href="language-word/{{$item->id}}">
                                        <button class="btn btn-sm btn-success">
                                            Edit Words
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="delete-news-confirm-modal" aria-hidden="true"
         aria-labelledby="examplePositionCenter"
         role="dialog" tabindex="-1">
        <div class="modal-dialog modal-simple modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Confirm Delete News</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure to delete this news instruction tag?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmDelete()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-language-modal" aria-hidden="true"
         aria-labelledby="examplePositionCenter"
         role="dialog" tabindex="-1">
        <div class="modal-dialog modal-simple modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Edit Language</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Code</label>
                        <input class="form-control" id="language_code">
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" id="language_name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmUpdate()">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <div>
        <script>
            var current_tr, current_id, dataTable;
            function deleteTag(targetElement, tag_id) {
                current_tr=$(targetElement).closest('tr');
                current_id=tag_id;
                $('#delete-news-confirm-modal').modal('show');
            }

            function confirmDelete() {
                $.ajax({
                    method:'post',
                    url:site_url+"/admin/language/delete/"+current_id,
                    success:data=>{
                        $('#delete-news-confirm-modal').modal('hide');
                        showSuccessNotify("Language deleted successfully");
                        $( current_tr ).remove();
                    },
                    error:error=>{
                        showErrorNotify("Sorry, Something is wrong, please try later")
                        $('#delete-news-confirm-modal').modal('hide');
                    }
                })
            }

            $('#add-new-btn').click(function () {
                current_tr=null;
                current_id='';
                $('#language_code').val('');
                $('#language_name').val('');
                $('#edit-language-modal').modal('show');
            })

            function editLanguage(targetElement, tag_id){
                current_tr=$(targetElement).closest('tr');
                current_id=tag_id;
                var language_code=$(current_tr).find('td:eq(0)').text();
                var language_name=$(current_tr).find('td:eq(1)').text();
                $('#language_code').val(language_code);
                $('#language_name').val(language_name);
                $('#edit-language-modal').modal('show');
            }
            function confirmUpdate(){
                var language_name=$('#language_name').val();
                var language_code=$('#language_code').val();
                saveLanguage(language_code, language_name, current_id);
            }
            function saveLanguage(language_code, language_name, id=''){
                $.ajax({
                    method:'post',
                    url:site_url+"/admin/language/create",
                    dataType:'json',
                    data:{
                        language_code:language_code,
                        language_name:language_name,
                        id:id,
                    },
                    success:data=>{
                        $('#edit-language-modal').modal('hide');
                        if(id===''){ // if create mode
                            var htmlContent=`
                            <tr>
                                <td>${language_code}</td>
                                <td>${language_name}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editLanguage(this,${data.id})"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteTag(this,${data.id})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                    <a href="language-word/${data.id}">
                                        <button class="btn btn-sm btn-success">
                                            Edit Words
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        `
                            $('#instruction-tags-table').find('tbody').append(htmlContent);
                        }
                        else{
                            $(current_tr).find('td:eq(0)').text(language_code);
                            $(current_tr).find('td:eq(1)').text(language_name);
                        }
                    },
                    error:error=>{
                    }
                })
            }
        </script>
    </div>
@endsection





