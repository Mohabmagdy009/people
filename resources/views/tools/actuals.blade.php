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
  <div class="title_left"><h3>{{$data[0]->user}}</h3></div>
</div>
<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <!-- Window content -->
      <div class="x_content">    
      <div class="form-group row" style="width:400px;padding:5px ;font-size:15px">
        <div class="col-sm-6">
          <label for="year" class="control-label">Year</label>
          <select class="form-control select2" id="year" name="year" data-placeholder="Select a year">
            @foreach(config('select.year') as $key => $value)
            <option value="{{ $key }}">
              {{ $value }}
            </option>
              @endforeach
          </select>
        </div>
        <div class="col-sm-6">
          <label for="week" class="control-label">Weeks</label>
          <select class="form-control" id="week" name="week" data-placeholder="Select a week">
            @foreach(config('select.month_names') as $key => $value)
            <option value="{{ $key }}">
              {{ $value }}
            </option>
              @endforeach
          </select>
        </div>
      </div>

      <!-- Week and Year drop down menus ended -->

      <!-- Variables sent from Tools Controller -->
      <input type="hidden" id="p_id" value="{{$project_id}}">
      <input type="hidden" id="u_id" value="{{$user_id}}">
      <input type="hidden" id="w" value="{{$week_no}}">
      <input type="hidden" id="y" value="{{$year}}">

      <div class="clearfix"><h2>{{$data[0]->project}}</h2></div> <!-- Project Name -->

      <!-- Main table -->
      <table id="sub_activity" class="table table-striped table-hover table-bordered mytablee" width="100%">
        <thead>
          <tr style="font-size: 18px; font-weight:bold">
            <td style="width:79%">Activity</td>
            <td style="width:16%;" id="actuals">Actual Hours</td>
            <td style="width: 5%;" id="actions" colspan="2">Actions</td>
          </tr>
        </thead>
        <tbody id='tableBody'>
          @foreach($content as $key1)
            <tr>
              <td cellpadding="0" cellspacing="0">        
                <select id="select-1" class="form-control database" onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
              <td colspan="2" id="oldData">
                <button type="button" id="button2" class="glyphicon glyphicon-trash" title="Fill the empty record"style="padding-left: 45%;"></button>
              </td>
            </tr>
          @endforeach  
            <tr id="selectionRow">
              <td cellpadding="0" cellspacing="0">        
                <select id="select-1" class="form-control" onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                  <option value="" id="option-1">Select your activity ...</option>
                    @foreach( $innerContent as $key )
                  <option value="{{ $key->id }}">
                    {{ $key->name }}
                  </option>
                    @endforeach
                </select>
              </td>
              <td contenteditable='true' class='hour'></td>
              <td class="removable"><button type="button" id="button" class="glyphicon glyphicon-plus" title="Kindly enter the actual hours"></button></td>
              <td class="span"><button type="button" id="button2" class="glyphicon glyphicon-trash padding"></button></td>
            </tr>
        </tbody>
        <tfoot style="font-size: 18px; font-weight:bold">
          <td>Total</td>
          <td id="totals"></td>
        </tfoot>
      </table>
      @stop
    </div>
  </div>  
</div>
@section('script')
<script>
// Variables From Controller 
var yearFromController = $('#y').val();
var weekFromController = $('#w').val();
var pid = $('#p_id').val();
var uid = $('#u_id').val();
var taskId;

//Insert week and year values sent from the controller to the drop down menus
$('#year').val(yearFromController);
$('#week').val(weekFromController);

//Get the current Year
function getYear(d){
  d = new Date(d);
  var year = d.getFullYear();
  return year;
}

// This function is to get the Week number of the current week
function getWeek(d) { 
  d = new Date(d);
  var day = d.getDay();
  diff = d.getDate() - day + (day == 0 ? -6:1);
  var dd = new Date(d.setDate(diff));
  var y = dd.getFullYear();
  var firstMonth = new Date(y,0,1);
  var numberOfDays = Math.floor((dd - firstMonth)/ (24 * 60 * 60 * 1000));
  var m =Math.ceil(( dd.getDay() + 1 + numberOfDays) / 7);
  return m;
}

$(document).ready(function(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  var currentYear = getYear(new Date());
  var currentWeek = getWeek(new Date());

  //Lock any editing if the user is checking old data
  if(yearFromController < currentYear || weekFromController<currentWeek){
    $(".database").prop("disabled", true);
    $(".hour").prop("contenteditable",false);
    $("#selectionRow").remove();
    $("#actions").remove();
    $("#oldData").remove();
    console.log(currentWeek);
  }

  //tooltip for the user if he has an empty record
  $(document).on("mouseover","#button",function(){
    var empty_th = $(this).closest('tr').find('.hour').html();
    var selection = $(this).closest('tr').find('#select-1').val();
    if(empty_th == '' || selection ==''){
      $(this).tooltip()
    }
    else{
      $(this).tooltip('disable');
    } 
  })

  $(document).on("click","#button",function(){
    var empty_th = $(this).closest('tr').find('.hour').html();
    var selection = $(this).closest('tr').find('#select-1').val(); 
    // Stop button from adding if the current input row is empty
    if(empty_th == '' || selection ==''){
      $(this).tooltip()
    }
    else{
    $('.removable').remove();
    $('.span').prop("colspan",2);
    $('.padding').css("padding-left","45%");
    $('#tableBody').
    append(
      "<tr><td cellpadding='0' cellspacing='0'><select id='select-1' class='form-control select2' onfocus='this.size=5;' onblur='this.size=1;' onchange='this.size=1; this.blur();'><option value='' id='option-1'>Select your activity ...</option>@foreach( $innerContent as $key )<option value='{{ $key->id }}'>{{ $key->name }}</option>@endforeach</select></td><td contenteditable='true' class='hour'></td><td class='removable'><button type='button' id='button' class='glyphicon glyphicon-plus' title='Kindly enter the actual hours'></button></td><td class='span'><button type='button' id='button2' class='glyphicon glyphicon-trash padding'></button></td>");
    }
  })

  //Sent data if the user selects an activity
  $(document).on("change","#select-1",function(){
    taskId = $(this).val();
    var taskh = $(this).closest('tr').find('.hour').html();
    if(taskh != ''){
      $(this).closest('tr').find('#button').tooltip('disable');
    }
    data ={
            'uid':uid,
            'pid':pid,
            'taskId':taskId,
            'taskHour':taskh,
            'week':weekFromController,
            'year':yearFromController
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
  })

  //Sent data if the user enters any hour
  $(document).on("keyup",".hour",function(){
    var taskHour = $(this).html();
    var taskId2 = $(this).closest('tr').find('#select-1').val(); //If user filled the task hour while empty drop down menu
    console.log(taskId2);
    if(taskId2 != ''){
      $(this).closest('tr').find('#button').tooltip('disable');
      data ={
        'uid':uid,
        'pid':pid,
        'taskId':taskId2,
        'taskHour':taskHour,
        'week':weekFromController,
        'year':yearFromController
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

  //Delete the record when the user clicks on the delete button
  $(document).on("click","#button2",function () {
    var taskh = $(this).closest('tr').find('.hour').html();
    var taskId = $(this).closest('tr').find('#select-1').val();
    if(taskh !== '' && taskId !== ''){ 
    data={
      'uid':uid,
      'pid':pid,
      'taskId':taskId,
      'taskHour':taskh,
      'week':weekFromController,
      'year':yearFromController
    }
    $.ajax({
        type: 'POST',
        url: "{!! route('delete') !!}",
        data:data,
        dataType: 'json',
        success: function() {
          location.reload();
        }
      });
    }else{
      return;
    }
  })

  // Avoid user to write letters
  $(document).on('keypress','.hour',(function(e){
    if(isNaN(String.fromCharCode(e.which))) e.preventDefault(); 
  }));

  //Drop down menus changes
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