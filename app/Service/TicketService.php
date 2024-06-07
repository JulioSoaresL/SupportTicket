<?php

namespace App\Service;

use App\Enums\UserRole;
use App\Models\Ticket;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketService
{
    use HttpResponses;
    public function getAllTickets()
    {
        return Ticket::all();
    }

    public function getTicketById($id)
    {
        return Ticket::findOrFail($id);
    }

    public function createTicket(Request $request): JsonResponse
    {
        if (Auth::user()->role !== UserRole::CLIENT) {
            return $this->error('Just users can create tickets', 400, []);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,doc,docx',
        ]);

        if ($validator->fails()) {
            return $this->error('Invalid data.', 400, $validator->errors());
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('public');
        }

        try {
            $ticket = Ticket::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'attachment' => $attachmentPath,
                'status' => 'Aberto',
            ]);
        } catch (\Exception $e) {
            return $this->error('Error to create ticket.', 400, $validator->errors());
        }

        return $this->success('Ticket created successfully', 200, $ticket);
    }

    public function updateTicketStatus(Request $request, $id): JsonResponse
    {
        if (Auth::user()->role !== UserRole::COLLABORATOR) {
            return response()->json(['message' => 'You dont permission to change ticket status.'], 403);
        }

        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:Aberto,Em atendimento,Finalizado'
        ]);

        $ticket->status = $request->status;
        $ticket->save();

        return $this->success('Update successfully', 200);
    }


}
