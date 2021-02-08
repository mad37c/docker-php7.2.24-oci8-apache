<?php
/**
 * Created by PhpStorm.
 * User: mad37c
 * Date: 5/28/2018
 * Time: 10:19 AM
 */

namespace Oracle;

require('config.php');
require('connection.php');


class Operations {

    /**
     * @var string
     */
    private $self_file = 'Operations.php';
    /**
     * @var bool
     */
    private $stage = false;
    /**
     * @var bool
     */
    private $_db = false;

    public function __construct(){

    }

    function set_operational_stage($new_operational_stage){
        $this->stage = $new_operational_stage;
    }

    function get_operational_stage(){
        return $this->stage;
    }

    public function initializeDatabaseConnection(){

       Db::initialize();
    }

    public function show_operational_stage($stage,$line){


        If ($line=="Line02" || $line=="Line07" || $line=="Line10") {
            $sizeCode='16';
        }
        else{
            $sizeCode='20';
        }

        If ($line == "Line01" || $line == "Line02"){
            $TypeClause="and NOT(a.manufacturing_center = '1W' or a.manufacturing_center = '01' or a.manufacturing_center = '1C')";
        }
        else {
            $TypeClause="and (a.manufacturing_center = '1W' or a.manufacturing_center = '01' or a.manufacturing_center = '1C')";

            if ($line=="Line07") {
                if ($stage =="FILL"){
                    $TypeClause = $TypeClause . " and ((d.part_comment not like '%10%' or d.part_comment is null) or a.batch_size >= 3100) and b.manufacturing_equipment  like '%10%'";
                }
                else {
                    $TypeClause = $TypeClause . " and ((d.part_comment not like '%10%') or a.batch_size >= 3100) ";
                }
            }
            elseif ($line=="Line09") {

                $TypeClause = $TypeClause." and (a.batch_Size < 3100 or (((d.part_comment <>'Line 8' and d.part_comment <>'line 8') or d.part_comment is null)  and  a.batch_Size >=3100)) ";
            }
            elseif ($line=="Line10") {

                if ($stage =="FILL") {
                    $TypeClause = $TypeClause . " and ( d.part_comment like '%10%' or b.manufacturing_equipment  like '%10%') ";
                }
                else{
                    $TypeClause = $TypeClause . " and ( d.part_comment like '%10%') ";
                }
            }
            else{

                $TypeClause = $TypeClause." and  (d.part_comment like '%8%'  and a.batch_size >3100)";
            }

        }

        $this->initializeDatabaseConnection();

        $sql = "SELECT a.master_rex, a.order_number, a.batch_size, a.batch_status_descr, a.machine_code,c.size_code, a.manufacturing_center, d.part_comment, b.manufacturing_equipment as Line, b.cycle_number FROM mfg_Batch_order  a
                join (select order_number,machine_code, size_code from mfg_fill_order group by order_number, machine_code, size_code) c on a.order_number= c.order_number
                join (select part_number, part_comment from mfg_part_master) d on a.master_rex = d.part_number
                join (select order_number,cycle_number, manufacturing_equipment from mfg_batch_operation where cycle_number = :size_bv ) b on b.order_number = a.order_number
                where a.MASTER_REX not like 'R-%' and a.batch_status_descr = :status_bv and c.size_code = :size_bv ".$TypeClause;
        $res = oci_parse(Db::$conn,$sql);

        oci_bind_by_name($res,":status_bv",$stage);
        oci_bind_by_name($res,":size_bv",$sizeCode);

        oci_execute($res);

        $queryReturnsRecordFlag= 0;

        while($row = oci_fetch_array($res, OCI_ASSOC+OCI_RETURN_NULLS)){
            echo '<div class="MasterRex">';
                echo '<table>';
                    echo '<tr><td>'.$row['MASTER_REX'].' '.$row['ORDER_NUMBER'].'</td></tr><tr><td>' .$row['MACHINE_CODE'].' Batch Size '.$row['BATCH_SIZE'].'</td></tr>';
                echo '</table>';

            if ($stage =='QA'){
                $this->currentStatusInLab($row['ORDER_NUMBER']);
            }
            echo '</div>';
            $queryReturnsRecordFlag++;
        }

        if ($queryReturnsRecordFlag ==0) {
            echo '<div class="MasterRex">';
            echo '<table>';
            echo '<tr><td>No Batch in '.$stage.' </td></tr>';
            echo '</table>';

            if ($stage =='QA' and $queryReturnsRecordFlag <>0) {
                $this->currentStatusInLab($row['ORDER_NUMBER']);
            }
            echo '</div>';

        }

    }

    private function currentStatusInLab($orderNum) {

        $sql = "select cycle_number, TO_CHAR(start_date_time,'YYYY-MM-DD HH24:MI:SS') as DT, TO_CHAR(complete_date_time,'YYYY-MM-DD HH24:MI:SS') as CT  from mfg_batch_operation where operation_number = '050' and Order_number = :order_bv order by cycle_number desc FETCH FIRST 2 ROWS ONLY";
        $res = oci_parse(Db::$conn,$sql);

        oci_bind_by_name($res,":order_bv",$orderNum);

        oci_execute($res);

        $row = oci_fetch_array($res, OCI_ASSOC+OCI_RETURN_NULLS);

        if($row['DT']==""){
            $row = oci_fetch_array($res, OCI_ASSOC+OCI_RETURN_NULLS);
            $this->currentPostAdd($orderNum, $row['CYCLE_NUMBER'],$row['CT']);
        }else{

            $hoursOnCycle = $this->timeBetweenStamps($row['DT']);

            if($hoursOnCycle >=2 )
                echo '<table class="divtoBlink">';
            else
                echo '<table>';

            echo '<tr><td> On Cycle ' .$row['CYCLE_NUMBER'] . '  for '.$hoursOnCycle.' hrs</td></tr>';
            echo '</table>';
        }

    }

    private function currentPostAdd($orderNum,$cycleNum,$CompleteTime) {

        $sql = "select raw_material, quantity from mfg_ebr_raw_add where order_number =:order_bv and cycle_number = :cycle_bv";
        $res = oci_parse(Db::$conn,$sql);

        oci_bind_by_name($res,":order_bv",$orderNum);
        oci_bind_by_name($res,":cycle_bv",$cycleNum);



            $hoursOnPostAdd = $this->timeBetweenStamps($CompleteTime);

            oci_execute($res);
            if($hoursOnPostAdd >=1 and  $cycleNum<>Null)
                echo '<table class="divtoBlink">';
            else
                echo '<table>';

            if ($cycleNum<>Null) {
                echo '<tr><td> Post-Add Cycle ' . $cycleNum . ' for ' . $hoursOnPostAdd . ' hrs </td></tr>';
            }else{
                echo '<tr><td> Batch is on 00 Cycle </td></tr>';
            }
            echo '</table>';



        //While($row = oci_fetch_array($res, OCI_ASSOC+OCI_RETURN_NULLS)) {
           // echo '<table>';
           // echo '<tr><td>' . $row['RAW_MATERIAL'] . '</td><td>' . $row['QUANTITY'] . '</td></tr>';
           // echo '</table>';
        //}

    }

    private function timeBetweenStamps($theTimeStamp){

        $d1 = strtotime(date('Y-m-d H:i:s'));
        $d2 = strtotime(date('Y-m-d H:i:s', strtotime($theTimeStamp)));

        return Round(($d1-$d2)/3600,1);
    }




    public function __destruct() {
      //  if(is_resource($this->_db) || get_resource_type($this->_db) == 'mysql link')
            /** @noinspection PhpUndefinedMethodInspection */
      //      $this->_db->close();
    }
}


    $_operations = new Operations;
