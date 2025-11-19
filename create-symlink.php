<?php

// Path absolut
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

echo "Target: $target\n";
echo "Link: $link\n";
echo "---\n";

// Cek apakah target ada
if (!file_exists($target)) {
    echo "❌ Error: Target folder tidak ditemukan!\n";
    echo "Path: $target\n";
    exit;
}

// Hapus link lama jika ada
if (file_exists($link)) {
    if (is_link($link)) {
        unlink($link);
        echo "🗑️ Link lama dihapus.\n";
    } else {
        echo "❌ Error: '$link' sudah ada dan bukan symbolic link!\n";
        echo "Hapus folder/file 'public/storage' secara manual terlebih dahulu.\n";
        exit;
    }
}

// Buat symbolic link
if (symlink($target, $link)) {
    echo "✅ Symbolic link berhasil dibuat!\n";
    echo "Dari: $link\n";
    echo "Ke: $target\n";
} else {
    echo "❌ Gagal membuat symbolic link.\n";
    echo "Kemungkinan fungsi symlink() dinonaktifkan di server.\n";
}