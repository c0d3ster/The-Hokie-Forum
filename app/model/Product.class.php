<?php

class Product extends DbObject {
    // name of database table
    const DB_TABLE = 'product';

    // database fields
    protected $id;
    protected $title;
    protected $category;
    protected $description;
    protected $sizes;
    protected $price;
    protected $image_url;
    protected $creator_id;

    // constructor
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'title' => '',
            'category' => '',
            'description' => null,
            'price' => 0,
            'sizes' => '',
            'image_url' => null,
            'creator_id' => 2,
            'date_created' => null
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->title = $args['title'];
        $this->category = $args['category'];
        $this->description = $args['description'];
        $this->price = $args['price'];
        $this->sizes = $args['sizes'];
        $this->image_url = $args['image_url'];
        $this->creator_id = $args['creator_id'];
        $this->date_created = $args['date_created'];
    }

    // save changes to object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'title' => $this->title,
            'category' => $this->category,
            'description' => $this->description,
            'price' => $this->price,
            'sizes' => $this->sizes,
            'image_url' => $this->image_url,
            'creator_id' => $this->creator_id
            );
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    // load all products
    public static function getAllProducts($limit=null) {
        $query = sprintf(" SELECT id FROM %s ORDER BY date_created DESC ",
            self::DB_TABLE
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return ($objects);
        }
    }

}
