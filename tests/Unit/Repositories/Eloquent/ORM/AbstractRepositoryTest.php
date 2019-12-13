<?php

namespace Tests\Unit\Repositories\Eloquent\ORM;

use App\Models\User;
use App\Repositories\Eloquent\ORM\AbstractRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Mockery\MockInterface;
use Tests\AbstractTestCase;
use Tests\Stubs\Models\ModelStub;
use Tests\Stubs\Repositories\Eloquent\ORM\RepositoryStub;

/**
 * @covers \App\Repositories\Eloquent\ORM\AbstractRepository
 */
final class AbstractRepositoryTest extends AbstractTestCase
{
    /**
     * @var \App\Repositories\Eloquent\ORM\AbstractRepository
     */
    private $repositoryStub;

    /**
     * Should fetch all.
     * 
     * @return void
     */
    public function testAll(): void
    {
        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->mock(
            Model::class,
            static function (MockInterface $mock): void {
                $mock->shouldReceive('all')
                    ->once()
                    ->withNoArgs()
                    ->andReturn(new Collection());
            }
        );
        
        self::assertInstanceOf(Collection::class, $this->getRepositoryStub($model)->all());
    }

    /**
     * Should create a model.
     * 
     * @return void
     */
    public function testCreate(): void
    {
        $parameters = ['name' => 'tester'];

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->mock(
            Model::class,
            static function (MockInterface $mock) use ($parameters): void {
                $mock->shouldReceive('create')
                    ->once()
                    ->with($parameters)
                    ->andReturn(new ModelStub($parameters));
            }
        );
        
        $created = $this->getRepositoryStub($model)->create($parameters);

        self::assertInstanceOf(Model::class, $created);
        self::assertEquals($created->name, (string)$parameters['name']);
    }

    /**
     * Should delete a model.
     * 
     * @return void
     */
    public function testDelete(): void
    {
        $id = 'test-id';

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->mock(
            Model::class,
            static function (MockInterface $mock) use ($id): void {
                $mock->shouldReceive('destroy')
                    ->once()
                    ->with($id)
                    ->andReturnNull();
            }
        );
        
        self::assertNull($this->getRepositoryStub($model)->delete($id));
    }

    /**
     * Should get a model.
     * 
     * @return void
     */
    public function testGetModel(): void
    {
        $model = new ModelStub();
        
        self::assertEquals($model, $this->getRepositoryStub($model)->getModel());
    }    

    /**
     * Should set a model.
     * 
     * @return void
     */
    public function testSetModel(): void
    {
        $model = new ModelStub();
        
        $this->getRepositoryStub($model)->setModel($model);
        
        self::assertEquals($model, $this->getRepositoryStub($model)->getModel());
    }    

    /**
     * Should find a record.
     * 
     * @return void
     */
    public function testFind(): void
    {
        $record = new ModelStub(['id' => 9999]);

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->mock(
            Model::class,
            static function (MockInterface $mock) use ($record): void {
                $mock->shouldReceive('find')
                    ->once()
                    ->with($record->id)
                    ->andReturn($record);
            }
        );
        
        $fetchedStub = $this->getRepositoryStub($model)->find($record->id);
        
        self::assertEquals($fetchedStub->id, $record->id);
    }    

    /**
     * Should find a records by filters.
     * 
     * @return void
     */
    public function testFindBy(): void
    {
        $record1 = new ModelStub(['id' => 9998, 'name' => 'lorem', 'description' => 'ipsum']);
        $record2 = new ModelStub(['id' => 9999, 'name' => 'lorem', 'description' => 'ipsum']);

        $filters = ['name' => 'lorem', 'description' => 'ipsum'];

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->mock(
            Model::class,
            static function (MockInterface $mock) use ($filters, $record1, $record2): void {
                foreach($filters as $field => $value) {
                    $mock->shouldReceive('where')
                    ->once()
                    ->withArgs(function($param1, $param2, $param3) use ($field, $value): bool {
                        
                        self::assertEquals($param1, $field);
                        self::assertEquals($param2, '=');
                        self::assertEquals($param3, $value);
                        
                        return true;  
                    })
                    ->andReturnSelf();
                }

                $mock->shouldReceive('get')
                    ->once()
                    ->withNoArgs()
                    ->andReturn(new Collection([$record1, $record2]));
            }
        );
        
        $items = $this->getRepositoryStub($model)->findBy($filters);

        self::assertInstanceOf(Collection::class, $items);
        self::assertEquals(2, $items->count());
        self::assertContains($record1->toArray(), $items->toArray());
        self::assertContains($record2->toArray(), $items->toArray());
    }    

    /**
     * Should find a record or fail.
     * 
     * @return void
     */
    public function testFindOrFail(): void
    {
        $record = new ModelStub(['id' => 9999]);

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->mock(
            Model::class,
            static function (MockInterface $mock) use ($record): void {
                $mock->shouldReceive('findOrFail')
                    ->once()
                    ->with($record->id)
                    ->andReturn($record);
            }
        );
        
        $fetchedStub = $this->getRepositoryStub($model)->findOrFail($record->id);
        
        self::assertEquals($fetchedStub->id, $record->id);
    }

    /**
     * Should show a record.
     * 
     * @return void
     */
    public function testUpdate(): void
    {
        $record = new ModelStub([
            'id' => 9999,
            'name' => 'Paolo yip, yip!'
        ]);

        $payload = ['name' => 'Paolo'];

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->mock(
            Model::class,
            static function (MockInterface $mock) use ($record, $payload): void {
                $mock->shouldReceive('findOrFail')
                    ->once()
                    ->with($record->id)
                    ->andReturn($record);
            }
        );

        $updated = $this->getRepositoryStub($model)->update($payload, $record->id);

        self::assertInstanceOf(Model::class, $updated);
    }

    /**
     * Should return eager loaded relations.
     * 
     * @return void
     */
    public function testWith(): void
    {
        $relation = 'winningNumbers';

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = $this->mock(
            Model::class,
            function (MockInterface $mock) use ($relation): void {
                $mock->shouldReceive('with')
                    ->once()
                    ->with($relation)
                    ->andReturn((new User())->winningNumbers());
            }
        );

        self::assertInstanceOf(Relation::class, $this->getRepositoryStub($model)->with($relation));
    }

    /**
     * Return a memoized instance of the repository stub.
     *
     * @return \App\Repositories\Eloquent\ORM\AbstractRepository
     */
    private function getRepositoryStub(Model $model): AbstractRepository
    {
        if ($this->repositoryStub !== null) {
            return $this->repositoryStub;
        }

        return $this->repositoryStub = new RepositoryStub($model);
    }
}
