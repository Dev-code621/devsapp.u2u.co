@extends('admin.layouts.template',['menu'=>'language-word'])
@section('page-content')
    <style>
        #edit-word-modal{
            z-index: 1701;
        }
    </style>
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-heading">
                <h3 class="panel-title">Word Lists</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <div class="text-right mb-10">
                        <button class="btn btn-primary" id="add-new-btn">Add New</button>
                    </div>
                    <table class="table" id="instruction-tags-table">
                        <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($words as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editLanguage(this, {{$item->id}})"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteTag(this,{{$item->id}})">
                                        <i class="fa fa-trash"></i>
                                    </button>
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

    <div class="modal fade" id="edit-word-modal" aria-hidden="true"
         aria-labelledby="examplePositionCenter"
         role="dialog" tabindex="-1">
        <div class="modal-dialog modal-simple modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Edit Word</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" id="word_name">
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
                    url:site_url+"/admin/word/delete/"+current_id,
                    success:data=>{
                        $('#delete-news-confirm-modal').modal('hide');
                        showSuccessNotify("Word deleted successfully");
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
                $('#word_name').val('');
                $('#edit-word-modal').modal('show');
            })

            function editLanguage(targetElement, tag_id){
                current_tr=$(targetElement).closest('tr');
                current_id=tag_id;
                var word_name=$(current_tr).find('td:eq(0)').text();
                $('#word_name').val(word_name);
                $('#edit-word-modal').modal('show');
            }
            function confirmUpdate(){
                var word_name=$('#word_name').val();
                saveWord(word_name,current_id);
            }
            function saveWord(word_name, id=''){
                $.ajax({
                    method:'post',
                    url:site_url+"/admin/word/create",
                    dataType:'json',
                    data:{
                        word_name:word_name,
                        id:id,
                    },
                    success:data=>{
                        $('#edit-word-modal').modal('hide');
                        if(id===''){ // if create mode
                            var htmlContent=`
                            <tr>
                                <td>${word_name}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editLanguage(this,${data.id})"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteTag(this,${data.id})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `
                            $('#instruction-tags-table').find('tbody').append(htmlContent);
                        }
                        else{
                            $(current_tr).find('td:eq(0)').text(word_name);
                        }
                    },
                    error:error=>{
                    }
                })
            }
        </script>
    </div>
@endsection





