<?php


namespace App\SiteTestEngine;


use App\Entity\Site;
use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;

class SiteTestProcessor
{
    /** @var SiteTestInterface[] */
    protected $testEngines = [];

    public function addTestType(SiteTestInterface $siteTest){
        $this->testEngines[] = $siteTest;
    }

    public function getResponseForSite(Site $site)
    {
        $client = new \GuzzleHttp\Client();
        return $client->request('GET', 'https://' . $site->getDomainName(), ['allow_redirects' => true, 'verify' => true]);
    }

    public function run(SiteTest $siteTest, ResponseInterface $response): SiteTestResults
    {
        foreach($this->testEngines as $engine) {
            if($engine::getId() == $siteTest->getType()) {
                return $engine->run($siteTest, $response);
            }
        }

        throw new \InvalidArgumentException();
    }
}