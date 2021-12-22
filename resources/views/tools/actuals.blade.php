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
    <h2>{{$data[0]->user}}</h2>
      <div class="form-group row">
        <div class="col-xs-3">
          <label for="year" class="control-label">Year</label>
          <select class="form-control select2" id="year" name="year" data-placeholder="Select a year">
            @foreach(config('select.year') as $key => $value)
            <option value="{{ $key }}">
              {{ $value }}
            </option>
              @endforeach
          </select>
        </div>
        <div class="col-xs-3">
          <label for="month" class="control-label">Weeks</label>
          <select class="form-control select2" id="week" name="week" data-placeholder="Select a week">
            @foreach(config('select.month_names') as $key => $value)
            <option value="{{ $key }}">
              {{ $value }}
            </option>
              @endforeach
          </select>
        </div>
      </div>  
  </div>
</div>
<!-- Week and Year drop down menues ended -->

<!-- Variables sent from Tools Controller -->
<input type="hidden" id="p_id" value="{{$project_id}}">
<input type="hidden" id="u_id" value="{{$user_id}}">
<input type="hidden" id="w" value="{{$week_no}}">
<input type="hidden" id="y" value="{{$year}}">

<div class="clearfix"><h3>{{$data[0]->project}}</h3></div> <!-- Project Name -->

<!-- Main table -->
<table id="sub_activity" class="table table-striped table-hover table-bordered mytablee" width="100%">
  <thead>
    <tr>
      <td>Activity</td>
      <td style="max-width: 30%; max-height: 40px;">Actual Hours</td>
      <td style="max-width: 20%;">Add</td>
    </tr>
  </thead>
  <tbody id='tableBody'>
    @foreach($content as $key1)
      <tr>
        <td>        
          <select id="select-1" class="form-control select2">
              <option value="{{$key1->id}}">{{$key1->name}}</option>
                @foreach( $innerContent as $key )
                  @if($key1->name != $key->name)
                    <option value="{{ $key->id }}">
                      {{ $key->name }}
                    </option>
                  @endif
                @endforeach
            </select> 
        </td>
        <td contenteditable='true' class='hour'>{{$key1->task_hour}}</td>
      </tr>
    @endforeach  
      <tr>
        <td>        
          <select id="select-2" class="form-control select2">
            <option value="" id="option-1">Select your activity</option>
              @foreach( $innerContent as $key )
            <option value="{{ $key->id }}">
              {{ $key->name }}
            </option>
              @endforeach
          </select>
        </td>
        <td contenteditable='true' class='hour' type='number'></td>
        <td><button type="button" id="button" class="glyphicon glyphicon-plus"></button></td>
      </tr>
  </tbody>
  <tfoot>
    <td>Total</td>
    <td id="totals"></td>
  </tfoot>
</table>
@stop

@section('script')
<script>
// Variables From Controller 
var yearFromController = $('#y').val();
var weekFromController = $('#w').val();
var pid = $('#p_id').val();
var uid = $('#u_id').val();
var taskId;

$('#year').val(yearFromController);
$('#week').val(weekFromController);

$(document).ready(function(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document).on("click","button",function(){
    var empty_th = $(this).closest('tr').find('.hour').html(); 
    // Stop button from adding if the current input row is empty
    if(empty_th == 0){
      return;
    }
    else{
    $('.glyphicon').remove();
    $('#tableBody').
    append(
      "<tr><td><select id='select-2' class='form-control select2'><option value='' id='option-1'>Select your activity</option>@foreach( $innerContent as $key )<option value='{{$key->id}}'>{{$key->name}}</option>@endforeach</select></td><td contenteditable='true' class='hour'></td><td id='l'><button type='button' class='glyphicon glyphicon-plus'></button></td></tr>");
    }
  })
  $(document).on("change",".select2",function(){
    taskId = $(this).val();
    var taskh = $(this).closest('tr').find('.hour').html();
    data ={
            'uid':uid,
            'pid':pid,
            'taskId':taskId,
            'taskHour':taskh,
            'week':weekFromController
      };
      $.ajax({
          type: 'POST',
          url: "{!! route('addNew') !!}",
          data:data,
          dataType: 'json',
          success: function(data) {
            console.log(data);
            getTotlas();
          }
    });
    console.log(taskh);
  })
  $(document).on("keyup",".hour",function(){
    var taskHour = $(this).html();
    var taskId2 = $(this).closest('tr').find('select.select2').val(); //If user filled the task hour while empty drop down menu
    if(typeof taskId2 === 'undefined' && typeof taskId !== 'undefined'){
      data = {
        'uid':uid,
        'pid':pid,
        'taskId':taskId,
        'taskHour':taskHour,
        'week':weekFromController
      };
      $.ajax({
        type: 'POST',
        url: "{!! route('addNew') !!}",
        data:data,
        dataType: 'json',
        success: function(data) {
          console.log(data);
          $('.hour').addClass('update_success');
          setTimeout(function () {
            $('.hour').removeClass('update_success');
          }, 1000);
        getTotlas();
        }
      });
    }
    else if(typeof taskId2 !== 'undefined'){ 
      data ={
        'uid':uid,
        'pid':pid,
        'taskId':taskId2,
        'taskHour':taskHour,
        'week':weekFromController
      };
      $.ajax({
        type: 'POST',
        url: "{!! route('addNew') !!}",
        data:data,
        dataType: 'json',
        success: function(data) {
          console.log(data);
          $('.hour').addClass('update_success');
          setTimeout(function () {
            $('.hour').removeClass('update_success');
          }, 1000);
          getTotlas();
        }
      });
    }
    else{
      return;
    }
  })
  // Avoid user to write letters
  $(".hour").keypress(function(e){
    if(isNaN(String.fromCharCode(e.which))) e.preventDefault();
  });
  $(document).on('change','#week',function(){
    var new_week = $('select#week').val();
    var new_year = $('select#year').val();
    window.location.href = "{!! route('getModalData',['','','','']) !!}/"+pid+"/"+uid+"/"+new_week+"/"+new_year;
  })
  $(document).on('change','#year',function(){
    var new_week = $('select#week').val();
    var new_year = $('select#year').val();
    window.location.href = "{!! route('getModalData',['','','','']) !!}/"+pid+"/"+uid+"/"+new_week+"/"+new_year;
  })
  // Total Actual Hours calculated
  function getTotlas(){
    var total =0;
    $('#sub_activity tbody').find('.hour').each(function(i,e){
      var d = parseInt($(this).html());
      var item = parseInt(d) || 0;
      total+=item;
    });
    console.log($('#sub_activity tfoot').find('#totals').html());
    $('#sub_activity tfoot').find('#totals').html(total);
  }
  // Run the function to have the totals ready
  getTotlas();
});
</script>
@stop