<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Logger\ServiceFactory;

use OxidEsales\EshopCommunity\Internal\Logger\DataObject\MonologConfiguration;
use OxidEsales\EshopCommunity\Internal\Logger\DataObject\MonologConfigurationInterface;
use OxidEsales\EshopCommunity\Internal\Logger\Mapper\LogLevelMapperInterface;
use OxidEsales\EshopCommunity\Internal\Logger\Mapper\MonologLogLevelMapper;
use OxidEsales\EshopCommunity\Internal\Logger\ServiceWrapper\LoggerServiceWrapper;
use OxidEsales\EshopCommunity\Internal\Logger\Validator\PsrLoggerConfigurationValidator;
use OxidEsales\EshopCommunity\Internal\Utility\ContextInterface;
use Psr\Log\LoggerInterface;

/**
 * @internal
 */
class LoggerServiceFactory
{
    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * LoggerServiceFactory constructor.
     *
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return new LoggerServiceWrapper(
            $this->getMonologLoggerFactory()->create()
        );
    }

    /**
     * @return MonologLoggerServiceFactory
     */
    private function getMonologLoggerFactory()
    {
        return new MonologLoggerServiceFactory(
            $this->getMonologConfiguration(),
            $this->getMonologLogLevelMapper()
        );
    }

    /**
     * @return MonologConfigurationInterface
     */
    private function getMonologConfiguration()
    {
        return new MonologConfiguration(
            'OXID Logger',
            $this->context->getLogFilePath(),
            $this->context->getLogLevel()
        );
    }

    /**
     * @return LogLevelMapperInterface
     */
    private function getMonologLogLevelMapper()
    {
        return new MonologLogLevelMapper(
            $this->getLoggerConfigurationValidator()
        );
    }

    /**
     * @return PsrLoggerConfigurationValidator
     */
    private function getLoggerConfigurationValidator()
    {
        return new PsrLoggerConfigurationValidator();
    }
}
