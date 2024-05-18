<?php

declare(strict_types=1);

namespace App\Collection\Symfony\Component\Finder;

use Steevanb\PhpCollection\{
    ObjectCollection\AbstractObjectCollection,
    ScalarCollection\StringCollection
};
use Symfony\Component\Finder\SplFileInfo;

/** @extends AbstractObjectCollection<SplFileInfo> */
class SplFileInfoCollection extends AbstractObjectCollection
{
    public static function getValueFqcn(): string
    {
        return SplFileInfo::class;
    }

    public function getPathnames(): StringCollection
    {
        $return = new StringCollection();
        foreach ($this->toArray() as $splFileInfo) {
            $return->add($splFileInfo->getPathname());
        }

        return $return;
    }
}
