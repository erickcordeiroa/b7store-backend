<?php

declare(strict_types=1);

namespace Tests\Unit\UseCases;

use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Entities\Product;
use Tests\TestCase;

/**
 * Teste de INTEGRAÇÃO do Repositório
 * 
 * ⚠️ IMPORTANTE: Este é um teste de INTEGRAÇÃO, não unitário
 * 
 * ✅ Usa banco de dados real (RefreshDatabase)
 * ✅ Testa REPOSITÓRIO com queries SQL reais
 * ✅ Testa persistência e consultas no banco
 * ✅ PRECISA usar create() - não pode usar make()
 * 
 * Para teste UNITÁRIO do UseCase (com mock), veja GetProductsUseCaseTest
 */
class GetProductsTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = app(ProductRepositoryInterface::class);
    }

    public function test_it_gets_all_products(): void
    {
        // Arrange: Criar os dados no banco usando create()
        // Não pode usar make() porque o repositório faz queries SQL reais
        Product::factory()->count(2)->create();

        // Act: Executar a consulta no repositório (query SQL real)
        $products = $this->productRepository->findByFilters(
            metadataFilters: [],
            orderBy: null,
            limit: 15
        );

        // Assert: Verificar que retornou os produtos do banco
        $this->assertCount(2, $products);
    }
}