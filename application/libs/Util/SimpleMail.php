<?php
/**
 * 簡単メール送信機能
 * @author ou
 */
class Lib_Util_SimpleMail
{
    /**
     * メール送信機能
     * @param $params パラメータの配列(キー： subject, body, from, to, replace)
     * @return void
     */
    public static function send($params)
    {
        $mailObj = new Zend_Mail('ISO-2022-JP');
        $mailObj->setSubject(mb_convert_encoding($params['subject'], 'ISO-2022-JP', 'UTF-8'));
        $body = $params['body'];
        if(isset($params['replace'])) {
            foreach($params['replace'] as $key => $value) {
               $body = str_replace("%$key%", $value, $body);
            }
        }
        $mailObj->setBodyText(mb_convert_encoding($body, 'ISO-2022-JP', 'UTF-8'));
        $mailObj->setFrom($params['from']);
        if(!is_array($params['to'])) {
            $params['to'] = split(',', $params['to']);
        }
        foreach($params['to'] as $to) {
            $mailObj->addTo(trim($to));
        }
        //エラーメールの返信先
        $tr = new Zend_Mail_Transport_Sendmail('-f'.trim($params['from']));
        $mailObj->setDefaultTransport($tr);
        
        $mailObj->send();
    }
}