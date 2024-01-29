<?php

namespace App\Tests\Entity;

use App\Entity\Cours;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CoursTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCreateCours(): void
    {
        $cours = new Cours();
        $cours->setNom('Maths');
        $cours->setProfesseur('Prof. X');
        $cours->setDuree('2 hours');

        $this->entityManager->persist($cours);
        $this->entityManager->flush();

        $this->assertNotNull($cours->getId());
    }

    public function testRetrieveCours(): void
    {
        $cours = $this->entityManager
            ->getRepository(Cours::class)
            ->findOneBy(['nom' => 'Maths']);

        $this->assertNotNull($cours);
        $this->assertEquals('Prof. X', $cours->getProfesseur());
    }

    public function testUpdateCours(): void
    {
        $cours = $this->entityManager
            ->getRepository(Cours::class)
            ->findOneBy(['nom' => 'Maths']);

        $cours->setProfesseur('Prof. Y');
        $this->entityManager->flush();

        $updatedCours = $this->entityManager
            ->getRepository(Cours::class)
            ->findOneBy(['nom' => 'Maths']);

        $this->assertEquals('Prof. Y', $updatedCours->getProfesseur());
    }

    public function testDeleteCours(): void
    {
        $cours = $this->entityManager
            ->getRepository(Cours::class)
            ->findOneBy(['nom' => 'Maths']);

        $this->entityManager->remove($cours);
        $this->entityManager->flush();

        $deletedCours = $this->entityManager
            ->getRepository(Cours::class)
            ->findOneBy(['nom' => 'Maths']);

        $this->assertNull($deletedCours);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}