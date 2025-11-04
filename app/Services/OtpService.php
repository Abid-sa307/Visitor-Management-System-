<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerificationMail;

class OtpService
{
    /**
     * Generate and save OTP for user
     */
    public function generateAndSendOtp(User $user): string
    {
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        try {
            // Save OTP to user
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(10), // OTP valid for 10 minutes
            ]);

            // Log the OTP for testing purposes
            \Log::info('OTP generated for user', [
                'user_id' => $user->id,
                'email' => $user->email,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10)->toDateTimeString()
            ]);

            // Send OTP via email
            Mail::to($user->email)->send(new OtpVerificationMail($otp));
            
            \Log::info('OTP email sent successfully', ['email' => $user->email]);
            
            return $otp;
            
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Still return the OTP so the flow can continue (for testing)
            return $otp;
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(User $user, string $otp): bool
    {
        // Log the verification attempt
        \Log::info('Verifying OTP in service', [
            'user_id' => $user->id,
            'provided_otp' => $otp,
            'stored_otp' => $user->otp,
            'otp_expires_at' => $user->otp_expires_at,
            'current_time' => now(),
            'otp_type' => gettype($user->otp),
            'otp_length' => $user->otp ? strlen($user->otp) : 0,
            'otp_match' => $user->otp === $otp,
            'not_expired' => $user->otp_expires_at && $user->otp_expires_at > now()
        ]);

        if ($user->otp === $otp && $user->otp_expires_at > now()) {
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
                'otp_verified_at' => now(),
            ]);
            \Log::info('OTP verification successful', ['user_id' => $user->id]);
            return true;
        }
        
        \Log::warning('OTP verification failed', [
            'user_id' => $user->id,
            'reason' => $user->otp !== $otp ? 'OTP mismatch' : 'OTP expired'
        ]);
        
        return false;
    }
}
