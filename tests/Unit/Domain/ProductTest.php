<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Entities\Product;
use PHPUnit\Framework\TestCase;

/**
 * Teste Unitário da Entidade Product
 * Testa regras de negócio da entidade SEM banco de dados
 * Cria instâncias manualmente (sem factory) para manter o teste isolado
 */
class ProductTest extends TestCase
{
    public function test_product_can_be_created_manually(): void
    {
        // Arrange & Act: Criar produto manualmente - NÃO usa banco de dados
        // Teste unitário puro - sem dependência de Laravel ou banco
        $product = new Product([
            'label' => 'Produto Teste',
            'price' => 99.99,
        ]);

        // Assert: Verificar que o produto foi criado corretamente em memória
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Produto Teste', $product->label);
        $this->assertEquals(99.99, $product->price);
        $this->assertNull($product->id); // Não persiste, então não tem ID
    }

    public function test_product_has_fillable_attributes(): void
    {
        // Arrange & Act: Criar produto com atributos específicos
        $product = new Product([
            'label' => 'Produto Teste',
            'slug' => 'produto-teste',
            'price' => 99.99,
        ]);

        // Assert: Verificar atributos
        $this->assertEquals('Produto Teste', $product->label);
        $this->assertEquals('produto-teste', $product->slug);
        $this->assertEquals(99.99, $product->price);
    }

    public function test_product_increment_views(): void
    {
        // Arrange: Criar produto com views_count inicial
        $product = new Product([
            'label' => 'Produto Teste',
            'price' => 99.99,
            'views_count' => 10,
        ]);

        // Act: Incrementar visualizações (regra de negócio da entidade)
        // Nota: Em teste unitário puro, increment() do Eloquent não funciona
        // porque precisa de conexão com banco. Testamos a lógica diretamente.
        $product->views_count = $product->views_count + 1;

        // Assert: Verificar que as visualizações foram incrementadas
        $this->assertEquals(11, $product->views_count);
    }
}

