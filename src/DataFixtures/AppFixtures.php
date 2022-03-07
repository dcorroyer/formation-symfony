<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
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

        $users = [];

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password");

            $user->setEmail("user$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash);

            $users[] = $user;

            $manager->persist($user);
        }

        $products = [];

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

                $products[] = $product;

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

        for ($p = 0; $p < mt_rand(20, 40); $p++) {
            $purchase = new Purchase();

            $purchase->setFullName($faker->name())
                ->setAddress($faker->streetAddress())
                ->setPostalCode($faker->postcode())
                ->setCity($faker->city())
                ->setUser($faker->randomElement($users))
                ->setTotal(mt_rand(2000, 30000))
                ->setPurchasedAt($faker->dateTimeBetween('-6 months'));

            $selectedProducts = $faker->randomElements($products, mt_rand(3, 5));

            foreach ($selectedProducts as $product) {
//                $purchase->addProduct($product);
                $purchaseItem = new PurchaseItem();

                $purchaseItem->setProduct($product)
                    ->setQuantity(mt_rand(1, 3))
                    ->setProductName($product->getName())
                    ->setProductPrice($product->getPrice())
                    ->setTotal($purchaseItem->getProductPrice() * $purchaseItem->getQuantity())
                    ->setPurchase($purchase)
                ;

                $manager->persist($purchaseItem);
            }

            if ($faker->boolean(90)) {
                $purchase->setStatus(Purchase::STATUS_PAID);
            }

            $manager->persist($purchase);
        }

        $manager->flush();
    }
}
