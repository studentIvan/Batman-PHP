<?php
namespace Framework\Packages\UserAuth\Drivers;

use \Framework\Packages\UserAuth\AbstractUserAuthDriver,
    \Framework\Packages\UserAuth\Exceptions\AuthException,
    \Framework\Common\Database,
    \Framework\Common\SQLBuilder,
    \Framework\Common\Cookies,
    \Framework\Common\Security;

/**
 * Masha UserAuth Driver
 *
 * + sessions storage in database
 * + lazy cookies authentication
 * + perfect security
 *
 * DB session data saved into <SpecialData> (methods: setSpecialData, getSpecialData)
 * Masha need to be setConfigure(array(KEY => VALUE...)):
 *
 * CONFIGURATION_KEY    DESCRIPTION
 * usersTable           users table name
 * passwordColumn       password column name in users table
 * uidColumn            uid column name in users table
 * emailColumn          login column name (email for example, recommended) in users table
 * sessionsTable        sessions table name (see sessions migration scheme)
 *
 * Cookies composition:
 * i - unique user ID
 * h - session hash (identify)
 * p - password token for lazy authentication
 * c - digital signature
 *
 */
class Masha extends AbstractUserAuthDriver
{
    protected $mashaData = array(),
        $usersTable, $passwordColumn, $uidColumn, $emailColumn, $sessionsTable;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    protected function procedure()
    {
        return new SQLBuilder($this->db);
    }

    protected function start()
    {
        $this->db = Database::getInstance();
        list($userId, $sessionHash, $userPass, $controlSum) = Cookies::get(array('i', 'h', 'p', 'c'));
        $realControlSum = Security::getArrayHash(array($userId, $sessionHash, $userPass));
        if ($controlSum !== $realControlSum)
        {
            return $this->_newSession();
        }
        else
        {
            $sql = $this->procedure()
                ->select(array('s.auth_user_id', 's.special'))
                ->from($this->sessionsTable, 's')
                ->where("identify = '$sessionHash'");

            $result = $this->db->query($sql);
            if ($row = $result->fetch(\PDO::FETCH_NUM))
            {
                $this->mashaData = isset($row[1]) ?
                    unserialize($row[1]) : false;
                if (false === $this->mashaData || null === $this->mashaData)
                    return $this->_newSession();
                $_uid = isset($row[0]) ? $row[0] : false;
                $this->setData('uid', $_uid);
                $this->setData('auth', ((bool) ($_uid)));
                $this->setData('hash', ($sessionHash));
                return true;
            }
            else
            {
                return $this->_newSession($sessionHash,
                    (($userId == '!#!') ? false : $userId),
                    (($userPass == '!#!') ? false : $userPass)
                );
            }
        }
    }

    protected function _newSession($rHash = false, $rId = false, $rPass = false)
    {
        $_ip = ip2long($_SERVER['REMOTE_ADDR']);
        $_ua = isset($_SERVER['HTTP_USER_AGENT']) ?
            substr(htmlspecialchars($_SERVER['HTTP_USER_AGENT']), 0, 200) : '';
        if ($rHash === false)
        {
            $hash = Security::getHash($_ua . $_ip . rand(1, 9999));
            $this->setData('hash', $hash);
            $affected = $this->db->insert($this->sessionsTable, array(
                'identify' => $hash,
                'client' => $_ip,
                'starttime' => date('Y-m-d H:i:sP'),
                'agent' => $_ua,
                'special' => 'a:0:{}',
            ));

            $in_memory = ($affected > 0);
            $this->setData('in_memory', $in_memory);
            if ($in_memory !== false)
            {
                Cookies::set(array(
                    'i' => '!#!',
                    'h' => $hash,
                    'p' => '!#!',
                    'c' => Security::getArrayHash(array('', $hash, '')),
                ));

                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            $hash = $rHash;
            $this->setData('hash', $hash);
            if ($rId === false)
            {
                $affected = $this->db->insert($this->sessionsTable, array(
                    'identify' => $hash,
                    'client' => $_ip,
                    'starttime' => date('Y-m-d H:i:sP'),
                    'agent' => $_ua,
                    'special' => 'a:0:{}',
                ));

                $in_memory = ($affected > 0);
                $this->setData('in_memory', $in_memory);
                if ($in_memory !== false)
                {
                    Cookies::set(array(
                        'i' => '!#!',
                        'h' => $hash,
                        'p' => '!#!',
                        'c' => Security::getArrayHash(array('', $hash, '')),
                    ));

                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $sql = $this->procedure()
                    ->select('u.' . $this->passwordColumn)
                    ->from($this->usersTable, 'u')
                    ->where("{$this->uidColumn} = '$rId'");

                $row = $this->db->query($sql)->fetch(\PDO::FETCH_NUM);
                $userPassword = isset($row[0]) ? $row[0] : false;
                if ($userPassword == $rPass)
                {
                    $this->setData('auth', true);
                    $this->setData('uid', $rId);

                    $affected = $this->db->insert($this->sessionsTable, array(
                        'identify' => $hash,
                        'client' => $_ip,
                        'starttime' => date('Y-m-d H:i:sP'),
                        'agent' => $_ua,
                        'special' => 'a:0:{}',
                        'auth_user_id' => $rId,
                    ));

                    $in_memory = ($affected > 0);
                    $this->setData('in_memory', $in_memory);
                    return $in_memory;
                }
                else
                {
                    $hash = Security::getHash($_ua . $_ip . rand(1, 9999));
                    $this->setData('hash', $hash);
                    $affected = $this->db->insert($this->sessionsTable, array(
                        'identify' => $hash,
                        'client' => $_ip,
                        'starttime' => date('Y-m-d H:i:sP'),
                        'agent' => $_ua,
                        'special' => 'a:0:{}',
                    ));

                    $in_memory = ($affected > 0);
                    $this->setData('in_memory', $in_memory);
                    if ($in_memory !== false)
                    {
                        Cookies::set(array(
                            'i' => '!#!',
                            'h' => $hash,
                            'p' => '!#!',
                            'c' => Security::getArrayHash(array('', $hash, '')),
                        ));
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
            }
        }
    }

    public function setData($key, $value)
    {
        if ($key == 'config')
        {
            $this->emailColumn = $value['emailColumn'];
            $this->passwordColumn = $value['passwordColumn'];
            $this->uidColumn = $value['uidColumn'];
            $this->usersTable = $value['usersTable'];
            $this->sessionsTable = $value['sessionsTable'];

            if (!$this->start())
                throw new AuthException('Failed start session', 4);
        }

        parent::setData($key, $value);
    }

    public function auth($email, $password)
    {
        if (!$this->getData('auth'))
        {
            $phash = Security::getHash($password);
            $sql = $this->procedure()
                ->select(array('u.' . $this->passwordColumn, 'u.' . $this->uidColumn))
                ->from($this->usersTable, 'u')
                ->where("{$this->emailColumn} = '$email'");

            $row = $this->db->query($sql)->fetch(\PDO::FETCH_NUM);

            if ($row)
            {
                if (isset($row[0]))
                {
                    if ($row[0] == $phash)
                    {
                        $userId = (int) $row[1];
                        $this->db->update($this->sessionsTable, array(
                            'auth_user_id' => $userId,
                            'starttime' => date('Y-m-d H:i:sP'),
                        ), array('identify' => $this->getData('hash')));

                        Cookies::set(array(
                            'i' => $userId,
                            'h' => $this->getData('hash'),
                            'p' => $phash,
                            'c' => Security::getArrayHash(array(
                                $userId, $this->getData('hash'), $phash
                            )),
                        ));

                        $this->setData('uid', $userId);
                    }
                    else
                    {
                        throw new AuthException('Wrong password', 3);
                    }
                }
                else
                {
                    throw new AuthException('Authorization failed', 3);
                }
            }
            else
            {
                throw new AuthException('Authorization failed', 3);
            }
        }
        else
        {
            throw new AuthException('User is already logged in', 1);
        }
    }

    public function getSpecialData($name) {
        return isset($this->mashaData[$name]) ? $this->mashaData[$name] : null;
    }

    public function setSpecialData($name, $value) {
        $this->mashaData[$name] = $value;
    }

    protected function finish()
    {
        if ($this->getData('in_memory'))
        {
            $this->db->update($this->sessionsTable, array(
                'special' => (count($this->mashaData) == 0) ?
                    'a:0:{}' : serialize($this->mashaData),
                'starttime' => date('Y-m-d H:i:sP'),
            ), array('identify' => $this->getData('hash')));
        }
    }

    public function out()
    {
        parent::out();
        $this->mashaData = array();
        $this->setData('uid', false);
        $this->setData('in_memory', false);
        $this->setData('is_auth', false);
        $this->db->delete($this->sessionsTable,
            array('identify' => $this->getData('hash')));
        $this->setData('hash', '');
        Cookies::set(array('i' => '', 'h' => '', 'p' => '', 'c' => ''));
    }

    function __destruct() {
        $this->finish();
    }
}
