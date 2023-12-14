<?php

namespace App\Http\Modules\Authentication\Services;
use Exception;
use App\Events\SendMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Events\PasswordReset;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Authentication\Requests\UserRequest;
use App\Http\Modules\Authentication\Requests\UserPhotoRequest;
use App\Http\Modules\Authentication\Repositories\UserRepository;
use App\Http\Modules\Authentication\Requests\UserProfileRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Modules\Authentication\Responses\Lists\UserListResponse;
use App\Http\Modules\Authentication\Requests\UserChangePasswordRequest;
use App\Http\Modules\Authentication\Responses\Table\UserTableCollection;
use App\Http\Modules\Authentication\Responses\Lists\UserPhotoListResponse;
use App\Http\Modules\Authentication\Responses\Lists\UserProfileListResponse;


class UserService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function add($request)
    {
        // Create a new user record
        try {
            $password = Str::random(14);
            $request->merge([
                'password' => $password
            ]);
            $this->welcomeEmailToUser($request);
            $user = $this->repository->add($request);
        } catch (Exception $e) {
            throw $e;
        }
        // Return the registration response data
        return $user;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CountryTableCollection
     */
    public function getAllPaginatedTableData($request)
    {

        // Return in the given API resource format
        try {
            return new UserTableCollection($this->repository->find($request));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the dynamic form elements
     *
     * @return array
     */
    public function getFormFields(): array
    {
        try {
            return $this->repository->getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the dynamic form elements
     *
     * @return array
     */
    public function getProfileFormFields(): array
    {
        try {
            return $this->repository->getProfileFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the dynamic form elements
     *
     * @return array
     */
    public function getChangePasswordFormFields(): array
    {
        try {
            return $this->repository->getChangePasswordFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Render the edit view for the User model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findUserById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new UserListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the User model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findUserProfileById(int $id)
    {
        try {
            return collect(new UserProfileListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the User model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findUserPhotoById(int $id)
    {
        try {
            return collect(new UserPhotoListResponse($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param UserRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, int $id)
    {
        // Retrieve the Country from the database
        try {
            $user = $this->repository->findById($id);
            if ($user) {
                return $this->repository->update($request, $id);
            }
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param UserProfileRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function profileUpdate(UserProfileRequest $request, int $id)
    {
        // Retrieve the Country from the database
        try {
            $user = $this->repository->findById($id);
            if ($user) {
                return $this->repository->profileUpdate($request, $id);
            }
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Update the specified resource in db.
     *
     * @param UserProfileRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function photoUpload(UserPhotoRequest $request, $fileUrl, int $id)
    {
        // Retrieve the Country from the database
        try {
            $user = $this->repository->findById($id);
            if ($fileUrl) {
                $request->merge([
                    'profile_photo' => $fileUrl
                ]);
            }
            if ($user) {
                return $this->repository->photoUpdate($request, $id);
            }
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param UserProfileRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(UserChangePasswordRequest $request, int $id)
    {
        // Retrieve the Country from the database
        try {
            $user = $this->repository->findById($id);
            if ($user) {
                $passwordReset = $this->repository->changePassword($request, $id);
                if ($passwordReset) {
                    $request->merge([
                        'name' => $user->UserKyc->full_name,
                        'email' => $user->email,
                    ]);
                    $this->passwordChangedEmailToUser($request);
                }
            }
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param UserProfileRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forgetPassword(UserChangePasswordRequest $request)
    {
        // Retrieve the Country from the database
        try {
            $user = $this->repository->findByEmail($request->email);
            if ($user) {

                $password = Str::random(14);
                $request->merge([
                    'name' => $user->UserKyc->full_name,
                    'email' => $user->email,
                    'password' => $password
                ]);
                $passwordReset = $this->repository->changePassword($request, $user->id);
                if($passwordReset){
                    $this->passwordChangedEmailToUser($request);
                }
            }
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an Country record to 'rejected'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateRejectStatus(array $id)
    {
        try {
            return $this->repository->updateStatusReject(array($id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an Country record to 'Active'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateFinalizeStatus(array $id)
    {
        try {
            return $this->repository->updateStatusFinalize(array($id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an Country record to 'Approved'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateApproveStatus(array $id)
    {
        try {
            return $this->repository->updateStatusApprove(array($id));
        } catch (Exception $e) {
            throw $e;
        }
    }

    function welcomeEmailToUser(mixed $request) {
        $data = [];
        $data['email'] = $request->email;
        $data['subject'] = 'Welcome Email';
        $data['username'] = $request->mobile;
        $data['password'] = $request->password;
        $data['link'] = '/login';
        return SendMail::dispatch($data);
    }

    function passwordChangedEmailToUser(mixed $request) {
        $data = [];
        $data['email'] = $request->email;
        $data['subject'] = 'Password Change Confirmation';
        $data['name'] = $request->name;
        return PasswordReset::dispatch($data);
    }


    /**
     * Creates a new Commodity in the DB
     *
     * @param Request $data
     *
     * @return Array
     */
    public function S3ImageUpload(Request $request)
    {
        try {
            if(!empty($request->file('profilePhoto'))){
                $path = $request->file('profilePhoto')->store('/dev/master-data/uapp_user_kyc', 's3');
                return Storage::cloud()->url($path);
            }
            return;
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    public function S3ImageDelete(int $id)
    {
        try {
            $user = $this->repository->findById($id);
            $imgUrl = parse_url($user->UserKyc->profile_photo);
            Storage::disk('s3')->delete($imgUrl['path']);
            return true;
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }
}
