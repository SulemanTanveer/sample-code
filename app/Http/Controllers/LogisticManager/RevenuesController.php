<?php

namespace App\Http\Controllers\LogisticManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use Carbon\Carbon;

class RevenuesController extends Controller
{
	/**
     * [orderGraph weekly]
     * @return [type] [array]
     */
    public function orderGraph()
    {
        $data['data'] = Order::orderByWeek($this->weeks());
        return $data;
    }

    protected  function weeks($range = 5)
    {
        return Carbon::now()->subWeeks($range);   
    }
}
