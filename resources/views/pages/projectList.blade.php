@extends('layouts.app')

@section('head')

<style type="text/css">
    .project-workDetail input,.project-workDetail textarea{
        border: none;
        background: transparent;
        padding-left: 5px;
        resize: none;
    }
    .editable{
        background-color: #fff !important;
        border: 1px solid #ccd0d2 !important;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.075) !important;
    }
    .edit-jobDetails i.fa{
        font-size: 24px;
    }

    .project-workDetail input[type="checkbox"][readonly="readonly"]{
        pointer-events: none;
    }
    select#userName .bg-success{
        display: none;
    }
</style>

@endsection

@section('content')
<input type="hidden" id="project-workDetail-route" value="{{ route('WorkDetails.edit') }}">
<div class="container">
    <div class="row">
        <div class="project-header-wrapper">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-10">
                        <h3><i class="fa fa-university fa-2x" aria-hidden="true"></i> Project Administration</h3>
                    </div>
                    @if($isSuperAdmin && !Auth::user()->hasRole('HRAdmin'))
                    <div class="col-md-2">
                        <a href="{{route('project.create')}}" class="btn btn-danger btn-block"> Create New Project</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @if(count($projects)> 0)
        <div class="col-md-12 panel panel-warning">
            <div class="project-create-wrapper">
                @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
                @endif
                
                <div class="col-md-12 ProjectSearchContainer">
                    <div class="col-md-5">
                        <input type="search" name="searchProject" class="form-control" placeholder="Search Projects" style="padding-right:26px;">
                        <i class="fa fa-search" style="position: absolute;top: 9px;right: 24px;font-size: 16px;"></i>
                    </div>
                </div>
                
                <!-- Projects Cards -->
                <div class="col-md-12 project-cards">
                @php
                    $row_count = 1;
                @endphp
                @foreach($projects as $project)
                    @php
                    //if( ($row_count % 2) != 0 ){
                        //echo '<div class="row">';
                    //}
                    @endphp
                    <div class="col-md-6 project-card">
                        <div class="modal-content project-container">
                            <div class="modal-header">
                                <div class="hidden searchSubCategoryContainer">
                                    <input type="search" name="searchSubCategory" class="form-control" placeholder="Search Sub Category" style="padding-right:26px;">
                                    <i class="fa fa-search" style="position: absolute;top: 25px;right: 24px;font-size: 16px;"></i>
                                </div>
                                <div class="pull-left project-state">
                                    <span class="project-title" title="Project Name"><a href="{{route('project.show',$project->id)}}">{{$project->name}}</span></a>
                                    <small>( {{$project->project_status}} )</small>

                                    @if($isSuperAdmin)
                                    <a class="btn btn-primary btn-sm btn-custom" href="{{route('subcategory.show',$project->id)}}" data-toggle="tooltip" title="Add Sub category" data-placement="bottom"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                    <button class="btn btn-primary btn-sm btn-custom addEditUserProject" href="{{route('subcategory.show',$project->id)}}" data-toggle="tooltip" title="Add Users" data-placement="bottom" data-pi="{{$project->id}}"><i class="fa fa-user-plus" aria-hidden="true"></i></button>
                                    @endif
                                    <a class="btn btn-primary btn-sm btn-custom searchSubCategoryT" title="Search Category"><i class="fa fa-search"></i></a>
                                </div>
                                <div class="pull-right project-actions">
                                    <a href="{{route('project.show',$project->id)}}" data-toggle="tooltip" title="View Project" data-placement="bottom"><i class="fa fa-eye " aria-hidden="true"></i> </a>
                                    @if($isSuperAdmin)
                                        <a href="{{route('project.edit',$project->id)}}" data-toggle="tooltip" title="Edit Project" data-placement="bottom"><i class="fa fa-pencil-square-o " aria-hidden="true"></i></a>
                                    @endif
                                    <a class="viewUpdateProjectUser" href="#" data-toggle="tooltip" title="View Users" data-placement="bottom" data-pi="{{$project->id}}"><i class="fa fa-users " aria-hidden="true"></i></a>
                                    @if($isSuperAdmin || $isSupervisor)
                                        <a href="{{route('document.show',$project->id)}}" data-toggle="tooltip" title="Upload Project Document" data-placement="bottom"><i class="fa fa-upload " aria-hidden="true"></i></a>
                                    @endif
                                </div>
                            </div>

                            <div class="modal-body project-categories-container">
                                <!-- <a class="btn btn-default btn-sm project-sub-category-btn" href="{{route('subcategory.show',$project->id)}}"><i class="fa fa-plus" aria-hidden="true"></i> Sub Category </a> -->
                                
                                @foreach($project->subCategories as $subcategory)
                                <div class="card" data-cat="{{$subcategory->name}}">
                                    <div class="project-sub-category-state">
                                        <div class="pull-left">
                                            <span class="project-sub-category-title" ><a href="{{route('subcategory.view',$subcategory->id)}}" class="text-danger" title="project sub category">{{$subcategory->name}}</a></span>
                                            <small>( {{$subcategory->subCategory_status}} ) </small>

                                            @if($isSuperAdmin)
                                            <a class="btn btn-default btn-sm pull-right btn-danger btn-custom" href="{{route('WorkDetails.Createview',[$project->id,$subcategory->id])}}" data-toggle="tooltip" title="Add Work Details"><i class="fa fa-plus" aria-hidden="true"></i> </a>
                                            @endif
                                        </div>
                                        <div class="pull-right sub-category-action">
                                            <a href="{{route('subcategory.view',$subcategory->id)}}" class="text-danger" data-toggle="tooltip" title="view sub category"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            @if($isSuperAdmin)
                                            <a href="{{route('subcategory.edit',$subcategory->id)}}" class="text-danger" data-toggle="tooltip" title="edit sub category"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-block">
                                        <table class="table table-striped project-workDetail">
                                            <thead>
                                                <th>Work Details</th>
                                                <th>Description</th>
                                                <th>User Accessable</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody>
                                        @foreach($subcategory->workDetails as $workDetail)
                                                <tr>
                                                    <td> <input type="text" value="{{$workDetail->name}}" readonly="readonly"> </td>
                                                    <td> <textarea readonly="readonly">{{$workDetail->description}}</textarea> </td>
                                                    <td> <input type="checkbox" value="1" {{ ($workDetail->isUserAccessable)?'checked':'' }} readonly="readonly"> </td>
                                                    <td class="workDetail-actions">
                                                    @if($isSuperAdmin || $isSupervisor)
                                                        {{-- <a href="" class="text-primary"><i class="fa fa-eye" aria-hidden="true"></i></a> --}}
                                                        <a href="javascipt::void()" title="edit" class="text-primary edit-jobDetails" editState="0" workdetail-id="{{$workDetail->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                    @endif
                                                    </td>
                                                </tr>
                                        @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        
                        </div>    
                    </div>
                    @php
                    //if( ($row_count % 2) == 0 ){
                        //echo '</div>';
                    //}
                    $row_count++;
                    @endphp
                @endforeach
                </div>
                <!-- /Project Cards -->

            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">User List</h4>
      </div>
      <form id="projectUserForm" action="{{route('addProjectUsers')}}" method="POST">
      {{ csrf_field() }}
      <div class="modal-body">
                        
        <div class="form-group{{ $errors->has('userName') ? ' has-error' : '' }}">
            <div class="row">
                <label for="userName" class="col-md-3 col-md-offset-2"> Name : </label>
                <div class="col-md-6"> 
                    <select class="form-control" id="userName" name="userName[]" multiple="multiple" required>
                        @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="pid" id="pid">
                @if ($errors->has('userName'))
                <span class="help-block">
                    <strong>{{ $errors->first('userName') }}</strong>
                </span>
                @endif
            </div>
        </div>

      </div>
      <div class="modal-footer">
        @if($isSuperAdmin)
        <button class="btn btn-primary saveUserBtn">Save</button>
        @endif
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </form>
    </div>

  </div>
</div>
@endsection

@section('footer')

<script type="text/javascript">

    (function(){
        var project_workDetail_route = $('#project-workDetail-route').val();

        $('.edit-jobDetails').on('click',function(){
            var workDetails_editBtn = $(this);
            var workDetails_icon = workDetails_editBtn.find('i.fa');

            var data_row = workDetails_editBtn.parents('tr');

            var workDetail_name = data_row.find('input[type="text"]');
            var workDetail_description = data_row.find('textarea');
            var workDetail_userAccessable = data_row.find('input[type="checkbox"]');

            data_row.removeAttr('class');
            if(workDetails_editBtn.attr('editState') == 0){
                data_row
                .find('input,textarea')
                .removeAttr('readonly')
                .addClass('editable');

                workDetails_editBtn.attr('editState',1);
                workDetails_icon.removeClass('fa-pencil-square-o').addClass('fa-floppy-o');

                workDetail_name.focus();

                return;
            }

            if(workDetails_editBtn.attr('editState') == 1){
                if(workDetail_name.val().trim() == '' || workDetail_description.val().trim() == ''){
                    alert('Do not leave the empty field/s');
                    return;
                }
                
                workDetail_name.attr('readonly','readonly').removeClass('editable');
                workDetail_description.attr('readonly','readonly').removeClass('editable');
                workDetail_userAccessable.attr('readonly','readonly').removeClass('editable');

                workDetails_editBtn.attr('editState',0);
                
                workDetails_editBtn.hide();
                workDetails_editBtn.after('<i class="wait_ fa fa-spin fa-spinner fa-2x"></i>');

                workDetails_icon.addClass('fa-pencil-square-o').removeClass('fa-floppy-o');

                var data = {
                    'workDetail_name': workDetail_name.val(),
                    'workDetail_description': workDetail_description.val(),
                    'workDetail_ID': workDetails_editBtn.attr('workdetail-id'),
                    'userAccessable': workDetail_userAccessable.prop('checked')?1:0,
                    '_token': $('meta[name="csrf-token"]').attr('content')
                }

                var editState = $.ajax({
                    url: project_workDetail_route,
                    method: 'post',
                    data: data,
                    dataType: 'json'
                });

                editState.done(function(res){
                    if(res.edit == true){
                        data_row.addClass('success');
                    }else{
                        data_row.addClass('danger');
                    }

                    workDetails_editBtn.siblings('.wait_').remove();
                    workDetails_editBtn.show();
                });

                editState.fail(function(res){
                    console.log(res);
                    data_row.addClass('danger');
                    workDetails_editBtn.siblings('.wait_').remove();
                    workDetails_editBtn.show();
                });

                //console.log(data);
            }

        });

        $('input[name="searchProject"]').on('input',function(e){
            let projectContainers = $('.project-card');
            let searchText = $(this).val().toLowerCase().trim();

            if(searchText == ''){
                projectContainers.show();
                return;
            }

            projectContainers.hide();

            for(let projectContainer of projectContainers){
                if( projectContainer.getElementsByClassName('project-title')[0].innerText.toLowerCase().indexOf(searchText) != -1 )
                    projectContainer.style.display = 'block';
            }

            return false;
        })

        var currentSearchingSubCatContainers = {};
        $('.searchSubCategoryT').on('click',function(){
            var modalHeader = $(this).parents('.modal-header');
            currentSearchingSubCatContainers = modalHeader.siblings('.project-categories-container').find('.card');
            modalHeader.find('.searchSubCategoryContainer').toggleClass('hidden');
        });
        
        $('input[name="searchSubCategory"]').on('focus',function(){
            currentSearchingSubCatContainers = $(this).parents('.project-container').find('.project-categories-container .card');
        });

        $('input[name="searchSubCategory"]').on('input',function(){
            var searchText = $(this).val().toLowerCase().trim();

            if(searchText == ''){
                currentSearchingSubCatContainers.show();
                return;
            }

            currentSearchingSubCatContainers.hide();

            for(let currentSearchingSubCatContainer of currentSearchingSubCatContainers){
                if(currentSearchingSubCatContainer.dataset.cat.toLowerCase().indexOf(searchText) != -1){
                    currentSearchingSubCatContainer.style.display = 'block';
                }
            }
            return false;
        })

        $('.addEditUserProject').on('click', async function(e){
            e.preventDefault()
            $('#pid').val(this.dataset.pi)
            const res = await getProjectUsersList(this.dataset.pi)
            res.forEach(ele => {
                userLooper(ele, true, 'bg-success')
            });
            $('#myModal').modal('show')
        })

        $('#myModal').on('hidden.bs.modal', function (e) {
            $('#pid').val('')
            $('.saveUserBtn').text('Save')
            $('#projectUserForm').attr('action', `{{route('addProjectUsers')}}`)
            $('#userName option').each(function(e) {
                this.disabled = false
                this.classList.remove("bg-success")
            })
        })

        function getProjectUsersList(proud) {
            return new Promise(resolve => {
                $.ajax({
                    url: `{{route('getProjectUserByProjectId')}}`,
                    data: {pid: proud},
                    dataType: 'json',
                    method: 'get'
                })
                .done(res => resolve(res))
                .fail(res => resolve(false))
            })
        }

        $('.viewUpdateProjectUser').on('click', async function(e){
            e.preventDefault()
            $('#pid').val(this.dataset.pi)
            $('.saveUserBtn').text('Remove')
            $('#projectUserForm').attr('action', `{{route('removeProjectUsers')}}`)
            const res = await getProjectUsersList(this.dataset.pi)
            const uId = res.map(e => e.user_id)
            $('#userName option').each(function(e) {
                if(!uId.includes(Number(this.value))){
                    this.disabled = true
                    this.classList.add('bg-success')
                }
            })
            $('#myModal').modal('show')
        })

        function userLooper(ele=[], buttDis=false, classL='', forEdit=false) {
            $('#userName option').each(function(e) {
                if(ele.user_id == this.value){
                    this.disabled = buttDis
                    this.classList.add(classL)
                }
            })
        }

    })()

</script>

@endsection
