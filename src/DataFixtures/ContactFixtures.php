<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends Fixture
{
    public const LASTNAME = [
        'Vador',
        'World',
        'Doe',
        'Stark',
        'Kenobi'
    ];
    
    public const FIRSTNAME = [
        'Dark',
        'Hello',
        'John',
        'Jon',
        'Obi-wan'
    ];

    public const MAIL = [
        'vador@deathstar.com',
        'hello@world.io',
        'johndoe@gmail.com',
        'youknow@nothing.fr',
        'maytheforth@bewithyou.com'
    ];

    public const ADRESS = [
        'Death Star, alderran City',
        'World 1 avenue Hello, World City',
        'John Doe, X avenue, Y City',
        'The great Wall, North',
        'Tatooine, Desert, The farm'
    ];

    public const PHONE = [
        '11 11 11 11 11',
        '12 13 14 15 16',
        '06 07 08 09 00',
        '+33 6 90 11 12 40',
        '+33 7 89 67 45 69'
    ];

    public const AGE = [
        '45',
        '22',
        '28',
        '23',
        '57'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::LASTNAME as $key => $contactLastName) {
            $contact = new Contact();
            $contact->setLastname($contactLastName);
            $contact->setFirstname(self::FIRSTNAME[$key]);
            $contact->setMail(self::MAIL[$key]);
            $contact->setAdress(self::ADRESS[$key]);
            $contact->setPhone(self::PHONE[$key]);
            $contact->setAge(self::AGE[$key]);
            $manager->persist($contact);
        }
        $manager->flush();
    }
}
