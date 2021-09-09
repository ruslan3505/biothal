<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use App\Models\{Admin\Products\GlobalSales,
    Admin\Products\GroupSales,
    User,
    Order,
    Region,
    Cart_Product,
    OrderHistory,
    ShoppingCart,
    OrderStatuses};
use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\Admin\Orders\OrderPageRequest;
use App\Models\UserOrderAddress;

class OrdersController extends Controller
{
    public function index()
    {
        $order_statuses = OrderStatuses::all()->toArray();
        return view('admin.orders.orders', [
            'order_statuses' => $order_statuses
        ]);
    }

    public function viewOrders($id, Request $request) {
        $order_statuses = [];
        $_statuses = OrderStatuses::all()->toArray();
        foreach ($_statuses as $status) {
            $order_statuses[$status['id']] = OrderStatuses::STATUS[$status['name']];
        }

        $order = Order::where('id', $id)->with([
            'userAddress',
            'productHistory',
            'orderStatus',
            'orderType',
            'shoppingCart',
            'products',
            'user'
        ])->first()->toArray();


        switch ($order['sale_type']) {
            case Order::GLOBAL_SALES:
                $sale = GlobalSales::where('id', $order['sale_id'])->first();
                break;
            case Order::GROUP_SALES:
                $sale = GroupSales::where('id', $order['sale_id'])->first();
                break;
            default: $sale = [];
        }

        $order_history = OrderHistory::where('order_id', $id)->get();

        if (empty($order)) {
            return response()->json([], 404);
        }

        $products = [];
        $registered_user = null;
        if (!empty($order['shopping_cart'])) {
            $products = Cart_Product::where('cart_products.cart_id', $order['shopping_cart']['id'])
                ->join('products', 'cart_products.product_id', '=', 'products.id')->get()->toArray();
            if (!empty($order['shopping_cart']['user_id'])) {
                $registered_user = User::where('id', $order['shopping_cart']['user_id'])->first();
            }
        }
        $registered_user = UserOrderAddress::where('id', $order['user_order_id'])->first();

        if(!empty($order['user'])){
            $registered_user['type'] = 'Зарегистрированный';
            $registered_user['email'] = $order['user']['email'];
        }
        if(!empty($order['products'])){
            $products = $order['products'];
        }

        if (!empty($order['user_address'])) {
            $order['user_address']['region_name'] = Region::where('id', )->value('region');
            $order['total_price'] = 0;
        }

        $totalPrice = 0;
        $totalSalesPrice = 0;
        $totalProductPrice = 0;
        if(!empty($order['products'])){
            foreach($order['products'] as $product){
                $totalProductPrice += ($product['price'] * $product['quantity']);
                if($product['is_sales']){
                    $totalPrice += ($product['price_with_sales'] * $product['quantity']);
                    $totalSalesPrice += ($product['price'] - $product['price_with_sales']);
                } else {
                    $totalPrice += ($product['price'] * $product['quantity']);
                }
            }
        }

        return view('admin.orders.viewOrders', compact('products',
            'totalPrice',
            'id',
            'order',
            'order_history',
            'order_statuses',
            'registered_user',
            'totalProductPrice',
            'totalSalesPrice',
            'sale'
        ));
    }


    public function saveHistory (Request $request) {
        OrderHistory::create([
            'order_id' => $request->input('order_id'),
            'notify' => ($request->input('notify') === 'true') ? 1 : 0,
            'comment' => $request->input('comment') ?? '',
            'status_id' => $request->input('status'),
        ]);

        Order::where('id', $request->input('order_id'))
            ->update(['order_status_id' => $request->input('status')]);

        return response()->json(['success' => true]);
    }

    public function sortOrdersTable(OrderPageRequest $request) {

        $orders = Order::with([
            'userAddress',
            'productHistory',
            'orderStatus',
            'shoppingCart',
            'products'
        ])->orderBy('created_at', 'desc');

        if (!empty($request->filters)) {
            foreach ($request->filters as $key => $filter) {
                if (!empty($filter)) {
                    switch ($key) {
                        case 'filter_order_id':
                            $orders = $orders->where('user_order_id','like', '%' . $filter . '%');
                            break;
                        case 'filter_client':
                            $filter_customer = !str_contains( 'N/a', $filter) ? $filter : null;
                            $orders = $orders->whereHas('userAddress', function ($query) use ($filter_customer) {
                                $query->where('full_name', $filter_customer);
                            });
                            break;
                        case 'filter_order_status':
                            $orders = $orders->where('order_status_id', $filter);
                            break;
                        case 'filter_total':
                            $orders = $orders->where('total_sum', $filter);
                            break;
                        case 'filter_date_added':
                            $date = Carbon::parse($filter)->format('Y-m-d');
                            $orders = $orders->where('created_at','like', '%' . $date . '%' );
                            break;
                        case 'filter_date_modified':
                            $date = Carbon::parse($filter)->format('Y-m-d');
                            $orders = $orders->where('updated_at', 'like', '%' . $date . '%' );
                            break;
                    }
                }
            }
        }

        $orders = $orders->limit(100)->get()->toArray();
        foreach ($orders as $order_key => $order) {
            $orders[$order_key]['total_price'] = 0;
            if(!empty($order['products'])){
                foreach($order['products'] as $product){
                    if($product['is_sales']){
                        $orders[$order_key]['total_price'] += ($product['price_with_sales'] * $product['quantity']);
                    } else {
                        $orders[$order_key]['total_price'] += ($product['price'] * $product['quantity']);
                    }
                }
            }
        }

        if ($request->ajax()) {
            return Datatables::of($orders)
                ->editColumn('', function ($row) {
                    return $row['id'];
                })
                ->editColumn('name', function ($row) {

                    return $row['user_address']['full_name'] ? $row['user_address']['full_name'] : "\"N/a\"";
                })
                ->editColumn('order_status', function ($row) {
                    $name_status = '';
                    if($row['order_status']['name'] == 'active') {
                        $name_status = 'Закупка';
                    }
                    elseif($row['order_status']['name'] == 'payment_process') {
                        $name_status = 'В процессе оплаты';
                    }
                    elseif($row['order_status']['name'] == 'shipping_process') {
                        $name_status = 'Отправленна получателю';
                        }
                    elseif($row['order_status']['name'] == 'finish') {
                        $name_status = 'Получена';
                    }
                    elseif($row['order_status']['name'] == 'pre_order') {
                        $name_status = 'Предзаказ';
                    }
                    elseif($row['order_status']['name'] == 'paid') {
                        $name_status = 'Оплачен';
                    }
                    elseif($row['order_status']['name'] == 'cancel'){
                        $name_status = 'Отменен';
                    }
                    elseif($row['order_status']['name'] == 'unfinished') {
                        $name_status = 'Не закончен';
                    }
                    return $name_status;
                })
                ->editColumn('total_sum', function ($row) {
                    return $row['total_sum'];
                })
                ->editColumn('created_at', function ($row) {
                    $row['created_at'] = Carbon::parse($row['created_at'])->format('Y-m-d');
                    return $row['created_at'];
                })
                ->editColumn('updated_at', function ($row) {
                    $row['updated_at'] = Carbon::parse($row['updated_at'])->format('Y-m-d');
                    return $row['updated_at'];
                })
                ->addColumn('change', function ($row) {
                    return '<button type="button" class="btn btn-info fa fa-eye button_href_to_details" id="button_details_'.$row['id'].'"></button>';
                })
                ->rawColumns(['change'])
                ->addIndexColumn()
                ->make(true);
        }

    }
}
