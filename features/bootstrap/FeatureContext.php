<?php

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Add custom behat methods here.
 */
class FeatureContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
