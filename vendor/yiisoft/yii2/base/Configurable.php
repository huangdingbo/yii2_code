<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\base;

/**
 * Configurable is the interface that should be implemented by classes who support configuring
 * its properties through the last parameter to its constructor.
 *
 * The interface does not declare any method. Classes implementing this interface must declare their constructors
 * like the following:
 *
 * ```php
 * public function __constructor($param1, $param2, ..., $config = [])
 * ```
 *
 * That is, the last parameter of the constructor must accept a configuration array.
 *
 * This interface is mainly used by [[\yii\di\Container]] so that it can pass object configuration as the
 * last parameter to the implementing class' constructor.
 *
 * For more details and usage information on Configurable, see the [guide article on configurations](guide:concept-configurations).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0.3
 *
 *
 *
 * 可配置的接口应该由支持配置的类实现
 *它的属性通过最后一个参数到它的构造函数。
 *
 *接口没有声明任何方法。实现此接口的类必须声明其构造函数
 *如下列:
 *
 *’‘php
 * public function __constructor($param1， $param2，…， $config = [])
 *’‘
 *
 *也就是说，构造函数的最后一个参数必须接受配置数组。
 *
 *此接口主要由[[\yii\di\Container]]使用，以便它可以传递对象配置作为
 *实现类构造函数的最后一个参数。
 *
 *关于可配置的更多细节和使用信息，请参见[配置指南](指南:concept-configurations)。
 */
interface Configurable
{
}
