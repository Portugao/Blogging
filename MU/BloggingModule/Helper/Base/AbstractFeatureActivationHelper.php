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

/**
 * Helper base class for dynamic feature enablement methods.
 */
abstract class AbstractFeatureActivationHelper
{
    /**
     * Categorisation feature
     */
    const CATEGORIES = 'categories';
    
    /**
     * Translation feature
     */
    const TRANSLATIONS = 'translations';
    
    /**
     * This method checks whether a certain feature is enabled for a given entity type or not.
     *
     * @param string $feature     Name of requested feature
     * @param string $objectType  Currently treated entity type
     *
     * @return boolean True if the feature is enabled, false otherwise
     */
    public function isEnabled($feature, $objectType)
    {
        if ($feature == self::CATEGORIES) {
            $method = 'hasCategories';
            if (method_exists($this, $method)) {
                return $this->$method($objectType);
            }
    
            return in_array($objectType, ['post']);
        }
        if ($feature == self::TRANSLATIONS) {
            $method = 'hasTranslations';
            if (method_exists($this, $method)) {
                return $this->$method($objectType);
            }
    
            return in_array($objectType, ['post']);
        }
    
        return false;
    }
}
