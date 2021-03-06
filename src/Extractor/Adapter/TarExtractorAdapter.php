<?php

/*
 * This file is part of the Extractor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Mmoreram\Extractor\Adapter;

use Mmoreram\Extractor\Adapter\Abstracts\AbstractExtractorAdapter;
use Mmoreram\Extractor\Adapter\Interfaces\ExtractorAdapterInterface;
use Phar;
use PharData;
use Symfony\Component\Finder\Finder;

/**
 * Class TarExtractorAdapter
 */
class TarExtractorAdapter extends AbstractExtractorAdapter implements ExtractorAdapterInterface
{
    /**
     * Return the adapter identifier
     *
     * @return string Adapter identifier
     */
    public function getIdentifier()
    {
        return 'Tar';
    }

    /**
     * Checks if current adapter can be used
     *
     * @return boolean Adapter usable
     */
    public function isAvailable()
    {
        return class_exists('\PharData');
    }

    /**
     * Extract files from a filepath
     *
     * @param string $filePath File path
     *
     * @return Finder
     */
    public function extract($filePath)
    {
        $directory = $this->directory->getDirectoryPath();
        $pharArchive = new PharData($filePath, null, null, Phar::TAR);
        $pharArchive->extractTo($directory);

        return $this->createFinderFromDirectory($directory);
    }
}
