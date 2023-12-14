<?php

namespace App\Http\Masters\Common\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Masters\Common\Services\DropdownService;
use App\Http\Masters\Common\Requests\DropdownRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class DropdownController extends Controller
{
    protected $service;

    /**
     * Base URL of this module
    */
    private string $baseUrl = "/api/v1/common/dropdown";

    /**
     * Injects Dropdown Service dependency through the constructor
     *
     * @param DropdownService $service
     *
     * @return Void
     */
    public function __construct(DropdownService $service)
    {
        $this->service = $service;
    }

    /**
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/country
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listCountry()
    {
        $data = null;
        $code = "SUCCESS";
        try{
            $data =  $this->service->getAllCountry();
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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/state
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listState(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            // if($request){
            //     $id = $request->countryId;
            // }
            $data =  $this->service->getAllState($id);

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/district
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listDistrict(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            // if($request){
            //     $id = $request->countryId;
            // }
            $data =  $this->service->getAllDistrict($id);

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/city
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listCity(int $districtId)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            // if($request){
            //     $id = $request->countryId;
            // }
            $data =  $this->service->getAllCity($districtId);

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/listRejectionReason
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listRejectionReason(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            // if($request){
            //     $id = $request->countryId;
            // }
            $data =  $this->service->getAllRejectionReason($id);

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/listRejectionReasonType
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listRejectionReasonType()
    {
        $data = null;
        $code = "SUCCESS";
        try{
            // if($request){
            //     $id = $request->countryId;
            // }
            $data =  $this->service->getAllRejectionReasonType();

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/listLanguage
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listLanguage()
    {
        $data = null;
        $code = "SUCCESS";
        try{
            // if($request){
            //     $id = $request->countryId;
            // }
            $data =  $this->service->getAllLanguage();

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/listLocation
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listLocation(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            // if($request){
            //     $id = $request->countryId;
            // }
            $data =  $this->service->getAllLocation($id);

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/listCommodity
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listCommodity(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            // if($request){
            //     $id = $request->countryId;
            // }
            $data =  $this->service->getAllCommodity($id);

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/listVariety
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listVariety(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            // if($request){
            //     $id = $request->countryId;
            // }
            $data =  $this->service->getAllVariety($id);

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/listLanguage
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listOfUomType()
    {
        $data = null;
        $code = "SUCCESS";
        try{
            $data =  $this->service->getAllUomType();

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/listVariety
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listOfUomByUomType(int $id=null)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            // if($request){
            //     $id = $request->countryId;
            // }
            $data =  $this->service->getAllUomByUomType($id);

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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/listAcivity
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listAcivity(int $id=null)
    {
        $data = null;
        $code = "SUCCESS";
        try{
            $data =  $this->service->getAllActivity($id);
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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/listPermission
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listPermission()
    {
        $data = null;
        $code = "SUCCESS";
        try{
            $data =  $this->service->getAllPermission();
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
     * List page to display all data.
     *
     *@link http://api/v1/dropdown/country
     *
     *@method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function listMenu()
    {
        $data = null;
        $code = "SUCCESS";
        try{
            $data =  $this->service->getAllMenu();
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
}
