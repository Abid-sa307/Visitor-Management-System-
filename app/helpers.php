<?php

if (!function_exists('panel_route')) {
    function panel_route($name, $params = []) {
        $prefix = auth()->check() && auth()->user()->role === 'super_admin' ? '' : 'company.';
        return route($prefix . $name, $params);
    }
}
