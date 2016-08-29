<?php
/*
 * Robot endpoint puzzle solution testing suite
 *
 * 
 */

include "inc/helpers.inc";

//$this_page = $_SERVER['REQUEST_URI'];
//$this_page = curr_page();
//$testrun = $_GET['test'];


?><!DOCTYPE html>
<html lang="en-US"><head>
    <meta charset="UTF-8">
    <title>Toy Robot Simulator Tests</title>
    <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link type="text/css" href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>if (!window.jQuery) { document.write('<script src="/js/jquery.min.js"><\/script>'); }</script>
    <script src="js/scripts.js"></script>
</head>
<body class="tests">
    <div class="wrapper">
        <div class="header">
            <h1>Toy Robot Simulator Test</h1>
            <a class="button" href="index.php">< Back to Simulator</a>
            <button class="debug-toggle" onclick="show_test_debug();">Show Debug Messages</button>
        </div>
        <div class="form-horizontal simulator">

                
                <div class="form-actions">

                    <button onclick="run_tests();" class="btn btn-primary">Run tests</button>
                </div>

                <table width="100%" cellspacing="2" cellpadding="2" id="test_results">
                    <thead>
                        <tr><th>Test Num</th><th>Data</th><th>Grid</th><th>Expected</th><th>Result</th><th>Errors</th></tr>
                    </thead>
                    <tfoot>
                        <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                    </tfoot>
                    <tbody>
                        <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                    </tbody>
                    
                </table>

                <span><?php //print $output; ?></span>

        </div>



        <div class="output">
            
            <div class="pane"></div>
            <?php //print $debug; ?>
        </div>

        <div class="footer">
            <span>made by <a href="http://www.junglecreative.com">me</a></span>
        </div>

    </div> <!-- /.wrapper -->

    <script type="text/javascript">
    /*
     USAGE : ?test=true&submit=1&command=PLACE%201,2,EAST%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AREPORT
     */
     $(function(){
        console.log('page script init');

        // Reload page on grid size select change
        $('select#gridsize').change(function(e){
            window.location.href = $("select#gridsize option:selected").attr('value');
        });
    });
        
        function run_tests() {
            console.log("init tests");

            var testData = new Array();
            testData = [
                        ["1","5","PLACE 4,3,NORTH%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AREPORT","out of bounds"],
                        ["2","5","PLACE 1,1,EAST%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0ARIGHT%0AREPORT","2,3,north"],
                        ["3","5","PLACE 0,1,NORTH%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ALEFT%0AREPORT","out of bounds"],
                        ["4","10","PLACE 8,5,WEST%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AREPORT","4,8,south"],
                        ["5","5","PLACE 0,1,NORTH%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AREPORT","3,3,north"],
                        ["6","5","PLACE 1,1,NORTH%0AMOVE%0ARIGHT%0AMOVE%0APLACE 3,1,EAST%0AMOVE%0ALEFT%0AMOVE%0AREPORT","4,2,north"],
                        ["7","10","PLACE 1,2,NORTH%0ARIGHT%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0APLACE 3,1,EAST%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AREPORT","4,3,north"],
                        ["8","20","PLACE 0,1,NORTH%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AREPORT","4,0,west"],
                        ["9","20","PLACE 3,5,NORTH%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AREPORT","15,4,west"],
                        ["10","20","PLACE 2,7,NORTH%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0APLACE 10,2,EAST%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0APLACE 7,7,WEST%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AREPORT","11,13,north"],
                        ["11","20","PLACE 15,3,WEST%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0ARIGHT%0AMOVE%0AMOVE%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AMOVE%0AREPORT","15,11,east"]
                    ];

     
      







            //console.log("form submit");
            //event.preventDefault();
            //validate submitted data
            //var $this = $(this);
            //var url = $this.attr('action');
            var url = 'index.php';
            var data_init ='test=true&submit=1'; //&tid=1&grid=5&command=';
            //PLACE%201,2,EAST%0AMOVE%0AMOVE%0ALEFT%0AMOVE%0AREPORT';
            //var dataToSend = $this.serialize();

            var data; // = data_init + testData[0][0];

            //usage: array($tid, $file_debug, $command_string, $output_txt, $err_output, $debug);
            var callback = function(dataReceived) {
                console.log("callback fired");
                //receive HTML snippet
                //$('.pane').append(dataReceived);
                $('.pane').append(dataReceived[5]);
                $('.alert').fadeIn();
                $('.debug-toggle').fadeIn();

                var testId = dataReceived[0];

                var tableRow = "<td>"+testId+"</td>" +
                                "<td>"+dataReceived[2]+"</td>" +
                                "<td>"+testData[testId-1][1]+"</td>" +
                                "<td>"+testData[testId-1][3]+"</td>" +
                                "<td>"+dataReceived[3]+"</td>" +
                                "<td>"+dataReceived[4]+"</td>";

                $('#test_results > tbody:last-child').append('<tr>'+tableRow+'</tr>');

/*
                var table=document.getElementById('test_results').getElementsByTagName('tbody')[0];
                var row=table.insertRow(table.rows.length);
/*
                for (var i = 0; i <= testData.length; i++) {
                    testData[i]
                };
*/
                // test id
 /*               var testId = dataReceived[0];

                var cell1=row.insertCell(0);
                var newText  = document.createTextNode(testId);
                cell1.appendChild(newText);

                // test command
                var cell2=row.insertCell(1);
                var newText  = document.createTextNode(dataReceived[2]);
                cell2.appendChild(newText);

                // grid size
                var cell3=row.insertCell(2);
                var newText  = document.createTextNode(testData[testId-1][1]);
                cell3.appendChild(newText);

                // expected result
                var cell4=row.insertCell(3);
                var newText  = document.createTextNode(testData[testId-1][3]);
                cell4.appendChild(newText);

                // actual result
                var cell5=row.insertCell(4);
                var newText  = document.createTextNode(dataReceived[3]);
                cell5.appendChild(newText);

                // result icon - display based on null/none-null value returned
                var cell6=row.insertCell(5);
                /*
                var iconSpan = document.createElement("span");
                if(dataReceived[4]!=''){
                    iconSpan.className("pass");
                    iconSpan.innerHtml = "pass";
                }else{
                    iconSpan.className("fail");
                    iconSpan.innerHtml = "fail";
                }
                */
   //             var newSpan  = document.createElement('span');
     //           cell6.appendChild(newSpan);


            }
            var typeOfDataToReceive = 'json';

            for(var i=0; i<=testData.length; i++){

                //var testId = $i;
                var testrun = testData[i][0];
                var gridsize = testData[i][1];
                var data = testData[i][2];
                var expected = testData[i][3];

                //Format the urL string
                var data = data_init + "&tid=" + testData[i][0] + "&grid=" + testData[i][1] + "&command=" + testData[i][2];

                // Pass to the test script
                //setTimeout(function() {
                    $.get(url, data, callback, typeOfDataToReceive); 
                //}, 1);
                
            }
            
        };
        
    </script>


</body></html>
