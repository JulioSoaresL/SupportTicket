<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return Ticket[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return Ticket::all();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'cliente') {
            return response()->json(['message' => 'Just users can create tickets.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|string|file|mimes:jpg,png,pdf,doc,docx',
        ]);

        if ($validator->fails()) {
            return $this->error('Invalid data', 422, $validator->errors());
        }

        $attachmentPath = null;
        if($request->hasFile('attachment')){
            $file = $request->file('attachment')->store('public');
        }

        try {
            $ticket = Ticket::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'attachment' => $attachmentPath,
                'status'=> 'Aberto'
            ]);
        } catch(\Exception $e) {
            return response()->json(['error' => 'Error to create user.'], 500);
        }

        return $this->success('Ticket send successfully', 201, $ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Ticket::where('id', $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'colaborador') {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
