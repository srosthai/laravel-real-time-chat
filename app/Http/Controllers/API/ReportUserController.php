<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateReportUserRequest;
use App\Repositories\ReportedUserRepository;
use Illuminate\Http\JsonResponse;

/**
 * Class ReportUserController
 */
class ReportUserController extends AppBaseController
{
    /**
     * @var ReportedUserRepository
     */
    private $reportedUserRepo;

    /**
     * ReportUserController constructor.
     *
     * @param  ReportedUserRepository  $reportedUserRepository
     */
    public function __construct(ReportedUserRepository $reportedUserRepository)
    {
        $this->reportedUserRepo = $reportedUserRepository;
    }

    /**
     * @param  CreateReportUserRequest  $request
     * @return JsonResponse
     */
    public function store(CreateReportUserRequest $request)
    {
        $this->reportedUserRepo->createReportedUser($request->all());

        return $this->sendSuccess('User reported successfully.');
    }
}
