<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Order;
use App\Models\Advertisements;
use App\Models\Artical;
use App\Models\UsersLikes;
use DB;

class DashboardController extends Controller
{
    public function dashboardModern()
    {
        return view('/pages/dashboard-modern');
    }

    public function dashboardEcommerce()
    {
        // navbar large
        $pageConfigs         = ['navbarLarge' => false];
        $userCount           = User::count();
        $todayUsers          = DB::table('users')->whereDate('created_at', DB::raw('CURDATE()'))->count();
        $order               = Order::count();
        $orderAmount         = Order::sum('amount');
        $todayAmount         = DB::table('orders')->whereDate('created_at', DB::raw('CURDATE()'))->sum('amount');
        $todayMatch          = 0;
        $matchCount          = 0;
        $todayOrder          = DB::table('orders')->whereDate('created_at', DB::raw('CURDATE()'))->count();
        $advertisements      = Advertisements::count();
        $todayAdvertisements = DB::table('advertisements')->whereDate('created_at', DB::raw('CURDATE()'))->count();
        $artical             = Artical::count();
        $todayArtical        = DB::table('artical')->whereDate('created_at', DB::raw('CURDATE()'))->count();

        return view('/pages/dashboard-ecommerce', ['pageConfigs' => $pageConfigs, 'userCount' => $userCount, 'order' => $order, 'orderAmount' => '$'.$orderAmount, 'todayUsers' => $todayUsers, 'todayOrder' => $todayOrder,'todayAmount' => $todayAmount, 'matchCount' => $matchCount, 'todayMatch' => $todayMatch,'advertisements' => $advertisements,'todayAdvertisements' => $todayAdvertisements,'artical' => $artical, 'todayArtical' => $todayArtical]);
    }

    public function dashboardAnalytics()
    {
        // navbar large
        $pageConfigs = ['navbarLarge' => false];

        return view('/pages/dashboard-analytics', ['pageConfigs' => $pageConfigs]);
    }
}
