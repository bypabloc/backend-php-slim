<?php

namespace App\Services;

use MongoDB\Client as MongoClient;
use MongoDB\BSON\toPHP as toPHP;

use App\Services\Logger;

class MongoModel
{
    private static $connection;
    private static $collection;

    private static $class_instance;
    private static $primaryKey = '_id';

    public static $data = [];

    private static $fields_general = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    private static $fields_search = [];

    public function __construct()
    {
        $connection = new MongoClient(getenv('MONGO_URI'));
        $connection = $connection->{getenv('MONGO_DB')};
        self::$connection = $connection;
    }

    private static function init_called_class() : void
    {
        $class = get_called_class();
        $instance = new $class();

        self::$class_instance = $instance;

        if(isset(self::$class_instance->primaryKey)){
            self::$primaryKey = self::$class_instance->primaryKey;
        }

        self::$collection = $instance->collection;
    }

    private static function creating_this() : void
    {
        $instance = self::$class_instance;

        $instance->data['created_at'] = date('Y-m-d H:i:s');

        if (method_exists($instance, 'creating')) {
            $instance->creating();
        }
    }
    private static function created_this() : void
    {
        $instance = self::$class_instance;
        if (method_exists($instance, 'created')) {
            $instance->created();
        }
    }

    private static function updating_this() : void
    {
        $instance = self::$class_instance;

        $instance->data['updated_at'] = date('Y-m-d H:i:s');

        if (method_exists($instance, 'updating')) {
            $instance->updating();
        }
    }
    private static function updated_this() : void
    {
        $instance = self::$class_instance;
        if (method_exists($instance, 'updated')) {
            $instance->updated();
        }
    }

    private static function deleting_this() : void
    {
        $instance = self::$class_instance;

        $instance->data['deleted_at'] = date('Y-m-d H:i:s');

        if (method_exists($instance, 'deleting')) {
            $instance->deleting();
        }
    }
    private static function deleted_this() : void
    {
        $instance = self::$class_instance;
        if (method_exists($instance, 'deleted')) {
            $instance->deleted();
        }
    }

    public static function setData(array $data = []) : void
    {
        $instance = self::$class_instance;
        $newData = [];
        foreach ($instance->fields as $value) {
            $newData[$value] = $data[$value];
        }
        foreach (self::$fields_general as $value) {
            if (isset($data[$value])) {
                $newData[$value] = $data[$value];
            }
        }

        if (isset($data[self::$primaryKey])) {
            $instance->{self::$primaryKey} = $data[self::$primaryKey];
        }
        
        $instance->dataOld = $instance->data;

        if($instance->dataOld){
            foreach ($instance->dataOld as $key => $value) {
                if( !$newData[$key] ){
                    $newData[$key] = $value;
                }
            }
        }
        $instance->data = $newData;
    }

    public static function getData() : array
    {
        $instance = self::$class_instance;

        foreach ($instance->fields as $value) {
            if (isset($instance->data[$value])) {
                self::$data[$value] = $instance->data[$value];
            }
        }

        foreach (self::$fields_general as $value) {
            if (isset($instance->data[$value])) {
                self::$data[$value] = $instance->data[$value];
            }
        }

        return self::$data;
    }

    public static function create(array $data)
    {
        self::init_called_class();

        self::setData($data);
        
        self::creating_this();

        try {
            $instance = self::$class_instance;
            // $newItem = self::$connection->{self::$collection}->insertOne($instance->data);
            print_r(self::$connection->listCollections());
            
            $newItem = self::$connection->selectCollection(self::$collection)->insertOne($instance->data);

            // self::$connection->{self::$collection}->createIndex(['token' => 1]);
            // self::$connection->{self::$collection}->createIndex(['user_id' => 1]);
            // self::$connection->{self::$collection}->createIndex(['token' => 1]);
            // self::$connection->{self::$collection}->createIndex(['token' => 1]);

            if(self::$primaryKey === '_id'){
                $instance->_id = $newItem->getInsertedId();
            }
            Logger::info(
                message: 'Created new item in collection: ' . self::$collection . ' with data: ' . json_encode($instance->data)
            );
        } catch (\Throwable $th) {
            Logger::error(
                message: [
                    'message' => $th->getMessage(),
                    'file' => $th->getFile(),
                    'line' => $th->getLine(),
                ],
            );
        }

        self::created_this();

        return self::ToClass();
    }

    public static function update(array $data)
    {
        self::setData($data);

        $instance = self::$class_instance;

        self::updating_this();

        self::$connection->{self::$collection}->updateOne(
            [self::$primaryKey => $instance->{self::$primaryKey}],
            ['$set' => $instance->data]
        );

        self::updated_this();

        return self::ToClass();
    }

    public static function delete()
    {
        self::deleting_this();

        $instance = self::$class_instance;

        if($instance->list){
            // $newValues = [];
            // foreach ($instance->list as $key => $value) {
            //     $value->data['deleted_at'] = date('Y-m-d H:i:s');
            //     $newValues[] = $value->data;
            // }
            self::$connection->{self::$collection}->updateMany(
                self::$fields_search,
                ['$set' => [
                    'deleted_at' => date('Y-m-d H:i:s'),
                ]],
            );
        }else{
            self::$connection->{self::$collection}->updateOne(
                [self::$primaryKey => $instance->{self::$primaryKey}],
                ['$set' => $instance->data]
            );
        }

        self::deleted_this();
    }

    public static function findByPk(string $value)
    {
        self::init_called_class();
        
        $items = self::$connection->{self::$collection}->find([self::$primaryKey => $value])->toArray();

        $result = null;
        if (count($items) > 0 ) {
            // https://stackoverflow.com/a/44651055/7100847
            self::setData( (array) $items[0]->jsonSerialize() );
            $result = self::ToClass();
        }

        return $result;
    }

    public static function findMany(array $data)
    {
        self::init_called_class();

        self::setData($data);
        $data = self::getData();

        $items = self::$connection->{self::$collection}->find($data)->toArray();

        $result = null;
        if (count($items) > 0 ) {
            self::$fields_search = $data;
            $result = [];
            foreach ($items as $item) {
                $result[] = (array) $item->jsonSerialize();
            }
            $result = self::ToClassList( $result );
        }else{
            $result = self::ToClassList( [] );
        }

        return $result;
    }

    public static function isDeleted() : bool
    {
        $instance = self::$class_instance;

        return isset($instance->data['deleted_at']);
    }

    private static function ToClass() : mixed
    {
        $data = self::getData();

        $instance = self::$class_instance;

        foreach ($data as $key => $value) {
            $instance->$key = $value;
        }

        if(isset($instance->validations)){
            foreach ($instance->validations as $key => $value) {
                if($value['type'] === 'datetime'){
                    if (! $instance->$key instanceof \DateTime) {
                        if($data[$key]){
                            $instance->$key = new \DateTime($data[$key]);
                        }
                    }
                }
            }
        }

        return $instance;
    }

    private static function ToClassList(array $data) : mixed
    {
        $instance = self::$class_instance;

        $result = [];
        foreach ($data as $key => $value) {
            self::setData( $value );
            $result[] = self::ToClass();
        }

        $instance->{'list'} = $result;

        return $instance;
    }
}