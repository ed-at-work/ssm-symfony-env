<?php

namespace SSMParameterStoreEnvVar\Tests;

use AppKernel;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Created by PhpStorm.
 * User: eepstein
 * Date: 7/20/18
 * Time: 4:46 PM
 */
class SSMParameterStoreEnvVarTests extends TestCase
{
    /**
     * @var AppKernel
     */
    protected $kernel;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setUp()
    {
        parent::setUp();
        $this->kernel = new AppKernel('test', true);
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();

        $ssmClient = $this->container->get('Aws\Ssm\SsmClient');
        $ssmClient->putParameter(['Name' => 'app_secret', 'Type' => 'SecureString', 'Value' => 'abc123']);
        $ssmClient->putParameter(['Name' => 'dynamicspecial', 'Type' => 'String', 'Value' => 'beepboop']);
        $ssmClient->putParameter(['Name' => 'nondynamic', 'Type' => 'String', 'Value' => 'floop']);
    }

    public function tearDown()
    {
        $ssmClient = $this->container->get('Aws\Ssm\SsmClient');
        $ssmClient->deleteParameter(['Name' => 'app_secret']);
        $ssmClient->deleteParameter(['Name' => 'dynamicspecial']);
        $ssmClient->deleteParameter(['Name' => 'nondynamic']);

        parent::tearDown();
    }

    public function testDefaultNonDynamic()
    {
        $testParam1 = $this->container->getParameter('env(testparam1)');
        $this->assertEquals('default_value', $testParam1);
    }

    public function testSsmDynamic()
    {
        $dynamic = $this->container->getParameter('env(dynamic)');
        $this->assertEquals('beepboop', $dynamic);
    }

    public function testSsmNonDynamic()
    {
        $regular = $this->container->getParameter('regular');
        $this->assertEquals('floop', $regular);
    }
}