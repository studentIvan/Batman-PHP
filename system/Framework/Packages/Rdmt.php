<?php
namespace Framework\Packages;

class Rdmt
{
    protected $stream, $sAddr, $sPort, $cState, $defaultSCPM, $excMode;

    const EXCEPT = true;
    const NOT_EXCEPT = false;

    /**
     * Rdmt object constructor
     *
     * @throws \Exception
     * @param int $sPort Server port [default 3308]
     * @param int $defaultSCPM Default value for count of signals per minutes [default 500]
     * @param string $sAddr Server address [default 127.0.0.1]
     */
    public function __construct($sPort = 3308, $defaultSCPM = 500, $sAddr = '127.0.0.1')
    {
        if (!$this->stream = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) {
            $e = socket_strerror(socket_last_error());
            socket_clear_error();
            throw new \Exception($e);
        }
        else
        {
            $timeout = array('sec' => 3, 'usec' => 0);
            socket_set_option($this->stream, SOL_SOCKET, SO_RCVTIMEO, $timeout);
            socket_set_option($this->stream, SOL_SOCKET, SO_SNDTIMEO, $timeout);
            $this->defaultSCPM = $defaultSCPM;
            $this->excMode = self::EXCEPT;
            $this->sAddr = $sAddr;
            $this->sPort = $sPort;
            $this->cState = false;
            return $this;
        }
    }

    /**
     * @param int $sPort Server port
     * @return \Framework\Packages\Rdmt
     */
    public function setServerPort($sPort)
    {
        $this->sPort = $sPort;
        return $this;
    }

    /**
     * @param string $sAddr Server address
     * @return \Framework\Packages\Rdmt
     */
    public function setServerAddress($sAddr)
    {
        $this->sAddr = $sAddr;
        return $this;
    }

    /**
     * @param int $defaultSCPM Default value for count of signals per minutes
     * @return \Framework\Packages\Rdmt
     */
    public function setDefaultSignalsPerMinutes($defaultSCPM)
    {
        $this->defaultSCPM = $defaultSCPM;
        return $this;
    }

    /**
     * Turn except mode On/Off
     *
     * On - throws exception on error [default]
     * Off - accept signal on error (not violate system work)
     *
     * Valid values ​​are:
     * Rdmt::EXCEPT - true
     * Rdmt::NOT_EXCEPT - false
     *
     * @param bool $excMode
     * @return \Framework\Packages\Rdmt
     */
    public function setExceptionMode($excMode)
    {
        $this->excMode = $excMode;
        return $this;
    }

    /**
     * Ask RDMT Server - accept or not accept signal
     *
     * Signal may be simple string, user id, database table name, sql-query, etc ...
     *
     * @throws \Exception
     * @param string $signal
     * @param int $SCPM Signals count per minutes [default - using default value]
     * @return bool
     */
    public function accept($signal, $SCPM = 0)
    {
        if ($SCPM === 0) $SCPM = $this->defaultSCPM;
        try
        {
            $this->_connect();
            $this->_query($signal, $SCPM);
            $result = $this->_answer();
        }
        catch (\Exception $e)
        {
            if ($this->excMode) {
                throw $e;
            } else {
                return true;
            }
        }
        return ($result === 1);
    }

    /**
     * Close RDMT Server Connection
     *
     * @return void
     */
    public function flush()
    {
        if ($this->cState) socket_close($this->stream);
        $this->cState = false;
    }

    protected function _connect()
    {
        if (!$this->cState) {
            socket_connect($this->stream, $this->sAddr, $this->sPort);

            try
            {
                $this->_checkErrors();
            }
            catch (\Exception $e)
            {
                throw $e;
            }

            $this->cState = true;
        }
        return true;
    }

    protected function _checkErrors()
    {
        if (socket_last_error($this->stream) != 0) {
            $e = socket_strerror(socket_last_error($this->stream));
            socket_clear_error($this->stream);
            throw new \Exception($e);
        } else {
            return true;
        }
    }

    protected function _query($signal, $SCPM)
    {
        $data = md5($signal) . ':' . $SCPM;
        socket_write($this->stream, $data, strlen($data));

        try
        {
            $this->_checkErrors();
        }
        catch (\Exception $e)
        {
            throw $e;
        }

        return true;
    }

    protected function _answer()
    {
        $result = intval(socket_read($this->stream, 1));

        try
        {
            $this->_checkErrors();
        }
        catch (\Exception $e)
        {
            throw $e;
        }

        return $result;
    }

    function __destroy()
    {
        if ($this->cState) socket_close($this->stream);
    }
}