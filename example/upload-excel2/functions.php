<?php

/*

CREATE TABLE `bsm_price` (
 `sku` varchar(32) NOT NULL,
 `title` varchar(128) DEFAULT NULL,
 `pgroup` varchar(16) DEFAULT NULL,
 `price` decimal(8,2) DEFAULT NULL,
 `units` varchar(8) DEFAULT NULL,
 PRIMARY KEY (`sku`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

CREATE TABLE `bsm_store_ak` (
 `sku` varchar(32) NOT NULL,
 `title` varchar(128) DEFAULT NULL,
 `qnty` varchar(8) DEFAULT NULL,
 PRIMARY KEY (`sku`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

*/

function getMysqliDb() {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DB);

    if ( $mysqli->connect_errno ) {
        return null;
    }

    $mysqli->set_charset("utf8"); //Встановлення utf8 для ДБ

    return $mysqli;
}

function clearPrice($mysqli) {
    return $mysqli->query('delete from ' . DBT_PRICE);
}
function dataToPrice($mysqli, &$data) {
    $query = array();
    $count = 0;
    $isError = false;
    $res = '';

    foreach ( $data as $row ) {
        $row['A'] = addslashes($row['A']);
        $row['B'] = addslashes($row['B']);
        $row['C'] = addslashes($row['C']);
        $row['D'] = addslashes($row['D']);

        $row['E'] = round($row['E'], 2);

        $query[] = "
            insert ignore into " . DBT_PRICE . "
            (   
                sku, 
                title, 
                pgroup, 
                price, 
                units
            )
            values 
            (
                '{$row['A']}', 
                '{$row['B']}', 
                '{$row['D']}', 
                '{$row['E']}', 
                '{$row['C']}'
            );";
        
        $count += 1;

        if ( 500 < $count ) {
            $res .= $mysqli->multi_query(implode("\n", $query)) ? '1' : $mysqli->error . "\n";

            $mysqli->close();
            $mysqli = getMysqliDb();

            $query = array();
            $count = 0;
        }
    }

    if ( !empty($query) ) {
        $res .= $mysqli->multi_query(implode("\n", $query)) ? '1' : '0';
        unset($query);
    }

    // return implode("\n", $query) . 
    // return print_r($data, true);
    return $res;
}

function clearStoreAK($mysqli) {
    return $mysqli->query('delete from ' . DBT_STORE_AK);
}
function dataToStoreAK($mysqli, &$data) {
    $query = array();
    $count = 0;
    $isError = false;
    $res = '';

    foreach ( $data as $row ) {
        $row['A'] = addslashes($row['A']);
        $row['B'] = addslashes($row['B']);
        $row['C'] = addslashes($row['C']);

        $query[] = "
            insert into " . DBT_STORE_AK . "
            (   
                sku, 
                title,
                qnty
            )
            values 
            (
                '{$row['A']}', 
                '{$row['B']}', 
                '{$row['C']}'
            );";
        
        $count += 1;

        if ( 500 < $count ) {
            $res .= $mysqli->multi_query(implode("\n", $query)) ? '1' : $mysqli->error . "\n";
            // $res .= implode("\n", $query) . "\n";

            $mysqli->close();
            $mysqli = getMysqliDb();

            $query = array();
            $count = 0;
        }
    }

    if ( !empty($query) ) {
        $res .= $mysqli->multi_query(implode("\n", $query)) ? '1' : '0';
        unset($query);
    }

    // return implode("\n", $query) . 
    // return print_r($data, true);
    return $res;
}











