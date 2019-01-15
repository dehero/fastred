<?php

function pgn($viewCount = 10, $page = 0, $count = 0) {
    $pgn = new stdClass();
    $pgn->page = $page;
    $pgn->viewCount = $viewCount;
    $pgn->count = $count;

    return $pgn;
}

function pgnGetSqlLimit($pgn) {
    return ' LIMIT ' . $pgn->page * $pgn->viewCount . ', ' . $pgn->viewCount;
}

function pgnGetPageCount($pgn) {
    $result = 0;
    if ($pgn->viewCount > 0) {
        $result = ceil($pgn->count / $pgn->viewCount);
    }

    return $result;
}

function pgnGetMaxCountMap($pgn, $maxLinkCount = 7) {
    $map = array();
    $pageCount = pgnGetPageCount($pgn);

    if ($pageCount < 2) return $map;

    $lastPage = $pageCount - 1;
    if ($maxLinkCount < 5) $maxLinkCount = 5;

    $scopeStart = 0;
    $scopeEnd = $lastPage;

    if ($maxLinkCount < $pageCount) {
        if ($pgn->page < $maxLinkCount - 2) {
            $scopeEnd = $maxLinkCount - 3;
        } else if ($lastPage - $pgn->page < $maxLinkCount - 2) {
            $scopeStart = $lastPage - $maxLinkCount + 3;
        } else {
            $scopeStart = $scopeEnd = $pgn->page;
            $i = 0;
            while ($scopeEnd - $scopeStart + 1 < $maxLinkCount - 4) {
                $scopeStart--;
                if ($i % 2 == 0) $scopeEnd++; else $scopeStart--;
            }
        }
    }

    if ($scopeStart > 2) {
        $map[0] = true;
        $map[$scopeStart - 1] = false;
    }

    for ($i = $scopeStart; $i <= $scopeEnd; $i++) {
        $map[$i] = true;
    }

    if ($scopeEnd < $lastPage - 2) {
        $map[$scopeEnd + 1] = false;
        $map[$lastPage] = true;
    }

    return $map;
}

function pgnGetShownCount($pgn) {
    if (empty($pgn->count)) return 0;
    $pageCount = pgnGetPageCount($pgn);

    return $pgn->page < $pageCount - 1
        ? $pgn->viewCount
        : $pgn->count - $pgn->viewCount * ($pageCount - 1);
}