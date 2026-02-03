<?php

namespace App\Models;

use App\Core\Database;

/**
 * BaseModel
 * 
 * Base class for all models providing common functionality
 */
abstract class BaseModel
{
    protected $db;
    protected $table;
    
    /**
     * Constructor
     * Initialize database connection
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find a record by ID
     */
    public function find($id)
    {
        // TODO: Implement find by ID
    }
    
    /**
     * Get all records
     */
    public function getAll()
    {
        // TODO: Implement get all records
    }
    
    /**
     * Create a new record
     */
    public function create(array $data)
    {
        // TODO: Implement create
    }
    
    /**
     * Update a record
     */
    public function update($id, array $data)
    {
        // TODO: Implement update
    }
    
    /**
     * Delete a record
     */
    public function delete($id)
    {
        // TODO: Implement delete
    }
}
