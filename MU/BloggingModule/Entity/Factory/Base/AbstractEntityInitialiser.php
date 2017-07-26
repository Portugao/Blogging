<?php
/**
 * Blogging.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link http://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\BloggingModule\Entity\Factory\Base;

use MU\BloggingModule\Entity\PostEntity;
use MU\BloggingModule\Helper\ListEntriesHelper;

/**
 * Entity initialiser class used to dynamically apply default values to newly created entities.
 */
abstract class AbstractEntityInitialiser
{
    /**
     * @var ListEntriesHelper Helper service for managing list entries
     */
    protected $listEntriesHelper;

    /**
     * EntityInitialiser constructor.
     *
     * @param ListEntriesHelper $listEntriesHelper Helper service for managing list entries
     */
    public function __construct(ListEntriesHelper $listEntriesHelper)
    {
        $this->listEntriesHelper = $listEntriesHelper;
    }

    /**
     * Initialises a given post instance.
     *
     * @param PostEntity $entity The newly created entity instance
     *
     * @return PostEntity The updated entity instance
     */
    public function initPost(PostEntity $entity)
    {
        $listEntries = $this->listEntriesHelper->getEntries('post', 'positionOfAdvertising1');
        $items = [];
        foreach ($listEntries as $listEntry) {
            if (true === $listEntry['default']) {
                $items[] = $listEntry['value'];
            }
        }
        $entity->setPositionOfAdvertising1(implode('###', $items));

        $listEntries = $this->listEntriesHelper->getEntries('post', 'positionOfBlock');
        $items = [];
        foreach ($listEntries as $listEntry) {
            if (true === $listEntry['default']) {
                $items[] = $listEntry['value'];
            }
        }
        $entity->setPositionOfBlock(implode('###', $items));

        $listEntries = $this->listEntriesHelper->getEntries('post', 'positionOfAdvertising2');
        $items = [];
        foreach ($listEntries as $listEntry) {
            if (true === $listEntry['default']) {
                $items[] = $listEntry['value'];
            }
        }
        $entity->setPositionOfAdvertising2(implode('###', $items));

        $listEntries = $this->listEntriesHelper->getEntries('post', 'positionOfBlock2');
        $items = [];
        foreach ($listEntries as $listEntry) {
            if (true === $listEntry['default']) {
                $items[] = $listEntry['value'];
            }
        }
        $entity->setPositionOfBlock2(implode('###', $items));

        $listEntries = $this->listEntriesHelper->getEntries('post', 'positionOfAdvertising3');
        $items = [];
        foreach ($listEntries as $listEntry) {
            if (true === $listEntry['default']) {
                $items[] = $listEntry['value'];
            }
        }
        $entity->setPositionOfAdvertising3(implode('###', $items));

        $listEntries = $this->listEntriesHelper->getEntries('post', 'positionOfBlock3');
        $items = [];
        foreach ($listEntries as $listEntry) {
            if (true === $listEntry['default']) {
                $items[] = $listEntry['value'];
            }
        }
        $entity->setPositionOfBlock3(implode('###', $items));

        $listEntries = $this->listEntriesHelper->getEntries('post', 'similarArticles');
        foreach ($listEntries as $listEntry) {
            if (true === $listEntry['default']) {
                $entity->setSimilarArticles($listEntry['value']);
                break;
            }
        }


        return $entity;
    }

    /**
     * Returns the list entries helper.
     *
     * @return ListEntriesHelper
     */
    public function getListEntriesHelper()
    {
        return $this->listEntriesHelper;
    }
    
    /**
     * Sets the list entries helper.
     *
     * @param ListEntriesHelper $listEntriesHelper
     *
     * @return void
     */
    public function setListEntriesHelper($listEntriesHelper)
    {
        if ($this->listEntriesHelper != $listEntriesHelper) {
            $this->listEntriesHelper = $listEntriesHelper;
        }
    }
    
}
