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
    <h3>{{$data[0]->user}}</h3>
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

      <div class="clearfix tableTitle">Your 12 Weeks Summarization</div> <!-- Project Name -->

      <!-- Main table -->
      <table id="sub_activity" class="table table-striped table-hover table-bordered mytablee2" width="100%">
        <thead>
          <tr class="tableFont">
            <td style="width:15%">Project Name</td>
            <td style="width:10%;">Project Type</td>
            <td id="clickable" class="font last" data-v="{{$week_no}}">Week {{$week_no}}</td>
            <td id="clickable" class="font" data-v="{{$week_2}}">Week {{$week_2}}</td>
            <td id="clickable" class="font" data-v="{{$week_3}}">Week {{$week_3}}</td>
            <td id="clickable" class="font" data-v="{{$week_4}}">Week {{$week_4}}</td>
            <td id="clickable" class="font" data-v="{{$week_5}}">Week {{$week_5}}</td>
            <td id="clickable" class="font" data-v="{{$week_6}}">Week {{$week_6}}</td>
            <td id="clickable" class="font" data-v="{{$week_7}}">Week {{$week_7}}</td>
            <td id="clickable" class="font" data-v="{{$week_8}}">Week {{$week_8}}</td>
            <td id="clickable" class="font" data-v="{{$week_9}}">Week {{$week_9}}</td>
            <td id="clickable" class="font" data-v="{{$week_10}}">Week {{$week_10}}</td>
            <td id="clickable" class="font" data-v="{{$week_11}}">Week {{$week_11}}</td>
            <td id="clickable" class="font" data-v="{{$week_12}}">Week {{$week_12}}</td>
          </tr>
        </thead>
        <tbody id='tableBody'>
          @foreach($data as $key => $value)
            <tr id="selectionRow" class="tableInfo">
            <td>{{$data[$key]->project}}</td>
            <td>{{$data[$key]->project_type}}</td>
            <td class="one font">{{$data[$key]->$week_no}}</td>
            <td class="two font">{{$data[$key]->$week_2}}</td>
            <td class="three font">{{$data[$key]->$week_3}}</td>
            <td class="four font">{{$data[$key]->$week_4}}</td>
            <td class="five font">{{$data[$key]->$week_5}}</td>
            <td class="six font">{{$data[$key]->$week_6}}</td>
            <td class="seven font">{{$data[$key]->$week_7}}</td>
            <td class="eight font">{{$data[$key]->$week_8}}</td>
            <td class="nine font">{{$data[$key]->$week_9}}</td>
            <td class="ten font">{{$data[$key]->$week_10}}</td>
            <td class="eleven font">{{$data[$key]->$week_11}}</td>
            <td class="twelve font">{{$data[$key]->$week_12}}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="tableFont">
          <td>Total</td>
          <td class="font" id="totals"></td>
          @foreach(config('select.totals') as $key => $month)
            <td class="font" id="{{$key}}"></td>
          @endforeach
        </tfoot>
      </table>
      @stop
    </div>
  </div>  
</div>

<input type="hidden" id="u_id" value="{{$user_id}}">
<input type="hidden" id="w" value="{{$oldWeek_no}}">
<input type="hidden" id="y" value="{{$oldYear_no}}">
<input type="hidden" id="read" value="{{$read}}">

@section('script')
<script>
var yearFromActuals = $('#y').val();
var weekFromActuals = $('#w').val();
var uid = $('#u_id').val();

$(document).ready(function(){
  $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});  

  function getTotals(){
    const totall =["one","two","three","four","five","six","seven","eight","nine","ten","eleven","twelve"];
    totall.forEach(function(element){
    var total = 0;
    $('#sub_activity tbody').find('.'+element).each(function(i,e){
      var d = parseInt($(this).html());
      var item = parseInt(d) || 0;
      total+=item;
    });
    $('#sub_activity tfoot').find('#'+element).html(total);
    });
  };
  $(document).on("click","#back",function(){
      if($("#read").val()==1){
      window.location.href = "{!! route('getModalData',['','','','','']) !!}/"+0+"/"+uid+"/"+weekFromActuals+"/"+yearFromActuals+"/"+1; 
      }else{
        window.location.href = "{!! route('getModalData',['','','','','']) !!}/"+0+"/"+uid+"/"+weekFromActuals+"/"+yearFromActuals+"/"+0;
      }
  })
  $(document).on("click","#clickable",function(){
      const week = $(this).data("v");
      const lastWeek = $(".last").data("v");
      var diff = lastWeek-week;
      var year = 0;
      if(diff<0){
        year = yearFromActuals-1;
      }else{
        year = yearFromActuals;
      }
      window.location.href = "{!! route('getModalData',['','','','','']) !!}/"+0+"/"+uid+"/"+week+"/"+year+"/"+1;
  })
  //Run the function to get the totals
  getTotals();
});
</script>
@stop