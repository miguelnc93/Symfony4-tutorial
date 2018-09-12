<?php
/**
 * Created by PhpStorm.
 * User: UserName
 * Date: 8/29/2018
 * Time: 1:38 PM
 */

namespace App\Twig;


use App\Entity\LikeNotification;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class AppExtension
 * @package App\Twig
 */
class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'priceFilter']),
            new TwigFilter('test', [$this, 'testFilter']),
           // new TwigFilter('array', [$this, 'arrayFilter']),
        ];
    }

    public function priceFilter($number)
    {
        return '$'.number_format($number, 3,'.',',').' kW';
    }

    public function testFilter($number)
    {
        return 'es una prueba desde testFilter'.$number;
    }

//    public function arrayFilter($var)
//    {
//        return is_array($var);
//    }



    public function getTests()
    {
        return [
          new \Twig_SimpleTest(
              'like',
              function ($obj) { return $obj instanceof LikeNotification;}
              )
        ];
    }
}