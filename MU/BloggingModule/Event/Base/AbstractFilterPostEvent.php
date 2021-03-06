<?php
/**
 * Blogging.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link https://ziku.la
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\BloggingModule\Event\Base;

use Symfony\Component\EventDispatcher\Event;
use MU\BloggingModule\Entity\PostEntity;

/**
 * Event base class for filtering post processing.
 */
class AbstractFilterPostEvent extends Event
{
    /**
     * @var PostEntity Reference to treated entity instance.
     */
    protected $post;

    /**
     * @var array Entity change set for preUpdate events.
     */
    protected $entityChangeSet = [];

    /**
     * FilterPostEvent constructor.
     *
     * @param PostEntity $post Processed entity
     * @param array $entityChangeSet Change set for preUpdate events
     */
    public function __construct(PostEntity $post, array $entityChangeSet = [])
    {
        $this->post = $post;
        $this->entityChangeSet = $entityChangeSet;
    }

    /**
     * Returns the entity.
     *
     * @return PostEntity
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Returns the change set.
     *
     * @return array Entity change set
     */
    public function getEntityChangeSet()
    {
        return $this->entityChangeSet;
    }
}
