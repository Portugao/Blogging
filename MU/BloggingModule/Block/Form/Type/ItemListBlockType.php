<?php
/**
 * Blogging.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\BloggingModule\Block\Form\Type;

use MU\BloggingModule\Block\Form\Type\Base\AbstractItemListBlockType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * List block form type implementation class.
 */
class ItemListBlockType extends AbstractItemListBlockType
{
    /**
     * Adds a sorting field.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addSortingField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add('sorting', ChoiceType::class, [
            'label' => $this->__('Sorting', 'mubloggingmodule') . ':',
            'empty_data' => 'default',
            'choices' => [
                $this->__('Random', 'mubloggingmodule') => 'random',
                $this->__('Newest', 'mubloggingmodule') => 'newest',
                $this->__('Updated', 'mubloggingmodule') => 'updated',
                $this->__('Start date', 'mubloggingmodule') => 'startdate',
                $this->__('Default', 'mubloggingmodule') => 'default'
            ],
            'multiple' => false,
            'expanded' => false
        ]);
    }
    // feel free to extend the list block form type class here
}
