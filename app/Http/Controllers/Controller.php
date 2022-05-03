<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\SecurityScheme(
 *       scheme="Bearer",
 *       securityScheme="Bearer",
 *       type="apiKey",
 *       in="header",
 *       name="Authorization",
 * ),
 * @OA\Info(
 * title="Your super ApplicationAPI",
 * version="1.0.0",
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
