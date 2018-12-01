<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasRoles, Notifiable;

    /**
     * Undocumented variable
     *
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['scope'];

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getScopeAttribute()
    {
        return $this->roles()->first()['name'];
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail($this->generatePassword()));
    }

    public function sendEmailVerification($password)
    {
        $this->notify(new VerifyEmail($password));
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function generatePassword()
    {
        $password = Str::random();

        $this->forceFill([
            'password' => Hash::make($password),
        ])->save();

        return $password;
    }

    /**
     * Undocumented function
     *
     * @param [type] $query
     * @param [type] $request
     * @return void
     */
    public function scopeFilterOn($query, $request)
    {
        $sortaz = $request->descending === 'true' ? 'desc' : 'asc';
        $sortby = $request->has('sortBy') ? $request->sortBy : null;
        $filter = $request->has('filter') ? $request->filter : null;

        $mixquery = $query;

        if ($filter) {
            $mixquery = $mixquery->where('name', 'like', "%{$filter}%");
        }

        if ($sortby) {
            $mixquery = $mixquery->orderBy($sortby, $sortaz);
        }

        return $mixquery;
    }

    /**
     * Store
     */
    public static function storeRecord($request)
    {
        DB::beginTransaction();

        try {
            $password = Str::random();

            $model = new static;
            $model->password = $password;
            $model->name = $request->name;
            $model->email = $request->email;
            $model->save();

            $model->assignRole($request->scope);

            $model->sendEmailVerification($password);

            DB::commit();

            return new UserResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Update
     */
    public static function updateRecord($request, $model)
    {
        DB::beginTransaction();

        try {
            $model->name = $request->name;
            $model->email = $request->email;
            $model->save();

            $model->assignRole($request->scope);

            DB::commit();

            return new UserResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Destroy
     */
    public static function destroyRecord($model)
    {
        DB::beginTransaction();

        try {
            $model->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }
}
