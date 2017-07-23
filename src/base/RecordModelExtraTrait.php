<?php

namespace slimExt\base;

use slimExt\helpers\ModelHelper;

/**
 * Class RecordModelExtraTrait
 * @package slimExt\base
 */
trait RecordModelExtraTrait
{
    /**
     * more @see AbstractDriver::insertBatch()
     * @param array $columns
     * @param array $values
     * @return bool|int
     */
    public static function insertBatch(array $columns, array $values)
    {
        if (static::getDb()->supportBatchSave()) {
            return static::getDb()->insertBatch(static::tableName(), $columns, $values);
        }

        throw new \RuntimeException('The driver [' . static::getDb()->getDriver() . '] don\'t support one-time insert multi records.');
    }

    /**
     * insert multiple
     * @param array $dataSet
     * @return array
     */
    public static function insertMulti(array $dataSet)
    {
        $pris = [];

        foreach ($dataSet as $k => $data) {
            $pris[$k] = static::load($data)->insert()->priValue();
        }

        return $pris;
    }

    /**
     * @param array $dataSet
     * @param array $updateColumns
     * @param bool|false $updateNulls
     * @return mixed
     */
    public static function updateMulti(array $dataSet, array $updateColumns = [], $updateNulls = false)
    {
        $res = [];

        foreach ($dataSet as $k => $data) {
            $res[$k] = static::load($data)->update($updateColumns, $updateNulls);
        }

        return $res;
    }

    /**
     * @param $data
     * @param array $conditions
     * @return bool
     */
    public static function updateBatch($data, array $conditions = [])
    {
        return static::getDb()->updateBatch(static::tableName(), $data, $conditions);
    }

    /**
     * @param int|array $priValue
     * @return int
     */
    public static function deleteByPk($priValue)
    {
        // only one
        $where = [static::$priKey => $priValue];

        // many
        if (is_array($priValue)) {
            $where = static::$priKey . ' IN (' . implode(',', $priValue) . ')';
        }

        return self::deleteBy($where);
    }

    /**
     * @param mixed $where
     * @return int
     */
    public static function deleteBy($where)
    {
        $query = ModelHelper::handleConditions($where, static::class)->delete(static::tableName());

        return static::setQuery($query)->execute()->countAffected();
    }

    /**
     * simple count
     * @param $where
     * @return int
     */
    public static function counts($where)
    {
        $query = static::query($where);

        return static::setQuery($query)->count();
    }

    /**
     * @param $where
     * @return int
     */
    public static function exists($where)
    {
        $query = static::query($where);

        return static::setQuery($query)->exists();
    }
}