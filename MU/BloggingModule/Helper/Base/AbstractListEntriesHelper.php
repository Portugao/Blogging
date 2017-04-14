<?php
/**
 * Blogging.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link http://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace MU\BloggingModule\Helper\Base;

use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;

/**
 * Helper base class for list field entries related methods.
 */
abstract class AbstractListEntriesHelper
{
    use TranslatorTrait;

    /**
     * ListEntriesHelper constructor.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }

    /**
     * Return the name or names for a given list item.
     *
     * @param string $value      The dropdown value to process
     * @param string $objectType The treated object type
     * @param string $fieldName  The list field's name
     * @param string $delimiter  String used as separator for multiple selections
     *
     * @return string List item name
     */
    public function resolve($value, $objectType = '', $fieldName = '', $delimiter = ', ')
    {
        if ((empty($value) && $value != '0') || empty($objectType) || empty($fieldName)) {
            return $value;
        }
    
        $isMulti = $this->hasMultipleSelection($objectType, $fieldName);
        if (true === $isMulti) {
            $value = $this->extractMultiList($value);
        }
    
        $options = $this->getEntries($objectType, $fieldName);
        $result = '';
    
        if (true === $isMulti) {
            foreach ($options as $option) {
                if (!in_array($option['value'], $value)) {
                    continue;
                }
                if (!empty($result)) {
                    $result .= $delimiter;
                }
                $result .= $option['text'];
            }
        } else {
            foreach ($options as $option) {
                if ($option['value'] != $value) {
                    continue;
                }
                $result = $option['text'];
                break;
            }
        }
    
        return $result;
    }
    

    /**
     * Extract concatenated multi selection.
     *
     * @param string  $value The dropdown value to process
     *
     * @return array List of single values
     */
    public function extractMultiList($value)
    {
        $listValues = explode('###', $value);
        $amountOfValues = count($listValues);
        if ($amountOfValues > 1 && $listValues[$amountOfValues - 1] == '') {
            unset($listValues[$amountOfValues - 1]);
        }
        if ($listValues[0] == '') {
            // use array_shift instead of unset for proper key reindexing
            // keys must start with 0, otherwise the dropdownlist form plugin gets confused
            array_shift($listValues);
        }
    
        return $listValues;
    }
    

    /**
     * Determine whether a certain dropdown field has a multi selection or not.
     *
     * @param string $objectType The treated object type
     * @param string $fieldName  The list field's name
     *
     * @return boolean True if this is a multi list false otherwise
     */
    public function hasMultipleSelection($objectType, $fieldName)
    {
        if (empty($objectType) || empty($fieldName)) {
            return false;
        }
    
        $result = false;
        switch ($objectType) {
            case 'post':
                switch ($fieldName) {
                    case 'workflowState':
                        $result = false;
                        break;
                    case 'block':
                        $result = false;
                        break;
                    case 'block2':
                        $result = false;
                        break;
                    case 'block3':
                        $result = false;
                        break;
                    case 'similarArticles':
                        $result = true;
                        break;
                }
                break;
        }
    
        return $result;
    }
    

    /**
     * Get entries for a certain dropdown field.
     *
     * @param string  $objectType The treated object type
     * @param string  $fieldName  The list field's name
     *
     * @return array Array with desired list entries
     */
    public function getEntries($objectType, $fieldName)
    {
        if (empty($objectType) || empty($fieldName)) {
            return [];
        }
    
        $entries = [];
        switch ($objectType) {
            case 'post':
                switch ($fieldName) {
                    case 'workflowState':
                        $entries = $this->getWorkflowStateEntriesForPost();
                        break;
                    case 'block':
                        $entries = $this->getBlockEntriesForPost();
                        break;
                    case 'block2':
                        $entries = $this->getBlock2EntriesForPost();
                        break;
                    case 'block3':
                        $entries = $this->getBlock3EntriesForPost();
                        break;
                    case 'similarArticles':
                        $entries = $this->getSimilarArticlesEntriesForPost();
                        break;
                }
                break;
        }
    
        return $entries;
    }

    
    /**
     * Get 'workflow state' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getWorkflowStateEntriesForPost()
    {
        $states = [];
        $states[] = [
            'value'   => 'waiting',
            'text'    => $this->__('Waiting'),
            'title'   => $this->__('Content has been submitted and waits for approval.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'approved',
            'text'    => $this->__('Approved'),
            'title'   => $this->__('Content has been approved and is available online.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'suspended',
            'text'    => $this->__('Suspended'),
            'title'   => $this->__('Content has been approved, but is temporarily offline.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'archived',
            'text'    => $this->__('Archived'),
            'title'   => $this->__('Content has reached the end and became archived.'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!waiting',
            'text'    => $this->__('All except waiting'),
            'title'   => $this->__('Shows all items except these which are waiting'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!approved',
            'text'    => $this->__('All except approved'),
            'title'   => $this->__('Shows all items except these which are approved'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!suspended',
            'text'    => $this->__('All except suspended'),
            'title'   => $this->__('Shows all items except these which are suspended'),
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => '!archived',
            'text'    => $this->__('All except archived'),
            'title'   => $this->__('Shows all items except these which are archived'),
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'block' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getBlockEntriesForPost()
    {
        $states = [];
        $states[] = [
            'value'   => 'none',
            'text'    => $this->__('None'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'block 2' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getBlock2EntriesForPost()
    {
        $states = [];
        $states[] = [
            'value'   => 'none',
            'text'    => $this->__('None'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'block 3' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getBlock3EntriesForPost()
    {
        $states = [];
        $states[] = [
            'value'   => 'none',
            'text'    => $this->__('None'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
    
    /**
     * Get 'similar articles' list entries.
     *
     * @return array Array with desired list entries
     */
    public function getSimilarArticlesEntriesForPost()
    {
        $states = [];
        $states[] = [
            'value'   => 'all',
            'text'    => $this->__('All'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
        $states[] = [
            'value'   => 'none',
            'text'    => $this->__('None'),
            'title'   => '',
            'image'   => '',
            'default' => false
        ];
    
        return $states;
    }
}
