<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\UploadedFile;

#[Fillable(['name', 'surname', 'email', 'password', 'klub', 'profilDescription', 'lastLogin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'lastLogin' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAvatarDirectory(): string
    {
        return public_path('users/'.$this->id);
    }

    public function getAvatarUrl(): ?string
    {
        $directory = $this->getAvatarDirectory();

        if (!is_dir($directory)) {
            return null;
        }

        foreach (['jpg', 'jpeg', 'png', 'webp', 'avif', 'gif'] as $extension) {
            if (file_exists($directory.'/avatar.'.$extension)) {
                return asset('users/'.$this->id.'/avatar.'.$extension);
            }
        }

        return null;
    }

    public function saveAvatar(UploadedFile $file): string
    {
        $directory = $this->getAvatarDirectory();

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $this->deleteAvatar();

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = 'avatar.'.$extension;

        $file->move($directory, $filename);

        return asset('users/'.$this->id.'/'.$filename);
    }

    public function deleteAvatar(): void
    {
        $directory = $this->getAvatarDirectory();

        if (!is_dir($directory)) {
            return;
        }

        foreach (glob($directory.'/avatar.*') ?: [] as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
