<style type="text/css">
.delete-row,.deleteTodaysWorkEntry-row{
    font-weight: bold;
    font-size: 20px;
    cursor: pointer;
}

#projectDetailsContainer-table td:first-child{
    padding-top: 2px !important;
}
#projectDetailsContainer-table tbody.todaysWorkEntriesContainer tr:last-child {
    border-bottom: 4px solid;
}
</style>
<input type="hidden" value="{{route('deleteWorkEntryAPI')}}" id="deleteWorkEntryAPI">
<div class="col-md-7 display-daywork-container">
    <div id="submitting-status" style="display:none; position:absolute; text-align: center; background-color: rgba(0,0,0,0.06);">
        <i class="fa fa-spinner fa-spin fa-5x" style="position:relative; top:calc(50% - 84px);"></i>
    </div>

    <form action="" method="post" id="WorkDayDetails">
    <button type="submit" class="btn btn-success submitWorkDay" style="display:none;">Submit</button>
    <div id="workDayHourView" style="margin:2em 0;">
        <div style="border-bottom: 1px solid #cccccc;">
            <div id="tablework-date" style="padding: 0.7em 2em;display: inline-block;border-radius:  0;background: #eaeaea;font-weight: bold;border-bottom: none;" class="btn btn-default">
            
            </div>
        </div>
        <table id="projectDetailsContainer-table" class="table table-striped">
            <thead>
                <th></th>
                <th>Projects</th>
                <th>Sub Categories</th>
                <th>Work Details</th>
                <th>Work Hour</th>
                <th>Comments</th>
            </thead>
            
<?php //var_dump($workDetails); exit(); ?>
            <tbody class="todaysWorkEntriesContainer">
            <!--@--if($workDetails->count() > 0)-- -->
             @if(count($workDetails) > 0)
                @foreach($workDetails as $workDetail)
                @php
                //var_dump( $workDetail->WorkDetails->name ); //exit;
                //subCategories
                //projects
                //WorkDetails
                @endphp
                <tr>
                    <td><span entryid="{{$workDetail->id}}" class="deleteTodaysWorkEntry-row text-danger">&times;</span></td>
                    <td class="project-field" project="{{$workDetail->project_id}}">{{$workDetail->projects->name}}</td>
                    <td class="subCategory-field" subCategory="{{$workDetail->subcat_id}}">{{$workDetail->subCategories->name}}</td>  
                    <td class="workDetail-field" workDetail="{{$workDetail->workDetail_id}}">{{$workDetail->WorkDetails->name}}</td>
                    <td class="workHour-field">{{$workDetail->workHour}}</td>
                    <td class="workComment-field">{{$workDetail->workComment}}</td>
                </tr>
                @endforeach
            @endif
            </tbody>
        
            <tbody class="projectDetailsContainer-fields-container">
            </tbody>

            <tbody class="totalHours-container">
                <tr><td></td><td></td><td></td><th class="totalHours-field-label">Total Hours</th><th class="totalHours-field">{{$totalHour}}</th><td></td></tr>
            </tbody>
        </table>
    </div>
    <button type="submit" class="btn btn-success submitWorkDay" style="display:none;">Submit</button>
    </form>
</div>