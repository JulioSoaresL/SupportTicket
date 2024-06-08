<?php

namespace App\Service;

use App\Enums\UserRole;
use App\Models\Reply;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReplyService
{
    use HttpResponses;

    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function createReply(Request $request, $ticketId): JsonResponse
    {

        if(Auth::user()->role !== UserRole::COLLABORATOR){
            return $this->error('You don\'t have permission to reply', 400, []);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Invalid data', 400, $validator->errors());
        }

        try {
            $reply = Reply::create([
                'ticket_id' => $ticketId,
                'user_id' => Auth::id(),
                'message' => $request['message'],
            ]);

            $statusRequest = new Request(['status' => 'Em atendimento']);
            $this->ticketService->updateTicketStatus($statusRequest, $ticketId);

            return $this->success('Reply successfully created', 200, $reply);

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400, []);
        }
    }
}
