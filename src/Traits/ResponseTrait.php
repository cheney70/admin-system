<?php
namespace Cheney\Content\Traits;

use App\Http\Constants\ResponseCodeConstant;
use stdClass;

/**
 * Created by PhpStorm.
 * User: codeanti
 * Date: 2020-1-4
 * Time: 下午3:08
 */
trait ResponseTrait
{
    /**
     * 找不到资源 统一返回格式
     * @param string $message
     * @return string
     * @author CodeAnti
     */
    public static function resourceNotFound($message = "Not Found Resource")
    {
        return self::encodeResult(200006, $message);
    }

    /**
     * 参数不合法 统一返回格式
     * @param null $message
     * @return string
     * @author CodeAnti
     */
    public static function parametersIllegal($message = null)
    {
        return self::encodeResult(200003, $message,false);
    }


    /**
     * 正确的返回统一格式
     * @param $data
     * @return string
     * @author CodeAnti
     */
    public static function success($data = null)
    {
        return self::encodeResult(100000, 'success', $data);
    }

    /**
     * 失败的返回统一格式
     * @param $message
     * @return string
     * @author CodeAnti
     */
    public static function fail($message = null)
    {
        return self::encodeResult(2000000, $message,false);
    }

    /**
     * 错误的返回统一格式
     * @param $code
     * @param null $message
     * @param $data
     * @return string
     * @author CodeAnti
     */
    public static function error($code, $message = null, $data = null)
    {
        return self::encodeResult($code, $message, $data);
    }

    /**
     * 统一返回格式
     * @param  $msgCode
     * @param  $message
     * @param  $data
     * @return string
     * @author CodeAnti
     */
    public static function encodeResult($msgCode, $message = null, $data = null)
    {
        if ($data == null) {
            $data = new stdClass();
        }

        $result = [
            'msg_code'    => $msgCode,
            'message'     => $message,
            'response'    => $data,
            'server_time' => time()
        ];
        return response()->json($result);
    }
}
