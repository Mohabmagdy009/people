@extends('layouts.app')


@section('style')
    <!-- CSS -->
    <!-- DataTables -->
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/build/css/custom2.css') }}" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('/css/datatables.css') }}">
    <!-- Select2 -->
    <link href="{{ asset('/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Switchery -->
    <link href="{{ asset('/plugins/gentelella/vendors/switchery/dist/switchery.min.css') }}" rel="stylesheet">
@stop

@section('scriptsrc')
    <!-- JS -->
    <!-- DataTables -->
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons/js/buttons.colVis.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons/js/buttons.flash.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons/js/buttons.print.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/jszip/dist/jszip.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/pdfmake/build/pdfmake.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/pdfmake/build/vfs_fonts.js') }}" type="text/javascript"></script>
    <!-- Select2 -->
    <script src="{{ asset('/plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <!-- Bootbox -->
    <script src="{{ asset('/plugins/bootbox/bootbox.min.js') }}"></script>
    <!-- Switchery -->
    <script src="{{ asset('/plugins/gentelella/vendors/switchery/dist/switchery.min.js') }}" type="text/javascript"></script>
    </script>
@stop

@section('content')
<!-- Page title -->
<div class="page-title">
  <div class="title_left">
    <h3>{{$projects[0]->name}}</h3>
  </div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <!-- Window content -->
      <div class="x_content">    
      <div class="form-group row" style="width:400px;padding:5px ;font-size:15px">
        <div class="col-sm-4">
          <label for="year" class="control-label">Year</label>
          <div class="form-control select2" id="year" name="year" data-placeholder="Select a year">{{$year}}</div>
        </div>
        <div class="col-sm-4">
          <label for="week" class="control-label">Weeks</label>
          <div class="form-control" id="week" name="week" data-placeholder="Select a week">Week {{$week_no}}</div>
        </div>
      </div>

      <div class="clearfix">
        <button type="button" id="back" class="btn btn-success" style="float: right;">
                     Back
            </button>
      </div> <!-- Project Name -->

      <!-- <div class="clearfix tableTitle">Your 12 Weeks Summarization</div>  -->
      <!-- Project Name -->

      <!-- Main table -->
      <table id="sub_activity" class="table table-striped table-hover table-bordered mytablee2" width="100%">
        <thead>
          <tr class="tableFont">
          	<td style="width:15%">Customer Name</td>
            <td style="width:15%">Project Name</td>
            <td style="width:10%;">Project Type</td>
            <td class="font last" data-v="{{$week_no}}">Week {{$week_no}}</td>
          </tr>
        </thead>
        <tbody id='tableBody'>
          @foreach($projects as $key => $value)
            <tr id="selectionRow" class="tableInfo">
            <td>{{$projects[$key]->project_name}}</td>
            <td>{{$projects[$key]->project_type}}</td>
            <td>{{$projects[$key]->customer_name}}</td>
            <td class="one font">{{$projects[$key]->task_hour}}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="tableFont">
          <td>Total</td>
          <td colspan="2"></td>
          <td class="font" id="totals"></td>
        </tfoot>
      </table>
      @stop
    </div>
  </div>  
</div>

@section('script')
<script>
$(document).ready(function(){
  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});  
  function getTotals(){
    var total = 0;
    $('#sub_activity tbody').find('.one').each(function(i,e){
      var d = parseInt($(this).html());
      var item = parseInt(d) || 0;
      total+=item;
    });
    $('#sub_activity tfoot').find('#totals').html(total);
    };
 
  $(document).on("click","#back",function(){
      window.location.href = "{!! route('toolsActivities') !!}"; 
  })
  //Run the function to get the totals
  getTotals();
});
</script>
@stop