<?php
namespace MyProject\Models;
use MyProject\Models\Articles\Article;
use MyProject\Services\Db;
abstract class ActiveRecordEntity{
    /**
     * @var int
     */
    protected $id;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    public function __set(string $name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    private function underscoreToCamelCase(string $source): string //On transforme les lignes avec soulignage vers les lignes camelCase(author_id => authorId)
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));

    }
    /**
     * @return static[]
     */
    public static function findAll()
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '`', [], static::class);
    }
    abstract protected static function getTableName(): string;

    /**
     * @param int $id
     * @return static|null
     */
    public static function getOneById(int $id)
    {
        $db = Db::getInstance();
        $entities = $db->query('SELECT * FROM `'. static::getTableName().'` WHERE id = :id', [':id' => $id], static::class);
        return $entities ? $entities[0] : null;
    }
    public function save(): void
    {
       $mappedPropperties = $this->mapPropertiesToDbFormat();
        if($this->id === null){
            $this->insert($mappedPropperties);
        }else{
            $this->update($mappedPropperties);
        }

    }
    private function camelCaseToUnderscore(string $source): string //On transforme les lignes camelCase vers les lignes avec soulignage (authorId => author_id)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }
    private function insert(array $mappedProperties)
    {
        $filtredPropperties = array_filter($mappedProperties);
        $columns = [];
        $paramsNames = [];
        $paramsValues = [];
        foreach ($filtredPropperties as $name => $value) {
            $columns[] = '`' . $name . '`';
            $paramsNames[] = ':' . $name;
            $paramsValues[':' . $name] = $value;
        }
        $strColumns = implode(', ', $columns);
        $strParamsNames = implode(', ', $paramsNames);
        $sql = 'INSERT INTO ' . static::getTableName() .'('. $strColumns . ') VALUES ('. $strParamsNames .')';
        $db = Db::getInstance();
        $db->query($sql, $paramsValues, static::class);
        $this->id = $db->getLastInsertedId();
        $this->refresh();
    }
    private function update(array $mappedProperties)
    {
        $columnsParams = [];
        $paramsValues = [];
        foreach ($mappedProperties as $name => $value)
        {
            $columnsParams[] = "$name = :$name";
            $paramsValues[':'.$name] = $value;
        }
        $db = Db::getInstance();
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columnsParams) . ' WHERE id = '. $this->id;
        $db->query($sql, $paramsValues, static::class);
    }
    protected function mapPropertiesToDbFormat(): array //On utilise la Reflection pour créer un array avec les noms de la propriété comme la clé et la valeur de la propriété commme la valeur. Pour n'importe quel class.

    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();
        $mappedProperties = [];
        foreach ($properties as $property)
        {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }
        return $mappedProperties;
    }
    private function refresh()
    {
        $objectFromDb = static::getOneById($this->id);
        $reflector = new \ReflectionObject($objectFromDb);
        $properties = $reflector->getProperties();
        foreach ($properties as $property)
        {
            $property->setAccessible(true);
            $nameProperty = $property->getName();
            $this->$nameProperty = $property->getValue($objectFromDb);
        }
    }
    public function delete()
    {
        $id = $this->getId();
        $sql = 'DELETE FROM ' . static::getTableName() .' WHERE id = :id';
        $db = Db::getInstance();
        $res = $db->query($sql, [':id' => $id], static::class);
        $this->id = null;
    }
    public static function findOneByColumn(string $columnName, $value)
    {
        $db = Db::getInstance();
        $result = $db->query('SELECT * FROM '. static::getTableName() . ' WHERE ' . $columnName . ' = :' .$columnName, [':'. $columnName => $value], static::class);
        if($result === []){
            return null;
        }else {
            return $result[0];
        }

    }
}
