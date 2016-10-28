<?php

class User extends DbObject {
    // name of database table
    const DB_TABLE = 'users';

    // database fields
    protected $id;
    protected $username;
    protected $password;
    protected $email;
    protected $admin;

    // constructor
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'username' => '',
            'password' => '',
            'email' => null,
            'admin' => 0
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->username = $args['username'];
        $this->password = $args['password'];
        $this->email = $args['email'];
        $this->admin = $args['admin'];
    }

    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'username' => $this->username,
            'password' => $this->password,
            'email' => $this->email,
            'admin' => $this->admin
            );
        $db->store($this, self::DB_TABLE, $db_properties);
    }

    // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    // load user by username
    public static function loadByUsername($username=null) {
        if($username == null)
            return null;

        $query = sprintf(" SELECT id FROM %s WHERE 'username' = '%s' ",
            self::DB_TABLE,
            $username
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $row = mysql_fetch_assoc($result);
            $obj = self::loadById($row['id']);
            return ($obj);
        }
    }


    //validate user information
    public static function loadByUsernameAndPassword($username=null, $password=null) {
        if($username == null || $password == null) {
            return null;
        }
        $query = sprintf(" SELECT id FROM %s WHERE 'username' = '%s' AND 'password' = '%s' ",
            self::DB_TABLE,
            $username,
            $password
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $row = mysql_fetch_assoc($result);
            $obj = self::loadById($row['id']);
            return ($obj);
        }
    }
}
