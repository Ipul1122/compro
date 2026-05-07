<?php

/**
 * Proxy index.php — Laravel Entry Point
 * 
 * File ini diperlukan jika Document Root hosting mengarah ke root project
 * Laravel, bukan ke subfolder /public. File ini akan meneruskan semua
 * request ke public/index.php.
 * 
 * CATATAN: Jika Anda bisa mengubah Document Root di cPanel ke subfolder
 * /public, hapus file ini dan lakukan itu sebagai gantinya (lebih aman).
 */

// Redirect semua request ke folder public
require __DIR__.'/public/index.php';
