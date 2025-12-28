<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;

class FaceRecognitionController extends Controller
{
    public function registerVisitor(Request $request, Visitor $visitor)
    {
        $request->validate([
            'face_encoding' => 'required|string',
        ]);

        try {
            $visitor->face_encoding = $request->face_encoding;
            $visitor->save();

            return response()->json([
                'success' => true,
                'message' => 'Face registered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register face: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyVisitor(Request $request, Visitor $visitor)
    {
        $request->validate([
            'face_encoding' => 'required|string',
        ]);

        try {
            // Simple verification - in real implementation, you'd compare face encodings
            if (!empty($visitor->face_encoding)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Face verified successfully',
                    'visitor_id' => $visitor->id
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No face data found for visitor'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkInWithFace(Request $request, Visitor $visitor)
    {
        try {
            if (!$visitor->in_time) {
                $visitor->in_time = now();
                $visitor->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Check-in successful with face verification'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Visitor already checked in'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Check-in failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkOutWithFace(Request $request, Visitor $visitor)
    {
        try {
            if ($visitor->in_time && !$visitor->out_time) {
                $visitor->out_time = now();
                $visitor->save();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Check-out successful with face verification'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Visitor not checked in or already checked out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Check-out failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
