<?php

namespace App\Command;

use App\Entity\Site;
use App\Entity\SiteChecks;
use App\Repository\SiteChecksRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\TransferStats;
use http\Env\Request;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

class SiteCheckCommand extends Command
{
    protected static $defaultName = 'app:SiteCheck';
    protected static $defaultDescription = 'Add a short description for your command';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();

    }

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    public function testSite($site)
    {
        $entity = $this->entityManager;
        $client = new \GuzzleHttp\Client();

        $siteCheck = new SiteChecks();
        $siteCheck->setSite($site);

        try {
            $response = $client->request('GET', 'https://' . $site->getDomainName(), ['allow_redirects' => true, 'verify' => true,
                'on_stats' => function (TransferStats $stats) use ($siteCheck) {
                    $siteCheck->setCertificate($stats->getHandlerStats()['scheme']);
                    $siteCheck->setTimeServer($stats->getTransferTime());
                }]);

            if ($response->getStatusCode() == 200) {
                $siteCheck->setResult(SiteChecks::STATUS_OK);
            } else {
                $siteCheck->setResult(SiteChecks::STATUS_ERROR);
            }

            $siteCheck->setStatusCode($response->getStatusCode());
            $siteCheck->setSslStatus(SiteChecks::STATUS_OK);

        } catch (\Exception $e) {
            $siteCheck->setError($e->getMessage());
            if (str_contains($e->getMessage(), 'SSL')) {
                dump($siteCheck->getStatusCode());
                $siteCheck->setSslStatus(SiteChecks::STATUS_ERROR);
            } else {
                $siteCheck->setSslStatus(SiteChecks::STATUS_OK);
            }
            if ($siteCheck->getStatusCode() == null) {
                $siteCheck->setStatusCode(0);
            } else {
                $siteCheck->setStatusCode();
            }
            $siteCheck->setResult(SiteChecks::STATUS_ERROR);
        }

        $entity->persist($siteCheck);
        $entity->flush();


    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $em = $this->entityManager;
        $siteRepo = $em->getRepository("App:Site");
        $sites = $siteRepo->findAll();

        /** @var SiteChecksRepository $siteCheckRepo */
        $siteCheckRepo = $em->getRepository("App:SiteChecks");

        /** @var Site $site */
        foreach ($sites as $site) {

            // switch to $site->getLastSiteCheck();
            $sitesCheck = $siteCheckRepo->findOneBy(['site' => $site], ['id' => 'DESC']);

            // wyrzucic - dodaj parameter przy filtrowaniu listy
            if ($site->getStatus() != 1) {
                continue;
            }
            if ($sitesCheck && $sitesCheck->getCreatedAt() > new \DateTime($site->getFrequency() . " minutes ago")) {
                continue;
            }
            $this->testSite($site);
        }


        return Command::SUCCESS;
    }
}
