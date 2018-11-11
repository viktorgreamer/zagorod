<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 20.03.2018
 * Time: 13:40
 */

namespace app\utils;

use Curl\Curl;

class MyCurl extends Curl
{

    public $ipPort;

    public $user_agent;

    public $ip_port_filename;

    public function getAjax($url, $options)
    {

        $curl = new Curl();

        if ($options['headers']) $curl->setHeaders($options['headers']);
        if ($options['ipPort'] !== false) {
            $curl->setOpt(CURLOPT_HTTPPROXYTUNNEL, 1);
            $curl->setOpt(CURLOPT_PROXY, $this->ipPort);
        }
        if ($options['cookiefile'] !== false) {
            $curl->setCookieFile($this->ip_port_filename);
        }
        if ($options['ref'] !== false) {
            $curl->setReferrer($options['ref']);
        }
        $curl->setUserAgent($this->user_agent);
        $curl->get($url);
        $curl->close();
        return $curl->response;

    }



    public function getFilenamecookie()
    {
        if ($this->ipPort) {
            $filename = "cookies/" . str2url($this->ipPort) . "_cookie.txt";

            if (!file_exists($filename)) {
              //  info(" FILE IS NOT EXIST", 'danger');
                file_put_contents($filename, "");
            } else  info(" FILE IS EXIST", 'success');
            return $filename;
        } else {
            $filename = "cookies/cookie.txt";

            if (!file_exists($filename)) {
            //    info(" FILE IS NOT EXIST", 'danger');
                file_put_contents($filename, "");
            } else  info(" FILE IS EXIST", 'success');
            $this->ip_port_filename = $filename;
            return $filename;
        }
    }

    public function getUrlWithCookies($url)
    {
        $filename = \Yii::getAlias("@app") . "/web/" . $this->getFilenamecookie();
        // info("filename = ".$filename);

        $this->setCookieFile($filename);
        $this->get($url);
        $this->setCookieJar($filename);
    }

    public function actionTestCurlAvito()
    {
        $curl = new MyCurl();
        $curl->setUserAgent("Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.117 Safari/537.36");
        $curl->getUrlWithCookies("https://www.avito.ru/");
        //   $cookies = $curl->getResponseCookies();
        sleep(2);
        //    my_var_dump($cookies);
        // $curl->setCookies($cookies);
        $url = "https://m.avito.ru/velikiy_novgorod/kvartiry/4-k_kvartira_110_m_56_et._1690325690";
        $curl->getUrlWithCookies($url);

        $tree = str_get_html($curl->response);
        $domain = "https://m.avito.ru/";
        $href = $tree->find("a.js-action-show-number", 0)->href;

        $ajax_url = $domain . $href . "?async";
        info(" request = " . $ajax_url);

        $headers = [
            'x-requested-with' => 'XMLHttpRequest',
            'accept' => 'application/json',
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language', 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,es;q=0.6'
        ];

        $curl->close();
        $response = json_decode(gzdecode($curl->getAjax($ajax_url, ['ref' => $url, 'headers' => $headers])));

        info(" TELEPHONE = " . $response->phone);

        return $this->render('index');

    }

    public function actionTestMyCurl()
    {
        $curl = new MyCurl();
        $curl->setUserAgent("Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.117 Safari/537.36");
//        $curl->setHeaders([
//            'Cache-Control' => 'max-age=0',
//            'Connection' => 'keep-alive',
//            'Host' => 'yandex.ru'
//        ]);

        $curl->getUrlWithCookies("https://www.avito.ru/velikiy_novgorod");
        $curl->getUrlWithCookies("https://github.com/php-curl-class/php-curl-class");
        $curl->getUrlWithCookies("https://novgorod.cian.ru/");
        $curl->getUrlWithCookies("https://novgorod.cian.ru/kupit-1-komnatnuyu-kvartiru/");
        my_var_dump( $curl->getResponseCookies());
        sleep(2);
        //  echo $curl->response;
        $curl->close();


        return $this->render('index');

    }

}