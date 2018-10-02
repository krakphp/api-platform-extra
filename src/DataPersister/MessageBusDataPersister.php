<?php

namespace Krak\ApiPlatformExtra\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageBusDataPersister implements DataPersisterInterface
{
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus) {
        $this->messageBus = $messageBus;
    }

    /**
     * Is the data supported by the persister?
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function supports($data): bool {
        return true;
    }

    /**
     * Persists the data.
     *
     * @param mixed $data
     *
     * @return object|void Void will not be supported in API Platform 3, an object should always be returned
     */
    public function persist($data) {
        return $this->messageBus->dispatch($data);
    }

    /**
     * Removes the data.
     *
     * @param mixed $data
     */
    public function remove($data) {
        return $this->messageBus->dispatch($data);
    }
}
