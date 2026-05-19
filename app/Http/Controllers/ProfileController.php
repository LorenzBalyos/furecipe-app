<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        // 1. Validate the form data coming from your me.blade.php modal
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        // 2. Fetch the current user identifier from the active session
        // (Make sure this matches how you store your user's primary key/document ID)
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->back()->withErrors(['error' => 'User session not found.']);
        }

        try {
            // 3. Initialize your Firestore connection
            $firestore = new FirestoreClient([
                'projectId' => config('services.firebase.project_id')
            ]);

            $userRef = $firestore->collection('users')->document($userId);

            // 4. Update the fields directly inside your main database document
            $userRef->update([
                ['path' => 'name', 'value' => $request->input('name')],
                ['path' => 'email', 'value' => $request->input('email')],
                ['path' => 'phone', 'value' => $request->input('phone')]
            ]);

            // 5. CRITICAL: Update the active session values so the changes
            // instantly show up at the top of your me.blade.php view card!
            session([
                'user_name'  => $request->input('name'),
                'user_email' => $request->input('email'),
                'user_phone' => $request->input('phone'),
            ]);

            // 6. Redirect back to the profile tab with a clear success alert banner
            return redirect('/dashboard?tab=me')->with('success', 'Profile database records updated successfully! 🐾');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Database update failed: ' . $e->getMessage()]);
        }
    }
}
