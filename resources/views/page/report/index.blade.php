@extends('layouts.pdpa')

@section('content')
<aside class="right-side">

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissable margin5">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Success:</strong> {{ $message }}
    </div>
    @endif

    <section class="content-header">
        <!--section starts-->
        <h1>รายงานทั้งหมด</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">
                    <i class="livicon" data-name="home" data-size="14" data-loop="true"></i> รายงาน
                </a>
            </li>
            <li>
                <a href="#">รายงานทั้งหมด</a>
            </li>

        </ol>
    </section>


    <section class="content paddingleft_right15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i> ค้นหา
                    </h4>

                </div>
                <br />

                <br />
                <div class="panel-body">
                    <form class="form-horizontal" action="#" method="POST">
                        @csrf
                        <fieldset>
                            <!-- Name input-->
                            <div class="form-group">
                                <label class="col-md-1 control-label" for="name">สาขา</label>
                                <div class="col-md-3">
                                    <select class="form-control" name="branch_id" id="branch_id">

                                        @foreach($branch as $branchs)
                                            <option value="{{$branchs->id}}">{{$branchs->name}} </option>
                                        @endforeach
                                    </select>
                                 </div>

                                 <div class="form-group">
                                    <div class="col-md-3">
                                        <input id="name" name="name" type="text" placeholder="ค้นหา" class="form-control" required></div>
                                </div>




                        </fieldset>
                    </form>

                    <div class="form-group">
                        <div class="col-md-1">
                            <button type="button" class="btn btn-responsive btn-primary btn-sm" onclick="fitter();">ค้นหา</button>
                        </div>
                        <div class="col-md-1">

                            <a class="btn btn-responsive btn-success btn-sm" href="{{ route('file-export') }}">Export</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>



    <section class="content paddingleft_right15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i> รายงาน
                    </h4>

                </div>
                <br />

                <br />
                <div class="panel-body">
                    <table class="table table-bordered " id="datatables-example">
                        <thead>
                            <tr class="filters">
                                <th>ลำดับ</th>
                                <th>รหัสแบบฟอร์ม</th>
                                <th>สาขา</th>
                                <th>
                                    หมายเหตุ
                                </th>
                                <th>
                                    IP
                                </th>
                                <th>
                                    เบอร์โทร
                                </th>
                                <th>
                                    เวลา
                                </th>
                                <th>จัดการ</th>

                            </tr>
                        </thead>
                        <tbody>
                        <tr>

                        </tr>
                    </tbody>

                    </table>
                    <!-- Modal for showing delete confirmation -->
                    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title" id="user_delete_confirm_title">
                                        Delete User
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    Are you sure to delete this user? This operation is irreversible.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <a href="deleted_users.html" class="btn btn-danger">Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- row-->
    </section>
</aside>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function RefreshTable(data) {
data._token = "{{ csrf_token() }}";
data.branch_id = $('select[name=branch_id]').val();
data.name = $('input[name=name]').val();

return data;

}

function reloadData() {

table.ajax.reload(null, false);
}



function fitter() {

table.ajax.reload(null, false);
}


$('body').on('click', '.btn-fitter', function (event) {




});




var searchData = {};

var table = $('#datatables-example').DataTable({
    processing: true,
    serverSide: true,

    ajax: {
        url:  "{!! route('reportall.data') !!}",
        method: 'POST',
        data: RefreshTable,
    },
    columns: [
        {data: 'id'},
        {data: 'pdpaform_id'},
        {data: 'branch_id'},
        {data: 'note'},
        {data: 'ip'},
        {data: 'telephone'},
        {data: 'created_at'},
        {data: 'action', name: 'action', orderable:false, serachable:false},

    ],catch (Error) {
                if (typeof console != "undefined") {
                    console.log(Error);
                }
    },
    columnDefs: [{
                targets: [0,7],
                orderable: false,
                searchable: false
            },

            {
                    targets: 7,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {

                        var btnview = '<a   href="/downloadPDF/'+ row.id +'" data-id="'+ row.id +'"  class="btn btn-outline-dark btn-sm"><i class="fa fa-edit"></i> ดู</a>';


                         return btnview;
                    }
                },
        ]



});


$('body').on('click', '.btn-delete', function (e) {

var id = $(this).attr('data-id');

deleteConf(id)

});

function deleteConf(id) {
            swal({
                title: "คุณต้องการลบจริงหรือไม่?",
                text: "ข้อมูลไม่สามารถกู้คืนได้!",
                icon: "warning",
                buttons: [
                    'ยกเลิกลบรายการ',
                    'ลบรายการ'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    swal({
                        title: 'ลบรายการ!',
                        text: 'ลบรายการเรียบร้อย',
                        icon: 'success'
                    }).then(function() {
                        $.ajaxSetup({
                             headers: {
                                   'X-CSSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                 }
                                });
                        $.ajax({
                            dataType: 'json',
                            type:'DELETE',
                            data: {
                                '_token': "{{ csrf_token() }}",
                                id: id
                            },
                            url: '/branch/' + id,
                            success: function(datas){

                            location.reload();
                            }

                        })
                    });
                } else {
                    swal("ยกเลิก", "ยกเลิกรายการ", "error");
                }
            });
        } // error form show text



   </script>
@endsection


