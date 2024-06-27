<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function createFeedback(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'email' => 'nullable|email',
            'name' => 'nullable|string',
        ]);

        return response()->json(['message' => 'Feedback sent successfully']);
    }
}
