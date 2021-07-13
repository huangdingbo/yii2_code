<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\base;

use Yii;

/**
 * BaseObject is the base class that implements the *property* feature.
 *
属性是由getter方法定义的(例如:' getLabel ')和/或一个setter方法(例如。“setLabel”)。例如,
下面的getter和setter方法定义了一个名为“label”的属性:
 *
 * ```php
 * private $_label;
 *
 * public function getLabel()
 * {
 *     return $this->_label;
 * }
 *
 * public function setLabel($value)
 * {
 *     $this->_label = $value;
 * }
 * ```
 *
 * Property names are *case-insensitive*.
 * 属性名不区分大小写。
 *
 * A property can be accessed like a member variable of an object. Reading or writing a property will cause the invocation
 * of the corresponding getter or setter method. For example,
 **属性可以像对象的成员变量一样被访问。读取或写入属性将导致调用
 *对应的getter或setter方法。例如,
 *
 * ```php
 * // equivalent to $label = $object->getLabel();
 * $label = $object->label;
 * // equivalent to $object->setLabel('abc');
 * $object->label = 'abc';
 * ```
 *
 * If a property has only a getter method and has no setter method, it is considered as *read-only*. In this case, trying
 * to modify the property value will cause an exception.
 * 如果一个属性只有一个getter方法而没有setter方法，那么它被认为是*只读的*。在这种情况下，尝试
 * 修改属性值将导致异常。
 *
 *
 * One can call [[hasProperty()]], [[canGetProperty()]] and/or [[canSetProperty()]] to check the existence of a property.
 * *可以调用[[hasProperty()]]， [[canGetProperty()]]和/或[[canSetProperty()]]来检查属性是否存在。
 *
 *
 * Besides the property feature, BaseObject also introduces an important object initialization life cycle. In particular,
 * creating an new instance of BaseObject or its derived class will involve the following life cycles sequentially:
 * *除了属性特性之外，BaseObject还引入了一个重要的对象初始化生命周期。特别是,
 *创建一个新的BaseObject实例或它的派生类将涉及以下生命周期顺序:
 *
 *
 * 1. the class constructor is invoked; * 1。调用类构造函数;
 * 2. object properties are initialized according to the given configuration; * 2。对象属性根据给定的配置进行初始化;
 * 3. the `init()` method is invoked. * 3。调用' init() '方法。
 *
 * In the above, both Step 2 and 3 occur at the end of the class constructor. It is recommended that
 * you perform object initialization in the `init()` method because at that stage, the object configuration
 * is already applied.
 * 在上面的例子中，第2步和第3步都发生在类构造函数的末尾。建议:
你在' init() '方法中执行对象初始化，因为在那个阶段，对象配置
 *已经被应用。
 *
 * In order to ensure the above life cycles, if a child class of BaseObject needs to override the constructor,
 * it should be done like the following:
 * *为了确保上面的生命周期，如果BaseObject的子类需要重写构造函数，
应该这样做:
 *
 * ```php
 * public function __construct($param1, $param2, ..., $config = [])
 * {
 *     ...
 *     parent::__construct($config);
 * }
 * ```
 *
 * That is, a `$config` parameter (defaults to `[]`) should be declared as the last parameter
 * of the constructor, and the parent implementation should be called at the end of the constructor.
 * *也就是说，一个' $config '参数(默认为'[]')应该声明为最后一个参数
的构造函数，父实现应该在构造函数的末尾调用
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0.13
 */
class BaseObject implements Configurable
{
    /**
     * Returns the fully qualified name of this class. 返回该类的完全限定名。
     * @return string the fully qualified name of this class. 这个类的完全限定名。
     * @deprecated since 2.0.14. On PHP >=5.5, use `::class` instead.
     *
     * get_class() 返回自身类名
     * get_called_class() 返回当前类名
     *
     *
    class Foo{
        public function test(){
            var_dump(get_class());
        }

        public function test2(){
            var_dump(get_called_class());
        }
    }

    class B extends Foo{

    }
    $B=new B();
    $B->test();
    $B->test2();
    string 'Foo' (length=3)
    string 'B' (length=1)
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * Constructor.
     *
     * The default implementation does two things:
     *
     * - Initializes the object with the given configuration `$config`.
     * - Call [[init()]].
     *
     * If this method is overridden in a child class, it is recommended that
     *
     * - the last parameter of the constructor is a configuration array, like `$config` here.
     * - call the parent implementation at the end of the constructor.
     *
     * *构造函数。
     *
     *默认实现做两件事:
     *
     * -用给定的配置' $config '初始化对象。
     * -调用[[init()]]。
     *
     *如果在子类中重写此方法，建议
     *
     * -构造函数的最后一个参数是一个配置数组，就像这里的' $config '。
     * -在构造函数的末尾调用父实现。
     *
     *
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            Yii::configure($this, $config);
        }
        $this->init();
    }

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
    }

    /**
     * Returns the value of an object property.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$value = $object->property;`.
     * 返回一个对象属性的值。
     *
     *不要直接调用这个方法，因为它是一个PHP魔术方法
      当执行' $value = $object-&gt;property; '时，将隐式调用*。
     * @param string $name the property name
     * @return mixed the property value
     * @throws UnknownPropertyException if the property is not defined
     * @throws InvalidCallException if the property is write-only
     * @see __set()
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        }

        throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
    }

    /**
     * Sets value of an object property.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$object->property = $value;`.
     * @param string $name the property name or the event name
     * @param mixed $value the property value
     * @throws UnknownPropertyException if the property is not defined
     * @throws InvalidCallException if the property is read-only
     * @see __get()
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Checks if a property is set, i.e. defined and not null.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `isset($object->property)`.
     * *检查一个属性是否设置，即定义和非空。
     *
     *不要直接调用这个方法，因为它是一个PHP魔术方法
     *将在执行' isset($object-&gt;property) '时被隐式调用。
     *注意，如果没有定义该属性，将返回false。
     *
     * Note that if the property is not defined, false will be returned.
     * @param string $name the property name or the event name
     * @return bool whether the named property is set (not null).
     * @see https://secure.php.net/manual/en/function.isset.php
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }

        return false;
    }

    /**
     * Sets an object property to null.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `unset($object->property)`.
     *
     * Note that if the property is not defined, this method will do nothing.
     * If the property is read-only, it will throw an exception.
     *
     * *设置一个对象属性为空。
     *
     *不要直接调用这个方法，因为它是一个PHP魔术方法
      当执行' unset($object->property) '时将隐式调用*。
     *
     *注意，如果属性没有定义，这个方法将什么也不做。
     *如果属性是只读的，它将抛出异常。
     *
     * @param string $name the property name
     * @throws InvalidCallException if the property is read only.
     * @see https://secure.php.net/manual/en/function.unset.php
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Unsetting read-only property: ' . get_class($this) . '::' . $name);
        }
    }

    /**
     * Calls the named method which is not a class method.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when an unknown method is being invoked.
     *
     * 调用非类方法的命名方法。
     *
     *不要直接调用这个方法，因为它是一个PHP魔术方法
     *将在调用未知方法时隐式调用。
     *
     * @param string $name the method name
     * @param array $params method parameters
     * @throws UnknownMethodException when calling unknown method
     * @return mixed the method return value
     */
    public function __call($name, $params)
    {
        throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$name()");
    }

    /**
     * Returns a value indicating whether a property is defined.
     *
     * A property is defined if:
     *
     * - the class has a getter or setter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * 返回一个值，指示是否定义了一个属性。
     *
     *如果有以下情况定义属性:
     *
     * -类有一个与指定名称相关联的getter或setter方法
     *(在本例中，属性名不区分大小写);
     * -类有一个具有指定名称的成员变量(当' $checkVars '为true时);
     *
     * @param string $name the property name
     * @param bool $checkVars whether to treat member variables as properties 是否将成员变量视为属性
     * @return bool whether the property is defined
     * @see canGetProperty()
     * @see canSetProperty()
     */
    public function hasProperty($name, $checkVars = true)
    {
        return $this->canGetProperty($name, $checkVars) || $this->canSetProperty($name, false);
    }

    /**
     * Returns a value indicating whether a property can be read.
     *
     * A property is readable if:
     *
     * - the class has a getter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * 返回一个值，该值指示一个属性是否可以读取。
     *
      一个属性是可读的，如果:
     *
     * -类有一个与指定名称相关联的getter方法
     * (在本例中，属性名不区分大小写);
     * -类有一个具有指定名称的成员变量(当' $checkVars '为true时);
     *
     * @param string $name the property name
     * @param bool $checkVars whether to treat member variables as properties
     * @return bool whether the property can be read
     * @see canSetProperty()
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name);
    }

    /**
     * Returns a value indicating whether a property can be set.
     *
     * A property is writable if:
     *
     * - the class has a setter method associated with the specified name
     *   (in this case, property name is case-insensitive);
     * - the class has a member variable with the specified name (when `$checkVars` is true);
     *
     * 返回一个值，指示是否可以设置一个属性。
     *
     *一个属性是可写的，如果:
     *
     * -类有一个与指定名称相关联的setter方法
     *(在本例中，属性名不区分大小写);
     * -类有一个具有指定名称的成员变量(当' $checkVars '为true时);
     *
     * @param string $name the property name
     * @param bool $checkVars whether to treat member variables as properties
     * @return bool whether the property can be written
     * @see canGetProperty()
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return method_exists($this, 'set' . $name) || $checkVars && property_exists($this, $name);
    }

    /**
     * Returns a value indicating whether a method is defined.
     *
     * The default implementation is a call to php function `method_exists()`.
     * You may override this method when you implemented the php magic method `__call()`.
     *
     * 返回一个值，指示是否定义了一个方法。
     *
     *默认实现是调用php函数' method_exists() '。
     *当你实现php的魔法方法' __call() '时，你可以重写这个方法。
     *
     * @param string $name the method name
     * @return bool whether the method is defined
     */
    public function hasMethod($name)
    {
        return method_exists($this, $name);
    }
}
