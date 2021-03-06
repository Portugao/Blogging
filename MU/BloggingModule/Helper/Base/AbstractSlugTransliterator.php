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

namespace MU\BloggingModule\Helper\Base;

use Gedmo\Sluggable\Util\Urlizer;

/**
 * Custom slug transliterator for proper handling of umlauts and accents during permalink generation.
 *
 * @see https://github.com/Atlantic18/DoctrineExtensions/pull/1504
 */
abstract class AbstractSlugTransliterator
{
    /**
     * Transliterates a given text.
     *
     * @param string $text
     * @param string $separator
     *
     * @return string
     */
    public static function transliterate($text, $separator = '-')
    {
        $text = Urlizer::unaccent($text);

        return Urlizer::urlize($text, $separator);
    }
}
