<?php


namespace App\SiteTestEngine;


use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use Iodev\Whois\Factory;
use Psr\Http\Message\ResponseInterface;

class DomainExpirationDate implements SiteTestInterface
{


    public function run(SiteTest $siteTest, ResponseInterface $response): SiteTestResults
    {
        $siteTestResults = new SiteTestResults();
        $notifyBeforeExpiration = $siteTest->getConfiguration()['notifyBeforeExpiration'];

        $url = $siteTest->getSite()->getDomainName();
        $domain = str_replace('www.', "", $url);

        $whois = Factory::get()->createWhois();
        $info = $whois->loadDomainInfo($domain);

        $expiration = $info->expirationDate;
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
        return 'Domain Expiration Test';
    }
}