<?php
$file = file_get_contents('./gift.php');
$file = preg_replace(
        "/mysql_fetch_array\(mysql_query\((.*)\)\)\)/",
        "\$mc->query($1)->fetch_array(MYSQLI_ASSOC))",
        $file);
$file = preg_replace(
        "/mysql_fetch_assoc\(mysql_query\((.*)\)\)\)/", 
        "\$mc->query($1)->fetch_array(MYSQLI_ASSOC))",
        $file);
$file = preg_replace(
        "/mysql_fetch_array\(mysql_query\((.*)\)\)/",
        "\$mc->query($1)->fetch_array(MYSQLI_ASSOC)",
        $file);
$file = preg_replace(
        "/mysql_fetch_assoc\(mysql_query\((.*)\)\)/", 
        "\$mc->query($1)->fetch_array(MYSQLI_ASSOC)",
        $file);
$file = preg_replace(
        "/mysql_query\((.*)\)\)/",
        "\$mc->query($1))",
        $file);
$file = preg_replace(
        "/mysql_query\((.*)\)/",
        "\$mc->query($1)",
        $file);
$file = preg_replace(
        "/mysql_query\((.*)\)\)/s",
        "\$mc->query($1))",
        $file);
$file = preg_replace(
        "/mysql_query\((.*)\)/s",
        "\$mc->query($1)",
        $file);
$file = preg_replace(
        "/mysql_fetch_array\((.*),(.*)\)\)/",
        "$1->fetch_array(MYSQLI_ASSOC))",
        $file);
$file = preg_replace(
        "/mysql_fetch_assoc\((.*),(.*)\)\)/",
        "$1->fetch_array(MYSQLI_ASSOC))",
        $file);
$file = preg_replace(
        "/mysql_fetch_array\((.*)\)\)/",
        "$1->fetch_array(MYSQLI_ASSOC))", $file);
$file = preg_replace(
        "/mysql_fetch_assoc\((.*)\)\)/",
        "$1->fetch_array(MYSQLI_ASSOC))",
        $file);
$file = preg_replace(
        "/mysql_fetch_array\((.*),(.*)\)/",
        "$1->fetch_array(MYSQLI_ASSOC)",
        $file);
$file = preg_replace(
        "/mysql_fetch_assoc\((.*),(.*)\)/",
        "$1->fetch_array(MYSQLI_ASSOC)",
        $file);
$file = preg_replace(
        "/mysql_fetch_array\((.*)\)/",
        "$1->fetch_array(MYSQLI_ASSOC)", $file);
$file = preg_replace(
        "/mysql_fetch_assoc\((.*)\)/",
        "$1->fetch_array(MYSQLI_ASSOC)",
        $file);
$file = preg_replace(
        "/mysql_insert_id\(\)/",
        "\$mc->insert_id",
        $file);
$file = preg_replace(
        "/mysql_result\((.*),(.*)\)/U",
        "$1->fetch_assoc()[$2]",
        $file);
echo $file;
?>
