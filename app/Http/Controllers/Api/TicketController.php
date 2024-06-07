<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\TicketService;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use HttpResponses;

    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(): JsonResponse
    {
        $tickets = $this->ticketService->getAllTickets();
        return $this->success('Tickets retrieved successfully', 200, $tickets);
    }

    public function create()
    {
        //
    }

    public function store(Request $request): JsonResponse
    {
        return $this->ticketService->createTicket($request);

    }

    public function show($id): JsonResponse
    {
        $ticket = $this->ticketService->getTicketById($id);
        return $this->success('Ticket retrieved successfully', 200, $ticket);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id): JsonResponse
    {
        return $this->ticketService->updateTicketStatus($request, $id);
    }

    public function destroy($id)
    {
        //
    }
}
