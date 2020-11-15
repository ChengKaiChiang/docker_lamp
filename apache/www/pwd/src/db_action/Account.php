<?php

define("_HASH_COST", 12);

class Account extends DBHelp
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function querySQL($sql)
    {
        return parent::querySQL($sql);
    }

    public function createAccount($acc, $pwd)
    {
        if ($this->readAccount($acc)) {
            return 'Account has been registered';
        } else {
            $hashAlgorithm = PASSWORD_DEFAULT;
            $hashArray = ['cost' => _HASH_COST];
            $rehash = password_needs_rehash($pwd, $hashAlgorithm, $hashArray);
            if ($rehash) {
                $newHash = password_hash($pwd, $hashAlgorithm, $hashArray);
                $sql = "INSERT INTO Operator(Operator_ID, Operator_Pwd) VALUES (:AID,:PWD)";
                $res = $this->querySQL($sql);
                $res->bindParam(':AID', $acc, PDO::PARAM_STR);
                $res->bindParam(':PWD', $newHash, PDO::PARAM_STR);
                $res->execute();
            }

            return true;
        }
    }

    public function readAccount($acc_id)
    {
        $sql = "SELECT Operator_ID, Operator_Pwd as Pwd FROM Operator WHERE Operator_ID=:AID";
        $res = $this->querySQL($sql);
        $res->bindParam(':AID', $acc_id, PDO::PARAM_STR);
        $res->execute();

        if ($res->rowCount() > 0) {
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function updateAccountPwd($acc, $pwd)
    {
        $account = $this->readAccount($acc);
        if ($account) {
            $hashAlgorithm = PASSWORD_DEFAULT;
            $hashArray = ['cost' => _HASH_COST];
            $rehash = password_needs_rehash($pwd, $hashAlgorithm, $hashArray);
            if ($rehash) {
                $newHash = password_hash($pwd, $hashAlgorithm, $hashArray);
                $sql = "UPDATE Operator SET Operator_Pwd=:PWD WHERE Operator_ID=:AID";
                $res = $this->querySQL($sql);
                $res->bindParam(':AID', $acc, PDO::PARAM_STR);
                $res->bindParam(':PWD', $newHash, PDO::PARAM_STR);
                $res->execute();
            }

            return true;
        } else {
            return 'No such account';
        }
    }

    // public function delAccount($acc)
    // {
    //     if ($this->readAccount($acc)) {
    //         $sql = "DELETE FROM Users WHERE ID = :AID";
    //         $res = $this->querySQL($sql);
    //         $res->bindParam(':AID', $acc, PDO::PARAM_STR);
    //         $res->execute();

    //         return true;
    //     } else {
    //         return 'No such account';
    //     }
    // }

    // private function updateAccountToken($acc)
    // {
    //     $sql = "UPDATE Users SET Token=:TOKEN WHERE ID=:AID";
    //     $res = $this->querySQL($sql);
    //     $res->bindParam(':AID', $acc, PDO::PARAM_STR);
    //     $res->bindParam(':TOKEN', $_COOKIE['PHPSESSID'], PDO::PARAM_STR);
    //     $res->execute();
    // }

    public function loginAccount($acc, $pwd)
    {
        $account = $this->readAccount($acc);
        if ($account) {
            foreach ($account as $v) {
                if (password_verify($pwd, $v['Pwd'])) {
                    //$this->updateAccountToken($acc);
                    $this->updateAccountPwd($acc, $pwd);

                    // $_SESSION['_uid'] = $acc;
                    // setcookie('uname', base64_encode($acc), 0, '/');

                    return 'login suceesfully';
                } else {
                    return 'account or password incorrect';
                }
            }
        } else {
            return 'No such account';
        }
    }
}