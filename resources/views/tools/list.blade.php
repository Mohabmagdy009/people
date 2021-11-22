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
    <script src="{{ asset('/plugins/bootbox/bootbox.min.js') }}"></script>script>
    <!-- Switchery -->
    <script src="{{ asset('/plugins/gentelella/vendors/switchery/dist/switchery.min.js') }}" type="text/javascript"></script>
@stop

@section('content')
<!-- Page title -->
<div class="page-title">
  <div class="title_left">
    <h3>Activities <small>(in days)</small></h3><button id="legendButton" class="btn btn-success btn-sm">legend</button>
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
        
        <!-- Selections for the table -->

        <div class="form-group row">
          <div class="col-xs-2">
            <label for="year" class="control-label">Year</label>
            <select class="form-control select2" id="year" name="year" data-placeholder="Select a year">
              @foreach(config('select.year') as $key => $value)
              <option value="{{ $key }}">
                {{ $value }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-xs-2">
            <label for="month" class="control-label">Week</label>
            <select class="form-control select2" id="month" name="month" data-placeholder="Select a week">
              @foreach(config('select.month_names') as $key => $value)
              <option value="{{ $key }}">
                {{ $value }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-xs-3">
            <label for="manager" class="control-label">Manager</label>
            <select class="form-control select2" id="manager" name="manager" data-placeholder="Select a manager" multiple="multiple">
              @foreach($authUsersForDataView->manager_list as $key => $value)
              <option value="{{ $key }}"
                @if(isset($authUsersForDataView->manager_selected) && $key == $authUsersForDataView->manager_selected) selected
                @endif>
                {{ $value }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-xs-3">
            <label for="user" class="control-label">User</label>
            <select class="form-control select2" id="user" name="user" data-placeholder="Select a user" multiple="multiple">
              @foreach($authUsersForDataView->user_list as $key => $value)
              <option value="{{ $key }}"
                @if(isset($authUsersForDataView->user_selected) && $key == $authUsersForDataView->user_selected) selected
                @endif>
                {{ $value }}
              </option>
              @endforeach
            </select>
          </div>
          <div class="col-xs-2">
            <label for="closed" class="control-label">Hide closed</label>
            <input name="closed" type="checkbox" id="closed" class="form-group js-switch-small" checked /> 
          </div>
        </div>

        <!-- Selections for the table -->

        <!-- Create new button -->
        @can('tools-activity-new')
        <div class="row button_in_row">
          <div class="col-md-12">
            <button id="new_project" class="btn btn-info btn-xs" align="left"><span class="glyphicon glyphicon-plus"> New Project</span></button>
          </div>
        </div>
        @endcan
        <!-- Create new button -->
        
        <!-- Main table -->
        <table id="activitiesTable" class="table table-striped table-hover table-bordered mytable" width="100%">
          <thead>
            <tr>
              <th>Manager ID</th>
              <th>Manager name</th>
              <th>User ID</th>
              <th>User name</th>
              <th>User Practice</th>
              <th>User country</th>
              <th>Employee type</th>
              <th>Customer name</th>
              <th>Customer cluster</th>
              <th>Customer country</th>
              <th>Project ID</th>
              <th>Project name</th>
              <th>Project type</th>
              <th>Activity type</th>
              <th>Project status</th>
              <th>Prime code</th>
              <th>Meta activity</th>
              <th>Project subtype</th>
              <th>Technology</th>
              <th>CL ID</th>
              <th>Pullthru CL ID</th>
              <th>Order intake inc. CS</th>
              <th>Consulting TCV</th>
              <th>Pullthru TCV</th>
              <th>CL Owner</th>
              <th>CL lead domain</th>
              <th>CL stage</th>
              <th>Start date</th>
              <th>End date</th>
              <th>Gold order</th>
              <th>Win ratio (%)</th>
              @foreach(config('select.available_months') as $key => $month)
              <th id="table_month_{{$key}}"></th>
              <th>ID</th>
              <th>OTL</th>
              @endforeach
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              @foreach(config('select.available_months') as $key => $month)
              <th></th>
              <th></th>
              <th></th>
              @endforeach
            </tr>
          </tfoot>
        </table>
        <!-- Main table -->

      </div>
      <!-- Window content -->

    </div>
  </div>
</div>
<!-- Window -->

<!-- Modal -->
<div class="modal fade" id="legendModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
              <button type="button" class="close" 
                  data-dismiss="modal">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only">Close</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">
                  Legend
              </h4>
          </div>
            
          <!-- Modal Body -->
          <div class="modal-body">
              
                <table class="table borderless">
                  <thead>
                    <th>Color</th><th>Meaning</th>
                  </thead>
                  <tbody>
                    <tr><td style="color: green;">Green</td><td>Validated by OTL</td></tr>
                    <tr><td style="color: blue;">Blue</td><td>Forecast entered by consultant</td></tr>
                  </tbody>
                </table>
              
          </div>
            
          <!-- Modal Footer -->
          <div class="modal-footer">
              <button type="button" class="btn btn-default"
                      data-dismiss="modal">
                          Close
              </button>
          </div>
        </div>
    </div>
</div>
<!-- Modal -->

  @stop

  @section('script')
  <script>
  var activitiesTable;
  var year = [];
  var month = [];
  var manager = [];
  var user = [];
  var month_col = [];
  var header_months = [];
  var checkbox_closed = 0;

  // switchery
  var small = document.querySelector('.js-switch-small');
  var switchery = new Switchery(small, { size: 'small' });
  // console.log(activity_list);

  function ajaxData(){
    var obj = {
      'year[]': year,
      'month[]': month,
      'manager[]': manager,
      'user[]': user,
      'checkbox_closed':checkbox_closed
    };
    return obj;
  }

  // This is the function that will set the values in the select2 boxes with info from Cookies
  function fill_select(select_id){
    array_to_use = [];
    values = Cookies.get(select_id);

    if (values != null) {
      values = values.replace(/\"/g,'').replace('[','').replace(']','');
      values = values.split(',');
      $('#'+select_id).val(values).trigger('change');
      array_to_use = [];
      $("#"+select_id+" option:selected").each(function()
      {
        // log the value and text of each option
        array_to_use.push($(this).val());

      });
    }
    else {
      var d = new Date();
      var y = d.getFullYear();
      var m = d.getMonth()+1;
      if (select_id == 'year') {
        $('#'+select_id).val(y).trigger('change');
      } else if (select_id == 'month') {
        $('#'+select_id).val(m).trigger('change');
      }
      $("#"+select_id+" option:selected").each(function()
      {
        // log the value and text of each option
        array_to_use.push($(this).val());
      });
    }
    return array_to_use;
  }

  $(document).ready(function() {



    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });


    //region Selection
    // SELECTIONS START
    // ________________
    // First we define the select2 boxes

    //Init select2 boxes
    $("#year").select2({
      allowClear: false
    });
    $("#month").select2({
      allowClear: false
    });
    $("#manager").select2({
      allowClear: false,
      disabled: {{ $authUsersForDataView->manager_select_disabled }}
    });
    $("#user").select2({
      allowClear: false,
      disabled: {{ $authUsersForDataView->user_select_disabled }}
    });

    // Then we need to get back the information from the cookie

    year = fill_select('year');
    month = fill_select('month');
    manager = fill_select('manager');
    user = fill_select('user');

    if (Cookies.get('checkbox_closed') != null) {
      if (Cookies.get('checkbox_closed') == 0) {
        checkbox_closed = 0;
        $('#closed').click();
      } else {
        checkbox_closed = 1;
      }
    }

    //alert($.fn.dataTable.version);

    // Then we define what happens when the selection changes

    $('#year').on('change', function() {
      Cookies.set('year', $('#year').val());
      year = [];
      $("#year option:selected").each(function()
      {
        // log the value and text of each option
        year.push($(this).val());

      });
      activitiesTable.ajax.reload(update_headers());
    });

    $('#month').on('change', function() {
      Cookies.set('month', $('#month').val());
      month = [];
      $("#month option:selected").each(function()
      {
        // log the value and text of each option
        month.push($(this).val());

      });
      //console.log(month);
      activitiesTable.ajax.reload(update_headers());
    });

    $('#manager').on('change', function() {
      Cookies.set('manager', $('#manager').val());
      manager = [];
      $("#manager option:selected").each(function()
      {
        // log the value and text of each option
        manager.push($(this).val());

      });
      activitiesTable.ajax.reload(update_headers());
    });

    $('#user').on('change', function() {
      Cookies.set('user', $('#user').val());
      user = [];
      $("#user option:selected").each(function()
      {
        // log the value and text of each option
        user.push($(this).val());

      });
      activitiesTable.ajax.reload(update_headers());
    });

    $('#closed').on('change', function() {
      if ($(this).is(':checked')) {
        Cookies.set('checkbox_closed', 1);
        checkbox_closed = 1;
      } else {
        Cookies.set('checkbox_closed', 0);
        checkbox_closed = 0;
      }
      //console.log(checkbox_closed);
      activitiesTable.ajax.reload();
    });

    // SELECTIONS END
    //endregion

    month_col = [31, 34, 37, 40, 43, 46, 49, 52, 55, 58, 61, 64];

    // This is to color in case it comes from prime or if forecast
    function color_for_month_value(value,from_otl,id,colonne,project_id,user_id,td) {
      if (from_otl == 1) {
        if (value == 0) {
          $(td).addClass("otl_zero");
        } else {
          $(td).addClass("otl");
        }        
      } else {
        @can('tools-activity-edit')
        $(td).addClass("editable");
        $(td).attr('contenteditable', true);
        $(td).attr('data-id', id);
        $(td).attr('data-colonne', colonne);
        $(td).attr('data-project_id', project_id);
        $(td).attr('data-user_id', user_id);
        $(td).attr('data-value', value);
        @endcan
        if (value == 0) {
          $(td).addClass("zero");
        } else {
          $(td).addClass("forecast");
        } 
      }
    }

    // This is to update the headers
    function update_headers() {
      months_from_selection = [];
      months_name = [
        @foreach(config('select.week') as $key => $month)
          '{{$month}}'
          @if($month != 'Week 52'),@endif
        @endforeach
      ];
      header_months = [];
      
      for (let index = parseInt(month[0],10); index <= 52; index++) {
        this_year = parseInt(year[0],10);
        months_from_selection.push(months_name[index-1]+' '+this_year.toString().substring(2));
        header_months.push({'year':this_year,'month':index});
      }
      if (month[0] > 1) {
        next_year = parseInt(year[0], 10)+1;
        for (let index = 1; index <= month[0]-1; index++) {
          months_from_selection.push(months_name[index-1]+' '+next_year.toString().substring(2));
          header_months.push({'year':next_year,'month':index});
        }
      }

      //console.log(months_from_selection);
      
      // We change the title of the months as it varies in function of the year and month selected
      for (let index = 1; index <= 52; index++) {
          //console.log(month);
          $('#table_month_'+index).empty().html(months_from_selection[index-1]);
        }
    }

  

    activitiesTable = $('#activitiesTable').DataTable({
      scrollX: true,
      @if(isset($table_height))
      scrollY: '{!! $table_height !!}vh',
      scrollCollapse: true,
      @endif
      serverSide: true,
      processing: true,
      stateSave: true,//m
      ajax: {
        url: "{!! route('listOfActivitiesPerUserAjax') !!}",
        type: "POST",
        data: function ( d ) {
          // console.log(ajaxData());
          $.extend(d,ajaxData());
        }, success: function(data){
          console.log(data);
        },
        dataType: "JSON"
      },
      columns: [
        { name: 'uu.manager_id', data: 'manager_id' , searchable: false , visible: false},
        { name: 'm.name', data: 'manager_name' , className: "dt-nowrap", visible: false},
        { name: 'temp_a.user_id', data: 'user_id' , searchable: false , visible: false},
        { name: 'u.name', data: 'user_name' , className: "dt-nowrap"},
        { name: 'u.domain', data: 'user_domain' , searchable: true, visible: false, className: "dt-nowrap"},
        { name: 'u.country', data: 'user_country' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'u.employee_type', data: 'user_employee_type' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'c.name', data: 'customer_name' , className: "dt-nowrap"},
        { name: 'c.cluster_owner', data: 'customer_cluster_owner' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'c.country_owner', data: 'customer_country_owner' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'temp_a.project_id', data: 'project_id' , searchable: false , visible: false},
        { name: 'p.project_name', data: 'project_name', className: "dt-nowrap"},
        { name: 'p.project_type', data: 'project_type', 
        render: function (data,type, rowData) {
          if (type === 'display') {
            @can('projectLoe-view')
            if (data == 'Pre-sales') {
              if (rowData.num_of_loe >=1) {
                return data + '<a href="{!! route('loeView','') !!}/'+rowData.project_id+'"><img src="{{ asset("/img/loe.png") }}" width="20" height="20" style="margin-left:10px;"></a>';
              } 
              @can('projectLoe-create')
              else {
                return data + '<span><img class="create_loe" data-project_id="'+rowData.project_id+'" src="{{ asset("/img/loe-bw.png") }}" width="20" height="20" style="margin-left:10px;"></span>';
              }
              @endcan
            }
            @endcan
          }
          return data;
        },
        visible: true, className: "dt-nowrap"},
        { name: 'p.activity_type', data: 'activity_type', visible: false, className: "dt-nowrap"},
        { name: 'p.project_status', data: 'project_status' , visible: true, className: "dt-nowrap"},
        { name: 'p.otl_project_code', data: 'otl_project_code' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.meta_activity', data: 'meta_activity' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.project_subtype', data: 'project_subtype' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.technology', data: 'technology' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.samba_id', data: 'samba_id' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.pullthru_samba_id', data: 'pullthru_samba_id' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.revenue', data: 'project_revenue' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.samba_consulting_product_tcv', data: 'samba_consulting_product_tcv' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.samba_pullthru_tcv', data: 'samba_pullthru_tcv' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.samba_opportunit_owner', data: 'samba_opportunit_owner' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.samba_lead_domain', data: 'samba_lead_domain' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.samba_stage', data: 'samba_stage' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.estimated_start_date', data: 'estimated_start_date' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.estimated_end_date', data: 'estimated_end_date' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.gold_order_number', data: 'gold_order_number' , searchable: true , visible: false, className: "dt-nowrap"},
        { name: 'p.win_ratio', data: 'win_ratio' , searchable: true , visible: false, className: "dt-nowrap"}
        @foreach(config('select.available_months') as $key => $month)

          ,{ name: 'm{{$key}}_com', data: 'm{{$key}}_com', 
            createdCell: function (td, cellData, rowData, row, col) {
              color_for_month_value(rowData.m{{$key}}_com,rowData.m{{$key}}_from_otl,rowData.m{{$key}}_id,{{$key}},rowData.project_id,rowData.user_id,td);
            }, width: '30px', searchable: false, visible: true, orderable: false},

          { name: 'm{{$key}}_id', data: 'm{{$key}}_id', width: '10px', searchable: false , visible: false, orderable: false},
          { name: 'm{{$key}}_from_otl', data: 'm{{$key}}_from_otl', width: '10px', searchable: false , visible: false, orderable: false}
          
        @endforeach
      ],
      order: [[1, 'asc'],[3, 'asc'],[7, 'asc'],[12, 'asc']],
      lengthMenu: [
          [ 10, 25, 50, -1 ],
          [ '10 rows', '25 rows', '50 rows', 'Show all' ]
      ],
      dom: 'Bfrtip',
      buttons: [
        {
          extend: "colvis",
          className: "btn-sm",
          collectionLayout: "three-column",
          columns: [ 1, 3, 4, 5, 6,7,8,9, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 34, 37, 40, 43, 46, 49, 52, 55, 58, 61, 64 ]
        },
        {
          extend: "pageLength",
          className: "btn-sm"
        },
        {
          extend: "csv",
          className: "btn-sm",
          exportOptions: {
              columns: ':visible'
          }
        },
        {
          extend: "excel",
          className: "btn-sm",
          exportOptions: {
              columns: ':visible'
          }
        },
        {
          extend: "print",
          className: "btn-sm",
          exportOptions: {
              columns: ':visible'
          }
        },
      ],
      footerCallback: function ( row, data, start, end, display ) {
        var api = this.api(), data;

        $.each(month_col, function( index, value ) {
          // Total over this page
          pageTotal = api
            .column( value, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
              return parseFloat(a)+parseFloat(b);
            }, 0 );

          // Update footer
          $( api.column( value ).footer() ).html(
              '<div style="font-size: 120%;">'+pageTotal.toFixed(1)+'</div>'
          );
        });
      },
      initComplete: function () {
        update_headers();
        // We create section below columns
        var columns = this.api().init().columns;
        this.api().columns().every(function () {
          var column = this;
          // this will get us the index of the column
          index = column[0][0];
          //console.log(columns[index].searchable);

          // Now we need to skip the column if it is not searchable and we return true, meaning we go to next iteration
          if (columns[index].searchable == false) {
            return true;
          }
          else {
            var input = document.createElement("input");
            $(input).appendTo($(column.footer()).empty())
            .on('keyup change', function () {
              column.search($(this).val(), false, false, true).draw();
            });
          };
        });
        // Restore state
        var state = activitiesTable.state.loaded();
        if (state) {
            activitiesTable.columns().eq(0).each(function (colIdx) {
                var colSearch = state.columns[colIdx].search;

                if (colSearch.search) {
                    $('input', activitiesTable.column(colIdx).footer()).val(colSearch.search);
                }
            });

            activitiesTable.draw();
        }
      }
    });

    //Create LoE
    @can('projectLoe-create')
    $('#activitiesTable').on('click','.create_loe', function() {
      var project_id = $(this).data('project_id');
      var span = $(this).closest('span');
      $.ajax({
        type: 'get',
        url: "{!! route('loeInit','') !!}/"+project_id,
        dataType: 'json',
        beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                span.empty();
            },
        success: function(data) {
          if (data.result == 'success'){
              box_type = 'success';
              message_type = 'success';
          }
          else {
              box_type = 'danger';
              message_type = 'error';
          }

          $('#flash-message').empty();
          var box = $('<div id="delete-message" class="alert alert-'+box_type+' alert-dismissible flash-'+message_type+'" role="alert"><button href="#" class="close" data-dismiss="alert" aria-label="close">&times;</button>'+data.msg+'</div>');
          $('#flash-message').append(box);
          $('#delete-message').delay(2000).queue(function () {
              $(this).addClass('animated flipOutX')
          });
        },
        error: function (jqXhr, textStatus, errorMessage) { // error callback 
          console.log('Error: ' + errorMessage);
        },
        complete: function () { // Set our complete callback, adding the .hidden class and hiding the spinner.
          activitiesTable.ajax.reload(update_headers());
            }
      });
    });
    @endcan

    @can('tools-activity-edit')
    $('#activitiesTable').on('click', 'tbody td', function() {
      var table = activitiesTable;
      var tr = $(this).closest('tr');
      var row = table.row(tr);
      //get the initialization options
      var columns = table.settings().init().columns;
      //get the index of the clicked cell
      var colIndex = table.cell(this).index().column;
      //console.log('you clicked on the column with the name '+columns[colIndex].name);
      //console.log('the user id is '+row.data().user_id);
      //console.log('the project id is '+row.data().project_id);
      // If we click on the name, then we create a new project
      if (columns[colIndex].name == 'p.project_name') {
        year = [];
        $("#year option:selected").each(function()
        {
          // log the value and text of each option
          year.push($(this).val());
        });
        window.location.href = "{!! route('toolsFormUpdate',['','','']) !!}/"+row.data().user_id+"/"+row.data().project_id+"/"+year[0];
      }
    });

    var editable_old_value = ''
    // This is to select the text when you click inside a td that is editable
    $(document).on('focus', 'td.editable', function() {
        var range = document.createRange();
        range.selectNodeContents(this);  
        var sel = window.getSelection(); 
        sel.removeAllRanges(); 
        sel.addRange(range);
        editable_old_value = $(this).html();
    });

    $(document).on('keypress', '.editable', function(e){
      //console.log('editing');
      if (e.which  == 13) { //Enter key's keycode
        update_activity($(this));
        return false;
      }
    });


    $(document).on('blur', '.editable', function(e){
      //console.log('editing');
      update_activity($(this));
    });

    function update_activity(td) {
      /* console.log(td.data('id'));
      console.log(td.data('project_id'));
      console.log(td.data('user_id'));
      console.log(td.data('colonne'));
      console.log(td.html());
      console.log(header_months[td.data('colonne')-1]); */
      if (td.html() != editable_old_value) {
        var td;
        var data = {
          'id':td.data('id'),
          'project_id':td.data('project_id'),
          'user_id':td.data('user_id'),
          'year':header_months[td.data('colonne')-1].year,
          'month':header_months[td.data('colonne')-1].month,
          'task_hour':td.html()
        }

        $.ajax({
              type: 'POST',
              url: "{!! route('updateActivityAjax') !!}",
              data:data,
              dataType: 'json',
              success: function(data) {
                //console.log(data);
                // SUCCESS
                if (data.result == 'success'){
                  if (data.action == 'create') {
                    td.attr('data-id', data.id); 
                  }
                  td.removeClass();
                  td.addClass('editable');
                  if (td.html() == 0) {
                    td.addClass('zero');
                  } else {
                    td.addClass('forecast');
                  }
                  td.attr('data-value', td.html()); 
                  td.addClass('update_success');
                  setTimeout(function () {
                    td.removeClass('update_success');
                  }, 1000);
                } else {
                  // ERROR
                  td.html(td.data('value'));
                  td.addClass('update_error');
                  setTimeout(function () {
                    td.removeClass('update_error');
                  }, 2000);

                  box_type = 'danger';
                  message_type = 'error';

                  $('#flash-message').empty();
                  var box = $('<div id="delete-message" class="alert alert-'+box_type+' alert-dismissible flash-'+message_type+'" role="alert"><button href="#" class="close" data-dismiss="alert" aria-label="close">&times;</button>'+data.msg+'</div>');
                  $('#flash-message').append(box);
                  $('#delete-message').delay(2000).queue(function () {
                      $(this).addClass('animated flipOutX')
                  });
                }
              }
        });
      }

    }
    
    @endcan



    @can('tools-activity-new')
    $('#new_project').on('click', function() {
      window.location.href = "{!! route('toolsFormCreate',['']) !!}/"+year[0];
    });
    @endcan

    $(document).on('click', '#legendButton', function () {
    $('#legendModal').modal();
  

  });

  });

  </script>
  @stop
