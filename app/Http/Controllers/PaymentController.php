<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Bill;
use App\Models\BillDetail;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use App\Services\PayOSService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    public function createBill(Request $request)
    {
        $params = $request->all();
        $now = Carbon::now();

        try {
            $newBill = Bill::create([
                'user_id' => Auth::user()->id,
                'order_code' => intval(substr(strval(microtime(true) * 10000), -6)),
                'playlist_id' => isset($params['playlist_id']) ? $params['playlist_id'] : null,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            // Check if user buy a playlist or a song only
            if (isset($params['playlist_id'])) {
                // find songs in playlist
                $songs = Song::where('playlist_id', $params['playlist_id'])
                    ->with('playlist')
                    ->get();
                // create bill details for each song
                foreach ($songs as $song) {
                    BillDetail::insert([
                        'bill_id' => $newBill->id,
                        'song_id' => $song->id,
                        'created_at' => $now,
                        'updated_at' => $now
                    ]);
                }
                $playlist = Playlist::find($params['playlist_id']);
                $newBill->total_price = $playlist->price;
            } else {
                // create bill detail for one song
                BillDetail::insert([
                    'bill_id' => $newBill->id,
                    'song_id' => $params['song_id'],
                    'created_at' => $now,
                    'updated_at' => $now
                ]);
                $song = Song::find($params['song_id']);
                $newBill->total_price = $song->price;
            }

            $items = [];
            if ($newBill->playlist_id == null) {
                $songs = Bill::where('bills.id', $newBill->id)
                    ->join('bill_details', 'bills.id', 'bill_details.bill_id')
                    ->join('songs', 'songs.id', 'bill_details.song_id')
                    ->first();
                $items = [
                    [
                        'name' => $songs->name,
                        'quantity' => 1,
                        'price' => $songs->price
                    ]
                ];
            } else {
                $songs = Bill::where('bills.id', $newBill->id)
                    ->join('playlists', 'bills.playlist_id', 'playlists.id')
                    ->join('songs', 'songs.playlist_id', 'playlists.id')
                    ->get();
                foreach ($songs as $song) {
                    array_push($items, [
                        'name' => $song->name,
                        'quantity' => 1,
                        'price' => $song->price
                    ]);
                }
            }
            $res = PayOSService::createPaymentLink($newBill, $items);
            if (!is_null($res)) {
                $newBill->checkout_url = $res['checkoutUrl'];
                return ApiResponse::success($newBill);
            } else {
                return ApiResponse::dataNotfound();
            }
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $bill = Bill::find($id);
            $bill->status = 2;
            $bill->touch();
            return ApiResponse::success();
        } catch (\Throwable $th) {
            return ApiResponse::internalServerError();
        }
    }
}