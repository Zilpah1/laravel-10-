<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        //validate the request data
        $request->validate([
            'body' => 'required|string',
        ]);

        //Create a new reply for the ticket
        $reply = new Reply([
            'body' => $request->input('body'),
            'user_id' => auth()->user()->id,
        ]);

        $ticket->replies()->save($reply);

        //Notify user about reply via email
        return redirect()->route('tickets.show', $ticket);
    }
}
