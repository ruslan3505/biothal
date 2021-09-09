<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CheckoutRequest;
use App\Models\Admin\Products\GlobalSales;
use App\Models\Admin\Products\GroupSales;
use App\Models\Admin\Products\Product;
use App\Models\Order;
use App\Models\OrderDeliveryType;
use App\Models\OrderHistory;
use App\Models\OrderProduct;
use App\Models\OrderStatuses;
use App\Models\OrderType;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\UserOrderAddress;
use App\Rules\PhoneValidation;
use App\Services\NovaPoshtaService;
use App\Services\PortmoneService;
use Crypt;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;

class CheckoutController extends Controller
{
    /**
     * @var NovaPoshtaService
     */
    public NovaPoshtaService $novaPoshtaService;


    public PortmoneService $portmoneService;

    /**
     * CheckoutController constructor.
     */
    public function __construct()
    {
        $this->novaPoshtaService = new NovaPoshtaService();
        $this->portmoneService = new PortmoneService();
    }

    public function getRegions()
    {
        $regions = $this->novaPoshtaService->getRegions();

        return response()->json($regions);
    }

    public function getRegionCities(Request $request)
    {
        $region = $request->get('region');

        $cities = $this->novaPoshtaService
            ->getRegionCities($region);

        return response()->json($cities);
    }

    public function getPostalOffices(Request $request)
    {
        $city = $request->get('city');

        $postalOffices = $this->novaPoshtaService
            ->getPostalOffices($city);

        return response()->json($postalOffices);
    }

    public function getPaymentMethods()
    {
        $paymentMethods = OrderType::all();

        return response()->json($paymentMethods);
    }

    public function getDeliveryMethods()
    {
        $deliveryMethods = OrderDeliveryType::all();

        return response()->json($deliveryMethods);
    }

    public function createOrder(Request $request)
    {
        $order = new Order();
        $orderHistory = new OrderHistory();
        $userOrderAddress = new UserOrderAddress();
        $orderOld = null;

        if(!empty($request->unfinished_order_id)) {
            $orderOld = Order::find($request->unfinished_order_id);
            if(!empty($orderOld)) {
                $userOrderAddressOld = UserOrderAddress::find($orderOld->user_order_id);
                $orderProductOld = OrderProduct::where('order_id', $request->unfinished_order_id)->delete();
                $orderHistoryOld = OrderHistory::where('order_id', $request->unfinished_order_id)->first();

                $orderOld->delete();
                $orderHistoryOld->delete();
                $userOrderAddressOld->delete();

                $orderHistory->id = $orderHistoryOld->id;
                $order->id = $request->unfinished_order_id;
                $userOrderAddress->id = $userOrderAddressOld->id;
            }
        }

        $user = User::where([['type', User::CLIENT_TYPE], ['phone_number', $request->get('number')]])->first();

        if ($request->get('deliveryMethod') == 1){
            $postalOffice = (object)$request->get('postalOffice');
        }

        $userOrderAddress->phone = $request->get('number');
        $userOrderAddress->name = !empty($user) ? $user->name : $request->get('name');
        $userOrderAddress->LastName = !empty($user) ? $user->sur_name : $request->get('surname');
        $userOrderAddress->region = $request->get('region');
        $userOrderAddress->cities = $request->get('city');
        $userOrderAddress->department = $request->get('deliveryMethod') == 1 ? $postalOffice->name : $request->get('postalOffice');
        $userOrderAddress->department_number = $request->get('deliveryMethod') == 1 ? $postalOffice->number : null;
        $userOrderAddress->full_name = !empty($user) ? $user->name . ' ' . $user->sur_name : $request->get('name') . ' ' . $request->get('surname');
        $userOrderAddress->is_address_delivery = $request->get('deliveryMethod') == 1 ? false : true;
        $userOrderAddress->not_call = $request->get('notCall') ? true : false;

        $userOrderAddress->save();

        $orderStatus = OrderStatuses::where('name', OrderStatuses::ACTIVE)
            ->first();

        $order->user_order_id = $userOrderAddress->id;
        $order->order_status_id = $orderStatus->id;
        $order->order_type_id = $request->get('paymentMethod');
        $order->user_id = !empty($request->get('user_id')) ? $request->get('user_id') : (!empty($user) ? $user->id : null);
        $order->token = $this->generateRandomString(12);

        $order->save();

        $total = 0;
        $orderProducts = [];
        foreach ($request->get('products') as $product) {
            $quantity = !empty($product['quantity']) ? $product['quantity'] : 1;
            $orderProduct = new OrderProduct();
            $orderProduct->product_id = $product['id'];
            $orderProduct->order_id = $order->id;
            $orderProduct->quantity = $quantity;
            $orderProduct->price = $product['price'];
            $orderProduct->price_with_sales = $product['price_with_sale'];
            $orderProduct->sale_id = $product['sale_id'];
            $orderProduct->is_sales = !empty($product['sale_id']) ? 1 : 0 ;
            $orderProduct->percent = !empty($product['sale_id']) ? $product['get_sale']['percent'] : null;
            $orderProduct->save();

            $orderProducts[] = $orderProduct;
            $total += !empty($product['sale_id']) ? $product['price_with_sale'] * $quantity : $product['price'] * $quantity;
        }
        $globalSale = GlobalSales::where([
            ['sum_modal', '<=', $total],
            ['active', 1]
        ])
            ->orderBy('procent_modal', 'desc')
            ->first();

        $groupSale = GroupSales::where([
            ['sum', '<=', $total],
            ['active', 1]
        ])
            ->orderBy('percent', 'desc')
            ->first();

        if (isset($globalSale)) {
            $order->sale_id = $globalSale->id;
            $order->with_sales = 1;
            $order->sale_type = Order::GLOBAL_SALES;
            $amountPercent = $total / 100 * $globalSale->procent_modal;
            $total = $total - $amountPercent;
        } else {
            if (isset($groupSale)) {
                $order->sale_id = $groupSale->id;
                $order->with_sales = 1;
                $order->sale_type = Order::GROUP_SALES;
                $amountPercent = $total / 100 * $groupSale->percent;
                $total = $total - $amountPercent;
            }
        }
        $order->total_sum = ceil($total);
        $order->save();
        $orderType = OrderType::find($request->get('paymentMethod'));

        $orderStatusHistory = OrderStatuses::where('name', OrderStatuses::ACTIVE)
            ->first();

        $orderHistory->order_id = $order->id;
        $orderHistory->notify = 0;
        $orderHistory->comment = 'Заказ создан';
        $orderHistory->status_id = $orderStatusHistory->id;
        $orderHistory->save();

        if (isset($orderType) and OrderType::CARD_METHOD === $orderType->type) {
            $amount = 0;

            $orderStatus = OrderStatuses::where('name', OrderStatuses::PAYMENT_PROCESS)
                ->first();
            $order->update(['order_status_id' => $orderStatus->id]);

            OrderHistory::create([
                'order_id' => $order->id,
                'notify' => 0,
                'comment' => 'В процессе оплаты',
                'status_id' => $orderStatus->id,
            ]);

            foreach ($orderProducts as $orderProduct) {
                if ($orderProduct->is_sales) {
                    $amount += $orderProduct->price_with_sales;
                } else {
                    $amount += $orderProduct->price;
                }
            }

            $globalSale = GlobalSales::where([
                ['sum_modal', '<=', $total],
                ['active', 1]
            ])
                ->orderBy('procent_modal', 'desc')
                ->first();

            $groupSale = GroupSales::where([
                ['sum', '<=', $total],
                ['active', 1]
            ])
                ->orderBy('percent', 'desc')
                ->first();

            if (isset($globalSale)) {
                $order->sale_id = $globalSale->id;
                $order->with_sales = 1;
                $order->sale_type = Order::GLOBAL_SALES;
                $amountPercent = $amountPercent;
                $amount = $total;
            } else {
                if (isset($groupSale)) {
                    $order->sale_id = $groupSale->id;
                    $order->with_sales = 1;
                    $order->sale_type = Order::GROUP_SALES;
                    $amountPercent = $amountPercent;
                    $amount = $total - $amountPercent;
                }
            }

            $portmoneUrl = $this->portmoneService->makeRequest(
                ceil($amount),
                $order
            );

            return response()->json([
                'message' => 'Заказ оформлен!',
                'portmone' => $portmoneUrl,
                'token' => $order->token
            ]);
        }

        return response()->json([
            'token' => $order->token,
            'order_id' => $order->user_order_id,
            'message' => 'Заказ оформлен!'
        ]);
    }

    public function getOrder($token)
    {
        $order = Order::with('userAddress')->where('token', $token)->first();

        return response()->json([
            'order' => $order,
            'message' => 'Заказ в обработке!'
        ]);
    }

    public function createQuickOrder(Request $request)
    {

        $userOrderAddress = new UserOrderAddress();

        $userId = $request->get('user_id');
        $user = User::where('id', $userId)->first();

        if (!empty($userId) && !empty($user)) {
            $userOrderAddress->phone = $user->phone_number;
            $userOrderAddress->name = $user->name;
            $userOrderAddress->LastName = $user->sur_name;
            $userOrderAddress->full_name = $user->name . ' ' . $user->sur_name;
        } else {
            $userOrderAddress->phone = $request->get('number');
            $userOrderAddress->name = $request->get('name');
        }
        $userOrderAddress->save();

        $orderStatus = OrderStatuses::where('name', OrderStatuses::ACTIVE)
            ->first();

        $order = new Order();
        $order->user_order_id = $userOrderAddress->id;
        $order->order_status_id = $orderStatus->id;
        $order->order_type_id = 1;
        $order->user_id = $request->get('user_id');
        $order->token = $this->generateRandomString(12);
        $order->save();

        $total = 0;
        foreach ($request->get('products') as $product) {

            $quantity = !empty($product['quantity']) ? $product['quantity'] : 1;
            $orderProduct = new OrderProduct();
            $orderProduct->product_id = $product['id'];
            $orderProduct->order_id = $order->id;
            $orderProduct->quantity = $quantity;
            $orderProduct->price = $product['price'];
            $orderProduct->price_with_sales = $product['price_with_sale'];
            $orderProduct->sale_id = $product['sale_id'];
            $orderProduct->is_sales = !empty($product['sale_id']) ? 1 : 0 ;
            $orderProduct->percent = !empty($product['sale_id']) ? $product['get_sale']['percent'] : null;
            $orderProduct->save();

            $total += !empty($product['sale_id']) ? $product['price_with_sale'] *  $quantity: $product['price'] * $quantity;
        }
        $globalSale = GlobalSales::where([
            ['sum_modal', '<=', $total],
            ['active', 1]
        ])
            ->orderBy('procent_modal', 'desc')
            ->first();

        $groupSale = GroupSales::where([
            ['sum', '<=', $total],
            ['active', 1]
        ])
            ->orderBy('percent', 'desc')
            ->first();

        if (isset($globalSale)) {
            $order->sale_id = $globalSale->id;
            $order->with_sales = 1;
            $order->sale_type = Order::GLOBAL_SALES;
            $amountPercent = $total / 100 * $globalSale->procent_modal;
            $total = $total - $amountPercent;
        } else {
            if (isset($groupSale)) {
                $order->sale_id = $groupSale->id;
                $order->with_sales = 1;
                $order->sale_type = Order::GROUP_SALES;
                $amountPercent = $total / 100 * $groupSale->percent;
                $total = $total - $amountPercent;
            }
        }
        $order->total_sum = ceil($total);
        $order->save();

        $orderType = OrderType::find(1);

        OrderHistory::create([
            'order_id' => $order->id,
            'notify' => 0,
            'comment' => 'Заказ создан',
            'status_id' => $orderStatus->id,
        ]);

        return response()->json([
            'token' => $order->token,
            'order_id' => $order->user_order_id,
            'message' => 'Заказ оформлен!'
        ]);
    }

    public function createQuickOrderFromProduct(Request $request)
    {
        $userOrderAddress = new UserOrderAddress();
        $userOrderAddress->phone = $request->get('phone');

        $userId = $request->get('user_id');
        $user = User::where('id', $userId)->first();

        if (!empty($userId) && !empty($user)) {
            $userOrderAddress->name = $user->name;
            $userOrderAddress->LastName = $user->sur_name;
            $userOrderAddress->full_name = $user->name . ' ' . $user->sur_name;
        }

        $userOrderAddress->save();

        $orderStatus = OrderStatuses::where('name', OrderStatuses::ACTIVE)
            ->first();

        $order = new Order();
        $order->user_order_id = $userOrderAddress->id;
        $order->order_status_id = $orderStatus->id;
        $order->order_type_id = 1;
        $order->user_id = $request->get('user_id');
        $order->token = $this->generateRandomString(12);
        $order->save();

        $product = $request->get('product');
        $total = 0;

        $quantity = !empty($product['quantity']) ? $product['quantity'] : 1;
        $orderProduct = new OrderProduct();
        $orderProduct->product_id = $product['id'];
        $orderProduct->order_id = $order->id;
        $orderProduct->quantity = $quantity;
        $orderProduct->price = $product['price'];
        $orderProduct->price_with_sales = $product['price_with_sale'];
        $orderProduct->sale_id = $product['sale_id'];
        $orderProduct->is_sales = !empty($product['sale_id']) ? 1 : 0 ;
        $orderProduct->percent = !empty($product['sale_id']) ? $product['get_sale']['percent'] : null;
        $orderProduct->save();
        $total += !empty($product['sale_id']) ? $product['price_with_sale'] : $product['price'];
        $total = $total * $quantity;
        $globalSale = GlobalSales::where([
            ['sum_modal', '<=', $total],
            ['active', 1]
        ])
            ->orderBy('procent_modal', 'desc')
            ->first();

        $groupSale = GroupSales::where([
            ['sum', '<=', $total],
            ['active', 1]
        ])
            ->orderBy('percent', 'desc')
            ->first();

        if (isset($globalSale)) {
            $order->sale_id = $globalSale->id;
            $order->with_sales = 1;
            $order->sale_type = Order::GLOBAL_SALES;
            $amountPercent = $total / 100 * $globalSale->procent_modal;
            $total = $total - $amountPercent;
        } else {
            if (isset($groupSale)) {
                $order->sale_id = $groupSale->id;
                $order->with_sales = 1;
                $order->sale_type = Order::GROUP_SALES;
                $amountPercent = $total / 100 * $groupSale->percent;
                $total = $total - $amountPercent;
            }
        }
        $order->total_sum = ceil($total);
        $order->save();

        $orderType = OrderType::find(1);

        OrderHistory::create([
            'order_id' => $order->id,
            'notify' => 0,
            'comment' => 'Заказ создан',
            'status_id' => $orderStatus->id,
        ]);

        return response()->json([
            'token' => $order->token,
            'order_id' => $order->user_order_id,
            'message' => 'Заказ оформлен!'
        ]);
    }

    public function createPreOrder(Request $request)
    {
        if (!empty($request->get('user_id'))) {
            $user = User::where('id', $request->get('user_id'))->first();
        } else {
            $user = null;
        }

        $userOrderAddress = new UserOrderAddress();
        $userOrderAddress->phone = $request->get('phone');
        $userOrderAddress->name =  $request->get('name');
        $userOrderAddress->LastName = $user ? $user->sur_name ?? '': null;
        $userOrderAddress->full_name = $user ? $user->name . ' ' . $user->sur_name ?? '' : null;
        $userOrderAddress->save();

        $order = new Order();
        $order->user_order_id = $userOrderAddress->id;
        $order->order_status_id = 5;
        $order->order_type_id = 0;
        $order->user_id = $request->get('user_id');
        $order->token = $this->generateRandomString(12);
        $order->save();

        $product = $request->get('product');

        $orderProduct = new OrderProduct();
        $orderProduct->product_id = $product['id'];
        $orderProduct->order_id = $order->id;
        $orderProduct->quantity = 0;
        $orderProduct->price = 0;
        $orderProduct->price_with_sales = 0;
        $orderProduct->sale_id = $product['sale_id'];
        $orderProduct->is_sales = !empty($product['sale_id']) ? 1 : 0 ;
        $orderProduct->percent = !empty($product['sale_id']) ? $product['get_sale']['percent'] : null;
        $orderProduct->save();

        $order->total_sum = 0;
        $order->save();

        $orderStatus = OrderStatuses::where('name', OrderStatuses::PRE_ORDER)->first();

        OrderHistory::create([
            'order_id' => $order->id,
            'notify' => 0,
            'comment' => 'Предзаказ создан',
            'status_id' => $orderStatus->id,
        ]);

        return response()->json([
            'token' => $order->token,
            'order_id' => $order->user_order_id,
            'message' => 'Предзаказ оформлен!'
        ]);
    }

    public function sendOrderStatus(Request $request)
    {
        $orderId = $request->data;
        if(!empty($orderId)){
            $order = Order::where('id', $orderId)->with('userAddress')->first();
            if(!empty($order->userAddress->email)){
                Mail::to($order->userAddress->email)->send(new OrderCreated($order));
            }
        }
    }

    public function createUnfinishedOrder (Request $request){

        $user = User::where([['type', User::CLIENT_TYPE], ['phone_number', $request->get('number')]])->first();

        $order = new Order();
        $orderHistory = new OrderHistory();
        $userOrderAddress = new UserOrderAddress();
        $orderOld = null;
        if(!empty($request->unfinished_order_id)) {
            $orderOld = Order::find($request->unfinished_order_id);
            if(!empty($orderOld)) {
                $userOrderAddressOld = UserOrderAddress::find($orderOld->user_order_id);
                $orderProductOld = OrderProduct::where('order_id', $request->unfinished_order_id)->delete();
                $orderHistoryOld = OrderHistory::where('order_id', $request->unfinished_order_id)->first();

                $orderOld->delete();
                $orderHistoryOld->delete();
                $userOrderAddressOld->delete();

                $orderHistory->id = $orderHistoryOld->id;
                $order->id = $request->unfinished_order_id;
                $userOrderAddress->id = $userOrderAddressOld->id;
            }
        }

        $userOrderAddress->phone = $request->get('number');
        $userOrderAddress->name = !empty($user) ? $user->name : $request->get('name');
        $userOrderAddress->LastName = !empty($user) ? $user->sur_name : null;

        $userOrderAddress->save();


        $orderStatus = OrderStatuses::where('name', OrderStatuses::UNFINISHED)
            ->first();

        $order->user_order_id = $userOrderAddress->id;
        $order->order_status_id = $orderStatus->id;
        $order->order_type_id = 1;
        $order->user_id = !empty($request->get('user_id')) ? $request->get('user_id') : (!empty($user) ? $user->id : null);
        $order->token = $this->generateRandomString(12);

        $order->save();

        $total = 0;
        $orderProducts = [];

        foreach ($request->get('products') as $product) {

            $quantity = !empty($product['quantity']) ? $product['quantity'] : 1;
            $orderProduct = new OrderProduct();
            $orderProduct->product_id = $product['id'];
            $orderProduct->order_id = $order->id;
            $orderProduct->quantity = $quantity;
            $orderProduct->price = $product['price'];
            $orderProduct->price_with_sales = $product['price_with_sale'];
            $orderProduct->sale_id = $product['sale_id'];
            $orderProduct->is_sales = !empty($product['sale_id']) ? 1 : 0 ;
            $orderProduct->percent = !empty($product['sale_id']) ? $product['get_sale']['percent'] : null;
            $orderProduct->save();

            $orderProducts[] = $orderProduct;
            $total += !empty($product['sale_id']) ? $product['price_with_sale'] * $quantity : $product['price'] * $quantity;
        }
        $globalSale = GlobalSales::where([
            ['sum_modal', '<=', $total],
            ['active', 1]
        ])
            ->orderBy('procent_modal', 'desc')
            ->first();

        $groupSale = GroupSales::where([
            ['sum', '<=', $total],
            ['active', 1]
        ])
            ->orderBy('percent', 'desc')
            ->first();

        if (isset($globalSale)) {
            $order->sale_id = $globalSale->id;
            $order->with_sales = 1;
            $order->sale_type = Order::GLOBAL_SALES;
            $amountPercent = $total / 100 * $globalSale->procent_modal;
            $total = $total - $amountPercent;
        } else {
            if (isset($groupSale)) {
                $order->sale_id = $groupSale->id;
                $order->with_sales = 1;
                $order->sale_type = Order::GROUP_SALES;
                $amountPercent = $total / 100 * $groupSale->percent;
                $total = $total - $amountPercent;
            }
        }
        $order->total_sum = ceil($total);
        $order->save();

        $orderStatusHistory = OrderStatuses::where('name', OrderStatuses::UNFINISHED)
            ->first();

        $orderHistory->order_id = $order->id;
        $orderHistory->notify = 0;
        $orderHistory->comment = 'Заказ создан';
        $orderHistory->status_id = $orderStatusHistory->id;
        $orderHistory->save();

        return response()->json([
            'token' => $order->token,
            'order_id' => $order->id
        ]);

    }

    public  function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
