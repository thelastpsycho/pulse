<?php

namespace App\Http\Controllers;

use App\Services\ApiDocumentationService;
use Illuminate\Http\Request;

class ApiDocumentationController extends Controller
{
    public function __construct(
        private ApiDocumentationService $docService
    ) {}

    public function index()
    {
        $docs = $this->docService->getDocumentation();

        return view('api-docs.github-style', [
            'categories' => $docs['categories'],
            'stats' => $docs['stats'],
            'endpoints' => $docs['endpoints'],
        ]);
    }
}
