<?php

namespace Krak\ApiPlatformExtra\Operation;

use ApiPlatform\Core\Operation\PathSegmentNameGeneratorInterface;

/** Data gets incorrectly pluralized to datas, this fixes that. */
class PluralDataPathSegmentNameGenerator implements PathSegmentNameGeneratorInterface
{
    private $generator;

    public function __construct(PathSegmentNameGeneratorInterface $generator) {
        $this->generator = $generator;
    }

    /**
     * Transforms a given string to a valid path name which can be pluralized (eg. for collections).
     *
     * @param string $name usually a ResourceMetadata shortname
     * @param bool $collection
     *
     * @return string A string that is a part of the route name
     */
    public function getSegmentName(string $name, bool $collection = true): string {
        $segmentName = $this->generator->getSegmentName($name, $collection);
        if ($segmentName === 'datas') {
            return 'data';
        }
        if (preg_match('/-datas$/', $segmentName)) {
            return substr($segmentName, 0, -1 * strlen('-datas')) . '-data';
        }
        return $segmentName;
    }
}
