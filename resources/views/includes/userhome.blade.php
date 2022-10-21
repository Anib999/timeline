<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h2> Task List </h2>

            <!-- Projects -->
        
            @foreach($projects as $project)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a data-toggle="collapse" href="#project-category-toogle-{{$project->id}}">
                        <div class="panel-title"> 
                            <p> {{ $project->name}} <span class="pull-right text-info"> Assigned For Project</span></p>
                        </div>
                    </a>
                    <div class=" panel-collapse collapse" id="project-category-toogle-{{$project->id}}">
                        @foreach($project->subCategories as $subcategory)
                        <div class="panel-body">
                            <div class="col-md-12">
                                <div class="panel-heading bg-danger">
                                    <p class="panel-title"> {{$subcategory->name}}</p>
                                </div>
                            </div>
                        </div>
                        
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

            <!--  Subcategories -->

           @foreach ($subcategories as $subcategory)
               @if($subcategory->project->supervisor !== $subcategory->incharge )
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a data-toggle="collapse" href="#collapse-project-sub-{{++$count_}}">
                                <div class="panel-title"> 
                                    <p>  {{$subcategory->project->name}} <span class="pull-right text-info"> Assigned For Subcategory</span></p>
                                </div>
                            </a>
                        </div>

                        <div class="panel-collapse collapse" id="collapse-project-sub-{{$count_}}">
                            <div class="panel-body">
                                <div class="panel-heading  bg-danger">
                                    <div class="panel-title">
                                        <p class="panel-title">{{$subcategory->name}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
               @endif
           @endforeach           
            
        </div>
    </div>
</div>

