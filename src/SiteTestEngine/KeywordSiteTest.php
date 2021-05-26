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
        $contents = $response->getBody()->getContents();

        foreach ($siteTest->getConfiguration()['Keyword'] as $value) {
            if (str_contains($contents, $value)) {
                $siteTestResults->setResult(1);
            } else {
                $siteTestResults->setResult(0);
            }
        }

        return $siteTestResults;
    }

    public static function getId(): string
    {
        return 'Keyword';
    }
}