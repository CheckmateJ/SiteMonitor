<?php


namespace App\SiteTestEngine;


use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use Psr\Http\Message\ResponseInterface;

class HeaderSiteTest implements SiteTestInterface
{

    public function run(SiteTest $siteTest, ResponseInterface $response): SiteTestResults
    {
        $siteTestResults = new SiteTestResults();
        foreach ($siteTest->getConfiguration()['requiredHeaders'] as $key => $check) {
            if ($response->getHeader($key)[0] == $check) {
                $siteTestResults->setResult(1);
            } else {
                $siteTestResults->setResult(0);
                break;
            }
        }

        return $siteTestResults;
    }

    public static function getId(): string
    {
        return "Header";
    }
}