@extends('backend.layouts.master')

@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Missing Orders</h6>
      <a href="{{route('order.index')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Add User"><i class="fas fa-plus"></i> Back </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        {{-- @if(count($posts)>0) --}}
        <form action="{{ route('delete.missingOrders') }}" method="post" name="del-form" id="del-form" style="display:inline">
            @csrf
            <input type="hidden" value="" name="selected_del_ids" id="selected_del_ids">
            <input type="button" name="btn-del" id="btn-del" class="btn btn-danger text-white" value="Permanent Delete" title="Delete Selected Record Only">
        </form>
        <table class="table table-bordered table-hover" id="order-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th> <input type="checkbox" id="checkAll" value="0"></th>
              <th>#</th>
              <th>Prodcut Title</th>
              <th>Client</th>
              <th>Phone</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($missingOrders as $order)
                <tr>
                    <td><input type="checkbox" name="chkOrder[]" value="{{ $order->id }}"/></td>
                    <td>{{$order->id}}</td>
                    <td>{{$order->product_name}}</td>
                    <td>{{$order->name}}</td>
                    <td>{{$order->phone}}</td>
                    <td>{{$order->created_at}}</td>
                    <td>
                        <form method="post" action="{{ route('delete.missingOrder',[$order->id]) }}">
                            @csrf
                            @method('delete')
                          <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        {{-- <span style="float:right">{{$missingOrders->links()}}</span>
          <h6 class="text-center">No posts found!!! Please create Post</h6> --}}
      </div>
    </div>
    <!-- Visit 'codeastro' for more projects -->
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
      .zoom {
        transition: transform .2s; /* Animation */
      }

      .zoom:hover {
        transform: scale(5);
      }
  </style>
@endpush

@push('scripts')

  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>

  <script>

$(document).ready(function() {
    var table = $('#order-dataTable').DataTable();

    // Check All checkbox
    $('#order-dataTable thead').on('change', 'input[type="checkbox"]', function() {
        //alert();
        // var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]').prop('checked', this.checked);
    });

    // Individual checkbox
    $('#order-dataTable tbody').on('change', 'input[type="checkbox"]', function() {
        if (!this.checked) {
            var el = $('#checkAll').get(0);
            if (el && el.checked && ('indeterminate' in el)) {
                el.indeterminate = true;
            }
        }
    });
});

  $('#order-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[8,9,10]
                }
            ]
        } );
        // Sweet alert

        function deleteData(id){

        }
  </script>
  <script>
      $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
          $('.dltBtn').click(function(e){
              var form=$(this).closest('form');
              var dataID=$(this).data('id');
              // alert(dataID);
              e.preventDefault();
              swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                       form.submit();
                    } else {
                        swal("Your data is safe!");
                    }
                });
          })
      });

//delete Multiple record`
      $('#btn-del').on('click', function() {
            var selectedIds = [];
            // Get all checked checkboxes
            $('input[type="checkbox"]:checked').each(function() {
                selectedIds.push($(this).val());
            });
            console.log(selectedIds);
            $('#selected_del_ids').val(selectedIds);
            if($('#selected_del_ids').val() != '' && $('#selected_del_ids').val() != 'undefined')
            {
                if(confirm('Are you sure?'))
                {
                    $('#del-form').submit();
                }

            }else{
                alert("Please select orders first!")
            }
    });
  </script>
@endpush
