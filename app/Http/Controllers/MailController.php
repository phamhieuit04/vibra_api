<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\FileHelper;
use App\Mail\SendAppreciation;
use App\Mail\SendGreeting;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendVerify(Request $request)
    {
        $params = $request->all();
        try {
            event(new Registered(User::find($params['id'])));

            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }

    public function verifyHandler($id, $hash)
    {
        $user = User::findOrFail($id);
        if (hash_equals($hash, sha1($user->getEmailForVerification()))) {
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();

                return redirect()->away('http://localhost:5173/verify-done');
            }

            return ApiResponse::unprocessableEntity();
        }

        return ApiResponse::internalServerError();
    }

    public function sendGreeting()
    {
        try {
            Mail::to(Auth::user())->send(new SendGreeting);

            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }

    public function sendAppreciation(Request $request)
    {
        $params = $request->all();
        $bill = Bill::findOrFail($params['id']);
        $items = collect([]);
        try {
            if (is_null($bill->playlist_id)) {
                $song = Bill::where('bills.id', $bill->id)
                    ->join('bill_details', 'bills.id', 'bill_details.bill_id')
                    ->join('songs', 'songs.id', 'bill_details.song_id')
                    ->join('users', 'songs.author_id', 'users.id')
                    ->select(['songs.id', 'songs.name', 'songs.price', 'users.email as email'])
                    ->first();
                $items->push([
                    'id'       => $song->id,
                    'name'     => $song->name,
                    'quantity' => 1,
                    'price'    => $song->price,
                    'path'     => public_path('uploads/' . FileHelper::getNameFromEmail($song) . '/songs/' . $song->name . '.mp3')
                ]);
            } else {
                Bill::where('bills.id', $bill->id)
                    ->join('playlists', 'bills.playlist_id', 'playlists.id')
                    ->join('songs', 'songs.playlist_id', 'playlists.id')
                    ->join('users', 'songs.author_id', 'users.id')
                    ->select(['songs.id', 'songs.name', 'songs.price', 'users.email as email'])
                    ->get()->each(function ($song) use ($items) {
                        $items->push([
                            'id'       => $song->id,
                            'name'     => $song->name,
                            'quantity' => 1,
                            'price'    => $song->price,
                            'path'     => public_path('uploads/' . FileHelper::getNameFromEmail($song) . '/songs/' . $song->name . '.mp3')
                        ]);
                    });
            }
            $totalPrice = 0;
            foreach ($items as $item) {
                $totalPrice += $item['price'];
            }
            Mail::to(Auth::user())->send(new SendAppreciation($items, $totalPrice));

            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }
}
