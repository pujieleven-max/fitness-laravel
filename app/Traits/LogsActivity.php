<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait LogsActivity
{
    /**
     * Flag to temporarily disable activity logging (e.g. during batch seeders).
     */
    public static bool $activityLoggingEnabled = true;

    /**
     * Boot the LogsActivity trait for the model.
     */
    public static function bootLogsActivity(): void
    {
        static::created(function (Model $model) {
            if (! static::$activityLoggingEnabled) {
                return;
            }

            try {
                $user = Auth::user();
                $module = $model->getModuleForActivity();
                $identifier = $model->getIdentifierForActivity();

                $attributes = $model->filterActivityAttributes($model->getAttributes());

                ActivityLog::create([
                    'user_id' => $user?->id,
                    'user_name' => $user?->name ?? 'Publik / Tamu',
                    'user_role' => $user?->role ?? 'guest',
                    'action' => 'INSERT',
                    'module' => $module,
                    'subject_type' => get_class($model),
                    'subject_id' => (string) $model->getKey(),
                    'description' => "Menambahkan {$module} baru: {$identifier}",
                    'old_values' => null,
                    'new_values' => $attributes,
                    'ip_address' => request()?->ip(),
                    'user_agent' => request() ? Str::limit(request()->userAgent(), 250) : null,
                ]);
            } catch (\Throwable $e) {
                // Silently log error or continue to avoid breaking business operations
                logger()->error('LogsActivity Created Error: ' . $e->getMessage());
            }
        });

        static::updated(function (Model $model) {
            if (! static::$activityLoggingEnabled) {
                return;
            }

            try {
                $dirty = $model->getDirty();
                $ignored = ['updated_at', 'password', 'remember_token'];
                foreach ($ignored as $key) {
                    unset($dirty[$key]);
                }

                if (empty($dirty)) {
                    return;
                }

                $user = Auth::user();
                $module = $model->getModuleForActivity();
                $identifier = $model->getIdentifierForActivity();

                $oldValues = [];
                $newValues = [];
                $original = $model->getOriginal();

                foreach (array_keys($dirty) as $key) {
                    $oldValues[$key] = $original[$key] ?? null;
                    $newValues[$key] = $model->getAttribute($key);
                }

                // Friendly changed fields list
                $changedLabels = array_map(function ($col) {
                    return ActivityLog::fieldLabel($col);
                }, array_keys($dirty));
                $changedStr = implode(', ', array_slice($changedLabels, 0, 4));
                if (count($changedLabels) > 4) {
                    $changedStr .= ', dan ' . (count($changedLabels) - 4) . ' lainnya';
                }

                ActivityLog::create([
                    'user_id' => $user?->id,
                    'user_name' => $user?->name ?? 'Publik / Sistem',
                    'user_role' => $user?->role ?? 'guest',
                    'action' => 'UPDATE',
                    'module' => $module,
                    'subject_type' => get_class($model),
                    'subject_id' => (string) $model->getKey(),
                    'description' => "Memperbarui {$module}: {$identifier} (Perubahan: {$changedStr})",
                    'old_values' => $oldValues,
                    'new_values' => $newValues,
                    'ip_address' => request()?->ip(),
                    'user_agent' => request() ? Str::limit(request()->userAgent(), 250) : null,
                ]);
            } catch (\Throwable $e) {
                logger()->error('LogsActivity Updated Error: ' . $e->getMessage());
            }
        });

        static::deleted(function (Model $model) {
            if (! static::$activityLoggingEnabled) {
                return;
            }

            try {
                $user = Auth::user();
                $module = $model->getModuleForActivity();
                $identifier = $model->getIdentifierForActivity();

                $attributes = $model->filterActivityAttributes($model->getOriginal());

                ActivityLog::create([
                    'user_id' => $user?->id,
                    'user_name' => $user?->name ?? 'Publik / Sistem',
                    'user_role' => $user?->role ?? 'guest',
                    'action' => 'DELETE',
                    'module' => $module,
                    'subject_type' => get_class($model),
                    'subject_id' => (string) $model->getKey(),
                    'description' => "Menghapus {$module}: {$identifier}",
                    'old_values' => $attributes,
                    'new_values' => null,
                    'ip_address' => request()?->ip(),
                    'user_agent' => request() ? Str::limit(request()->userAgent(), 250) : null,
                ]);
            } catch (\Throwable $e) {
                logger()->error('LogsActivity Deleted Error: ' . $e->getMessage());
            }
        });
    }

    /**
     * Get module label for this model.
     */
    public function getModuleForActivity(): string
    {
        $class = class_basename($this);

        return match ($class) {
            'Supplement' => 'Suplemen',
            'Member' => 'Member',
            'GymPackage' => 'Paket Gym',
            'Order' => 'Transaksi POS',
            'OrderItem' => 'Item Transaksi',
            'Booking' => 'Booking',
            'Trainer' => 'Trainer',
            'FitnessClass' => 'Kelas Gym',
            'User' => 'Pengguna',
            'Setting' => 'Pengaturan',
            'Category' => 'Kategori',
            'StockMutation' => 'Mutasi Stok',
            default => $class,
        };
    }

    /**
     * Get identifiable name or label for activity description.
     */
    public function getIdentifierForActivity(): string
    {
        if (! empty($this->invoice_no)) {
            return '#' . $this->invoice_no . (! empty($this->customer_name) ? " ({$this->customer_name})" : '');
        }

        if (! empty($this->booking_code)) {
            return '#' . $this->booking_code . (! empty($this->customer_name) ? " ({$this->customer_name})" : '');
        }

        if (! empty($this->member_code)) {
            return "{$this->name} [{$this->member_code}]";
        }

        if (! empty($this->sku)) {
            return "{$this->name} [{$this->sku}]";
        }

        if (! empty($this->code)) {
            return "{$this->name} [{$this->code}]";
        }

        if (! empty($this->name)) {
            return $this->name;
        }

        if (! empty($this->app_name)) {
            return $this->app_name;
        }

        return '#' . $this->getKey();
    }

    /**
     * Filter out password, token, or oversized columns.
     */
    public function filterActivityAttributes(array $attributes): array
    {
        $ignored = ['password', 'remember_token', 'updated_at', 'created_at'];
        foreach ($ignored as $key) {
            unset($attributes[$key]);
        }

        return $attributes;
    }

    /**
     * Run a callback without logging activities.
     */
    public static function withoutActivityLog(callable $callback): mixed
    {
        $previous = static::$activityLoggingEnabled;
        static::$activityLoggingEnabled = false;

        try {
            return $callback();
        } finally {
            static::$activityLoggingEnabled = $previous;
        }
    }
}

