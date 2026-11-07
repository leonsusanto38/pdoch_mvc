<?php

namespace App\Core;

class Upload
{
  private const DIR      = __DIR__ . '/../../assets/uploads/';
  private const MAX_SIZE = 2 * 1024 * 1024; // 2MB
  private const ALLOWED  = ['jpg', 'jpeg', 'png', 'gif'];

  /**
   * Simpan gambar dari $_FILES[$field] ke assets/uploads.
   * Return nama file baru, atau null kalau user tidak memilih file.
   * Lempar RuntimeException berisi pesan yang siap ditampilkan ke user.
   */
  public static function image(string $field, string $prefix): ?string
  {
    $file = $_FILES[$field] ?? null;
    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
      return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
      throw new \RuntimeException('Upload gagal. Pastikan ukuran file maksimal 2MB.');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, self::ALLOWED, true) || @getimagesize($file['tmp_name']) === false) {
      throw new \RuntimeException('Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.');
    }
    if ($file['size'] > self::MAX_SIZE) {
      throw new \RuntimeException('Ukuran file terlalu besar. Maksimal 2MB.');
    }

    if (!is_dir(self::DIR)) {
      mkdir(self::DIR, 0755, true);
    }

    $newName = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], self::DIR . $newName)) {
      throw new \RuntimeException('Terjadi kesalahan saat memindahkan file.');
    }
    return $newName;
  }

  /** Hapus file lama dari assets/uploads (aman kalau null / file tidak ada). */
  public static function delete(?string $fileName): void
  {
    if ($fileName && is_file(self::DIR . basename($fileName))) {
      unlink(self::DIR . basename($fileName));
    }
  }
}
