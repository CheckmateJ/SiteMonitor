<?php

namespace App\Command;

use App\Entity\Site;
use App\Entity\SiteChecks;
use App\Repository\SiteChecksRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    private $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;

        parent::__construct();

    }

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    public function saveToDatabase($site)
    {

        $entity = $this->container->get('doctrine')->getManager();
        $client = new \GuzzleHttp\Client();
        $codeOk = 1;
        $codeNotOk = 0;

        $siteCheck = new SiteChecks();
        $response = $client->request('GET', $site->getDomainName(), ['allow_redirects' => true]);

        $siteCheck->setSite($site);

        if ($response->getStatusCode() == 200) {
            $siteCheck->setResult($codeOk);
        } else {
            $siteCheck->setResult($codeNotOk);
        }

        $siteCheck->setStatusCode($response->getStatusCode());
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

            $sitesChecks = $siteCheckRepo->findOneBy(['site' => $site], ['id' => 'DESC']);

            if ($sitesChecks) {
                $created = $sitesChecks->getCreatedAt();
                $date = new \DateTime();
                $interval = $date->diff($created);
                $minutes = $interval->i;
                $hours = $interval->h * 60;
                $days = $interval->d * 60 * 24;

                $result = $minutes + $hours + $days;

                if ($result > $site->getFrequency()) {
                    $this->saveToDatabase($site);
                }

            } else {
                $this->saveToDatabase($site);
            }
        }


        return Command::SUCCESS;
    }
}
