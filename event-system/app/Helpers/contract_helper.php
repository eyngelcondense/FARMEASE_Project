<?php

if (!function_exists('getStatusIcon')) {
    function getStatusIcon($status) {
        $icons = [
            'active' => 'check-circle',
            'pending' => 'clock',
            'expired' => 'times-circle',
            'draft' => 'edit',
            'signed' => 'file-signature',
            'cancelled' => 'ban'
        ];
        
        return $icons[$status] ?? 'circle';
    }
}

if (!function_exists('getStatusBadge')) {
    function getStatusBadge($status) {
        $badges = [
            'active' => 'success',
            'pending' => 'warning',
            'expired' => 'secondary',
            'draft' => 'info',
            'signed' => 'primary',
            'cancelled' => 'danger'
        ];
        
        return $badges[$status] ?? 'secondary';
    }
}

if (!function_exists('formatExpirationDate')) {
    function formatExpirationDate($expiresAt) {
        if (empty($expiresAt) || $expiresAt == '0000-00-00 00:00:00' || strtotime($expiresAt) <= 0) {
            return 'No expiration date set';
        }
        
        $expiration = strtotime($expiresAt);
        $now = time();
        
        if ($expiration < $now) {
            return '<span class="text-danger">Expired on ' . date('F j, Y g:i A', $expiration) . '</span>';
        } else {
            $daysLeft = floor(($expiration - $now) / (60 * 60 * 24));
            return date('F j, Y g:i A', $expiration) . ' <small class="text-muted">(' . $daysLeft . ' days remaining)</small>';
        }
    }
}

?>