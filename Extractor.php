<?php

/**
 * This file is part of the Extractor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace Mmoreram\Extractor;

use Mmoreram\Extractor\Adapter\Interfaces\ExtractorAdapterInterface;
use Mmoreram\Extractor\Exception\AdapterNotAvailableException;
use Mmoreram\Extractor\Exception\ExtensionNotSupportedException;
use Mmoreram\Extractor\Exception\FileNotFoundException;
use Mmoreram\Extractor\Resolver\ExtensionResolver;
use Mmoreram\Extractor\Resolver\Interfaces\ExtensionResolverInterface;
use Symfony\Component\Finder\Finder;

/**
 * Class Extractor
 */
class Extractor
{
    /**
     * @var ExtensionResolver
     *
     * Extension resolver
     */
    protected $extensionResolver;

    /**
     * Construct method
     *
     * @param ExtensionResolverInterface $extensionResolver Extension resolver
     */
    public function __construct(ExtensionResolverInterface $extensionResolver)
    {
        $this->extensionResolver = $extensionResolver;
    }

    /**
     * Extract files from compressed file
     *
     * @param string $filePath Compressed file path
     *
     * @return Finder Finder instance with all files added
     *
     * @throws ExtensionNotSupportedException Exception not found
     * @throws AdapterNotAvailableException   Adapter not available
     * @throws FileNotFoundException          File not found
     */
    public function extractFromFile($filePath)
    {
        if (!is_file($filePath)) {

            throw new FileNotFoundException($filePath);
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        $extractorAdapterNamespace = $this
            ->extensionResolver
            ->getAdapterNamespaceGivenExtension($extension);

        $extractorAdapter = $this
            ->instanceExtractorAdapter($extractorAdapterNamespace);

        if (!$extractorAdapter->isAvailable()) {

            throw new AdapterNotAvailableException($extractorAdapter->getIdentifier());
        }

        return $extractorAdapter->extract($filePath);
    }

    /**
     * Instance new extractor adapter given its namespace
     *
     * @param string $extractorAdapterNamespace Extractor Adapter namespace
     *
     * @return ExtractorAdapterInterface Extrator adapter
     */
    protected function instanceExtractorAdapter($extractorAdapterNamespace)
    {
        return new $extractorAdapterNamespace();
    }
}
