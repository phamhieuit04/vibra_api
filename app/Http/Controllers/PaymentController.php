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
            $res = PayOSService::createPaymentLink($newBill);
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
}