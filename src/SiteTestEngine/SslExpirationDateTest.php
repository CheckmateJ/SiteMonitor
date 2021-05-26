<?php

namespace App\SiteTestEngine;


use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use Psr\Http\Message\ResponseInterface;
use function Sodium\add;

class SslExpirationDateTest implements SiteTestInterface
{

    public function run(SiteTest $siteTest, ResponseInterface $response): SiteTestResults
    {
        $siteTestResults = new SiteTestResults();
        $notifyBeforeExpiration = $siteTest->getConfiguration()['notifyBeforeExpiration'][0];

        $url = "https://" . $siteTest->getSite()->getDomainName();
        $originalParse = parse_url($url, PHP_URL_HOST);
        $get = stream_context_create(array("ssl" => array("capture_peer_cert" => TRUE)));
        $read = stream_socket_client("ssl://" . $originalParse . ":443", $errno, $errstr,
            30, STREAM_CLIENT_CONNECT, $get);
        $cert = stream_context_get_params($read);
        $certinfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);

        $expiration = $certinfo['validTo_time_t'];
        $today = new \DateTime('now');
        $weekLater = $today->modify($notifyBeforeExpiration == null ? '+7 day' : ('+' . $notifyBeforeExpiration . ' day'));
        $timeStamp = $weekLater->getTimestamp();

        if ($timeStamp < $expiration) {
            $siteTestResults->setResult(1);
        } else {
            $siteTestResults->setResult(0);
        }

        return $siteTestResults;
    }

    public static function getId(): string
    {
        return 'Ssl Expiration Test';
    }
}