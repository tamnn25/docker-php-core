<?php
public function reportOrderQuantityByProduct(array $attributes)
{
    $orderDetail = [];
    $temp = [];
    $status = [Order::STATUS['PENDING'], Order::STATUS['CONFIRMED']];
    $orders = Order::whereDate('created_at', '>=', $attributes['start_date'])
        ->whereDate('created_at', '<=', $attributes['end_date'])
        ->whereIn('status', $status)
        ->orderBy('created_at')->get();

    foreach ($orders as $order) {

        $productDetail = [];
        foreach ($order->orderDetail as  $orderDetail) {

            foreach ($orderDetail->product->exchange as $value) {
                $conversionRate = $value->conversion_rate;
            }

            if ($orderDetail->unitOrderDetail->name != $orderDetail->product->standardUnit->name) {
                $conversionRate = $orderDetail->quantity % $conversionRate;
            } else {
                $conversionRate = $orderDetail->quantity;
            }

            $productDetail[$orderDetail->product->name] = [
                "created_at" => $orderDetail->order->created_at->format('y-m-d'),
                "order-code" => $orderDetail->order->order_code,
                "unit" => $orderDetail->unitOrderDetail->name,
                "quantity" => $orderDetail->quantity,
                "standardUnit" => $orderDetail->product->standardUnit->name,
                "conversionRate" => $conversionRate,
                "status" => $orderDetail->order->status,
            ];

            foreach (Order::STATUS as $key => $value) {
                if ($value == $productDetail[$orderDetail->product->name]['status']) {
                    $productDetail[$orderDetail->product->name]['status'] = $key;
                }
            }

            $parentName = Product::where('id', $orderDetail->product->parent_id)->first();

            $productGroup = ProductGroup::where('id', $parentName->product_group_id)->first();

            $temp[$productGroup->name][$parentName->name]['orderDetail'] = $productDetail;
        }
    }
    $limit = 2;
    $page = 1;
    if (!empty($attributes['limit'])) {
        $limit = $attributes['limit'];
    }

    if (!empty($attributes['page'])) {
        $page = $attributes['page'];
    }

    $result = $this->paginateCollection($temp, $limit, $page);

    return $result;
}

/**
 * Paginate for collection
 * @param $object
 * @param $limit
 * @return LengthAwarePaginator
 */
public function paginateCollection($items, $perPage = 2, $page = null, $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);

    $result = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    $result->setPath(request()->url());

    return $result;
}

// 
    public function reportOrderQuantityByProduct(Request $request)
    {
        $productGroup = $this->productGroupRepository->reportOrderQuantityByProduct($request->all());

        return $this->success(['data' => $productGroup], trans('lang::messages.common.getListSuccess'));
    }

    // 
    \Route::get('report-order-quantity-by-products', 'ProductGroupController@reportOrderQuantityByProduct');


    