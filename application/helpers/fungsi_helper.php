<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
function matan_biru2($str){
	$pattern = '/\((.*?)\)/';
	$replacement = '<span style="color:blue">(${1})</span>';
  return preg_replace($pattern, $replacement, $str);
}
function sesi($sesi, $val=NULL){
	$ci=&get_instance();
	if(is_null($val)){
		return $ci->session->userdata($sesi);		
	}else{
		$ci->session->set_userdata($sesi, $val);
		return;
	}
}
function uniord($u) {
  // i just copied this function fron the php.net comments, but it should work fine!
  $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
  $k1 = ord(substr($k, 0, 1));
  $k2 = ord(substr($k, 1, 1));
  return $k2 * 256 + $k1;
}
function replace_brnbsp($str){
	if(!$str) return '';
	$ret = preg_replace('/<$/', '', $str);
	$ret = str_replace("<br>&nbsp;", "", $ret);
	$ret = str_replace("<br /><ul>", "<ul>", $ret);
	return $ret;
}
function replace_p($str){
	if(!$str) return '';
	$ret = preg_replace('#<p(.*?)>(.*?)</p>#is', '$2<br>', $str);
	$ret = str_replace(")))", "", $ret);
	$ret = str_replace("اه.", "", $ret);
	$ret = str_replace("اﻫ.", "", $ret);
	$ret = str_replace(" »", "»", $ret);
	$ret = str_replace("  ", " ", $ret);
	$ret = str_replace("صلى الله عليه وسلم", "ﷺ", $ret);
	$ret_len = strlen($ret);
	$ret = substr($ret, 0, $ret_len-4);
	return $ret;
}
function replace_p2($str){
	$s = preg_replace("/<p[^>]*?>/", "", $str);
	$s = str_replace("  ", " ", $s);
	$s = str_replace(" SWT", " <i>ta'ala</i>", $s);
	$s = str_replace(" SAW", " <i>shallallahu 'alaihi wa sallam</i>", $s);
	$s = str_replace(" shallallahu 'alaihi wa sallam", " <i>shallallahu 'alaihi wa sallam</i>", $s);
	$s = str_replace(" radhiyallahu 'anhuma", " <i>radhiyallahu 'anhuma</i>", $s);
	$s = str_replace(" radhiyallahu 'anha", " <i>radhiyallahu 'anha</i>", $s);
	$s = str_replace(" radhiyallahu 'anhu", " <i>radhiyallahu 'anhu</i>", $s);
	$s = str_replace("<i><i>", " <i>", $s);
	$s = str_replace("</i></i>", " </i>", $s);
	$s_len = strlen($s);
	$s = substr($s, 0, $s_len-4);
	return str_replace("</p>", "<br />", $s);
}
function is_arabic($str){
  if(mb_detect_encoding($str) !== 'UTF-8') {
      $str = mb_convert_encoding($str,mb_detect_encoding($str),'UTF-8');
  }

  /*
  $str = str_split($str); <- this function is not mb safe, it splits by bytes, not characters. we cannot use it
  $str = preg_split('//u',$str); <- this function woulrd probably work fine but there was a bug reported in some php version so it pslits by bytes and not chars as well
  */
  preg_match_all('/.|\n/u', $str, $matches);
  $chars = $matches[0];
  $arabic_count = 1;
  $latin_count = 1;
  $total_count = 2;
  foreach($chars as $char) {
    //$pos = ord($char); we cant use that, its not binary safe 
    $pos = uniord($char);
//        echo $char ." --> ".$pos.PHP_EOL;

    if($pos >= 1536 && $pos <= 1791) {
        $arabic_count++;
    } else if($pos > 123 && $pos < 123) {
        $latin_count++;
    }
    $total_count++;
  }
  if(($arabic_count/$total_count) > 0.6) {
    // 60% arabic chars, its probably arabic
    return true;
  }
  return false;
}

function rem_harokat($str){
$harokat = ['ّ','َ','ً','ُ','ٌ','ِ','ٍ','ْ']; 
return str_replace($harokat, '', $str);
}

function replace_hamzah($str){
$hamzah = ['أ','إ','آ'];
return str_replace($hamzah, 'ا', $str);
}
function replace_petik_kurung($str){
	return preg_replace('/"([^"]*)"/', '«$1»', $str);
}
//untuk mengganti html tag dengan spasi
function rip_tags($string) {
   
    // ----- remove HTML TAGs -----
    $string = preg_replace ('/<[^>]*>/', ' ', $string);
   
    // ----- remove control characters -----
    $string = str_replace("\r", '', $string);    // --- replace with empty space
    $string = str_replace("\n", ' ', $string);   // --- replace with space
    $string = str_replace("\t", ' ', $string);   // --- replace with space
   
    // ----- remove multiple spaces -----
    $string = trim(preg_replace('/ {2,}/', ' ', $string));
   
    return $string;
}
function random_string($length=10){
  $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $chars_length = strlen($chars);
  $ret = '';
  for ($i = 0; $i < $length; $i++) {
    $ret .= $chars[rand(0, $chars_length - 1)];
  }
  return $ret;
}

