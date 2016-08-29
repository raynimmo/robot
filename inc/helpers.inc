<?php
/*
 * Helper functions referenced from index.php
 */

/*
 * Build grid display
 */
function build_grid($grid_size, $start_ypos, $new_ypos, $start_xpos, $new_xpos, $start_facing, $end_facing, $movement_commands, $moves, $place_start, $place_end){
    for($i = $grid_size-1; $i >= 0; $i--){
        $grid_lines .='<li class="line-'.$i.'"><span class="y_linenum">'.$i.'</span>';
            $cells = '';
        //add cells to grid
        for($j = 0; $j < $grid_size; $j++){
            $cells .= '<span class="cell-'.$j;
            // Mark starting points
            for($n=0; $n <= sizeof($place_start)-1; $n++){
                if(($i==$place_start[$n][1]) && ($j==$place_start[$n][0])) {
                    if(($place_start[$n][0] != "") && ($place_start[$n][1] != "")){
                        //starting point on grid
                        $cells .= ' begin '.$place_start[$n][2];
                    }
                }
            }
            // Mark end points
            for($m=0; $m <= sizeof($place_end)-1; $m++){
                if(($i==$place_end[$m][1]) && ($j==$place_end[$m][0])){
                    //ending point on grid
                    if((sizeof($place_end[$m][0]) > 0) && (sizeof($place_end[$m][1]) > 0)){
                        $cells .= ' end '.$place_end[$m][2];
                    }
                }
            }
            //check path array for current grid position
            for ($k=0; $k <= sizeof($moves); $k++) { 
                if(($i==$moves[$k][1]) && ($j==$moves[$k][0])){
                    if(!empty($moves[$k][0]) || !empty($moves[$k][1])){
                        //on the path
                        $cells .= ' path ';
                    }
                }
            }
            $cells .='">';
            if ($i==0) {
                $cells .= '<span class="x_linenum">'.$j.'</span>';
            }
            $cells .= '</span>';
        }
        $grid_lines .= $cells."</li>";
    }
    $grid_output = "<ul>".$grid_lines."</ul>";

    return $grid_output;
}

/*
 * Move robot position
 */
function move_position($i, $facing, $start_ypos, $new_ypos, $start_xpos, $new_xpos, $movement_commands, $moves) {
    // Move robot one unit towards $facing direction
    $cmd_debug .= "<br />[".$i."] *** move_position() <br />";
    $cmd_debug .= "[".$i."] *** Y1[".$start_ypos."] > Y2[".$new_ypos."]<br />[".$i."] *** X1[".$start_xpos."] > X2[".$new_xpos."]<br />";

    switch($facing){
        case 'north':         
            if ($new_ypos === 0) {
                $new_ypos = $start_ypos+1;
            }else{
                $new_ypos = $new_ypos+1;
            }

            $cmd_debug .= "[".$i."] *** ".$movement_commands." > ".$facing.", y > ".$new_ypos."<br />";
        break;

        case 'east':                
            if ($new_xpos === 0) {
                $new_xpos = $start_xpos+1;
            }else{
                $new_xpos = $new_xpos+1;
            }

            $cmd_debug .= "[".$i."] *** ".$movement_commands." > ".$facing.", x > ".$new_xpos."<br />";
        break;

        case 'south':  
            $tempvar .="s";        
            //if (($new_ypos === 0) && ($moves[$i-1][1] === 0)) {
            if (($new_ypos === 0) && $i === 0){
                $new_ypos = $start_ypos-1;
                $tempvar .="a";
            }elseif (($new_ypos === 0) && ($moves[$i-1][1] > 0)) {
                $new_ypos = $new_ypos-1;
                $tempvar .="b";
            }else{
                $new_ypos = $new_ypos-1;
                $tempvar .="c";
            }

            $cmd_debug .= $tempvar." [".$i."] *** ".$movement_commands." > ".$facing.", y > ".$new_ypos."<br />";
        break;

        case 'west':        
            if (($new_xpos === 0) && ($moves[$i-1][0] != 0)) {
                $new_xpos = $start_xpos-1;
                $cmd_debug .= "<br /> opt A [".$i."][".$moves[$i-1][0]." > ".$moves[$i][0]."]<br />";
            }else{
                $new_xpos = $new_xpos-1;
                $cmd_debug .= "<br /> opt B<br />";
            }

            $cmd_debug .= "[".$i."] *** ".$movement_commands." > ".$facing.", x > ".$new_xpos."<br />";
        break;
    }

    $move_result = array();
    $move_result[0] = $new_xpos;
    $move_result[1] = $new_ypos;
    
    $cmd_debug .= "[".$i."] *** Move Result: >>> xpos[".$move_result[0]."] ypos[".$move_result[1]."]<br />";
    
    $move_result[2] = $cmd_debug;

    return $move_result;
}

/*
 * Function to record all move data within arrays for later use
 */
function save_move($i, $new_xpos, $new_ypos, $start_xpos, $start_ypos, $facing, $moves){
    $tempvar='';

    if(($new_xpos === 0) && ($start_xpos === 0)){
        $moves[$i][0] = $new_xpos;
        $tempvar .= " a ";
    }else if($new_xpos > 0){
        $moves[$i][0] = $new_xpos;
        $tempvar .= " b ";
    }elseif(($new_xpos === 0) && ($moves[$i-1][0] > 0)){
        $moves[$i][0] = $new_xpos;
        $tempvar .= " c ";
    }elseif(($new_xpos === 0) && ($moves[$i-1][0] === 0)){
        $moves[$i][0] = $new_xpos;
        $tempvar .= " d ";
    }else{
        $moves[$i][0] = $start_xpos;
        $tempvar .= " e ";
    }

    if(($new_ypos === 0) && ($start_ypos === 0)){
        $moves[$i][1] = $new_ypos;
        $tempvar .= " f ";
    }else if($new_ypos > 0){
        $moves[$i][1] = $new_ypos;
        $tempvar .= " g ";
    }elseif(($new_ypos === 0) && ($moves[$i-1][1] > 0)){
        $moves[$i][1] = $new_ypos;
        $tempvar .= " h ";
    }elseif(($new_ypos === 0) && ($moves[$i-1][1] === 0)){
        $moves[$i][1] = $new_ypos;
        $tempvar .= " i ";
    }elseif(($new_ypos == 0) && ($moves[$i][1] == 0)){
        $moves[$i][1] = $new_ypos;
        $tempvar .= " j ";
    }else{
        $moves[$i][1] = $start_ypos;
        $tempvar .= " k ";
    }

    $cmd_debug .= "[".$i."] >>> Record Move B<br />[".$i."] >>> tempvar: ".$tempvar."<br />";
    $moves[$i][2] = $facing;

    $cmd_debug .= "[".$i."] >>> x=".$moves[$i][0].", y=".$moves[$i][1].", f=".$moves[$i][2]."<br />";

    return array($moves, $cmd_debug);
}

/*
 * Turn left and determine new facing direction
 */
function turn_left($facing){
    switch($facing){
        case 'north':
            $new_facing = 'west';
        break;
        case 'east':
            $new_facing = 'north';
        break;
        case 'south':
            $new_facing = 'east';
        break;
        case 'west':
            $new_facing = 'south';
        break;
    }
    return $new_facing;
}

/*
 * Turn right and determine new facing direction
 */
function turn_right($facing){
    switch($facing){
        case 'north':
            $new_facing = 'east';
        break;
        case 'east':
            $new_facing = 'south';
        break;
        case 'south':
            $new_facing = 'west';
        break;
        case 'west':
            $new_facing = 'north';
        break;
    }
    return $new_facing;
}

/*
 * File upload handler
 */
function upload_file($file, $file_debug, $valid_file){
    if($_FILES['fileupload']['name']){
        $file_debug .= "<br />init upload_file()<br /> --> ".$_FILES['fileupload']['name'];

        if(!$_FILES['fileupload']['error']) {
            //rename uploaded file
            $new_file_name = strtolower($_FILES['file']['tmp_name']); 
            //check the size
            if($_FILES['fileupload']['size'] > (1024)) {
                $valid_file = false;
                $file_debug .= "Your file's size is to large.<br />";
            }
            //if the file has passed the test
            if($valid_file) {
                //move it to where we want it to be
                $currentdir = getcwd();
                $target = $currentdir .'/upload/' . basename($_FILES['fileupload']['name']);
                move_uploaded_file($_FILES['fileupload']['tmp_name'], $target);

                $file_debug .= "<br />Your file was accepted.<br />";
                $file = fopen($target,"r") or exit("<br />Unable to open file!<br />"); 

                while(!feof($file)) {
                    $file_contents .= fgetc($file);
                }
            }
        } else {
            //set that to be the returned message
            $file_debug .= '<br />Upload error:  '.$_FILES['fileupload']['error'];
        }
        $file_debug .= "<br />read from file<br /> => ".$file_contents;
    }

    $upload_result = array();
    $upload_result[0] = $valid_file;
    $upload_result[1] = $file_debug;
    $upload_result[2] = $file_contents;

    return $upload_result;
}

/*
 * Check boundary function
 */
function check_bounds($i, $moves, $move_result, $grid_size, $facing, $cmd_debug, $movement_commands){
    $move_validity = false;
    $cmd_debug .= "[".$i."] *** check_bounds()<br />";
    $cmd_debug .= "[".$i."] *** ".$moves[$i-1][0]." < 0 | ".$move_result[0]." >= ".$grid_size." | ".$move_result[1]." < 0 | ".$move_result[1]." >= ".$grid_size."<br />";

    if($moves[$i-1][0] < 0 | $move_result[0] >= $grid_size | $move_result[1] < 0 | $move_result[1] >= $grid_size | $move_result[0] < 0){
        $move_validity = false;
        $cmd_debug .= "[".$i."] ** Test Fail @ check_bounds() test A<br />";

    }elseif($moves[$i][0] == '' && $moves[$i-1][0] == 0){
        $move_validity = true;
        $cmd_debug .= "[".$i."] ** Test Pass @ check_bounds() A<br />";
    }else{
        if ($facing==='south' && $moves[$i-1][1]==0 && $movement_commands[$i]!='report') {
                $move_validity = false;
                $cmd_debug .= "[".$i."] ** Test Fail @ check_bounds() test C<br />";

        }elseif ($facing==='west' && $move_result[0] < 0) {
                $move_validity = false;
                $cmd_debug .= "[".$i."] ** Test Fail @ check_bounds() test D<br />";
        }else{
            $move_validity = true;
            $cmd_debug .= "[".$i."] ** Test Pass @ check_bounds() B<br />";
        }
    }
    if($move_validity){
        $cmd_debug .= "[".$i."] ** Valid Move **<br />";
    }else{
        $cmd_debug .= "[".$i."] ** Invalid Move **<br />";
            
    }
    return array($move_validity, $cmd_debug);
}

/*
 * Return current page name
 */
function curr_page() {
    return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

/*
 * Display reporting box according to conditions
 */
function report_display($type, $header, $txt){
    if($type=='error'){
        $txt = '<br /><p>'.$txt.'</p>';
    }
    $report_display = '<div class="alert alert-'.$type.'"><strong>' . $header . '</strong> '.$txt.'</div>';

    return $report_display;
}

/*
 * Function to determine output report message
 */
function report_build($display_report, $move_validity, $hasError, $invalidPlace, $output_txt, $err_ouput){
    if($display_report) {

        if($move_validity && !$hasError){
            $report_type = "info";
            $report_header = "Robot Position";

        }elseif($hasError && !$invalidPlace){
            $output_txt = "<br />".$err_ouput."<br /><br />Robot Position at REPORT command: ".$output_txt;
            $report_type = "error";
            $report_header = "Invalid Submission A";

        }elseif($hasError && $invalidPlace){
            $output_txt = "<br />".$err_ouput."<br /><br />Robot Position at last valid move command: ".$output_txt."<br />".$alt_move;
            $report_type = "error";
            $report_header = "Invalid Submission B";
        
        }elseif($invalidPlace){
            $report_type = "warning";
            $report_header = "Robot Position Out Of Bounds.<br />".$alt_move;

        }
    }elseif($hasError && !$display_report){
        $output_txt = $err_output;
        $report_type = "error";
        $report_header = "Invalid submission C";

    }else{
        $output_txt = "Submission did not include a REPORT command to display output.";
        $report_type = "error";
        $report_header = "Invalid submission D";

    }

    return array($output_txt, $report_type, $report_header);
}

/*
 * Function to suggest alternate move when hitting edge
 */
function suggest_moves($border, $value, $grid_size) {
    if(($value < $grid_size-1) && ($value > 0)){
        $alt_move_opts = "left, right";
    }else{
    switch ($border) {
        case 'north':
        case 'west':
            if($value==$grid_size-1){
                $alt_move_opts = "left";
            }elseif($value==0){
                $alt_move_opts = "right";
            }
        break;

        case 'south':
        case 'east':
            if($value==$grid_size-1){
                $alt_move_opts = "right";
            }elseif($value==0){
                $alt_move_opts = "left";
            }
        break;

        default:
            # code...
        break;
    }
    }                              
    $alt_move = "Available moves: ".$alt_move_opts;
    $cmd_debug = "<br />[error] Hitting the ".$border." border<br />alternate: ".$alt_move;
    $err_output = "<br />Robot crossed the ".$border." edge.<br />"; //.$alt_move;

    return array($alt_move, $cmd_debug, $err_output);
}

/*
 * Debug output controller
 */
function debug_output($type, $debugvars){
    switch ($type) {
        case 'init' :
            $init = $debugvars[0];
            $line = $debugvars[1];
            $xpos = $debugvars[2];
            $ypos = $debugvars[3];

            $cmd_debug .= "init OK: ".$init."<br /> --> ".$line."<br /> --> start[0] : ".$xpos."<br /> --> start[1] : ".$ypos."<br />";

        break;

        case 'place':
            $i = $debugvars[0];
            $moves = $debugvars[1];
            $facing = $debugvars[3];
            $xpos = $debugvars[4];
            $ypos = $debugvars[5];
            $grid_size = $debugvars[6];

            $cmd_debug .= "Valid PLACE @: ".$xpos.",".$ypos." using ".$grid_size."x".$grid_size." grid.<br />";
            $cmd_debug .= "[".$i."] >>> start: ".$xpos.", ".$ypos.", ".$facing."<br />";
            $cmd_debug .= "[".$i."] >>> move: ".$moves[0][0].",".$moves[0][1]."<br />";
            $cmd_debug .= "[".$i."] >>> place: ".$xpos.",".$ypos.",".$facing."<br />";

        break;

        case 'report':
            $i = $debugvars[0];
            $moves = $debugvars[1];
            $movement_commands = $debugvars[2];
            $facing = $debugvars[3];
            $xpos = $debugvars[4];
            $ypos = $debugvars[5];
            
            $cmd_debug .= "<br />[".$i."] >>> Detected 'REPORT' command.<br />";
            $cmd_debug .= "[".$i."] => Current position: ".$xpos.",".$ypos.",".$facing."<br />";
            $cmd_debug .= "[".$i."] => Previous position:".$moves[$i-1][0].",".$moves[$i-1][1].",".$moves[$i-1][2]."<br />";
            $cmd_debug .= "[".$i."] => Current command: ".$movement_commands[$i]."<br />";

        break;
        
        case 'move':
            $i = $debugvars[0];
            $new_xpos = $debugvars[1];
            $new_ypos = $debugvars[2];
            $moved_xpos = $debugvars[3];
            $moved_ypos = $debugvars[4];

            $cmd_debug .= "<br /> -----<br />[".$i."] >>> Start move ".$i."...";
            $cmd_debug .= "<br />[".$i."] >>> Validate @ switch(move) : <br />[".$i."] >>> xpos[".$new_xpos." to ".$moved_xpos."]<br />[".$i."] >>> ypos[".$new_ypos." to ".$moved_ypos."]<br />";

        break;

        case 'turn':
            $i = $debugvars[0];
            $movement_commands = $debugvars[2];
            $facing = $debugvars[3];

            $cmd_debug ="<br />[".$i."] >>> Detected 'TURN' comamnd.<br /> [".$i."] ==> ".$movement_commands[$i]." > turn to ".$facing."<br />";

        break;

        case 'start':
            $i = $debugvars[0];
            $moves = $debugvars[1];
            $facing = $debugvars[3];
            $xpos = $debugvars[4];
            $ypos = $debugvars[5];

            $cmd_debug .= "<br />[".$i."] > Detected 'PLACE' command.<br />----<br /> place @ -> ".$xpos.",".$ypos."<br />";

            if($moves[$i-1][0]!==''){
                $cmd_debug .= "[".$i."] ***  not first PLACE<br />";
            }else{
                $cmd_debug .= "[".$i."] ***  first PLACE<br />";
            }
            $cmd_debug .= "[".$i."] > create start [1] = ".$xpos.", ".$ypos.", ".$facing."<br />";
            $cmd_debug .= "[".$i."] --> [1] Place recorded:".$xpos.",".$ypos.",".$facing."<br />";
            $cmd_debug .= "[".$i."] --> [1] Previous position:".$moves[$i-1][0].",".$moves[$i-1][1].",".$moves[$i-1][2]."<br />";

        break;

        case 'fulloutput':
            $i = $debugvars[0];
            $moves = $debugvars[1];
            $movement_commands = $debugvars[2];
            $place_start = $debugvars[3];
            $place_end = $debugvars[4];

            $cmd_debug .= "----<br />place_start[] array() : ".sizeof($place_start)." entries.<br />";
            for($n=0; $n <= sizeof($place_start); $n++){
                $cmd_debug .= " - start [".$n."] = ".$place_start[$n][0].",".$place_start[$n][1].",".$place_start[$n][2]."<br />";
            }
            
            $cmd_debug .= "moves[] array() : ".sizeof($movement_commands)." entries.<br />";
            for($i = 0; $i <= sizeof($movement_commands); $i++){
                $cmd_debug .= " - move [".$i."]->".$moves[$i][0].",".$moves[$i][1].",".$moves[$i][2]."<br />";
            }

            $cmd_debug .= "place_end[] array() : ".sizeof($place_end)." entries.<br />";
            for($n=0; $n <= sizeof($place_end); $n++){
                $cmd_debug .= " - end [".$n."] = ".$place_end[$n][0].",".$place_end[$n][1].",".$place_end[$n][2]."<br />";
            }
            $cmd_debug .= "----<br />";

        break;

        case 'invalid':
            $i = $debugvars[0];
            $facing = $debugvars[1];
            $moved_xpos = $debugvars[2];
            $moved_ypos = $debugvars[3];
            $err_output = $debugvars[4];

            $cmd_debug .= "<br />[".$i."] *** INVALID PLACEMENT: ".$moved_xpos.",".$moved_ypos.",".$facing."<br />[error] ".$err_output;
                                

        break;

        default:
            # code...
            break;
    }
    return $cmd_debug;
}
?>