<?php

namespace App\Http\Controllers;

use Xendit\Xendit;
use App\Models\Bootcamp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MemberTransaction;
use App\Models\XenditTransaction;
use Illuminate\Support\Facades\Auth;
use DB;

class HomeController extends Controller
{
    public function __construct()
    {
        Xendit::setApiKey(env('XENDIT_API_KEY'));
    }

    public function index()
    {
        $bootcamps = Bootcamp::orderBy('id', 'desc')->get();
        return view('list', ['bootcamps' => $bootcamps]);
    }

    public function checkout($bootcampID)
    {

        $memberTransaction = MemberTransaction::with('xendit')
            ->where([
                'member_id' => Auth::id(),
                'bootcamp_id' => $bootcampID
            ])
            ->first();

        $checkkout = false;
        if ($memberTransaction && $memberTransaction->status != MemberTransaction::PAYMENT_STATUS_EXPIRED) {
            $checkkout = true;
        }

        $bootcamp = Bootcamp::where('id', $bootcampID)->first();
        if (!$bootcamp) {
            return redirect()->back('danger', "Data tidak ditemukan");
        }


        $bootcamp->ppn = 0.11 * $bootcamp->price;
        $bootcamp->total = $bootcamp->ppn + $bootcamp->price;

        if ($checkkout == true) {
            return to_route('detail', $memberTransaction->transaction_id);
        }
        return view('checkout', ['bootcamp' => $bootcamp]);
    }

    public function invoice(Request $request, $bootcampID)
    {
        $memberTransaction = MemberTransaction::where([
            'member_id' => Auth::id(),
            'bootcamp_id' => $bootcampID
        ])
            ->first();

        if ($memberTransaction == MemberTransaction::PAYMENT_STATUS_PENDING) {
            return to_route('detail', $memberTransaction->transaction_id);
        }

        $bootcamp = Bootcamp::where('id', $bootcampID)->first();
        $finalPrice = (0.11 * $bootcamp->price) + $bootcamp->price;
        $external_id = Str::uuid();
        $params = [
            'external_id'       => $external_id,
            'amount'            => intval($finalPrice),
            'description'       => 'Pembayaran ' . $bootcamp->title,
        ];


        $createInvoce = \Xendit\Invoice::create($params);

        DB::beginTransaction();

        $transaction = new MemberTransaction();
        $transaction->transaction_id = $external_id;
        $transaction->member_id = Auth::id();
        $transaction->bootcamp_id = $bootcampID;
        $transaction->price = $bootcamp->price;
        $transaction->final_price = $finalPrice;
        $transaction->status = MemberTransaction::PAYMENT_STATUS_PENDING;
        $transaction->save();

        $xendit = new XenditTransaction();
        $xendit->id = $createInvoce['id'];
        $xendit->external_id = $external_id;
        $xendit->invoice_url = $createInvoce['invoice_url'];
        $xendit->description = $createInvoce['description'];
        $xendit->save();

        DB::commit();

        dd($createInvoce);
        // return redirect($createInvoce['invoice_url']);
    }

    public function actCheckout(Request $request, $bootcampID)
    {
        $memberTransaction = MemberTransaction::where([
            'member_id' => Auth::id(),
            'bootcamp_id' => $bootcampID
        ])
            ->first();

        if ($memberTransaction == MemberTransaction::PAYMENT_STATUS_PENDING) {
            return to_route('detail', $memberTransaction->transaction_id);
        }

        if ($memberTransaction == MemberTransaction::PAYMENT_STATUS_ACCEPT) {
            return to_route('bootcamps')->with('danger', 'Kamu sudah membeli bootcamp ini');
        }

        // validate bootcamp
        $bootcamp = Bootcamp::with('member')->where('id', $bootcampID)->first();
        if (!$bootcamp) {
            return redirect()->back()->with('danger', 'Bootcamp tidak ditemukan');
        }
        $finalPrice = (0.11 * $bootcamp->price) + $bootcamp->price;

        $uuidna = Str::uuid();
        $params = [
            'external_id'       => $uuidna,
            'amount'            => intval($finalPrice),
            'description'       => 'Pembayaran ' . $bootcamp->title,
        ];

        $createInvoce = \Xendit\Invoice::create($params);
        $tgl = date("Y-m-d H:i:s", strtotime($createInvoce['expiry_date']));

        try {
            DB::beginTransaction();

            $transaction = new MemberTransaction();
            $transaction->transaction_id = $uuidna;
            $transaction->member_id = Auth::id();
            $transaction->bootcamp_id = $bootcampID;
            $transaction->price = $bootcamp->price;
            $transaction->final_price = $finalPrice;
            $transaction->status = MemberTransaction::PAYMENT_STATUS_PENDING;
            $transaction->save();

            $xendit = new XenditTransaction();
            $xendit->id = $createInvoce['id'];
            $xendit->external_id = $uuidna;
            $xendit->invoice_url = $createInvoce['invoice_url'];
            $xendit->description = $createInvoce['description'];
            $xendit->expiry_date = $tgl;
            $xendit->save();

            DB::commit();
            return to_route('detail', $transaction->transaction_id);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info('transaction');
            Log::error($th);
            return to_route('bootcamps');
        }
    }

    public function detail($bootcampTransactionID)
    {
        // $memberTransaction = $this->transactionService->detail(Auth::id(), $bootcampTransactionID);
        $memberTransaction = MemberTransaction::with(['xendit', 'bootcamp'])->where([
            'member_id' => Auth::id(),
            'transaction_id' => $bootcampTransactionID
        ])
            ->first();

        if (!$memberTransaction) {
            return to_route('bootcamps');
        }
        return view('detail', ['memberTransaction' => $memberTransaction]);
    }
}
