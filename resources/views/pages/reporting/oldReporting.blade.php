@if($setting == 0)

<?php
$engine = App::make("getReporticoEngine");
$engine->initial_execute_mode = "MENU";
$engine->access_mode = "ONEPROJECT";
$engine->initial_project = "timeline";
//$engine->initial_project_password = "password"; 
$engine->clear_reportico_session = true;
//var_dump( $engine->output_template_parameters ); exit();

$engine->output_template_parameters["show_hide_prepare_csv_button"] = "show";
$engine->execute();
?>
@elseif($setting == 1)

<?php
$engine = App::make("getReporticoEngine");
$engine->initial_execute_mode = "MENU";
$engine->access_mode = "ONEPROJECT";
$engine->initial_project = "Timeline_withoutCheckIn";
//$engine->initial_project_password = "password"; 
$engine->clear_reportico_session = true;
$engine->reportico_ajax_mode = true;
$engine->output_template_parameters["show_hide_prepare_csv_button"] = "show";
$engine->execute();
?>
@endif
