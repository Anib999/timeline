@extends('layouts.app')
@section('content')
<input type="hidden" id="position-disable-route" value="{{ route('position.status') }}">
<div class="container-fluid">
<!-- <a href="{{  route('user.index') }}" class="btn btn-md btn-default"><i class="fa fa-arrow-left"></i> Back</a> -->
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default register-wrapper">
                <div class="panel-body">
                    <table id="positions-list" class="table table-hover table-responsive">
                    <thead >
                        <tr>
                            <th>Position Name</th>
                            <th>Details</th>
                            <th>Rank</th>
                            <th>Status</th>
    <!--                        <th>Delete</th>-->
                            <th>Action</th>
                            <!-- <th></th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($positions as $position)
                        <tr class="row-{{$position->id}}">
                            <td>{{$position->name}}</td>
                            <td>{{$position->details}}</td>
                            <td>{{$position->rank}}</td>
                            <td class="status">{{($position->status == 1)?'Active':'InActive'}}</td>
    <!--                        <td><a href="{{route('position.destroy',['position_id' => $position->id])}}"><i class="fa fa-trash-o fa-2x" aria-hidden="true"></i></a></td>-->
                            <td>
                                <span class="btn-default btn-xs edit-position fa fa-pencil" position-id="{{ $position->id }}" status="0" style="cursor: pointer;"></span>
                                <!-- font-weight: bold;font-size: 20px; -->
                                <span class="btn-warning btn-xs delete-position fa fa-times" position-id="{{ $position->id }}" status="0" style="cursor: pointer;"></span>
                                <!-- font-weight: bold;font-size: 20px; -->
                                <span class="btn-success btn-xs active-position fa fa-check" position-id="{{ $position->id }}" status="1" style="cursor: pointer;"></span>
                                <!-- font-weight: bold;font-size: 20px; -->
                            </td>
                            <!-- <td><span class="delete-position fa fa-times" position-id="{{ $position->id }}" status="0" style="font-weight: bold;font-size: 20px;cursor: pointer;"></span></td>
                            <td><span class="active-position fa fa-check" position-id="{{ $position->id }}" status="1" style="font-weight: bold;font-size: 20px;cursor: pointer;"></span></td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table> 
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default register-wrapper">
                <div class="panel-body">
                    <div class="well text-center text-danger">
                        <p> Rank Order :  1-Lowest & 14-Highest</p>
                    </div>
                    <form class="form-horizontal" method="POST" action="{{ route('position.store') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" id="position" name="position">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-2 control-label">Name</label>

                            <div class="col-md-10">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
                            <label for="details" class="col-md-2 control-label">Details</label>

                            <div class="col-md-10">
                                <textarea id="details" type="text" rows="2" cols="15" class="form-control" name="details" value="{{ old('details') }}" required autofocus> </textarea>

                                @if ($errors->has('details'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('detailss') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('rank') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-2 control-label">Rank</label>
                            <div class="col-md-10">
                                <select class="form-control"  id="rank" name="rank" required="required">
                                    @for($i=0; $i<count($ranks); $i++)
                                        <option value="{{ $ranks[$i]}}">{{ $ranks[$i] }} </option>  
                                    @endfor
                                </select>
                                @if ($errors->has('rank'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('rank') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary btn-block" id="createPostBtn">
                                    Create Position
                                </button>
                                <button type="reset" class="btn btn-default btn-block" id="resetBtn" style="display: none;">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
(function(){
    var positionRemoveRoute = $('#position-disable-route').val();

    $('#positions-list').on('click','span.delete-position, span.active-position',function(){
        var positionDeleteBtn = $(this);
        var positionId = positionDeleteBtn.attr('position-id');
        var positionRow = positionDeleteBtn.parents('tr');

        // positionRow.removeAttr('class');

        var data = {
            'positionId': positionId,
            'status': positionDeleteBtn.attr('status'),
            '_token': $('meta[name="csrf-token"]').attr('content'),
        }
        positionDeleteBtn.after('<i class="wait_ fa fa-spin fa-spinner"></i>');
        positionDeleteBtn.hide();

        var disableStatus = $.ajax({
            url: positionRemoveRoute,
            method: 'post',
            data: data,
            dataType: 'json'
        });

        disableStatus.done(function(res){
            if(res.statusChange == true){
                // positionRow.addClass('success');
                if(data.status == 0)
                    positionRow.find('td.status').text('InActive');
                else
                    positionRow.find('td.status').text('Active');
            }else{
                // positionRow.addClass('danger');
            }
            positionDeleteBtn.siblings('i.wait_').remove();
            positionDeleteBtn.show();
        });

        disableStatus.fail(function(res){
            // console.log(res);
            // positionRow.addClass('danger');
            positionDeleteBtn.siblings('i.wait_').remove();
            positionDeleteBtn.show();
        });
    });

    $('.edit-position').on('click', function(e) {
        e.preventDefault()
        const editPos = this.attributes['position-id'].value
        const editVal = $(`.row-${editPos}`)[0].children;
        $('#name').val(editVal[0].innerHTML)
        $('#details').val(editVal[1].innerHTML)
        $('#rank').val(editVal[2].innerHTML.split('.')[0])
        $('#position').val(editPos)
        $('#createPostBtn').text('Update Position')
        $('#resetBtn').show()
    })

    $('#resetBtn').on('click', function(e) {
        // e.preventDefault()
        $('#resetBtn').hide()
        $('#position').val('')
        $('#createPostBtn').text('Create Position')
    })

})()
</script>

@endsection
