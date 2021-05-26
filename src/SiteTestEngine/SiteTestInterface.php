<?php

namespace App\SiteTestEngine;

use App\Entity\SiteTest;
use App\Entity\SiteTestResults;
use Psr\Http\Message\ResponseInterface;

interface SiteTestInterface {
    /**
     * @param SiteTest $siteTest
     * @param ResponseInterface $response
     * @return SiteTestResults
     *
     * method run site test against response
     */
    public function run(SiteTest $siteTest, ResponseInterface $response): SiteTestResults;

    /**
     * @return string
     *
     * return type ID
     */
    public static function getId(): string;
}