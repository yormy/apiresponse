<?php

declare(strict_types=1);

//namespace Yormy\Xid\Models\Traits;
//
//use Yormy\Xid\Services\XidService;
//
//trait Xid
//{
//    public static function bootXid()
//    {
//        static::creating(function ($model) {
//            if (!property_exists($model, 'xid')) {
//                if (!$model->xid) {
//                    $model->xid = XidService::generate();
//                }
//            }
//        });
//    }
//}
