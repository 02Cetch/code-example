<?php

namespace App\Tests\Facade;

use App\Dto\Response\Article\ArticlePaginationResponse;
use App\Entity\Tag;
use App\Facade\ArticleFacade;
use App\Repository\ArticleRepository;
use App\Service\ArticleService;
use App\Service\TagService;
use App\Tests\Mock\MockableArticleRepository;
use Faker\Factory;
use Faker\Generator;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleFacadeTest extends TestCase
{
    private readonly ArticleFacade $facade;
    private readonly RequestStack $request;
    private Generator $faker;

    protected function setUp(): void
    {
//        $this->facade = $this->createMock(ArticleFacade::class);
        $this->faker = Factory::create('ru_RU');

        $this->request = (new RequestStack());
        $this->request->push(
            Request::create(
                $this->faker->url(),
                Request::METHOD_GET
            )
        );

        parent::setUp();
    }

    public function testGetPaginatedArticlesByTagLinkAndRequestStack()
    {
        $articleRepository = $this->createMock(MockableArticleRepository::class);
        $articleService = (new ArticleService($articleRepository));

        $tagService = $this->createMock(TagService::class);
        $paginator = $this->createMock(PaginatorInterface::class);

        $facade = new ArticleFacade(
            $articleService,
            $tagService,
            $paginator
        );

        // mocked tag link
        $tagLink = 'mocked_tag_link';

        $articleRepository->expects($this->once())
            ->method('getArticlesQueryByTagLink')
            ->with($tagLink);
        $tagService->expects($this->once())
            ->method('getTagByLink')
            ->with($tagLink)
            ->willReturn($this->createMock(Tag::class));
        $paginator->expects($this->once())
            ->method('paginate');

        $articlePaginationResponse = $facade->getPaginatedArticlesByTagLinkAndRequestStack(
            $tagLink,
            $this->request
        );
        $this->assertInstanceOf(ArticlePaginationResponse::class, $articlePaginationResponse);
    }
}
