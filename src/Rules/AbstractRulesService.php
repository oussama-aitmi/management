<?php

namespace TTP\ApiBundle\Validator\Filter\Rules;

use Monolog\Logger;
use OAuth2\AdapterBundle\Traits\Session\SessionAwareTrait;
use TTP\ApiBundle\Entity\ApiEntityInterface;
use TTP\ApiBundle\Response\TTPApiResponse;
use TTP\ApiBundle\Response\TTPApiResponseException;
use TTP\ApiBundle\Traits\EntityManagerAwareTrait;

abstract class AbstractRulesService implements RulesServiceInterface
{
    use EntityManagerAwareTrait;
    use SessionAwareTrait;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName
     *
     * @return \TTP\ApiBundle\Validator\Filter\Rules\RulesServiceInterface
     */
    public function setEntityName(string $entityName): RulesServiceInterface
    {
        $this->entityName = $entityName;
        return $this;
    }

    /**
     * @return array $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
    * @param array $data
    *
    * @return \TTP\ApiBundle\Validator\Filter\Rules\AbstractRulesService
    */
    public function setData(array $data): RulesServiceInterface
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return \Monolog\Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     *
     * @return \TTP\ApiBundle\Service\Organization\FolderService
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    protected function extractEntityName()
    {

        $exp = explode('\\', $this->entityName);

        return strtoupper(str_replace('Entity', '', end($exp)));
    }

    public function getEntity(): ApiEntityInterface
    {
        if (!empty($this->data['id'])) {
            $entity = $this->getEntityManager()
                    ->getRepository($this->entityName)
                    ->find((int)$this->data['id']);

            if (!$entity) {
                $this->logger->error("Resource not found: ", ['id' => $this->data['id']]);

                $ttpResponse = new TTPApiResponse(400, TTPApiResponse::TYPE_RESOURCE_NOT_FOUND);
                throw new TTPApiResponseException($ttpResponse);
            }
        } else {
            $entity = new $this->entityName;
        }
        $entity->hydrate($this->data);

        return $entity;
    }

    /**
     * @param string $updatedAt
     *
     * @return \DateTime
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function updatedAt($updatedAt = null): \DateTime
    {
        return new \DateTime("now");
    }

    /**
     * @param mixed $createdAt
     *
     * @return \DateTime
     */
    public function createdAt($createdAt = null): \DateTime
    {
        if (null === $createdAt || empty($this->data['id'])) {
            return new \DateTime('now');
        }

        return $createdAt;
    }

    /**
     * uid generator
     *
     * @param string $uid
     *
     * return string
     */
    public function uid(string $uid = null): string
    {
        if (empty($uid)) {
            if (function_exists('com_create_guid') === true) {
                return trim(com_create_guid(), '{}');
            }
            return sprintf(
                '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(16384, 20479),
                mt_rand(32768, 49151),
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 65535)
            );
        }
        return $uid;
    }
}
