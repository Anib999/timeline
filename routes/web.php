<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {

    Route::resource('profile', 'UserProfileController');

    Route::post('/updatePassword', [
        'uses' => 'UserProfileController@update',
        'as' => 'updatePassword'
    ]);

    Route::resource('attendence', 'AttendenceController');
    Route::resource('dayWorkHour', 'DayWorkEntryController');
    Route::post('job/postJob', [
        'uses' => 'DayWorkEntryController@postJob',
        'as' => 'job.postJob'
    ]);
    Route::get('api/projectdropdown', 'DayWorkEntryController@projectAPI');
    Route::get('api/subcatdropdown', 'DayWorkEntryController@subcatAPI');

    //new
    Route::get('ajaxAssignedUserProject', [
        'uses' => 'DayWorkEntryController@ajaxAssignedUserProject',
        'as' => 'ajaxAssignedUserProject'
    ]);
    //new

    Route::post('attendence/requestForCheckIn', [
        'uses' => 'AttendenceController@requestForCheckIn',
        'as' => 'attendence.requestForCheckIn'
    ]);

    Route::post('makeReadableMessage/{id}', [
        'uses' => 'AdminToEmployeeRequestForCheckInOutController@makeRead',
        'as' => 'makeReadableMessage.makeRead'
    ]);


    Route::post('attendence/CheckIn', [
        'uses' => 'AttendenceController@checkIn',
        'as' => 'attendence.checkIn'
    ]);

    Route::resource('userLeave', 'UserLeaveController');

    Route::get('employeeAttendance', [
        'uses' => 'ReportingController@employeeAttendance',
        'as' => 'employeeAttendance'
    ]);

    Route::get('employeeLeave', [
        'uses' => 'ReportingController@employeeLeave',
        'as' => 'employeeLeave'

    ]);
    Route::get('downloadAttendancePDF/{id}', [
        'uses' => 'ReportingController@downloadAttendancePDF',
        'as' => 'downloadAttendancePDF'

    ]);
    Route::get('downloadLeavePDF/{id}', [
        'uses' => 'ReportingController@downloadLeavePDF',
        'as' => 'downloadLeavePDF'

    ]);

    Route::get('employeeDayWorkReport', [
        'uses' => 'ReportingController@employeeDayWorkReport',
        'as' => 'employeeDayWorkReport'

    ]);

    Route::post('api/workDetailsAPI', [
        'uses' => 'DayWorkEntryController@workDetailsAPI',
        'as' => 'workDetailsAPI'
    ]);

    Route::post('getWorkEntriesByDate', [
        'uses' => 'DayWorkEntryController@getWorkEntriesByDate',
        'as' => 'getWorkEntriesByDate'
    ]);

    Route::post('api/deleteWorkEntryAPI', [
        'uses' => 'DayWorkEntryController@deleteWorkEntryAPI',
        'as' => 'deleteWorkEntryAPI'
    ]);

    //field visit
    Route::get('addFieldVisit', [
        'uses' => 'DayWorkEntryController@addFieldVisit',
        'as' => 'addFieldVisit'
    ]);

    Route::post('storeFieldVisit', [
        'uses' => 'DayWorkEntryController@storeFieldVisit',
        'as' => 'storeFieldVisit'
    ]);

    Route::post('getFieldVisitEntriesByDate', [
        'uses' => 'DayWorkEntryController@getFieldVisitEntriesByDate',
        'as' => 'getFieldVisitEntriesByDate'
    ]);

    //field visit

    Route::post('changeNotificationStatus', [
        'uses' => 'NotificationsController@changeNotificationStatus',
        'as' => 'changeNotificationStatus'
    ]);

    Route::get('viewAllNotification', [
        'uses' => 'NotificationsController@viewAllNotification',
        'as' => 'viewAllNotification'
    ]);
    //Route::get('api/workDetailsAPI', 'DayWorkEntryController@workDetailsAPI');

    //new added
    Route::get('getLocationData', [
        'uses' => 'LocationController@getLocationData',
        'as' => 'getLocationData'
    ]);

    Route::get('checkWhetherCheckOut', [
        'uses' => 'AttendenceController@checkWhetherCheckOut',
        'as' => 'checkWhetherCheckOut'
    ]);

    Route::get('updateCheckOutWithoutAdmin/{udata}', [
        'uses' => 'AttendenceController@updateCheckOutWithoutAdmin',
        'as' => 'updateCheckOutWithoutAdmin'
    ]);

    Route::get('ajaxAttendanceOfUser', [
        'uses' => 'ReportingController@ajaxAttendanceOfUser',
        'as' => 'ajaxAttendanceOfUser'
    ]);

    Route::get('ajaxLeaveOfUser', [
        'uses' => 'ReportingController@ajaxLeaveOfUser',
        'as' => 'ajaxLeaveOfUser'
    ]);

    Route::get('ajaxDayworkOfUser', [
        'uses' => 'ReportingController@ajaxDayworkOfUser',
        'as' => 'ajaxDayworkOfUser'
    ]);

    Route::get('ajaxCheckUserNameAndReturn', [
        'uses' => 'UserController@ajaxCheckUserNameAndReturn',
        'as' => 'ajaxCheckUserNameAndReturn'
    ]);

    Route::post('addProjectUsers', [
        'uses' => 'ProjectController@addProjectUsers',
        'as' => 'addProjectUsers'
    ]);

    Route::get('getProjectUserByProjectId', [
        'uses' => 'ProjectController@getProjectUserByProjectId',
        'as' => 'getProjectUserByProjectId'
    ]);

    Route::post('removeProjectUsers', [
        'uses' => 'ProjectController@removeProjectUsers',
        'as' => 'removeProjectUsers'
    ]);

    Route::get('ajaxLeaveApplicableByUserId', [
        'uses' => 'UserLeaveController@ajaxLeaveApplicableByUserId',
        'as' => 'ajaxLeaveApplicableByUserId'
    ]);
    //new added

});

/* Admin Auth Route */
Route::group(['middleware' => ['auth', 'admin']], function () {

    Route::resource('project', 'ProjectController');
    Route::resource('subcategory', 'SubCategoryController');
    Route::resource('job', 'JobController');
    Route::get('/ajax-subcat', 'JobController@projectAPI');



    Route::resource('document', 'ProjectDocumentController');
    Route::get('subcategory/view/{id}', [
        'uses' => 'SubCategoryController@view',
        'as' => 'subcategory.view'
    ]);
    Route::get('job/{pro}/{cat}', [
        'uses' => 'JobController@getCreate',
        'as' => 'job.getCreate'
    ]);

    Route::get('workDetail/{project_id}/{subcategory_id}', [
        'uses' => 'WorkDetailsController@createWorkDetail_view',
        'as' => 'WorkDetails.Createview'
    ]);

    Route::post('leave/editLeaveType', [
        'uses' => 'LeaveController@editLeaveType',
        'as' => 'Leave.editLeaveType'
    ]);

    Route::post('leave/editLeaveYear', [
        'uses' => 'LeaveController@editLeaveYear',
        'as' => 'Leave.editLeaveYear'
    ]);

    Route::post('workDetail/create', [
        'uses' => 'WorkDetailsController@createWorkDetail',
        'as' => 'WorkDetails.create'
    ]);
    Route::post('workDetail/edit', [
        'uses' => 'WorkDetailsController@editWorkDetail',
        'as' => 'WorkDetails.edit'
    ]);
    Route::resource('user', 'UserController');
    Route::resource('position', 'PositionController');

    Route::resource('userDocument', 'UserDocController');
    Route::resource('AdminUserView', 'AdminUserViewDetails');
    Route::post('userDocUpload', [
        'uses' => 'UserDocController@upload',
        'as' => 'userDocUpload.upload'
    ]);

    Route::resource('EmployeeRequestForCheckInOut', 'AdminToEmployeeRequestForCheckInOutController');

    Route::post('position/status', [
        'uses' => 'PositionController@disableEnablePosition',
        'as' => 'position.status'
    ]);

    Route::post('withCheck', [
        'uses' => 'HomeController@withCheckstore',
        'as' => 'withCheck.withCheckstore'
    ]);

    Route::resource('leave', 'LeaveController');


    Route::post('leave/year', [
        'uses' => 'LeaveController@creteLeaveYear',
        'as' => 'leaveYear.creteLeaveYear'
    ]);
    Route::post('leave/applicable', [
        'uses' => 'LeaveController@makeLeaveApplicable',
        'as' => 'leaveApplicable.makeLeaveApplicable'
    ]);

    Route::post('leave/action', [
        'uses' => 'LeaveController@leaveRequestAction',
        'as' => 'leave.leaveRequestAction'
    ]);

    Route::resource('holiday', 'HolidayController');

    Route::post('holiday/year', [
        'uses' => 'HolidayController@createHoliday',
        'as' => 'holiday.createHoliday'
    ]);

    Route::post('holiday/applicable', [
        'uses' => 'HolidayController@makeHolidayApplicable',
        'as' => 'holiday.makeHolidayApplicable'
    ]);

    Route::post('holiday/delete', [
        'uses' => 'HolidayController@deleteHoliday_day',
        'as' => 'holiday.deleteHoliday_day'
    ]);

    Route::get('viewReporting', [
        'uses' => 'ReportingController@viewReporting',
        'as' => 'viewReporting'

    ]);

    Route::resource('roleGroup', 'RoleGroupController');

    Route::post('leave/removeLeaveYear', [
        'uses' => 'LeaveController@inactive_leave_year',
        'as' => 'leave.removeLeaveYear'
    ]);

    Route::post('leave/removeLeaveType', [
        'uses' => 'LeaveController@inactive_leave_types',
        'as' => 'leave.removeLeaveType'
    ]);

    Route::post('leave/removeLeaveApplicableUser', [
        'uses' => 'LeaveController@inactive_leave_applicable',
        'as' => 'leave.removeLeaveApplicableUser'
    ]);

    Route::post('holiday/removeHolidayYear', [
        'uses' => 'HolidayController@removeHolidayYear',
        'as' => 'holiday.removeHolidayYear'
    ]);

    Route::post('holiday/removeHolidayType', [
        'uses' => 'HolidayController@removeHolidayType',
        'as' => 'holiday.removeHolidayType'
    ]);
});
