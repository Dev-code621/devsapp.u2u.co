@extends('admin.layouts.template',['menu'=>'reseller_packages'])

@section('page-content')
    <style>
        .filter-wrapper label{
            color:#fff !important;
        }
    </style>
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-heading">
                <h3 class="panel-title">Resellers</h3>
            </div>
            <div class="panel-body">
                <div class="list-wrapper">
                    <div class="text-right mb-20">
                        <button class="btn btn-primary" id="add-plan-btn">Add New Plan</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="item-list-table">
                            <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Max Connections</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody id="tbody">
                                @foreach($reseller_packages as $item)
                                    <tr>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->max_connections}}</td>
                                        <td>{{$item->price}}</td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-edit" data-package_id="{{$item->id}}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-primary btn-delete" data-package_id="{{$item->id}}">
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
    </div>
    <div class="modal fade" id="delete-confirm-modal">
        <div class="modal-dialog modal-simple modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Confirm Delete</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure to delete this package?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmDelete()">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog modal-simple modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Add or Edit Package</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Package Name</label>
                        <input type="text" id="package-name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Package Price</label>
                        <input type="number" id="package-price" class="form-control" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Max Connections</label>
                        <input type="number" id="max-connections" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addPackage()">Confirm</button>
                </div>
            </div>
        </div>
    </div>
        @endsection
        @section('script')
            <div>
                <script>
                    var current_tr, current_package_id, current_button;

                    $(document).on('click','.btn-delete', function () {
                        current_tr=$(this).closest('tr');
                        current_package_id=$(this).data('playlist_id');
                        $('#delete-confirm-modal').modal('show');
                    })

                    $('#add-plan-btn').click(function () {
                        current_package_id=-1;
                        $('#package-name').val('');
                        $('#package-price').val('')
                        $('#max-connections').val('');
                        $('#edit-modal').modal('show');
                    })
                    $(document).on('click','.btn-edit', function () {
                        current_tr=$(this).closest('tr');
                        current_package_id=$(this).data('package_id');
                        var package_name=$(this).closest('tr').find('td:eq(0)').text();
                        var max_connections=$(this).closest('tr').find('td:eq(1)').text();
                        var package_price=$(this).closest('tr').find('td:eq(2)').text();
                        $('#package-name').val(package_name);
                        $('#package-price').val(package_price)
                        $('#max-connections').val(max_connections);
                        $('#edit-modal').modal('show');
                    })

                    function addPackage() {
                        var package_name=$('#package-name').val();
                        var package_price=$('#package-price').val();
                        var max_connections=$('#max-connections').val();
                        if(package_name=='' || package_price=='' || max_connections==''){
                            showErrorNotify('Sorry, some fields are missed');
                            return;
                        }
                        $.ajax({
                            method:'post',
                            dataType:'json',
                            url:site_url+"/admin/price_package/create",
                            data:{
                                package_id:current_package_id,
                                package_name:package_name,
                                package_price:package_price,
                                max_connections:max_connections
                            },
                            success:data=>{
                                if(data.status=='success'){
                                    $('#activate-confirm-modal').modal('hide');
                                    var html=`
                                        <td>${package_name}</td>
                                        <td>${max_connections}</td>
                                        <td>${package_price}</td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-edit" data-package_id=${data.id}>
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-primary btn-delete" data-package_id=${data.id}>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    `
                                    if(current_package_id==-1){
                                        $('#tbody').append(`
                                                <tr>
                                                    ${html}
                                                </tr>
                                            `
                                        );
                                    }else{
                                        $(current_tr).html(html);
                                    }
                                    $('#edit-modal').modal('hide');
                                }else{

                                }
                            }
                        })
                    }

                    $(document).on('click','.btn-delete',function () {
                        current_package_id=$(this).data('package_id');
                        current_tr=$(this).closest('tr');
                        $('#delete-confirm-modal').modal('show');
                    })
                    function confirmDelete() {
                        $.ajax({
                            method:'post',
                            dataType:'json',
                            url:site_url+"/admin/price_package/delete",
                            data:{
                                package_id:current_package_id,
                            },
                            success:data=>{
                                if(data.status=='success'){
                                    $(current_tr).remove();
                                    $('#delete-confirm-modal').modal('hide');
                                }else{
                                }
                            }
                        })
                    }
                </script>
            </div>
@endsection





