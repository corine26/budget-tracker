<?php

namespace Database\Seeders;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LabAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $studentPassword = env('LAB_STUDENT_PASSWORD');
        $adminPassword = env('LAB_ADMIN_PASSWORD');

        if (! $studentPassword || ! $adminPassword) {
            $this->command->error('Set LAB_STUDENT_PASSWORD and LAB_ADMIN_PASSWORD in .env first.');
            return;
        }

        $a = $this->account('Juan Dela Cruz', 'student.a@example.com', $studentPassword, 'student');
        $b = $this->account('Maria Santos', 'student.b@example.com', $studentPassword, 'student');
        $this->account('Budget Admin', 'admin@example.com', $adminPassword, 'admin');

        // Link existing Lab 2 records to owners (nothing is deleted)
        ServiceRequest::where('requester_email', 'juan.delacruz@example.com')->update(['user_id' => $a->id]);
        ServiceRequest::where('requester_email', 'maria.santos@example.com')->update(['user_id' => $b->id]);
        ServiceRequest::where('requester_email', 'pedro.reyes@example.com')->update(['user_id' => $a->id]);
    }

    private function account(string $name, string $email, string $password, string $role): User
    {
        $user = User::firstOrNew(['email' => $email]);
        $user->name = $name;
        $user->password = Hash::make($password);
        $user->role = $role; // trusted setup only
        $user->save();

        return $user;
    }
}