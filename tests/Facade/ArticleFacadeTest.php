<?php

namespace App\Tests\Facade;

use App\Dto\Response\Article\ArticlePaginationResponse;
use App\Entity\Tag;
use App\Facade\ArticleFacade;
use App\Repository\ArticleRepository;
use App\Service\ArticleService;
use App\Service\TagService;
use App\Tests\Mock\MockableArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleFacadeTest extends TestCase
{
    private readonly ArticleService $articleService;

    private readonly TagService $tagService;

    private readonly PaginatorInterface $paginator;

    private readonly ArticleFacade $facade;
    private readonly RequestStack $request;
    private Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create('ru_RU');

        $this->request = (new RequestStack());
        $this->request->push(
            Request::create(
                $this->faker->url(),
                Request::METHOD_GET
            )
        );

        $this->articleService = $this->createMock(ArticleService::class);

        $this->tagService = $this->createMock(TagService::class);
        $this->paginator = $this->createMock(PaginatorInterface::class);

        $this->facade = new ArticleFacade(
            $this->articleService,
            $this->tagService,
            $this->paginator
        );

        parent::setUp();
    }

    public function testGetPaginatedArticlesByTagLinkAndRequestStack()
    {
        // mocked tag link
        $tagLink = 'mocked_tag_link';

        $this->articleService->expects($this->once())
            ->method('getArticlesQueryByTagLink')
            ->with($tagLink);
        $this->tagService->expects($this->once())
            ->method('getTagByLink')
            ->with($tagLink)
            ->willReturn($this->createMock(Tag::class));
        $this->paginator->expects($this->once())
            ->method('paginate');

        $articlePaginationResponse = $this->facade->getPaginatedArticlesByTagLinkAndRequestStack(
            $tagLink,
            $this->request
        );
        $this->assertInstanceOf(ArticlePaginationResponse::class, $articlePaginationResponse);
    }

    public function testGetArticlesBySearchQuery()
    {
        $this->articleService->expects($this->once())
            ->method('getArticlesByPattern');

        $articles = $this->facade->getArticlesBySearchQuery($this->faker->realText(10));
        $this->assertIsArray($articles);
    }
}
