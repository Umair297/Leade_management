<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{

    public function index()
    {
        $users = User::orderBy('created_at', 'DESC')->get();
        return view('users.list', compact('users'));
    }
    

   // Rest of the methods remain the same

   public function create()
   {
       return view('users.create');
   }
   

   public function store(Request $request)
   {
       $messages = [
        'email.unique' => 'Aap ki Gmail address pehle se hi register hai. Kripya koi aur Gmail address enter karein.',
    ];
    
       $rules = [
           'name' => 'required',
           'email' => 'required',
           'password' => 'required',
           'phone1' => 'required',
           'role' => 'required',
       ];
   
       $validator = Validator::make($request->all(), $rules, $messages);
   
       if ($validator->fails()) {
           return redirect()->route('users.create')->withInput()->withErrors($validator);
       }
   
       $user= new User();
       $user->name = $request->name;
       $user->email = $request->email;
       $user->password = bcrypt($request->password);
       $user->phone1 = $request->phone1;
       $user->role = $request->role;
   
       // Check if image is uploaded, else set a default value
       if ($request->hasFile('image')) {
           $image = $request->file('image');
           $ext = $image->getClientOriginalExtension();
           $imageName = time() . '.' . $ext;
           $image->move(public_path('uploads/users'), $imageName);
           $user->image = $imageName;
       } else {
           $user->image = null; // Set to null if no image is provided
       }
   
       $user->save();
   
return redirect()->route('users.index')->with('success', 'User created successfully!');
   }
   public function edit($id)
   {
       $user= User::findOrFail($id);
       return view('users.list', [
           'employe' => $user
       ]);
   }
   

  public function update(Request $request, User $user)
{
    // Validate the request
    $request->validate([
        'name' => 'required',
        'email' => 'required',
        'phone1' => 'nullable',
        'role' => 'required',
        'image' => 'nullable',
        'password' => 'nullable', // Password validation (optional)
    ]);

    // Update basic fields
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone1 = $request->phone1;

    // Update password if provided
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password); // Hash the new password
    }

    // Handle image upload if provided
    if ($request->hasFile('image')) {
        $imagePath = 'uploads/users/' . $user->image;
        if (File::exists($imagePath)) {
            File::delete($imagePath); // Delete old image if it exists
        }

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/users'), $imageName);
        $user->image = $imageName;
    }

    // Save the user record
    $user->save();

    // Redirect back with a success message
    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}
   public function destroy($id)
   {
       $user = User::findOrFail($id);
   
       // Delete user image if it exists
       if ($user->image) {
           $imagePath = public_path('uploads/users/' . $user->image);
           if (File::exists($imagePath)) {
               File::delete($imagePath);
           }
       }
   
       // Delete the user
       $user->delete();
   
       return redirect()->route('users.index')->with('success', 'User deleted successfully!');
   }
   
}