<?php

namespace App\Http\Controllers;

use App\Repositories\FormRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{

    /** @var \App\Repositories\FormRepository */
    protected $formRepository;

    /** @var \App\Repositories\UserRepository */
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FormRepository $formRepository, UserRepository $userRepository)
    {
        $this->middleware('auth');
        $this->formRepository = $formRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $forms = $this->formRepository->getAll(Auth::id());

        return view('home', [
            'forms' => $forms,
        ]);
    }
}
