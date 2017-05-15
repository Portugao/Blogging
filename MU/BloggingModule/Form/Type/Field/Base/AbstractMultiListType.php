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

namespace MU\BloggingModule\Form\Type\Field\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use MU\BloggingModule\Form\DataTransformer\ListFieldTransformer;
use MU\BloggingModule\Helper\ListEntriesHelper;

/**
 * Multi list field type base class.
 */
abstract class AbstractMultiListType extends AbstractType
{
    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * MultiListType constructor.
     *
     * @param ListEntriesHelper $listHelper ListEntriesHelper service instance
     */
    public function __construct(ListEntriesHelper $listHelper)
    {
        $this->listHelper = $listHelper;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ListFieldTransformer($this->listHelper);
        $builder->addModelTransformer($transformer);
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'mubloggingmodule_field_multilist';
    }
}
