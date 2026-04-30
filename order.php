<?php
ob_start();
session_start();
include 'condb.php';

// ตรวจสอบว่ามีการส่ง id สินค้ามาหรือไม่ เพื่อป้องกัน Error
if (!isset($_GET["id"]) || $_GET["id"] == "") {
    header("location:shop.php");
    exit;
}

// เช็คว่าตะกร้าสินค้าถูกสร้างขึ้นหรือยัง
if (!isset($_SESSION["intLine"])) {
    // กรณีเริ่มต้นตะกร้าครั้งแรก
    $_SESSION["intLine"] = 0;
    $_SESSION["strProductID"] = array(); // ประกาศให้เป็น Array ป้องกัน Fatal Error 
    $_SESSION["strQty"] = array();       // ประกาศให้เป็น Array
    
    $_SESSION["strProductID"][0] = $_GET["id"];
    $_SESSION["strQty"][0] = 1;
} else {
    if (!isset($_SESSION["strProductID"]) || !is_array($_SESSION["strProductID"])) {
        $_SESSION["strProductID"] = array();
        $_SESSION["strQty"] = array();
    }

    // ค้นหาว่ามีสินค้านี้อยู่ในตะกร้าแล้วหรือยัง
    $key = array_search($_GET["id"], $_SESSION["strProductID"]);
    
    if ((string)$key != "") {
        // ถ้ามีแล้ว ให้เพิ่มจำนวน 
        $_SESSION["strQty"][$key] = $_SESSION["strQty"][$key] + 1;
    } else {
        // ถ้ายังไม่มี ให้เพิ่มแถวใหม่ 
        $_SESSION["intLine"] = $_SESSION["intLine"] + 1;
        $intNewLine = $_SESSION["intLine"];
        $_SESSION["strProductID"][$intNewLine] = $_GET["id"];
        $_SESSION["strQty"][$intNewLine] = 1;
    }
}

header("location:cart.php");
exit;
?>