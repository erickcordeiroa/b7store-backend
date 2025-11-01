<?php

declare(strict_types=1);

namespace Tests\Unit\UseCases;

use App\Application\DTOs\ProductDTO;
use App\Application\DTOs\ProductFilterDTO;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Application\UseCases\GetProductsUseCase;
use App\Application\UseCases\GetProductUseCase;
use App\Domain\Entities\Product;
use App\Domain\Entities\ProductImage;
use App\Domain\Entities\Category;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Tests\TestCase;

/**
 * Teste UNITÁRIO do UseCase usando Mock
 * 
 * ✅ NÃO usa banco de dados (cria instâncias manualmente)
 * ✅ Testa apenas a LÓGICA do UseCase (transformação de Product → ProductDTO)
 * ✅ Isolado - mocka o repositório
 */
class GetProductUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private ProductRepositoryInterface $productRepository;
    private GetProductUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = Mockery::mock(ProductRepositoryInterface::class);
        $this->useCase = new GetProductUseCase($this->productRepository);
    }

    public function test_it_converts_product_to_dto_with_images(): void
    {
        // Arrange: Criar produtos manualmente - teste unitário puro sem banco
        $category = new Category();
        $category->id = 1;
        $category->name = 'Categoria 1';
        $category->slug = 'categoria-1';

        $product1 = new Product();
        $product1->id = 1;
        $product1->category_id = 1;
        $product1->label = 'Produto 1';
        $product1->slug = 'produto-1';
        $product1->price = 10.99;
        $product1->description = 'Descrição do produto 1';

        // Mockar as relações
        $image1 = new ProductImage(['uri' => 'products/imagem1.jpg']);
        $product1->setRelation('images', Collection::make([$image1]));
        $product1->setRelation('category', $category);

        // Mockar o retorno do repositório
        $this->productRepository
            ->shouldReceive('findBySlug')
            ->once()
            ->with('produto-1')
            ->andReturn($product1);

        // Mockar o save após incrementViews
        $this->productRepository
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function ($arg) use ($product1) {
                return $arg === $product1;
            }))
            ->andReturn(true);

        // Act: Executar o UseCase
        $result = $this->useCase->execute('produto-1');

        // Assert: Verificar que transformou corretamente para DTOs
        $this->assertEquals(1, $result->id);
        $this->assertEquals('Produto 1', $result->label);
        $this->assertEquals(10.99, $result->price);
    }

    public function test_it_uses_default_image_when_product_has_no_images(): void
    {
        // Arrange: Criar produto manualmente - teste unitário puro sem banco
        $category = new Category();
        $category->id = 2;
        $category->name = 'Categoria 2';
        $category->slug = 'categoria-2';

        $product = new Product();
        $product->id = 1;
        $product->category_id = 2;
        $product->label = 'Produto Sem Imagem';
        $product->slug = 'produto-sem-imagem';
        $product->price = 15.50;
        $product->description = 'Descrição do produto sem imagem';

        // Mockar as relações
        $product->setRelation('images', Collection::make());
        $product->setRelation('category', $category);

        $this->productRepository
            ->shouldReceive('findBySlug')
            ->once()
            ->with('produto-sem-imagem')
            ->andReturn($product);

        // Mockar o save após incrementViews
        $this->productRepository
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function ($arg) use ($product) {
                return $arg === $product;
            }))
            ->andReturn(true);

        // Act
        $result = $this->useCase->execute('produto-sem-imagem');

        // Assert: Deve usar a imagem padrão
        $this->assertStringContainsString('image-not-found.jpeg', $result->images['uri']);
    }
}

