<?php

declare(strict_types=1);

namespace App\Collection;

use Steevanb\PhpCollection\{
    ObjectCollection\AbstractObjectCollection,
    ScalarCollection\StringCollection
};
use Symfony\Component\Finder\SplFileInfo;

class SplFileInfoCollection extends AbstractObjectCollection
{
    public function __construct()
    {
        parent::__construct(SplFileInfo::class);
    }

    public function add(SplFileInfo $splFileInfo): static
    {
        return $this->doAdd($splFileInfo);
    }

    public function getPathnames(): StringCollection
    {
        $return = new StringCollection();
        foreach ($this->toArray() as $splFileInfo) {
            $return->add($splFileInfo->getPathname());
        }

        return $return;
    }

    /** @return array<SplFileInfo> */
    public function toArray(): array
    {
        return parent::toArray();
    }
}
