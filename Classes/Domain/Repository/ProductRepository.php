<?php
namespace Aoe\RestlerExamples\Domain\Repository;

use Aoe\RestlerExamples\Domain\Model\Product;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ProductRepository extends Repository
{
    /**
     * set default query-settings:
     *  - It should not matter on which page are the records
     *
     * @return void
     */
    public function initializeObject()
    {
        /** @var $defaultQuerySettings Typo3QuerySettings */
        $defaultQuerySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
        $defaultQuerySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($defaultQuerySettings);
    }

    /**
     * @param integer $uid
     * @return Product
     */
    public function findOne($uid)
    {
        $query = $this->createQuery();
        return $query
            ->matching(
                $query->logicalAnd(
                    $query->equals('uid', $uid)
                )
            )
            ->execute()
            ->getFirst();
    }

    /**
     * @return array<Product>
     */
    public function findAll()
    {
        $query = $this->createQuery();
        return $query->execute()->toArray();
    }
}
