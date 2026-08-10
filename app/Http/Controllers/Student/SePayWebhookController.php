<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\SePayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SePayWebhookController extends Controller
{
    public function __construct(protected SePayService $sePayService) {}

    public function webhook(Request $request): JsonResponse
    {
        if (! $this->sePayService->validateWebhookHeader($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized Webhook Signature / API Key',
            ], 401);
        }

        $result = $this->sePayService->processWebhookPayload($request->all());

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
