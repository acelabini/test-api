<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
use App\Http\Resources\Articles\ArticleCollection;
use App\Http\Resources\Articles\ArticleResource;
use App\Models\Article;
use App\Traits\WithArticleService;
use App\Traits\WithCacheService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ArticleController extends ApiController
{
    use WithCacheService;
    use WithArticleService;

    const DEFAULT_LIMIT = 10;

    protected const TAG = 'article';

    /**
     * @OA\Get(
     *     path="/v1/management/articles",
     *     summary="Get all articles with parameters",
     *     description="Get all articles with parameters",
     *     tags={"ARTICLES"},
     *     security={
     *         { "bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit of results",
     *         required=false,
     *         schema={
     *            "type"="integer",
     *            "example"="4"
     *         }
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Pagination page",
     *         required=false,
     *         schema={
     *            "type"="integer",
     *            "example"="1"
     *         }
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Articles Record Found.",
     *         @OA\JsonContent(
     *             example={
     *               "success": true,
     *               "message": "Success.",
     *               "data": {
     *                }
     *            }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Unauthenticated.",
     *                 "error_code": 401,
     *                 "data": {}
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             example={
     *                  "success": false,
     *                  "message": "Unauthorized.",
     *                  "error_code": 403,
     *                  "data": {}
     *             }
     *         )
     *     )
     * )
     *
     * Display a listing of the article resource
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'limit' => 'integer|max:' . self::MAX_LIMIT,
        ]);

        $articles = self::$cache::remember(
            self::TAG,
            'index',
            fn () => Article::paginate($request->query('limit', self::DEFAULT_LIMIT))
        );

        return $this->response(new ArticleCollection($articles));
    }

    /**
     * @OA\Post(
     *     path="/v1/management/articles",
     *     summary="Store article with request body",
     *     description="Store article with request body",
     *     tags={"ARTICLES"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ArticleStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Articles Record Created.",
     *         @OA\JsonContent(
     *             example={
     *               "success": true,
     *               "message": "Success.",
     *               "data": {
     *                }
     *            }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Unauthenticated.",
     *                 "error_code": 401,
     *                 "data": {}
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             example={
     *                  "success": false,
     *                  "message": "Unauthorized.",
     *                  "error_code": 403,
     *                  "data": {}
     *             }
     *         )
     *     )
     * )
     *
     * Store a newly created article resource in storage.
     *
     * @param ArticleStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ArticleStoreRequest $request)
    {
        $createdArticle = Article::create($request->validated());

        $article = self::$cache::remember(
            self::TAG,
            "store-{$createdArticle->id}",
            fn () => new ArticleResource($createdArticle)
        );

        return $this->response($article);
    }

    /**
     * @OA\Get(
     *     path="/v1/articles/{id}",
     *     summary="Get article by ID",
     *     description="Get article by ID",
     *     tags={"ARTICLES"},
     *     security={
     *         { "bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Developments Record Found.",
     *         @OA\JsonContent(
     *             example={
     *               "success": true,
     *               "message": "Success.",
     *               "data": {
     *                }
     *            }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Unauthenticated.",
     *                 "error_code": 401,
     *                 "data": {}
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             example={
     *                  "success": false,
     *                  "message": "Unauthorized.",
     *                  "error_code": 403,
     *                  "data": {}
     *             }
     *         )
     *     )
     * )
     * Display the specified article resource.
     *
     * @param Request $request
     * @param Article $article
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(Request $request, Article $article)
    {
        $article = self::$cache::remember(
            self::TAG,
            "show-{$article->id}",
            fn () => new ArticleResource($article)
        );

        return $this->response($article);
    }

    /**
     * @OA\Put(
     *     path="/v1/management/articles/{id}",
     *     summary="Update article with request body",
     *     description="Update article with request body",
     *     tags={"ARTICLES"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ArticleUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Articles Record Updated.",
     *         @OA\JsonContent(
     *             example={
     *               "success": true,
     *               "message": "Success.",
     *               "data": {
     *                }
     *            }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Unauthenticated.",
     *                 "error_code": 401,
     *                 "data": {}
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             example={
     *                  "success": false,
     *                  "message": "Unauthorized.",
     *                  "error_code": 403,
     *                  "data": {}
     *             }
     *         )
     *     )
     * )
     *
     * Update the specified article resource in storage.
     *
     * @param ArticleUpdateRequest $request
     * @param Article $article
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ArticleUpdateRequest $request, Article $article)
    {
        $updatedArticle = $article->fill($request->validated());
        $updatedArticle->save();

        $article = self::$cache::remember(
            self::TAG,
            "updated-{$updatedArticle->id}",
            fn () => new ArticleResource($updatedArticle)
        );

        return $this->response($article);
    }

    /**
     * @OA\Delete(
     *     path="/v1/management/articles/{id}",
     *     summary="Delete article using id",
     *     description="Delete article using id",
     *     tags={"ARTICLES"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Articles Record Deleted.",
     *         @OA\JsonContent(
     *             example={
     *               "success": true,
     *               "message": "Success.",
     *               "data": {
     *                }
     *            }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Unauthenticated.",
     *                 "error_code": 401,
     *                 "data": {}
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             example={
     *                  "success": false,
     *                  "message": "Unauthorized.",
     *                  "error_code": 403,
     *                  "data": {}
     *             }
     *         )
     *     )
     * )
     *
     * Remove the specified article resource in storage.
     *
     * @param Article $article
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return $this->response(['success' => true]);
    }

    /**
     * @OA\Put(
     *     path="/v1/management/articles/{id}/{status}",
     *     summary="Update article status and published_at",
     *     description="Update article status and published_at",
     *     tags={"ARTICLES"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Article ID",
     *         required=true,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *      @OA\Parameter(
     *         name="status",
     *         in="path",
     *         description="Status",
     *         required=true,
     *         schema={
     *            "type"="string"
     *         }
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_OK,
     *         description="Articles Record Updated.",
     *         @OA\JsonContent(
     *             example={
     *               "success": true,
     *               "message": "Success.",
     *               "data": {
     *                }
     *            }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             example={
     *                 "success": false,
     *                 "message": "Unauthenticated.",
     *                 "error_code": 401,
     *                 "data": {}
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN,
     *         description="Unauthorized.",
     *         @OA\JsonContent(
     *             example={
     *                  "success": false,
     *                  "message": "Unauthorized.",
     *                  "error_code": 403,
     *                  "data": {}
     *             }
     *         )
     *     )
     * )
     *
     * Update the specified article resource status and published_at.
     *
     * @param Article $article
     * @param string $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function patch(Article $article, string $status)
    {
        $patchedArticle = self::$article::patch($article, $status);

        $article = self::$cache::remember(
            self::TAG,
            "patched-{$patchedArticle->id}",
            fn () => new ArticleResource($patchedArticle)
        );

        return $this->response($article);
    }
}
