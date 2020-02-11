<?php

namespace Ministra\Storage\Lib;

use Exception;
class RESTManager
{
    private function __construct()
    {
    }
    public static function handleRequest()
    {
        $result = null;
        $response = new \Ministra\Storage\Lib\RESTResponse();
        try {
            $request = new \Ministra\Storage\Lib\RESTRequest();
            if (($request->getAction() != 'get' || !\in_array($request->getResource(), ['vclub', 'karaoke', 'recorder'])) && (!\in_array($request->getAction(), ['create', 'update']) && !\in_array($request->getResource(), ['recorder']))) {
                self::checkAccess($request->getAccessToken());
            }
            $cmd_r = new \Ministra\Storage\Lib\RESTCommandResolver();
            $cmd = $cmd_r->getCommand($request);
            $result = $cmd->execute($request);
        } catch (\Exception $e) {
            $response->setError($e->getMessage());
        }
        $response->setBody($result);
        $response->send();
    }
    public static function checkAccess($token)
    {
        if (\defined('STORAGE_NAME')) {
            $storage_name = STORAGE_NAME;
        } else {
            $storage_name = $_SERVER['SERVER_NAME'] ? $_SERVER['SERVER_NAME'] : $_SERVER['SERVER_ADDR'];
        }
        if (!\defined('PORTAL_URL')) {
            throw new \Ministra\Storage\Lib\RESTManagerException('STORAGE ' . $storage_name . ': PORTAL_URL is not defined');
        }
        $token_resp = \file_get_contents(PORTAL_URL . '/server/api/chk_storage_token.php?token=' . $token);
        if ($token_resp === false) {
            throw new \Ministra\Storage\Lib\RESTManagerException('STORAGE ' . $storage_name . ': Portal connection failure');
        }
        $token_resp = \json_decode($token_resp, true);
        if ($token_resp === false || !isset($token_resp['result'])) {
            throw new \Ministra\Storage\Lib\RESTManagerException('STORAGE ' . $storage_name . ': Could not decode portal response');
        }
        if ($token_resp['result'] !== true) {
            throw new \Ministra\Storage\Lib\RESTManagerException('STORAGE ' . $storage_name . ': Not valid token', $token);
        }
    }
}
