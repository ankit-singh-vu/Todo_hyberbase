<?php
/**
 * Inflector logic.
 *  
 * @package    Plugin
 * @subpackage DB\ActiveRecord\\Mongo\Inflector
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  (c) 2014 - 2015, Sky
 * @license    https://licenses.domain.com/psl-1.0.txt Proprietary Service Licence ver. 1.0 
 */

namespace ActiveRecord\Mongo;

use ActiveRecord\Mongo\MongoRecordIterator as MongoRecordIterator;
use ActiveRecord\Mongo\Model as BaseMongoRecord;
use ActiveRecord\Mongo\MongoRecord as MongoRecord;

/**
 * Inflector Class.
 */
class Inflector
{
    /**
     * Pluralized words.
     * 
     * @var array
     */
    public $pluralized = array();
    
    /**
     * List of pluralization rules in the form of pattern => replacement.
     * 
     * @var array
     */
    public $pluralRules = array();
    
    /**
     * Singularized words.
     *
     * @var array
     */
    public $singularized = array();
    
    /**
     * List of singularization rules in the form of pattern => replacement.
     *
     * @var array
     */
    public $singularRules = array();
    
    /**
     * Plural rules from inflections.php.
     *
     * @var array
     */
    public $__pluralRules = array();
    
    /**
     * Un-inflected plural rules from inflections.php.
     * 
     * @var array
     */
    public $__uninflectedPlural = array();
    
    /**
     * Irregular plural rules from inflections.php.
     * 
     * @var array
     */
    public $__irregularPlural = array();
    
    /**
     * Singular rules from inflections.php.
     * 
     * @var array
     */
    public $__singularRules = array();
    
    /**
     * Un-inflectd singular rules from inflections.php.
     * 
     * @var array
     */
    public $__uninflectedSingular = array();
    
    /**
     * Irregular singular rules from inflections.php.
     * 
     * @var array
     */
    public $__irregularSingular = array();
    
    /**
     * Gets a reference to the Inflector object instance.
     * 
     * @return object
     * @access public
     */
    public static function getInstance()
    {
        static $instance = array();

        if (!$instance) {
            $instance[0] = new Inflector();
        }
        return $instance[0];
    }//end getInstance()
    
    /**
     * Initializes plural inflection rules.
     * 
     * @return void
     */
    public function __initPluralRules()
    {
        $corePluralRules       = array(
            '/(s)tatus$/i' => '\1\2tatuses',
            '/(quiz)$/i' => '\1zes',
            '/^(ox)$/i' => '\1\2en',
            '/([m|l])ouse$/i' => '\1ice',
            '/(matr|vert|ind)(ix|ex)$/i' => '\1ices',
            '/(x|ch|ss|sh)$/i' => '\1es',
            '/([^aeiouy]|qu)y$/i' => '\1ies',
            '/(hive)$/i' => '\1s',
            '/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
            '/sis$/i' => 'ses',
            '/([ti])um$/i' => '\1a',
            '/(p)erson$/i' => '\1eople',
            '/(m)an$/i' => '\1en',
            '/(c)hild$/i' => '\1hildren',
            '/(buffal|tomat)o$/i' => '\1\2oes',
            '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$/i' => '\1i',
            '/us$/' => 'uses',
            '/(alias)$/i' => '\1es',
            '/(ax|cris|test)is$/i' => '\1es',
            '/s$/' => 's',
            '/^$/' => '',
            '/$/' => 's'
        );
        $coreUninflectedPlural = array(
            '.*[nrlm]ese', '.*deer', '.*fish', '.*measles', '.*ois', '.*pox', '.*sheep', 'Amoyese',
            'bison', 'Borghese', 'bream', 'breeches', 'britches', 'buffalo', 'cantus', 'carp', 'chassis', 'clippers',
            'cod', 'coitus', 'Congoese', 'contretemps', 'corps', 'debris', 'diabetes', 'djinn', 'eland', 'elk',
            'equipment', 'Faroese', 'flounder', 'Foochowese', 'gallows', 'Genevese', 'Genoese', 'Gilbertese', 'graffiti',
            'headquarters', 'herpes', 'hijinks', 'Hottentotese', 'information', 'innings', 'jackanapes', 'Kiplingese',
            'Kongoese', 'Lucchese', 'mackerel', 'Maltese', 'media', 'mews', 'moose', 'mumps', 'Nankingese', 'news',
            'nexus', 'Niasese', 'Pekingese', 'People', 'Piedmontese', 'pincers', 'Pistoiese', 'pliers', 'Portuguese', 'proceedings',
            'rabies', 'rice', 'rhinoceros', 'salmon', 'Sarawakese', 'scissors', 'sea[- ]bass', 'series', 'Shavese', 'shears',
            'siemens', 'species', 'swine', 'testes', 'trousers', 'trout', 'tuna', 'Vermontese', 'Wenchowese',
            'whiting', 'wildebeest', 'Yengeese'
        );
        $coreIrregularPlural   = array(
            'atlas' => 'atlases',
            'beef' => 'beefs',
            'brother' => 'brothers',
            'child' => 'children',
            'corpus' => 'corpuses',
            'cow' => 'cows',
            'ganglion' => 'ganglions',
            'genie' => 'genies',
            'genus' => 'genera',
            'graffito' => 'graffiti',
            'hoof' => 'hoofs',
            'loaf' => 'loaves',
            'man' => 'men',
            'money' => 'monies',
            'mongoose' => 'mongooses',
            'move' => 'moves',
            'mythos' => 'mythoi',
            'numen' => 'numina',
            'occiput' => 'occiputs',
            'octopus' => 'octopuses',
            'opus' => 'opuses',
            'ox' => 'oxen',
            'penis' => 'penises',
            'person' => 'people',
            'sex' => 'sexes',
            'soliloquy' => 'soliloquies',
            'testis' => 'testes',
            'trilby' => 'trilbys',
            'turf' => 'turfs'
        );
        $pluralRules           = $corePluralRules;
        $uninflected           = $coreUninflectedPlural;
        $irregular             = $coreIrregularPlural;
        $this->pluralRules     = array('pluralRules' => $pluralRules, 'uninflected' => $uninflected, 'irregular' => $irregular);
        $this->pluralized      = array();
    }//end __initPluralRules()
    
    /**
     * Return $word in plural form.
     * 
     * @param string $word Word in singular.
     * 
     * @return string Word in plural.
     */
    public function pluralize($word)
    {
        $_this = self::getInstance();
        if (!isset($_this->pluralRules) || empty($_this->pluralRules)) {
            $_this->__initPluralRules();
        }
        if (isset($_this->pluralized[$word])) {
            return $_this->pluralized[$word];
        }
        extract($_this->pluralRules);
        if (!isset($regexUninflected) || !isset($regexIrregular)) {
            $regexUninflected                       = __enclose(implode('|', $uninflected));
            $regexIrregular                         = __enclose(implode('|', array_keys($irregular)));
            $_this->pluralRules['regexUninflected'] = $regexUninflected;
            $_this->pluralRules['regexIrregular']   = $regexIrregular;
        }
        if (preg_match('/^(' . $regexUninflected . ')$/i', $word, $regs)) {
            $_this->pluralized[$word] = $word;
            return $word;
        }
        if (preg_match('/(.*)\\b(' . $regexIrregular . ')$/i', $word, $regs)) {
            $_this->pluralized[$word] = $regs[1] . substr($word, 0, 1) . substr($irregular[strtolower($regs[2])], 1);
            return $_this->pluralized[$word];
        }
        foreach ($pluralRules as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                $_this->pluralized[$word] = preg_replace($rule, $replacement, $word);
                return $_this->pluralized[$word];
            }
        }
    }//end pluralize()
    
    /**
     * Initializes singular inflection rules.
     * 
     * @return void
     */
    public function __initSingularRules()
    {
        $coreSingularRules       = array(
            '/(s)tatuses$/i' => '\1\2tatus',
            '/^(.*)(menu)s$/i' => '\1\2',
            '/(quiz)zes$/i' => '\\1',
            '/(matr)ices$/i' => '\1ix',
            '/(vert|ind)ices$/i' => '\1ex',
            '/^(ox)en/i' => '\1',
            '/(alias)(es)*$/i' => '\1',
            '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|viri?)i$/i' => '\1us',
            '/([ftw]ax)es/' => '\1',
            '/(cris|ax|test)es$/i' => '\1is',
            '/(shoe)s$/i' => '\1',
            '/(o)es$/i' => '\1',
            '/ouses$/' => 'ouse',
            '/uses$/' => 'us',
            '/([m|l])ice$/i' => '\1ouse',
            '/(x|ch|ss|sh)es$/i' => '\1',
            '/(m)ovies$/i' => '\1\2ovie',
            '/(s)eries$/i' => '\1\2eries',
            '/([^aeiouy]|qu)ies$/i' => '\1y',
            '/([lr])ves$/i' => '\1f',
            '/(tive)s$/i' => '\1',
            '/(hive)s$/i' => '\1',
            '/(drive)s$/i' => '\1',
            '/([^fo])ves$/i' => '\1fe',
            '/(^analy)ses$/i' => '\1sis',
            '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
            '/([ti])a$/i' => '\1um',
            '/(p)eople$/i' => '\1\2erson',
            '/(m)en$/i' => '\1an',
            '/(c)hildren$/i' => '\1\2hild',
            '/(n)ews$/i' => '\1\2ews',
            '/eaus$/' => 'eau',
            '/^(.*us)$/' => '\\1',
            '/s$/i' => ''
        );
        $coreUninflectedSingular = array(
            '.*[nrlm]ese', '.*deer', '.*fish', '.*measles', '.*ois', '.*pox', '.*sheep', '.*ss', 'Amoyese',
            'bison', 'Borghese', 'bream', 'breeches', 'britches', 'buffalo', 'cantus', 'carp', 'chassis', 'clippers',
            'cod', 'coitus', 'Congoese', 'contretemps', 'corps', 'debris', 'diabetes', 'djinn', 'eland', 'elk',
            'equipment', 'Faroese', 'flounder', 'Foochowese', 'gallows', 'Genevese', 'Genoese', 'Gilbertese', 'graffiti',
            'headquarters', 'herpes', 'hijinks', 'Hottentotese', 'information', 'innings', 'jackanapes', 'Kiplingese',
            'Kongoese', 'Lucchese', 'mackerel', 'Maltese', 'media', 'mews', 'moose', 'mumps', 'Nankingese', 'news',
            'nexus', 'Niasese', 'Pekingese', 'Piedmontese', 'pincers', 'Pistoiese', 'pliers', 'Portuguese', 'proceedings',
            'rabies', 'rice', 'rhinoceros', 'salmon', 'Sarawakese', 'scissors', 'sea[- ]bass', 'series', 'Shavese', 'shears',
            'siemens', 'species', 'swine', 'testes', 'trousers', 'trout', 'tuna', 'Vermontese', 'Wenchowese',
            'whiting', 'wildebeest', 'Yengeese'
        );
        $coreIrregularSingular   = array(
            'atlases' => 'atlas',
            'beefs' => 'beef',
            'brothers' => 'brother',
            'children' => 'child',
            'corpuses' => 'corpus',
            'cows' => 'cow',
            'ganglions' => 'ganglion',
            'genies' => 'genie',
            'genera' => 'genus',
            'graffiti' => 'graffito',
            'hoofs' => 'hoof',
            'loaves' => 'loaf',
            'men' => 'man',
            'monies' => 'money',
            'mongooses' => 'mongoose',
            'moves' => 'move',
            'mythoi' => 'mythos',
            'numina' => 'numen',
            'occiputs' => 'occiput',
            'octopuses' => 'octopus',
            'opuses' => 'opus',
            'oxen' => 'ox',
            'penises' => 'penis',
            'people' => 'person',
            'sexes' => 'sex',
            'soliloquies' => 'soliloquy',
            'testes' => 'testis',
            'trilbys' => 'trilby',
            'turfs' => 'turf',
            'waves' => 'wave'
        );
        $singularRules           = $coreSingularRules;
        $uninflected             = $coreUninflectedSingular;
        $irregular               = $coreIrregularSingular;
        $this->singularRules     = array('singularRules' => $singularRules, 'uninflected' => $uninflected, 'irregular' => $irregular);
        $this->singularized      = array();
    }//end __initSingularRules()
    
    /**
     * Return $word in singular form.
     * 
     * @param string $word Word in plural.
     * 
     * @return string Word in singular.
     */
    public function singularize($word)
    {
        $_this = self::getInstance();
        if (!isset($_this->singularRules) || empty($_this->singularRules)) {
            $_this->__initSingularRules();
        }
        if (isset($_this->singularized[$word])) {
            return $_this->singularized[$word];
        }
        extract($_this->singularRules);
        if (!isset($regexUninflected) || !isset($regexIrregular)) {
            $regexUninflected                         = __enclose(implode('|', $uninflected));
            $regexIrregular                           = __enclose(implode('|', array_keys($irregular)));
            $_this->singularRules['regexUninflected'] = $regexUninflected;
            $_this->singularRules['regexIrregular']   = $regexIrregular;
        }
        if (preg_match('/^(' . $regexUninflected . ')$/i', $word, $regs)) {
            $_this->singularized[$word] = $word;
            return $word;
        }
        if (preg_match('/(.*)\\b(' . $regexIrregular . ')$/i', $word, $regs)) {
            $_this->singularized[$word] = $regs[1] . substr($word, 0, 1) . substr($irregular[strtolower($regs[2])], 1);
            return $_this->singularized[$word];
        }
        foreach ($singularRules as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                $_this->singularized[$word] = preg_replace($rule, $replacement, $word);
                return $_this->singularized[$word];
            }
        }
        $_this->singularized[$word] = $word;
        return $word;
    }//end singularize()
    
    /**
     * Returns the given lower_case_and_underscored_word as a CamelCased word.
     * 
     * @param string $lowerCaseAndUnderscoredWord Word to camelize.
     * 
     * @return string Camelized word. LikeThis.
     */
    public function camelize($lowerCaseAndUnderscoredWord)
    {
        return str_replace(" ", "", ucwords(str_replace("_", " ", $lowerCaseAndUnderscoredWord)));
    }//end camelize()
    
    /**
     * Returns the given camelCasedWord as an underscored_word.
     * 
     * @param string $camelCasedWord Camel-cased word to be "underscorized".
     * 
     * @return string Underscore-syntaxed version of the $camelCasedWord.
     */
    public function underscore($camelCasedWord)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $camelCasedWord));
    }//end underscore()
    
    /**
     * Returns the given underscored_word_group as a Human Readable Word Group.
     * Underscores are replaced by spaces and capitalized following words.
     * 
     * @param string $lowerCaseAndUnderscoredWord String to be made more readable.
     * 
     * @return string Human-readable string.
     */
    public function humanize($lowerCaseAndUnderscoredWord)
    {
        return ucwords(str_replace("_", " ", $lowerCaseAndUnderscoredWord));
    }//end humanize()
    
    /**
     * Returns corresponding table name for given model $className. ("people" for the model class "Person").
     * 
     * @param string $className Name of class to get database table name for.
     * 
     * @return string Name of the database table for given class.
     */
    public function tableize($className)
    {
        return Inflector::pluralize(Inflector::underscore($className));
    }//end tableize()
    
    /**
     * Returns Cake model class name ("Person" for the database table "people".) for given database table.
     * 
     * @param string $tableName Name of database table to get class name for.
     * 
     * @return string Class name.
     */
    public function classify($tableName)
    {
        return Inflector::camelize(Inflector::singularize($tableName));
    }//end classify()
    
    /**
     * Returns camelBacked version of an underscored string.
     * 
     * @param string $string The input string.
     * 
     * @return string In variable form.
     */
    public function variable($string)
    {
        $string  = Inflector::camelize(Inflector::underscore($string));
        $replace = strtolower(substr($string, 0, 1));
        return $replace . substr($string, 1);
    }//end variable()
    
    /**
     * Returns a string with all spaces converted to underscores (by default), accented
     * characters converted to non-accented characters, and non word characters removed.
     * 
     * @param string $string      The input string.
     * @param string $replacement Replaceent charecter.
     * 
     * @return string
     */
    public function slug($string, $replacement = '_')
    {
        if (!class_exists('String')) {
            include LIBS . 'string.php';
        }
        $map = array(
            '/à|á|å|â/' => 'a',
            '/è|é|ê|ẽ|ë/' => 'e',
            '/ì|í|î/' => 'i',
            '/ò|ó|ô|ø/' => 'o',
            '/ù|ú|ů|û/' => 'u',
            '/ç/' => 'c',
            '/ñ/' => 'n',
            '/ä|æ/' => 'ae',
            '/ö/' => 'oe',
            '/ü/' => 'ue',
            '/Ä/' => 'Ae',
            '/Ü/' => 'Ue',
            '/Ö/' => 'Oe',
            '/ß/' => 'ss',
            '/[^\w\s]/' => ' ',
            '/\\s+/' => $replacement,
            String::insert('/^[:replacement]+|[:replacement]+$/', array('replacement' => preg_quote($replacement, '/'))) => '',
        );
        return preg_replace(array_keys($map), array_values($map), $string);
    }//end slug()
}//end class

/**
 * Enclose a string for preg matching.
 * 
 * @param string $string String to enclose.
 * 
 * @return string Enclosed string.
 */
function __enclose($string)
{
    return '(?:' . $string . ')';
}//end __enclose()
