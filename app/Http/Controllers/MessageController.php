<?php

namespace App\Http\Controllers;

use App\Events\Chat\SendMessage;
use Illuminate\Http\Request;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class MessageController extends Controller
{

    public function listMessages($userTo)
    {

        $userFrom = Auth::user()->id;

        /**
         * [from = $userFrom && to = $userTo]
         * OR
         * [from = $userTo && to = $userFrom]
         */
        $menssages = Message::where(
            function ($query) use ($userFrom, $userTo) {
                $query->where([
                    'from' => $userFrom,
                    'to' => $userTo
                ]);
            }
        )->orWhere(
            function ($query) use ($userFrom, $userTo) {
                $query->where([
                    'from' => $userTo,
                    'to' => $userFrom
                ]);
            }
        )
            ->orderBy('created_at', 'ASC')
            ->get();

        return response()->json(['result' => $menssages], 200);
    }

    public function listMessagesPaginate($userTo)
    {

        $userFrom = Auth::user()->id;

        /**
         * [from = $userFrom && to = $userTo]
         * OR
         * [from = $userTo && to = $userFrom]
         */
        $menssages = Message::where(
            function ($query) use ($userFrom, $userTo) {
                $query->where([
                    'from' => $userFrom,
                    'to' => $userTo
                ]);
            }
        )->orWhere(
            function ($query) use ($userFrom, $userTo) {
                $query->where([
                    'from' => $userTo,
                    'to' => $userFrom
                ]);
            }
        )
            ->orderBy('created_at', 'DESC')
            ->paginate(15);

        return response()->json(['result' => $menssages], 200);
    }


    public function create(Request $request)
    {

        $this->validate($request, [
            'content' => 'required|string',
            'to' => 'required',
        ]);

        try {

            $userFrom = Auth::user()->id;
            $userTo = $request->to;

            $message = new Message;
            $message->from = $userFrom;
            $message->to = $userTo;
            $message->content = filter_var($request->content, \FILTER_SANITIZE_STRIPPED);
            $message->save();

            // Event::dispatch(new SendMessage($message, $userTo));
            // broadcast(new SendMessage($message, $userTo));
            // SendMessage::dispatch($message, $userTo);

            return response()->json([
                'result' => 'success',
                'message' => $message,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'result' => 'failed',
                'error' => $e,
            ], 409);
        }
    }
}
