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
     * overwrite parent method, so that we can override the property 'detailsPage' and set the URL of the detailsPage
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
     * @return UriBuilder
     */
    private function getUriBuilder()
    {
        if (null === $this->uriBuilder) {
            $objectManager = new ObjectManager();
            /** @var ConfigurationManagerInterface $r */
            $configurationManager = $objectManager->get(ConfigurationManagerInterface::class);
            $configurationManager->setContentObject($objectManager->get(ContentObjectRenderer::class));
            $this->uriBuilder = $objectManager->get(UriBuilder::class);
        }
        return $this->uriBuilder;
    }
}
