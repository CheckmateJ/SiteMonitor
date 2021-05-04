<?php


namespace App\SiteTestEngine;


use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use Psr\Http\Message\ResponseInterface;

class KeywordSiteTest implements SiteTestInterface
{

    public function run(SiteTest $siteTest, ResponseInterface $response): SiteTestResults
    {
        $siteTestResults = new SiteTestResults();

        if (str_contains($response->getBody()->getContents(), $siteTest->getConfiguration()['keyword'])) {
            $siteTestResults->setResult(1);
        } else {
            $siteTestResults->setResult(0);
        }

        return $siteTestResults;
    }

    public static function getId(): string
    {
        return 'Keyword';
    }
}