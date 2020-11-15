<?php
require_once "connectDB.php";

$jdata = array();
$tool_data = array();
$tool_img = array();
$res = null;
$sta = "0";

$sql = "SELECT `No`, `Name`, `Position`, `Shape` FROM `gripper`";
$res = SQL($db, $sql);
$res->execute();
if ($res->rowCount() > 0) {
    $sta = "1";
    foreach ($res->fetchAll(PDO::FETCH_ASSOC) as $v) {
        $tool_data[] = $v;
    }
} else {
    $sta = "0";
}
$jdata["0"] = array("sta" => $sta, "tool_data" => $tool_data);


echo json_encode($jdata);
