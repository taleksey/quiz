<?php

declare(strict_types=1);

namespace App\Presentation\Transformers;

use Symfony\Component\HttpFoundation\Request;

class TransformJsonToRequest
{
    public function transform(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $request;
        }

        if (empty($data)) {
            return $request;
        }

        $request->request->replace($data);

        return  $request;
    }
}
