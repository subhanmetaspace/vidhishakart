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
      <h6 class="m-0 font-weight-bold text-primary float-left">Order Lists</h6>
    </div>
    <div class="card-body">
    <p>
        <strong> Filter By: </strong>
        <a href="{{ url('admin/order/') }}?status=" id="btn-status" class="btn btn-dark">ALL</a>
        <a href="{{ url('admin/order/') }}?status=new" id="btn-status" class="btn btn-primary">NEW</a>
        <a href="{{ url('admin/order/') }}?status=confirm" id="btn-status" class="btn btn-info">CONFIRM</a>
        <a href="{{ url('admin/order/') }}?status=dispatched" id="btn-status" class="btn btn-warning">DISPATCHED</a>
        <a href="{{ url('admin/order/') }}?status=waiting" id="btn-status" class="btn btn-warning">WAITING</a>
        <a href="{{ url('admin/order/') }}?status=delivered" id="btn-status" class="btn btn-success">DELIVERED</a>
        <a href="{{ url('admin/order/') }}?status=delete" id="btn-status" class="btn btn-danger">DELETE</a>
        <a href="{{ url('admin/order/') }}?status=return" id="btn-status" class="btn btn-danger">RETURN</a>
        <a href="{{ url('admin/order/') }}?status=dup" id="btn-status" class="btn btn-warning">DUPLICATE</a>
    </p>
      <div class="table-responsive">
        @if(count($orders)>0)
        <select name="status" class="m-2 " id="status" style="height:35px">
            <option value="">Select Status</option>
            <option value="NEW">NEW</option>
            <option value="CONFIRM">CONFIRM</option>
            <option value="DISPATCHED">DISPATCHED</option>
            <option value="WAITING">WAITING</option>
            <option value="DELIVERED">DELIVERED</option>
            <option value="DELETE">DELETE</option>
            <option value="RETURN">RETURN</option>
        </select>
        <input type="button" class="btn btn-primary" value="Change Status" id="checkStatusButton">
        <form action="{{ route('export.csv') }}" method="post" name="export-form" id="export-form" style="display:inline">
            @csrf
            <input type="hidden" value="" name="selected_ids" id="selected_ids">
            <a type="button" name="btn-export" id="export-csv" class="btn btn-warning text-white">Export CSV</a>
        </form>
        <form action="{{ route('delete.orders') }}" method="post" name="del-form" id="del-form" style="display:inline">
            @csrf
            <input type="hidden" value="" name="selected_del_ids" id="selected_del_ids">
            <input type="button" name="btn-del" id="btn-del" class="btn btn-danger text-white" value="Permanent Delete" title="Delete Selected Record Only">
        </form>
        <table class="table table-bordered table-hover" id="order-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th> <input type="checkbox" id="checkAll"></th>
              <th class="d-none">id</th>
              <th>#</th>
              <th>Order No.</th>
              <th>Product</th>
              <th>Client</th>
              <th>Phone</th>
              <th>Qty.</th>
              <th>Order Date</th>
              <th>Status/Last Update</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php $counter = 0;  @endphp
            @foreach($orders as $order)
            @php

                $shipping_charge=DB::table('shippings')->where('id',$order->shipping_id)->pluck('price');

                if($order->phone)

            @endphp
                <tr @if(isset($_GET['status']) && $_GET['status'] == 'dup') class="bg-secondary text-white {{ $order->phone }}" @else class="row-{{ $order->phone }}" @endif>
                    <td><input type="checkbox" name="chkOrder[]" value="{{ $order->id }}"/></td>
                    <td class="d-none">{{ $order->id }}</td>
                    <td>{{++$counter}}</td>
                    @if($order->orderCount > 1)
                        <td class="bg-success">
                            <a href="{{ url('admin/order/') }}?status=same&orderId={{ $order->user_order_id }}" class="text-white" data-toggle="tooltip" title="View all order with this order ID" data-placement="bottom">
                                 {{ $order->user_order_id }}
                            </a>
                        </td>
                    @else
                        <td class="">{{ $order->user_order_id }}</td>
                    @endif
                    <td>{{$order->product_name}}</td>
                    <td class="col-{{ $order->phone }}">{{$order->first_name}} {{$order->last_name}}</td>

                    @if(isset($_GET['status']) && $_GET['status'] == 'dup')
                        <td>
                            <a href="{{ url('admin/order/') }}?status=dup&phone={{ $order->phone }}" class="text-white" data-toggle="tooltip" title="View duplicate records" data-placement="bottom"><u>{{ $order->phone }}</u></a>
                        </td>
                    @else
                        <td>{{$order->phone}}</td>
                    @endif

                    {{-- <td class="mobile" data-value="{{$order->phone}}">
                        <input type="hidden" class="phone" value="{{ $order->phone }}">
                        {{$order->phone}}
                    </td> --}}
                    <td>{{$order->quantity}}</td>
                    {{-- <td>@foreach($shipping_charge as $data) AED {{number_format($data,2)}} @endforeach</td>
                    <td>AED {{number_format($order->total_amount,2)}}</td> --}}
                    <td>{{ $order->created_at }}</td>
                    <td>
                        {{-- 'new',confirm,'delete',dispatched,return,waiting,'delivered' --}}
                        @if($order->status=='new')
                          <span class="badge badge-primary">NEW</span>
                          <br>{{$order->updated_at}}
                        @elseif($order->status=='confirm')
                          <span class="badge badge-info">Confirm</span>
                          <br>{{$order->updated_at}}
                        @elseif($order->status=='dispatched')
                          <span class="badge badge-warning">Dispatched</span>
                          <br>{{$order->updated_at}}
                        @elseif($order->status=='waiting')
                        <span class="badge badge-warning">Waiting</span>
                        <br>{{$order->updated_at}}
                        @elseif($order->status=='delivered')
                          <span class="badge badge-success">Delivered</span>
                          <br>{{$order->updated_at}}
                        @elseif($order->status=='delete')
                          <span class="badge badge-danger">Delete</span>
                          <br>{{$order->updated_at}}
                        @elseif($order->status=='return')
                        <span class="badge badge-danger">Return</span>
                        <br>{{$order->updated_at}}
                        @else
                          <span class="badge badge-danger">{{$order->status}}</span>
                          <br>{{$order->updated_at}}
                        @endif
                    </td>
                    <td>
                        <input type="hidden" class="phone" value="{{ $order->phone }}">
                        <a href="{{route('order.show',$order->id)}}" class="btn btn-warning btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="view" data-placement="bottom"><i class="fas fa-eye"></i></a>
                        <a href="{{route('order.edit',$order->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="Change Status" data-placement="bottom"><i class="fas fa-toggle-on"></i></a>
                        <a href="{{route('order.edit-order',$order->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="Edit Order Detail" data-placement="bottom"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{route('order.destroy',[$order->id])}}">
                          @csrf
                          @method('delete')
                              <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        @if(!isset($_GET['status']) || $_GET['status'] == '')
            <span style="float:right">{{$orders->links()}}</span>
        @endif
        @else
          <h6 class="text-center">No orders found!!! Please order some products</h6>
        @endif
      </div>
    </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
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
  @if(!isset($_GET['status']) || $_GET['status'] == '')
  <script>
      $('#order-dataTable').DataTable({
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[8],
                }
            ]
        });

  </script>
  @endif
   @if(isset($_GET['status']) && $_GET['status'] !== '')
  <script>
    $(document).ready(function() {
        $('#order-dataTable').dataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false });
    });

  </script>
   @endif
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
                       // swal("Your data is safe!");
                    }
                });
          })
      })

$(document).ready(function() {
    var table = $('#order-dataTable').DataTable();

    // Check All checkbox
    $('#checkAll').on('click', function() {
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    // Individual checkbox
    $('#example tbody').on('change', 'input[type="checkbox"]', function() {
        if (!this.checked) {
            var el = $('#checkAll').get(0);
            if (el && el.checked && ('indeterminate' in el)) {
                el.indeterminate = true;
            }
        }
    });
});

// Button click event
$('#checkStatusButton').on('click', function() {
        var selectedIds = [];
        // Get all checked checkboxes
        $('#order-dataTable tbody input[type="checkbox"]:checked').each(function() {
            var row = $(this).closest('tr');
            var data = $('#order-dataTable').DataTable().row(row).data();
            selectedIds.push(data[1]);
        });
        // Make an AJAX request
        if (selectedIds.length > 0) {
            //console.log(selectedIds);
            var status = $('#status').val();
            var token = "{{ csrf_token() }}";
            if(status != '')
            {
                $.ajax({
                    url: "{{ route('change.status') }}", // Replace with your actual AJAX endpoint
                    type: 'POST',
                    data: { 'ids': selectedIds, 'status': status, '_token': token },
                    success: function(response) {
                        // Handle the AJAX response
                        if(response == "yes")
                        {
                            window.location.reload();
                        }else{
                          //  alert("Status not update, Something went wrong!");
                        }
                        console.log(response);
                    },
                    error: function(error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }
            else {
                alert('No Status selected.');
            }

        } else {
            alert('No orders selected.');
        }
    });

//Export CSV
    $('#export-csv').on('click', function() {
        var selectedIds = [];
        // Get all checked checkboxes
        $('#order-dataTable tbody input[type="checkbox"]:checked').each(function() {
            var row  = $(this).closest('tr');
            var data = $('#order-dataTable').DataTable().row(row).data();
            selectedIds.push(data[1]);
        });
        $('#selected_ids').val(selectedIds);
        if($('#selected_ids').val() != '' && $('#selected_ids').val() != 'undefined')
        {
            $('#export-form').submit();
        }else{
            alert("Please select orders first!")
        }

    });

//Delete records
    $('#btn-del').on('click', function() {
            var selectedIds = [];
            // Get all checked checkboxes
            $('#order-dataTable tbody input[type="checkbox"]:checked').each(function() {
                var row  = $(this).closest('tr');
                var data = $('#order-dataTable').DataTable().row(row).data();
                selectedIds.push(data[1]);
            });
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

//check Today Order By Phone and change color
    var selectedIds = $('.phone').map((_,el) => el.value).get();
    console.log(selectedIds)
    var token = "{{ csrf_token() }}";
    $.ajax({
                url: "{{ route('order.checkTodayOrders') }}", // Replace with your actual AJAX endpoint
                type: 'POST',
                data: { 'ids': selectedIds, 'status': status, '_token': token },
                success: function(response){
                    //console.log(response);
                    var url = '';
                    response.forEach(function(item)
                    {
                        url = "{{ url('admin/order/') }}?status=dup&date=today&phone=" + item.phone;
                        console.log("Phone: " + item.phone + ", Count: " + item.phoneCount);
                        $('.row-' + item.phone).find('.col-' + item.phone).css('background-color', '#ddd');
                        $('.row-' + item.phone).find('.col-' + item.phone).html('<a href="'+ url + '" title="Click to view today\'s Order">' + item.full_name +'</a>');
                    });
                },
                error: function(error) {
                    console.error('AJAX Error:', error);
                }
        });
</script>
@endpush
