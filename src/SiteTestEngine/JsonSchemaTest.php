<?php

namespace App\SiteTestEngine;


use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use Doctrine\DBAL\Schema\Constraint;
use GuzzleHttp\TransferStats;
use JsonSchema\Constraints\BaseConstraint;
use JsonSchema\Validator;
use Psr\Http\Message\ResponseInterface;
use function Symfony\Component\String\s;


class JsonSchemaTest implements SiteTestInterface
{

    public function run(SiteTest $siteTest, ResponseInterface $response): SiteTestResults
    {
        $siteTestResults = new SiteTestResults();
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://' . $siteTest->getSite()->getDomainName() . $siteTest->getUrl(), ['allow_redirects' => true, 'verify' => true]);

        $type = $response->getHeaders();
        $validator = new Validator();
        if ($type["Content-Type"][0] == "application/json") {
            $content = $response->getBody()->getContents();
            $value = json_decode($content);

            $request = ($value);

            if ($siteTest->getConfiguration()['requiredSchema']) {
                $validator->validate(
                    $request,
                    $siteTest->getConfiguration()['requiredSchema'],
                    \JsonSchema\Constraints\Constraint::CHECK_MODE_COERCE_TYPES
                ); // validates!

                $result = $validator->isValid();
                $siteTestResults->setResult($result);

            }
        } else {
            $siteTestResults->setResult(0);
            $siteTestResults->setDetails(\json_encode($validator->getErrors()));
//            $siteTestResults->setDetails($type["Content-Type"][0]);
        }
        return $siteTestResults;
    }

    public static function getId(): string
    {
        return 'Schema Test';
    }
}