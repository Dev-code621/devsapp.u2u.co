@extends('admin.layouts.template',['menu'=>'epg-code'])
@section('page-content')
    <style>
        #edit-tag-modal{
            z-index: 1701;
        }
    </style>
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-heading">
                <h3 class="panel-title">Epg Code Lists</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <div class="text-right mb-10">
                        <button class="btn btn-primary" id="add-new-btn">Add New</button>
                    </div>
                    <table class="table" id="instruction-tags-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Country</th>
                                <th>Url</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($epg_codes as $item)
                                <tr>
                                    <td>{{$item->country}}</td>
                                    <td>{{$item->url}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="editTag(this, {{$item->id}})"><i class="fa fa-edit"></i></button>
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

    <div class="modal fade" id="edit-tag-modal" aria-hidden="true"
         aria-labelledby="examplePositionCenter"
         role="dialog" tabindex="-1">
        <div class="modal-dialog modal-simple modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Edit Tag</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Country</label>
                        <select class="form-control" data-plugin="select2" id="country">
                            @foreach($countries as $item)
                                <option value="{{$item->name}}">{{$item->code}} - {{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Url</label>
                        <input class="form-control" id="edit_tag_name">
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
            var current_tr, current_tag_id, dataTable, countries=<?php echo json_encode($countries) ?>;
            $(document).ready(function () {
                $('#country').select2({
                    dropdownParent: $('#edit-tag-modal')
                });
            })

            function deleteTag(targetElement, tag_id) {
                current_tr=$(targetElement).closest('tr');
                current_tag_id=tag_id;
                $('#delete-news-confirm-modal').modal('show');
            }

            function confirmDelete() {
                $.ajax({
                    method:'post',
                    url:site_url+"/admin/epg/delete/"+current_tag_id,
                    success:data=>{
                        $('#delete-news-confirm-modal').modal('hide');
                        showSuccessNotify("Instruction tag deleted successfully");
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
                current_tag_id='';
                $('#country').val('').trigger('change');
                $('#edit_tag_name').val('');
                $('#edit-tag-modal').modal('show');
            })

            function editTag(targetElement, tag_id){
                current_tr=$(targetElement).closest('tr');
                current_tag_id=tag_id;
                var country=$(current_tr).find('td:eq(0)').text();
                var current_tag_name=$(current_tr).find('td:eq(1)').text();
                $('#country').val(country).trigger('change');
                $('#edit_tag_name').val(current_tag_name);
                $('#edit-tag-modal').modal('show');
            }
            function confirmUpdate(){
                var updated_tag_name=$('#edit_tag_name').val();
                var country=$('#country').val();
                saveTag(updated_tag_name, country, current_tag_id);
            }

            function saveTag(url, country, id=''){
                $.ajax({
                    method:'post',
                    url:site_url+"/admin/epg/create",
                    dataType:'json',
                    data:{
                        url:url,
                        country:country,
                        id:id,
                    },
                    success:data=>{
                        $('#edit-tag-modal').modal('hide');
                        if(id===''){ // if create mode
                            var htmlContent=`
                            <tr>
                                <td>${country}</td>
                                <td>${url}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editTag(this,${data.id})"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteTag(this,${data.id})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `
                            $('#instruction-tags-table').find('tbody').append(htmlContent);
                        }
                        else{
                            var updated_tag_name=$('#edit_tag_name').val();
                            $(current_tr).find('td:eq(0)').text(country);
                            $(current_tr).find('td:eq(1)').text(updated_tag_name);
                        }
                    },
                    error:error=>{

                    }
                })
            }
        </script>
    </div>
@endsection





