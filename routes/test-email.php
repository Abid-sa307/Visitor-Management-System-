<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email from your application', function($message) {
            $message->to('nntvms@gmail.com')
                    ->subject('Test Email from Visitor Management System');
        });
        
        return 'Test email sent successfully to nntvms@gmail.com';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
