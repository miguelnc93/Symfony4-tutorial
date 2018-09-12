<?php
/**
 * Created by PhpStorm.
 * User: UserName
 * Date: 8/28/2018
 * Time: 5:17 PM
 */

namespace App\Service;


use Psr\Log\LoggerInterface;

class Greeting
{
    /**
     * Greeting constructor.
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function greet(string $name):string
    {
        $this->logger->info("Greeted $name");
        return "Hello $name";
    }
}