<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['key', 'value'];

    /**
     * Get the current active session ID
     * 
     * @return int|null The current session ID or null if not found
     */
    public function getCurrentSession()
    {
        try {
            $db = \Config\Database::connect('second_db');
            $builder = $db->table('settings');
            $builder->where('key', 'session_id');
            $query = $builder->get();
            
            if ($query->getNumRows() > 0) {
                $row = $query->getRow();
                return $row->value;
            }
            
            // Default to 1 if no session is found
            return 1;
        } catch (\Exception $e) {
            log_message('error', 'Error getting current session: ' . $e->getMessage());
            // Default to 1 if there's an error
            return 1;
        }
    }
} 