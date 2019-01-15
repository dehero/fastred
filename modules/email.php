<?php

if (!function_exists('email')) {
    function email($options) {
        fastredRequire('obj');

        return obj($options);
    }
}

if (!function_exists('emailSend')) {
    /**
     * @param $email
     * @param $to
     * @param $copy
     * @return bool
     */
    function emailSend($email, $to = null, $copy = null) {

        $to = empty($to) ? $email->to : $to;
        $copy = empty($copy) ? $email->copy : $copy;

        $fromStr = emailUserToStr($email->from);
        $toStr = is_array($to) ? emailUserArrToStr($to) : emailUserToStr($to);
        $copyStr = is_array($copy) ? emailUserArrToStr($copy) : emailUserToStr($copy);
        $replyToStr = is_array($email->replyTo) ? emailUserArrToStr($email->replyTo) : emailUserToStr($email->replyTo);

        if (empty($fromStr) || empty($toStr)) return false;

        // Creating UTF-8 email header
        $subject = "=?UTF-8?B?" . base64_encode($email->subject) . "?=";
        $headers = "From: $fromStr\r\n"
            . (!empty($copyStr) ? "Cc: $copyStr\r\n" : '')
            . (!empty($replyToStr) ? "Reply-To: $replyToStr\r\n" : '')
            . "MIME-Version: 1.0\r\n"
            . (!empty($email->contentType) ? "Content-Type: $email->contentType; charset=UTF-8\r\n" : '');

        // Sending e-mail
        return mail($toStr, $subject, $email->content, $headers);
    }
}

if (!function_exists('emailAddressIsValid')) {
    /**
     * Check if email address is valid
     * @param $addrsss
     * @return bool
     */
    function emailAddressIsValid($address) {
        return (bool)preg_match('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/', $address);
    }
}

function emailUser($address, $name = null) {
    fastredRequire('obj');

    $result = obj();
    $result->address = $address;
    $result->name = $name;

    return $result;
}

function emailUserToStr($user) {
    if (empty($user)) return null;

    if (is_string($user)) {
        $result = $user;

    } elseif (is_object($user)) {
        $result = $user->address;
        $name = $user->name;

    } elseif (is_array($user)) {
        $result = $user['address'];
        $name = $user['name'];
    }

    if (!empty($name)) {
        $result = '=?UTF-8?B?' . base64_encode($name) . '?= <' . $result . '>';
    }

    // Protect from E-mail header injection
    return preg_replace('=((<CR>|<LF>|0x0A/%0A|0x0D/%0D|\\n|\\r)\S).*=i',
        null, $result);
}

function emailUserArrToStr($arr) {

    $result = '';
    $i = 0;
    foreach ($arr as $user) {
        $result .= ($i++ ? ', ' : '') . emailUserToStr($user);
    }

    return $result;
}