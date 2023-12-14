<?php

namespace App\Http\Masters\Gen\Controllers;

use Exception;
use App\Exports\ErrorExport;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Masters\Gen\Models\Bank;
use App\Imports\CustomImportsService;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Auth\AuthenticationException;
use App\Http\Masters\Gen\Requests\BankRequest;
use App\Http\Masters\Gen\Services\BankService;
use App\Http\Masters\Geo\Requests\BulkInsertRequest;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class BankController extends Controller
{
    protected $service,$bank;

    /**
     * Base URL of this module
    */
    private string $baseUrl = "";

    /**
     * Injects Bank Service dependency through the constructor
     *
     * @param BankService $service
     *
     * @return Void
     */
    public function __construct(BankService $service,Bank $bank)
    {
        $this->service = $service;
        $this->bank = $bank;
    }

    /**
     * List page to display all data with pagination support.
     *
     *@link http://api/v1/gen/bank
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            $data =  $this->service->getAllPaginatedTableData($request);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        } else {
             return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

    /**
     * Displays a form to add new Bank
     *
     *@link http://api/v1/gen/bank/create/
     *
     *@method GET
     *
     * @return Response
     */
    public function create()
    {
        $data = null;
        $code = "SUCCESS";
        try{
             $data = [
                'formData' => [
                    //'type' => 'Add',
                    //'title' => 'Add General Bank',
                    'fields' => $this->service->getFormFields(),
                    'formStoreUrl' => $this->baseUrl . 'gen/bank',
                ],
                'bulkInsert' => [$this->service->getBulkInsertFormFields()]
            ];
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }

         if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        } else {
              return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

     /**
     * Store a newly created resource in storage.
     *
     *@link http://api/v1/gen/bank/store
     *
     *@method POST
     *
     * @param BankRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BankRequest $request)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $this->service->add(data: $request);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        } else {
              return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

     /**
     * Displays a form with values to update Bank
     *
     *@link http://api/v1/gen/bank/edit/id
     *
     *@method GET
     *
     * @return Response
     */
    public function edit(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $tableData = $this->service->findBankById($id);
            $data = collect($this->service->getFormFields())->map(function ($fields) use ($tableData) {
                $attrib = $fields['name'];
                $fields['value']  = $tableData[$attrib];
                return $fields;
            });

            $data =  [
                'formData' => [
                // 'type' => 'Edit',
                // 'methodName' => 'PUT',
                // 'title' => 'General Bank',
                   'fields' => $data,
                   'formStoreUrl' => "{$this->baseUrl}gen/bank/$id",
                ]
            ];
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        } else {
             return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        }
    }

    /**
     * update the status in list
     *
     *@link http://api/v1/gen/bank/update/id
     *
     *@method PATCH/PUT
     *
     * @param BankRequest  $request The request object
     * @param int $id The request object
     *
     * @return request
     */
    public function update(BankRequest $request, int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $this->service->update($request, $id);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (ModelNotFoundException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        }else{
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        }
    }

    /**
     * reject the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
     public function reject(Request $request)
    {
        $data = null;
        $code ="SUCCESS";
        try{
            $id = $request->all();
            $this->service->updateRejectStatus(
                id: $id
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data
            );
        }else{
             return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

    /**
     * finalize the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
    public function finalize(Request $request)
    {
        $data = null;
        $code ="SUCCESS";
        try{
            $id = $request->all();
            $this->service->updateFinalizeStatus(
                id: $id
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }else{
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

    /**
     * approve the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
    public function approve(Request $request)
    {
        $data = null;
        $code ="SUCCESS";
        try{
            $id = $request->all();
            $this->service->updateApproveStatus(
                id: $id
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }else{
             return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }


    /**
     * updateStatus the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
    public function updateStatus(Request $request,string $status)
    {
        $data = null;
        $code ="SUCCESS";
        try{
            $id = $request->all();
            $this->service->updateStatus(
                id: $id,
                status: $status,
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if($code!=="SUCCESS"){
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }else{
             return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        }
    }

    /* import the specified resource in db.
    *
    * @param \App\Http\Requests\Test\Request $request
    * @param int $request
    *
    * @return \Illuminate\Http\RedirectResponse
    */
    public function import(BulkInsertRequest $bulkInsertRequest)
    {
        $version_url = Config::get('app.version_url');
        $code = "SUCCESS";
        try {
            //Define fields to validate excel data
            $validation = [
                'name' => 'required|unique:agri_quality,name',
            ];
            $validationArr = collect($validation)->keys();
            //Remove slash from value
            // $validationArr = $validationArr->map(function (String $value) {
            //     return Str::replace('\\','',$value);
            // });
            $file = $bulkInsertRequest->file('file');
            //fetch all headings from excel
            $headings = (new HeadingRowImport)->toCollection($file)->first()->first();
            //campare and validate excel heading
            $diff = $validationArr->diff($headings)->filter()->all();
            // dd($validationArr,$headings,$diff);
            if (!empty($diff)) {
                return response()->json([
                    'status'=> 422,
                    'message'=>'Invalid headers'
                ],422);
            } else {
                $import = new CustomImportsService($this->bank, $validation);
                $import->import($file);
            }
        } catch (ValidationException $e) {
            $code = "DATA_NOT_FOUND";
            $collection = (new CustomImportsService($this->bank, []))->toCollection($file);
            $failures = $e->failures();
            $fileName = $this->bank->getTable() . '_error.xlsx';
            Excel::store(
                new ErrorExport($failures, $collection),
                $fileName,
                'local'
            );
            //TODO: Remove redirect and send response
            $path = url("{$version_url}agri/quality/download/{$fileName}");
            // return an error
            $bulkInsert = $this->service->getBulkInsertFormFields();
            $bulkInsert['downloadErrorFileUrl'] = $path;
            return response()->json([
                'status'=> 422,
                'message'=>'fail',
                'data' => [
                    'bulkInsert' => [$bulkInsert]
                ]
            ],422);

        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if ($code !== "SUCCESS") {
            return ResponseHelper::respond(
                code: $code
            );
        } else {
            return ResponseHelper::respond(
                code: $code
            );
        }
    }

    //Download error sheet and delete after download.
    public function download($file_name)
    {
        $file_path = storage_path('app/' . $file_name);
        return response()->download($file_path)->deleteFileAfterSend(true);
    }
}
