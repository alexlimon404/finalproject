<?php
/**
 * Created by PhpStorm.
 * User: Odmen
 * Date: 3/23/2019
 * Time: 8:39 PM
 */

namespace App\Http\Transformers;

use Illuminate\Support\Collection;
use League\Fractal\TransformerAbstract;
use Spatie\Fractalistic\ArraySerializer;
use App\Models\CartItem;
use League\Fractal;
use League\Fractal\Manager;

class Transformer extends TransformerAbstract
{
    public static function transform(CartItem $items)
    {
        dd($items);
        return [
            'item_id' => (int) $items->item_id,
            'amount' => $items->amount,
        ];
    }
    /**
     *
     *
     * */

    public static function transformCollection(Collection $items, $resourceKey = null)
    {
        return fractal()
            ->collection($items, new Transformer())
            ->serializeWith(new ArraySerializer())
            ->withResourceName($resourceKey);
    }

}