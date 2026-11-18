<?php

namespace App\Models;

class Profile
{
    // دالة مساعدة لتحديد مكان الملف وإنشائه تلقائياً إذا لم يكن موجوداً
    private static function file()
    {
        $path = storage_path('app/profiles.json');
        
        // إذا لم يكن المجلد أو الملف موجوداً، نقوم بإنشائهما فوراً لتجنب أي أخطاء
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        if (!file_exists($path)) {
            file_put_contents($path, json_encode([]));
        }
        
        return $path;
    }

    // جلب كل البروفايلات
    public static function all(): array
    {
        $content = file_get_contents(self::file());
        return json_decode($content, true) ?? [];
    }

    // البحث عن بروفايل برقم الـ ID
    public static function find(int $id)
    {
        $profiles = self::all();
        foreach ($profiles as $profile) {
            if ($profile['id'] === $id) {
                return $profile;
            }
        }
        return null;
    }

    // إنشاء بروفايل جديد وحفظه في ملف الـ JSON
    public static function create(array $data)
    {
        $profiles = self::all();

        // حساب الـ ID الجديد تلقائياً
        $id = 1;
        if (count($profiles) > 0) {
            $ids = array_column($profiles, 'id');
            $id = max($ids) + 1;
        }

        $newProfile = [
            'id'                  => $id,
            'first_name'          => $data['first_name'] ?? '',
            'last_name'           => $data['last_name'] ?? '',
            'followers'           => $data['followers'] ?? 0,
            'is_account_verified' => $data['is_account_verified'] ?? false,
        ];

        $profiles[] = $newProfile;
        
        // حفظ البيانات في الملف
        file_put_contents(self::file(), json_encode(array_values($profiles), JSON_PRETTY_PRINT));

        return $newProfile;
    }

    // تحديث بروفايل موجود
    public static function update(int $id, array $data)
    {
        $profiles = self::all();
        
        foreach ($profiles as $index => $profile) {
            if ($profile['id'] === $id) {
                $profiles[$index]['first_name']          = $data['first_name'] ?? $profile['first_name'];
                $profiles[$index]['last_name']           = $data['last_name'] ?? $profile['last_name'];
                $profiles[$index]['followers']           = $data['followers'] ?? $profile['followers'];
                $profiles[$index]['is_account_verified'] = $data['is_account_verified'] ?? $profile['is_account_verified'];

                file_put_contents(self::file(), json_encode(array_values($profiles), JSON_PRETTY_PRINT));
                
                return $profiles[$index];
            }
        }

        return null;
    }

    // حذف بروفايل
    public static function delete(int $id): bool
    {
        $profiles = self::all();
        $filtered = [];
        $found = false;

        foreach ($profiles as $profile) {
            if ($profile['id'] === $id) {
                $found = true;
            } else {
                $filtered[] = $profile;
            }
        }

        if ($found) {
            file_put_contents(self::file(), json_encode(array_values($filtered), JSON_PRETTY_PRINT));
            return true;
        }

        return false;
    }
}