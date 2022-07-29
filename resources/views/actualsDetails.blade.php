@extends('layouts.app')

@section('style')
    <!-- CSS -->
    <!-- DataTables -->
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">

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
    <h3>Actuals History</small></h3>
  </div>
</div>
<div class="clearfix"></div>
<!-- Page title -->
<!-- Window -->
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <!-- Window content -->
      <div class="x_content">
         <div class="form-group row">
        <div class="col-xs-1">
            <label for="year" class="control-label">Year</label>
            <div id="year-by" name="year" class="form-control select2"></div>
          </div>
          <div class="col-xs-1">
            <label for="month" class="control-label">Week</label>
            <div id="month-by" name="month" class="form-control select2"></div>
          </div>
          <div class="col-xs-3">
            <label for="manager" class="control-label">Manager</label>
            <div id="manager" name="manager" class="form-control select2">{{$manager_name[0]->name}}</div>
          </div>
           <div class="clearfix">
        <button type="button" id="back" class="btn btn-success" style="float: right;">
                     Back
            </button>
      </div>
      </div>
      </div>
        <!-- Main table -->
       <table id="sub_activity" class="table table-striped table-hover table-bordered mytablee2" width="100%">
        <thead>
          <tr class="tableFont">
            <td style="width:15%">User Name</td>
            <td class="font last" id="{{$week_no}}">Week {{$week_no}}</td>
            <td class="font" data-v="{{$week_2}}" id="s">Week {{$week_2}}</td>
            <td class="font" data-v="{{$week_3}}">Week {{$week_3}}</td>
            <td class="font" data-v="{{$week_4}}">Week {{$week_4}}</td>
            <td class="font" data-v="{{$week_5}}">Week {{$week_5}}</td>
            <td class="font" data-v="{{$week_6}}">Week {{$week_6}}</td>
            <td class="font" data-v="{{$week_7}}">Week {{$week_7}}</td>
            <td class="font" data-v="{{$week_8}}">Week {{$week_8}}</td>
            <td class="font" data-v="{{$week_9}}">Week {{$week_9}}</td>
            <td class="font" data-v="{{$week_10}}">Week {{$week_10}}</td>
            <td class="font" data-v="{{$week_11}}">Week {{$week_11}}</td>
            <td class="font" data-v="{{$week_12}}">Week {{$week_12}}</td>
          </tr>
        </thead>
        <tbody id='tableBody'>
          @foreach($data as $key => $value)
            <tr id="selectionRow" class="tableInfo">
            <td class="user" id= "{{$data[$key]->user_id}}">{{$data[$key]->user}}</td>
            <td id="{{$week_no}}" class="one font clickable">{{$data[$key]->$week_no}}</td>
            <td id="{{$week_2}}" class="two font clickable">{{$data[$key]->$week_2}}</td>
            <td id="{{$week_3}}" class="three font clickable">{{$data[$key]->$week_3}}</td>
            <td id="{{$week_4}}" class="four font clickable">{{$data[$key]->$week_4}}</td>
            <td id="{{$week_5}}" class="five font clickable">{{$data[$key]->$week_5}}</td>
            <td id="{{$week_6}}" class="six font clickable">{{$data[$key]->$week_6}}</td>
            <td id="{{$week_7}}" class="seven font clickable">{{$data[$key]->$week_7}}</td>
            <td id="{{$week_8}}" class="eight font clickable">{{$data[$key]->$week_8}}</td>
            <td id="{{$week_9}}" class="nine font clickable">{{$data[$key]->$week_9}}</td>
            <td id="{{$week_10}}" class="ten font clickable">{{$data[$key]->$week_10}}</td>
            <td id="{{$week_11}}" class="eleven font clickable">{{$data[$key]->$week_11}}</td>
            <td id="{{$week_12}}" class="twelve font clickable">{{$data[$key]->$week_12}}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="tableFont">
          <td>Total</td>
          @foreach(config('select.totals') as $key => $month)
            <td class="font" id="{{$key}}"></td>
          @endforeach
        </tfoot>
      </table>
      <!-- Window content -->
    </div>
  </div>
</div>
<!-- Window -->

<!-- Modal -->

<!-- Modal -->
<!-- Variables sent from Tools Controller -->
<input type="hidden" id="managerId" value="{{$user_id}}">
@stop
@section('script')
<script>
function getMonday(d) { // This function is to get the Week number of the current week
d = new Date(d); // Today's date
var day = d.getDay(); // Today's day in 0-6 (2)
diff = d.getDate() - day + (day == 0 ? -6:1); // Adjust when day is sunday
var dd = new Date(d.setDate(diff));
var y = dd.getFullYear();
var firstMonth = new Date(y,0,1);
var numberOfDays = Math.floor((dd - firstMonth)/ (24 * 60 * 60 * 1000));
var m =Math.ceil(( dd.getDay() + 1 + numberOfDays) / 7);
var fullDate = d.toDateString();

document.getElementById("month-by").innerHTML = m;

  return m;
}
function getYear(d){
d = new Date(d);
var year = d.getFullYear();
document.getElementById("year-by").innerHTML = year; 

return year;
}

getMonday(new Date())   
getYear(new Date());

$(document).ready(function(){
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click","#back",function(){
        window.location.href = "{!! route('toolsActivities') !!}"; 
    })

    $(document).on("click",".clickable",function(){
          var mid = $("#managerId").val();
          var uid = $(this).closest('tr').find(".user").attr('id');
          const week = $(this).attr('id');
          const lastWeek = $(".last").data("v");
          var diff = lastWeek-week;
          var year = 0;
          var yearFromBox = $("#year-by").html();
          if(diff<0){
            year = yearFromBox-1;
          }else{
            year = yearFromBox;
          }
          // console.log(week);
          window.location.href = "{!! route('getModalData',['','','','','']) !!}/"+mid+"/"+uid+"/"+week+"/"+year+"/"+1;
      }) 
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
    getTotals();
});
</script>
@stop