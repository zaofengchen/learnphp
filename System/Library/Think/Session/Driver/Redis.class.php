<?php
namespace Think\Session\Driver;
// use think\Exception;

class Redis implements \SessionHandlerInterface
{
    /** @var \Redis */
    protected $handler = null;
    protected $config = [];

    /**
     * 架构函数
     * @param array $config 参数
     * @access public
     */
    public function __construct($config=array()) 
    {
        $config = array_merge( array (
            'host'         => C('REDIS_HOST') ? :'127.0.0.1', // redis主机
            'port'         => C('REDIS_PORT') ? :6379, // redis端口
            'password'     => C('REDIS_AUTH') ? :'', // 密码
            'select'       => 0, // 操作库
            'expire'       => 3600, // 有效期(秒)
            'timeout'      => 60, // 超时时间(秒)
            'persistent'   => true, // 是否长连接
            'session_name' => C('SESSION_PREFIX'), // sessionkey前缀
        ),$this->config);
        $this->config = $config;
    }

    /**
     * 打开Session
     * @access public
     * @param string $savePath
     * @param mixed  $sessName
     * @return bool
     * @throws Exception
     */
    public function open($savePath, $sessName)
    {
        if (!extension_loaded('redis')) {
            E(L('_NOT_SUPPORT_').':redis');
        }
        $this->handler = new \Redis;
        // 建立连接
        $func = $this->config['persistent'] ? 'pconnect' : 'connect';
        $this->handler->$func($this->config['host'], $this->config['port'], $this->config['timeout']);
        if ('' != $this->config['password']) {
            $this->handler->auth($this->config['password']);
        }
        if (0 != $this->config['select']) {
            $this->handler->select($this->config['select']);
        }
        // var_dump($this->handler->get('array_category'));
        return true;
    }

    /**
     * 关闭Session
     * @access public
     */
    public function close()
    {
        $this->gc(ini_get('session.gc_maxlifetime'));
        $this->handler->close();
        $this->handler = null;
        return true;
    }

    /**
     * 读取Session
     * @access public
     * @param string $sessID
     * @return string
     */
    public function read($sessID)
    {
        return (string) $this->handler->get($this->config['session_name'] . $sessID);
    }

    /**
     * 写入Session
     * @access public
     * @param string $sessID
     * @param String $sessData
     * @return bool
     */
    public function write($sessID, $sessData)
    {
        if ($this->config['expire'] > 0) {
            return $this->handler->setex($this->config['session_name'] . $sessID, $this->config['expire'], $sessData);
        } else {
            return $this->handler->set($this->config['session_name'] . $sessID, $sessData);
        }
    }

    /**
     * 删除Session
     * @access public
     * @param string $sessID
     * @return bool
     */
    public function destroy($sessID)
    {
        return $this->handler->delete($this->config['session_name'] . $sessID) > 0;
    }

    /**
     * Session 垃圾回收
     * @access public
     * @param string $sessMaxLifeTime
     * @return bool
     */
    public function gc($sessMaxLifeTime)
    {
        return true;
    }
}
