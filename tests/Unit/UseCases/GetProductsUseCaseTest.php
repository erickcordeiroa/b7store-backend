<?php

declare(strict_types=1);

namespace Tests\Unit\UseCases;

use App\Application\DTOs\ProductDTO;
use App\Application\DTOs\ProductFilterDTO;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Application\UseCases\GetProductsUseCase;
use App\Domain\Entities\Product;
use App\Domain\Entities\ProductImage;
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
class GetProductsUseCaseTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private ProductRepositoryInterface $productRepository;
    private GetProductsUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = Mockery::mock(ProductRepositoryInterface::class);
        $this->useCase = new GetProductsUseCase($this->productRepository);
    }

    public function test_it_converts_products_to_dtos_with_images(): void
    {
        // Arrange: Criar produtos manualmente - teste unitário puro sem banco
        $product1 = new Product();
        $product1->id = 1;
        $product1->label = 'Produto 1';
        $product1->slug = 'produto-1';
        $product1->price = 10.99;

        // Mockar a relação de imagens
        $image1 = new ProductImage(['uri' => 'products/imagem1.jpg']);
        $product1->setRelation('images', Collection::make([$image1]));

        $product2 = new Product();
        $product2->id = 2;
        $product2->label = 'Produto 2';
        $product2->slug = 'produto-2';
        $product2->price = 20.99;

        $product2->setRelation('images', Collection::make());

        $products = Collection::make([$product1, $product2]);

        // Mockar o retorno do repositório
        $this->productRepository
            ->shouldReceive('findByFilters')
            ->once()
            ->with([], null, 15)
            ->andReturn($products);

        $filterDTO = new ProductFilterDTO(
            metadataFilters: [],
            orderBy: null,
            limit: 15
        );

        // Act: Executar o UseCase
        $result = $this->useCase->execute($filterDTO);

        // Assert: Verificar que transformou corretamente para DTOs
        $this->assertCount(2, $result);
        $this->assertInstanceOf(ProductDTO::class, $result->first());

        $firstDTO = $result->first();
        $this->assertEquals(1, $firstDTO->id);
        $this->assertEquals('Produto 1', $firstDTO->label);
        $this->assertEquals('produto-1', $firstDTO->slug);
        $this->assertEquals(10.99, $firstDTO->price);
        $this->assertFalse($firstDTO->liked);
    }

    public function test_it_uses_default_image_when_product_has_no_images(): void
    {
        // Arrange: Criar produto manualmente - teste unitário puro sem banco
        $product = new Product();
        $product->id = 1;
        $product->label = 'Produto Sem Imagem';
        $product->slug = 'produto-sem-imagem';
        $product->price = 15.50;

        $product->setRelation('images', Collection::make());

        $this->productRepository
            ->shouldReceive('findByFilters')
            ->once()
            ->andReturn(Collection::make([$product]));

        $filterDTO = new ProductFilterDTO(
            metadataFilters: [],
            orderBy: null,
            limit: 15
        );

        // Act
        $result = $this->useCase->execute($filterDTO);

        // Assert: Deve usar a imagem padrão
        $dto = $result->first();
        $this->assertStringContainsString('default.png', $dto->image);
    }
}

