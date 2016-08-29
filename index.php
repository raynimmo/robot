<?php
/*
 * Robot endpoint puzzle solution
 *
 * self submitting php form with graphical display of start, finish and path taken
 */

include "inc/helpers.inc";

$this_page = curr_page();
$testrun = $_GET['test'];
$hasError = false;
$invalidPlace =  false;
$display_report = false;
$moves = array();
$place_start = array();
$place_end = array();
$move_result = array();
$border = '';
$panel=false;

// default grid
$grid_size = 5;
if($_POST['grid'] || $_GET['grid']){
    $_GET['grid'] ? $grid_size = $_GET['grid'] : $grid_size = $_POST['grid'];
    $panel = true;
}

/*
 * Collect submitted data
 */
if($_POST['submit'] || $_GET['submit']){

    $command = $_POST['command'];
    if(!$_POST['command']){
        $command = $_GET['command'];
    }
    $file = $_POST['fileupload'];
    $valid_file = true;

    // Process the commands according to the source
    if($command && !$_FILES['fileupload']['name'] && !$testrun){
        $file_debug .= 'textarea submitted command<br />';
        $command_string = $command;
        $lines = explode("\r\n", $command);

    }elseif($_FILES['fileupload']['name']) {
        $file_debug .= 'file upload submitted command<br />'.$upload_result[1];
        $upload_result = upload_file($file, $file_debug, $valid_file);
        $valid_file = $upload_result[0];
        // Format the input for better splitting
        $file_contents = str_replace(" ", "",$upload_result[2]);
        $command_string = str_replace("PLACE", "PLACE ",$file_contents);
        $lines = explode("\n", $command_string);

    }if($testrun){
        // Command submitted by test run
        $file_debug .= 'test submitted command<br />';
        // Format the input for better splitting
        $file_contents = str_replace("%0A", "\n",$command);
        $command_string = str_replace("PLACE", "PLACE ",$file_contents);
        $lines = explode("\n", $command_string);
    }

    // How many commands
    $command_amnt = sizeof($lines);
    
    // Get start position
    // First line contains the placing information
    $start = explode(" ", $lines[0]);
    // Check for the PLACE command at first position
    $init = strtolower($start[0]);

    // Check command contains exact wording at start
    if(substr($init, 0, 5 ) === "place") {
        
        $debugvars = array($init, $lines[0], $start[0], $start[1]);
        $cmd_debug .= debug_output('init', $debugvars);

        // Get the x.y coords
        if(!$testrun) {
            $placed = explode(",", $start[1]);
        }else{
            $placed = explode(",", $start[2]);
        }
        $xpos = $placed[0];
        $ypos = $placed[1];
        // Get the position that is facing
        $facing = str_replace(' ', '', strtolower($placed[2]));

        // Check if position valid
        // Basic boundary check for first PLACE command
        if($xpos < 0 | $xpos > $grid_size | $ypos < 0 | $ypos > $grid_size){
            $valid_placement = false;
        }else{
            $valid_placement = true;
        }
        
        if($valid_placement == true){
            $debugvars = array($i, $moves, '', $facing, $xpos, $ypos, $grid_size);
            $cmd_debug .= debug_output('place', $debugvars);
            //add to moves array
            array_push($moves, array($xpos, $ypos, $facing));
            //add to placed array
            array_push($place_start, array($xpos, $ypos, $facing));

        }else{
            // Invalid place command not detected at start
            $err_output .= "Placement out of bounds, keep grid position between 0,0 and ".($grid_size-1).",".($grid_size-1).".";
            $cmd_debug .= "invalid placement: ".$xpos.",".$ypos."<br />".$err_output;
            $hasError = true;
        }

    }else{
        // Incorrect command initialisation
        $err_output .= "Start a command using the 'PLACE' command";
        $cmd_debug .= "invalid init: ".$init."<br />".$err_output;
        $hasError = true;
    }

    // Proceed if no errors exist
    if(!$hasError) {
        // Initialise variables
        $start_xpos = $xpos;
        $start_ypos = $ypos;
        $start_facing = $facing;
        $new_ypos = 0;
        $new_xpos = 0;
        $new_facing = '';

        // Extract commands into better format
        for ($i=0; $i <= $command_amnt; $i++) {
            $cleaned_command = str_replace(' ', '', strtolower($lines[$i]));
            if(substr(strtolower($cleaned_command), 0, 5 ) === "place") {
                $movement_commands[$i] = str_replace("place", "place ",strtolower($cleaned_command));
            }else{
                $movement_commands[$i] = strtolower($cleaned_command);
            }
        }

        // Iterate through the commands for moving the robot
        for ($i=0; $i < sizeof($movement_commands)-1; $i++) {

            $new_place = false;

            // Check for alternate PLACE command within instructions
            if(substr($movement_commands[$i], 0, 5 ) === "place") {
                // Re-place the robot to a new position
                $new_position = str_replace('place', '', strtolower($movement_commands[$i]));
                $cleaned_command = str_replace(' ', '', strtolower($new_position));

                // Get the x.y coords
                $placed = explode(",", $new_position);
                $xpos = $placed[0];
                $ypos = $placed[1];

                // Get the position that the robot is facing
                $facing = str_replace(' ', '', strtolower($placed[2]));
                $debugvars = array($i, $moves, '', $facing, $xpos, $ypos);
                $cmd_debug .= debug_output('report', $debugvars);

                // Set finish at last position
                array_push($place_end, array($moves[$i-1][0], $moves[$i-1][1], $moves[$i-1][2]));
                array_push($place_start, array($xpos, $ypos, $facing));
                array_push($moves, array($xpos, $ypos, $facing));

                $new_place = true;

                $cmd_debug .= "<br />### Record Move A<br />";

            }elseif($movement_commands[$i]==="report"){
                // Display reports - stops move processing
                $debugvars = array($i, $moves, $movement_commands, $moves[$i-1][2], $moves[$i-1][0], $moves[$i-1][1]);
                $cmd_debug .= debug_output('report', $debugvars);

                $boundary = check_bounds($i, $moves, $move_result, $grid_size, $facing, $cmd_debug, $movement_commands);

                $valid_move = $boundary[0];
                $cmd_debug = $boundary[1]; 

                if($valid_move){
                    // Set the finish at last position
                    array_push($moves, array($moves[$i-1][0], $moves[$i-1][1], $moves[$i-1][2]));
                    array_push($place_end, array($moves[$i-1][0], $moves[$i-1][1], $moves[$i-1][2]));
                    $display_report = true;

                }else{
                    // Invalid move passed
                    $err_output .= "Placement out of bounds, keep grid position between 0,0 and ".($grid_size-1).",".($grid_size-1).".";
                    $debugvars = array($i, $facing, $moved_xpos, $moved_ypos, $err_output);
                    $cmd_debug .= debug_output('invalid', $debugvars);

                    array_push($place_end, array($moves[$i-1][0], $moves[$i-1][1], $moves[$i-1][2]));
                    
                    $hasError = true;
                    $invalidPlace = true;
                    $display_report = true;
                }
            
            }else{

                // Warn against empty line within input
                if($movement_commands[$i]===""){
                    $err_output = "Empty line input as command";
                    $cmd_debug .= "[".$i."] > Error: Empty line entered<br />";
                    $hasError = true;

                // Warn against moved attempted after report marker
                }elseif($display_report){
                    $err_output .= "Attempted moves input after passing REPORT command";
                    $cmd_debug .= "[".$i."] > Error: '".$movement_commands[$i]."' passed after REPORT<br />";
                    $hasError = true;

                // If no errors then proceed
                }elseif(!$hasError){
                    switch($movement_commands[$i]){
                        // Move robots position on the grid by one unit
                        // Direction of movement dependant upon $facing var
                        case 'move' :
                            $move_result = move_position($i, $facing, $start_ypos, $new_ypos, $start_xpos, $new_xpos, $movement_commands[$i], $moves);

                            $moved_xpos = $move_result[0];
                            $moved_ypos = $move_result[1];
                            $cmd_debug .= $move_result[2];

                            $debugvars = array($i, $new_xpos, $new_ypos, $moved_xpos, $moved_ypos);
                            $cmd_debug .= debug_output('move', $debugvars);

                            $boundary = check_bounds($i, $moves, $move_result, $grid_size, $facing, $cmd_debug, $movement_commands);

                            $valid_move = $boundary[0];
                            $cmd_debug = $boundary[1]; 

                            if($valid_move){
                                $new_xpos = $moved_xpos;
                                $new_ypos = $moved_ypos;

                            }else{
                                $err_output .= "Placement out of bounds, keep grid position between 0,0 and ".($grid_size-1).",".($grid_size-1).".";
                                
                                $debugvars = array($i, $facing, $moved_xpos, $moved_ypos, $err_output);
                                $cmd_debug .= debug_output('invalid', $debugvars);

                                $hasError = true;
                                $invalidPlace = true;

                                // suggest available moves
                                // use $facing and $grid_size variables to offer alternates from last position
                                $border = $facing;
                                if(($facing=='north') || ($facing=='south')){
                                    $move_value = $moved_xpos;
                                }elseif(($facing=='east') || ($facing=='west')){
                                    $move_value = $moved_ypos;
                                }
                                
                                $suggest = suggest_moves($border, $move_value, $grid_size);

                                $alt_move = $suggest[0];
                                $cmd_debug .= $suggest[1];
                                $err_output .= $suggest[2];
                            }
                            
                        break;

                        case 'left' :
                            // Rotate robots facing direction left
                            $debugvars = array($i, '', $movement_commands, $facing);
                            $cmd_debug .= debug_output('turn', $debugvars);

                            $facing = turn_left($facing);
                        
                        break;

                        case 'right' :
                            // Rotate robots facing direction right
                            $debugvars = array($i, '', $movement_commands, $facing);
                            $cmd_debug .= debug_output('turn', $debugvars);

                            $facing = turn_right($facing);

                        break;
                    }
                }
            }

            // Record move in array for later use
            if($new_place){
                $new_xpos = $xpos;
                $new_ypos = $ypos;
                $cmd_debug .= "[".$i."] new >>> x=".$new_xpos.", y=".$new_ypos.", f=".$facing."<br />";

            }else{
                $cmd_debug .= "[".$i."] >>> default move:<br />";
            }

            $save_result = save_move($i, $new_xpos, $new_ypos, $start_xpos, $start_ypos, $facing, $moves);

            $moves = $save_result[0];
            $cmd_debug .= $save_result[1];

            // Check boundary
            if($movement_commands[$i]!='report') {
                $boundary = check_bounds($i, $moves, $move_result, $grid_size, $facing, $cmd_debug, $movement_commands);
                $move_validity = $boundary[0];
                $cmd_debug = $boundary[1];
                
            }
        } // end for($i) loop

        // Get the final positions
        $end_facing = $facing;
        $end_position = $new_xpos.",".$new_ypos;

        // Dump all variables to debugging info panel
        $debugvars = array($i, $moves, $movement_commands, $place_start, $place_end);      
        $cmd_debug .= debug_output("fulloutput", $debugvars);


        /* 
         * Construct grid output
         */
        $grid_output = build_grid($grid_size, $start_ypos, $new_ypos, $start_xpos, $new_xpos, $start_facing, $end_facing, $movement_commands, $moves, $place_start, $place_end);

    } // end !$hasError

    /*
     * Construct output sections
     */

    $debug_output = "string: ".$command_string."<br />".
                        "lines: ".$command_amnt."<br />".
                        "start position: ".$start_xpos.",".$start_ypos."<br />".
                        "start facing: ".$start_facing."<br />".
                        "----<br />".$cmd_debug."<br />".
                        "----<br />end position: ".$end_position."<br />".
                        "end facing: ".$facing; //.
                        //"<br />----<br />moves:<br />".$moves_debug;

    if($_GET['test']){
        $debug = $debug_output;
    }else{
        $debug = '<div class="debug">'.$cmd_debug.'</div>';
    }

    $report_type = 1;
    $output_txt= $end_position.','.$facing;

    // Build out report data
    $report_data = report_build($display_report, $move_validity, $hasError, $invalidPlace, $output_txt, $err_output);

    $output_txt = $report_data[0];
    $report_type = $report_data[1];
    $report_header = $report_data[2];

   if($display_report) {
        $grid = '<div class="output_grid">'.$grid_output.'</div>';
    }
    // Assign report display to variable
    $report_msg = report_display($report_type, $report_header, $output_txt);
 
} // end submit


/*
 * Testing output - text only
 */
if($_GET['test']){
    $tid = $_GET['tid'];
    $test_results = array($tid, $file_debug, $command_string, $output_txt, $err_output, $debug);
    // Simplified json format returned for test suite
    echo json_encode($test_results);
    
}else{
if($_GET['panels']=='open' || $_POST['panels']=='open'){
    $panel=true;
}

?><!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Toy Robot Simulator</title>
    <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>if (!window.jQuery) { document.write('<script src="js/jquery.min.js"><\/script>'); }</script>
    <script src="js/scripts.js"></script>
</head>
<body class="<?php 
        if($_POST['submit']){ 
            print 'submitted ';
        }
        if($panel){ 
            print 'open';
        }
    ?>">
    <div class="debug">
        debug:<br /><?php print $file_debug; ?>
        <br />----------<br />File:<br />
        <?php print $command_string;
        ?>
    </div>
    <div class="wrapper">
        <form class="form-horizontal simulator <?php if(!$panel){ print 'middle'; }?>" enctype="multipart/form-data" method="post" action="<?php if($_POST['grid']){ print "/?grid=".$grid_size; } ?>">

            <fieldset>
                <legend>Toy Robot Simulator
                    <button onclick="toggleChart();" type="button" name="viewdisplay" class="btn view"><?php if($panel){ print 'Hide';}else{ print 'Show'; }?> Graph</button>
                </legend>

                <div class="control-group">
                    <label class="control-label" for="command-input">Command</label>
                    <div class="controls">
                        <textarea rows="10" id="command" class="input-xlarge" name="command"><?php print $command_string; ?></textarea>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="file">File</label>
                    <div class="controls">
                        <input type="file" name="fileupload" id="fileupload" />
                    </div>
                </div>
                <span><?php print $report_msg; ?></span>
                <div class="form-actions">
                    <button type="submit" name="submit" value="1" class="btn btn-primary">Send Commands</button>
                </div>
            </fieldset>
            <input type="hidden" value="<?php if($panel){ print 'open';}else{ print 'closed'; }?>" name="panels" id="panels" />
        </form>

        <div class="output <?php if(!$panel){ print 'middle'; }?>">
            <div class="presets">
                <?php 
                if ($grid_size=='20'){ 
                ?>
                <button onclick="presets('p11');" class="btn">Preset 1</button>
                <button onclick="presets('p9');" class="btn">Preset 2</button>
                <button onclick="presets('p10');" class="btn">Preset 3</button>
                <button onclick="presets('p12');" class="btn">Preset 4</button>
                <button onclick="presets('p13');" class="btn">Preset 5</button>
                <?php 
                } elseif ($grid_size=='10'){ 
                ?>
                <button onclick="presets('p1');" class="btn">Preset 1</button>
                <button onclick="presets('p3');" class="btn">Preset 2</button>
                <button onclick="presets('p4');" class="btn">Preset 3</button>
                <button onclick="presets('p8');" class="btn">Preset 4</button>
                <?php 
                } else {
                ?>
                <button onclick="presets('p5');" class="btn">Preset 1</button>
                <button onclick="presets('p2');" class="btn">Preset 2</button>
                <button onclick="presets('p6');" class="btn">Preset 3</button>
                <button onclick="presets('p7');" class="btn">Preset 4</button>
                <?php 
                }
                 ?>
                <label for="grid">Grid</label>
                <select name="grid" id="gridsize" width="5">
                    <option value="<?php print $this_page; ?>?grid=5" <?php if($grid_size =='5'){ print " selected "; } ?> >5</option>
                    <option value="<?php print $this_page; ?>?grid=10" <?php if($grid_size =='10'){ print " selected "; } ?> >10</option>
                    <option value="<?php print $this_page; ?>?grid=20" <?php if($grid_size =='20'){ print " selected "; } ?> >20</option>
                </select>
                <a class="button" href="tests.php">Tests</a>
           
                <button class="debug-toggle" onclick="show_debug();">Debug</button>
            </div>
            <div class="pane grid-<?php print $grid_size; ?>-display"><?php print $grid; ?></div>
            <?php print $debug; ?>
        </div>

        <div class="footer">
            <span>made by <a href="http://www.junglecreative.com/about-me">me</a></span>
        </div>
    </div> <!-- /.wrapper -->
</body>
</html>

<?php
} // end/else
?>