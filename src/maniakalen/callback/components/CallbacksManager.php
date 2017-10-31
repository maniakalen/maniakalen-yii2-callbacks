<?php
/**
 * PHP Version 5.5
 *
 *  Callback manager to run inline callback methods
 *
 * @category Manager
 * @package  Callbacks
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     LINK
 */

namespace maniakalen\callback\components;

use maniakalen\callback\exceptions\CallbackException;
use yii\base\Component;

/**
 * Class CallbacksManager
 *
 *  Callback manager to run inline callback methods
 *
 * @category Manager
 * @package  Callbacks
 * @author   Peter Georgiev <peter.georgiev@concatel.com>
 * @license  GNU GENERAL PUBLIC LICENSE https://www.gnu.org/licenses/gpl.html
 * @link     -
 */
class CallbacksManager extends Component
{

    /**
     * Takes as param a callback function and executes it with the extra params given.
     * Returns the result of the given function
     *
     * @param string $callback Callback function
     *
     * @return mixed
     * @throws CallbackException
     */
    public function run($callback)
    {
        try {
            $params = func_get_args();
            array_shift($params);
            if (is_string($callback)) {
                if (!$this->isCallback($callback)) {
                    throw new \Exception("Given parameter is not a valid callback");
                }
                $callback = $this->evaluateStringExpression($callback);
            }
            if (!is_callable($callback)) {
                throw new CallbackException("Provided callback is not a valid callable");
            }
            return call_user_func_array($callback, $params);
        } catch (\Exception $ex) {
            throw new CallbackException("Failed to execute callback method", 0, $ex);
        }
    }

    /**
     * This method verifies if the string is a valid inline function
     *
     * @param string $callback String to be verified if it can be treaten as callback
     *
     * @return bool
     */
    public function isCallback($callback)
    {
        return strpos($callback, 'function(') === 0;
    }

    /**
     * Evaluates php expression presented as string
     *
     * @param string $expression expression to be evaluated
     *
     * @return mixed
     */
    public function evaluateStringExpression($expression)
    {
        $temp = tempnam('/tmp', 'cbk');
        file_put_contents($temp, '<?php return ' . $expression . '; ?>');
        $callback = include $temp;
        unlink($temp);
        return $callback;
    }
}