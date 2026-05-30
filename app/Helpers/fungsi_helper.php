<?php

date_default_timezone_set('Asia/Jakarta');

function matan_biru2(string $str): string
{
    $pattern = '/\((.*?)\)/';
    $replacement = '<span style="color:blue">(${1})</span>';

    return preg_replace($pattern, $replacement, $str) ?? $str;
}

function sesi(string $key, $val = null)
{
    $session = session();

    if ($val === null) {
        return $session->get($key);
    }

    $session->set($key, $val);

    return null;
}

function uniord(string $u): int
{
    $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
    $k1 = ord(substr($k, 0, 1));
    $k2 = ord(substr($k, 1, 1));

    return $k2 * 256 + $k1;
}

function replace_brnbsp(?string $str): string
{
    if (!$str) {
        return '';
    }

    $ret = preg_replace('/<$/', '', $str) ?? $str;
    $ret = str_replace('<br>&nbsp;', '', $ret);
    $ret = str_replace('<br /><ul>', '<ul>', $ret);

    return $ret;
}

function replace_p(?string $str): string
{
    if (!$str) {
        return '';
    }

    $ret = preg_replace('#<p(.*?)>(.*?)</p>#is', '$2<br>', $str) ?? $str;
    $ret = str_replace(')))', '', $ret);
    $ret = str_replace('اه.', '', $ret);
    $ret = str_replace('اﻫ.', '', $ret);
    $ret = str_replace(' »', '»', $ret);
    $ret = str_replace('  ', ' ', $ret);
    $ret = str_replace('صلى الله عليه وسلم', 'ﷺ', $ret);
    $retLen = strlen($ret);

    if ($retLen >= 4) {
        $ret = substr($ret, 0, $retLen - 4);
    }

    return $ret;
}

function replace_p2(string $str): string
{
    $s = preg_replace('/<p[^>]*?>/', '', $str) ?? $str;
    $s = str_replace('  ', ' ', $s);
    $s = str_replace(' SWT', " <i>ta'ala</i>", $s);
    $s = str_replace(' SAW', " <i>shallallahu 'alaihi wa sallam</i>", $s);
    $s = str_replace(" shallallahu 'alaihi wa sallam", " <i>shallallahu 'alaihi wa sallam</i>", $s);
    $s = str_replace(" radhiyallahu 'anhuma", " <i>radhiyallahu 'anhuma</i>", $s);
    $s = str_replace(" radhiyallahu 'anha", " <i>radhiyallahu 'anha</i>", $s);
    $s = str_replace(" radhiyallahu 'anhu", " <i>radhiyallahu 'anhu</i>", $s);
    $s = str_replace('<i><i>', ' <i>', $s);
    $s = str_replace('</i></i>', ' </i>', $s);
    $sLen = strlen($s);

    if ($sLen >= 4) {
        $s = substr($s, 0, $sLen - 4);
    }

    return str_replace('</p>', '<br />', $s);
}

function is_arabic(string $str): bool
{
    if (mb_detect_encoding($str) !== 'UTF-8') {
        $str = mb_convert_encoding($str, mb_detect_encoding($str), 'UTF-8');
    }

    preg_match_all('/.|\n/u', $str, $matches);
    $chars = $matches[0] ?? [];
    $arabicCount = 1;
    $totalCount = 2;

    foreach ($chars as $char) {
        $pos = uniord($char);

        if ($pos >= 1536 && $pos <= 1791) {
            $arabicCount++;
        }

        $totalCount++;
    }

    return ($arabicCount / $totalCount) > 0.6;
}

function rem_harokat(string $str): string
{
    $harokat = ['ّ', 'َ', 'ً', 'ُ', 'ٌ', 'ِ', 'ٍ', 'ْ'];

    return str_replace($harokat, '', $str);
}

function replace_hamzah(string $str): string
{
    $hamzah = ['أ', 'إ', 'آ'];

    return str_replace($hamzah, 'ا', $str);
}

function replace_petik_kurung(string $str): string
{
    return preg_replace('/"([^"]*)"/', '«$1»', $str) ?? $str;
}

function rip_tags(string $string): string
{
    $string = preg_replace('/<[^>]*>/', ' ', $string) ?? $string;
    $string = str_replace("\r", '', $string);
    $string = str_replace("\n", ' ', $string);
    $string = str_replace("\t", ' ', $string);

    return trim(preg_replace('/ {2,}/', ' ', $string) ?? $string);
}

function random_string(int $length = 10): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charsLength = strlen($chars);
    $ret = '';

    for ($i = 0; $i < $length; $i++) {
        $ret .= $chars[rand(0, $charsLength - 1)];
    }

    return $ret;
}
