<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Resource;
use App\Repository\ResourceRepository;
use Doctrine\ORM\EntityManagerInterface;

class ResourceManager
{
    private EntityManagerInterface $entityManager;
    private ResourceRepository $resourceRepository;

    public function __construct(EntityManagerInterface $entityManager, ResourceRepository $resourceRepository)
    {
        $this->entityManager = $entityManager;
        $this->resourceRepository = $resourceRepository;
    }

    /**
     * Get one resource
     *
     * @param string $slug
     * @return Resource|null
     */
    public function getOne(string $slug): ?Resource
    {
        return $this->resourceRepository->findOneBy(['slug' => $slug]);
    }

    /**
     * Returns all available resources
     *
     * @return Resource[]
     */
    public function getAll(): array
    {
        return $this->resourceRepository->findAll();
    }

    /**
     * Creates or updates a resource
     *
     * @param Resource $resource
     */
    public function persist(Resource $resource): void
    {
        $this->entityManager->persist($resource);
        $this->entityManager->flush();
    }

    /**
     * Removes a resource
     *
     * @param Resource $resource
     */
    public function delete(Resource $resource): void
    {
        $this->entityManager->remove($resource);
        $this->entityManager->flush();
    }
}