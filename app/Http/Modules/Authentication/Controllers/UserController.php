<?php

namespace App\Http\Modules\Authentication\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\AuthenticationException;
use App\Http\Modules\Authentication\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Authentication\Requests\UserRequest;
use App\Http\Modules\Authentication\Services\UserService;
use App\Http\Modules\Authentication\Requests\LoginRequest;
use App\Http\Modules\Authentication\Requests\RegisterRequest;
use App\Http\Modules\Authentication\Requests\UserPhotoRequest;
use App\Http\Modules\Authentication\Requests\UserProfileRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\Authentication\Requests\ForgetPasswordRequest;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Modules\Authentication\Requests\UserChangePasswordRequest;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgetPassword']]);

        $this->service = $service;
    }

    /**
     * List page to display all data with pagination support.
     *
     * @link http://user
     *
     * @method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data = null;
        $code = "SUCCESS";
        try {
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
        if ($code !== "SUCCESS") {
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
     * Displays a form to add new user
     *
     * @link http://user/create/
     *
     * @method GET
     *
     * @return Response
     */
    public function create()
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $data = [
                'formData' => [
                    // 'type' => 'Add',
                    // 'title' => 'Add Geographical user',
                    'fields' => $this->service->getFormFields(),
                    'formStoreUrl' => 'user'
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

        if ($code !== "SUCCESS") {
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
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = null;
        $code = "SUCCESS";

        // Create a new user record
        try {
            // Check if the authenticated user has permission
            if (Auth::user()->checkHasPermissionTo('storeUser')) {
                $data = $this->service->add($request);
            } else {
                $code = "UNAUTHORIZED_USER";
            }
        } catch (AuthenticationException $e) {
            $code = "UNAUTHORIZED_USER";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        return ResponseHelper::respond(
            $code,
            $data,
        );
    }


    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {

        // Create a new user record
        $data = null;
        $code = "SUCCESS";
        try {
            $this->service->add($request);
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
     * update the status in list
     *
     * @link http://user/update/id
     *
     * @method PATCH/PUT
     *
     * @param UserRequest $request The request object
     * @param int $id The request object
     *
     * @return request
     */
    public function profileUpdate(UserProfileRequest $request, int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $this->service->profileUpdate($request, $id);
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
        if ($code !== "SUCCESS") {
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
     * @link http://user/update/id
     *
     * @method PATCH/PUT
     *
     * @param UserRequest $request The request object
     * @param int $id The request object
     *
     * @return request
     */
    public function photoUpload(UserPhotoRequest $request, int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $fileUrl = '';
            if ($request->hasFile('profilePhoto')) {
                $fileUrl = $this->service->S3ImageUpload($request);
                if ($fileUrl) {
                    //Remove old image
                    $this->service->S3ImageDelete($id);
                }
            }
            $this->service->photoUpload($request, $fileUrl, $id);
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
        if ($code !== "SUCCESS") {
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
     * Displays a form with values to update user
     *
     * @link http://geo/user/edit/id
     *
     * @method GET
     *
     * @return Response
     */
    public function edit(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $tableData = $this->service->findUserById($id);
            $data = collect($this->service->getFormFields())->map(function ($fields) use ($tableData) {
                $attrib = $fields['name'];
                if (isset($tableData[$attrib])) {
                    $fields['value'] = $tableData[$attrib];
                } else {
                    $fields['value'] = '';
                }
                return $fields;
            });
            $data = [
                'formData' => [
                    'fields' => $data,
                    'formStoreUrl' => "user/$id"
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

        if ($code !== "SUCCESS") {
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
     * @link http://user/update/id
     *
     * @method PATCH/PUT
     *
     * @param UserRequest $request The request object
     * @param int $id The request object
     *
     * @return request
     */
    public function update(UserRequest $request, int $id)
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
        if ($code !== "SUCCESS") {
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
     * Log in the user with mobile and password credentials and generate a JWT token upon successful login.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing mobile and password fields.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the JWT token on successful login or an error message on failure.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Get the user input from the login request
        $mobile = $request->input('mobile');
        $password = $request->input('password');

        // Create a User model instance to apply the accessor
        $user = new User();
        $mobileWithPrefix = $user->getMobileNumberAttribute($mobile);

        // Prepare the credentials array for the login attempt
        $credentials = [
            'mobile' => $mobileWithPrefix,
            'password' => $password,
        ];

        try {
            // Attempt to authenticate the user with the provided credentials and generate a JWT token
            if (!$token = auth()->attempt($credentials)) {
                // Return a JSON response with an error message if authentication fails
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            // Return a JSON response with an error message if there is an issue creating the JWT token
            return response()->json(['error' => 'Could not create token'], 500);
        }

        // Return a JSON response with the generated JWT token on successful login
        return $this->respondWithToken($token);
    }

    /**
     * Generate a JSON response containing the JWT token.
     *
     * @param string $token The JWT token to be included in the response.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the JWT token.
     */
    protected function respondWithToken($token)
    {
        // Create a JSON response with the JWT token
        return response()->json([
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL()
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
        $code = "SUCCESS";
        try {
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
        if ($code !== "SUCCESS") {
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
     * finalize the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
    public function finalize(Request $request)
    {
        $data = null;
        $code = "SUCCESS";
        try {
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
        if ($code !== "SUCCESS") {
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        } else {
            return ResponseHelper::respond(
                code: $code,
                data: $data
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
        $code = "SUCCESS";
        try {
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
        if ($code !== "SUCCESS") {
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
     * Displays a form with values to update user
     *
     * @link http://geo/user/edit/id
     *
     * @method GET
     *
     * @return Response
     */
    public function profile(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $data = $this->service->findUserProfileById($id);
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
     * @link http://user/changePassword/id
     *
     * @method PATCH/PUT
     *
     * @param UserRequest $request The request object
     * @param int $id The request object
     *
     * @return request
     */
    public function changePassword(UserChangePasswordRequest $request, int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $this->service->changePassword($request, $id);
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
        if ($code !== "SUCCESS") {
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
     * Displays a form with values to update user
     *
     * @link http://geo/user/edit/id
     *
     * @method GET
     *
     * @return Response
     */
    public function photo(int $id)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $data = $this->service->findUserPhotoById($id);
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
     * @link http://user/forgetPassword/id
     *
     * @method PATCH/PUT
     *
     * @param UserforgetPasswordRequest $request The request object
     * @param int $id The request object
     *
     * @return request
     */
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $this->service->forgetPassword($request);
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
        if ($code !== "SUCCESS") {
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
}
