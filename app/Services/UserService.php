<?php

namespace App\Services;

/**
 * UserService
 * 
 * Service for managing users with mock data
 */
class UserService extends MockDataService
{
    public function __construct()
    {
        $this->dataKey = 'users';
        $this->autoIncrementKey = 'users_id';
        parent::__construct();
        $this->seedDefaultUsers();
    }

    private function seedDefaultUsers()
    {
        if (empty($_SESSION[$this->dataKey])) {
            // Admin user
            $this->create([
                'name' => 'Admin User',
                'email' => 'admin@blog.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'verified' => true,
                'avatar' => 'https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff'
            ]);

            // Regular users
            $this->create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'user',
                'verified' => true,
                'avatar' => 'https://ui-avatars.com/api/?name=John+Doe&background=random'
            ]);

            $this->create([
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'user',
                'verified' => true,
                'avatar' => 'https://ui-avatars.com/api/?name=Jane+Smith&background=random'
            ]);
        }
    }

    public function findByEmail($email)
    {
        $users = $this->where('email', $email);
        return !empty($users) ? reset($users) : null;
    }

    public function verifyPassword($user, $password)
    {
        return password_verify($password, $user['password']);
    }

    public function updatePassword($id, $newPassword)
    {
        return $this->update($id, ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);
    }

    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'admin';
    }
}
