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

namespace MU\BloggingModule\Form\DataTransformer\Base;

use Symfony\Component\Form\DataTransformerInterface;
use MU\BloggingModule\Helper\ListEntriesHelper;

/**
 * List field transformer base class.
 *
 * This data transformer treats multi-valued list fields.
 */
abstract class AbstractListFieldTransformer implements DataTransformerInterface
{
    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * ListFieldTransformer constructor.
     *
     * @param ListEntriesHelper $listHelper ListEntriesHelper service instance
     */
    public function __construct(ListEntriesHelper $listHelper)
    {
        $this->listHelper = $listHelper;
    }

    /**
     * Transforms the object values to the normalised value.
     *
     * @param string|null $values
     *
     * @return array
     */
    public function transform($values)
    {
        if (null === $values || '' === $values) {
            return [];
        }

        return $this->listHelper->extractMultiList($values);
    }

    /**
     * Transforms an array with values back to the string.
     *
     * @param array $values
     *
     * @return string
     */
    public function reverseTransform($values)
    {
        if (!$values) {
            return '';
        }

        return '###' . implode('###', $values) . '###';
    }
}
