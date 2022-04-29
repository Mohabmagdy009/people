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
        <!-- Weeks and Year DropDown menues -->
        <div class="form-group row" style="width:400px;padding:5px ;font-size:15px">
          <div class="col-xs-6">
            <label for="year" class="control-label">Year</label>
            <select class="form-control" id="year" name="year" data-placeholder="Select a year">
              @foreach(config('select.year') as $key => $value)
              <option value="{{ $key }}" class="dropdown-item">
                {{ $value }}
              </option>
                @endforeach
            </select>
          </div>
          <div class="col-xs-6">
            <label for="week" class="control-label">Weeks</label>
            <select class="form-control" id="week" name="week" data-placeholder="Select a week">
              @foreach(config('select.month_names') as $key => $value)
              <option value="{{ $key }}" class="dropdown-item">
                {{ $value }}
              </option>
                @endforeach
            </select>
          </div>
        </div>
      </div>
      <!-- Weeks and Year DropDown menues ended -->

      <!-- Variables sent from Tools Controller -->
      <input type="hidden" id="u_id" value="{{$user_id}}">
      <input type="hidden" id="w" value="{{$week_no}}">
      <input type="hidden" id="y" value="{{$year}}">
      <input type="hidden" id="readOnly" value="{{$read}}">
      <input type="hidden" id="mid" value="{{$user_id}}">

      <!-- Report Button -->
      <div class="clearfix">
        <h2 style="display:inline-block;"></h2>
          <button type="button" id="actualsButton" class="btn btn-success" style="float: right;">
            Actuals Activity Report
          </button>
          <button type="button" id="back" class="btn btn-success" style="float: right;">
            Back
          </button>
      </div> 

      <!-- Main table -->
      <table id="sub_activity" class="table table-striped table-hover table-bordered mytablee" width="100%">
        <thead>
          <tr class="tableFont">
            <td style="width:40%">Project Name</td>
            <td style="width:39%">Activity</td>
            <td style="width:16%;" id="actuals">Actual Hours</td>
            <td style="width: 5%;" id="actions" colspan="2">Actions</td>
          </tr>
        </thead>
        <tbody id='tableBody'>
        @if($empty == "true")
          <tr id="selectionRow">
            <td cellpadding="0" cellspacing="0">        
              <select id="select-1" class="form-control projects">
                <option value="empty" id="option-1">Select your Project ...</option>
                  @foreach( $projects as $key )
                <option value="{{$key->project_id}}" class="dropdown-item">
                  {{$key->project_name}}
                </option>
                  @endforeach
              </select>
            </td>
            <td cellpadding="0" cellspacing="0">        
              <select id="select-1" class="form-control ndatabase"></select>
            </td>
            <td contenteditable='true' class='hour'></td>
            <td class="removable"><button type="button" id="button" class="glyphicon glyphicon-plus" title="Kindly enter the actual hours"></button></td>
            <td class="span"><button type="button" id="button2" class="glyphicon glyphicon-trash padding"></button></td>
          </tr>
        @else
        @foreach($data as $key1)
          <tr>
            <td cellpadding="0" cellspacing="0">        
              <select id="select-1" class="form-control projects">
                <option value="{{$key1->project_id}}" id="option-1">{{$key1->project}}</option>
                 @foreach( $projects as $key )
                <option value="{{$key->project_id}}" class="dropdown-item">
                  {{$key->project_name}}
                </option>
                  @endforeach
              </select>
            </td>
            <td cellpadding="0" cellspacing="0">        
              <select id="select-1" class="form-control ndatabase">
                  <option value="{{$key1->id}}">{{$key1->name}}</option>
                </select> 
            </td>
            <td contenteditable='true' class='hour'>{{$key1->task_hour}}</td>
            <td colspan="2" id="oldData">
              <button type="button" id="button2" class="glyphicon glyphicon-trash"style="padding-left: 45%;"></button>
            </td>
          </tr>
        @endforeach  
          <tr id="selectionRow">
             <td cellpadding="0" cellspacing="0">        
              <select id="select-1" class="form-control projects">
                <option value="empty" id="option-1">Select your Project ...</option>
                  @foreach( $projects as $key )
                <option value="{{$key->project_id}}" class="dropdown-item">
                  {{$key->project_name}}
                </option>
                  @endforeach
              </select>
            </td>
            <td cellpadding="0" cellspacing="0">        
              <select id="select-1" class="form-control ndatabase"></select>
            </td>
            <td contenteditable='true' class='hour'></td>
            <td class="removable"><button type="button" id="button" class="glyphicon glyphicon-plus" title="Kindly enter the actual hours"></button></td>
            <td class="span"><button type="button" id="button2" class="glyphicon glyphicon-trash padding"></button></td>
          </tr>
          @endif
        </tbody>
        <tfoot class="tableFont">
          <td>Total</td>
          <td></td>
          <td id="totals"></td>
          <td colspan="2" class="empty"></td>
        </tfoot>
      </table>
      @stop
    </div>
  </div>  
</div>

@section('script')
<script>
// Variables From Controller 
var mid = $('#mid').val();
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

  // Run the function to have the totals ready
  getTotals();
    
  var currentYear = getYear(new Date());
  var currentWeek = getWeek(new Date());

  //Lock any editing if the user is checking old data
  if($("#readOnly").val() == 1){
    $(".projects, .ndatabase, #year, #week").prop("disabled", true);
    $(".hour").prop("contenteditable",false);
    $(".empty, #oldData, #actions, #selectionRow").remove();
    $("#actualsButton").css("display","none");
  }
  else if($("#readOnly").val() == 2){
    $(".projects, .ndatabase, #year, #week").prop("disabled", true);
    $(".hour").prop("contenteditable",false);
    $(".empty, #oldData, #actions, #selectionRow").remove();
    $("#actualsButton").css("display","none");
    $("#readOnly").val(0);
  }

  //Make sure we have the correct subactivities of each project type
  $(document).on("change",".projects",function(){
    if($(this).val()==1813){
      $(this).closest("tr").find('.ndatabase').
      html("<option value= 'empty' id='option-1'>Select your activity ...</option>@foreach( $Account as $key ) @if($key->name != 'Zero')<option value='{{ $key->id }}' class='dropdown-item'>{{ $key->name }}</option>@endif @endforeach")
    }
    else if($(this).val()==1812){
      $(this).closest("tr").find('.ndatabase').
      html("<option value= 'empty' id='option-1'>Select your activity ...</option>@foreach( $General as $key )<option value='{{ $key->id }}' class='dropdown-item'>{{ $key->name }}</option>@endforeach")
    }
    else{ 
      $(this).closest("tr").find('.ndatabase').
      html("<option value= 'empty' id='option-1'>Select your activity ...</option>@foreach( $Opportunity as $key )<option value='{{ $key->id }}' class='dropdown-item'>{{ $key->name }}</option>@endforeach")
    }
  })

  $(document).on("click","#actualsButton",function(){
    var read = $("#readOnly").val();
    window.location.href = "{!! route('actualsView',['','','','','','']) !!}/"+uid+"/"+currentWeek+"/"+currentYear+"/"+weekFromController+"/"+yearFromController+"/"+read; 
  })
  $(document).on("click","#back",function(){
    if($("#readOnly").val() == 1){
       window.location.href = "{!! route('actualDetails',['','','']) !!}/"+mid+"/"+currentWeek+"/"+currentYear; 
    }else{
      window.location.href = "{!! route('toolsActivities') !!}/"; 
    }
  })

  //Tooltip for the user if he has an empty record on the Add button in the Actions Column
  $(document).on("mouseover","#button",function(){
    var projectCol = $(this).closest('tr').find('.projects').val();
    var empty_th = $(this).closest('tr').find('.hour').html();
    var selection = $(this).closest('tr').find('ndatabase').val();
    if(empty_th == '' || selection =='' || projectCol ==''){
      $(this).tooltip()
    }
    else{
      $(this).tooltip('disable');
    } 
  })

  //Add Button Action
  $(document).on("click","#button",function(){
    var projectCol = $(this).closest('tr').find('.projects').val();
    var empty_th = $(this).closest('tr').find('.hour').html();
    var selection = $(this).closest('tr').find('ndatabase').val();
    // Stop button from adding if the current input row is empty
    if(empty_th == '' || selection =='' || projectCol ==''){
      $(this).tooltip()
    }
    else{
    $('.removable').remove();
    $('.span').prop("colspan",2);
    $('.padding').css("padding-left","45%");
    $('#tableBody').
    append(
      "<tr id='selectionRow'><td cellpadding='0' cellspacing='0'><select id='select-1' class='form-control projects'><option value='empty' id='option-1'>Select your Project ...</option>@foreach( $projects as $key )<option value='{{$key->project_id}}'class='dropdown-item'>{{$key->project_name}}</option>@endforeach</select></td><td cellpadding='0' cellspacing='0'><select id='select-1' class='form-control ndatabase'></select></td><td contenteditable='true' class='hour'></td><td class='removable'><button type='button' id='button' class='glyphicon glyphicon-plus' title='Kindly enter the actual hours'></button></td><td class='span'><button type='button' id='button2' class='glyphicon glyphicon-trash padding'></button></td></tr>");
    }
  })

  //Sent data if the user selects an activity
  $(document).on("change","#select-1",function(){
    var projectCol = $(this).closest('tr').find('.projects').val();
    taskId = $(this).val();
    var taskh = $(this).closest('tr').find('.hour').html();
    if(taskh != '' && projectCol!='empty'){
      $(this).closest('tr').find('#button').tooltip('disable');
    }
    data =
      {
        'uid':uid,
        'pid':projectCol,
        'taskId':taskId,
        'taskHour':taskh,
        'week':weekFromController,
        'year':yearFromController
      };
    $.ajax(
      {
        type: 'POST',
        url: "{!! route('addNew') !!}",
        data:data,
        dataType: 'json',
        success: function(data) {
          getTotals();
        }
      }
    );
  })

  //Sent data if the user enters anything in the Actual Hours Column
  $(document).on("keyup",".hour",function(){
    var projectCol = $(this).closest('tr').find('.projects').val();
    var taskHour = $(this).html();
    var taskId2 = $(this).closest('tr').find('.ndatabase').val(); //If user filled the task hour while empty drop down menu
    if(taskId2 != null && projectCol != 'empty' && taskId2 != 'empty') {
      $(this).closest('tr').find('#button').tooltip('disable');
      data =
        {
          'uid':uid,
          'pid':projectCol,
          'taskId':taskId2,
          'taskHour':taskHour,
          'week':weekFromController,
          'year':yearFromController
        };
      $.ajax(
        {
          type: 'POST',
          url: "{!! route('addNew') !!}",
          data:data,
          dataType: 'json',
          success: function(data) {
            $('.hour').addClass('update_success');
            setTimeout(function () {
              $('.hour').removeClass('update_success');
            }, 1000);
            getTotals();
            $("#actualsButton").fadeIn(3000);
          }
        }
      );
    }
    else{
      return;
    }
  })

  //Delete the record when the user clicks on the delete button
  $(document).on("click","#button2",function () {
    var projectCol = $(this).closest('tr').find('.projects').val();
    var taskh = $(this).closest('tr').find('.hour').html();
    var taskId = $(this).closest('tr').find('.ndatabase').val();
    if(taskh != '' && taskId != 'empty' && projectCol!='empty'&&taskId != null){ 
    data = 
      {
        'uid':uid,
        'pid':projectCol,
        'taskId':taskId,
        'taskHour':taskh,
        'week':weekFromController,
        'year':yearFromController
      }
    $.ajax(
      {
        type: 'POST',
        url: "{!! route('delete') !!}",
        data:data,
        dataType: 'json',
        success: function() {
         location.reload();
        }
      }
    );
    }
    else {
      return;
    }
  })

  // Avoid user to write letters inside the Actual Hours Column
  $(document).on('keypress','.hour',(function(e){
    if(isNaN(String.fromCharCode(e.which))) e.preventDefault(); 
  }));

  //Drop down menus changes
  $(document).on('change','#week',function(){
    var new_week = $('select#week').val();
    var new_year = $('select#year').val();
    window.location.href = "{!! route('getModalData',['','','','','']) !!}/"+mid+"/"+uid+"/"+new_week+"/"+new_year+"/"+0;
  })
  $(document).on('change','#year',function(){
    var new_week = $('select#week').val();
    var new_year = $('select#year').val();
    window.location.href = "{!! route('getModalData',['','','','','']) !!}/"+mid+"/"+uid+"/"+new_week+"/"+new_year+"/"+0;
  })

  // Total Actual Hours calculated
  function getTotals(){
    var total =0;
    $('#sub_activity tbody').find('.hour').each(function(i,e){
      var d = parseInt($(this).html());
      var item = parseInt(d) || 0;
      total+=item;
    });
    $('#sub_activity tfoot').find('#totals').html(total);
    return total;
  }
});
</script>
@stop