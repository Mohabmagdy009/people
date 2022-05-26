@extends('layouts.app')

@section('style')
    <!-- CSS -->
    <!-- DataTables -->
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/gentelella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css') }}" rel="stylesheet">
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
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons/js/buttons.flash.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons/js/buttons.print.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-buttons/js/buttons.colVis.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/datatables.net-scroller/js/dataTables.scroller.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/jszip/dist/jszip.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/pdfmake/build/pdfmake.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/plugins/gentelella/vendors/pdfmake/build/vfs_fonts.js') }}" type="text/javascript"></script>
    <!-- Bootbox -->
    <script src="{{ asset('/plugins/bootbox/bootbox.min.js') }}"></script>
    <!-- Switchery -->
    <script src="{{ asset('/plugins/gentelella/vendors/switchery/dist/switchery.min.js') }}" type="text/javascript"></script>
@stop

@section('content')

<!-- Page title -->
<div class="page-title">
  <div class="title_left">
    <h3>User</h3>
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
        <div class="col-sm-1"><h2>List</small></h2></div>
        <div style="display: none;"class="col-sm-2">Exclude contractors  <input name="exclude_contractors" type="checkbox" id="exclude_contractors" class="form-group js-switch-small" /></div>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
      </div>
    <!--   <div class="container">
        <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card" style="padding: 12px;">
          <div class="card-header">This Field Uses to Update Users and cretae new user by uploading ExcelSheet (Ex. CaC Manager, Cac User) <br> Note: Upload Managers before Users</div>
          <div class="card-body">
            @if(session('status'))
              <div class="alert alert-sucess" role="alert">
                {{ session('ststus') }}
              </div>
            @endif
            <form action="import" method="post" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <input type="file" name="file" style="display: inline-block;margin: 12px;">
                <button type="submit" class="btn btn-primary" style = "display: inline-block;">Import</button>
              </div>
            </form>
          </div>
          
        </div>
        
      </div>
      
    </div>
      </div> -->
      <!-- Window title -->

      <!-- Window content -->
      <div class="x_content">
        <table id="userTable" class="table table-striped table-hover table-bordered mytable" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Manager name</th>
                    <th>Email</th>
                    <th>FT ID</th>
                    <th>PIMS ID</th>
                    <th>Is Manager</th>
                    <th>Activity</th>
                    <th>Date started</th>
                    <th>Date ended</th>
                    <th>Region</th>
                    <th>Country</th>
                    <th>Practice</th>
                    <th>Management Code</th>
                    <th>Job role</th>
                    <th>Type</th>
                    <th>From OTL</th>
                    <th>Roles</th>
                    <th class="last_column">
                      @can('user-create')
                        <a href="{{ route('userFormCreate') }}" class="btn btn-info btn-xs" align="right"><span class="glyphicon glyphicon-plus"> New</span></a>
                      @endcan
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Manager name</th>
                    <th>Email</th>
                    <th>FT ID</th>
                    <th>PIMS ID</th>
                    <th>Is Manager</th>
                    <th>Activity</th>
                    <th>Date started</th>
                    <th>Date ended</th>
                    <th>Region</th>
                    <th>Country</th>
                    <th>Domain</th>
                    <th>Management Code</th>
                    <th>Job role</th>
                    <th>Type</th>
                    <th>From OTL</th>
                    <th></th>
                    <th class="last_column"></th>
                </tr>
            </tfoot>
        </table>
        <!-- Modal Reset Password -->
        <div class="modal fade" id="modal_reset_password" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" style="display:table;">
            <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title" id="modal_reset_password_title"></h4>
              </div>
              <!-- Modal Header -->
                
              <!-- Modal Body -->
              <div class="modal-body">
                <div class="row">
                  Please copy paste this text and send it via email - <span id="modal_reset_password_email"></span> - to the user:
                </div>
                <div class="row" style="margin-top: 10px;margin-left: 15px;">
                  <p>
                    Please go to the following url: {{ url('') }}
                  </p>
                  <p>
                    Connect with the following credentials:
                    <ul>
                      <li>username: <span id="modal_reset_password_username"></span></li>
                      <li>password: <span id="modal_reset_password_password"></li>
                    </ul>
                  </p>
                  <p>
                    At your first login, please click on your name top right then change password.
                  </p>
                </div>
                
              </div>
                
              <!-- Modal Footer -->
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" id="modal_reset_password_button" class="btn btn-warning">Reset Password</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal Reset Password -->
      </div>
      <!-- Window content -->

    </div>
  </div>
</div>
<!-- Window -->

@stop

@section('script')
<script>
  var userTable;
  var record_id;
  var exclude_contractors;

  $(document).ready(function() {

    // switchery
    var small = document.querySelector('.js-switch-small');
    var switchery = new Switchery(small, { size: 'small' });

    // exclude contractors
    // Init
    if ($('#exclude_contractors').is(':checked')) {
      exclude_contractors = 1;
    } else {
      exclude_contractors = 0;
    }

    // Change
    $('#exclude_contractors').on('change', function() {
      if ($(this).is(':checked')) {
        exclude_contractors = 1;
      } else {
        exclude_contractors = 0;
      }
      userTable.ajax.url("{!! route('listOfUsersAjax','') !!}/"+exclude_contractors).load();
    });


  
    // Generate a password string
    function randString(availableSet,dataSize){
      var dataSet = availableSet.split(',');  
      var possible = '';
      if($.inArray('a-z', dataSet) >= 0){
        possible += 'abcdefghijklmnopqrstuvwxyz';
      }
      if($.inArray('A-Z', dataSet) >= 0){
        possible += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      }
      if($.inArray('0-9', dataSet) >= 0){
        possible += '0123456789';
      }
      if($.inArray('#', dataSet) >= 0){
        possible += '![]{}()%&*$#^<>~@|';
      }
      var text = '';
      for(var i=0; i < dataSize; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
      }
      return text;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    userTable = $('#userTable').DataTable({
        serverSide: true,
        processing: true,
        scrollX: true,
        stateSave: true,
        ajax: {
                url: "{!! route('listOfUsersAjax','') !!}/"+exclude_contractors,
                type: "GET",
                dataSrc: function ( json ) {
                  //console.log(json);;
                    for ( var i=0, ien=json.data.length ; i<ien ; i++ ) {
                        if (json.data[i].is_manager == 1){
                            json.data[i].is_manager = 'Yes';
                        }
                        else {
                            json.data[i].is_manager = 'No';
                        }
                    }
                    return json.data;
                }
            },
        columns: [
            { name: 'users.id', data: 'id' , searchable: false , visible: false },
            { name: 'users.name', data: 'name' , searchable: true , visible: true },
            { name: 'u2.name', data: 'manager_name' , searchable: true , visible: true  },
            { name: 'users.email', data: 'email' , searchable: true , visible: false },
            { name: 'users.ftid', data: 'ftid' , searchable: true , visible: false },
            { name: 'users.pimsid', data: 'pimsid' , searchable: true , visible: false },
            { name: 'users.is_manager', data: 'is_manager' , searchable: true , visible: true },
            { name: 'users.activity_status', data: 'activity_status' , searchable: true , visible: true },
            { name: 'users.date_started', data: 'date_started' , searchable: true , visible: true },
            { name: 'users.date_ended', data: 'date_ended' , searchable: true , visible: true },
            { name: 'users.region', data: 'region' , searchable: true , visible: true },
            { name: 'users.country', data: 'country' , searchable: true , visible: true },
            { name: 'users.domain', data: 'domain' , searchable: true , visible: true },
            { name: 'users.management_code', data: 'management_code' , searchable: true , visible: false },
            { name: 'users.job_role', data: 'job_role' , searchable: true , visible: true },
            { name: 'users.employee_type', data: 'employee_type' , searchable: true , visible: true },
            { name: 'users.from_otl', data: 'from_otl' , searchable: false , visible: false },
            { name: 'roles', data: 'roles' , searchable: false , visible: true, render: function (data, type) {
                return $('<div/>').html(data).text();
              } 
            },
            {
                name: 'actions',
                data: null,
                sortable: false,
                searchable: false,
                render: function (data) {
                    var actions = '';
                    actions += '<div class="btn-group btn-group-xs">';
                    if ({{ Auth::user()->can('user-edit') ? 'true' : 'false' }}){
                      actions += '<button data-id="'+data.id+'" data-toggle="tooltip" data-placement="top" title="Reset password" class="buttonResetPassword btn btn-warning"><span class="glyphicon glyphicon-lock"></span></button>';
                    };
                    if ({{ Auth::user()->can('user-edit') ? 'true' : 'false' }}){
                      actions += '<button id="'+data.id+'" class="buttonUpdate btn btn-primary"><span class="glyphicon glyphicon-pencil"></span></button>';
                    };
                    if ({{ Auth::user()->can('user-delete') ? 'true' : 'false' }}){
                      actions += '<button id="'+data.id+'" class="buttonDelete btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>';
                    };
                    actions += '</div>';
                    return actions;
                },
                width: '70px'
            }
            ],
        order: [[1, 'asc']],
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        dom: 'Bfrtip',
        buttons: [
          {
            extend: "colvis",
            className: "btn-sm",
            columns: [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,17 ]
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
            var state = userTable.state.loaded();
            if (state) {
                userTable.columns().eq(0).each(function (colIdx) {
                    var colSearch = state.columns[colIdx].search;

                    if (colSearch.search) {
                        $('input', userTable.column(colIdx).footer()).val(colSearch.search);
                    }
                });

                userTable.draw();
            }
        }
    });



    $(document).on('click', '.buttonUpdate', function () {
        window.location.href = "{!! route('userFormUpdate','') !!}/"+this.id;
    } );

    $(document).on('click', '.buttonResetPassword', function () {
      var table = userTable;
      var tr = $(this).closest('tr');
      var row = table.row(tr);

      var password = 'Welcome1';

      $('#modal_reset_password_title').text('Reset password for user '+row.data().name);
      $('#modal_reset_password_email').text(row.data().email);
      $('#modal_reset_password_username').text(row.data().email);
      $('#modal_reset_password_password').text(password);
      $('#modal_reset_password_button').attr('data-id',row.data().id);
      $('#modal_reset_password_button').attr('data-pwd',password);
      $('#modal_reset_password').modal("show");
    } );

    $(document).on('click', '#modal_reset_password_button', function () {
      var id = $('#modal_reset_password_button').attr('data-id');
      var password = $('#modal_reset_password_button').attr('data-pwd');

      $.ajax({
          type: 'post',
          url: "{!! route('passwordUpdateAjax','') !!}/"+id,
          data: {'password':password},
          dataType: 'json',
          success: function(data) {
              console.log(data);
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
              userTable.ajax.reload();
          }
      });

      $('#modal_reset_password').modal("hide");

    } );

    $(document).on('click', '.buttonDelete', function () {
        record_id = this.id;
        bootbox.confirm("Are you sure want to delete this record?", function(result) {
            if (result){
                $.ajax({
                    type: 'get',
                    url: "{!! route('userDelete','') !!}/"+record_id,
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
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
                        userTable.ajax.reload();
                    }
                });
            }
        });
    } );

  });
</script>
@stop
