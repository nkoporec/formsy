<?php

namespace App\Http\Controllers;

use App\Repositories\FormEventRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserSettingsController extends Controller
{

    /** @var \App\Repositories\UserRepository */
    protected $userRepository;

    /** @var \App\Repositories\FormEventRepository */
    protected $formEventRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, FormEventRepository $formEventRepository)
    {
        $this->middleware('auth');
        $this->userRepository = $userRepository;
        $this->formEventRepository = $formEventRepository;
    }

    /**
     * Show user settings.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function userSettings()
    {
        return view('user', [
            'user' => $this->userRepository->get(),
        ]);
    }

    /**
     * Update user settings.
     *
     * @param \Illuminate\Http\Request $request
     *   Request.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request)
    {
        // Get current user.
        $account = $this->userRepository->get();

        // Get submited data.
        $submittedData = array_filter($request->all());

        // If there is no data then abort.
        if (!$submittedData) {
            notify()->error('Something went wrong, please try again later.');
            return redirect()->back();
        }

        $this->formEventRepository->create(
            null,
            Auth::id(),
            'info',
            "User settings has been updated.",
        );

        // Update name or email.
        if (isset($submittedData['name']) || isset($submittedData['email'])) {
            if ($submittedData['name'] != $account->name || $submittedData['email'] != $account->email) {
                // @todo: Add unique email validator.
                $account = $this->userRepository->update([
                    'name' => $submittedData['name'],
                    'email' => $submittedData['email']
                ]);

                notify()->success('Account has been successfully updated.');
                return redirect()->back();
            }
        }

        if (!isset($submittedData['password']) || !isset($submittedData['current-password'])) {
            notify()->error('Please enter current password.');
            return redirect()->back();
        }

        // Validate the current password.
        if (!$this->validatePassword($request)) {
            notify()->error('Password does not match.');
            return redirect()->back();
        }

        $account = $this->userRepository->update([
            'password' => Hash::make($request->get('password')),
        ]);

        notify()->error('Password has been successfully updated.');
        return redirect()->back();
    }

    /**
     * Validate the new password.
     *
     * @param \Illuminate\Http\Request $request
     *   Request.
     *
     * @return bool
     */
    protected function validatePassword(Request $request)
    {
        // Validate the current password.
        $request->validate([
            'current-password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if the current password match.
        $currentPassword = $this->userRepository->get()->password;
        if (!Hash::check($request->get('current-password'), $currentPassword)) {
            $request->session()->flash('error', 'Current password is not correct.');
            return false;
        }

        return true;
    }
}
