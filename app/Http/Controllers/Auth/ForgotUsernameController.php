<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Recipient;
use Illuminate\Http\Request;

class ForgotUsernameController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('throttle:3,1')->only('sendReminderEmail');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('auth.usernames.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendReminderEmail(Request $request)
    {
        $this->validateEmail($request);

        $recipient = Recipient::all()->where('email', $request->email)->first();

        if (isset($recipient->user)) {
            $recipient->user->sendUsernameReminderNotification();
        }

        return back()->with('status', 'A reminder has been sent if that email exists.');
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email:rfc,dns']);
    }
}