<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return TicketResource::collection(Ticket::query()->orderByDesc('id')->get());
    }

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $ticket = Ticket::query()->create($request->validated());

        return (new TicketResource($ticket))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
