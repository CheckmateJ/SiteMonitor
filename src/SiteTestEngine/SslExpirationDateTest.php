<?php

namespace App\SiteTestEngine;


use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use Psr\Http\Message\ResponseInterface;

class SslExpirationDateTest implements SiteTestInterface
{

    public function run(SiteTest $siteTest, ResponseInterface $response): SiteTestResults
    {
        $siteTestResults = new SiteTestResults();
        $url = "https://www.google.com";
        $orignal_parse = parse_url($url, PHP_URL_HOST);
        $get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
        $read = stream_socket_client("ssl://".$orignal_parse.":443", $errno, $errstr,
            30, STREAM_CLIENT_CONNECT, $get);
        $cert = stream_context_get_params($read);
        $certinfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);


        $valid_from = date(DATE_RFC2822,$certinfo['validFrom_time_t']);
        $valid_to = date(DATE_RFC2822,$certinfo['validTo_time_t']);
        echo "Valid From: ".$valid_from."<br>";
        echo "Valid To:".$valid_to."<br>";


        return $siteTestResults;
    }

    public static function getId(): string
    {
        return 'SslExpirationTest';
    }
}