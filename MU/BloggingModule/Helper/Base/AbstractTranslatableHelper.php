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

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\SettingsModule\Api\ApiInterface\LocaleApiInterface;
use MU\BloggingModule\Entity\Factory\EntityFactory;

/**
 * Helper base class for translatable methods.
 */
abstract class AbstractTranslatableHelper
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var VariableApiInterface
     */
    protected $variableApi;

    /**
     * @var LocaleApiInterface
     */
    protected $localeApi;

    /**
     * @var EntityFactory
     */
    protected $entityFactory;

    /**
     * TranslatableHelper constructor.
     *
     * @param TranslatorInterface $translator    Translator service instance
     * @param RequestStack        $requestStack  RequestStack service instance
     * @param VariableApiInterface  $variableApi  VariableApi service instance
     * @param LocaleApiInterface   $localeApi    LocaleApi service instance
     * @param EntityFactory       $entityFactory EntityFactory service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack,
        VariableApiInterface $variableApi,
        LocaleApiInterface $localeApi,
        EntityFactory $entityFactory
    ) {
        $this->translator = $translator;
        $this->request = $requestStack->getCurrentRequest();
        $this->variableApi = $variableApi;
        $this->localeApi = $localeApi;
        $this->entityFactory = $entityFactory;
    }

    /**
     * Return list of translatable fields per entity.
     * These are required to be determined to recognise
     * that they have to be selected from according translation tables.
     *
     * @param string $objectType The currently treated object type
     *
     * @return array list of translatable fields
     */
    public function getTranslatableFields($objectType)
    {
        $fields = [];
        switch ($objectType) {
            case 'post':
                $fields = ['title', 'permalink', 'descriptionForGoogle', 'descriptionOfImageForArticle', 'summaryOfPost', 'content', 'content2', 'advertising', 'content3', 'content4', 'advertising2', 'content5', 'content6', 'advertising3'];
                break;
        }
    
        return $fields;
    }

    /**
     * Return the current language code.
     *
     * @return string code of current language
     */
    public function getCurrentLanguage()
    {
        return $this->request->getLocale();
    }

    /**
     * Return list of supported languages on the current system.
     *
     * @param string $objectType The currently treated object type
     *
     * @return array list of language codes
     */
    public function getSupportedLanguages($objectType)
    {
        if ($this->variableApi->getSystemVar('multilingual')) {
            return $this->localeApi->getSupportedLocales();
        }
    
        // if multi language is disabled use only the current language
        return [$this->getCurrentLanguage()];
    }

    /**
     * Returns a list of mandatory fields for each supported language.
     *
     * @param string $objectType The currently treated object type
     *
     * @return array
     */
    public function getMandatoryFields($objectType)
    {
        $mandatoryFields = [];
        foreach ($this->getSupportedLanguages($objectType) as $language) {
            $mandatoryFields[$language] = [];
        }
    
        return $mandatoryFields;
    }

    /**
     * Collects translated fields for editing.
     *
     * @param EntityAccess $entity The entity being edited
     *
     * @return array collected translations having the language codes as keys
     */
    public function prepareEntityForEditing($entity)
    {
        $translations = [];
        $objectType = $entity->get_objectType();
    
        if ($this->variableApi->getSystemVar('multilingual') != 1) {
            return $translations;
        }
    
        // check if there are any translated fields registered for the given object type
        $fields = $this->getTranslatableFields($objectType);
        if (!count($fields)) {
            return $translations;
        }
    
        // get translations
        $repository = $this->entityFactory->getObjectManager()->getRepository('Gedmo\Translatable\Entity\Translation');
        $entityTranslations = $repository->findTranslations($entity);
    
        $supportedLanguages = $this->getSupportedLanguages($objectType);
        $currentLanguage = $this->getCurrentLanguage();
        foreach ($supportedLanguages as $language) {
            if ($language == $currentLanguage) {
                foreach ($fields as $fieldName) {
                    if (null === $entity[$fieldName]) {
                        $entity[$fieldName] = '';
                    }
                }
                // skip current language as this is not treated as translation on controller level
                continue;
            }
            $translationData = [];
            foreach ($fields as $fieldName) {
                $translationData[$fieldName] = isset($entityTranslations[$language][$fieldName]) ? $entityTranslations[$language][$fieldName] : '';
            }
            // add data to collected translations
            $translations[$language] = $translationData;
        }
    
        return $translations;
    }

    /**
     * Post-editing method persisting translated fields.
     * This ensures easy compatibility to the Forms plugins where it
     * it is not possible yet to define sub arrays in the group attribute.
     *
     * @param EntityAccess  $entity        The entity being edited
     * @param FormInterface $form          Form containing translations
     * @param EntityManager $entityManager Entity manager
     */
    public function processEntityAfterEditing($entity, $form, $entityManager)
    {
        $objectType = $entity->get_objectType();
        $entityTransClass = '\\MU\\BloggingModule\\Entity\\' . ucfirst($objectType) . 'TranslationEntity';
        $repository = $entityManager->getRepository($entityTransClass);
    
        $supportedLanguages = $this->getSupportedLanguages($objectType);
        foreach ($supportedLanguages as $language) {
            if (!isset($form['translations' . $language])) {
                continue;
            }
            $translatedFields = $form['translations' . $language];
            foreach ($translatedFields as $fieldName => $formField) {
                if (!$formField->getData()) {
                    // avoid persisting unrequired translations
                    continue;
                }
                $repository->translate($entity, $fieldName, $language, $formField->getData());
            }
        }
    }
}
