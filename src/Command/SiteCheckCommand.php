<?php

namespace App\Command;

use App\Entity\Site;
use App\Entity\SiteChecks;
use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use App\Repository\SiteChecksRepository;
use App\SiteTestEngine\HeaderSiteTest;
use App\SiteTestEngine\KeywordSiteTest;
use App\SiteTestEngine\SiteTestProcessor;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\TransferStats;
use http\Env\Response;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Psr7;

class SiteCheckCommand extends Command
{
    protected static $defaultName = 'app:SiteCheck';
    protected static $defaultDescription = 'Add a short description for your command';

    private $entityManager;

    /**
     * @var SiteTestProcessor
     */
    private $siteTestProcesseor;

    public function __construct(EntityManagerInterface $entityManager, SiteTestProcessor $siteTestProcessor)
    {
        $this->entityManager = $entityManager;
        $this->siteTestProcesseor = $siteTestProcessor;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    public function testSite($site)
    {
        $em = $this->entityManager;
        $client = new \GuzzleHttp\Client();

        $siteCheck = new SiteChecks();
        $siteCheck->setSite($site);
        $response = null;
        try {
            $response = $client->request('GET', 'https://' . $site->getDomainName(), ['allow_redirects' => true, 'verify' => true,
                'on_stats' => function (TransferStats $stats) use ($siteCheck) {
                    $siteCheck->setCertificate($stats->getHandlerStats()['scheme']);
                    $siteCheck->setTimeServer($stats->getTransferTime());
                }]);
            $siteCheck->setStatusCode($response->getStatusCode());
            $siteCheck->setSslStatus(SiteChecks::STATUS_OK);

            if ($response->getStatusCode() == 200) {
                $siteCheck->setResult(SiteChecks::STATUS_OK);
            } else {
                $siteCheck->setResult(SiteChecks::STATUS_ERROR);
            }

        } catch (\Exception $e) {
            $siteCheck->setError($e->getMessage());
            if (str_contains($e->getMessage(), 'SSL')) {
                $siteCheck->setSslStatus(SiteChecks::STATUS_ERROR);
            } else {
                $siteCheck->setSslStatus(SiteChecks::STATUS_OK);
            }
            if ($siteCheck->getStatusCode() == null) {
                $siteCheck->setStatusCode(0);
            } else {
                $siteCheck->setStatusCode(20);
            }
            $siteCheck->setResult(SiteChecks::STATUS_ERROR);
        }

        $em->persist($siteCheck);
        $em->flush();
        if ($response) {
            $this->advanceSiteTest($response, $site);
        }
    }


    public function advanceSiteTest(Psr7\Response $response, Site $site)
    {
        $em = $this->entityManager;
        $siteTest = $em->getRepository("App:SiteTest")->findBy(['site' => $site]);

        foreach ($siteTest as $test) {
            try {
                $testCheck = $test->getTestCheckResult()->last();
                if ($testCheck !== false && $testCheck->getCreatedAt() > new \DateTime($test->getFrequency() . " minutes ago")) {
                    continue;
                }
                $siteTestResults = $this->siteTestProcesseor->run($test, $response);
                $siteTestResults->setSite($site);
                $siteTestResults->setSiteTest($test);

                $em->persist($siteTestResults);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
        }
        $em->flush();
    }

    protected
    function execute(InputInterface $input, OutputInterface $output): int
    {
        $em = $this->entityManager;
        $siteRepo = $em->getRepository("App:Site");
        $sites = $siteRepo->findAll();

        /** @var Site $site */
        foreach ($sites as $site) {

            $sitesCheck = $site->getSiteCheck()->last();
            if (($site->getStatus() != 1) || ($sitesCheck && $sitesCheck->getCreatedAt() > new \DateTime($site->getFrequency() . " minutes ago"))) {
                continue;
            }
            $this->testSite($site);
        }

        return Command::SUCCESS;
    }
}