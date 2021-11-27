<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Help section
Route::get('help', ['as' => 'help', 'uses' => 'GeneralController@help']);

//Offer section
Route::get('offer/isco', ['as' => 'offer_isco', 'uses' => 'OfferController@isco']);

//Auth
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

// All routes in this function will be protected by user needed to be logged in.
Route::group(['middleware' => ['auth', 'general','last_login']], function () {
    Route::get('/home', ['uses' => 'HomeController@index', 'as' => 'home']);
    Route::get('/', ['uses' => 'HomeController@index']);

    // Backup routes
    Route::get('backup', ['uses' => 'BackupController@index', 'as' => 'backupList', 'middleware' => ['permission:backup-create|backup-download|backup-delete']]);
    Route::get('backup/create', ['uses' => 'BackupController@create', 'as' => 'backupCreate', 'middleware' => ['permission:backup-create']]);
    Route::get('backup/download/{file_name}', ['uses' => 'BackupController@download', 'as' => 'backupDownload', 'middleware' => ['permission:backup-download']]);
    Route::get('backup/delete/{file_name}', ['uses' => 'BackupController@delete', 'as' => 'backupDelete', 'middleware' => ['permission:backup-delete']]);

    //OTL
    Route::get('otlupload', ['uses' => 'OtlUploadController@getForm', 'as' => 'otluploadform', 'middleware' => ['permission:otl-upload']]);
    Route::get('otlupload_help', ['uses' => 'OtlUploadController@help', 'as' => 'otluploadhelp', 'middleware' => ['permission:otl-upload']]);
    Route::post('otlupload', ['uses' => 'OtlUploadController@postForm', 'middleware' => ['permission:otl-upload']]);

    //Revenue
    Route::get('revenueupload', ['uses' => 'RevenueUploadController@getForm', 'as' => 'revenueuploadform', 'middleware' => ['permission:revenue-upload']]);
    Route::post('revenueupload', ['uses' => 'RevenueUploadController@postForm', 'middleware' => ['permission:revenue-upload']]);
    Route::post('revenueUploadChangeName', ['uses' => 'CustomerOtherNameController@addNameAjax', 'as' => 'revenueUploadChangeName', 'middleware' => ['permission:revenue-upload']]);

    //Samba
    Route::get('sambaupload', ['uses' => 'SambaUploadController@getForm', 'as' => 'sambauploadform', 'middleware' => ['permission:samba-upload']]);
    Route::post('sambaupload', ['uses' => 'SambaUploadController@postForm', 'as' => 'sambauploadPOST', 'middleware' => ['permission:samba-upload']]);
    Route::post('sambauploadcreate', ['uses' => 'SambaUploadController@postFormCreate', 'as' => 'sambauploadcreatePOST', 'middleware' => ['permission:samba-upload']]);
    Route::post('sambaUploadUpdateProject/{project}', ['uses' => 'SambaUploadController@sambaUploadUpdateProject', 'as' => 'sambaUploadUpdateProject', 'middleware' => ['permission:samba-upload']]);
    Route::post('sambaUploadCreateProject', ['uses' => 'SambaUploadController@sambaUploadCreateProject', 'as' => 'sambaUploadCreateProject', 'middleware' => ['permission:samba-upload']]);

    // Samba user checks
    Route::get('sambauserupload', ['uses' => 'SambaUserUploadController@getForm', 'as' => 'sambauserupload', 'middleware' => ['permission:samba-upload']]);
    Route::post('sambauserupload', ['uses' => 'SambaUserUploadController@postForm', 'as' => 'sambauseruploadPOST', 'middleware' => ['permission:samba-upload']]);
    Route::post('sambaUploadCreateUser', ['uses' => 'SambaUserUploadController@sambaUploadCreateUser', 'as' => 'sambaUploadCreateUser', 'middleware' => ['permission:samba-upload']]);

    //Customer upload
    Route::get('customerupload', ['uses' => 'CustomerUploadController@getForm', 'as' => 'customeruploadform', 'middleware' => ['permission:customer-upload']]);
    Route::post('customerupload', ['uses' => 'CustomerUploadController@postForm', 'middleware' => ['permission:customer-upload']]);

    //User
    //  Main user list
    Route::get('userList', ['uses' => 'UserController@getList', 'as' => 'userList', 'middleware' => ['permission:user-view|user-create|user-edit|user-delete']]);

    //  Create new user
    Route::get('userFormCreate', ['uses' => 'UserController@getFormCreate', 'as' => 'userFormCreate', 'middleware' => ['permission:user-create']]);
    Route::post('userFormCreate', ['uses' => 'UserController@postFormCreate', 'middleware' => ['permission:user-create']]);
    //  Update user
    Route::get('userFormUpdate/{user}', ['uses' => 'UserController@getFormUpdate', 'as' => 'userFormUpdate', 'middleware' => ['permission:user-edit']]);
    Route::post('userFormUpdate/{user}', ['uses' => 'UserController@postFormUpdate', 'middleware' => ['permission:user-edit']]);
    //  Delete user
    Route::get('userDelete/{n}', ['uses' => 'UserController@delete', 'as' => 'userDelete', 'middleware' => ['permission:user-delete']]);
    //  user profile
    Route::get('profile/{n}', ['uses' => 'UserController@profile', 'as' => 'profile']);
    Route::get('updatePassword/{user}', ['uses' => 'UserController@updatePasswordGet', 'as' => 'updatePasswordGet']);
    Route::post('updatePassword/{user}', ['uses' => 'UserController@updatePasswordStore', 'as' => 'updatePasswordStore']);
    Route::post('passwordUpdateAjax/{user}', ['uses' => 'UserController@passwordUpdateAjax', 'as' => 'passwordUpdateAjax']);
    Route::post('optionsUpdate/{id}', ['uses' => 'UserController@optionsUpdate', 'as' => 'optionsUpdate']);
    //  AJAX
    Route::get('listOfUsersAjax/{exclude_contractors?}', ['uses' => 'UserController@listOfUsers', 'as' => 'listOfUsersAjax', 'middleware' => ['permission:user-view|user-create|user-edit|user-delete']]);

    //ProfileToolsController
    Route::get('ajax_git_pull', ['uses' => 'ProfileToolsController@ajax_git_pull', 'as' => 'ajax_git_pull']);
    Route::post('db_cleanup', ['uses' => 'ProfileToolsController@db_cleanup', 'as' => 'db_cleanup']);
    Route::get('factory_reset', ['uses' => 'ProfileToolsController@factory_reset', 'as' => 'factory_reset']);
    Route::get('ajax_env_app_debug/{n}', ['uses' => 'ProfileToolsController@ajax_env_app_debug', 'as' => 'ajax_env_app_debug']);

    // Roles
    Route::resource('roles','RoleController');

    //Project
    //  Main project list
    Route::get('projectList', ['uses' => 'ProjectController@getList', 'as' => 'projectList', 'middleware' => ['permission:project-view|project-create|project-edit|project-delete']]);
    //  project information
    Route::get('project/{n}', ['uses' => 'ProjectController@show', 'as' => 'project', 'middleware' => ['permission:project-view']]);
    //  Create new project
    Route::get('projectFormCreate', ['uses' => 'ProjectController@getFormCreate', 'as' => 'projectFormCreate', 'middleware' => ['permission:project-create']]);
    Route::post('projectFormCreate', ['uses' => 'ProjectController@postFormCreate', 'middleware' => ['permission:project-create']]);
    //  Update project
    Route::get('projectFormUpdate/{n}', ['uses' => 'ProjectController@getFormUpdate', 'as' => 'projectFormUpdate', 'middleware' => ['permission:project-edit']]);
    Route::post('projectFormUpdate/{n}', ['uses' => 'ProjectController@postFormUpdate', 'middleware' => ['permission:project-edit']]);
    //  Delete project
    Route::get('projectDelete/{n}', ['uses' => 'ProjectController@delete', 'as' => 'projectDelete', 'middleware' => ['permission:project-delete']]);
    //  AJAX
    Route::post('listOfProjectsAjax', ['uses' => 'ProjectController@listOfProjects', 'as' => 'listOfProjectsAjax', 'middleware' => ['permission:tools-all_projects-view|tools-unassigned-view|tools-activity-edit|project-view|project-create|project-edit|project-delete']]);
    Route::get('listOfProjectsNotUsedInPrimeAjax/{user_name}/{year}', ['uses' => 'ProjectController@listOfProjectsNotUsedInPrime', 'as' => 'listOfProjectsNotUsedInPrimeAjax', 'middleware' => ['permission:tools-all_projects-view']]);
    Route::get('listOfProjectsNotUsedInCLAjax/{year}/{user_id?}', ['uses' => 'ProjectController@listOfProjectsNotUsedInCL', 'as' => 'listOfProjectsNotUsedInCLAjax', 'middleware' => ['permission:tools-all_projects-view']]);
    Route::post('createProjectFromPrimeUpload', ['uses' => 'ProjectController@createProjectFromPrimeUpload', 'as' => 'createProjectFromPrimeUpload', 'middleware' => ['permission:tools-all_projects-edit']]);
    Route::post('editProjectFromPrimeUpload', ['uses' => 'ProjectController@editProjectFromPrimeUpload', 'as' => 'editProjectFromPrimeUpload', 'middleware' => ['permission:tools-all_projects-edit']]);

    //Customer
    //  Main customer list
    Route::get('customerList', ['uses' => 'CustomerController@getList', 'as' => 'customerList', 'middleware' => ['permission:project-view|project-create|project-edit|project-delete']]);
    //  customer information
    Route::get('customer/{n}', ['uses' => 'CustomerController@show', 'as' => 'customer', 'middleware' => ['permission:project-view']]);
    //  Create new customer
    Route::get('customerFormCreate', ['uses' => 'CustomerController@getFormCreate', 'as' => 'customerFormCreate', 'middleware' => ['permission:project-create']]);
    Route::post('customerFormCreate', ['uses' => 'CustomerController@postFormCreate', 'middleware' => ['permission:project-create']]);
    //  Update customer
    Route::get('customerFormUpdate/{n}', ['uses' => 'CustomerController@getFormUpdate', 'as' => 'customerFormUpdate', 'middleware' => ['permission:project-edit']]);
    Route::post('customerFormUpdate/{n}', ['uses' => 'CustomerController@postFormUpdate', 'middleware' => ['permission:project-edit']]);
    //  Delete customer
    Route::get('customerDelete/{n}', ['uses' => 'CustomerController@delete', 'as' => 'customerDelete', 'middleware' => ['permission:project-delete']]);
    //  AJAX
    Route::post('listOfCustomersAjax', ['uses' => 'CustomerController@listOfCustomers', 'as' => 'listOfCustomersAjax', 'middleware' => ['permission:project-view|project-create|project-edit|project-delete']]);

    //Consulting Pricing
    //  Main pricing list
    Route::get('consulting_pricing_upload', ['uses' => 'ConsultingPricingController@upload', 'as' => 'ConsultingPricingUpload', 'middleware' => ['permission:consulting_pricing_upload']]);
    Route::post('consulting_pricing_uploadFile', ['uses' => 'ConsultingPricingController@uploadFile', 'middleware' => ['permission:consulting_pricing_upload']]);

    //Activity
    //  Main activity list
    Route::get('activityList', ['uses' => 'ActivityController@getList', 'as' => 'activityList', 'middleware' => ['permission:activity-view|activity-create|activity-edit|activity-delete']]);
    //  activity information
    Route::get('activity/{n}', ['uses' => 'ActivityController@show', 'as' => 'activity', 'middleware' => ['permission:activity-view']]);
    //  Create new activity
    Route::get('activityFormCreate', ['uses' => 'ActivityController@getFormCreate', 'as' => 'activityFormCreate', 'middleware' => ['permission:activity-create']]);
    Route::post('activityFormCreate', ['uses' => 'ActivityController@postFormCreate', 'middleware' => ['permission:activity-create']]);
    //  Update activity
    Route::get('activityFormUpdate/{n}', ['uses' => 'ActivityController@getFormUpdate', 'as' => 'activityFormUpdate', 'middleware' => ['permission:activity-edit']]);
    Route::post('activityFormUpdate/{n}', ['uses' => 'ActivityController@postFormUpdate', 'middleware' => ['permission:activity-edit']]);
    //  Delete activity
    Route::get('activityDelete/{n}', ['uses' => 'ActivityController@delete', 'as' => 'activityDelete', 'middleware' => ['permission:activity-delete']]);
    //  AJAX
    Route::get('listOfActivitiesAjax', ['uses' => 'ActivityController@listOfActivities', 'as' => 'listOfActivitiesAjax', 'middleware' => ['permission:activity-view|activity-create|activity-edit|activity-delete']]);
    

    //Comment
    Route::get('comment/{id}', ['uses' => 'CommentController@show', 'as' => 'comment_show', 'middleware' => ['permission:tools-projects-comments']]);
    Route::get('comments/{project_id}', ['uses' => 'CommentController@getList', 'as' => 'comment_list', 'middleware' => ['permission:tools-projects-comments']]);
    Route::post('comments', ['uses' => 'CommentController@store', 'as' => 'commentInsert', 'middleware' => ['permission:comment-create']]);
    Route::patch('comments/{id}', ['uses' => 'CommentController@update', 'as' => 'comment_edit', 'middleware' => ['permission:comment-edit']]);
    Route::delete('comment/{id}', ['uses' => 'CommentController@destroy', 'as' => 'comment_delete', 'middleware' => ['permission:comment-delete']]);
    Route::post('commentList', ['uses' => 'CommentController@commentList', 'as' => 'commentList', 'middleware' => ['permission:tools-projects-comments']]);
    

    //Tools
    Route::get('toolsActivities', ['uses' => 'ToolsController@activities', 'as' => 'toolsActivities', 'middleware' => ['permission:tools-activity-view']]);
    Route::get('toolsUserSummary', ['uses' => 'ToolsController@userSummary', 'as' => 'toolsUserSummary', 'middleware' => ['permission:tools-user-summary']]);
    Route::post('actionList', ['uses' => 'ActionController@actionList', 'as' => 'actionList', 'middleware' => ['permission:tools-user-summary']]);
    Route::post('actionInsertUpdate', ['uses' => 'ActionController@actionInsertUpdate', 'as' => 'actionInsertUpdate', 'middleware' => ['permission:action-create']]);
    Route::post('actionDelete', ['uses' => 'ActionController@actionDelete', 'as' => 'actionDelete', 'middleware' => ['permission:action-delete']]);
    Route::post('userSummaryProjects', ['uses' => 'ToolsController@userSummaryProjects', 'as' => 'userSummaryProjects', 'middleware' => ['permission:tools-activity-view']]);
    Route::get('toolsProjectsAll', ['uses' => 'ToolsController@projectsAll', 'as' => 'projectsAll', 'middleware' => ['permission:tools-all_projects-view']]);
    Route::get('toolsProjectsLost', ['uses' => 'ToolsController@projectsLost', 'as' => 'projectsLost', 'middleware' => ['permission:projects-lost']]);
    Route::get('toolsProjectsAssignedAndNot', ['uses' => 'ToolsController@projectsAssignedAndNot', 'as' => 'projectsAssignedAndNot', 'middleware' => ['permission:tools-unassigned-view']]);
    Route::get('toolsProjectsMissingInfo', ['uses' => 'ToolsController@projectsMissingInfo', 'as' => 'projectsMissingInfo', 'middleware' => ['permission:tools-missing_info-view']]);
    Route::get('toolsProjectsMissingOTL', ['uses' => 'ToolsController@projectsMissingOTL', 'as' => 'projectsMissingOTL', 'middleware' => ['permission:tools-missing_info-view']]);
    Route::get('actionListAjax/{project_id?}', ['uses' => 'ActionController@actionListAjax', 'as' => 'actionListAjax', 'middleware' => ['permission:action-view']]);
    Route::get('projectActionDelete/{action_id}', ['uses' => 'ActionController@projectActionDelete', 'as' => 'projectActionDelete', 'middleware' => ['permission:action-delete']]);
    Route::post('ActionAddAjax', ['uses' => 'ActionController@store', 'as' => 'ActionAddAjax', 'middleware' => ['permission:action-create']]);
    Route::patch('ActionUpdateAjax/{id}', ['uses' => 'ActionController@update', 'as' => 'ActionUpdateAjax', 'middleware' => ['permission:action-edit']]);

    //  Create new activity
    Route::get('toolsFormCreate/{y}/{tab?}', ['uses' => 'ToolsController@getFormCreate', 'as' => 'toolsFormCreate', 'middleware' => ['permission:tools-activity-new']]);
    Route::post('toolsFormCreate', ['uses' => 'ToolsController@postFormCreate', 'middleware' => ['permission:tools-activity-new']]);
    //  Update activity
    Route::get('x/{u}/{p}/{y}/{tab?}', ['uses' => 'ToolsController@getFormUpdate', 'as' => 'toolsFormUpdate', 'middleware' => ['permission:tools-activity-edit']]);
    Route::post('toolsFormUpdate', ['uses' => 'ToolsController@postFormUpdate', 'middleware' => ['permission:tools-activity-edit']]);
    //  Transfer user
    Route::get('toolsFormTransfer/{user_id}/{project_id}', ['uses' => 'ToolsController@getFormTransfer', 'as' => 'toolsFormTransfer', 'middleware' => ['permission:tools-user_assigned-transfer']]);
    Route::get('toolsFormTransferAction/{user_id}/{old_project_id}/{new_project_id}', ['uses' => 'ToolsController@getFormTransferAction', 'as' => 'toolsFormTransferAction', 'middleware' => ['permission:tools-user_assigned-transfer']]);
    // Users skills
    Route::get('toolsUsersSkills', ['uses' => 'ToolsController@userskillslist', 'as' => 'toolsUsersSkills', 'middleware' => ['permission:tools-usersskills']]);
    Route::get('userskillFormCreate/{id?}', ['uses' => 'ToolsController@getuserskillFormCreate', 'as' => 'userskillFormCreate', 'middleware' => ['permission:tools-usersskills']]);
    Route::post('userskillFormCreate', ['uses' => 'ToolsController@postuserskillFormCreate', 'middleware' => ['permission:tools-usersskills']]);
    Route::get('userskillFormUpdate/{id}', ['uses' => 'ToolsController@getuserskillFormUpdate', 'as' => 'userskillFormUpdate', 'middleware' => ['permission:tools-usersskills']]);
    Route::post('userskillFormUpdate/{id}', ['uses' => 'ToolsController@postuserskillFormUpdate', 'middleware' => ['permission:tools-usersskills']]);
    //  AJAX
    Route::get('ProjectsRevenueAjax/{id}', ['uses' => 'ProjectController@listOfProjectsRevenue', 'as' => 'listOfProjectsRevenueAjax', 'middleware' => ['permission:projectRevenue-create']]);
    Route::post('ProjectsRevenueAddAjax', ['uses' => 'ProjectController@addRevenue', 'as' => 'ProjectsRevenueAddAjax', 'middleware' => ['permission:projectRevenue-create']]);
    Route::patch('ProjectsRevenueUpdateAjax/{id}', ['uses' => 'ProjectController@updateRevenue', 'as' => 'ProjectsRevenueUpdateAjax', 'middleware' => ['permission:projectRevenue-edit']]);
    Route::get('projectRevenueDelete/{n}', ['uses' => 'ProjectController@deleteRevenue', 'as' => 'projectRevenueDelete', 'middleware' => ['permission:projectRevenue-delete']]);
    Route::post('listOfActivitiesPerUserAjax', ['uses' => 'ActivityController@listOfActivitiesPerUser', 'as' => 'listOfActivitiesPerUserAjax', 'middleware' => ['permission:tools-activity-view']]);
    Route::get('listOfProjectsMissingInfoAjax', ['uses' => 'ProjectController@listOfProjectsMissingInfo', 'as' => 'listOfProjectsMissingInfoAjax', 'middleware' => ['permission:tools-missing_info-view']]);
    Route::get('listOfProjectsMissingOTLAjax', ['uses' => 'ProjectController@listOfProjectsMissingOTL', 'as' => 'listOfProjectsMissingOTLAjax', 'middleware' => ['permission:tools-missing_info-view']]);
    Route::get('listOfProjectsLostAjax', ['uses' => 'ProjectController@listOfProjectsLost', 'as' => 'listOfProjectsLostAjax', 'middleware' => ['permission:tools-all_projects-view']]);
    Route::get('listOfProjectsAll', ['uses' => 'ProjectController@listOfProjectsAll', 'as' => 'listOfProjectsAllAjax', 'middleware' => ['permission:tools-all_projects-view']]);
    Route::post('listOfSkills/{cert}', ['uses' => 'ToolsController@listOfSkills', 'as' => 'listOfSkills', 'middleware' => ['permission:tools-usersskills']]);
    Route::post('listOfUsersSkills/{cert}', ['uses' => 'ToolsController@listOfUsersSkills', 'as' => 'listOfUsersSkills', 'middleware' => ['permission:tools-usersskills']]);
    Route::get('userskillDelete/{id}', ['uses' => 'ToolsController@userSkillDelete', 'as' => 'userskillDelete', 'middleware' => ['permission:tools-usersskills']]);
    Route::post('updateActivityAjax', ['uses' => 'ActivityController@updateActivityAjax', 'as' => 'updateActivityAjax', 'middleware' => ['permission:tools-activity-new|tools-activity-edit|tools-activity-all-edit']]);

    //Dashboards
    Route::get('dashboardLoad', ['uses' => 'DashboardController@load', 'as' => 'dashboardLoad', 'middleware' => ['permission:dashboard-view']]);
    Route::get('dashboardLoadChart', ['uses' => 'DashboardController@load_chart', 'as' => 'dashboardLoadChart', 'middleware' => ['permission:dashboard-view']]);
    Route::get('clusterdashboard/{year?}/{customer_id?}/{domain_selected?}/{manager_id?}/{user_id?}', ['uses' => 'DashboardController@clusterboard', 'as' => 'clusterdashboard', 'middleware' => ['permission:cluster-view']]);
    Route::get('dashboarddscisc/{year?}', ['uses' => 'DashboardController@dscisc', 'as' => 'dashboarddscisc', 'middleware' => ['permission:dashboard-view']]);
    Route::get('revenuedashboard/{year?}/{user_id?}', ['uses' => 'DashboardController@revenue', 'as' => 'revenuedashboard', 'middleware' => ['permission:dashboardRevenue-view']]);
    Route::get('orderdashboard/{year?}/{user_id?}', ['uses' => 'DashboardController@order', 'as' => 'orderdashboard', 'middleware' => ['permission:dashboardOrder-view']]);
    
    Route::get('actiondashboard/{user_name?}', ['uses' => 'DashboardController@action', 'as' => 'actiondashboard', 'middleware' => ['permission:action-view']]);
    //  AJAX
    Route::post('listOfLoadPerUserAjax', ['uses' => 'ActivityController@listOfLoadPerUserAjax', 'as' => 'listOfLoadPerUserAjax', 'middleware' => ['permission:dashboard-view']]);
    Route::post('listOfLoadPerUserChartAjax', ['uses' => 'ActivityController@listOfLoadPerUserChartAjax', 'as' => 'listOfLoadPerUserChartAjax', 'middleware' => ['permission:dashboard-view']]);
    // Skills
    //  Main skill list
    Route::get('skillList', ['uses' => 'SkillController@getList', 'as' => 'skillList', 'middleware' => ['permission:user-view|user-create|user-edit|user-delete']]);
    //  skill information
    Route::get('skill/{n}', ['uses' => 'SkillController@show', 'as' => 'skill', 'middleware' => ['permission:user-view']]);
    //  Create new skill
    Route::get('skillFormCreate', ['uses' => 'SkillController@getFormCreate', 'as' => 'skillFormCreate', 'middleware' => ['permission:user-create']]);
    Route::post('skillFormCreate', ['uses' => 'SkillController@postFormCreate', 'middleware' => ['permission:user-create']]);
    //  Update skill
    Route::get('skillFormUpdate/{n}', ['uses' => 'SkillController@getFormUpdate', 'as' => 'skillFormUpdate', 'middleware' => ['permission:user-edit']]);
    Route::post('skillFormUpdate/{n}', ['uses' => 'SkillController@postFormUpdate', 'middleware' => ['permission:user-edit']]);
    //  Delete skill
    Route::get('skillDelete/{n}', ['uses' => 'SkillController@delete', 'as' => 'skillDelete', 'middleware' => ['permission:user-delete']]);
    //  AJAX
    Route::post('listOfSkillsAjax', ['uses' => 'SkillController@listOfSkills', 'as' => 'listOfSkillsAjax', 'middleware' => ['permission:user-view|user-create|user-edit|user-delete']]);
    Route::get('test', ['uses' => 'ActivityController@test', 'middleware' => ['permission:user-view']]);

    //region LOE
    Route::get('loeHistory/{id}', ['uses' => 'LoeController@loeHistory', 'as' => 'loeHistory', 'middleware' => ['permission:projectLoe-view']]);
    Route::get('loe/init/{id}', ['uses' => 'LoeController@init', 'as' => 'loeInit', 'middleware' => ['permission:projectLoe-create']]);
    Route::get('loe/delete/{id}', ['uses' => 'LoeController@delete', 'as' => 'loeDelete', 'middleware' => ['permission:projectLoe-delete']]);
    Route::get('loe/create/{id}', ['uses' => 'LoeController@create', 'as' => 'loeCreate', 'middleware' => ['permission:projectLoe-create']]);
    Route::get('loe/duplicate/{id}', ['uses' => 'LoeController@duplicate', 'as' => 'loeDuplicate', 'middleware' => ['permission:projectLoe-create']]);
    //Various AJAX edit
    Route::post('loe/edit_general', ['uses' => 'LoeController@edit_general', 'as' => 'loeEditGeneral', 'middleware' => ['permission:projectLoe-edit']]);
    Route::post('loe/edit_consulting', ['uses' => 'LoeController@edit_consulting', 'as' => 'loeEditConsulting', 'middleware' => ['permission:projectLoe-edit']]);
    Route::post('loe/edit_site', ['uses' => 'LoeController@edit_site', 'as' => 'loeEditSite', 'middleware' => ['permission:projectLoe-edit']]);
    Route::post('loe/edit_row_order', ['uses' => 'LoeController@edit_row_order', 'as' => 'loeEditRowOrder', 'middleware' => ['permission:projectLoe-edit']]);
    Route::post('loe/cons_set_default', ['uses' => 'LoeController@cons_set_default', 'as' => 'loeConsSetDefault', 'middleware' => ['permission:projectLoe-edit']]);
    Route::post('loe/append_template', ['uses' => 'LoeController@append_template', 'as' => 'loeAppendTemplate', 'middleware' => ['permission:projectLoe-edit']]);

    Route::get('loe/site_delete/{id}', ['uses' => 'LoeController@site_delete', 'as' => 'loeSiteDelete', 'middleware' => ['permission:projectLoe-delete']]);
    Route::post('loe/site_create/{id}', ['uses' => 'LoeController@site_create', 'as' => 'loeSiteCreate', 'middleware' => ['permission:projectLoe-create']]);
    Route::patch('loe/site_edit/{id}', ['uses' => 'LoeController@site_edit', 'as' => 'loeSiteEdit', 'middleware' => ['permission:projectLoe-edit']]);
    Route::get('loe/cons_delete/{id}', ['uses' => 'LoeController@cons_delete', 'as' => 'loeConsDelete', 'middleware' => ['permission:projectLoe-delete']]);
    Route::post('loe/cons_create/{id}', ['uses' => 'LoeController@cons_create', 'as' => 'loeConsCreate', 'middleware' => ['permission:projectLoe-create']]);
    Route::patch('loe/cons_edit/{id}', ['uses' => 'LoeController@cons_edit', 'as' => 'loeConsEdit', 'middleware' => ['permission:projectLoe-edit']]);

    Route::get('loe/signoff/{id}', ['uses' => 'LoeController@signoff', 'as' => 'loeSignoff', 'middleware' => ['permission:projectLoe-signoff']]);
    Route::get('loe/masssignoff/{id}', ['uses' => 'LoeController@masssignoff', 'as' => 'loeMassSignoff', 'middleware' => ['permission:projectLoe-signoff']]);
    
    Route::get('loeCreateUpdate/{id}', ['uses' => 'LoeController@view', 'as' => 'loeView', 'middleware' => ['permission:projectLoe-view']]);
    Route::get('loe/dashboard/projects/{id}', ['uses' => 'LoeController@dashboardProjects', 'as' => 'loeDashboardProjects', 'middleware' => ['permission:projectLoe-dashboard_view']]);
    Route::get('loe/{id}', ['uses' => 'LoeController@listFromProjectID', 'as' => 'listFromProjectID', 'middleware' => ['permission:projectLoe-view|projectLoe-dashboard_view']]);
    //endregion
});

Route::get('/home', 'HomeController@index')->name('home');


// Route::get('updator',['uses'=>'UpdateTableController@updator', 'as'=>'updator']);

Route::post('import', ['uses' => 'UserController@UploadExcelToCreateOrUpdateUsers', 'as' => 'UploadExcelToCreateOrUpdateUsers', 'middleware' => ['permission:user-view|user-create|user-edit|user-delete']]);
// Route::get('export', 'UpdateTableController@export');
// Route::get('import',['uses'=>'UpdateTableController@show', 'as' =>'show']);
