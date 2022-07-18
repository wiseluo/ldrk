<?php



namespace app\webscoket;


use Swoole\Server;
use Swoole\Websocket\Frame;
use think\Event;
use think\facade\Cache;
use think\response\Json;
use think\swoole\Websocket;
use think\swoole\websocket\Room;
use app\webscoket\Room as NowRoom;

/**
 * Class Manager
 * @package app\webscoket
 */
class Manager extends Websocket
{

    /**
     * @var Ping
     */
    protected $pingService;

    /**
     * @var int
     */
    protected $cache_timeout;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var \Redis
     */
    protected $cache;

    /**
     * @var NowRoom
     */
    protected $nowRoom;

    const USER_TYPE = ['admin', 'user', 'kefu'];

    /**
     * Manager constructor.
     * @param Server $server
     * @param Room $room
     * @param Event $event
     * @param Response $response
     * @param Ping $ping
     * @param \app\webscoket\Room $nowRoom
     */
    public function __construct(Server $server, Room $room, Event $event, Response $response, Ping $ping, NowRoom $nowRoom)
    {
        parent::__construct($server, $room, $event);
        $this->response = $response;
        $this->pingService = $ping;
        $this->nowRoom = $nowRoom;
        $this->cache = Cache::store('redis')->handler();
        $this->nowRoom->setCache($this->cache);
        $this->cache_timeout = intval(app()->config->get('swoole.websocket.ping_timeout', 60000) / 1000) + 2;
    }

    /**
     * @param int $fd
     * @param Request $request
     * @return mixed
     * @author xaboy
     * @day 2020-05-06
     */
    public function onOpen($fd, \think\Request $request)
    {
        $type = $request->get('type');
        $token = $request->get('token');
        $touristUid = $request->get('tourist_uid', '');
        $tourist = !!$touristUid;
        if (!$token || !in_array($type, self::USER_TYPE)) {
            return $this->server->close($fd);
        }
        // 只有用户模式下才能使用游客模式
        if ($type !== self::USER_TYPE[1] && $tourist) {
            return $this->server->close($fd);
        }
        $types = self::USER_TYPE;
        $this->nowRoom->type(array_flip($types)[$type]);
        try {
            $data = $this->exec($type, 'login', [$fd, $request->get('form_type', null), ['token' => $token, 'tourist' => $tourist], $this->response])->getData();
        } catch (\Throwable $e) {
            return $this->server->close($fd);
        }
        if ($tourist) {
            $data['status'] = 200;
            $data['data']['uid'] = $touristUid;
        }
        if ($data['status'] != 200 || !($data['data']['uid'] ?? null)) {
            return $this->server->close($fd);
        }
        $uid = $data['data']['uid'];
        $type = array_search($type, self::USER_TYPE);
        $this->login($type, $uid, $fd);
        $this->nowRoom->add((string)$fd, $uid, 0, $tourist ? 1 : 0);
        $this->pingService->createPing($fd, time(), $this->cache_timeout);
        $this->send($fd, $this->response->message('ping', ['now' => time()]));
        return $this->send($fd, $this->response->success());
    }

    public function login($type, $uid, $fd)
    {
        $key = '_ws_' . $type;
        $this->cache->sadd($key, $fd);
        $this->cache->sadd($key . $uid, $fd);
        $this->refresh($type, $uid);
    }

    public function refresh($type, $uid)
    {
        $key = '_ws_' . $type;
        $this->cache->expire($key, 1800);
        $this->cache->expire($key . $uid, 1800);
    }

    public function logout($type, $uid, $fd)
    {
        $key = '_ws_' . $type;
        $this->cache->srem($key, $fd);
        $this->cache->srem($key . $uid, $fd);
    }


    public static function userFd($type, $uid = '')
    {
        $key = '_ws_' . $type . $uid;
        return Cache::store('redis')->handler()->smembers($key) ?: [];
    }

    /**
     * 执行事件调度
     * @param $type
     * @param $method
     * @param $result
     * @return null|Json
     * @author xaboy
     * @day 2020-05-06
     */
    protected function exec($type, $method, $result)
    {
        if (!in_array($type, self::USER_TYPE)) {
            return null;
        }
        if (!is_array($result)) {
            return null;
        }
        /** @var Json $response */
        return $this->event->until('swoole.websocket.' . $type, [$method, $result, $this, $this->nowRoom]);
    }

    /**
     * @param Frame $frame
     * @return bool
     * @author xaboy
     * @day 2020-04-29
     */
    public function onMessage(Frame $frame)
    {
        $info = $this->nowRoom->get($frame->fd);
        $result = json_decode($frame->data, true) ?: [];

        if (!isset($result['type']) || !$result['type']) return true;
        $this->refresh($info['type'], $info['uid']);
        if ($result['type'] == 'ping') {
            return $this->send($frame->fd, $this->response->message('ping', ['now' => time()]));
        }
        $data = $result['data'] ?? [];
        $frame->uid = $info['uid'];
        /** @var Response $res */
        $res = $this->exec(self::USER_TYPE[$info['type']], $result['type'], [$frame->fd, $result['form_type'] ?? null, $data, $this->response]);
        if ($res) return $this->send($frame->fd, $res);
        return true;
    }

    /**
     * 发送文本响应
     * @param $fd
     * @param Json $json
     * @return bool
     */
    public function send($fd, \think\response\Json $json)
    {
        $this->pingService->createPing($fd, time(), $this->cache_timeout);
        return $this->pushing($fd, $json->getData());
    }

    /**
     * 发送
     * @param $data
     * @return bool
     */
    public function pushing($fds, $data, $exclude = null)
    {
        if ($data instanceof \think\response\Json) {
            $data = $data->getData();
        }
        $data = is_array($data) ? json_encode($data) : $data;
        $fds = is_array($fds) ? $fds : [$fds];
        foreach ($fds as $fd) {
            if (!$fd) {
                continue;
            }
            if ($exclude && is_array($exclude) && !in_array($fd, $exclude)) {
                continue;
            } elseif ($exclude && $exclude == $fd) {
                continue;
            }
            $this->server->push($fd, $data);
        }
        return true;
    }

    /**
     * 关闭连接
     * @param int $fd
     * @param int $reactorId
     * @author xaboy
     * @day 2020-04-29
     */
    public function onClose($fd, $reactorId)
    {
        $tabfd = (string)$fd;
        if ($this->nowRoom->exist($fd)) {
            $data = $this->nowRoom->get($tabfd);
            $this->logout($data['type'], $data['uid'], $fd);
            $this->nowRoom->type($data['type'])->del($tabfd);
            $this->exec(self::USER_TYPE[$data['type']], 'close', [$fd, null, ['data' => $data], $this->response]);
        }
        $this->pingService->removePing($fd);
    }
}
