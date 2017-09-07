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

namespace MU\BloggingModule\Helper;

use MU\BloggingModule\Helper\Base\AbstractListEntriesHelper;
use MU\BloggingModule\Entity\Factory\EntityFactory;

/**
 * Helper implementation class for list field entries related methods.
 */
class ListEntriesHelper extends AbstractListEntriesHelper
{	
	/**
	 * @var EntityFactory
	 */
	protected $entityfactory;
	
    /**
     * Get 'similar articles' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getSimilarArticlesEntriesForPost()
    {
    	$postRepository = $this->entityFactory->getRepository('post');
    	$posts = $postRepository->selectWhere();

        $states = [];
    	foreach ($posts as $post) {    	
    		$thisPost = $postRepository->find($post['id']);
        
            $states[] = [
                'value'   => $thisPost['id'],
                'text'    => $thisPost['title'],
                'title'   => '',
                'image'   => '',
                'default' => false
            ];
    	}
    
        return $states;
    }
    
    public function setEntityFactory(EntityFactory $entityFactory)
    {
    	$this->entityFactory = $entityFactory;
    }
}
