<?php

namespace App\Http\Modules\Authentication\Repositories;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Enums\UserActivityExcludeEnaum;
use Illuminate\Database\QueryException;
use App\Http\Modules\Authentication\Models\User;
use Spatie\FlareClient\Http\Exceptions\NotFound;
use App\Http\Modules\CaseValidation\Models\UserKyc;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Modules\Authentication\Requests\UserRequest;
use App\Http\Modules\Authentication\Models\UserVerification;
use App\Http\Modules\Authentication\Requests\UserPhotoRequest;
use App\Http\Modules\Authentication\Requests\UserProfileRequest;
use App\Http\Modules\CaseValidation\Enums\CaseValidationStatusEnum;
use App\Http\Modules\CaseValidation\Requests\RejectDocumentRequest;
use App\Http\Modules\CaseValidation\Requests\ApproveDocumentRequest;
use App\Http\Modules\Authentication\Requests\UserChangePasswordRequest;
use App\Http\Modules\Authentication\Repositories\RepoInterface\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function add(Request $request)
    {
        try {
            // Create the User
            $User = User::create([
                'mobile' => $request['mobile'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'language_id' => $request['language_id']
            ]);
            // Assign the role to the User
            // $role = Role::findById($request['activity_id']);
            $role = Role::whereIn('id', explode(',', $request['activity_id']))->get();
            // dd($role);
            if ($role) {
                $User->assignRole($role);
            } else {
                throw new NotFound;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $User;
    }

    /**
     * Find Countries and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find($request, int $rowsPerPage = 50)
    {
        $rowsPerPage = $request->size;
        $order = 'desc';
        if (!empty($request->order)) {
            $order = $request->order;
        }
        $orderBy = 'id';
        if (!empty($request->orderBy)) {
            $orderBy = $request->orderBy;
        }
        $request = $request->except(['page', 'size', 'order', 'orderBy']);
        try {
            $userActivityExcludeEnaum = array_column(UserActivityExcludeEnaum::cases(), 'name');
            $activityIds = Role::whereIn('name', $userActivityExcludeEnaum)->get()->pluck('id');
            return User::select('id', 'language_id', 'email', 'mobile', 'status')
                ->when(Arr::has($request, 'name'), function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request['name']}%");
                }, function ($query) use ($request) {
                    $query->where($request);
                })
                ->whereNotIn('id', function ($query) use ($activityIds){
                    $query->select('user_id')
                        ->from('auth_user_activity')
                        ->whereIn('activity_id', $activityIds);
                })
               ->with('Language:id,name','UserActivity.Activity:id,name')
               ->orderBy($orderBy, $order)->paginate($rowsPerPage)
               ->appends(request()->query());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        try {
            return User::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


     /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getProfileFormFields(): array
    {
        try {
            return User::getProfileFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get cached user details by user id
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function findByIdCached(int $userId)
    {
        //Redis::flushDB();
        try {
            $cacheKey = 'user:' . $userId;

            if ($userDetails = Redis::get($cacheKey)) {
                return $userDetails = json_decode($userDetails);
            }

            return $this->findById($userId);

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Find record by id
     * @param int $id
     */
    public function findById(int $id)
    {
        try {
            $userDetails =  User::select('id', 'language_id','email','mobile')
            ->with('UserActivity.Activity:id,name','UserKyc:user_id,full_name,dob,gender,profile_photo')
            ->findOrFail($id);

            $cacheKey = 'user:' . $id;
            Redis::setex($cacheKey, 60*60*2, json_encode($userDetails));

            return $userDetails;

        } catch (ModelNotFoundException $e) {
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
        try {
            $user = User::find($id);
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->language_id = $request->language_id;
            $user->save();
            if (!empty($request['activity_id'])) {
                $role = Role::whereIn('id', explode(',', $request['activity_id']))->get();
                if ($role) {
                    $user->syncRoles($role);
                } else {
                    throw new NotFound;
                }
            }
            return $user;
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
        try {
            $user = User::find($id);
            $user->email = $request->email;
            $user->save();
            if($user){
                UserKyc::updateOrCreate(
                    ['user_id' => $id],
                    [
                        'full_name' => $request->fullName,
                        'dob' => $request->dob,
                        'gender' => $request->gender
                    ]
                );
            }
            return $user;
        } catch (Exception $e) {
            dd($e);
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param UserPhotoRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function photoUpdate(UserPhotoRequest $request, int $id)
    {
        try {
            $user = User::find($id);
            if($user){
                $fileUrl = $request->input('profile_photo');
                $userKyc = UserKyc::where('user_id',$id)->first();
                $userKyc->profile_photo = $fileUrl;
                $userKyc->save();
            }
            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Update the specified resource in db.
     *
     * @param UserChangePasswordRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(UserChangePasswordRequest $request, int $id)
    {
        try {
            $user = User::find($id);
            $user->password = Hash::make($request->newPassword);
            $user->save();
            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of ids from request
     *
     * @return array
     */
    public function updateStatusReject(array $id)
    {
        try {
            User::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
                return User::whereIn("id", $ids)
                    ->update(['Status' => 'REJECTED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of ids from request
     *
     * @return array
     */
    public function updateStatusFinalize(array $id)
    {
        try {
            User::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
                return User::whereIn("id", $ids)
                    ->update(['status' => 'PENDING']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of ids from request
     *
     * @return array
     */
    public function updateStatusApprove(array $id)
    {
        try {
            User::findOrFail(Arr::flatten($id));
            $idcollection = collect($id);

            $idcollection->map(function (array $ids) {
                return User::whereIn("id", $ids)
                    ->update(['status' => 'APPROVED']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }

    //BO: Added By Anjali
    /**
     * reject document.
     * @param RejectDocumentRequest $rejectDocumentRequest
     * @return \Illuminate\Support\Collection
     */
    public function rejectDocument(RejectDocumentRequest $rejectDocumentRequest,int $userId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_FAILED->value;

            $user = User::find($userId);
            $user->status = $status;
            $user->save();

            $userVerification = UserVerification::where('user_id',$userId)->first();
            $userVerification->basic_kyc_status = $status;
            $userVerification->save();

            $updateData = array(
                'status' => $status,
                'rejection_reason_id' => $rejectDocumentRequest->rejectionReasonId
            );

           $user->userDoc()->updateExistingPivot($rejectDocumentRequest->docId,$updateData);

           return Null;

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * approve document.
     * @param RejectDocumentRequest $rejectDocumentRequest
     * @return \Illuminate\Support\Collection
     */
    public function approveDocument(ApproveDocumentRequest $approveDocumentRequest,int $userId)
    {
        try {

            $status = CaseValidationStatusEnum::VALIDATION_DONE->value;

            $user = User::find($userId);
            /*$user->status = $status;
            $user->save();*/

            $updateData = array(
                'status' => $status,
                'rejection_reason_id' => null
            );

           $user->userDoc()->updateExistingPivot($approveDocumentRequest->docId,$updateData);

           return Null;

        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
    //EO: Added By Anjali

    /**
     * Find record by id
     * @param int $id
     */
    public function findByEmail(string $email)
    {
        try {
            return User::select('id', 'name','description')
            ->where('email',$email)
            ->first();
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
