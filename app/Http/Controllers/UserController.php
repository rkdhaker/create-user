<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Role};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    // Function to handle the main user listing page (AJAX data retrieval)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Fetch users ordered by ID in descending order
            $users = User::orderBy('id', 'desc')->get();

            // Return a DataTable response
            return DataTables::of($users)
                ->addColumn('user', function($row) {
                    // Prepare the user card HTML with profile image and details
                    $userCard = '<div class="user_card">';
                    $userCard .= '<span class="user_img"><img src="'.asset('storage/'.$row->profile_image).'" alt="Profile Image"></span>';
                    $userCard .= '<ul class="user_caption">';
                    $userCard .= '<li>'.$row->name.'</li>';
                    $userCard .= '<li>'.$row->email ?? 'N/A'.'</li>';
                    $userCard .= '<li>'.$row->phone ?? 'N/A'.'</li>';
                    $userCard .= '</ul>';
                    $userCard .= '</div>';
                    return $userCard;
                })
                ->addColumn('role', function($row) {
                    // Return the associated role name
                    return $row->getRole ? $row->getRole->name : 'N/A';
                })
                ->addColumn('date', function($row) {
                    // Format and return the user creation date
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->rawColumns(['user', 'role', 'date'])
                ->make(true);
        }
    }

    // Show the form to create a new user with available roles
    public function create(Request $request)
    {
        // Fetch roles that are active
        $roles = Role::where('status', 1)->get();

        // Return the user creation view with roles data
        return view('user.create', compact('roles'));
    }

    // Handle the form submission for creating a user
    public function submitUser(Request $request)
    {
        // Validate the incoming request data
        $valid = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone'         => ['required', 'regex:/^[789]\d{9}$/'],
            'description'   => 'required|string',
            'role_id'       => 'required|exists:roles,id', 
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        // If validation fails, return errors in JSON format
        if ($valid->fails()) {
            return response()->json(['status' => false, 'errors' => $valid->errors()->first()]);
        }

        // Start a database transaction to ensure data integrity
        DB::beginTransaction();
        try {
            // Handle file upload for profile image (if provided)
            $profileImagePath = null;
            if ($request->hasFile('profile_image')) {
                $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
            }

            // Create the new user in the database
            $savedUser = User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'description'   => $request->description,
                'role_id'       => $request->role_id,
                'profile_image' => $profileImagePath,
            ]);

            // Commit the transaction if everything is successful
            DB::commit();

            // Return a success response
            return response()->json([
                'status'  => true,
                'message' => 'User created successfully'
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            // Return an error response
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
