<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('login');
        }

        $pharmacyId = Auth::guard('pharmacien')->user()->pharmacy_id;
        $noteFilter = $request->get('note');

        // Récupérer les avis avec les infos de l'utilisateur
        $query = DB::table('review')
            ->leftJoin('users_pharma', 'review.username', '=', 'users_pharma.username')
            ->where('review.pharmacy_id', $pharmacyId)
            ->select(
                'review.id_review',
                'review.commentaire',
                'review.evaluation',
                'review.username',
                'review.created_at',
                'users_pharma.first_name',
                'users_pharma.last_name',
                'users_pharma.profile_picture'
            )
            ->orderBy('review.created_at', 'desc');

        if ($noteFilter) {
            $query->where('review.evaluation', $noteFilter);
        }

        $reviews = $query->paginate(10)->withQueryString();

        // Calcul du résumé des notes (toujours sur tous les avis, sans filtre)
        $allReviews = DB::table('review')->where('pharmacy_id', $pharmacyId)->get();

        $ratingSummary = (object) [
            'counter'           => $allReviews->count(),
            'average'           => $allReviews->count() > 0 ? round($allReviews->avg('evaluation'), 1) : 0,
            'counterFiveStars'  => $allReviews->where('evaluation', 5)->count(),
            'counterFourStars'  => $allReviews->where('evaluation', 4)->count(),
            'counterThreeStars' => $allReviews->where('evaluation', 3)->count(),
            'counterTwoStars'   => $allReviews->where('evaluation', 2)->count(),
            'counterOneStars'   => $allReviews->where('evaluation', 1)->count(),
        ];
        return view('pharmacies.reviews', compact('reviews', 'ratingSummary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
