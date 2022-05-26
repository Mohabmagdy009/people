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
@stop

@section('content')

<!-- Page title -->
<div class="page-title">
  <div class="title_left">
    <h3>All Projects</h3>
  </div>
</div>
<div class="clearfix"></div>
<!-- Page title -->

<!-- Window -->
<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">

      <!-- Window title -->
      <div class="x_title">
        <h2>List</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>
      <!-- Window title -->

      <!-- Window content -->
      <div class="x_content">
            @can('tools-activity-new')
            <div class="row button_in_row">
              <div class="col-md-12">
                <button id="new_project" class="btn btn-info btn-xs" align="right"><span class="glyphicon glyphicon-plus"> New Project</span></button>
              </div>
            </div>
            @endcan
            <table id="projectTable" class="table table-striped table-hover table-bordered mytable" width="100%">
                <thead>
                    <tr>
                      <th>ID</th>
                      <th>Customer name</th>
                      <th>Project name</th>
                      <!-- <th>OTL project code</th> -->
                      <!-- <th>Meta-activity</th> -->
                      <th>CL ID</th>
                      <th>Project type</th>
                      <!-- <th>Activity type</th> -->
                      <th>Project status</th>
                      <th>Region</th>
                      <th>Country</th>
                      <!-- <th>Technology</th> -->
                      <th>Description</th>
                      <th>Estimated start date</th>
                      <th>Estimated end date</th>
                      <th>Comments</th>
                      <th>LoE onshore</th>
                      <th>LoE nearshore</th>
                      <th>LoE offshore</th>
                      <th>LoE contractor</th>
                      <th>Gold order</th>
                      <th>FPC</th>
                      <th>Revenue (€)</th>
                      <th>Win ratio (%)</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                      <!-- <th></th> -->
                      <!-- <th></th> -->
                      <!-- <th></th> -->
                      <!-- <th></th> -->
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
                    </tr>
                </tfoot>
            </table>
        </div>
      <!-- Window content -->

    </div>
  </div>
</div>
<!-- Window -->
@stop

@section('script')
    <script>
        var projectTable;

        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            projectTable = $('#projectTable').DataTable({
                scrollX: true,
                serverSide: true,
                processing: true,
                stateSave: true,
                ajax: {
                        url: "{!! route('listOfProjectsAllAjax') !!}",
                        type: "GET"
                    },
                columns: [
                    { name: 'projects.id', data: 'id', searchable: false , visible: false },
                    { name: 'customers.name', data: 'customer_name' },
                    { name: 'projects.project_name', data: 'project_name' },
                    // { name: 'projects.otl_project_code', data: 'otl_project_code' },
                    // { name: 'projects.meta_activity', data: 'meta_activity'},
                    { name: 'projects.samba_id', data: 'samba_id', searchable: true , visible: true},
                    { name: 'projects.project_type', data: 'project_type',
                      render: function (data,type, rowData) {
                        if (type === 'display') {
                          @can('projectLoe-view')
                          if (data == 'Pre-sales') {
                            if (rowData.num_of_loe >=1) {
                              return data + '<a href="{!! route('loeView','') !!}/'+rowData.id+'"><img src="{{ asset("/img/loe.png") }}" width="20" height="20" style="margin-left:10px;"></a>';
                            }
                          }
                          @endcan
                        }
                        return data;
                      }
                    },
                    // { name: 'projects.activity_type', data: 'activity_type'},
                    { name: 'projects.project_status', data: 'project_status'},
                    { name: 'projects.region', data: 'region'  , visible: false},
                    { name: 'projects.country', data: 'country'  , visible: false},
                    // { name: 'projects.technology', data: 'technology' },
                    { name: 'projects.description', data: 'description' , visible: false },
                    { name: 'projects.estimated_start_date', data: 'estimated_start_date' , visible: false },
                    { name: 'projects.estimated_end_date', data: 'estimated_end_date' , visible: false },
                    { name: 'projects.comments', data: 'comments' , visible: false },
                    { name: 'projects.LoE_onshore', data: 'LoE_onshore', visible: false },
                    { name: 'projects.LoE_nearshore', data: 'LoE_nearshore' , visible: false},
                    { name: 'projects.LoE_offshore', data: 'LoE_offshore' , visible: false },
                    { name: 'projects.LoE_contractor', data: 'LoE_contractor' , visible: false },
                    { name: 'projects.gold_order_number', data: 'gold_order_number' , visible: false },
                    { name: 'projects.product_code', data: 'product_code' , visible: false },
                    { name: 'projects.revenue', data: 'revenue', visible: false},
                    { name: 'projects.win_ratio', data: 'win_ratio', visible: false }
                    ],
                order: [[1, 'asc'],[3, 'asc'],[4, 'asc'],[6, 'asc']],
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                ],
                dom: 'Bfrtip',
                buttons: [
                  {
                    extend: "colvis",
                    className: "btn-sm",
                    columns: ':gt(0)'
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
                initComplete: function () {
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
                        }
                    });
                    // Restore state
                    var state = projectTable.state.loaded();
                    if (state) {
                        projectTable.columns().eq(0).each(function (colIdx) {
                            var colSearch = state.columns[colIdx].search;

                            if (colSearch.search) {
                                $('input', projectTable.column(colIdx).footer()).val(colSearch.search);
                            }
                        });

                        projectTable.draw();
                    }
                }
            });

            @can('tools-activity-edit')
            $('#projectTable').on('click', 'tbody td', function() {
              var table = projectTable;
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
              window.location.href = "{!! route('toolsFormUpdate',[$user_id_for_update,'','']) !!}/"+row.data().id+"/"+{{ $year }};
            });
            @endcan

            @can('tools-activity-new')
            $('#new_project').on('click', function() {
              window.location.href = "{!! route('toolsFormCreate',$year) !!}";
            });
            @endif

        } );
    </script>
@stop
