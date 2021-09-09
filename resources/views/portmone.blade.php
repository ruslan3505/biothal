
<form action="https://www.portmone.com.ua/gateway/" method="post">
    <input type="hidden" name="payee_id" value="26293" />
    <input type="hidden" name="shop_order_number" value="76575j65465464161" />
    <input type="hidden" name="bill_amount" value="1"/>
    <input type="hidden" name="description" value="Опис замовлення"/>
    <input type="hidden" name="success_url" value="{{route('success')}}" />
    <input type="hidden" name="failure_url" value="http://example.com/failure.html" />
    <input type="hidden" name="lang" value="ru" />
    <input type="hidden" name="encoding"  value= "UTF-8" />
    <input type="hidden" name="exp_time"  value= "400" />
    <input type="submit" value="Portmone.com" />
</form>
