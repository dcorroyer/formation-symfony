<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $encoder;

    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        $admin = new User();
        $hash  = $this->encoder->encodePassword($admin, "password");

        $admin->setEmail("admin@admin.fr")
            ->setPassword($hash)
            ->setFullName("Admin")
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");

            $user->setEmail("user$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash);

            $manager->persist($user);
        }

        for ($c = 0; $c < 3; $c++) {
            $category = new Category();

            $category
                ->setName($faker->department())
                ->setSlug(strtolower($this->slugger->slug($category->getName())))
            ;

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product();

                $product
                    ->setName($faker->productName())
                    ->setPrice($faker->price(1000, 25000))
                    ->setSlug(strtolower($this->slugger->slug($product->getName())))
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true))
                    ->setCategory($category)
                ;

                $manager->persist($product);
            }
        }

//        for ($i = 0; $i < 100; $i++) {
//            $product = new Product();
//            $product
//                ->setName("Produit n.$i")
//                ->setPrice(mt_rand(1000, 25000))
//                ->setSlug("produit-n-$i")
//            ;
//            $product
//                ->setName($faker->sentence())
//                ->setPrice(mt_rand(1000, 25000))
//                ->setSlug($faker->slug())
//            ;
//            $product
//                ->setName($faker->productName())
//                ->setPrice($faker->price(1000, 25000))
//                ->setSlug(strtolower($this->slugger->slug($product->getName())))
//            ;
//
//            $manager->persist($product);
//        }

        $manager->flush();
    }
}
