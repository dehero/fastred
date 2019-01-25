<?php

if (!function_exists('sqlColumnsToStr')) {
    function sqlColumnsToStr($columns, $pairs = false) {
        if (is_array($columns)) {
            return implode(', ', $columns);
        } else {
            return (string)$columns;
        }
    }
}

if (!function_exists('sqlGetInsert')) {
    function sqlGetInsert($table, $values, $command = null) {
        return
            (is_null($command) ? 'INSERT' : $command)
            . ' INTO ' . sqlTableToStr($table) . ' (' . sqlValuesToStr($values, 'keys')
            . ') VALUES (' . sqlValuesToStr($values, 'values') . ')';
    }
}

if (!function_exists('sqlGetSelect')) {
    function sqlGetSelect($table, $columns = null, $where = null, $orderBy = null, $count = null, $offset = null, $keywords = null) {
        return
            'SELECT '
            . (!empty($keywords) ? $keywords . ' ' : '')
            . (!empty($columns) ? sqlColumnsToStr($columns) : ' * ')
            . ' FROM ' . sqlTableToStr($table)
            . (!empty($where) ? ' WHERE ' . sqlWhereToStr($where) : '')
            . (!empty($orderBy) ? ' ORDER BY ' . sqlOrderByToStr($orderBy) : '')
            . (!empty($count) ? ' LIMIT ' . (!empty($offset) ? (integer)($offset) . ', ' : '') . (integer)($count) : '');
    }
}

if (!function_exists('sqlGetDelete')) {
    function sqlGetDelete($table, $where) {
        return
            'DELETE FROM ' . sqlTableToStr($table)
            . ' WHERE ' . sqlWhereToStr($where);
    }
}

if (!function_exists('sqlGetUpdate')) {
    function sqlGetUpdate($table, $values, $where = null) {
        return
            'UPDATE ' . sqlTableToStr($table)
            . ' SET ' . (is_string($values) ? $values : sqlValuesToStr($values, 'pairs'))
            . (!empty($where) ? ' WHERE ' . sqlWhereToStr($where) : '');
    }
}

if (!function_exists('sqlOrderByToStr')) {
    function sqlOrderByToStr($orderBy) {
        $result = '';

        if (is_array($orderBy)) {
            $i = 0;
            foreach ($orderBy as $key => $value) {
                $result .= ($i++ ? ', ' : '') . $key . ($value == 'asc' ? ' ASC' : ' DESC');
            }
        } else {
            $result = (string)$orderBy;
        }

        return $result;
    }
}

if (!function_exists('sqlTableToStr')) {
    function sqlTableToStr($table) {
        $result = '';

        if (is_array($table)) {
            for ($i = 0; $i < count($table); $i++) {
                $result .= ($i > 0 ? ', ' : '') . $table[$i];
            }
        } else {
            $result = $table;
        }

        return $result;
    }
}

if (!function_exists('sqlWhereToStr')) {
    function sqlWhereToStr($where, $operator = 'AND') {
        $result = '';

        if (is_array($where)) {
            $i = 0;
            foreach ($where as $whereKey => $whereValue) {
                $result .= ($i++ ? " $operator " : '');
                if (is_integer($whereKey)) {
                    $result .= ' ' . $whereValue;
                } else {
                    $result .= $whereKey;
                    if (is_null($whereValue)) {
                        $result .= ' IS NULL';
                    } else if (is_array($whereValue)) {
                        $j = 0;
                        $result .= ' IN (';
                        foreach ($whereValue as $value) {
                            if (isset($value)) {
                                $result .= ($j++ ? ', ' : '') . sqlValueToStr($value);
                            }
                        }
                        $result .= ')';
                    } else {
                        $result .= ' = ' . sqlValueToStr($whereValue);
                    }
                }
            }
        } else {
            $result = (string)$where;
        }

        return $result;
    }
}

if (!function_exists('sqlValueGetEscaped')) {
    function sqlValueGetEscaped($value) {
        return (string)$value;
    }
}

if (!function_exists('sqlValueToStr')) {
    function sqlValueToStr($value) {
        if (is_null($value)) {
            return 'NULL';
        } else {
            return '"' . sqlValueGetEscaped($value) . '"';
        }
    }
}

function sqlValuesGetFiltered($values, $columns = null) {

    fastredRequire('arr');

    $result = new stdClass();

    foreach ($values as $key => $value) {
        if (arrIncludes($columns, $key)) {
            $result->{$key} = $value;
        }
    }

    return $result;
}

if (!function_exists('sqlValuesToStr')) {
    function sqlValuesToStr($values, $mode = 'pairs') {
        $i = 0;
        $result = '';

        switch ($mode) {
            case 'keys':
                foreach ($values as $key => $value) {
                    $result .= ($i++ ? ', ' : '') . $key;
                }
                break;
            case 'values':
                foreach ($values as $value) {
                    $result .= ($i++ ? ', ' : '') . sqlValueToStr($value);
                }
                break;
            case 'pairs':
                foreach ($values as $key => $value) {
                    $result .= ($i++ ? ', ' : '') . $key . ' = ' . sqlValueToStr($value);
                }
                break;
        }

        return $result;
    }
}
