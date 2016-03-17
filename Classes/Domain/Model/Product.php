<?php
namespace Aoe\RestlerExamples\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

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
        return $this->getCObject()->parseFunc($this->description, array(), '< lib.parseFunc_RTE');
    }

    /**
     * @return ContentObjectRenderer
     */
    private function getCObject()
    {
        if (null === $this->cObject) {
            $objectManager = new ObjectManager();
            $this->cObject = $objectManager->get('TYPO3\\CMS\\Frontend\ContentObject\\ContentObjectRenderer');
        }
        return $this->cObject;
    }
    /**
     * @return UriBuilder
     */
    private function getUriBuilder()
    {
        if (null === $this->uriBuilder) {
            $objectManager = new ObjectManager();
            /** @var ConfigurationManagerInterface $r */
            $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
            $configurationManager->setContentObject($objectManager->get('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer'));
            $this->uriBuilder = $objectManager->get('TYPO3\\CMS\\Extbase\\Mvc\\Web\\Routing\\UriBuilder');
        }
        return $this->uriBuilder;
    }
}
