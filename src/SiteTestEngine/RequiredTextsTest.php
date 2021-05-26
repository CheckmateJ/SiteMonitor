<?php


namespace App\SiteTestEngine;

use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use App\SiteTestEngine\SiteTestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class RequiredTextsTest implements SiteTestInterface
{

    public function run(SiteTest $siteTest, ResponseInterface $response): SiteTestResults
    {
        $siteTestResults = new SiteTestResults();
        $domainName = $siteTest->getSite()->getDomainName();
        $url = $siteTest->getUrl();
//        $keys = array_keys($siteTest->getConfiguration());
        $html = file_get_contents('https://' . $domainName . '/' . $url);


        foreach ($siteTest->getConfiguration()['requiredTexts'] as $key => $attr) {
            try {
                $converter = new CssSelectorConverter();
                $filterPath = $converter->toXPath($key);
                $crawler = new Crawler($html);
                $contentSelector = $crawler->filterXPath($filterPath)->extract(['_text']);
                if (in_array($attr, $contentSelector)) {
                    $siteTestResults->setResult(1);
                } else {
                    $siteTestResults->setResult(0);

                }
            } catch (\Exception $e) {
                $siteTestResults->setResult(0);
                break;
            };
        }

        return $siteTestResults;
    }


    public static function getId(): string
    {
        return 'Required Texts';
    }
}