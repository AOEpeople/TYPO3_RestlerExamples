<?php

declare(strict_types=1);

namespace Aoe\RestlerExamples\Domain\Model;

use Aoe\Restler\System\TYPO3\Loader;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class Product extends AbstractEntity
{
    public $uid;

    public string $description;

    public string $detailsPage;

    public string $name;

    private ?ContentObjectRenderer $cObject = null;

    private ?UriBuilder $uriBuilder = null;

    /**
     * overwrite parent method, so that we can override the following properties:
     *  - property 'detailsPage': build the URL of the detailsPage (otherwise the property would contain the pageId as value)
     *  - property 'description': render the RTE-content correct as HTML (otherwise the property would contain pseudo-HTML-code)
     */
    public function _setProperty(string $propertyName, mixed $propertyValue): bool
    {
        $result = parent::_setProperty($propertyName, $propertyValue);

        if ($propertyName === 'detailsPage') {
            $this->detailsPage = $this->buildDetailPageUrl();
        }

        if ($propertyName === 'description') {
            $this->description = $this->renderDescriptionAsHtml();
        }

        return $result;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDetailsPage(): string
    {
        return $this->detailsPage;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function buildDetailPageUrl(): string
    {
        return $this->getUriBuilder()
            ->setAbsoluteUriScheme('http')
            ->setCreateAbsoluteUri(true)
            ->setTargetPageUid((int) $this->detailsPage)
            ->buildFrontendUri();
    }

    private function renderDescriptionAsHtml(): string
    {
        return $this->getCObject()
            ->parseFunc($this->description, [], '< lib.parseFunc_RTE');
    }

    private function getCObject(): ContentObjectRenderer
    {
        if ($this->cObject === null) {
            $this->cObject = $this->initializeCObject();
        }

        return $this->cObject;
    }

    private function getUriBuilder(): UriBuilder
    {
        if ($this->uriBuilder === null) {
            $this->initializeCObject();

            $this->uriBuilder = GeneralUtility::makeInstance(ObjectManager::class)->get(UriBuilder::class);
        }

        return $this->uriBuilder;
    }

    private function initializeCObject(): ContentObjectRenderer
    {
        /** @var Loader $typo3Loader */
        $typo3Loader = GeneralUtility::makeInstance(Loader::class);
        $typo3Loader->initializeFrontendRendering();

        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
        $configurationManager->setContentObject($contentObjectRenderer);

        return $contentObjectRenderer;
    }
}
