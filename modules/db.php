<?php

if (!defined('DB_PERMANENT'))   define('DB_PERMANENT', false);
if (!defined('DB_HOST'))        define('DB_HOST', 'localhost');
if (!defined('DB_USER'))        define('DB_USER', 'root');
if (!defined('DB_PASSWORD'))    define('DB_PASSWORD', '');
if (!defined('DB_NAME'))        define('DB_NAME', 'fastred');
if (!defined('DB_QUERY_LOG'))   define('DB_LOG', false);

function db($permanent = DB_PERMANENT) {
    static $db;

    if (!isset($db)) {
        $db = mysqli_connect(($permanent ? 'p:' : '') . DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $db->set_charset('utf8');
        mysqli_query($db, 'SET @@session.time_zone=\'+00:00\'');
    }

    if (!$db) throw new Exception('DB connection error.');

    return $db;
}

function dbStrGetEscaped($str) {
    return mysqli_real_escape_string(db(), $str);
}

function dbGetInsertId() {
    return mysqli_insert_id(db());
}

function dbGetResult() {
    $db = db();

    mysqli_next_result($db);
    $result = mysqli_store_result($db);

    return $result;
}

function dbHasResult() {
    return mysqli_next_result(db());
}

function dbLoadArrOfObj($sql, $pgn = null) {
    fastredRequire('obj');

    if (objHasProperties($pgn)) {
        $sql .= '; SELECT FOUND_ROWS() as count';

        if (dbQuery($sql, true)) {
            $dbResult = dbGetResult();
            $pgn->count = dbResultToObj(dbGetResult())->count;
        }
    } else {
        $dbResult = dbQuery($sql);
    }

    $result = dbResultToArrOfObj($dbResult);
    dbResultFree($dbResult);

    return $result;
}

function dbLoadArrOfValue($sql) {
    $dbResult = dbQuery($sql);

    $result = dbResultToArrOfValue($dbResult);
    dbResultFree($dbResult);

    return $result;    
}

function dbLoadObj($sql) {
    $dbResult = dbQuery($sql);

    $result = dbResultToObj($dbResult);
    dbResultFree($dbResult);

    return $result;
}

function dbLoadIndexOfObj($sql, $pgn = null, $id = 'id') {
    fastredRequire('obj');

    if (objHasProperties($pgn)) {
        $sql .= '; SELECT FOUND_ROWS() as count';

        if (dbQuery($sql, true)) {
            $dbResult = dbGetResult();
            $pgn->count = dbResultToObj(dbGetResult())->count;
        }
    } else {
        $dbResult = dbQuery($sql);
    }

    $result = dbResultToIndexOfObj($dbResult, $id);
    dbResultFree($dbResult);

    return $result;
}

function dbLoadValue($sql) {
    $dbResult = dbQuery($sql);

    $result = dbResultToValue($dbResult);
    dbResultFree($dbResult);

    return $result;
}

function dbQueryLog($sql = null) {

    static $log = array();

    if (!is_null($sql)) {
        array_push($log, $sql); 
    } else {
        return implode("\n", $log);
    }
}

function dbQueryTime($startTime = null) {
    static $result = 0;
    if (isset($startTime)) {
        $result += microtime(true) - $startTime;
    }
    return $result;
}

function dbQueryCount($increment = null) {
    static $result = 0;
    if (isset($increment)) {
        $result += $increment;
    }
    return $result;
}
function dbSqlGetSelect($table, $columns = null, $where = null, $orderBy = null, $pgn = null) {
    fastredRequire('sql');

    return sqlGetSelect(
        $table, $columns, $where, $orderBy,
        $pgn ? $pgn->viewCount : null,
        $pgn ? $pgn->page * $pgn->viewCount : null,
        $pgn ? 'SQL_CALC_FOUND_ROWS' : null
    );
}

function dbTransactionStart() {
    $db = db();
    if (function_exists('mysqli_begin_transaction')) {
        return mysqli_begin_transaction($db);
    }

    // For PHP < 5.5
    return mysqli_query($db, 'START TRANSACTION');
}

function dbTransactionRollback() {
    return mysqli_rollback(db());
}

function dbTransactionEnd() {
    return mysqli_commit(db());
}

function dbQuery($sql, $multi = false) {
    $db = db();

    dbQueryCount(1);
    IF (DB_QUERY_LOG) {
        dbQueryLog($sql);
    }
    $startTime = microtime(true);
    if ($multi) {
        $result = mysqli_multi_query($db, $sql);
    } else {
        $result = mysqli_query($db, $sql);
    }
    dbQueryTime($startTime);

    return $result;
}

function dbResultToArrOfObj($dbResult) {
    if (!$dbResult) return null;

    $result = [];

    $rowCount = mysqli_num_rows($dbResult);
    for($i = 0; $i < $rowCount; $i++) {
        $obj = mysqli_fetch_object($dbResult);
        $result[] = $obj;
    }

    return $result;
}

function dbResultToArrOfValue($dbResult) {
    if (!$dbResult) return null;

    $result = [];

    $rowCount = mysqli_num_rows($dbResult);
    for($i = 0; $i < $rowCount; $i++) {
        $arr = mysqli_fetch_array($dbResult);
        $result[] = $arr[0];
    }

    return $result;
}

function dbResultToIndexOfObj($dbResult, $id = 'id') {
    if (!$dbResult) return null;

    fastredRequire('obj');

    $result = obj();

    $rowCount = mysqli_num_rows($dbResult);
    for($i = 0; $i < $rowCount; $i++) {
        $obj = mysqli_fetch_object($dbResult);
        $key = $obj->{$id};
        $result->{$key} = $obj;
    }

    return $result;
}

function dbResultToObj($dbResult) {
    if (!$dbResult) return null;

    if (mysqli_num_rows($dbResult) > 0) {
        return mysqli_fetch_object($dbResult);
    }

    return null;
}

function dbResultToValue($dbResult) {
    if (!$dbResult) return null;

    if (mysqli_num_rows($dbResult) > 0) {
        $arr = mysqli_fetch_array($dbResult);

        return $arr[0];
    }

    return null;
}

function dbResultFree($dbResult) {
    if (!$dbResult) return;

    mysqli_free_result($dbResult);
}
