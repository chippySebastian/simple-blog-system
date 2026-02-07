<?php

namespace App\Services;

/**
 * MockDataService
 * 
 * Base service for managing mock data in sessions
 */
abstract class MockDataService
{
    protected $dataKey;
    protected $autoIncrementKey;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION[$this->dataKey])) {
            $_SESSION[$this->dataKey] = [];
        }
        
        if (!isset($_SESSION[$this->autoIncrementKey])) {
            $_SESSION[$this->autoIncrementKey] = 1;
        }
    }

    protected function getNextId()
    {
        return $_SESSION[$this->autoIncrementKey]++;
    }

    public function all()
    {
        return $_SESSION[$this->dataKey];
    }

    public function find($id)
    {
        return $_SESSION[$this->dataKey][$id] ?? null;
    }

    public function create($data)
    {
        $id = $this->getNextId();
        $data['id'] = $id;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $_SESSION[$this->dataKey][$id] = $data;
        return $data;
    }

    public function update($id, $data)
    {
        if (isset($_SESSION[$this->dataKey][$id])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $_SESSION[$this->dataKey][$id] = array_merge($_SESSION[$this->dataKey][$id], $data);
            return $_SESSION[$this->dataKey][$id];
        }
        return null;
    }

    public function delete($id)
    {
        if (isset($_SESSION[$this->dataKey][$id])) {
            unset($_SESSION[$this->dataKey][$id]);
            return true;
        }
        return false;
    }

    public function where($field, $value)
    {
        return array_filter($_SESSION[$this->dataKey], function ($item) use ($field, $value) {
            return isset($item[$field]) && $item[$field] == $value;
        });
    }

    public function search($fields, $query)
    {
        return array_filter($_SESSION[$this->dataKey], function ($item) use ($fields, $query) {
            foreach ($fields as $field) {
                if (isset($item[$field]) && stripos($item[$field], $query) !== false) {
                    return true;
                }
            }
            return false;
        });
    }
}
