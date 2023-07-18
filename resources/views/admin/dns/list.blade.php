@extends('admin.layouts.template',['menu'=>'allow_dns'])
@section('page-content')
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-body">
                <div class="row justify-content-center">
                    <div class="col-6">
                        <form id="create-tag-form">
                            <div class="form-group">
                                <label>DNS Name</label>
                                <input type="text" class="form-control" id="dns_name" required>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" id="create-dns-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-boxed">
            <div class="panel-heading">
                <h3 class="panel-title">DNS List</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table" id="dns-list-table">
                        <thead class="table-dark">
                            <tr>
                                <th>DNS Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dns as $item)
                                <tr>
                                    <td>{{$item->dns_name}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="editDNS(this, {{$item->id}})"><i class="fa fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteDNS(this,{{$item->id}})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <!-- <a href="{{url('admin/dns/page/'.$item->id)}}">
                                            <button class="btn btn-success btn-sm"><i class="fa fa-eye"></i></button>
                                        </a> -->
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
                    <h4 class="modal-title">Confirm Delete DNS</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure to delete this DNS?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmDelete()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-dns-modal" aria-hidden="true"
         aria-labelledby="examplePositionCenter"
         role="dialog" tabindex="-1">
        <div class="modal-dialog modal-simple modal-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Edit DNS</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>DNS Name</label>
                        <input class="form-control" id="edit_dns_name">
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
            var current_tr, current_dns_id, dataTable;
            function deleteDNS(targetElement, dns_id) {
                current_tr=$(targetElement).closest('tr');
                current_dns_id=dns_id;
                $('#delete-news-confirm-modal').modal('show');
            }
            function confirmDelete() {
                $.ajax({
                    method:'post',
                    url:site_url+"/admin/dns/deleteDNS/"+current_dns_id,
                    success:data=>{
                        $('#delete-news-confirm-modal').modal('hide');
                        showSuccessNotify("DNS is deleted successfully");
                        $( current_tr ).remove();
                    },
                    error:error=>{
                        showErrorNotify("Sorry, Something is wrong, please try later")
                        $('#delete-news-confirm-modal').modal('hide');
                    }
                })
            }

            $('#create-dns-submit').click(function (e) {
                e.preventDefault();
                var dns_name=$('#dns_name').val();
                console.log(dns_name)
                saveDNS(dns_name);
            })

            function editDNS(targetElement, dns_id){
                current_tr=$(targetElement).closest('tr');
                current_dns_id=dns_id;
                var current_dns_name=$(current_tr).find('td:eq(0)').text();
                $('#edit_dns_name').val(current_dns_name);
                $('#edit-dns-modal').modal('show');
            }
            function confirmUpdate(){
                var updated_dns_name=$('#edit_dns_name').val();
                saveDNS(updated_dns_name, current_dns_id,true);
            }

            function saveDNS(dns_name, id='', is_update=false){
                $.ajax({
                    method:'post',
                    url:site_url+"/admin/dns/createDNS",
                    dataType:'json',
                    data:{
                        dns_name:dns_name,
                        id:id
                    },
                    success:data=>{
                        $('#edit-dns-modal').modal('hide');
                        if(!is_update){ // if create mode
                            var htmlContent=`
                            <tr>
                                <td>${dns_name}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editDNS(this,${data.id})"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteDNS(this,${data.id})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                   
                                </td>
                            </tr>
                        `
                            $('#dns-list-table').find('tbody').append(htmlContent);
                        }
                        else{
                            var updated_dns_name=$('#edit_dns_name').val();
                            $(current_tr).find('td:eq(0)').text(updated_dns_name);
                        }
                    },
                    error:error=>{

                    }
                })
            }
        </script>
    </div>
@endsection





