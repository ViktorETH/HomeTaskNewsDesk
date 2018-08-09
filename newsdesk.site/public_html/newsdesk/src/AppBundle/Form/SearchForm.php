<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 11.06.18
 * Time: 15:25
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType::class)
            ->add('submit', SubmitType::class);
    }
}