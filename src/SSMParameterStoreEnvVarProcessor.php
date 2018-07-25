<?php

namespace SSMParameterStoreEnvVar;

use Aws\Ssm\SsmClient;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;


/**
 * Created by PhpStorm.
 * User: eepstein
 * Date: 7/20/18
 * Time: 2:53 PM
 */

/**
 * Class SSMParameterStoreEnvVarProcessor
 */
class SSMParameterStoreEnvVarProcessor implements EnvVarProcessorInterface
{
    /**
     * @var SsmClient
     */
    private $ssmClient;

    /**
     * SSMParameterStoreEnvVarProcessor constructor.
     * @param SsmClient $ssmClient
     */
    public function __construct(SsmClient $ssmClient)
    {
        $this->ssmClient = $ssmClient;
    }

    /**
     * Returns the value of the given variable as managed by the current instance.
     *
     * @param string   $prefix The namespace of the variable
     * @param string   $name   The name of the variable within the namespace
     * @param \Closure $getEnv A closure that allows fetching more env vars
     *
     * @return mixed
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\RuntimeException on error
     */
    public function getEnv($prefix, $name, \Closure $getEnv)
    {
        return $this->ssmClient
            ->getParameter(['Name' => $name, 'WithDecryption' => true])->search('Parameter.Value');
    }

    /**
     * @return string[] The PHP-types managed by getEnv(), keyed by prefixes
     */
    public static function getProvidedTypes()
    {
        return [
            'ssm' => 'string'
        ];
    }
}