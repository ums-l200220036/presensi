<?php
// config/attendance.php

// Helper function to generate a range of IPs for a /24 subnet
function generateIpRange($baseIp, $start, $end) {
    $ips = [];
    for ($i = $start; $i <= $end; $i++) {
        $ips[] = $baseIp . '.' . $i;
    }
    return $ips;
}

// Basis IP untuk segmen 192.168.60.x
$baseIp = '192.168.60';

return [
    'allowed_local_ips' => array_merge(
        [
            // IP Gateway Tenda N300 Anda
            '192.168.243.102',

            '114.125.93.184', // IP publik yang diizinkan (misalnya, IP server)
            '127.0.0.1', // localhost IPv4
            // '::1',       // localhost IPv6
        ],
        // Menambahkan seluruh rentang IP dari 192.168.60.1 hingga 192.168.60.254
        // Anda bisa menyesuaikan rentang start dan end jika DHCP Anda lebih spesifik
        generateIpRange($baseIp, 1, 254)
    ),
    'network_description' => 'Jaringan Wi-Fi Kantor (Tenda N300 - Seluruh Rentang Lokal)',
];