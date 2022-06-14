<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">

    @canany(['otl-upload','samba-upload','revenue-upload','customer-upload','consulting_pricing_upload'])
    <ul class="nav side-menu">
      <li><a><i class="fa fa-home"></i>DB Feed <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
          @can('otl-upload')
            <li><a href="{!!route('otluploadform')!!}">Prime</a></li>
          @endcan
          @can('samba-upload')
            <li><a href="{!!route('sambauploadform')!!}">CL</a></li>
          @endcan
          @can('samba-upload')
            <li><a href="{!!route('sambauserupload')!!}">CL user synch</a></li>
          @endcan
          @can('revenue-upload')
            <li><a href="{!!route('revenueuploadform')!!}">Revenue</a></li>
          @endcan
          @can('customer-upload')
            <li><a href="{!!route('customeruploadform')!!}">Customer</a></li>
          @endcan
          @can('consulting_pricing_upload')
          <li><a href="{!!route('ConsultingPricingUpload')!!}">Consulting Pricing</a></li>
          @endcan
        </ul>
      </li>
    </ul>
    @endcan

    
    @canany(['tools-activity-view','tools-all_projects-view','tools-usersskills','projects-lost','tools-user-summary'])
    <ul class="nav side-menu">
      <li><a href="{!!route('toolsActivities')!!}"><i class="fa fa-home"></i>Home</a>
        <!-- <ul class="nav child_menu">
          @canany(['user-view','user-edit','user-create','user-delete'])
          <li><a href="{!!route('userList')!!}">Users list</a></li>
          @endcan
          @canany(['role-view','role-edit','role-create','role-delete'])
          <li><a href="{!!route('roles.index')!!}">Roles list</a></li>
          @endcan
          @canany(['project-view','project-edit','project-create','project-delete'])
          <li><a href="{!!route('projectList')!!}">Projects list</a></li>
          @endcan
          @canany(['activity-view','activity-edit','activity-create','activity-delete'])
          <li><a href="{!!route('activityList')!!}">Activity list</a></li>
          @endcan
          @canany(['project-view','project-edit','project-create','project-delete'])
          <li><a href="{!!route('customerList')!!}">Customers list</a></li>
          @endcan
          @can('skills-addnew')
          <li><a href="{!!route('skillList')!!}">Skills list</a></li>
          @endcan
          @canany(['backup-create','backup-download','backup-delete'])
          <li><a href="{!!route('backupList')!!}">Backups list</a></li>
          @endcan
        </ul> -->
      </li>
    </ul>
    @endcan

    @canany(['user-view','role-view','project-view','activity-view','activity-edit','activity-create','activity-delete',
    'skills-addnew','backup-download'
    ])
    <ul class="nav side-menu">
      <li><a href="{!!route('userList')!!}"><i class="fa fa-user"></i>Manage Users</a>
      </li>
    </ul>

    @endcan

      @canany(['dashboard-view','cluster-view'])
      <ul class="nav side-menu">
        <li><a><i class="fa fa-bar-chart-o"></i>Dashboard<span class="fa fa-chevron-down"></span></a>
          <ul class="nav child_menu">
            @can('dashboard-view')
            <li><a href="{!!route('dashboardLoad')!!}">Users load</a></li>
            <li><a href="{!!route('dashboardLoadChart')!!}">Users load Chart</a></li>
            <li><a href="{!!route('dashboarddscisc')!!}/{{date('Y')}}">ASC vs ISC</a></li>
            @endcan
            @can('cluster-view')
            <li><a href="{!!route('clusterdashboard')!!}/{{date('Y')}}/0/all/0/0">Cluster Dashboard</a></li>
            @endcan
            @can('dashboardRevenue-view')
            <li><a href="{!!route('revenuedashboard')!!}/{{date('Y')}}/0">Revenue Dashboard</a></li>
            @endcan
            @can('dashboardOrder-view')
            <li><a href="{!!route('orderdashboard')!!}/{{date('Y')}}/0">Order Dashboard</a></li>
            @endcan
            @can('action-view')
            <li><a href="{!!route('actiondashboard')!!}">Action Dashboard</a></li>
            @endcan
          </ul>
        </li>
      </ul>
      @endcan

      @canany(['tools-activity-view','tools-all_projects-view','tools-usersskills','projects-lost','tools-user-summary'])
      <ul class="nav side-menu">
        <li><a href="{!!route('projectsAll')!!}"><i class="fa fa-wrench"></i>Manage Projects</a>
          <!-- <ul class="nav child_menu">
            @can('tools-activity-view')
            <li><a href="{!!route('toolsActivities')!!}">Activity list per project</a></li>
            @endcan
            @can('tools-all_projects-view')
            <li><a href="{!!route('projectsAll')!!}">Project list</a></li>
            @endcan
            @can('projects-lost')
            <li><a href="{!!route('projectsLost')!!}">Projects lost</a></li>
            @endcan
            @can('tools-usersskills')
            <li style="display:none;"><a href="{!!route('toolsUsersSkills')!!}">Users skills</a></li>
            @endcan
          </ul> -->
        </li>
      </ul>
      @endcan

      @canany(['tools-unassigned-view','tools-missing_info-view'])
      <ul class="nav side-menu">
        <!-- <li><a><i class="fa fa-server"></i>Maintenance<span class="fa fa-chevron-down"></span></a> -->
          <ul class="nav child_menu">
            @can('tools-unassigned-view')
            <li><a href="{!!route('projectsAssignedAndNot')!!}">Unassigned projects</a></li>
            @endcan
            @can('tools-missing_info-view')
            <li><a href="{!!route('projectsMissingInfo')!!}">Project missing info</a></li>
            @endcan
            @can('tools-missing_info-view')
            <li><a href="{!!route('projectsMissingOTL')!!}">Project missing OTL</a></li>
            @endcan
          </ul>
        </li>
      </ul>
      @endcan

  </div>
</div>
