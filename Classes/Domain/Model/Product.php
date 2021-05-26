<?php
namespace Aoe\RestlerExamples\Domain\Model;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Site\Entity\NullSite;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Page\PageRepository;

class Product extends AbstractEntity
{
    /**
     * @var ContentObjectRenderer
     */
    private $cObject;
    /**
     * @var UriBuilder
     */
    private $uriBuilder;

    /**
     * @var int The uid of the record. The uid is only unique in the context of the database table.
     */
    public $uid;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $detailsPage;
    /**
     * @var string
     */
    public $name;

    /**
     * overwrite parent method, so that we can override the following properties:
     *  - property 'detailsPage': build the URL of the detailsPage (otherwise the property would contain the pageId as value)
     *  - property 'description': render the RTE-content correct as HTML (otherwise the property would contain pseudo-HTML-code)
     *
     * @param string $propertyName
     * @param mixed $propertyValue
     * @return boolean
     */
    public function _setProperty($propertyName, $propertyValue) {
        $result = parent::_setProperty($propertyName, $propertyValue);

        if ($propertyName === 'detailsPage') {
            $this->detailsPage = $this->buildDetailPageUrl();
        }
        if ($propertyName === 'description') {
            $this->description = $this->renderDescriptionAsHtml();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @return string
     */
    public function getDetailsPage()
    {
        return $this->detailsPage;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    private function buildDetailPageUrl()
    {
        return $this->getUriBuilder()
            ->setAbsoluteUriScheme('http')
            ->setCreateAbsoluteUri(true)
            ->setTargetPageUid((integer) $this->detailsPage)
            ->buildFrontendUri();
    }
    /**
     * @return string
     */
    private function renderDescriptionAsHtml()
    {
        return $this->getCObject()->parseFunc($this->description, [], '< lib.parseFunc_RTE');
    }

    /**
     * @return ContentObjectRenderer
     */
    private function getCObject()
    {
        if (null === $this->cObject) {
            $this->cObject = $this->initializeCObject();
        }
        return $this->cObject;
    }
    /**
     * @return UriBuilder
     */
    private function getUriBuilder()
    {
        if (null === $this->uriBuilder) {
            $this->initializeCObject();

            $this->uriBuilder = GeneralUtility::makeInstance(ObjectManager::class)->get(UriBuilder::class);
        }
        return $this->uriBuilder;
    }

    /**
     * @return ContentObjectRenderer
     */
    private function initializeCObject() {
        $this->initializeTsfe();

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        /** @var ContentObjectRenderer $contentObjectRenderer */
        $contentObjectRenderer = $objectManager->get(ContentObjectRenderer::class);
        /** @var ConfigurationManagerInterface $configurationManager */
        $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
        $configurationManager->setContentObject($contentObjectRenderer);

        return $contentObjectRenderer;
    }

    /**
     * @param integer $pageId
     * @param integer $type
     * @return TypoScriptFrontendController
     */
    private function initializeTsfe($pageId = 0, $type = 0)
    {
        if (class_exists(\TYPO3\CMS\Core\Site\Entity\NullSite::class)) {
            $context = GeneralUtility::makeInstance(Context::class);
            $nullSite = new NullSite();
            $GLOBALS['TSFE'] = GeneralUtility::makeInstance(
                TypoScriptFrontendController::class,
                $context,
                $nullSite,
                $nullSite->getDefaultLanguage()
            );
            $GLOBALS['TSFE']->sys_page = GeneralUtility::makeInstance(PageRepository::class, $context);
        } else {
            if ($type > 0) {
                $_GET['type'] = $type;
            }
            if (false === array_key_exists('TSFE', $GLOBALS)) {
                $GLOBALS['TSFE'] = GeneralUtility::makeInstance(
                    TypoScriptFrontendController::class,
                    $GLOBALS['TYPO3_CONF_VARS'],
                    $pageId,
                    $type
                );
                $GLOBALS['TSFE']->sys_page = GeneralUtility::makeInstance(PageRepository::class);
            }
        }

        return $GLOBALS['TSFE'];
    }
}
