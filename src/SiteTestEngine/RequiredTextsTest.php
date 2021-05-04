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
        $url = $siteTest->getSite()->getDomainName();
        $html = file_get_contents('https://' . $url);

        foreach ($siteTest->getConfiguration()['requiredTexts'] as $key => $attr) {
            try {
                $crawler = new Crawler($html);
                $value = $siteTest->getConfiguration()['requiredTexts'][$key];
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
        return $siteTestResults;
    }

    public static function getId(): string
    {
        return 'requiredTexts';
    }
}