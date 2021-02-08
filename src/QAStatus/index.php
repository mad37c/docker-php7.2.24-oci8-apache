<?php
/**
 * Created by PhpStorm.
 * User: mad37c
 * Date: 5/30/2018
 * Time: 10:59 AM
 */
require 'inc/Operations.php';

$lineTotals= 0;

?>
<head>
    <meta http-equiv="refresh" content = "65" />
</head>
<link type="text/css" rel="stylesheet" href="inc/css/QAStatus.css" media="all" />
<body onload = "loadvalues();">

<div class="container">
    <div class="Line1-div">
        <div class="lineTitle">
            <h3> <label id = 'Line01' class ="Line01">Line 1 -- Gallons Available to Fill are </label> </h3>
        </div>
        <?php
        echo'<div class="separate"> FILLING </div>';
        $lineTotals = $_operations->show_operational_stage('FILL', 'Line01');
        echo'<div class="separate"> QA COMPLETE </div>';
        $lineTotals = $lineTotals  + $_operations->show_operational_stage('QAC', 'Line01');
        echo'<div class="separate"> QA </div>';
        $_operations->show_operational_stage('QA', 'Line01');
        ?>
        <input id = "line01amount" type = "hidden" value = "<?php echo $lineTotals; ?>"</input>
    </div>

    <div class="Line2-div">
        <div class="lineTitle">
            <h3> <Label id = 'Line02' class ="Line02">Line 2 -- Gallons Available to Fill are </Label> </h3>
        </div>
        <?php
        echo'<div class="separate"> FILLING </div>';
        $lineTotals =$_operations->show_operational_stage('FILL', 'Line02');
        echo'<div class="separate"> QA COMPLETE </div>';
        $lineTotals =$lineTotals  + $_operations->show_operational_stage('QAC', 'Line02');
        echo'<div class="separate"> QA </div>';
        $_operations->show_operational_stage('QA', 'Line02');
        ?>
        <input id = "line02amount" type = "hidden" value = "<?php echo $lineTotals; ?>"</input>
    </div>

    <div class="Line7-div">
        <div class="lineTitle">
            <h3> <Label id = 'Line07' class ="Line07">Line 7 -- Gallons Available to Fill are </Label> </h3>
        </div>
        <?php
        echo'<div class="separate"> FILLING </div>';
        $lineTotals =$_operations->show_operational_stage('FILL', 'Line07');
        echo'<div class="separate"> QA COMPLETE </div>';
        $lineTotals  = $lineTotals + $_operations->show_operational_stage('QAC', 'Line07');
        echo'<div class="separate"> QA </div>';
        $_operations->show_operational_stage('QA', 'Line07');
        ?>
        <input id = "line07amount" type = "hidden" value = "<?php echo $lineTotals; ?>"</input>
    </div>
</div>
<div class="container">
    <div class="Line6and8-div">
        <div class="lineTitle">
            <h3> <Label id = 'Line6and8' class ="Line6and8">Lines 6 And 8 -- Gallons Available to Fill are </Label> </h3>

        </div>
        <?php
        echo'<div class="separate"> FILLING </div>';
        $lineTotals= $_operations->show_operational_stage('FILL', 'Line06And08');
        echo'<div class="separate"> QA COMPLETE </div>';
        $lineTotals=$lineTotals+$_operations->show_operational_stage('QAC', 'Line06And08');
        echo'<div class="separate"> QA </div>';
        $_operations->show_operational_stage('QA', 'Line06And08');
        ?>
        <input id = "line6and8amount" type = "hidden" value = "<?php echo $lineTotals; ?>"</input>
    </div>
    <div class="Line9-div">
        <div class="lineTitle">
            <h3> <Label id = 'Line09' class ="Line09">Line 9 -- Gallons Available to Fill are </Label> </h3>
        </div>
        <?php
        echo'<div class="separate"> FILLING </div>';
        $lineTotals= $_operations->show_operational_stage('FILL', 'Line09');
        echo'<div class="separate"> QA COMPLETE </div>';
        $lineTotals = $lineTotals+ $_operations->show_operational_stage('QAC', 'Line09');
        echo'<div class="separate"> QA </div>';
        $_operations->show_operational_stage('QA', 'Line09');
        ?>
        <input id = "line09amount" type = "hidden" value = "<?php echo $lineTotals; ?>"</input>
    </div>
    <div class="Line10-div">
        <div class="lineTitle">
            <h3> <Label id = 'Line10' class ="Line10">Line 10 -- Gallons Available to Fill are </Label> </h3>
        </div>
        <?php
        echo'<div class="separate"> FILLING </div>';
        $lineTotals=$_operations->show_operational_stage('FILL', 'Line10');
        echo'<div class="separate"> QA COMPLETE </div>';
        $lineTotals=$lineTotals+$_operations->show_operational_stage('QAC', 'Line10');
        echo'<div class="separate"> QA </div>';
        $_operations->show_operational_stage('QA', 'Line10');
        ?>
        <input id = "line10amount" type = "hidden" value = "<?php echo $lineTotals; ?>"</input>
    </div>
    </div>
</div>
</body>
<script>
function loadvalues(){

    let flashNumber =2;

    let amounts = document.getElementById('line01amount').value;
    document.getElementById('Line01').textContent = 'Line 1 -- Gallons Available to Fill are '+ parseInt(amounts).toLocaleString()+ ' (' + Number((amounts/271).toFixed(2)) + ' hrs)';
    if ((amounts/271*10)/10<flashNumber) {
        document.getElementById('Line01').className += " divtoBlink";}

    amounts = document.getElementById('line02amount').value;
    document.getElementById('Line02').textContent = 'Line 2 -- Gallons Available to Fill are '+parseInt(amounts).toLocaleString()+  ' (' + Number((amounts/271).toFixed(2))  + ' hrs)';
    if ((amounts/271*10)/10<flashNumber) {
        document.getElementById('Line02').className += " divtoBlink";}

    amounts = document.getElementById('line07amount').value;
    document.getElementById('Line07').textContent = 'Line 7 -- Gallons Available to Fill are '+parseInt(amounts).toLocaleString()+   ' (' + Number((amounts/958).toFixed(2))  + ' hrs)';
    if ((amounts/958*10)/10<flashNumber) {
        document.getElementById('Line07').className += " divtoBlink";}

    amounts = document.getElementById('line6and8amount').value;
    document.getElementById('Line6and8').textContent = 'Lines 6 And 8 -- Gallons Available to Fill are '+parseInt(amounts).toLocaleString()+   ' (' + Number((amounts/3250).toFixed(2))  + ' hrs)';
    if ((amounts/3250*10)/10<flashNumber) {
        document.getElementById('Line6and8').className += " divtoBlink";}

    amounts = document.getElementById('line09amount').value;
    document.getElementById('Line09').textContent = 'Line 9 -- Gallons Available to Fill are '+parseInt(amounts).toLocaleString()+   ' (' + Number((amounts/958).toFixed(2)) + ' hrs)';
    if ((amounts/958*10)/10<flashNumber) {
        document.getElementById('Line09').className += " divtoBlink";}

    amounts = document.getElementById('line10amount').value;
    document.getElementById('Line10').textContent = 'Lines 10 -- Gallons Available to Fill are '+parseInt(amounts).toLocaleString()+   ' (' + Number((amounts/167).toFixed(2))  + ' hrs)';
    if ((amounts/167*10)/10<flashNumber) {
        document.getElementById('Line10').className += " divtoBlink";}
}

</script>