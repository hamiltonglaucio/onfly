<?php

namespace App\TravelService\UserInterface\Controllers;

use App\Http\Controllers\Controller;
use App\TravelService\UseCases\CancelApprovedTravelRequest;
use App\TravelService\UseCases\CreateTravelRequest;
use App\TravelService\UseCases\DTO\TravelRequestDTO;
use App\TravelService\UseCases\FilterTravelRequests;
use App\TravelService\UseCases\GetTravelRequestById;
use App\TravelService\UseCases\GetUserTravelRequests;
use App\TravelService\UseCases\ListTravelRequestsByStatus;
use App\TravelService\UserInterface\Requests\CreateTravelRequestRequest;
use App\TravelService\UserInterface\Requests\FilterTravelRequestFormRequest;
use App\TravelService\UserInterface\Requests\UpdateTravelRequestStatus;
use App\TravelService\UseCases\UpdateTravelRequestStatus as UseCasesUpdateTravelRequestStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Date;

class TravelRequestController extends Controller
{

    public function __construct(
        private readonly CreateTravelRequest $createUseCase,
        private readonly UseCasesUpdateTravelRequestStatus $updateUseCase,
        private readonly GetTravelRequestById $getByIdUseCase,
        private readonly ListTravelRequestsByStatus $listUseCase,
        private readonly CancelApprovedTravelRequest $cancelApprovedUseCase,
        private readonly FilterTravelRequests $filterTravelRequests,
        private readonly GetUserTravelRequests $userTravelRequests,
    ){}

    /**
     * Listar todos os pedidos de viagem
     *
     * Retorna todos os pedidos de viagem cadastrados. Caso um status seja informado na URL (`/status/{status}`),
     * a listagem será filtrada por esse status.
     *
     * @authenticated
     * @group Pedidos de Viagem
     *
     * @urlParam status string optional Status para filtro opcional. Example: request
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "userId": 2,
     *       "applicantName": "Hamiton Gláucio de Oliveira Júnior",
     *       "destination": "São Paulo",
     *       "departureDate": "2025-07-01T00:00:00.000000Z",
     *       "returnDate": "2025-07-02T00:00:00.000000Z",
     *       "status": "cancelled"
     *     },
     *     {
     *       "id": 2,
     *       "userId": 2,
     *       "applicantName": "Hamiton Gláucio de Oliveira Júnior",
     *       "destination": "São Paulo",
     *       "departureDate": "2025-10-05T00:00:00.000000Z",
     *       "returnDate": "2025-10-06T00:00:00.000000Z",
     *       "status": "approved"
     *     },
     *     {
     *       "id": 3,
     *       "userId": 3,
     *       "applicantName": "Hamiton Gláucio de Oliveira Júnior",
     *       "destination": "Salvador",
     *       "departureDate": "2025-10-07T00:00:00.000000Z",
     *       "returnDate": "2025-10-06T00:00:00.000000Z",
     *       "status": "request"
     *     }
     *   ]
     * }
     */
    public function index($status = '')
    {
        $result = $this->listUseCase->execute($status);

        return response()->json([
            'data' => collect($result)->map(fn($item) => $item->toArray())
        ], Response::HTTP_OK);
    }

    /**
     * Criar novo pedido de viagem
     *
     * Cria um novo pedido de viagem com os dados fornecidos no corpo da requisição.
     *
     * @authenticated
     * @group Pedidos de Viagem
     *
     * @bodyParam applicant_name string required Nome do solicitante da viagem. Example: José Alves
     * @bodyParam destination string required O destino da viagem. Example: Recife
     * @bodyParam departure_date date required Data de ida no formato AAAA-MM-DD. Example: 2025-04-10
     * @bodyParam return_date date required Data de volta no formato AAAA-MM-DD. Example: 2025-04-12
     *
     * @response 201 {
     *   "travelRequest": {
     *     "id": 7,
     *     "userId": 3,
     *     "applicantName": "José Alves",
     *     "destination": "Recife",
     *     "departureDate": "2025-04-10T00:00:00.000000Z",
     *     "returnDate": "2025-04-12T00:00:00.000000Z",
     *     "status": "request"
     *   }
     * }
     *
     * @response 422 {
     *   "message": "Validation failed.",
     *   "errors": {
     *     "applicant_name": [
     *       "The name is required."
     *     ],
     *     "destination": [
     *       "The destination is required."
     *     ],
     *     "departure_date": [
     *       "The start date is required."
     *     ],
     *     "return_date": [
     *       "The return date is required."
     *     ]
     *   }
     * }
     */

    public function store(CreateTravelRequestRequest $request): JsonResponse
    {
        $dto = new TravelRequestDTO(
            null,
            $request->user()->id,
            $request->input('applicant_name'),
            $request->input('destination'),
            Date::parse($request->input('departure_date')),
            Date::parse($request->input('return_date')),
            $request->input('status') ?? 'request',
        );

        $travelRequest = $this->createUseCase->execute($dto);

        return response()->json([
            'travelRequest' => $travelRequest->toArray()
        ],
            Response::HTTP_CREATED);
    }

    /**
     * Atualizar status do pedido de viagem
     *
     * Atualiza o status de um pedido de viagem (por exemplo: approved, rejected, cancelled, etc).
     *
     * @authenticated
     * @group Pedidos de Viagem
     *
     * @urlParam id integer required ID do pedido de viagem. Example: 2
     * @bodyParam status string required Novo status da viagem. Example: cancelled
     *
     * @response 200 {
     *   "travelRequest": {
     *     "id": 2,
     *     "userId": 2,
     *     "applicantName": "Hamiton Gláucio de Oliveira Júnior",
     *     "destination": "São Paulo",
     *     "departureDate": "2025-10-05T00:00:00.000000Z",
     *     "returnDate": "2025-10-06T00:00:00.000000Z",
     *     "status": "cancelled"
     *   }
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\TravelService\\Infrastructure\\Eloquent\\TravelRequest] 20",
     *   "exception": "Symfony\\Component\\HttpKernel\\Exception\\NotFoundHttpException"
     * }
     *
     * @response 422 {
     *   "message": "Validation failed.",
     *   "errors": {
     *     "status": ["A cancelled request cannot be updated."]
     *   }
     * }
     */

    public function updateStatus(UpdateTravelRequestStatus $request, int $id): JsonResponse
    {
        $entity = $this->updateUseCase->execute($id, $request->input('status'));
        return response()->json(['travelRequest' => $entity->toArray()], Response::HTTP_OK);
    }

    /**
     * Detalhar pedido de viagem
     *
     * Retorna os dados completos de um pedido de viagem pelo seu ID.
     *
     * @authenticated
     * @group Pedidos de Viagem
     *
     * @urlParam id integer required ID do pedido de viagem. Example: 1
     *
     * @response 200 {
     *   "travelRequest": {
     *     "id": 1,
     *     "userId": 2,
     *     "applicantName": "Hamiton Gláucio de Oliveira Júnior",
     *     "destination": "São Paulo",
     *     "departureDate": "2025-07-01T00:00:00.000000Z",
     *     "returnDate": "2025-07-02T00:00:00.000000Z",
     *     "status": "cancelled"
     *   }
     * }
     *
     * @response 404 {
     *   "message": "Pedido de viagem não encontrado."
     * }
     */

    public function show(int $id): JsonResponse
    {
        $entity = $this->getByIdUseCase->execute($id);
        return response()->json(['travelRequest' => $entity->toArray()], Response::HTTP_OK);
    }

    /**
     * Cancelar pedido de viagem
     *
     * Cancela um pedido de viagem. Viagens com status "approved" só podem ser canceladas se a data de partida ainda não tiver passado.
     *
     * @authenticated
     * @group Pedidos de Viagem
     *
     * @urlParam id integer required ID do pedido de viagem. Example: 3
     *
     * @response 200 {
     *   "travelRequest": {
     *     "id": 3,
     *     "userId": 3,
     *     "applicantName": "Hamiton Gláucio de Oliveira Júnior",
     *     "destination": "Salvador",
     *     "departureDate": "2025-10-07T00:00:00.000000Z",
     *     "returnDate": "2025-10-06T00:00:00.000000Z",
     *     "status": "cancelled"
     *   }
     * }
     *
     * @response 400 {
     *   "message": "A viagem aprovada não pode ser cancelada após a data de partida."
     * }
     *
     * @response 401 {
     *   "message": "Invalid or missing JWT token",
     *   "error": "The token has been blacklisted"
     * }
     *
     * @response 404 {
     *   "message": "Pedido de viagem não encontrado."
     * }
     */

    public function cancel(int $id): JsonResponse
    {
        $travelRequest = $this->cancelApprovedUseCase->execute($id);
        return response()->json(['travelRequest' => $travelRequest->toArray()], Response::HTTP_OK);
    }

    /**
     * Filtrar pedidos de viagem
     *
     * Permite buscar pedidos de viagem utilizando filtros combinados como destino, data de ida e data de volta.
     * Todos os filtros são opcionais e podem ser usados em conjunto.
     *
     * @authenticated
     * @group Pedidos de Viagem
     *
     * @queryParam destination string O destino desejado. Example: São Paulo
     * @queryParam departure_start date Data mínima de ida (inclusive). Example: 2025-04-10
     * @queryParam departure_end date Data máxima de ida (inclusive). Example: 2025-04-20
     * @queryParam return_start date Data mínima de volta (inclusive). Example: 2025-04-15
     * @queryParam return_end date Data máxima de volta (inclusive). Example: 2025-04-25
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "userId": 2,
     *       "applicantName": "Hamiton Gláucio de Oliveira Júnior",
     *       "destination": "São Paulo",
     *       "departureDate": "2025-07-01T00:00:00.000000Z",
     *       "returnDate": "2025-07-02T00:00:00.000000Z",
     *       "status": "cancelled"
     *     },
     *     {
     *       "id": 2,
     *       "userId": 2,
     *       "applicantName": "Hamiton Gláucio de Oliveira Júnior",
     *       "destination": "São Paulo",
     *       "departureDate": "2025-10-05T00:00:00.000000Z",
     *       "returnDate": "2025-10-06T00:00:00.000000Z",
     *       "status": "approved"
     *     }
     *   ]
     * }
     *
     * @response 401 {
     *   "message": "Invalid or missing JWT token",
     *   "error": "Could not decode token: Error while decoding from Base64Url, invalid base64 characters detected"
     * }
     */

    public function filter(FilterTravelRequestFormRequest $request): JsonResponse
    {
        $filtered = $this->filterTravelRequests->execute(
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            destination: $request->input('destination'),
            byUser: $request->input('user_id') ?? null,
        );

        return response()->json([
            'data' => array_map(fn($item) => $item->toArray(), $filtered)
        ]);
    }

    /**
     * Listar notificações de pedidos de viagem
     *
     * Retorna notificações relacionadas aos pedidos de viagem do usuário autenticado, como atualizações de status.
     *
     * @authenticated
     * @group Pedidos de Viagem
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": "fec1edaf-d628-4b81-a26a-b00e1226dac6",
     *       "type": "App\\TravelService\\UserInterface\\Notifications\\TravelRequestStatusUpdated",
     *       "notifiable_type": "App\\AuthService\\Infrastructure\\Eloquent\\User",
     *       "notifiable_id": 3,
     *       "data": {
     *         "travel_request_id": 2,
     *         "status": "approved",
     *         "destination": "São Paulo",
     *         "departure_date": "2025-10-05",
     *         "return_date": "2025-10-06",
     *         "message": "Sua solicitação de viagem para São Paulo foi approved."
     *       },
     *       "read_at": null,
     *       "created_at": "2025-04-06T22:16:38.000000Z",
     *       "updated_at": "2025-04-06T22:16:38.000000Z"
     *     },
     *     {
     *       "id": "c8212df4-fa73-4b2c-959b-d90eb2d6f304",
     *       "type": "App\\TravelService\\UserInterface\\Notifications\\TravelRequestStatusUpdated",
     *       "notifiable_type": "App\\AuthService\\Infrastructure\\Eloquent\\User",
     *       "notifiable_id": 3,
     *       "data": {
     *         "travel_request_id": 1,
     *         "status": "cancelled",
     *         "destination": "São Paulo",
     *         "departure_date": "2025-07-01",
     *         "return_date": "2025-07-02",
     *         "message": "Sua solicitação de viagem para São Paulo foi cancelled."
     *       },
     *       "read_at": null,
     *       "created_at": "2025-04-06T22:15:58.000000Z",
     *       "updated_at": "2025-04-06T22:15:58.000000Z"
     *     }
     *   ]
     * }
     *
     * @response 200 {
     *   "data": []
     * }
     */

    public function notifications():JsonResponse
    {
        $user = auth()->user();

        return response()->json([
            'data' => $user->notifications()->latest()->get()
        ]);
    }

    /**
     * Listar pedidos de viagem do usuário autenticado
     *
     * Retorna todos os pedidos de viagem feitos pelo usuário atualmente autenticado.
     *
     * @authenticated
     * @group Pedidos de Viagem
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 3,
     *       "userId": 3,
     *       "applicantName": "Hamiton Gláucio de Oliveira Júnior",
     *       "destination": "Salvador",
     *       "departureDate": "2025-10-07T00:00:00.000000Z",
     *       "returnDate": "2025-10-06T00:00:00.000000Z",
     *       "status": "request"
     *     },
     *     {
     *       "id": 4,
     *       "userId": 3,
     *       "applicantName": "Hamiton Gláucio de Oliveira Júnior",
     *       "destination": "João Pessoa",
     *       "departureDate": "2025-10-07T00:00:00.000000Z",
     *       "returnDate": "2025-10-06T00:00:00.000000Z",
     *       "status": "request"
     *     }
     *   ]
     * }
     *
     * @response 401 {
     *   "message": "Token inválido ou expirado"
     * }
     */
    public function findByUserId(): JsonResponse
    {
        $results = $this->userTravelRequests->execute();

        return response()->json([
            'data' => array_map(fn($r) => $r->toArray(), $results),
        ]);
    }
}
