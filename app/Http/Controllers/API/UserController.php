<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

;

class UserController extends Controller
{
    public function register (Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'npm' => 'sometimes|string|max:255',
                'tgl_lahir' => 'sometimes|string|max:255',
                'gender' => 'sometimes|string',
                'phone' => 'sometimes|string|max:255',
                'role' => 'sometimes|string',
                'alamat' => 'sometimes|string',
                'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
                'status' => 'sometimes|string|max:255'
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'npm' => $request->npm,
                'tgl_lahir' => $request->tgl_lahir,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'role' => $request->role,
                'alamat' => $request->alamat,
                'status' => $request->status,
            ]);


            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormmater::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Register Succcess');
        }

        catch (Exception $error)
        {
            return ResponseFormmater::error([
                'message' => 'Register Failed',
                'error' => $error,
            ], 'Register Failed', 404);
        }
    }

    public function login (Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = request(['email', 'password']);

            if(!Auth::attempt($credentials))
            {
                return ResponseFormmater::error([
                    'message' => 'Unauthorized'
                ], 'Unauthorized Failed', 404);
            }

            $user = User::where('email', $request->email)->first();

            if(!Hash::check($request->password, $user->password, []))
            {
                 throw new \Exception('Password is incorrect');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormmater::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Login Success');
        }
        catch(Exception $error)
        {
            return ResponseFormmater::error([
                'message' => 'Login Failed',
                'error' => $error
            ], 'Login Failed', 404);
        }
    }

    public function getUser (Request $request, $id)
    {
        $user = User::findOrFail($id);
        return ResponseFormmater::success(
            $user,
            'Data User berhasil diambil'
        );
    }

    public function logout (Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormmater::success($token, 'Logout Berhasil');
    }


    public function updateUser (Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->all();

        if($request->hasFile('avatar'))
        {
            if($request->file('avatar')->isValid())
            {
                Storage::disk('upload')->delete($request->avatar);
                $avatar = $request->file('avatar');
                $extensions = $avatar->getClientOriginalExtension();
                $userAvatar = "user-avatar/".date('YmdHis').".".$extensions;
                $uploadPath = env('UPLOAD_PATH'). "/user-avatar";
                $request->file('avatar')->move($uploadPath, $userAvatar);
                $data['avatar'] = $userAvatar;
            }
        }

        $user->update($data);
        return ResponseFormmater::success(
            $user,
            'Update Success'
        );
    }


    public function userList (Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $role = $request->input('role');
        $status = $request->input('status');

        if($id)
        {
            $user = User::find($id);

            if($user)
            {
                return ResponseFormmater::success(
                    $user,
                    'User berhasil diambil'
                );
            }

            else {
                return ResponseFormmater::error(
                    null,
                    'User Gagal diambil',
                    404
                );
            }
        }

        $user = User::query();

        if($name)
        {
            $user->where('name', 'like', '%' . $name . '%');
        }

        if($role)
        {
            $user->where('role', 'like', '%' . $role . '%');
        }

        if($status)
        {
            $user->where('status', 'like', '%' . $status . '%');
        }

        return ResponseFormmater::success(
            $user->get(),
            'List User Berhasil diambil'
        );
    }

    public function deleteUser (Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->all();
        Storage::disk('upload')->delete($request->avatar);
        $user->delete($data);
        return ResponseFormmater::success(
            $user,
            'Data User Berhasil di delete'
        );
    }
}
