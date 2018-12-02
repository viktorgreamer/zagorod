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

    public static $proxies = [
        "194.242.125.25:8000:YKXPef:so0wYA",
        "194.242.124.101:8000:YKXPef:so0wYA",
        "194.242.126.186:8000:YKXPef:so0wYA",
        "194.242.125.233:8000:YKXPef:so0wYA",
        "91.241.46.125:8000:YKXPef:so0wYA",
        "194.242.126.207:8000:YKXPef:so0wYA",
        "194.242.124.94:8000:YKXPef:so0wYA",
        "194.242.125.234:8000:YKXPef:so0wYA",
    ];

    public static function getCookieFilename($url, $ipPort)
    {
        if ($url) $url = parse_url($url)['host'];
        if ($ipPort) {
            $filename = "cookies/" . str2url($url . "_" . $ipPort) . ".txt";
            if (!file_exists($filename)) {
                //  info(" FILE IS NOT EXIST", 'danger');
                file_put_contents($filename, "");
            } // else  info(" FILE IS EXIST", 'success');
            return $filename;
        } else {
            $filename = "cookies/cookie.txt";

            if (!file_exists($filename)) {
                //    info(" FILE IS NOT EXIST", 'danger');
                file_put_contents($filename, "");
            } //  else  info(" FILE IS EXIST", 'success');
            $ip_port_filename = $filename;
            return $filename;
        }
    }

    public function muti_thread_request($nodes)
    {
        $proxies = self::$proxies;
        $mh = curl_multi_init();
        $curl_array = array();
        foreach ($nodes as $i => $url) {
            $proxy = preg_split("/:/", array_shift($proxies));
            if ($proxy) {
                $proxyPort = $proxy[0] . ":" . $proxy[1];
                $proxyAuth = $proxy[2] . ":" . $proxy[3];
                $cookieFileName = self::getCookieFilename($url, $proxy[1]);
                D::echor($proxyPort);
                D::echor($proxyAuth);
            }
            $curl_array[$i] = curl_init($url);
            //  curl_setopt($curl_array[$i], CURLOPT_HEADER, 1);
            //  curl_setopt($curl_array[$i], CURLOPT_NOBODY, 1);

            curl_setopt($curl_array[$i], CURLOPT_HEADER, self::$headersCurlOpt);
            curl_setopt($curl_array[$i], CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl_array[$i], CURLOPT_USERAGENT, self::$useragent);
            curl_setopt($curl_array[$i], CURLOPT_HTTPPROXYTUNNEL, true);
            curl_setopt($curl_array[$i], CURLOPT_PROXY, $proxyPort);
            curl_setopt($curl_array[$i], CURLOPT_PROXYUSERPWD, $proxyAuth);
          //  curl_setopt($curl_array[$i], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_array[$i], CURLOPT_COOKIEFILE, $cookieFileName);
            curl_setopt($curl_array[$i], CURLOPT_COOKIEJAR, $cookieFileName);
            curl_multi_add_handle($mh, $curl_array[$i]);
        }
        $running = NULL;
        do {
            //  usleep(10000);
            curl_multi_exec($mh, $running);
        } while ($running > 0);

        $res = array();
        foreach ($nodes as $i => $url) {
            $res[$url] = curl_multi_getcontent($curl_array[$i]);
        }

        foreach ($nodes as $i => $url) {
            curl_multi_remove_handle($mh, $curl_array[$i]);
        }
        curl_multi_close($mh);
        return $res;
    }


    public $ipPort;

    public $user_agent;

    public $ip_port_filename;

    public static $useragent = "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.117 Safari/537.36";

    public static $headers = ['Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
       // 'Accept-Encoding' => 'gzip, deflate, br',
        'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,es;q=0.6',
        'DNT' => '1',
        'Upgrade-Insecure-Requests' => '1',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36'];

    public static $headersCurlOpt = [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding: gzip, deflate, br',
        'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,es;q=0.6',
        'DNT: 1',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36'];

    public function setDefaultSettings()
    {
        $this->setHeaders(self::$headers);
        $this->setDefaultUserAgent();
        $this->setOpt(CURLOPT_FOLLOWLOCATION, true);

    }

    public function setUrlWithCookies($url)
    {
        $filename = $this->getFilenamecookie($url);
        $this->setCookieFile($filename);
        $this->setCookieJar($filename);
        $this->setUrl($url);
    }

    public function setDefaultUserAgent()
    {
        $this->setUserAgent(self::$useragent);
    }

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
        $response = $curl->response;
        $curl->close();
        return $response;

    }


    public function getFilenamecookie($url = '')
    {
        if ($url) $url = parse_url($url)['host'];
        if ($this->ipPort) {
            $filename = "cookies/" . str2url($url . "_" . $this->ipPort) . ".txt";

            if (!file_exists($filename)) {
                //  info(" FILE IS NOT EXIST", 'danger');
                file_put_contents($filename, "",FILE_USE_INCLUDE_PATH | LOCK_EX );
            } // else  info(" FILE IS EXIST", 'success');
            return $filename;
        } else {
            $filename = "cookies/cookie.txt";

            if (!file_exists($filename)) {
                //    info(" FILE IS NOT EXIST", 'danger');
                file_put_contents($filename, "",FILE_USE_INCLUDE_PATH | LOCK_EX );
            } //  else  info(" FILE IS EXIST", 'success');
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
        my_var_dump($curl->getResponseCookies());
        sleep(2);
        //  echo $curl->response;
        $curl->close();


        return $this->render('index');

    }

}