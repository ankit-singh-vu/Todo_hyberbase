<?php
/**
 * ActiveRecord Model Class.
 * 
 * @package    Plugin
 * @subpackage DB/ActiveRecord/Model
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  (c) 2014 - 2015, Sky
 * @license    https://licenses.domain.com/psl-1.0.txt Proprietary Service Licence ver. 1.0
 */

namespace ActiveRecord;

/**
 * The base class for your models.
 *
 * Defining an ActiveRecord model for a table called people and orders:
 *
 * <code>
 * CREATE TABLE people(
 *   id int primary key auto_increment,
 *   parent_id int,
 *   first_name varchar(50),
 *   last_name varchar(50)
 * );
 *
 * CREATE TABLE orders(
 *   id int primary key auto_increment,
 *   person_id int not null,
 *   cost decimal(10,2),
 *   total decimal(10,2)
 * );
 * </code>
 *
 * <code>
 * class Person extends ActiveRecord\Model {
 *   static $belongs_to = array(
 *     array('parent', 'foreign_key' => 'parent_id', 'class_name' => 'Person')
 *   );
 *
 *   static $has_many = array(
 *     array('children', 'foreign_key' => 'parent_id', 'class_name' => 'Person'),
 *     array('orders')
 *   );
 *
 *   static $validates_length_of = array(
 *     array('first_name', 'within' => array(1,50)),
 *     array('last_name', 'within' => array(1,50))
 *   );
 * }
 *
 * class Order extends ActiveRecord\Model {
 *   static $belongs_to = array(
 *     array('person')
 *   );
 *
 *   static $validates_numericality_of = array(
 *     array('cost', 'greater_than' => 0),
 *     array('total', 'greater_than' => 0)
 *   );
 *
 *   static $before_save = array('calculate_total_with_tax');
 *
 *   public function calculate_total_with_tax() {
 *     $this->total = $this->cost * 0.045;
 *   }
 * }
 * </code>
 *
 * For a more in-depth look at defining models, relationships, callbacks and many other things
 * please consult with Dyutiman Chakraborty <dc@mclogics.com>.
 */
abstract class Model
{
    /**
     * An instance of {@link Errors} and will be instantiated once a write method is called.
     *
     * @var Errors
     */
    public $errors;

    /**
     * Contains model values as column_name => value.
     *
     * @var array
     */
    private $attributes = array();

    /**
     * Flag whether or not this model's attributes have been modified since it will either be null or an array of column_names that have been modified.
     *
     * @var array
     */
    private $dirty = null;

    /**
     * Flag that determines of this model can have a writer method invoked such as: save/update/insert/delete.
     *
     * @var boolean
     */
    private $readonly = false;

    /**
     * Array of relationship objects as model_attribute_name => relationship.
     *
     * @var array
     */
    private $relationships = array();

    /**
     * Flag that determines if a call to save() should issue an insert or an update sql statement.
     *
     * @var boolean
     */
    private $new_record = true;

    /**
     * Set to the name of the connection this {@link Model} should use.
     *
     * @var string
     */
    public static $connection;

    /**
     * Set to the name of the database this Model's table is in.
     *
     * @var string
     */
    public static $db;

    /**
     * Set this to explicitly specify the model's table name if different from inferred name.
     *
     * If your table doesn't follow our table name convention you can set this to the
     * name of your table to explicitly tell ActiveRecord what your table is called.
     *
     * @var string
     */
    public static string $table_name;
        
    /**
     * Holds the Model schema.
     * 
     * @var array
     */
    public static array $schema;

    /**
     * Set this to override the default primary key name if different from default name of "id".
     *
     * @var string
     */
    public static $primary_key = 'id';

    /**
     * Set this to explicitly specify the sequence name for the table.
     *
     * @var string
     */
    public static $sequence;

    /**
     * Allows you to create aliases for attributes.
     * 
     * Following is an example.
     *
     * <code>
     * class Person extends ActiveRecord\Model {
     *   static $alias_attribute = array(
     *     'the_first_name' => 'first_name',
     *     'the_last_name' => 'last_name');
     * }
     *
     * $person = Person::first();
     * $person->the_first_name = 'Tito';
     * echo $person->the_first_name;
     * </code>
     *
     * @var array
     */
    public static $alias_attribute = array();

    /**
     * Whitelist of attributes that are checked from mass-assignment calls such as constructing a model or using update_attributes.
     *
     * This is the opposite of {@link attr_protected $attr_protected}.
     *
     * <code>
     * class Person extends ActiveRecord\Model {
     *   static $attr_accessible = array('first_name','last_name');
     * }
     *
     * $person = new Person(array(
     *   'first_name' => 'Tito',
     *   'last_name' => 'the Grief',
     *   'id' => 11111));
     *
     * echo $person->id; # => null
     * </code>
     *
     * @var array
     */
    public static $attr_accessible = array();

    /**
     * Blacklist of attributes that cannot be mass-assigned.
     *
     * This is the opposite of {@link attr_accessible $attr_accessible} and the format
     * for defining these are exactly the same.
     *
     * @var array
     */
    public static $attr_protected = array();

    /**
     * Delegates calls to a relationship.
     * 
     * Following is an example.
     *
     * <code>
     * class Person extends ActiveRecord\Model {
     *   static $belongs_to = array(array('venue'),array('host'));
     *   static $delegate = array(
     *     array('name', 'state', 'to' => 'venue'),
     *     array('name', 'to' => 'host', 'prefix' => 'woot'));
     * }
     * </code>
     *
     * Can then do:
     *
     * <code>
     * $person->state     # same as calling $person->venue->state
     * $person->name      # same as calling $person->venue->name
     * $person->woot_name # same as calling $person->host->name
     * </code>
     *
     * @var array
     */
    public static $delegate = array();

    /**
     * Define customer setters methods for the model.
     *
     * You can also use this to define custom setters for attributes as well.
     *
     * <code>
     * class User extends ActiveRecord\Model {
     *   static $setters = array('password','more','even_more');
     *
     *   # now to define the setter methods. Note you must
     *   # prepend set_ to your method name:
     *   function set_password($plaintext) {
     *     $this->encrypted_password = md5($plaintext);
     *   }
     * }
     *
     * $user = new User();
     * $user->password = 'plaintext';  # will call $user->set_password('plaintext')
     * </code>
     *
     * If you define a custom setter with the same name as an attribute then you
     * will need to use assign_attribute() to assign the value to the attribute.
     * This is necessary due to the way __set() works.
     *
     * For example, assume 'name' is a field on the table and we're defining a
     * custom setter for 'name':
     *
     * <code>
     * class User extends ActiveRecord\Model {
     *   static $setters = array('name');
     *
     *   # INCORRECT way to do it
     *   # function set_name($name) {
     *   #   $this->name = strtoupper($name);
     *   # }
     *
     *   function set_name($name) {
     *     $this->assign_attribute('name',strtoupper($name));
     *   }
     * }
     *
     * $user = new User();
     * $user->name = 'bob';
     * echo $user->name; # => BOB
     * </code>
     *
     * @var array
     */
    public static $setters = array();

    /**
     * Define customer getter methods for the model.
     * 
     * You can also use this to define custom getters for attributes as well.
     * 
     * <code>
     * class User extends ActiveRecord\Model {
     *   static $getters = array('middle_initial','more','even_more');
     *
     *   # now to define the getter method. Note you must
     *   # prepend get_ to your method name:
     *   function get_middle_initial() {
     *     return $this->middle_name{0};
     *   }
     * }
     *
     * $user = new User();
     * echo $user->middle_name;  # will call $user->get_middle_name()
     * </code>
     *
     * If you define a custom getter with the same name as an attribute then you
     * will need to use read_attribute() to get the attribute's value.
     * This is necessary due to the way __get() works.
     *
     * For example, assume 'name' is a field on the table and we're defining a
     * custom getter for 'name':
     *
     * <code>
     * class User extends ActiveRecord\Model {
     *   static $getters = array('name');
     *
     *   # INCORRECT way to do it
     *   # function get_name() {
     *   #   return strtoupper($this->name);
     *   # }
     *
     *   function get_name() {
     *     return strtoupper($this->read_attribute('name'));
     *   }
     * }
     *
     * $user = new User();
     * $user->name = 'bob';
     * echo $user->name; # => BOB
     * </code>
     *
     * @var array
     */
    public static $getters = array();

    /**
     * The number of unit by which auto incriment should increase.
     *  
     * @var intiger
     */
    public static $primary_key_auto_increment = 1;

    /**
     * DB storage engine for MySql.
     * 
     * @var string
     */
    public static $db_storage_engine = 'InnoDB';

    /**
     * Path to the fixture file if available.
     * 
     * @var boolean 
     */
    public static $fixture_file = false;
    
    /**
     * Holds a reference to the Kernel object.
     * 
     * @var \System\Kernel
     */
    protected $kernel;
    
    /**
     * A list of valid finder options.
     *
     * @var array
     */
    public static $VALID_OPTIONS = array('conditions', 'limit', 'offset', 'order', 'select', 'joins', 'include', 'readonly', 'group', 'from', 'having');

    protected $attachment = array();

    /**
     * Constructs a model.
     *
     * When a user instantiates a new object (e.g.: it was not ActiveRecord that instantiated via a find)
     * then @var $attributes will be mapped according to the schema's defaults. Otherwise, the given
     * $attributes will be mapped via set_attributes_via_mass_assignment.
     *
     * <code>
     * new Person(array('first_name' => 'Priyanka', 'last_name' => 'Basak'));
     * </code>
     *
     * @param array   $attributes             Hash containing names and values to mass assign to the model.
     * @param boolean $guard_attributes       Set to true to guard attributes.
     * @param boolean $instantiating_via_find Set to true if this model is being created from a find call.
     * @param boolean $new_record             Set to true if this should be considered a new record.
     */
    public function __construct(array $attributes = array(), $guard_attributes = true, $instantiating_via_find = false, $new_record = true)
    {
        $this->new_record = $new_record;
        //$this->kernel     = \Kernel();
        // Initialize attributes applying defaults.
        if (!$instantiating_via_find) {
            foreach (static::table()->columns as $name => $meta) {
                $this->attributes[$meta->inflected_name] = $meta->default;
            }
        }
        $this->set_attributes_via_mass_assignment($attributes, $guard_attributes);
        // Since all attribute assignment now goes thru assign_attributes() we want to reset.
        // Dirty if instantiating via find since nothing is really dirty when doing that.
        if ($instantiating_via_find) {
            $this->dirty = array();
        }
        $this->invoke_callback('after_construct', false);
    }//end __construct()

    /**
     * @return \System\Kernel
     */
    public function kernel()
    {
        if(!isset($this->kernel)) {
            $this->kernel = \Kernel();
        }
        return $this->kernel;
    }

    /**
     * Magic method which delegates to read_attribute(). This handles firing off getter methods,
     * as they are not checked/invoked inside of read_attribute(). This circumvents the problem with
     * a getter being accessed with the same name as an actual attribute.
     *
     * @param string $name Name of an attribute.
     * 
     * @return mixed The value of the attribute.
     */
    public function &__get($name)
    {
        // Check for getter.
        if (in_array("get_$name", static::$getters)) {
            $name  = "get_$name";
            $value = $this->$name();
            return $value;
        }

        return $this->read_attribute($name);
    }//end __get()

    /**
     * Determines if an attribute exists for this Model.
     *
     * @param string $attribute_name Name of the attribute.
     * 
     * @return boolean
     */
    public function __isset($attribute_name)
    {
        return array_key_exists($attribute_name, $this->attributes) || array_key_exists($attribute_name, static::$alias_attribute);
    }//end __isset()

    /**
     * Magic allows un-defined attributes to set via $attributes.
     *
     * @param string $name  Name of attribute, relationship or other to set.
     * @param mixed  $value The value.
     * 
     * @throws UndefinedPropertyException If $name does not exist.
     * 
     * @return mixed The value.
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, static::$alias_attribute)) {
            $name = static::$alias_attribute[$name];
        } elseif (in_array("set_$name", static::$setters)) {
            $name = "set_$name";
            return $this->$name($value);
        }
        if (array_key_exists($name, $this->attributes)) {
            return $this->assign_attribute($name, $value);
        }
        foreach (static::$delegate as &$item) {
            if (($delegated_name = $this->is_delegated($name, $item))) {
                return $this->$item['to']->$delegated_name = $value;
            }
        }
        //return $this->$name = $value;
        throw new UndefinedPropertyException(get_called_class(), $name);
    }//end __set()

    /**
     * Make sure the models Table instance gets initialized when waking up.
     * 
     * @return void 
     */
    public function __wakeup()
    {
        static::table();
    }//end __wakeup()
    
    /**
     * Returns a collection.
     * 
     * @param string $name       Name of the model.
     * @param array  $options    Additional options.
     * @param array  $attributes Additional attributes.
     * 
     * @return \ActiveRecord\Model
     */
    public function getCollections($name, array $options = array(), array $attributes = array())
    {
        if (stripos($name, '\\') === false) {
            $collectionClass = '\Model\\' . ucfirst($name);
        } else {
            $collectionClass = $name;
        }
        if (isset(static::$collection_relations[$name])) {
            $foreignKey = static::$collection_relations[$name];
        } else {
            $foreignKey = $this->kernel->depluralize(static::$table_name) . '_id';
        }
        return $collectionClass::find(
            array_merge(
                $attributes,
                array(
                    $foreignKey => $this->$this->get_primary_key()
                )
            ),
            $options
        );
    }//end getCollections()
    
    /**
     * Assign a value to an attribute.
     *
     * @param string $name  Name of the attribute.
     * @param mixed  $value Value of the attribute.
     * 
     * @return mixed the attribute value
     */
    public function assign_attribute($name, $value)
    {
        $table = static::table();
        if (array_key_exists($name, $table->columns) && !is_object($value)) {
            $value = $table->columns[$name]->cast($value, static::connection());
        }
        // Convert php's \DateTime to ours.
        //if ($value instanceof \DateTime) {
        //    $value = new DateTime($value->format(\Kernel()->config('app.date_format') . ' ' .\Kernel()->config('app.time_format')));
        //}
        // Make sure DateTime values know what model they belong to so.
        // Dirty stuff works when calling set methods on the DateTime object.
        if ($value instanceof DateTime) {
            $value->attribute_of($this, $name);
        }
        $this->attributes[$name] = $value;
        $this->flag_dirty($name);
        return $value;
    }//end assign_attribute()

    /**
     * Retrieves an attribute's value or a relationship object based on the name passed. If the attribute
     * accessed is 'id' then it will return the model's primary key no matter what the actual attribute name is
     * for the primary key.
     *
     * @param string $name Name of an attribute.
     * 
     * @throws UndefinedPropertyException If name could not be resolved to an attribute, relationship.
     * @throws Exception                  Throws Exception in case of composite key. 
     * 
     * @return mixed The value of the attribute
     */
    public function &read_attribute($name)
    {
        // Check for aliased attribute.
        if (array_key_exists($name, static::$alias_attribute)) {
            $name = static::$alias_attribute[$name];
        }
        // Check for attribute.
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        // Check relationships if no attribute.
        if (array_key_exists($name, $this->relationships)) {
            return $this->relationships[$name];
        }
        $table = static::table();
        // This may be first access to the relationship so check Table.
        if (($relationship = $table->get_relationship($name))) {
            $this->relationships[$name] = $relationship->load($this);
            return $this->relationships[$name];
        }
        if ($name == 'id') {
            if (count($this->get_primary_key()) > 1) {
                throw new Exception("TODO composite key support");
            }
            if (isset($this->attributes[$table->pk[0]])) {
                return $this->attributes[$table->pk[0]];
            }
        }
        // Do not remove - have to return null by reference in strict mode.
        $null = null;
        foreach (static::$delegate as &$item) {
            if (($delegated_name = $this->is_delegated($name, $item))) {
                $to = $item['to'];
                if ($this->$to) {
                    $val =& $this->$to->$delegated_name;
                    return $val;
                } else {
                    return $null;
                }
            }
        }
        throw new UndefinedPropertyException(get_called_class(), $name);
    }//end read_attribute()

    /**
     * Flags an attribute as dirty.
     *
     * @param string $name Attribute name.
     * 
     * @return void
     */
    public function flag_dirty($name)
    {
        if (!$this->dirty) {
            $this->dirty = array();
        }
        $this->dirty[$name] = true;
    }//end flag_dirty()

    /**
     * Returns hash of attributes that have been modified since loading the model.
     *
     * @return mixed null if no dirty attributes otherwise returns array of dirty attributes.
     */
    public function dirty_attributes()
    {
        if (!$this->dirty) {
            return null;
        }
        $dirty = array_intersect_key($this->attributes, $this->dirty);
        return !empty($dirty) ? $dirty : null;
    }//end dirty_attributes()

    /**
     * Returns a copy of the model's attributes hash.
     * 
     * @param mixed $skip False or array of the attributes to hide.
     *
     * @return array A copy of the model's attribute data
     */
    public function attributes($skip = false)
    {
        if (is_array($skip)) {
            $responce = $this->attributes;
            foreach ($skip as $key) {
                if (isset($responce[$key])) {
                    unset($responce[$key]);
                }
            }
            return $responce;
        }
        return $this->attributes;
    }//end attributes()
        
    /**
     * Returns the array format representation of a Model.
     * 
     * @param mixed $skip False or array of the attributes to hide.
     * 
     * @return array
     */
    public function to_array($skip = false)
    {
        return $this->attributes($skip);
    }//end to_array()
        

    /**
     * Retrieve the primary key name.
     *
     * @return string The primary key for the model
     */
    public function get_primary_key()
    {
        return Table::load(get_class($this))->pk;
    }//end get_primary_key()

    /**
     * Returns the actual attribute name if $name is aliased.
     *
     * @param string $name An attribute name.
     * 
     * @return string
     */
    public function get_real_attribute_name($name)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $name;
        }
        if (array_key_exists($name, static::$alias_attribute)) {
            return static::$alias_attribute[$name];
        }
        return null;
    }//end get_real_attribute_name()

    /**
     * Returns an associative array containing values for all the attributes in $attributes.
     *
     * @param array $attributes Array containing attribute names.
     * 
     * @return array A hash containing $name => $value
     */
    public function get_values_for(array $attributes)
    {
        $ret = array();
        foreach ($attributes as $name) {
            if (array_key_exists($name, $this->attributes)) {
                $ret[$name] = $this->attributes[$name];
            }
        }
        return $ret;
    }//end get_values_for()

    /**
     * Retrieves the name of the table for this Model.
     *
     * @return string
     */
    public static function table_name()
    {
        return static::table()->table;
    }//end table_name()

    public static function get_connection_info()
    {
        if(self::$connection) {
            $db_connection_name = self::$connection;
        } else {
            $db_connection_name = \Kernel()->config('app.plugin.system.db.default_connection');
        }
        $connection = \Kernel()->config('app.plugin.system.db.connections.'.$db_connection_name);
        $sg1        = explode('/', $connection);
        $sg2        = explode('@', $sg1[2]);
        $sg3        = explode(':', $sg2[0]);
        $dbname     = $sg1[3];
        $dbhost     = $sg2[1];
        $dbuser     = $sg3[0];
        $dbpass     = $sg3[1];
        return array(
            'dbhost' => $dbhost,
            'dbuser' => $dbuser,
            'dbpass' => $dbpass,
            'dbname' => $dbname
        );
    }

    /**
     * Builds the DB Table from the initial schema.
     * 
     * @param boolean $apply_schema_upgrades Should schema upgrades be applied or not.
     *
     * @throws \Exception Throws exception if unable tro connect to MySql Server.
     * 
     * @return boolean
     */
    public static function build_table_from_schema($apply_schema_upgrades = true)
    {
        if (!isset(static::$schema) || !is_array(static::$schema)) {
            return false;
        }
        $connection = self::get_connection_info();
        $conn       = mysqli_connect(
            $connection['dbhost'], $connection['dbuser'], $connection['dbpass'], $connection['dbname']
        );

        if (!$conn) {
            throw new \Exception('Can not connect to MySql Server');
        }
        $sql = 'CREATE TABLE IF NOT EXISTS `' . static::$table_name . '` ( ';
        foreach (static::$schema as $column => $prop) {
            $sql .= ' `' . $column . '` ' . $prop . ', ';
        }
        $sql .= 'PRIMARY KEY (`'.static::$primary_key.'`) )';
        $sql .= 'ENGINE='.static::$db_storage_engine.'  DEFAULT CHARSET=latin1 AUTO_INCREMENT=' . static::$primary_key_auto_increment;

        if (mysqli_query($conn, $sql)) {
            $return = true;
        } else {
            $return = false;
        }
        mysqli_close($conn);
        if ($apply_schema_upgrades == true) {
            static::upgrade_table_from_schema();
        }
        return $return;
    }//end build_table_from_schema()

    /**
     * Updates Table from schema updates.
     *
     * @return boolean
     */
    protected static function upgrade_table_from_schema()
    {
        if (!isset(static::$schema_updates) || !is_array(static::$schema_updates)) {
            return false;
        }
        $connection = self::get_connection_info();
        $conn       = mysqli_connect(
            $connection['dbhost'], $connection['dbuser'], $connection['dbpass'], $connection['dbname']
        );
        $schema_version = \Kernel()->loadDBVersionRegistry(static::$table_name);
        $responce = array();
        for ($a=$schema_version->version; $a<count(static::$schema_updates); $a++) {
            foreach(static::$schema_updates[$a] as $sql) {
                if (mysqli_query($conn, $sql)) {
                    $responce[$a] = true;
                } else {
                    $responce[$a] = false;
                }
            }
        }
        $schema_version->version($a);
        mysqli_close($conn);
        return $responce;
    }//end upgrade_table_from_schema()

    /**
     * Returns the attribute name on the delegated relationship if $name is
     * delegated or null if not delegated.
     *
     * @param string $name      Name of an attribute.
     * @param array  &$delegate An array containing delegate data.
     * 
     * @return delegated attribute name or null.
     */
    private function is_delegated($name, &$delegate)
    {
        if ($delegate['prefix'] != '') {
            $name = substr($name, strlen($delegate['prefix'])+1);
        }
        if (is_array($delegate) && in_array($name, $delegate['delegate'])) {
            return $name;
        }
        return null;
    }//end is_delegated()

    /**
     * Determine if the model is in read-only mode.
     *
     * @return boolean
     */
    public function is_readonly()
    {
        return $this->readonly;
    }//end is_readonly()

    /**
     * Determine if the model is a new record.
     *
     * @return boolean
     */
    public function is_new_record()
    {
        return $this->new_record;
    }//end is_new_record()

    /**
     * Throws an exception if this model is set to readonly.
     *
     * @param string $method_name Name of method that was invoked on model for exception message.
     * 
     * @throws \ActiveRecord\ReadOnlyException Throws error if it is readonly.
     * 
     * @return void
     */
    private function verify_not_readonly($method_name)
    {
        if ($this->is_readonly()) {
            throw new \ActiveRecord\ReadOnlyException(get_class($this), $method_name);
        }
    }//end verify_not_readonly()

    /**
     * Flag model as readonly.
     *
     * @param boolean $readonly Set to true to put the model into readonly mode.
     * 
     * @return void
     */
    public function readonly($readonly = true)
    {
        $this->readonly = $readonly;
    }//end readonly()

    /**
     * Retrieve the connection for this model.
     *
     * @return Connection
     */
    public static function connection()
    {
        return static::table()->conn;
    }//end connection()

    /**
     * Returns the {@link Table} object for this model.
     *
     * Be sure to call in static scoping: static::table()
     *
     * @return Table
     */
    public static function table()
    {
        return Table::load(get_called_class());
    }//end table()

    /**
     * Creates a model and saves it to the database.
     *
     * @param array   $attributes Array of the models attributes.
     * @param boolean $validate   True if the validators should be run.
     * 
     * @return \ActiveRecord\Model
     */
    public static function create(array $attributes, $validate = true)
    {
        $class_name = get_called_class();
        $model      = new $class_name($attributes);
        $model->save($validate);
        return $model;
    }//end create()

    /**
     * Save the model to the database.
     *
     * This function will automatically determine if an INSERT or UPDATE needs to occur.
     * If a validation or a callback for this model returns false, then the model will
     * not be saved and this will return false.
     *
     * If saving an existing model only data that has changed will be saved.
     *
     * @param boolean $validate Set to true or false depending on if you want the validators to run or not.
     * 
     * @return boolean True if the model was saved to the database otherwise false.
     */
    public function save($validate = true)
    {
        $this->verify_not_readonly('save');
        return $this->is_new_record() ? $this->insert($validate) : $this->update($validate);
    }//end save()

    /**
     * Issue an INSERT sql statement for this model's attribute.
     *
     * @param boolean $validate Set to true or false depending on if you want the validators to run or not.
     * 
     * @return boolean True if the model was saved to the database otherwise false
     */
    private function insert($validate = true)
    {
        $this->verify_not_readonly('insert');
        if (($validate && !$this->validate() || !$this->invoke_callback('before_create', false))) {
            return false;
        }
        $table = static::table();
        if (!($attributes = $this->dirty_attributes())) {
            $attributes = $this->attributes;
        }
        $attributes = \Kernel()->events('model')->filter('create_'.strtolower(str_replace('Model\\', '', get_called_class())), $attributes);

        $pk = $this->get_primary_key();
        $use_sequence = false;
        if ($table->sequence && !isset($attributes[$pk[0]])) {
            if (($conn = static::connection()) instanceof OciAdapter) {
                // Terrible oracle makes us select the nextval first.
                $attributes[$pk[0]] = $conn->get_next_sequence_value($table->sequence);
                $table->insert($attributes);
                $this->attributes[$pk[0]] = $attributes[$pk[0]];
            } else {
                // Unset pk that was set to null.
                if (array_key_exists($pk[0], $attributes)) {
                    unset($attributes[$pk[0]]);
                }
                $table->insert($attributes, $pk[0], $table->sequence);
                $use_sequence = true;
            }
        } else {
            $table->insert($attributes);
        }
        // If we've got an autoincrementing/sequenced pk set it.
        if (count($pk) == 1) {
            $column = $table->get_column_by_inflected_name($pk[0]);

            if ($column->auto_increment || $use_sequence) {
                $this->attributes[$pk[0]] = $table->conn->insert_id($table->sequence);
            }
        }
        $this->invoke_callback('after_create', false);
        $this->new_record = false;
        \Kernel()->events('model')->trigger(strtolower(str_replace('Model\\', '', get_called_class())).'_created', array($this));
        return true;
    }//end insert()

    /**
     * Issue an UPDATE sql statement for this model's dirty attributes.
     *
     * @param boolean $validate Set to true or false depending on if you want the validators to run or not.
     * 
     * @throws ActiveRecordException Throws exception in case of error.
     * 
     * @return boolean True if the model was saved to the database otherwise false
     */
    private function update($validate = true)
    {
        $this->verify_not_readonly('update');
        if ($validate && !$this->validate()) {
            return false;
        }
        if ($this->is_dirty()) {
            $pk = $this->values_for_pk();
            if (empty($pk)) {
                throw new ActiveRecordException("Cannot update, no primary key defined for: " . get_called_class());
            }
            if (!$this->invoke_callback('before_update', false)) {
                return false;
            }
            $dirty = \Kernel()->events('model')->filter('update_'.strtolower(str_replace('Model\\', '', get_called_class())), $this->dirty_attributes());
            static::table()->update($dirty, $pk);
            $this->invoke_callback('after_update', false);
            \Kernel()->events('model')->trigger(strtolower(str_replace('Model\\', '', get_called_class())).'_updated', array($this));
        }
        return true;
    }//end update()

    /**
     * Deletes this model from the database and returns true if successful.
     *
     * @throws ActiveRecordException Throws exception in case of error.
     * 
     * @return boolean
     */
    public function delete()
    {
        $this->verify_not_readonly('delete');
        $pk = $this->values_for_pk();
        if (empty($pk)) {
            throw new ActiveRecordException("Cannot delete, no primary key defined for: " . get_called_class());
        }
        \Kernel()->events('model')->trigger('delete_'.strtolower(str_replace('Model\\', '', get_called_class())), array($this));
        if (!$this->invoke_callback('before_destroy', false)) {
            return false;
        }
        static::table()->delete($pk);
        $this->invoke_callback('after_destroy', false);
        \Kernel()->events('model')->trigger(strtolower(str_replace('Model\\', '', get_called_class())).'_deleted', array($this));
        return true;
    }//end delete()

    /**
     * Helper that creates an array of values for the primary key(s).
     *
     * @return array An array in the form array key_name => value,.
     */
    public function values_for_pk()
    {
        return $this->values_for(static::table()->pk);
    }//end values_for_pk()

    /**
     * Helper to return a hash of values for the specified attributes.
     *
     * @param array $attribute_names Array of attribute names.
     * 
     * @return array An array in the form array name => value,.
     */
    public function values_for(array $attribute_names)
    {
        $filter = array();

        foreach ($attribute_names as $name) {
            $filter[$name] = $this->$name;
        }

        return $filter;
    }//end values_for()

    /**
     * Returns array of validator data for this Model.
     *
     * Will return an array looking like:
     *
     * <code>
     * array(
     *   'name' => array(
     *     array('validator' => 'validates_presence_of'),
     *     array('validator' => 'validates_inclusion_of', 'in' => array('Bob','Joe','John')),
     *   'password' => array(
     *     array('validator' => 'validates_length_of', 'minimum' => 6))
     *   )
     * );
     * </code>
     *
     * @return array An array containing validator data for this model.
     */
    public function get_validation_rules()
    {
        //include_once __DIR__ . '/Validations.php';
        if(method_exists($this, 'set_custom_validations')) {
            $this->set_custom_validations();
        }
        $validator = new \ActiveRecord\Validations($this);
        return $validator->rules();
    }//end get_validation_rules()

    /**
     * Validates the model.
     *
     * @return boolean True if passed validators otherwise false
     */
    private function validate()
    {
        //include_once __DIR__ . '/Validations.php';
        if(method_exists($this, 'set_custom_validations')) {
            $this->set_custom_validations();
        }
        $validator     = new \ActiveRecord\Validations($this);
        $validation_on = 'validation_on_' . ($this->is_new_record() ? 'create' : 'update');
        foreach (array('before_validation', "before_$validation_on") as $callback) {
            if (!$this->invoke_callback($callback, false)) {
                return false;
            }
        }
        $this->errors = $validator->validate();
        foreach (array('after_validation', "after_$validation_on") as $callback) {
            $this->invoke_callback($callback, false);
        }
        if (!$this->errors->is_empty()) {
            return false;
        }
        return true;
    }//end validate()

    /**
     * Returns true if the model has been modified.
     *
     * @return boolean True if modified
     */
    public function is_dirty()
    {
        return empty($this->dirty) ? false : true;
    }//end is_dirty()

    /**
     * Run validations on model and returns whether or not model passed validation.
     *
     * @return boolean
     */
    public function is_valid()
    {
        return $this->validate();
    }//end is_valid()

    /**
     * Runs validations and returns true if invalid.
     *
     * @return boolean
     */
    public function is_invalid()
    {
        return !$this->validate();
    }//end is_invalid()

    /**
     * Updates a model's timestamps.
     * 
     * @return void
     */
    public function set_timestamps()
    {
        $now = date('Y-m-d H:i:s');
        //___debug($now);
        if (isset($this->updated_at)) {
            $this->updated_at = $now;
        }
        if (isset($this->created_at) && $this->is_new_record()) {
            $this->created_at = $now;
        }
    }//end set_timestamps()

    /**
     * Mass update the model with an array of attribute data and saves to the database.
     *
     * @param array $attributes An attribute data array in the form array(name => value, ...).
     * 
     * @return boolean True if successfully updated and saved otherwise false.
     */
    public function update_attributes(array $attributes)
    {
        $this->set_attributes($attributes);
        return $this->save();
    }//end update_attributes()

    /**
     * Updates a single attribute and saves the record without going through the normal validation procedure.
     *
     * @param string $name  Name of attribute.
     * @param mixed  $value Value of the attribute.
     * 
     * @return boolean True if successful otherwise false.
     */
    public function update_attribute($name, $value)
    {
        $this->__set($name, $value);
        return $this->update(false);
    }//end update_attribute()

    /**
     * Mass update the model with data from an attributes hash.
     *
     * Unlike update_attributes() this method only updates the model's data
     * but DOES NOT save it to the database.
     *
     * @param array $attributes An array containing data to update in the form array(name => value, ...).
     * 
     * @return void
     */
    public function set_attributes(array $attributes)
    {
        $this->set_attributes_via_mass_assignment($attributes, true);
    }//end set_attributes()

    /**
     * Passing $guard_attributes as true will throw an exception if an attribute does not exist.
     * 
     * @param array   &$attributes      An array in the form array.
     * @param boolean $guard_attributes Flag of whether or not attributes should be guarded.
     *
     * @throws \ActiveRecord\UndefinedPropertyException Throws Exception in case of error.
     * 
     * @return void
     */
    private function set_attributes_via_mass_assignment(array &$attributes, $guard_attributes)
    {
        // Access uninflected columns since that is what we would have in result set.
        $table               = static::table();
        $exceptions          = array();
        $use_attr_accessible = !empty(static::$attr_accessible);
        $use_attr_protected  = !empty(static::$attr_protected);
        $connection          = static::connection();
        foreach ($attributes as $name => $value) {
            // Is a normal field on the table.
            if (array_key_exists($name, $table->columns)) {
                $value = $table->columns[$name]->cast($value, $connection);
                $name  = $table->columns[$name]->inflected_name;
            }
            if ($guard_attributes) {
                if ($use_attr_accessible && !in_array($name, static::$attr_accessible)) {
                    continue;
                }
                if ($use_attr_protected && in_array($name, static::$attr_protected)) {
                    continue;
                }
                // Set valid table data.
                try {
                    $this->$name = $value;
                } catch (UndefinedPropertyException $e) {
                    $exceptions[] = $e->getMessage();
                }
            } else {
                // Ignore OciAdapter's limit() stuff.
                if ($name == 'ar_rnum__') {
                    continue;
                }
                // Set arbitrary data.
                $this->assign_attribute($name, $value);
            }//end if
        }//end foreach
        if (!empty($exceptions)) {
            throw new \ActiveRecord\UndefinedPropertyException(get_called_class(), $exceptions);
        }
    }//end set_attributes_via_mass_assignment()

    /**
     * Add a model to the given named ($name) relationship.
     * Note: This should <strong>only</strong> be used by eager load.
     * 
     * @param \ActiveRecord\Model $model Model to be added.
     * @param string              $name  Of relationship for this table.
     * 
     * @throws RelationshipException Throws exception in relationship is not found.
     * 
     * @return array
     */
    public function set_relationship_from_eager_load(\ActiveRecord\Model $model = null, $name = null)
    {
        $table = static::table();
        if (($rel = $table->get_relationship($name))) {
            if ($rel->is_poly()) {
                // If the related model is null and it is a poly then we should have an empty array.
                if (is_null($model)) {
                    return $this->relationships[$name] = array();
                } else {
                    return $this->relationships[$name][] = $model;
                }
            } else {
                return $this->relationships[$name] = $model;
            }
        }
        throw new RelationshipException("Relationship named $name has not been declared for class: {$table->class->getName()}");
    }//end set_relationship_from_eager_load()

    /**
     * Reloads the attributes and relationships of this object from the database.
     *
     * @return \ActiveRecord\Model
     */
    public function reload()
    {
        $this->relationships = array();
        $pk = array_values($this->get_values_for($this->get_primary_key()));

        $this->set_attributes($this->find($pk)->attributes);
        $this->reset_dirty();

        return $this;
    }//end reload()

    /**
     * Clones a Model Object.
     * 
     * @return \ActiveRecord\Model
     */
    public function __clone()
    {
        $this->relationships = array();
        $this->reset_dirty();
        return $this;
    }//end __clone()

    /**
     * Resets the dirty array.
     * 
     * @return void
     */
    public function reset_dirty()
    {
        $this->dirty = null;
    }//end reset_dirty()

    /**
     * Enables the use of dynamic finders.
     *
     * Dynamic finders are just an easy way to do queries quickly without having to
     * specify an options array with conditions in it.
     *
     * <code>
     * SomeModel::find_by_first_name('Priyanka');
     * SomeModel::find_by_first_name_and_last_name('Priyanka','the Grief');
     * SomeModel::find_by_first_name_or_last_name('Priyanka','the Grief');
     * SomeModel::find_all_by_last_name('Basak');
     * SomeModel::count_by_name('Tapun')
     * SomeModel::count_by_name_or_state('Tapun','VA')
     * SomeModel::count_by_name_and_state('Tapun','VA')
     * </code>
     *
     * You can also create the model if the find call returned no results:
     *
     * <code>
     * Person::find_or_create_by_name('Priyanka');
     *
     * # would be the equivalent of
     * if (!Person::find_by_name('Priyanka'))
     *   Person::create(array('Priyanka'));
     * </code>
     *
     * Some other examples of find_or_create_by:
     *
     * <code>
     * Person::find_or_create_by_name_and_id('Priyanka',1);
     * Person::find_or_create_by_name_and_id(array('name' => 'Priyanka', 'id' => 1));
     * </code>
     *
     * @param string $method Name of method.
     * @param mixed  $args   Method args.
     * 
     * @throws ActiveRecordException If invalid query.
     * 
     * @return Model
     */
    public static function __callStatic($method, $args)
    {
        $options = static::extract_and_validate_options($args);
        $create  = false;
        if (substr($method, 0, 17) == 'find_or_create_by') {
            $attributes = substr($method, 17);
            // Can't take any finders with OR in it when doing a find_or_create_by.
            if (strpos($attributes, '_or_') !== false) {
                throw new ActiveRecordException("Cannot use OR'd attributes in find_or_create_by");
            }
            $create = true;
            $method = 'find_by' . substr($method, 17);
        }
        if (substr($method, 0, 7) === 'find_by') {
            $attributes = substr($method, 8);
            $options['conditions'] = SQLBuilder::create_conditions_from_underscored_string(static::table()->conn, $attributes, $args, static::$alias_attribute);
            if (!($ret = static::find('first', $options)) && $create) {
                return static::create(SQLBuilder::create_hash_from_underscored_string($attributes, $args, static::$alias_attribute));
            }
            return $ret;
        } elseif (substr($method, 0, 11) === 'find_all_by') {
            $options['conditions'] = SQLBuilder::create_conditions_from_underscored_string(static::table()->conn, substr($method, 12), $args, static::$alias_attribute);
            return static::find('all', $options);
        } elseif (substr($method, 0, 8) === 'count_by') {
            $options['conditions'] = SQLBuilder::create_conditions_from_underscored_string(static::table()->conn, substr($method, 9), $args, static::$alias_attribute);
            return static::count($options);
        }
        throw new ActiveRecordException("Call to undefined method: $method");
    }//end __callStatic()

    /**
     * Enables the use of build|create for associations.
     *
     * @param string $method Name of method.
     * @param mixed  $args   Method args.
     * 
     * @throws ActiveRecordException Throws exception in case of invalid call.
     * 
     * @return mixed An instance of a given {@link AbstractRelationship}
     */
    public function __call($method, $args)
    {
        // Check for build|create_association methods.
        if (preg_match('/(build|create)_/', $method)) {
            if (!empty($args)) {
                $args = $args[0];
            }
            $association_name = str_replace(array('build_', 'create_'), '', $method);
            if (($association = static::table()->get_relationship($association_name))) {
                // Access association to ensure that the relationship has been loaded.
                // So that we do not double-up on records if we append a newly created.
                $this->$association_name;
                $method = str_replace($association_name, 'association', $method);
                return $association->$method($this, $args);
            }
        }
        throw new ActiveRecordException("Call to undefined method: $method");
    }//end __call()

    /**
     * Alias for self::find('all').
     *
     * @return array Array of records found.
     */
    public static function all()
    {
        return call_user_func_array('static::find', array_merge(array('all'), func_get_args()));
    }//end all()

    /**
     * Get a count of qualifying records.
     *
     * <code>
     * YourModel::count(array('conditions' => 'amount > 3.14159265'));
     * </code>
     *
     * @return integer Number of records that matched the query
     */
    public static function count()
    {
        $args = func_get_args();
        $options = static::extract_and_validate_options($args);
        $options['select'] = 'COUNT(*)';
        if (!empty($args)) {
            if (is_hash($args[0])) {
                $options['conditions'] = $args[0];
            } else {
                $options['conditions'] = call_user_func_array('static::pk_conditions', $args);
            }
        }
        $table  = static::table();
        $sql    = $table->options_to_sql($options);
        $values = $sql->get_where_values();
        return $table->conn->query_and_fetch_one($sql->to_s(), $values);
    }//end count()
     
    /**
     * Returns the result in a paginated format.
     * 
     * @param array           $params          The Query to be executed.
     * @param integer         $record_per_page Max number of record per page.
     * @param integer|boolean $offset          Offset of the quest.
     * 
     * @return array
     */
    public static function paginate(array $params, $record_per_page = 10, $offset = false)
    {
        if ($offset===false) {
            $request = \Kernel()->request();
            $offset  = $request->get('offset', 0);
        }
        $total_record = static::count($params);
            
        $responce                    = array();
        $responce['total_rows']      = $total_record;
        $responce['total_pages']     = ceil($total_record/$record_per_page);
        $responce['record_per_page'] = $record_per_page;
        $responce['current_page']    = ceil(($offset +1)/$record_per_page);
        $responce['start']           = $offset + 1;
        $current_record_suppose_ends = $offset + $record_per_page;
            
        if ($current_record_suppose_ends >= $total_record) {
            $responce['end']  = $total_record;
            $responce['next'] = false;
        } else {
            $responce['end']  = $current_record_suppose_ends;
            $responce['next'] = $offset + $record_per_page;
        }
            
        if ($offset <= 0) {
            $responce['offset']   = 0;
            $responce['previous'] = false;
        } else {
            $responce['offset']   = $offset;
            $responce['previous'] = $offset - $record_per_page;
        }
            
        $responce['rows'] = static::all(
            array_merge(
                $params,
                array(
                    'offset' => $offset,
                    'limit'  => $record_per_page
                )
            )
        );
        return $responce;
    }//end paginate()

    /**
     * Determine if a record exists.
     *
     * <code>
     * SomeModel::exists(123);
     * SomeModel::exists(array('conditions' => array('id=? and name=?', 123, 'Tito')));
     * SomeModel::exists(array('id' => 123, 'name' => 'Tito'));
     * </code>
     *
     * @return boolean
     */
    public static function exists()
    {
        return call_user_func_array('static::count', func_get_args()) > 0 ? true : false;
    }//end exists()

    /**
     * Alias for self::find('first').
     *
     * @return Model The first matched record or null if not found
     */
    public static function first()
    {
        return call_user_func_array('static::find', array_merge(array('first'), func_get_args()));
    }//end first()

    /**
     * Alias for self::find('last').
     *
     * @return Model The last matched record or null if not found
     */
    public static function last()
    {
        return call_user_func_array('static::find', array_merge(array('last'), func_get_args()));
    }//end last()

    /**
     * Find records in the database.
     *
     * Finding by the primary key:
     *
     * <code>
     * # queries for the model with id=123
     * YourModel::find(123);
     *
     * # queries for model with id in(1,2,3)
     * YourModel::find(1,2,3);
     *
     * # finding by pk accepts an options array
     * YourModel::find(123,array('order' => 'name desc'));
     * </code>
     *
     * Finding by using a conditions array:
     *
     * <code>
     * YourModel::find('first', array('conditions' => array('name=?','Tito'),
     *   'order' => 'name asc'))
     * YourModel::find('all', array('conditions' => 'amount > 3.14159265'));
     * YourModel::find('all', array('conditions' => array('id in(?)', array(1,2,3))));
     * </code>
     *
     * Finding by using a hash:
     *
     * <code>
     * YourModel::find(array('name' => 'Priyanka', 'id' => 1));
     * YourModel::find('first',array('name' => 'Priyanka', 'id' => 1));
     * YourModel::find('all',array('name' => 'Priyanka', 'id' => 1));
     * </code>
     *
     * An options array can take the following parameters:
     *
     * <ul>
     * <li><b>select:</b> A SQL fragment for what fields to return such as: '*', 'people.*', 'first_name, last_name, id'</li>
     * <li><b>joins:</b> A SQL join fragment such as: 'JOIN roles ON(roles.user_id=user.id)' or a named association on the model</li>
     * <li><b>conditions:</b> A SQL fragment such as: 'id=1', array('id=1'), array('name=? and id=?','Priyanka',1), array('name IN(?)', array('Mou','Tapun')),
     * array('name' => 'Priyanka', 'id' => 1)</li>
     * <li><b>limit:</b> Number of records to limit the query to</li>
     * <li><b>offset:</b> The row offset to return results from for the query</li>
     * <li><b>order:</b> A SQL fragment for order such as: 'name asc', 'name asc, id desc'</li>
     * <li><b>readonly:</b> Return all the models in readonly mode</li>
     * <li><b>group:</b> A SQL group by fragment</li>
     * </ul>
     *
     * @throws RecordNotFound If no options are passed or finding by pk and no records matched.
     * 
     * @return mixed An array of records found if doing a find_all otherwise a
     *   single Model object or null if it wasn't found. NULL is only return when
     *   doing a first/last find. If doing an all find and no records matched this
     *   will return an empty array.
     */
    public static function find()
    {
        $class = get_called_class();
        if (func_num_args() <= 0) {
            throw new RecordNotFound("Couldn't find $class without an ID");
        }
        $args     = func_get_args();
        $options  = static::extract_and_validate_options($args);
        $num_args = count($args);
        $single   = true;
        if ($num_args > 0 && ($args[0] === 'all' || $args[0] === 'first' || $args[0] === 'last')) {
            switch ($args[0]) {
                case 'all':
                    $single = false;
                    break;
                case 'last':
                    if (!array_key_exists('order', $options)) {
                        $options['order'] = join(' DESC, ', static::table()->pk) . ' DESC';
                    } else {
                        $options['order'] = SQLBuilder::reverse_order($options['order']);
                    }
                    // Fall thru.
                case 'first':
                    $options['limit']  = 1;
                    $options['offset'] = 0;
                    break;
            }
            $args = array_slice($args, 1);
            $num_args--;
        } elseif (1 === count($args) && 1 == $num_args) {
            $args = $args[0];
        }//end if

        // Anything left in $args is a find by pk.
        if ($num_args > 0 && !isset($options['conditions'])) {
            return static::find_by_pk($args, $options);
        }
        $options['mapped_names'] = static::$alias_attribute;

        //_debug(str_replace('Model\\', '', get_called_class()));

        $list = \Kernel()->events('model')->filter('read_'.strtolower(str_replace('Model\\', '', get_called_class())), static::table()->find($options));

        return $single ? (!empty($list) ? $list[0] : null) : $list;
    }//end find()

    /**
     * Finder method which will find by a single or array of primary keys for this model.
     *
     * @param mixed $values  An array containing values for the pk.
     * @param array $options An options array.
     * 
     * @throws RecordNotFound If a record could not be found.
     * 
     * @return Model.
     */
    public static function find_by_pk($values, array $options = array())
    {
        $options['conditions'] = static::pk_conditions($values);
        $list = static::table()->find($options);
        $results = count($list);

        if ($results != ($expected = @count($values))) {
            $class = get_called_class();

            if ($expected == 1) {
                if (!is_array($values)) {
                    $values = array($values);
                }

                throw new RecordNotFound("Couldn't find $class with ID=" . join(',', $values));
            }

            $values = join(',', $values);
            throw new RecordNotFound("Couldn't find all $class with IDs ($values) (found $results, but was looking for $expected)");
        }
        return $expected == 1 ? $list[0] : $list;
    }//end find_by_pk()

    /**
     * Find using a raw SELECT query.
     *
     * <code>
     * YourModel::find_by_sql("SELECT * FROM people WHERE name=?",array('Tito'));
     * YourModel::find_by_sql("SELECT * FROM people WHERE name='Tito'");
     * </code>
     *
     * @param string $sql    The raw SELECT query.
     * @param mixed  $values An array of values for any parameters that needs to be bound.
     * 
     * @return array An array of models
     */
    public static function find_by_sql($sql, $values = null)
    {
        return static::table()->find_by_sql($sql, $values, true);
    }//end find_by_sql()

    /**
     * Determines if the specified array is a valid ActiveRecord options array.
     *
     * @param mixed   $array An options array.
     * @param boolean $throw True to throw an exception if not valid.
     * 
     * @throws ActiveRecordException If the array contained any invalid options.
     * 
     * @return boolean True if valid otherwise valse.
     */
    public static function is_options_hash($array, $throw = true)
    {
        if (is_hash($array)) {
            $keys = array_keys($array);
            $diff = array_diff($keys, self::$VALID_OPTIONS);

            if (!empty($diff) && $throw) {
                throw new ActiveRecordException("Unknown key(s): " . join(', ', $diff));
            }

            $intersect = array_intersect($keys, self::$VALID_OPTIONS);

            if (!empty($intersect)) {
                return true;
            }
        }
        return false;
    }//end is_options_hash()

    /**
     * Returns a hash containing the names => values of the primary key.
     * Note:  This needs to eventually support composite keys.
     * 
     * @param mixed $args Primary key value(s).
     * 
     * @return array An array in the form on key => value.
     */
    public static function pk_conditions($args)
    {
        $table = static::table();
        $ret   = array($table->pk[0] => $args);
        return $ret;
    }//end pk_conditions()

    /**
     * Pulls out the options hash from $array if any.
     * Note: DO NOT remove the reference on $array.
     * 
     * @param array &$array An array.
     * 
     * @throws Exception Throws Exception incase of error.
     * 
     * @return array A valid options array.
     */
    public static function extract_and_validate_options(array &$array)
    {
        $options = array();

        if ($array) {
            $last = &$array[count($array)-1];

            //_debug($array);

            try {
                if (self::is_options_hash($last)) {
                    array_pop($array);
                    $options = $last;
                }
            } catch (ActiveRecordException $e) {
                if (!is_hash($last)) {
                    throw $e;
                }

                $options = array('conditions' => $last);
            }
        }
        return $options;
    }//end extract_and_validate_options()


    public function attach($name, $value)
    {
        if(isset($this->attachment[$name])) {
            return false;
        }
        $this->attachment[$name] = $value;
    }

    public function attachmentExists($name)
    {
        return isset($this->attachment[$name]);
    }

    public function load($name)
    {
        if($this->attachmentExists($name)) {
            if(is_callable($this->attachment[$name])) {
                return call_user_func_array($this->attachment[$name], array($this));
            }
            return $this->attachment[$name];
        }
        throw new \Exception('Attachment '.$name.' do not exists');
    }


    /**
     * Returns a JSON representation of this model.
     *
     * @param array $options An array containing options for json serialization (see {@link Serialization} for valid options).
     *
     * @return string JSON representation of the model
     */
    public function to_json(array $options = array())
    {
        return $this->serialize('Json', $options);
    }//end to_json()

    /**
     * Returns an XML representation of this model.
     * 
     * @param array $options An array containing options for xml serialization (see {@link Serialization} for valid options).
     * 
     * @return string XML representation of the model
     */
    public function to_xml(array $options = array())
    {
        return $this->serialize('Xml', $options);
    }//end to_xml()

    /**
     * Creates a serializer based on pre-defined to_serializer().
     *
     * An options array can take the following parameters:
     *
     * <ul>
     * <li><b>only:</b> a string or array of attributes to be included.</li>
     * <li><b>excluded:</b> a string or array of attributes to be excluded.</li>
     * <li><b>methods:</b> a string or array of methods to invoke. The method's name will be used as a key for the final attributes array
     * along with the method's returned value</li>
     * <li><b>include:</b> a string or array of associated models to include in the final serialized product.</li>
     * </ul>
     *
     * @param string $type    Either Xml or Json.
     * @param array  $options Options array for the serializer.
     * 
     * @return string Serialized representation of the model
     */
    private function serialize($type, array $options)
    {
        include_once 'Serialization.php';
        $class      = "ActiveRecord\\{$type}Serializer";
        $serializer = new $class($this, $options);
        return $serializer->to_s();
    }//end serialize()

    /**
     * Invokes the specified callback on this model.
     *
     * @param string  $method_name Name of the call back to run.
     * @param boolean $must_exist  Set to true to raise an exception if the callback does not exist.
     * 
     * @return boolean True if invoked or null if not
     */
    private function invoke_callback($method_name, $must_exist = true)
    {
        return static::table()->callback->invoke($this, $method_name, $must_exist);
    }//end invoke_callback()

    /**
     * Executes a block of code inside a database transaction.
     *
     * <code>
     * YourModel::transaction(function()
     * {
     *   YourModel::create(array("name" => "blah"));
     * });
     * </code>
     *
     * If an exception is thrown inside the closure the transaction will
     * automatically be rolled back. You can also return false from your
     * closure to cause a rollback:
     *
     * <code>
     * YourModel::transaction(function()
     * {
     *   YourModel::create(array("name" => "blah"));
     *   throw new Exception("rollback!");
     * });
     *
     * YourModel::transaction(function()
     * {
     *   YourModel::create(array("name" => "blah"));
     *   return false; # rollback!
     * });
     * </code>
     *
     * @param Closure $closure The closure to execute. To cause a rollback have your closure return false or throw an exception.
     * 
     * @throws Exception Throws exception id unable to run the transaction.
     * 
     * @return boolean True if the transaction was committed, False if rolled back.
     */
    public static function transaction(Closure $closure)
    {
        $connection = static::connection();

        try {
            $connection->transaction();

            if (call_user_func($closure) === false) {
                $connection->rollback();
                return false;
            } else {
                $connection->commit();
            }
        } catch (\Exception $e) {
            $connection->rollback();
            throw $e;
        }
        return true;
    }//end transaction()
}//end class
