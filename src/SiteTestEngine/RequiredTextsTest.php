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
        $keys = array_keys($siteTest->getConfiguration());
        $html = file_get_contents('https://' . $domainName . '/' . $url);


        if ($keys[0] == 'checkElements') {
            foreach ($siteTest->getConfiguration()['checkElements'] as $key => $attr) {
                try {
                    $crawler = new Crawler($html);
                    $value = $attr;
                    $crawler = $crawler->filterXPath('//*[@' . $key . '="' . $value . '"]');
                    $checkAttr = $crawler->attr($key);

                    if ($checkAttr != null) {
                        $details = $crawler->extract(['_text']);;
                        $result = json_encode($details);
                        $result = str_replace('\n', ' ', $result);
                        $result = preg_replace('/\s\s+/', ' ', $result);
                        $siteTestResults->setResult(1);
                        $siteTestResults->setDetails($result);
                    }
                } catch (\Exception $e) {
                    $siteTestResults->setResult(0);
                    $siteTestResults->setDetails($e->getMessage());
                    break;
                };

            }
        } elseif ($keys[0] == 'requiredTexts') {
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
        }
        return $siteTestResults;
    }

    public static function getId(): string
    {
        return 'Selector Test';
    }
}