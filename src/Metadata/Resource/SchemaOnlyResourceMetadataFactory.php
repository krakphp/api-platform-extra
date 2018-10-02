<?php

namespace Krak\ApiPlatformExtra\Metadata\Resource;

use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Resource\ResourceMetadata;

class SchemaOnlyResourceMetadataFactory implements ResourceMetadataFactoryInterface
{
    private $metadataFactory;

    public function __construct(ResourceMetadataFactoryInterface $metadataFactory) {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * Creates a resource metadata.
     * @throws ResourceClassNotFoundException
     */
    public function create(string $resourceClass): ResourceMetadata {
        $resourceMetadata = $this->metadataFactory->create($resourceClass);
        return (bool) $resourceMetadata->getAttribute("schema_only")
            ? $resourceMetadata->withCollectionOperations([])->withItemOperations([])
            : $resourceMetadata;
    }
}
