<?php
public function getReportOrderByProduct(array $attributes)
{
    $childrenPrice = 0;
    $childrenCollection = 0;
    $childrenPay = 0;
    $parentPrice = 0;
    $parentCollection = 0;
    $parentPay = 0;
    $productGroupPrice = 0;
    $productGroupCollection = 0;
    $productGroupPay = 0;
    $childrenProduct = [];
    $parentProduct = [];
    $productGroup = [];
    $result = [];

    $status = [Order::STATUS['PENDING'], Order::STATUS['COMPLETED'], Order::STATUS['SHIPPING'], Order::STATUS['CONFIRMED']];
    $orders = Order::whereDate('created_at', '>=', $attributes['start_date'])
        ->whereDate('created_at', '<=', $attributes['end_date'])
        ->whereIn('status', $status)
        ->orderBy('created_at')->get();

    foreach ($orders as $order) {

        $totalCollection = 0;
        foreach ($order->collectionMoney as $value) {
            if ($value->status != CollectionMoney::STATUS['CANCELLED']) {
                $totalCollection += $value->money_received;
            }
        }

        $collection = $totalCollection - $order->shipping_fee;

        foreach ($order->orderDetail as $orderDetails) {

            $productCollection = 0;
            // chua lay duoc collectedMoney cua 1 san pham
            if ($orderDetails->product->id == $orderDetails->product_id) {
                $childrenPrice += $orderDetails->total_price;
            }

            $childrenPay = $childrenPrice - $productCollection;

            $childrenProduct[$orderDetails->product->name] = [
                "childrenPrice" => $childrenPrice,
                "childrenCollection" => $productCollection,
                "childrenPay" => $childrenPay,
            ];

            $nameProductParent = Product::where('id', $orderDetails->product->parent_id)->where('parent_id', null)->first();
        }

        foreach ($childrenProduct as $childrenProducts) {
            $parentPrice += $childrenProducts['childrenPrice'];
            $parentCollection += $childrenProducts['childrenCollection'];
            $parentPay += $childrenProducts['childrenPay'];
        }

        $parentProduct[$nameProductParent->name] = [
            "parentPrice" => $parentPrice,
            "parentCollection" => $parentCollection,
            "parentpay" => $parentPay,
            "children" => $childrenProduct,
        ];

        $nameProductGroup = ProductGroup::where('id', $nameProductParent->product_group_id)->first();

        $productGroup[$nameProductGroup->name] = [
            "parent" => $parentProduct
        ];
    }

    $result = $productGroup;
    $limit = 2;
    $page = 1;
    if (!empty($attributes['limit'])) {
        $limit = $attributes['limit'];
    }

    if (!empty($attributes['page'])) {
        $page = $attributes['page'];
    }

    $result = $this->paginateCollection($result, $limit, $page);

    return $result;
}

public function paginateCollection($items, $perPage = 2, $page = null, $options = [])
{

    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);

    $result = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    $result->setPath(request()->url());

    return $result;
}

public function reportOrderByProduct(Request $request)
    {
        $productGroup = $this->productGroupRepository->getReportOrderByProduct($request->all());

        return $this->success(['data' => $productGroup], trans('lang::messages.common.getListSuccess'));
    }

    \Route::get('report-order-by-products', 'ProductGroupController@reportOrderByProduct');