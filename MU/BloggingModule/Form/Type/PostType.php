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

namespace MU\BloggingModule\Form\Type;

use MU\BloggingModule\Form\Type\Base\AbstractPostType;

use Symfony\Component\Form\FormBuilderInterface;
use MU\BloggingModule\Form\Type\Field\MultiListType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Post editing form type implementation class.
 */
class PostType extends AbstractPostType
{
    /**
     * Adds basic entity fields.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function addEntityFields(FormBuilderInterface $builder, array $options = [])
    {
    	parent::addEntityFields($builder, $options);
    	
    	$listEntries = $this->listHelper->getEntries('post', 'positionOfBlock');
    	$choices = [];
    	$choiceAttributes = [];
    	foreach ($listEntries as $entry) {
    	    $choices[$entry['text']] = $entry['value'];
    	    $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
    	}
    	$builder->add('positionOfBlock', ChoiceType::class, [
    	    'label' => $this->__('Position of block') . ':',
    	    'empty_data' => 'none',
    	    'attr' => [
    	        'class' => 'bloggerblock',
    	        'title' => $this->__('Choose the position of block.')
    	    ],
    	    'required' => false,
    	    'placeholder' => $this->__('Choose an option'),
    	    'choices' => $choices,
    	    'choice_attr' => $choiceAttributes,
    	    'multiple' => false,
    	    'expanded' => false
    	]);
        
        $listEntries = $this->listHelper->getEntries('post', 'similarArticles');
        $choices = [];
        $choiceAttributes = [];
        foreach ($listEntries as $entry) {
            $choices[$entry['text']] = $entry['value'];
            $choiceAttributes[$entry['text']] = ['title' => $entry['title']];
        }
        $builder->add('similarArticles', MultiListType::class, [
            'label' => $this->__('Similar articles') . ':',
            'empty_data' => 'none',
            'attr' => [
                'class' => '',
                'title' => $this->__('Choose the similar articles')
            ],
            'required' => false,
            'placeholder' => $this->__('Choose an option'),
            'choices' => $choices,
            'choices_as_values' => true,
            'choice_attr' => $choiceAttributes,
            'multiple' => true,
            'expanded' => false
        ]);
    }
}
