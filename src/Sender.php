<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Shridhar\Sms;

use Exception;

/**
 * Description of Sms
 *
 * @author Shridhar
 */
class Sender {

    protected $mobile, $url, $params = [], $message;

    public function __construct($mobile, $message = null, $api = "default") {
        $this->setMobile($mobile);
        $this->setMessage($message);
        $this->setApi($api);
        $this->url = $this->config("url");
        $this->params = $this->config("params");
    }

    static function make($mobile, $message = null, $api = "default") {
        return app()->makeWith(__CLASS__, [
                    "mobile" => $mobile,
                    "message" => $message,
                    "api" => $api
        ]);
    }

    function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    function setMobile($mobile) {
        $this->mobile = array_wrap($mobile);
        return $this;
    }

    function setApi($api) {
        $this->api = $api;
        return $this;
    }

    function getConfig($key) {
        return config("sms.$this->api.$key");
    }

    protected function get_url() {
        $mobile_field = $this->config("mobile_field", "to");
        $message_field = $this->config("message_field", "text");
        $mobile_seperator = $this->config("mobile_seperator", ",");
        $allow_empty = $this->config("allow_empty_message");

        $url = $this->url;
        $params = $this->params;
        $text = $this->message;

        if (empty($this->mobile)) {
            throw new Exception("No mobile numbers provided");
        }

        if (!$allow_empty && empty($this->message)) {
            throw new Exception("No message provided");
        }

        $mobile = implode($mobile_seperator, $this->mobile);
        $params[$mobile_field] = $mobile;
        $params[$message_field] = $text;
        foreach ($params as $key => &$value) {
            $value = "$key=" . urlencode($value);
        }
        $url .= "?" . implode("&", $params);
        return $url;
    }

    public function send() {
        $url = $this->get_url();

        $c = curl_init();

        curl_setopt_array($c, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE
        ]);

        $result = curl_exec($c);

        return $result;
    }

}
