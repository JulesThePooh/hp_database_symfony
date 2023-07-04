<?php

namespace App\Command;

use App\Entity\House;
use App\Entity\HousePoint;
use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\Teacher;
use App\Entity\TypeOfClass;
use App\Repository\HousePointRepository;
use App\Repository\HouseRepository;
use App\Repository\StudentRepository;
use App\Repository\SubjectRepository;
use App\Repository\TeacherRepository;
use App\Repository\TypeOfClassRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:hp-api',
    description: 'Add a short description for your command',
)]
class HpApiCommand extends Command
{


    public function __construct(
        private HttpClientInterface    $httpClient,
        private StudentRepository      $studentRepository,
        private TeacherRepository      $teacherRepository,
        private EntityManagerInterface $entityManager,
        private HouseRepository        $houseRepository,
        private TypeOfClassRepository  $typeOfClassRepository,
        private SubjectRepository      $subjectRepository,
        private HousePointRepository   $housePointRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {


        $this->deleteAllData();

        /**
         * handle add student
         */
        $this->addStudents();

        /**
         * handle add teacher
         */
        $this->addTeachers();

        /**
         * handle add type of class
         */
        $this->addSubjects();

        /**
         * handle house point
         */
        $this->addHousePoint();

        /**
         * handle type of class
         */
        $this->addTypeOfClass();


        return Command::SUCCESS;
    }

    public function getHouseEntityFromHouseEnglishString($houseEnglishString): House
    {

        if ($houseEnglishString === "Gryffindor") {
            $houseEnglishString = "Gryffondor";
        } else if ($houseEnglishString === "Slytherin") {
            $houseEnglishString = "Serpentard";
        } else if ($houseEnglishString === "Hufflepuff") {
            $houseEnglishString = "Poufsouffle";
        } else if ($houseEnglishString === "Ravenclaw") {
            $houseEnglishString = "Serdaigle";
        } else {
            $houseEnglishString = "Inconnu";
        }

        $houseEntity = $this->houseRepository->findOneBy(['houseName' => $houseEnglishString]);
        if ($houseEntity === null) {
            $houseEntity = new House();
            $houseEntity->setHouseName($houseEnglishString);
            if ($houseEntity->getHouseName() === "Gryffondor") {
                $houseEntity->setFounderFirstName("Godric");
                $houseEntity->setFounderLastName("Gryffondor");
            } else if ($houseEntity->getHouseName() === "Serpentard") {
                $houseEntity->setFounderFirstName("Salazar");
                $houseEntity->setFounderLastName("Serpentard");
            } else if ($houseEntity->getHouseName() === "Poufsouffle") {
                $houseEntity->setFounderFirstName("Helga");
                $houseEntity->setFounderLastName("Poufsouffle");
            } else if ($houseEntity->getHouseName() === "Serdaigle") {
                $houseEntity->setFounderFirstName("Rowena");
                $houseEntity->setFounderLastName("Serdaigle");
            } else {
                $houseEntity->setFounderFirstName("Inconnu");
                $houseEntity->setFounderLastName("Inconnu");
            }

            $this->entityManager->persist($houseEntity);
            $this->entityManager->flush();
        }

        return $houseEntity;
    }

    public function deleteAllData(): void
    {
        /**
         * delete student
         */
        $studentEntities = $this->studentRepository->findAll();
        foreach ($studentEntities as $studentEntity) {
            $this->entityManager->remove($studentEntity);
        }

        /**
         * delete teacher
         */
        $teacherEntities = $this->teacherRepository->findAll();
        foreach ($teacherEntities as $teacherEntity) {
            $this->entityManager->remove($teacherEntity);
        }

        /**
         * delete house point
         */
        $housePointEntities = $this->housePointRepository->findAll();
        foreach ($housePointEntities as $housePoint) {
            $this->entityManager->remove($housePoint);
        }

        /**
         * delete type of class
         */
        $typeOfClassEntities = $this->typeOfClassRepository->findAll();
        foreach ($typeOfClassEntities as $typeOfClass) {
            $this->entityManager->remove($typeOfClass);
        }


        $this->entityManager->flush();
    }

    public function addStudents(): void
    {
        $jsonStudent = $this->httpClient->request('GET', 'https://hp-api.onrender.com/api/characters/students');
        $jsonStudent = $jsonStudent->getContent();
        $studentData = json_decode($jsonStudent, true);

        foreach ($studentData as $data) {
            $houseString = $data['house'];
            $houseEntity = $this->getHouseEntityFromHouseEnglishString($houseString);

            $student = new Student();
            $student->setName($data['name']);
            $student->setHouse($houseEntity);
            $student->setIsAlive($data['alive']);

            if ($data['yearOfBirth'] === null) {
                $student->setYearOfBirth(1900);
            } else {
                $student->setYearOfBirth($data['yearOfBirth']);
            }


            $this->entityManager->persist($student);
        }

        $this->entityManager->flush();
    }

    public function addTeachers(): void
    {
        $jsonTeacher = $this->httpClient->request('GET', 'https://hp-api.onrender.com/api/characters/staff');
        $jsonTeacher = $jsonTeacher->getContent();
        $teacherData = json_decode($jsonTeacher, true);

        foreach ($teacherData as $teacher) {
            $teacherEntity = new Teacher();
            if ($teacher['name'] !== '') {
                $teacherEntity->setName($teacher['name']);
                $this->entityManager->persist($teacherEntity);
            }
        }

        $this->entityManager->flush();
    }

    public function addSubjects(): void
    {
        $array = [
            'Astronomie',
            'Botanique',
            'Défense contre les forces du Mal',
            'Histoire de la magie',
            'Métamorphose',
            'Potions',
            'Sortilèges',
            'Vol sur balai'
        ];

        foreach ($array as $item) {
            $entity = $this->subjectRepository->findOneBy(['subjectName' => $item]);
            if ($entity === null) {
                $entity = new Subject();
                $entity->setSubjectName($item);
                $this->entityManager->persist($entity);
            }
        }

        $this->entityManager->flush();
    }

    public function addHousePoint(): void
    {
        $houseEntities = $this->houseRepository->findAll();
        foreach ($houseEntities as $houseEntity) {
            $year = 2023;
            for ($i = 0; $i < 25; $i++) {
                $housePointEntity = new HousePoint();
                $housePointEntity->setHouse($houseEntity);
                $housePointEntity->setYear($year);
                $housePointEntity->setTotalPoint(rand(1000, 9999));
                $this->entityManager->persist($housePointEntity);
                $year--;
            }
        }

        $this->entityManager->flush();
    }

    public function addTypeOfClass(): void
    {
        $teacherEntities = $this->teacherRepository->findAll();

        $subjectEntities = $this->subjectRepository->findAll();
        foreach ($subjectEntities as $subjectEntity) {
            $year = 2023;
            for ($i = 0; $i < 25; $i++) {
                $typeOfClassEntity = new TypeOfClass();
                $typeOfClassEntity->setSubject($subjectEntity);
                $typeOfClassEntity->setTeacher($teacherEntities[rand(0, (count($teacherEntities) - 1))]);
                $typeOfClassEntity->setYearTaught($year);
                $year--;

                $this->entityManager->persist($typeOfClassEntity);
            }
        }

        $this->entityManager->flush();
    }
}
